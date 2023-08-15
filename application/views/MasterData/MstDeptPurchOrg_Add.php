<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="">Add Department Purch Org</a></li>
    <li class="breadcrumb-item active">Add Department-Purch Org</li>
</ol>
<h1 class="page-header">Add Department-Purch Org</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Add Department-Purch Org</h4>
    </div>
    <div class="panel-body">
            <div id="add">
                <div class="col-md-4 pull-right">
                        <div class="input-group">
                            <input type="text" id="search_by" name="search" class="form-control" placeholder="Cari.." >
                        </div>
                    </div>
                <div class="table-responsive">
                    <table id="insertDtDept" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtDept_info">
                        <thead>
                            <tr role="row">
                                <!-- <th class="text-center sorting_asc" style="width: 30px;">No</th> -->
                                <th class="text-center sorting">COMPANY</th>
                                <th class="text-center sorting">PLANT</th>
                                <th class="text-center sorting">BUSINESS UNIT</th>
                                <th class="text-center sorting">PURCH ORG</th>
                                <th class="text-center sorting">DEPARTMENT</th>
                                <!-- <th class="text-center sorting">Status</th> -->
                                <!-- <th class="text-center sorting_disabled" aria-label="Action"></th> -->
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
    </div>
        <div class="panel-footer text-left">
            <button type="button" id="btnSave" onclick="Save()" class="btn btn-primary btn-sm m-l-5">Save</button>
            <button type="button" class="btn btn-warning btn-sm m-l-5" onclick="Cancel()">Cancel</button>
        </div>
</div>

