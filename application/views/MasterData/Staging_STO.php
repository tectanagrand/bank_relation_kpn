<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="">Add STO</a></li>
    <li class="breadcrumb-item active">Add STO</li>
</ol>
<h1 class="page-header">Add STO</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Add STO</h4>
    </div>
    <div class="panel-body">
            <div id="add">
                <div class="row form-group">
                    <div class="col-md-3">
                        <select name="company" id="fcompany" class="form-control">
                            <option value="">Choose</option>
                            <?php 
                            foreach ($DtCompany as $values) {
                                echo '<option value="' . $values->EXTSYSCOMPANYCODE . '">' . $values->COMPANYNAME .'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <!-- <div class="col-md-3">
                        <select name="businesunit" id="fbusinessunit" class="form-control">
                            <option value="">Choose</option>
                            <?php  
                            // foreach ($DtBu as $values) {
                            //     echo '<option value="' . $values->EXTSYSBUSINESSUNITCODE . '">' . $values->EXTSYSBUSINESSUNITCODE .'</option>';
                            // }
                            //?>
                        </select>
                    </div> -->
                    <div class="col-md-4 pull-right">
                        <div class="input-group">
                            <input type="text" id="search_by" name="search" class="form-control" placeholder="Cari.." >
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="insertDtDept" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtDept_info">
                        <thead>
                            <tr role="row">
                                <!-- <th class="text-center sorting_asc" style="width: 30px;">No</th> -->
                                <th class="text-center sorting_disabled"><input type="checkbox" id="pil"></th>
                                <th class="text-center sorting">ExtSys</th>
                                <th class="text-center sorting">Company</th>
                                <th class="text-center sorting">Dept</th>
                                <th class="text-center sorting">Docnumber</th>
                                <th class="text-center sorting">Docdate</th>
                                <th class="text-center sorting">Vendor</th>
                                <th class="text-center sorting">Amount VAT</th>
                                <th class="text-center sorting">Total</th>
                                <th class="text-center sorting">Currency</th>
                                <th class="text-center sorting">PPN</th>
                                <!-- <th class="text-center sorting">Status</th> -->
                                <th class="text-center sorting_disabled" aria-label="Action"></th>
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
    var BUSINESSUNIT = '',YEAR = '',DOCNUMBER= '',COMPANY = '';
    var dtPurch = [];
    var saveAll = [];
    $(document).ready(function () {
        // LoadDataTable();
            
            // var selectDept = <?php ($DtDepartment) ?>;

        SetDataKosong();
        function LoadDataTable(){   
            if (!$.fn.DataTable.isDataTable('#insertDtDept')) {
                $('#insertDtDept').DataTable({
                        "processing": true,
                        "serverSide":true,
                        "ajax": {
                            "url": "<?php echo site_url('Staging/ShowStagingDataSTO') ?>",
                            "datatype": "JSON",
                            "type": "POST",
                            "data": function (d) {
                                d.COMPANY  = COMPANY;
                                // d.BUSINESSUNIT    = BUSINESSUNIT;
                                // d.YEAR = YEAR;
                                // d.DOCNUMBER = DOCNUMBER;
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
                        {
                            "data": null,
                            "className": "text-center align-middle",
                            "orderable": false,
                            render: function (data, type, row, meta) {
                                var html = '<input type="checkbox" name="pils" class="pils">';
                                return html;
                            }
                        },
                        {"data": "EXTSYSBUSINESSUNITCODE","className": "text-center"},
                        {"data": "COMPANYNAME","className": "text-center"},
                        {"data": "DEPARTMENT","className": "text-center"},
                        {"data": "DOCNUMBER","className": "text-center"},
                        {"data": "DOCDATE","className": "text-center"},
                        {"data": "VENDORNAME","className": "text-center"},
                        {"data": "AMOUNT_INCLUDE_VAT","className": "text-center",render: $.fn.dataTable.render.number(',', '.', 2)},
                        {"data": "TOTAL_BAYAR","className": "text-center",render: $.fn.dataTable.render.number(',', '.', 2)},
                        {"data": "CURRENCY","className": "text-center"},
                        {"data": "AMOUNT_PPN","className": "text-center",render: $.fn.dataTable.render.number(',', '.', 2)},
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
                table2.on('change', '.pils', function () {
                    $tr = $(this).closest('tr');
                    var data = table2.row($tr).data();
                    if (this.checked) {
                        dtPurch.push(data);
                        // console.log(dtPurch);
                        // console.log(data);
                    } else {
                        dtPurch.splice(dtPurch,1);
                        // console.log(dtPurch);
                    }
                });
                $("#insertDtDept_filter").remove();
                $("#search_by").on({
                    'keyup': function () {
                        table2.search(this.value, true, false, true).draw();
                    }
                });
            }else{
                table2.ajax.reload();
            }
        }

        $('#fcompany').on({
            'change': function() {

                COMPANY = this.value;

                LoadDataTable();
            }
        });

        // $('#fbusinessunit').on({
        //     'change': function() {

        //         BUSINESSUNIT = this.value;

        //         LoadDataTable();
        //     }
        // });
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
            $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Staging/saveStagingSTO'); ?>",
                    data: {
                       dtPurch: dtPurch
                       // ACTION: ACTION
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
</script>