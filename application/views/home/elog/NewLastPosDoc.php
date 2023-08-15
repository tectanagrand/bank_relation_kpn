<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<link href="./assets/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css" rel="stylesheet" />
<script src="./assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="./assets/plugins/DataTables/extensions/Buttons/js/jszip.min.js"></script>
<script src="./assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
<script src="./assets/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<style>
    .filter-option-inner-inner{
        color: black;
    }
</style>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">E-Log</li>
</ol>
<h1 class="page-header">E-Log Last Doc</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">E-Log Last Doc</h4>
    </div>
    <div class="panel-body">
        <?php if (empty($_GET)) { ?>
            <div class="row mb-2">
                <div>
                    <button id="clearSelect" type="button" class="btn btn-success btn-sm"><span> Clear Select</span></button>
                </div>
                <div class="col-md-3">
                    <select class="selectpicker form-control mkreadonly" name="COMPANY" id="COMPANY" name="COMPANY[]" id="COMPANY" multiple data-live-search="true" data-style="">
                                <option value="0">All Company</option>
                                <?php
                                foreach ($DtCompany as $values) {
                                    echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                }
                                ?>
                            </select>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="PERIOD" id="PERIOD" autocomplete="off">
                </div>
                <div>
                    <button type="button" class="btn btn-info mr-2" style="padding: 3px 10px;" onclick="searchCashflow()">Show</button>
                </div>
                <div>
                    <button id="btnExport" type="button" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i><span> Export</span></button>
                </div>
                <div class="col-md-4 pull-right">
                    <div class="input-group">
                        <input type="text" id="search" name="search" class="form-control" placeholder="Cari.." >
                    </div>
                </div>
            </div>
            <div class="row m-0 table-responsive">
                <table id="DtElog" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" width="100%" aria-describedby="DtElog_info" style="width: 100%;">
                    <thead>
                        <tr role="row">
                            <!-- <th class="text-center_asc" style="width: 30px;">No</th> -->
                            <th class="text-center">Company</th>
                            <!-- <th class="text-center">Receipt Doc</th> -->
                            <th class="text-center">Invoice Code</th>
                            <th class="text-center">No PO</th>
                            <th class="text-center">Vendor</th>
                            <th class="text-center">Currency</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Voucherno</th>
                            <th class="text-center">Dpp</th>
                            <th class="text-center">Pph</th>
                            <th class="text-center">Ppn</th>
                            <th class="text-center">Net</th>
                            <th class="text-center">Remarks</th>
                            <th class="text-center">Dept</th>
                            <th class="text-center">Send To</th>
                            <th class="text-center">Updated By</th>
                            <th class="text-center">Date</th>
                            <!-- <th class="text-center">Status</th> -->
                            <!-- <th class="text-center_disabled" aria-label="Action">#</th> -->
                        </tr>
                    </thead>
                </table>
            </div>
        <?php } ?>
    </div>
    <div class="modal fade" id="MExport" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Export Data</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <form id="FExport" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="ECOMPANY">Company</label>
                                    <select class="form-control mkreadonly" name="ECOMPANY" id="ECOMPANY">
                                        <option value="0" selected>All Company</option>
                                        <?php
                                        foreach ($DtCompany as $values) {
                                            echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <?php
                                    $CDepartment = '';
                                    foreach ($DtDepartment as $values) {
                                        $CDepartment .= '<option value="' . $values->DEPARTMENT . '">' . $values->DEPARTEMENTNAME . '</option>';
                                    }
                                ?>
                                <label>Dept</label>
                                <select class="form-control w-100" name="DEPT" id="DEPT">
                                    <option value="0" selected>All Department</option>
                                    <?php echo $CDepartment; ?>
                                </select>
                            </div>
                            <div class="col-md-12 mt-4">
                                <?php
                                    $CDepartment = '';
                                    foreach ($DtDepartment as $values) {
                                        $CDepartment .= '<option value="' . $values->DEPARTMENT . '">' . $values->DEPARTEMENTNAME . '</option>';
                                    }
                                ?>
                                <label>Send To</label>
                                <select class="form-control w-100" name="SEND_TO" id="SEND_TO">
                                    <option value="0" selected>All Department</option>
                                    <?php echo $CDepartment; ?>
                                </select>
                            </div>
                            <div class="col-md-12 mt-4">
                                <label>Vendor</label>
                                <select class="form-control w-100 EVENDOR" id="EVENDOR" name="EVENDOR" style="width: 100%">
                                    <option disabled selected>Select</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="form-group">
                                    <label for="EPERIOD">Period</label>
                                    <input type="text" class="form-control" name="EPERIOD" id="EPERIOD" placeholder="MM YYYY" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="checkDate">
                                    <label class="form-check-label" for="checkDate">
                                    By Date
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="EPERIOD">From Date</label>
                                    <input type="text" class="form-control" name="FROMDATE" id="FROMDATE" placeholder="MM/DD/YYYY" autocomplete="off" disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="EPERIOD">To Date</label>
                                    <input type="text" class="form-control" name="TODATE" id="TODATE" placeholder="MM/DD/YYYY" autocomplete="off" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="Export('EXCEL')"><i class="fa fa-file-excel"></i> Excel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal view  -->
    <!-- <div class="modal fade" id="MView">
        <div class="modal-dialog" style="max-width: 95%  !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">View Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="FView" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="fcname">Invoice Code *</label>
                                <input type="text" class="form-control VINVOICE_CODE" id="VINVOICE_CODE" name="INVOICE_CODE">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="fcname">NO PO *</label>
                                <input type="text" class="form-control NO_PO" id="VNO_PO" name="VNO_PO">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="fcname">Vendor *</label>
                                <select class="form-control vendor" id="VVENDOR" name="VENDOR">
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="description"> Amount *</label>
                                <input type="text" data-type='currency' name="VAMOUNT" id="VAMOUNT" class="form-control">
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div> -->
    <!-- end modal -->
    <?php if (!empty($_GET)) { ?>
        <div class="panel-footer text-left">
            <button type="button" id="btnSave" onclick="SaveMaster()" class="btn btn-primary btn-sm m-l-5">Save</button>
            <button type="button" class="btn btn-warning btn-sm m-l-5" onclick="Cancel()">Cancel</button>
        </div>
    <?php } ?>
</div>

<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var DEPT = "<?php echo $SESSION->DEPARTMENT; ?>";
    // var SYEAR;
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
    var table, ACTION, ID;
    var COMPANY = 0;
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    $(document).ready(function () {

        var SYEAR = new Date();
        SYEAR.getFullYear();

        $('#PERIOD').datepicker({
            "autoclose": true,
            "todayHighlight": true,
            "viewMode": "years",
            "minViewMode": "years",
            "format": "yyyy"
            // "setDate": YEAR
        });

         $("#PERIOD").datepicker().datepicker("setDate", SYEAR);
        
        $('#btnExport').on({
        'click': function() {
                // if(USERNAME != 'ADMIN'){
                //     alert('Maintenance, Comeback Later.');    
                // }else{
                    $('#FExport').parsley().reset();
                    $("#MExport").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                // }
            }
        });

        $('[data-dismiss=modal]').on('click', function (e) {
                $("#EVENDOR").val([]).trigger("change");
        });

    });

    $(".selectpicker").selectpicker({
            noneSelectedText : 'Please Select Company' // by this default 'Nothing selected' -->will change to Please Select
        });
    
    $('#clearSelect').on({
        'click': function() {
        $(".selectpicker").selectpicker('deselectAll');
        }
    });


        var searchCashflow = function () {
            if ($('#COMPANY').val() == '' ||  $('#COMPANY').val() == null) {
                alert("cannot be empty!")
                return false
            } else {
                // COMPANY = $('#COMPANY').val();
                loadData()
            }
        }
        
        function loadData(){
            if (!$.fn.DataTable.isDataTable('#DtElog')) {
                $('#DtElog').DataTable({
                    dom: 'Bfrtip',
                    "order": [[ 10, "desc" ]],
                    "buttons": [{
                            extend: "excel",
                            title: 'Data Last Position Elog',
                            className: "btn-xs btn-green mb-2",
                            text: 'Export To Excel'
                        }],
                    "pageLength": 100,
                    "processing": true,
                    "serverSide":true,
                    "deferRender":true,
                    "ajax": {
                        "url": "<?php echo site_url('Elog/ShowDataLastDocNew') ?>",
                        "datatype": "JSON",
                        "type": "POST",
                        "data": function (d) {
                            d.COMPANY = $('#COMPANY').val();
                            d.YEAR = moment($('#PERIOD').val()).format('YYYY');
                        },
                        "dataSrc": function (ext) {
                            if (ext.status == 200) {
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
                            $('#loader').addClass('show');
                        },
                        "complete": function() {
                            $('#loader').removeClass('show');
                        }
                    },
                    "columns": [
                    // {
                    //     "data": null,
                    //     "className": "text-center",
                    //     render: function (data, type, row, meta) {
                    //         return meta.row + 1;
                    //     }
                    // },
                    {"data": "COMPANYNAME","orderable": false},
                    // {"data": "NO_RECEIPT_DOC"},
                    {"data": "INVOICE_CODE","orderable": false},
                    {"data": "NO_PO","orderable": false},
                    {"data": "VENDORNAME","orderable": false},
                    {"data": "CURRENCY","orderable": false},
                    {
                        "data": "AMOUNT",
                        "className": "text-right",
                        "orderable": false,
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {"data": "VOUCHERNO"},
                    {
                        "data": "DPP",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNT_PPN",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNT_PPH",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNT_NET",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {"data": "REMARK","orderable": false},
                    {"data": "DEPT","orderable": false},
                    {"data": "SEND_TO","orderable": false},
                    {"data": "UPDATED_BY","orderable": false},
                    {"data": "DATE_RECEIPT"},
                    // {
                    //     "data": null,
                    //     "className": "text-center",
                    //     "orderable": false,
                    //     render: function (data, type, row, meta) {
                    //         var html = '';
                    //         html += '<button class="btn btn-info btn-icon btn-circle btn-sm mr-2 view" title="view data" data-id="'+data.NO_RECEIPT_DOC+'"><i class="fa fa-eye"></i></button>';
                    //         // if (EDITS == 1) {
                    //         //     html += '<button class="btn btn-success btn-icon btn-circle btn-sm mr-2 edit" title="Edit" style="margin-right: 5px;">\n\
                    //         //     <i class="fa fa-edit" aria-hidden="true"></i>\n\
                    //         //     </button>';
                    //         // }
                    //         // if (EDITS == 1) {
                    //         //     html += '<button class="btn btn-info btn-icon btn-circle btn-sm mr-2 view" title="view data" data-id="'+data.UUID+'"><i class="fa fa-eye"></i></button>';
                    //         // }
                    //         // if (DELETES == 1) {
                    //         //     html += '<button class="btn btn-danger btn-icon btn-circle btn-sm ml-4 delete" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                    //         // }
                    //         return html;
                    //     }
                    // }
                    ],
                    responsive: {
                        details: {
                            renderer: function (api, rowIdx, columns) {
                                var data = $.map(columns, function (col, i) {
                                    return col.hidden ?
                                    '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                                    '<td>' + col.title + '</td> ' +
                                    '<td>:</td> ' +
                                    '<td>' + col.data + '</td>' +
                                    '</tr>' :
                                    '';
                                }).join('');
                                return data ? $('<table/>').append(data) : false;
                            }
                        }
                    },
                    "bFilter": true,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bInfo": true,
                    "columnDefs": [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: 1
                    },
                    {
                        responsivePriority: 3,
                        targets: -1
                    }
                    ]
                });
                $('#DtElog thead th').addClass('text-center');
                table = $('#DtElog').DataTable();
                table.on('click', '.edit', function() {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    window.location.href = window.location.href + '?type=edit&UUID=' + data.UUID;
                });
                table.on('click', '.view', function () {
                    var UUID = $(this).attr('data-id');
                    $('#loader').addClass('show');
                    $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('Elog/viewDetail'); ?>",
                            data: {
                                ID: UUID},
                            success: function (response) {
                                $('#loader').removeClass('show');
                                $('#VCOMPANYNAME').val(response[0].COMPANYNAME);
                                $('#VDEPARTMENT').val(response[0].DEPARTMENT);
                                $('#VDOCNUMBER').val(response[0].DOCNUMBER);
                                $('#VDOCDATE').val(response[0].DOCDATE);
                                $('#VVENDOR').val(response[0].VENDORNAME);
                                $("#MView").modal({
                                    backdrop: 'static',
                                    keyboard: false
                                });
                            },
                            error: function (e) {
                                $('#loader').removeClass('show');
                                alert('Error Get data !!');
                            }
                        });
                });
                table.on('click', '.export', function () {
                    var DOCNUM = $(this).attr('data-docnumber');
                    var COMP = $(this).attr('data-company');
                    var url = "<?php echo site_url('Elog/exportTransaction'); ?>?COMPANY=PARAM1&DOCNUMBER=PARAM2";
                    url = url.replace("PARAM1", COMP);
                    url = url.replace("PARAM2", DOCNUM);
                    window.open(url, '_blank');
                });
                $("#DtElog_filter").remove();

                // $("#search").on({
                //     'keyup': function () {
                //         table.search(this.value, true, false, true).draw();
                //     }
                // });

                $('#search').keyup(delay(function (e) {
                  table.search(this.value, true, false, true).draw();
                }, 500));

                function delay(fn, ms) {
                  let timer = 0
                  return function(...args) {
                    clearTimeout(timer)
                    timer = setTimeout(fn.bind(this, ...args), ms || 0)
                  }
                }
            }
            else{
                table.ajax.reload();
            }
        }

    $('#checkDate').change(function() {
        if ($('#checkDate').is(':checked') == true){
              $('#EPERIOD').prop('disabled', true);
              $('#FROMDATE').prop('disabled', false);
              $('#TODATE').prop('disabled', false);

                $('#FROMDATE').datepicker({
                    "autoclose": true,
                    "todayHighlight": true,
                    "setDate": new Date()
                });

                $('#TODATE').datepicker({
                    "autoclose": true,
                    "todayHighlight": true,
                    "setDate": new Date()
                });
              // console.log('checked');
           } else {
            $('#EPERIOD').prop('disabled', false);
            $('#FROMDATE').prop('disabled', true);
            $('#TODATE').prop('disabled', true);
           }
    });

    $('#EPERIOD').datepicker({
        "autoclose": true,
        // "todayHighlight": true,
        "viewMode": "months",
        "minViewMode": "months",
        "format": "M yyyy"
    });

    $("#EVENDOR").select2({
            // theme: 'bootstrap4',
            width: 'resolve',
            dropdownParent: $('#MExport .modal-content'),
            ajax: {
                url: "<?php echo site_url('Leasing/getVendor') ?>",
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

    var Export = function(type) {
        if ($('#FExport').parsley().validate()) {
            var EVENDOR  = $('#EVENDOR').val();

            if($('#checkDate').is(':checked') == true){
                var url = "<?php echo site_url('Elog/LastDocExport'); ?>?COMPANY=PARAM2&FROMDATE=PARAM3&TODATE=PARAM4&SEND_TO=PARAM6&DEPT=PARAM7&VENDOR=PARAM9";
                url = url.replace("PARAM1", type);
                if ($("#ECOMPANY").val() == "" || $("#ECOMPANY").val() == null || $("#ECOMPANY").val() == undefined) {
                    alert('Cant Null');
                } else {
                    url = url.replace("PARAM2", $("#ECOMPANY").val());
                }

                if(EVENDOR == null || EVENDOR == 'null'){
                    EVENDOR = 0;
                }
                var FROMDATE = $('#FROMDATE').val();
                var TODATE   = $('#TODATE').val();
                url = url.replace("PARAM6", $("#SEND_TO").val());
                url = url.replace("PARAM7", $("#DEPT").val());
                url = url.replace("PARAM3", FROMDATE);
                url = url.replace("PARAM4", TODATE);
                url = url.replace("PARAM9", EVENDOR);
            }else{
                var url = "<?php echo site_url('Elog/LastDocExport'); ?>?COMPANY=PARAM2&MONTH=PARAM4&YEAR=PARAM5&SEND_TO=PARAM6&DEPT=PARAM7&VENDOR=PARAM9";
                url = url.replace("PARAM1", type);
                if ($("#ECOMPANY").val() == "" || $("#ECOMPANY").val() == null || $("#ECOMPANY").val() == undefined) {
                    alert('Cant Null');
                } else {
                    url = url.replace("PARAM2", $("#ECOMPANY").val());
                }
                if(EVENDOR == null || EVENDOR == 'null'){
                    EVENDOR = 0;
                }
                MONTH = ListBulan.indexOf($('#EPERIOD').val().substr(0, 3)) + 1;
                YEAR = $('#EPERIOD').val().substr(4, 4);
                url = url.replace("PARAM4", MONTH);
                url = url.replace("PARAM5", YEAR);
                url = url.replace("PARAM6", $("#SEND_TO").val());
                url = url.replace("PARAM7", $("#DEPT").val());
                url = url.replace("PARAM9", EVENDOR);
            }
            
            window.open(url, '_blank');
        }
    }

    var Add = function () {
        window.location.href = window.location.href + '?type=add';
    };
    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }

</script>
<!-- formatting -->
<script type="text/javascript">
    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            formatCurrency($(this), "blur");
        }
    });
    function formatCurrency(input, blur) {
        var input_val = input.val();
        if (input_val === "") {
            return;
        }
        var original_len = input_val.length;
        var caret_pos = input.prop("selectionStart");
        if (input_val.indexOf(".") >= 0) {
            var decimal_pos = input_val.indexOf(".");
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring((decimal_pos + 1));
            left_side = formatNumber(left_side);
            //            right_side = formatNumber(right_side);
            right_side = formatDesimal(right_side);
            //            if (blur === "blur") {
            //                right_side += "00";
            //            }
            //            right_side = right_side.substring(0, 2);
            input_val = left_side + "." + right_side;
        } else {
            input_val = formatNumber(input_val);
            input_val = input_val;
            if (blur === "blur") {
                input_val += ".00";
            }
        }
        input.val(input_val);
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }

    function formatDesimal(n) {
        return n.replace(/[^0-9.-]+/g, "");
    }

    function fCurrency(n) {
        if (n.indexOf(".") >= 0) {
            var decimal_pos = n.indexOf(".");
            var left_side = n.substring(0, decimal_pos);
            var right_side = n.substring(decimal_pos);
            left_side = formatNumber(left_side);
            right_side = formatNumber(right_side);
            right_side += "00";
            right_side = right_side.substring(0, 2);
            n = left_side + "." + right_side;
            return n;
        } else {
            n = formatNumber(n);
            n += ".00";
            return n;
        }
    }
</script>