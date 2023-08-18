<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">KMK KI</a></li>
    <li class="breadcrumb-item active">Forecast KMK KI</li>
</ol>
<h1 class="page-header">Forecast KMK KI</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Forecast KMK KI</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="form-row align-items-center">
                    <div class="col-4 form-group">
                        <label for="COMPANY">Company</label>
                        <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                            <option value="0">Select Company</option>
                            <?php
                            foreach ($DtCompany as $values) {
                                echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <!-- <div class="col-2">
                        <label for="PERIOD">Period</label>
                        <div class="input-group input-daterange">
                            <input type="text" class="form-control" id="PERIODFROM" autocomplete="off">
                            <div class="input-group-addon" style="padding:4px 0 0 0 !important;">></div>
                            <input type="text" class="form-control" id="PERIODTO"  autocomplete="off">
                        </div>
                    </div>  -->
                    <div class="col-2 form-group">
                        <label for="COMPANY">Credit Type</label>
                        <select class="form-control " name="CREDIT_TYPE" id="CREDIT_TYPE">
                            <option value="0"> Select All </option>
                            <option value="KMK">KMK</option>
                            <option value="KI">KI</option>
                        </select>
                    </div>
                    <div class="col-2 form-group">
                        <label for="DOCNUMBER">Search</label>
                        <input type="text" class="form-control" name="search" id="search" autocomplete="off" required>
                    </div>
                    <div class="col-2 form-group">
                        <label for="PERIOD">Period</label>
                        <input type="text" class="form-control" name="PERIOD" id="PERIOD" autocomplete="off" disabled='disabled'>
                    </div>
                    <div class="col-2 form-group">
                        <button class="btn btn-primary m-t-10 m-l-10" id="ForecastAll">Forecast All</button>
                    </div>
                    <!-- <div class="col-4 mt-4">
                        <button type="button" class="btn btn-info" style="padding: 3px 10px;" onclick="searchLeasing()">Search</button>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="row ml-0 mr-0 mb-0 mt-2 table-responsive">
            <table id="DtForecastKMKKI" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                <thead>
                    <tr role="row">
                        <th class="text-center sorting align-middle" >Company</th>
                        <th class="text-center sorting align-middle" >PK NUMBER</th>
                        <!-- <th class="text-center sorting align-middle" >Contract Number</th>
                        <th class="text-center sorting align-middle" >PK Number</th> -->
                        <th class="text-center sorting align-middle" >Credit Type</th>
                        <th class="text-center sorting align-middle" >Docdate</th>
                        <th class="text-center sorting align-middle" >Contract Number</th>
                        <th class="text-center sorting align-middle" >Interest Rate</th>
                        <th class="text-center sorting align-middle" >Amount Drawdown</th>
                        <th class="text-center sorting align-middle" >Installment</th>
                        <th class="text-center sorting align-middle" >IDC Installment</th>
                        <th class="text-center sorting align-middle" >Interest</th>
                        <th class="text-center sorting align-middle" >IDC Interest</th>
                        <th class="text-center sorting align-middle" >Action</th>
                        <th class="text-center"><input type="checkbox" id="pils"></th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- modal -->
        <div class="modal fade" id="ReceiveModal" tabindex="-1" role="dialog" aria-labelledby="ReceiveModal" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Doc No - <span id="DOCNUMB"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="FORMPAYMENT" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                    <div class="row align-items-center pay_state">
                        <div class="col-md-6 ">
                            <h5 class='pay_state_txt'>Next Payment Period :</h5>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="NEXT_PERIOD"></label>
                            <input type="text" name="NEXT_PERIOD" id="NEXT_PERIOD" class="form-control" disabled>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="EZUPPERI" class="btn btn-sm btn-primary" onclick="EasyUpdatePeriod()">Change</button>
                        </div>
                    </div>
                    <div class="row item_form">
                        <input type="hidden" id="id">
                        <input type="hidden" id="mcontractnumber">
                        <input type="hidden" id="mpknumber">
                        <input type="hidden" id="mcompany">
                        <input type="hidden" id="mwdtype">
                        <input type="hidden" id="mcurrency">
                        <input type="hidden" id="mdocdate">
                        <input type="hidden" id="ID">
                        <!-- <input type="hidden" id="noreceiptdoc"> -->
                        <!-- <span style="color:red;">* Maksimal 100 Karakter</span> -->
                        <div class="col-md-6 mb-2 form-group">
                                <label for="datepay">Date Installment</label>
                                <input type="date" class="form-control" name="DATE_INSTALL" id="DATE_INSTALL" disabled="disabled">
                        </div>
                        <div class="col-md-6 mb-2 form-group">
                            <label for="COMPANY">Date Payment</label>
                            <input type="text" class="form-control" name="MPERIOD" id="MPERIOD" autocomplete="off" required>
                        </div>
                        <div class="col-md-8 form-group">
                            <label for="COMPANY">Amount By User</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon" id="Curr"></span>
                                <input type="text" data-type='currency' placeholder="amount" class="form-control" name="AMOUNT_MODAL" id="AMOUNT_MODAL" autocomplete="off" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="installment">Installment</label>
                            <input type="text" data-type='currency' placeholder="amount" class="form-control" name="AMOUNT_INSTALLMENT" id="AMOUNT_INSTALLMENT" autocomplete="off" disabled="disabled">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="insterest">Interest Amount</label>
                            <input type="text" data-type='currency' placeholder="amount" class="form-control" name="AMOUNT_INTEREST" id="AMOUNT_INTEREST" autocomplete="off" disabled="disabled">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="installment_IDC">IDC Installment</label>
                            <input type="text" data-type='currency' placeholder="amount" class="form-control" name="AMOUNT_IDC_INSTALLMENT" id="AMOUNT_IDC_INSTALLMENT" autocomplete="off" disabled="disabled">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="insterest_IDC">IDC Interest Amount</label>
                            <input type="text" data-type='currency' placeholder="amount" class="form-control" name="AMOUNT_IDC_INTEREST" id="AMOUNT_IDC_INTEREST" autocomplete="off" disabled="disabled">
                        </div>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveModal">Save Payment Request</button>
              </div>
            </div>
          </div>
        </div>
        <!-- end modal -->
        <!-- modal for kmk -->
        <div class="modal fade" id="ReceiveModalKMK" tabindex="-1" role="dialog" aria-labelledby="ReceiveModalKMK" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Doc No - <span id="DOCNUMBKMK"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="FORMPAYMENTKMK" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                    <div class="row">
                        <input type="hidden" id="id">
                        <input type="hidden" id="mcontractnumber">
                        <input type="hidden" id="mpknumber">
                        <input type="hidden" id="mcompany">
                        <input type="hidden" id="mwdtype">
                        <input type="hidden" id="mcurrency">
                        <input type="hidden" id="mdocdate">
                        <input type="hidden" id="ID">
                        <input type="hidden" id="PERIOD">
                        <input type="hidden" id="START_PERIOD">
                        <!-- <input type="hidden" id="noreceiptdoc"> -->
                        <!-- <span style="color:red;">* Maksimal 100 Karakter</span> -->
                        <div class="col-md-6 mb-2 form-group">
                            <label for="COMPANY">Date Payment</label>
                            <input type="date" class="form-control" name="MPERIOD" id="MPERIODKMK" autocomplete="off" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="COMPANY">Amount By User</label>
                            <input type="text" data-type='currency' placeholder="amount" class="form-control" name="AMOUNT_MODAL" id="AMOUNT_MODALKMK" autocomplete="off" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="insterest">Interest Amount</label>
                            <input type="text" data-type='currency' placeholder="amount" class="form-control" name="AMOUNT_INTEREST" id="AMOUNT_INTERESTKMK" autocomplete="off" disabled="disabled">
                        </div>
                        <div class="col-md-6 form-group">
                             <label for="FINAL_AMOUNT">Final Amount</label>
                            <input type="text" data-type='currency' placeholder="amount" class="form-control" name="FINAL_AMOUNT" id="FINAL_AMOUNT" autocomplete="off" disabled="disabled" >
                        </div>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveModalKMK">Save Payment Request</button>
              </div>
            </div>
          </div>
        </div>
        <!-- end modal kmk -->
    </div>
</div>
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<!-- <script src="./assets/js/datetime/bootstrap-datetimepicker.min.js"></script> -->
<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var filetypeUpload = ['PDF', 'DOC', 'DOCX'];
    var files = '';
    var MultiFrcst = {} ;

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
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var table2;

    // $(document).ready(function () {
    //     LoadDataTable();
    // });
    var sDate,fDate;

    $('#MPERIOD').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "format": "mm-dd-yyyy"
    });
    var MONTH,YEAR;


    $('#PERIODTO').datepicker({
                "autoclose": true,
                "todayHighlight": true
        });
    $('#PERIODFROM').datepicker({
                "autoclose": true,
                "todayHighlight": true
        });

    $(document).ready(function(e){
        $('#loader').addClass('show');
        $('button.dt-button').addClass('btn');
        $('button.dt-button').addClass('btn-primary');
    });

    // $('#MPERIOD').datepicker({
    //     "autoclose": true,
    //     "todayHighlight": true,
    //     "format": "mm/dd/yyyy",
    //     "startDate": '-1m',
    // });

    // function LoadDataTable() {
        if (!$.fn.DataTable.isDataTable('#DtForecastKMKKI')) {
            $('#DtForecastKMKKI').DataTable({
                "processing": true,
                // dom: 'Bfrtip',
                // buttons: [
                //     {
                //         extend: 'excelHtml5',
                //         title: 'Report Payment'
                //     },  
                // ],
                "ajax": {
                    "url": "<?php echo site_url('Kmk/ShowDataForecast') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function (d) {
                        d.CREDIT_TYPE = $('#CREDIT_TYPE').val();
                        d.sDATE = sDate;
                        d.fDATE = fDate;
                        // d.DOCDATE =  moment($('#DOCDATE').val()).format('MM-DD-YYYY');
                        d.COMPANY   = $('#COMPANY').val();
                    },
                    "dataSrc": function (ext) {
                        
                        if (ext.status == 200) {
                            $("#loader").hide();
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
                "columns": [
                    {"data": "COMPANYCODE","className": "text-center",},
                    {"data": "PK_NUMBER","className": "text-center",},
                    {"data": "SUB_CREDIT_TYPE","className": "text-center",},
                    // {"data": "CONTRACT_NUMBER"},
                    // {"data": "PK_NUMBER"},
                    {"data": "DOCDATE","className": "text-center",},
                    {"data": "CONTRACT_NUMBER","className": "text-center",},
                    {"data": "INTEREST_RATE","className": "text-center",},
                    {"data": "TOTALWD","className": "text-center",render: $.fn.dataTable.render.number(',', '.', 2)},
                    {"data": "INSTALLMENT","className": "text-center",render: $.fn.dataTable.render.number(',', '.', 2)},
                    {"data": "IDC_INSTALLMENT","className": "text-center",render: $.fn.dataTable.render.number(',', '.', 2)},
                    {"data": "INTEREST","className": "text-center",render: $.fn.dataTable.render.number(',', '.', 2)},
                    {"data": "IDC_INTEREST","className": "text-center",render: $.fn.dataTable.render.number(',', '.', 2)},
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            var html = '';
                            // if(row.SUB_CREDIT_TYPE == 'WA' || row.SUB_CREDIT_TYPE == 'RK' || row.SUB_CREDIT_TYPE == 'TL' || row.SUB_CREDIT_TYPE == 'BD') {
                            //     html += '<button class="btn btn-indigo btn-sm ReceiveModalKMK" data-id="'+data.UUID+'" data-contractnumber="'+data.CONTRACT_NUMBER+'" data-pknumber="'+data.PK_NUMBER+'" data-company="'+data.COMPANY+'" data-wdtype="'+data.SUB_CREDIT_TYPE+'" data-currency="'+data.CURRENCY+'" data-docdate="'+data.DOCDATE+'"  id="ReceiveModalsKMK" title="Receive" data-toggle="modal" data-target="#ReceiveModalKMK">Forecast</button>';
                            // }
                            // else {
                            //     html += '<button class="btn btn-indigo btn-sm ReceiveModal" data-id="'+data.UUID+'" data-contractnumber="'+data.CONTRACT_NUMBER+'" data-pknumber="'+data.PK_NUMBER+'" data-company="'+data.COMPANY+'" data-wdtype="'+data.SUB_CREDIT_TYPE+'" data-currency="'+data.CURRENCY+'" data-docdate="'+data.DOCDATE+'"  id="ReceiveModals" title="Receive" data-toggle="modal" data-target="#ReceiveModal">Forecast</button>';
                            // }
                            if(row.IS_PAYMENT == 1) {
                                html += '<button class="btn btn-lime btn-sm Forecast" data-id="'+data.UUID+'" data-contractnumber="'+data.CONTRACT_NUMBER+'" data-pknumber="'+data.PK_NUMBER+'" data-company="'+data.COMPANY+'" data-wdtype="'+data.SUB_CREDIT_TYPE+'" data-currency="'+data.CURRENCY+'" data-docdate="'+data.DOCDATE+'"  id="Forecast" title="Receive">Forecasted</button>'
                            }
                            else if (row.ID == null) {
                                html += '<button class="btn btn-yellow btn-sm Forecast" data-id="'+data.UUID+'" data-contractnumber="'+data.CONTRACT_NUMBER+'" data-pknumber="'+data.PK_NUMBER+'" data-company="'+data.COMPANY+'" data-wdtype="'+data.SUB_CREDIT_TYPE+'" data-currency="'+data.CURRENCY+'" data-docdate="'+data.DOCDATE+'"  id="Forecast" title="Receive">Not In Range</button>'
                            }
                            else {
                                html += '<button class="btn btn-primary btn-sm Forecast" data-id="'+data.UUID+'" data-contractnumber="'+data.CONTRACT_NUMBER+'" data-pknumber="'+data.PK_NUMBER+'" data-company="'+data.COMPANY+'" data-wdtype="'+data.SUB_CREDIT_TYPE+'" data-currency="'+data.CURRENCY+'" data-docdate="'+data.DOCDATE+'"  id="Forecast" title="Receive">Forecast</button>'
                            }
                            // html += '<button class="btn btn-info btn-sm COMPLETION" data-id="'+data.ID+'" data-year="'+data.PERIOD_YEAR+'" data-month="'+data.PERIOD_MONTH+'"  data-docnumber="'+data.DOCNUMBER+'" data-lineno="'+data.LINENO+'"  id="COMPLETION" title="Pay">Execute</button>';
                            return html;
                        }
                    },
                    {
                        "data": null,
                        "className": "text-center align-middle",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            if(row.IS_PAYMENT == 1 || row.ID == null) {
                                return '' ;
                            }
                            else {
                                return '<input type="checkbox" class="pil">';
                            }
                        }
                    }
                ],
                deferRender: true,
                scrollY: 400,
                scrollX: true,
                scrollCollapse: true,
                scroller: true,
                "bFilter": true,
                "bPaginate": false,
                "bLengthChange": false,
                "bInfo": true,
            });

            $('#DtForecastKMKKI_filter').remove()

            $('#search').on( 'input', function () {
                table2.search( this.value ).draw();
            });

            $('#DtForecastKMKKI thead th').addClass('text-center');
            table2 = $('#DtForecastKMKKI').DataTable();
            // $("#DtForecastKMKKI_filter").remove();
            // $("#DOCNUMBER").on({
            //     'keyup': function () {
            //         table2.search(this.value, true, false, true).draw();
            //     }
            // });

        } else {
            table2.ajax.reload();
        }
    // }

    

    var searchLeasing = function () {
        // if ($('#DOCDATE').val() == '' || $('#DOCNUMBER').val() == '' || $('#DOCDATE').val() == null || $('#DOCNUMBER').val() == null) {
        //     alert("'cannot be empty!")
        //     return false
        // } else {
            
        // }
        LoadDataTable();
    }

    var EasyUpdatePeriod = function() {
        var COMPANY = $('#mcompany').text();
        var PERIOD = $('#NEXT_PERIOD').val();
        $('#loader').addClass('show');
        $('#loader').show();
        $.ajax({
            dataType:"JSON",
            type:'POST',
            url : "<?php echo site_url("Kmk/easyUpdatePeriodControl")?>",
            data : {
                "PERIOD" : PERIOD,
                "COMPANY" : COMPANY
            },
            success : function (response) {
                $('#loader').hide();
                $('#loader').removeClass('show');
                var data = response.result.data ;
                var status = response.status ;
                
                if(status == 200) {
                    $('#ReceiveModal').modal('hide');
                    toastr.success('Period Change') ;
                }
                else if(status == 500) {
                    $('#ReceiveModal').modal('hide');
                    toastr.error('Error');
                }
                $('#loader').removeClass('show');
            },
            error : function(err) {
                $('#loader').hide();
                $('#loader').removeClass('show');
                $('#ReceiveModal').modal('hide');
                console.error(err);
            }
        })
    }

    $('#COMPANY').on({
        'change': function () {
            // MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
            // YEAR = this.value.substr(4, 4);
            // COMPANY = $(this).val();
            table2 = $('#DtForecastKMKKI').DataTable();
            table2.ajax.reload();
            // LoadDataTable();
        }
    });
    $('#CREDIT_TYPE').on({
        'change': function () {
            // MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
            // YEAR = this.value.substr(4, 4);
            // COMPANY = $(this).val();
            table2 = $('#DtForecastKMKKI').DataTable();
            table2.ajax.reload();
            // LoadDataTable();
        }
    });

    $('#pils').on('change', function() {
        if(this.checked) {
            $('#DtForecastKMKKI .pil').prop('checked', true);
            table2.rows().every(function(rowIdx, tableLoop, rowLoop) {
                MultiFrcst[`${this.data().CONTRACT_NUMBER}`] = this.data() ;
            })
        }
        else {
            $('#DtForecastKMKKI .pil').prop('checked', false);
            for (var prop in MultiFrcst) 
                { 
                    if (MultiFrcst.hasOwnProperty(prop)) 
                    { 
                        delete MultiFrcst[prop]; 
                    } 
                }
        }
        $('#DtLeasingTransaction .pil').change();
    });

    $('body').on('change', '.pil', function() {
        let tr = $(this).closest('tr') ;
        var data = table2.row(tr).data();
        if(this.checked) {
            if(!MultiFrcst.hasOwnProperty(data.CONTRACT_NUMBER)){
                MultiFrcst[data.CONTRACT_NUMBER] = data ;
            }
        }
        else {
                delete MultiFrcst[data.CONTRACT_NUMBER] ;
        }
    })

   $('body').on('click', '#Forecast', function() {
        $("#loader").addClass('show');
        $("#loader").show();
        let tr = $(this).closest('tr') ;
        var data = table2.row(tr).data();
        if(data.ID != null && data.IS_PAYMENT != 1) {
            if(confirm(`Forecast ${data.CONTRACT_NUMBER} for Period : ${data.MONTH} - ${data.YEAR} ?`)){
                $.ajax({
                    dataType : 'JSON',
                    type: 'POST',
                    url : "<?php echo site_url("Kmk/ForecastSingle")?>",
                    data : {
                        'FRCST' : data,
                        'USERNAME' : USERNAME,
                    },
                    success : function(response) {
                        $('#loader').hide();
                        var data = response.result.data ;
                        if(response.status == 200) {
                            toastr.success(data);
                        }
                        else {
                            toastr.error(data);
                        }
                        table2.ajax.reload();
                    },
                    error : function(err) {
                        $('#loader').hide();
                        toastr.error('err');
                        console.log(err);
                        table2.ajax.reload();
                    }
                });
            }
            else {
                $('#loader').hide();
            }
        }
        else {
            if(confirm(`Move Period ${data.CONTRACT_NUMBER} To Period : ${data.LAT_PM} - ${data.LAT_PY} ?`)){
                var PERIOD = `${data.LAT_PM}-${data.LAT_PY}` ;
                $.ajax({
                    dataType : 'JSON',
                    type: 'POST',
                    url : "<?php echo site_url("Kmk/easyUpdatePeriodControl")?>",
                    data : {
                        'PERIOD' : PERIOD,
                        'COMPANY' : data.COMPANY
                    },
                    success : function(response) {
                        $('#loader').hide();
                        var data = response.result.data ;
                        if(response.status == 200) {
                            toastr.success(data);
                        }
                        else {
                            toastr.error(data);
                        }
                        table2.ajax.reload();
                    },
                    error : function(err) {
                        $('#loader').hide();
                        toastr.error('err');
                        console.log(err);
                        table2.ajax.reload();
                    }
                })
                
            } 
            else {
                $('#loader').hide();
            }
        }
   })

   $('body').on('click', '#ForecastAll', function() {
        $('#loader').addClass('show');
        $('#loader').show();
        var items = Object.keys(MultiFrcst);
        var text = `Are you sure want to forecast ${items.join(' , ')} contracts?` 
        if(Object.keys(MultiFrcst).length != 0 && confirm(text)) {
            $.ajax({
                dataType : 'JSON',
                type : 'POST',
                url : "<?php echo site_url("Kmk/ForecastMultiple")?>",
                data: {
                    'FRCST' : JSON.stringify(MultiFrcst),
                    'USERNAME' : USERNAME
                },
                success: function(response) {
                    var data = response.result.data ;
                    if(response.status == 200) {
                        toastr.success(data) ;
                    }
                    else {
                        toastr.error(data);
                    }
                    table2.ajax.reload();
                    $('#loader').hide();
                },
                error: function(error) {
                    toastr.error('err');
                    $('#loader').hide();
                    table2.ajax.reload();
                }
            })
        }
        else {
            toastr.error('No item in forecast');
            $('#loader').hide();
        }
        for (var prop in MultiFrcst) 
            { 
                if (MultiFrcst.hasOwnProperty(prop)) 
                { 
                    delete MultiFrcst[prop]; 
                } 
            }
        // console.log(MultiFrcst);
   })

    $('body').on('change', '#MPERIODKMK', function() {
        var contract_number = $('#mcontractnumber').text();
        var pk_number = $('#mpknumber').text();
        $.ajax({
            dataType : 'JSON',
            type : 'POST',
            url : "<?php echo site_url("Kmk/showInterestKMKByDate")?>",
            data : {
                CONTRACT_NUMBER : contract_number,
                PAYMENT_DATE : $('#MPERIODKMK').val()
            },
            success : function(response) {
                var data = response.result.data ;
                if(response.status == 200) {
                    $('#AMOUNT_INTERESTKMK').val(fCurrency(String(data.INTEREST)));
                    var amount = parseInt(formatDesimal($('#AMOUNT_MODALKMK').val() ? $('#AMOUNT_MODALKMK').val() : '0'));
                    var finalamount = amount + data.INTEREST ;
                    $('#FINAL_AMOUNT').val(fCurrency(String(finalamount)));
                    $('#saveModalKMK').removeAttr('disabled');
                    $('#PERIOD').val(data.PERIOD);
                    $('#START_PERIOD').val(data.START_PERIOD);
                } 
                else {
                    toastr.error(data);
                    $('#AMOUNT_INTERESTKMK').val(fCurrency('0'));
                    $('#FINAL_AMOUNT').val(fCurrency('0'));
                    $('#saveModalKMK').attr('disabled', 'disabled');
                }
            },
            error : function(err) {
                toastr.error(err);
            }
        })
        // console.log(contract_number, pk_number);

    });

    $('body').on('change','#AMOUNT_MODALKMK', function(){
        var amount = parseInt(formatDesimal($(this).val()));
        var interest = parseInt(formatDesimal($('#AMOUNT_INTERESTKMK').val() ? $('#AMOUNT_INTERESTKMK').val() : '0'));
        var finalamount = amount+interest ;
        $('#FINAL_AMOUNT').val(fCurrency(String(finalamount)));
    })

</script>

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
        // console.log(input_val);
        // console.log(input_val.indexOf('.')); 
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

    function fCurrency(n, curr = '') {
        if (n.indexOf(".") >= 0) {
            var decimal_pos = n.indexOf(".");
            var left_side = n.substring(0, decimal_pos);
            var right_side = n.substring(decimal_pos);
            left_side = formatNumber(left_side);
            right_side = formatNumber(right_side);
            right_side += "00";
            right_side = right_side.substring(0, 2);
            n = left_side + "." + right_side;
        } else {
            n = formatNumber(n);
            n += ".00";
          
        }
        if(curr != '') {
            n = curr +" "+ n ;
        } 
        return n ;
    }

</script>