<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };
    var table, ACTION,ID;
    var BUSINESSUNIT_SEARCH = $('#BUSINESSUNIT_SEARCH').val();
    var PURCHORG_SEARCH     = $('#PURCHORG_SEARCH').val();
    var dtPurch = [];
    var saveAll = [];
    $(document).ready(function () {
        // LoadDataTable();
            
            var selectDept = <?php echo json_encode($DtDepartment) ?>;
            var COMPANY = '';
            var PLANT   = '';
            var PURCHORG = '';
            SetDataKosong();
            if (!$.fn.DataTable.isDataTable('#insertDtDept')) {
                $('#insertDtDept').DataTable({
                        "processing": true,
                        "serverSide":true,
                        "ajax": {
                            "url": "<?php echo site_url('MstDeptPurch/ShowStagingData') ?>",
                            "datatype": "JSON",
                            "type": "POST",
                            "data": function (d) {
                                d.COMPANY  = COMPANY;
                                d.PLANT    = PLANT;
                                d.PURCHORG = PURCHORG;
                            },
                            "dataSrc": function (ext) {
                                if (ext.status == 200) {
                                    saveAll = ext.result.data.data;
                                    ext.draw = ext.result.data.draw;
                                    ext.recordsTotal = ext.result.data.recordsTotal;
                                    ext.recordsFiltered = ext.result.data.recordsFiltered;
                                    return ext.result.data.data;
                                } else if (ext.status == 504) {
                                    alert(ext.result.data);
                                    location.reload();
                                    return [];
                                } else {
                                    console.info(ext.result.data);
                                    return [];
                                }
                            },
                            "beforeSend": function() {
                                $("#loader").addClass('show');
                            },
                            "complete": function() {
                                $("#loader").removeClass('show');
                            }
                        },
                        "columns": [
                        {"data": "COMPANY","className": "text-center"},
                        {"data": "PLANT","className": "text-center"},
                        {"data": "BUSINESSUNIT_CSF","className": "text-center"},
                        {"data": "PURCHORG","className": "text-center"},
                        {
                            "data": null,
                            "className": "text-center selectDept",
                            "orderable": false,
                            render: function(data, type, row, meta) {
                            var html = '';
                            var mSel = '<select name="DEPARTMENT" id="DEPARTMENT" class="form-control DEPARTMENT"><option>Choose</option>';
                            for (i in selectDept) {
                              
                                mSel+= '<option value="'+ selectDept[i].DEPARTMENT +'">'+ selectDept[i].DEPARTEMENTNAME +'</option>';
                            }

                            return mSel;
                            }
                        }
                        ],
                        // responsive: {
                        //     details: {
                        //         renderer: function (api, rowIdx, columns) {
                        //             var data = $.map(columns, function (col, i) {
                        //                 return col.hidden ?
                        //                 '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                        //                 '<td>' + col.title + '</td> ' +
                        //                 '<td>:</td> ' +
                        //                 '<td>' + col.data + '</td>' +
                        //                 '</tr>' :
                        //                 '';
                        //             }).join('');
                        //             return data ? $('<table/>').append(data) : false;
                        //         }
                        //     }
                        // },
                        "bFilter": true,
                        "bPaginate": true,
                        "bLengthChange": false,
                        "bInfo": true
                });
                $('#insertDtDept thead th').addClass('text-center');
                table2 = $('#insertDtDept').DataTable();
                table2.on('change', '.DEPARTMENT', function () {
                    $tr = $(this).closest('tr');

                    var data = table2.row($tr).data();
                    
                    // if (this) {
                        if($(this).closest("tr").find("#DEPARTMENT").val() == 'Choose' || '' || null){
                            alert('Please Choose DEPARTMENT for row '+ data.PLANT + ' - ' + data.PURCHORG);
                        }else{
                            
                            // data.FLAG  = "1";
                            data.DEPARTMENT = $(this).closest("tr").find("#DEPARTMENT").val();
                            // alert(data.DEPARTMENT);
                            // data.REMARKS = $(this).closest("tr").find("#valREMARKS").val();    
                        }
                    // } else {
                    //     data.DEPARTMENT = "";
                    // }
                });
                $("#insertDtDept_filter").remove();
                $("#search_by").on({
                    'keyup': function () {
                        table2.search(this.value, true, false, true).draw();
                    }
                });
            }
            
        
    });

    var Add = function () {
        window.location.href = window.location.href + '?type=add';
    };
    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }
    function SetDataKosong() {
        $('.panel-title').text('Add Data');
        // $('#BUSINESSUNIT').val('');
        // $('#PURCHORG').val('');
        // ID = 0;
        ACTION = 'ADD';
    }

    var Save = function () {
        $('#loader').addClass('show');
        $.each(table2.data(), function (index, value) {
                //  var ins = $('#DtElog').find("tbody select").map(function() {

                //     // return $(this).find(":selected").val() // get selected text
                //     return $(this).val() // get selected value

                // }).get()

                //  alert(ins);
                
                if (value.DEPARTMENT == undefined || value.DEPARTMENT == null || value.DEPARTMENT == '') {
                } else {

                    if (value.DEPARTMENT != null || value.DEPARTMENT != '') {
                        dtPurch.push(value);
                    }
                }
            });
            $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('MstDeptPurch/saveStaging'); ?>",
                    data: {
                       dtPurch: dtPurch,
                       ACTION: ACTION
                    },
                    success: function (response) {
                        //$("#page-loader").addClass('d-none');
                        
                        if (response.status == 200) {
                            dtPurch = [];
                            alert(response.result.data);
                            table2.ajax.reload();
                        } else if (response.status == 504) {
                            alert(response.result.data);
                            table2.ajax.reload();
                        } else {
                            alert(response.result.data);
                            table2.ajax.reload();
                        }
                    },
                    error: function (e) {
                        //$("#page-loader").addClass('d-none');
                        // console.info(e);
                        alert('Data Save Failed !!');   
                        $('#loader').removeClass('show');
                    }
                });
            
            $(this).prop('disabled', false);
            $('#loader').removeClass('show');
    };


    $(".BUSINESSUNIT").select2({
                // theme: 'bootstrap4',
                ajax: {
                    url: "<?php echo site_url('MstDeptPurch/getBU') ?>",
                    dataType: 'json',
                    delay: 250,
                    type: 'GET',
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function(data, page) {
                        // parse the results into the format expected by Select2.
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data
                        return {
                            results: $.map(data, function (item) {
                              return {
                                id:item.ID,
                                text:item.TEXT
                            }
                        })
                        };
                    },
                    cache: true
                },
                escapeMarkup: function(markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                //templateResult: formatRepo,
                //templateSelection: formatRepoSelection
    });

    $(".PURCHORG").select2({
                // theme: 'bootstrap4',
                ajax: {
                    url: "<?php echo site_url('MstDeptPurch/getPurch') ?>",
                    dataType: 'json',
                    delay: 250,
                    type: 'GET',
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function(data, page) {
                        // parse the results into the format expected by Select2.
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data
                        return {
                            results: $.map(data, function (item) {
                              return {
                                id:item.ID,
                                text:item.TEXT
                            }
                        })
                        };
                    },
                    cache: true
                },
                escapeMarkup: function(markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                //templateResult: formatRepo,
                //templateSelection: formatRepoSelection
    });
</script>