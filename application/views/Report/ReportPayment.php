<link href="./assets/css/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<link href="https://cdn.datatables.net/rowgroup/1.1.1/css/rowGroup.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/rowgroup/1.1.1/js/dataTables.rowGroup.min.js"></script>
<style type="text/css">
    .dt-button{
        transition-duration: 0.4s;
        background-color: #76b6d6;
    }
    .dt-button:hover {
            background-color: #138496;
            color: #138496;
    }
</style>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Payment</li>
</ol>
<h1 class="page-header">Payment</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Payment</h4>
    </div>
    <div class="panel-body">
            <div class="row">
                <div class="col-md-9">
                    <form id="FAddEditForm" class="form-inline" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                        <div class="row">
                            <div class="form-group col-5">
                                <select class="form-control w-100" name="DEPARTMENT" id="DEPARTMENT">
                                    <?php if($this->session->userdata('DEPARTMENT') != "COMMERCIAL"){ ?>
                                        <option value="" selected>All Department</option>
                                        <?php
                                        foreach ($departement as $values) {
                                            echo '<option value=' . $values->DEPARTMENT . '>' . $values->DEPARTEMENTNAME . '</option>';
                                        } 
                                        }else { ?>
                                            <option value="COMMERCIAL">COMMERCIAL</option>
                                        <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <!-- <label for="FROMDATE">From Date *</label> -->
                                <div class="input-group date" id="FROMDATE">
                                    <input type="text" class="form-control mkreadonly roleaccess" id="FROMDATE2" placeholder="From Date"/>
                                    <div class="input-group-addon input-group-append">
                                        <div class="input-group-text">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-3">
                                <!-- <label for="TODATE">To Date *</label> -->
                                <div class="input-group date" id="TODATE">
                                    <input type="text" class="form-control mkreadonly roleaccess" name="TODATE" id="TODATE2" placeholder="To Date"/>
                                    <div class="input-group-addon input-group-append">
                                        <div class="input-group-text">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-1">
                                <button type="button" class="btn btn-info" style="padding: 3px 10px;" onclick="searchCashflow()">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-2 offset-md-1">
                    <!-- <label for="" class="text-light">Search</label> -->
                    <input type="text" id="searchInvoice" class="form-control" placeholder="Cari..">
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <div class="form-group col-5 listdataAjaxBank" style="display: none">
                        <ul id="listBANK" class="roleaccess listDataBANK"></ul>
                    </div>
                </div>
            </div>
            <div class="row ml-0 mr-0 mb-0 mt-2 table-responsive">
                <table id="DtReportPayment" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                    <thead>
                        <tr role="row">
                            <th class="text-nowrap text-center no-sort align-middle">Department</th>
                            <th class="text-nowrap text-center no-sort align-middle">Company</th>
                            <th class="text-nowrap text-center no-sort align-middle">Business Unit</th>
                            <th class="text-nowrap text-center no-sort align-middle">Voucher</th>
                            <th class="text-nowrap text-center no-sort align-middle">Vendor</th>
                            <th class="text-nowrap text-center no-sort align-middle">Doc Number</th>
                            <th class="text-nowrap text-center no-sort align-middle">Invoice Vendor</th>
                            <th class="text-nowrap text-center no-sort align-middle">AP Invoice</th>
                            <th class="text-nowrap text-center no-sort align-middle">Remarks</th>
                            <th class="text-nowrap text-center no-sort align-middle">Currency</th>
                            <th class="text-nowrap text-center no-sort align-middle">Amount</th>
                            <th class="text-nowrap text-center no-sort align-middle">Amount Paid</th>
                            <th class="text-nowrap text-center no-sort align-middle">Payment Date</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
    </div>
</div>

<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<script src="./assets/js/datetime/bootstrap-datetimepicker.min.js"></script>

<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    if (dd <= 10) {
        dd = '0' + dd;
    }
    if (mm <= 10) {
        mm = '0' + mm;
    }
    
    var tgl = mm + '/' + dd + '/' + today.getFullYear();

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
    var table2;

    $(document).ready(function () {
        LoadDataTable();

        $('#FROMDATE').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "MM/DD/YYYY",
        });

        $('#TODATE').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "MM/DD/YYYY",
        });
    });

    $(document).ready(function(e){
        $('button.dt-button').addClass('btn');
        $('button.dt-button').addClass('btn-primary');
    });

    function LoadDataTable() {
        if (!$.fn.DataTable.isDataTable('#DtReportPayment')) {
            $('#DtReportPayment').DataTable({
                "processing": true,
                "rowGroup": {
                    startRender: null,
                    endRender: function(rows, group) {
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };
                        vAMOUNT = 0;
                        vPAYAMOUNT = 0;
                       
                        var vAMOUNT = rows.data().pluck('AMOUNT').reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        var vPAYAMOUNT = rows.data().pluck('PAYAMOUNT').reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        
                        var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                        return $('<tr/>')
                            .append('<td class="text-right" colspan="9">Total</td>')
                            .append('<td class="text-right">' + numFormat(vAMOUNT) + '</td>')
                            .append('<td class="text-right">' + numFormat(vPAYAMOUNT) + '</td>')
                            .append('<td></td>');
                    },
                    // dataSrc: ["AMOUNT","PAYAMOUNT"]
                },
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;
         
                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                            
                    amount = api
                            .column( 10 ,{ page: 'current'} )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                    basic_conv = api
                            .column( 11 ,{ page: 'current'} )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                    total9 = api
                            .column( 10, { search:'applied' } )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                    total10 = api
                            .column( 11,{ search:'applied' } )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                            
                    // numFormat(amount) + ' | '+ 
                    // numFormat(basic_conv) + ' | '+ 
                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                    $( api.column( 10 ).footer() ).html(numFormat(total9) + ' Total All Pages');
                    $( api.column( 11 ).footer() ).html(numFormat(total10) + ' Total All Pages ');
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Report Payment',
                        filename: 'Report_Payment_' + tgl
                    }, 
                ],
                "ajax": {
                    "url": "<?php echo site_url('otherReport/ReportPayment') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function (d) {
                        d.DEPARTMENT = $('#DEPARTMENT').val();
                        d.DATEFROM = moment($('#FROMDATE2').val()).format('YYYYMMDD');
                        d.DATETO = moment($('#TODATE2').val()).format('YYYYMMDD');
                    },
                    "dataSrc": function (ext) {
                        if (ext.status == 200) {
                            return ext.result.data;
                        } else if (ext.status == 504) {
                            alert(ext.result.data);
                            location.reload();
                            return [];
                        } else {
                            // console.info(ext.result.data);
                            return [];
                        }
                    }
                },
                "columnDefs": [
                    { targets: 0, visible: false},
                ],
                "columns": [
                    {"data": "DEPARTMENT"},
                    {"data": "COMPANYNAME"},
                    {"data": "BUSINESSUNITNAME"},
                    {
                        "data": "VOUCHERNO",
                        "orderable": false
                    },
                    {"data": "SUPPLIERNAME"},
                    {"data": "DOCNUMBER"},
                    {"data": "INVOICEVENDORNO"},
                    {"data": "APINV"},
                    {"data": "REMARK"},
                    {"data": "CURRENCY"},
                    {
                        "data": "AMOUNT",
                        "orderable": false,
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data":"PAYAMOUNT",
                        "orderable": false,
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "DATERELEASE",
                        "orderable": false
                    }
                ],
            });

            $('#DtReportPayment_filter').remove()

            $('#searchInvoice').on( 'input', function () {
                table2.search( this.value ).draw();
            });

            $('#DtReportPayment thead th').addClass('text-center');
            table2 = $('#DtReportPayment').DataTable();

        } else {
            table2.ajax.reload();
        }
    }


    // function footerCallback(row, data, start, end, display) {
    //             var api = this.api(), data;
         
    //                 // Remove the formatting to get integer data for summation
    //                 var intVal = function ( i ) {
    //                     return typeof i === 'string' ?
    //                         i.replace(/[\$,]/g, '')*1 :
    //                         typeof i === 'number' ?
    //                             i : 0;
    //                 };

                            
    //                 amount = api
    //                         .column( 9 ,{ page: 'current'} )
    //                         .data()
    //                         .reduce( function (a, b) {
    //                             return intVal(a) + intVal(b);
    //                         }, 0 );

    //                 basic_conv = api
    //                         .column( 10 ,{ page: 'current'} )
    //                         .data()
    //                         .reduce( function (a, b) {
    //                             return intVal(a) + intVal(b);
    //                         }, 0 );

    //                 total9 = api
    //                         .column( 9 )
    //                         .data()
    //                         .reduce( function (a, b) {
    //                             return intVal(a) + intVal(b);
    //                         }, 0 );

    //                 total10 = api
    //                         .column( 10 )
    //                         .data()
    //                         .reduce( function (a, b) {
    //                             return intVal(a) + intVal(b);
    //                         }, 0 );

    //                 var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                            
    //                 $( api.column( 9 ).footer() ).html(numFormat(amount) + ' | '+ numFormat(total9) + ' Total All Pages');
    //                 $( api.column( 10 ).footer() ).html(numFormat(basic_conv) + ' | '+ numFormat(total10) + ' Total All Pages ');
    // }
    var searchCashflow = function () {
        if ($('#FROMDATE2').val() == '' || $('#TODATE2').val() == '' || $('#FROMDATE2').val() == null || $('#TODATE2').val() == null) {
            alert("'From Date' & 'To Date' can not be empty!")
            return false
        } else {
            LoadDataTable()
        }
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

    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

</script>