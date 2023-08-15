<link rel="stylesheet" href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css">
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" type="text/css">

<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Weekly Report</li>
</ol>

<h1 class="page-header">Weekly Report</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript" data-click="panel-expand" class="btn btn-xs btn-icon btn-circle btn-default">
                <i class="fa fa-expand"></i>
            </a>
        </div>
        <h4 class="panel-title">Weekly Report</h4>
    </div>
    <div class="panel-body">
        <form id="ExportWeekly" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="COMPANY">Company</label>
                    <select name="COMPANY" id="COMPANY" class="form-control" >
                        <option value="" selected="selected">--- Select Company ---</option>
                        <?php
                            foreach($DtCompany as $value) {
                                echo "<option value='{$value->COMPANYCODE}'>{$value->COMPANYCODE} - {$value->COMPANYNAME}</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="BANK">Bank</label>
                    <select name="BANK" id="BANK"  class="form-control">
                        <option value="" selected="selected">--- Select Bank ---</option>
                        <?php 
                            foreach($DtBank as $value) {
                                echo "<option value='{$value->FCNAME}'>{$value->FCNAME}</option>" ;
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label for="PERIOD">Period</label>
                    <input type="date" id="PERIOD" class="form-control">
                </div>
                <div class="col-md-2 form-group">
                    <label for="CREDIT_TYPE">Credit Type</label>
                    <select name="CREDIT_TYPE" id="CREDIT_TYPE" class="form-control">
                        <option value="" selected="selected">--- Select Credit Type</option>
                        <option value="KMK">KMK</option>
                        <option value="KI">KI</option>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label for="RATE">Current Curs Rate</label>
                    <input type='text' id='CURS_RATE' data-type='currency' class='form-control' required >
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <button id="btnExport" class="btn btn-success btn-sm mt-4" type="button">
                        <i class="fa fa-file-excel"></i><span>Export</span>
                    </button>
                </div>
            </div>
        </form>
        <div class="row table-responsive">
            <table id="TableReport" class="table table-striped table-bordered" cellspacing="0" width="100%"></table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var date = moment().format('YYYY-MM-DD') ;
        $('#PERIOD').val(date);
        var renderer = $.fn.dataTable.render.number( ',', '.', 2 ).display;
    if(!$.fn.DataTable.isDataTable('#TableReport')) {
        $('#TableReport').DataTable({
            dom : 'Bfrtip',
            "deferRender" : true,
            "processing" : true,
            "ajax" : {
                "url" : "<?php echo site_url("ReportFacility/ShowSummaryReportWeekly")?>",
                "type" : "POST",
                "dataType" : "JSON",
                "data" : function(d) {
                    d.COMPANY = $('#COMPANY').val();
                    d.CREDIT_TYPE = $('#CREDIT_TYPE').val();
                    d.PERIOD = $('#PERIOD').val();
                    d.BANK = $('#BANK').val();
                },
                "dataSrc": function (ext) {
                    if (ext.status == 200) {
                        if(ext.result.size == 0) {
                            $('#btnExport').attr('disabled', true);
                        }
                        else {
                            $('#btnExport').attr('disabled', false);
                        }
                        return ext.result.data;
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
                },
            },
                "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading..n.</span> '
                },
                "columns" : 
                    [
                        {"title": "Company","data" : "COMPANY"},
                        {"title": "Bank",
                            render: function(data, type, row, meta) {
                                var bankname ;
                                if(row.BANK_SYD != null) {
                                    bankname = "SIND - " + row.BANK_SYD ;
                                }
                                else {
                                    bankname = row.BANK ;
                                }
                                return bankname ;
                            }
                        },
                        {"title" : "Facility Purpose",
                            render: function(data, type, row, meta) {
                                // var facName ;
                                // if(row.PURPOSE == null) {
                                //     facName = row.PK_NUMBER ;
                                // } else {
                                //     facName = row.PK_NUMBER +' : '+row.TRANCHE_NUMBER ;
                                // }
                                // return facName ;
                                return row.PURPOSE;
                            }
                        },
                        {"title" : "Credit Type",
                            render : function(data, type, row, meta) {
                                return row.SUB_WA ? row.SUB_WA : row.SUB_CREDIT_TYPE ; 
                            }
                        },
                        {"title" : "Currency",
                            render : function(data, type, row, meta) {
                                return row.CURRENCY ? row.CURRENCY : 'N/A' ; 
                            }
                        },
                        {"title" : "Limit",
                            render : function(data, type, row, meta) {
                                var limit ;
                                if(row.LIMIT_SYD != null) {
                                    limit = row.LIMIT_SYD ;
                                }
                                else {
                                    limit = row.LIMIT ;
                                }
                                return renderer(limit);
                            }
                        },
                        {"title": "Provisi","data" : "PROVISI"},
                        {"title": "Interest","data" : "INTEREST"},
                        {"title": "Tenor","data" : "PERIOD"},
                        {"title": "Due DateCompany","data" : "DUEDATE"},
                        {"title" : "First Payment Date","data" : "FIRSTPAY"},
                        {"title": "Past Due","data" : "PAST_DUE"},
                        {"title": "Outstanding","data" : "OUTSTANDING",
                            render : function(data, type, row, meta) {
                                var OUTSTANDING ;
                                if(row.OUTSTANDING_SYD != null) {
                                    OUTSTANDING = row.OUTSTANDING_SYD ;
                                }
                                else {
                                    OUTSTANDING = row.OUTSTANDING ;
                                }
                                return renderer(OUTSTANDING);
                        }}
                    ],
                    "bFilter": true,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bInfo": true,
        });
    }
    var tableReport = $('#TableReport').DataTable();
    var tableReport = $('#TableReport').DataTable();

    $('#COMPANY').on('change',function(){
        tableReport.ajax.reload();
    }) ;
    $('#BANK').on('change',function(){
        tableReport.ajax.reload();
    }) ;
    $('#PERIOD').on('change',function(){
        tableReport.ajax.reload();
    }) ;
    $('#CREDIT_TYPE').on('change',function(){
        tableReport.ajax.reload();
    }) ;
    $('#btnExport').on('click', function(){
        if($('#ExportWeekly').parsley().validate()){
            var url = "<?php echo site_url('ReportFacility/ExportReportWeekly'); ?>?COMPANY=PARAM1&BANK=PARAM2&CREDIT_TYPE=PARAM3&PERIOD=PARAM4&CURSRATE=PARAM5";
            url = url.replace("PARAM1", $('#COMPANY').val());
            url = url.replace("PARAM2", $('#BANK').val());
            url = url.replace("PARAM3", $('#CREDIT_TYPE').val());
            url = url.replace("PARAM4", $('#PERIOD').val());
            url = url.replace("PARAM5", $('#CURS_RATE').val());
            // url = url.replace("PARAM2", DOCNUM);
            window.open(url, '_blank');
        }
    }) ;

    });
    
    
</script>