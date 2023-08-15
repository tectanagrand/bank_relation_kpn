<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">KMK</a></li>
    <li class="breadcrumb-item active">Payment Request</li>
</ol>
<h1 class="page-header">Payment Request</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Payment Request</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="form-row">
                    <div class="col-4">
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
                    <div class="col-2">
                        <label for="COMPANY">Credit Type</label>
                        <select class="form-control " name="CREDIT_TYPE" id="CREDIT_TYPE">
                            <option value="0"> Select All </option>
                            <option value="KMK">KMK</option>
                            <option value="KI">KI</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="DOCNUMBER">Search</label>
                        <input type="text" class="form-control" name="search" id="search" autocomplete="off" required>
                    </div>
                     <div class="col-2">
                        <label for="PERIOD">Period</label>
                        <input type="text" class="form-control" name="PERIOD" id="PERIOD" autocomplete="off" disabled='disabled'>
                    </div>
                    <!-- <div class="col-4 mt-4">
                        <button type="button" class="btn btn-info" style="padding: 3px 10px;" onclick="searchLeasing()">Search</button>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="row ml-0 mr-0 mb-0 mt-2 table-responsive">
            <table id="DtLeasingCompletion" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                <thead>
                    <tr role="row">
                        <th class="text-center sorting align-middle" >Company</th>
                        <th class="text-center sorting align-middle" >PK NUMBER</th>
                        <!-- <th class="text-center sorting align-middle" >Contract Number</th>
                        <th class="text-center sorting align-middle" >PK Number</th> -->
                        <th class="text-center sorting align-middle" >Credit Type</th>
                        <th class="text-center sorting align-middle" >Docdate</th>
                        <th class="text-center sorting align-middle" >Contract Number</th>
                        <th class="text-center sorting align-middle" >Interest</th>
                        <th class="text-center sorting align-middle" >Amount</th>
                        <th class="text-center sorting align-middle" >Action</th>
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
        if (!$.fn.DataTable.isDataTable('#DtLeasingCompletion')) {
            $('#DtLeasingCompletion').DataTable({
                "processing": true,
                // dom: 'Bfrtip',
                // buttons: [
                //     {
                //         extend: 'excelHtml5',
                //         title: 'Report Payment'
                //     },  
                // ],
                "ajax": {
                    "url": "<?php echo site_url('Kmk/ShowDataPaymentRequest') ?>",
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
                    {"data": "INTEREST","className": "text-center",},
                    {"data": "TOTALWD","className": "text-center",render: $.fn.dataTable.render.number(',', '.', 2)},
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            var html = '';
                            if(row.SUB_CREDIT_TYPE == 'WA' || row.SUB_CREDIT_TYPE == 'RK' || row.SUB_CREDIT_TYPE == 'TL' || row.SUB_CREDIT_TYPE == 'BD') {
                                html += '<button class="btn btn-indigo btn-sm ReceiveModalKMK" data-id="'+data.UUID+'" data-contractnumber="'+data.CONTRACT_NUMBER+'" data-pknumber="'+data.PK_NUMBER+'" data-company="'+data.COMPANY+'" data-wdtype="'+data.SUB_CREDIT_TYPE+'" data-currency="'+data.CURRENCY+'" data-docdate="'+data.DOCDATE+'"  id="ReceiveModalsKMK" title="Receive" data-toggle="modal" data-target="#ReceiveModalKMK">Create Request</button>';
                            }
                            else {
                                html += '<button class="btn btn-indigo btn-sm ReceiveModal" data-id="'+data.UUID+'" data-contractnumber="'+data.CONTRACT_NUMBER+'" data-pknumber="'+data.PK_NUMBER+'" data-company="'+data.COMPANY+'" data-wdtype="'+data.SUB_CREDIT_TYPE+'" data-currency="'+data.CURRENCY+'" data-docdate="'+data.DOCDATE+'"  id="ReceiveModals" title="Receive" data-toggle="modal" data-target="#ReceiveModal">Create Request</button>';
                            }
                            // html += '<button class="btn btn-info btn-sm COMPLETION" data-id="'+data.ID+'" data-year="'+data.PERIOD_YEAR+'" data-month="'+data.PERIOD_MONTH+'"  data-docnumber="'+data.DOCNUMBER+'" data-lineno="'+data.LINENO+'"  id="COMPLETION" title="Pay">Execute</button>';
                            return html;
                        }
                    }
                ],
            });

            $('#DtLeasingCompletion_filter').remove()

            $('#search').on( 'input', function () {
                table2.search( this.value ).draw();
            });

            $('#DtLeasingCompletion thead th').addClass('text-center');
            table2 = $('#DtLeasingCompletion').DataTable();
            // $("#DtLeasingCompletion_filter").remove();
            // $("#DOCNUMBER").on({
            //     'keyup': function () {
            //         table2.search(this.value, true, false, true).draw();
            //     }
            // });

        } else {
            table2.ajax.reload();
        }
    // }

    $('body').on('click','#ReceiveModals',function(){
            // console.log($(this).attr('data-currency'));
            // var IDS = $(this).attr('data-id');
            $('body').find('#saveModal').html('Save Payment Request').removeClass('btn-success').addClass('btn-primary');
            var UUID           = $(this).attr('data-id');
            var contractnumber = $(this).attr('data-contractnumber');
            var COMPANY   = $(this).attr('data-company');
            var pknumber  = $(this).attr('data-pknumber');
            var wdtype    = $(this).attr('data-wdtype');
            var currency  = $(this).attr('data-currency');
            var docdate = $(this).attr('data-docdate');
            $('#DOCNUMB').text(contractnumber);
            $('#MPERIOD').val("");
            $('#AMOUNT_MODAL').val("") ;
            $('#AMOUNT_INSTALLMENT').val("");
            $('#AMOUNT_INTEREST').val("");
            $('#AMOUNT_IDC_INSTALLMENT').val("");
            $('#AMOUNT_IDC_INTEREST').val("");
            $('#DATE_INSTALL').val("");
            $('#EXC_RATE').val("");
            $('#id').text(UUID);
            $('#mcontractnumber').text(contractnumber);
            $('#mdocdate').text(docdate);
            $('#mcompany').text(COMPANY);
            $('#mpknumber').text(pknumber);
            $('#mwdtype').text(wdtype);
            $('#mcurrency').val(currency);
            $('#EZUPPERI').attr("disabled", true);

            if(wdtype == 'KI') {
                $.ajax({
                    dataType : 'JSON',
                    type : 'POST',
                    url : "<?php echo site_url("Kmk/ShowLatestInstallment")?>",
                    data : {
                        CONTRACT_NUMBER : contractnumber
                    },
                    success : function(response){
                        var res = response.result.data;
                        var data = res.DATA ;
                        var currentMonth = parseInt(data['CURRENTACCOUNTINGPERIOD']) ;
                        var currentYear = parseInt(data['CURRENTACCOUTINGYEAR']);
                        var latMonth = parseInt(data['LAT_PM']);
                        var latYear = parseInt(data['LAT_PY']) ;
                        $(".alert-warning").remove();
                        // console.log(data);
                        if(res.IS_EXIST == true && data['IS_PAYMENT'] == null) {
                            if(currentMonth != latMonth && currentYear != latYear) {
                                $('.pay_state').show();
                                $('#NEXT_PERIOD').val(String(latMonth)+'-'+String(latYear));
                                var alertChg = `<div class="alert alert-warning" role="alert">
                                                    This Payment exceeded latest period payment term, please go back by click "Change" button
                                                    </div>`;
                                $(alertChg).insertBefore('.item_form');
                               $('body').find('#saveModal').attr('disabled',false);
                               $('body').find('#EZUPPERI').attr('disabled',false);
                            }
                            else {
                                $(".alert-warning").remove();
                                $('.pay_state').hide();
                                $('body').find('#EZUPPERI').attr('disabled','disabled');
                               $('body').find('#saveModal').attr('disabled',false);
                            }
                            $('body').find('#saveModal').html('Save Payment Request').removeClass('btn-success').addClass('btn-primary');
                            $('#MPERIOD').attr("disabled",false);
                            $('#DATE_INSTALL').val(data['END_PERIOD_C']);
                            var amount_to_pay = parseInt(data['INSTALLMENT']) + parseInt(data['INTEREST']) + parseInt(data['IDC_INTEREST'] ? data['IDC_INTEREST'] : 0 ) + parseInt(data['IDC_INSTALLMENT'] ? data['IDC_INSTALLMENT'] :0);
                            $('#AMOUNT_INSTALLMENT').val(fCurrency(data['INSTALLMENT'], data['CURRENCY']));
                            $('#AMOUNT_INTEREST').val(fCurrency(data['INTEREST'], data['CURRENCY']));
                            $('#AMOUNT_IDC_INSTALLMENT').val(data['CURRENCY'] +" "+fCurrency(data['IDC_INSTALLMENT'] ? data['IDC_INSTALLMENT'] : '0'));
                            $('#AMOUNT_IDC_INTEREST').val(data['CURRENCY'] +" "+ fCurrency(data['IDC_INTEREST'] ? data['IDC_INTEREST'] : '0'));
                            $('#AMOUNT_MODAL').val(fCurrency(String(amount_to_pay))) ;
                            $('#Curr').html(data['CURRENCY']);
                            $('#EXC_RATE').val(data['EXCHANGE_RATE']);
                            MONTH = data['CURRENTACCOUNTINGYEAR'] ;
                            YEAR = data['CURRENTACCOUNTINGPERIOD'];
                            $('#ID').val(data['ID']);
                            $('#PERIOD').val(MONTH + '-' + YEAR);
                            
                        }
                        else if(res.IS_EXIST == true && data['IS_PAYMENT'] == 1) {
                            $('.pay_state').show();
                            $('#NEXT_PERIOD').val(String(latMonth)+'-'+String(latYear));
                            var alertChg = `<div class="alert alert-warning" role="alert">
                                                This Payment period term already paid, please go to next term by click "Change" button
                                                </div>`;
                            $(alertChg).insertBefore('.item_form');
                            $('body').find('#EZUPPERI').attr('disabled',false);
                            // $(".alert-warning").remove();
                            // $('.pay_state').hide();
                            $('#DATE_INSTALL').val(data['END_PERIOD_C']);
                            var amount_to_pay = parseInt(data['INSTALLMENT']) + parseInt(data['INTEREST']) + parseInt(data['IDC_INTEREST'] ? data['IDC_INTEREST'] : 0 ) + parseInt(data['IDC_INSTALLMENT'] ? data['IDC_INSTALLMENT'] :0);
                            $('#AMOUNT_INSTALLMENT').val(fCurrency(data['INSTALLMENT'], data['CURRENCY']));
                            $('#AMOUNT_INTEREST').val(fCurrency(data['INTEREST'], data['CURRENCY']));
                            $('#AMOUNT_IDC_INTEREST').val(data['CURRENCY'] +" "+ fCurrency(data['IDC_INTEREST'] ? data['IDC_INTEREST'] : '0' ));
                            $('#AMOUNT_IDC_INSTALLMENT').val(data['CURRENCY'] +" "+fCurrency(data['IDC_INSTALLMENT'] ? data['IDC_INSTALLMENT'] : '0'));
                            $('#MPERIOD').val(data['PAYMENT_DATE_C']);
                            $('#MPERIOD').attr("disabled","disabled");
                            $('#AMOUNT_MODAL').val(fCurrency(String(amount_to_pay))) ;
                            $('#Curr').html(data['CURRENCY']);
                            $('body').find('#saveModal').html('Payment Requested').removeClass('btn-primary').addClass('btn-success');
                            $('body').find('#saveModal').attr('disabled','disabled');
                            MONTH = data['CURRENTACCOUNTINGYEAR'] ;
                            YEAR = data['CURRENTACCOUNTINGPERIOD'];
                            $('#PERIOD').val(MONTH + '-' + YEAR);
                        }
                        else if(res.IS_EXIST == false) {
                            $(".alert-warning").remove();
                            $('.pay_state').show();
                            $('body').find('.btn-primary').attr('disabled','disabled');
                            $('#MPERIOD').attr("disabled",false);
                            $('#NEXT_PERIOD').val(data['PERIOD_NXT']);
                            $('#EZUPPERI').attr("disabled", false);
                            toastr.error('Not In Payment Period');
                        }
                        else {
                            $(".alert-warning").remove();
                            $('body').find('.btn-primary').attr('disabled','disabled');
                            $('#MPERIOD').attr("disabled",false);
                            toastr.error('ERROR');
                        }
                    },
                    error : function(error) {
                        toastr.error(error);
                    }
                });
            }
            else {
                alert('On Development');
                $('body').find('.btn-primary').attr('disabled','disabled');
            }

            // $('#noreceiptdoc').val(IDS);
            // alert($('#ModNO_PO').text());
    });

    $('body').on('click','#ReceiveModalsKMK',function(){
            // console.log($(this).attr('data-contractnumber'));
            // alert('On Development');
            // var IDS = $(this).attr('data-id');
            $('body').find('#saveModal').html('Save Payment Request').removeClass('btn-success').addClass('btn-primary');
            var UUID           = $(this).attr('data-id');
            var contractnumber_kmk = $(this).attr('data-contractnumber');
            var COMPANY   = $(this).attr('data-company');
            var pknumber  = $(this).attr('data-pknumber');
            var wdtype    = $(this).attr('data-wdtype');
            var currency  = $(this).attr('data-currency');
            var docdate = $(this).attr('data-docdate');
            $('#DOCNUMBKMK').text(contractnumber_kmk);
            $('#MPERIODKMK').val("");
            $('#AMOUNT_MODALKMK').val("");
            $('#FINAL_AMOUNT').val("");
            $('#AMOUNT_INTERESTKMK').val("");
            $('#PERIOD').val("");
            $('#START_PERIOD').val("");
            $('#id').text(UUID);
            $('#mcontractnumber').text(contractnumber_kmk);
            $('#mdocdate').text(docdate);
            $('#mcompany').text(COMPANY);
            $('#mpknumber').text(pknumber);
            $('#mwdtype').text(wdtype);
            $('#mcurrency').val(currency);
            // $('#saveModalKMK').attr('disabled', 'disabled');

            // $('#noreceiptdoc').val(IDS);
            // alert($('#ModNO_PO').text());
    });

    $('body').on('click','#saveModal',function(){
        // console.log($('#mcurrency').val());
        $('#loader').addClass('show');
        $('#loader').show();
        var dttime          = moment($('#MPERIOD').val()).format('YYYY-MM-DD');
        var UUID            = $("#id").text();
        var COMPANY         = $('#mcompany').text(); //$(this).attr('data-company');
        var contractnumber  = $("#mcontractnumber").text();
        var docdate         = $("#mdocdate").text();
        var pknumber        = $("#mpknumber").text();
        var wdtype          = $("#mwdtype").text();
        var amount          = $('#AMOUNT_MODAL').val();
        var currency        = $('#mcurrency').val();

        if(amount == '' || amount == null){
            toastr.error("Amount Cant Empty");
        }else{
            if($('#FORMPAYMENT').parsley().validate()) {
                var fd = new FormData();
                fd.append("UUID", UUID);
                fd.append("COMPANY", COMPANY);
                fd.append("PK_NUMBER", pknumber);
                fd.append("CURRENCY", currency);
                fd.append("CONTRACT_NUMBER", contractnumber);
                fd.append("ID", $("#ID").val());
                fd.append("CREDIT_TYPE", wdtype);
                fd.append("AMOUNT_PAID", amount);
                fd.append("DATE_PAY", dttime);
                fd.append("USERNAME", USERNAME);
                fd.append("DOCDATE", docdate);
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Kmk/savePaymentReq'); ?>",
                    data: fd,
                    processData : false,
                    contentType : false,
                    cache : false,
                    success: function (response) {
                        $('#loader').removeClass('show');
                        $('#loader').hide();
                        if (response.status == 200) {
                            toastr.success(response.result.data);
                            $('#ReceiveModal').modal('hide');
                            $('#ReceiveModal').on('hidden.bs.modal', function(e) {
                            $(this).find('#FORMPAYMENT')[0].reset();
                            });
                            $('#FORMPAYMENT').parsley().reset();
                            // $('.PAY').attr('disabled',true);
                            $('#amount_modal').val('');
                            table2.ajax.reload();
                        } else if (response.status == 504) {
                            alert(response.result.data);
                            $('#ReceiveModal').modal('hide');
                            $('#ReceiveModal').on('hidden.bs.modal', function(e) {
                            $(this).find('#FORMPAYMENT')[0].reset();
                            });
                            $('#FORMPAYMENT').parsley().reset();
                            $('#amount_modal').val('');
                            table2.ajax.reload();
                        } else {
                            alert(response.result.data);
                            $('#ReceiveModal').modal('hide');
                            $('#ReceiveModal').on('hidden.bs.modal', function(e) {
                            $(this).find('#FORMPAYMENT')[0].reset();
                            });
                            $('#FORMPAYMENT').parsley().reset();
                            $('#amount_modal').val('');
                        }
                    },
                    error: function (e) {
                        $('#loader').removeClass('show');
                        $('#loader').hide();
                        $('#ReceiveModal').modal('hide');
                        $('#ReceiveModal').on('hidden.bs.modal', function(e) {
                        $(this).find('#FORMPAYMENT')[0].reset();
                        });
                        $('#FORMPAYMENT').parsley().reset();
                        // console.info(e);
                        alert('Data Save Failed !!');
                        table2.ajax.reload();
                        $('#btnSave').removeAttr('disabled');
                    }
                });
            }
        }
    });
    $('body').on('click','#saveModalKMK',function(){
        // console.log($('#mcurrency').val());
        $('#loader').addClass('show');
        $('#loader').show();
        var dttime          = $('#MPERIODKMK').val();
        var UUID            = $("#id").text();
        var COMPANY         = $('#mcompany').text(); //$(this).attr('data-company');
        var contractnumber  = $("#mcontractnumber").text();
        var docdate         = $("#mdocdate").text();
        var pknumber        = $("#mpknumber").text();
        var wdtype          = $("#mwdtype").text();
        var amount          = $('#FINAL_AMOUNT').val();
        var currency        = $('#mcurrency').val();
        var installment     = $('#AMOUNT_MODALKMK').val();
        var interest        = $('#AMOUNT_INTERESTKMK').val();
        var period          = $('#PERIOD').val();
        var start_period    = $('#START_PERIOD').val();

        var fileInput = $('#upload-payment-filekmk');
        var extFile = $('#upload-payment-filekmk').val().split('.').pop().toUpperCase();
        var maxSize = fileInput.data('max-size');
        if($.inArray(extFile, filetypeUpload) === -1) {
            toastr.error('Format file tidak valid');
            files = '';
            $('#upload-payment-filekmk').val('');
            return;
        }
        if(fileInput.get(0).files.length) {
            var fileSize = fileInput.get(0).files[0].size;
            if(fileSize > maxSize) {
                toastr.error('Ukuran file terlalu besar');
                files = '';
                $('#upload-payment-filekmk').val('');
                return;
            }
        }
        if(amount == '' || amount == null){
            toastr.error("Amount Cant Empty");
        }else{
            if($('#FORMPAYMENTKMK').parsley().validate()) {
                // table2.ajax.reload();
                files = document.getElementById('upload-payment-filekmk').files;
                // console.log(files);
                var FILENAME = files[0].name;
                var fd = new FormData();
                $.each(files, function(i, data) {
                    fd.append("userfile", data);
                });
                fd.append("COMPANY", COMPANY);
                fd.append("PK_NUMBER", pknumber);
                fd.append("CURRENCY", currency);
                fd.append("CONTRACT_NUMBER", contractnumber);
                fd.append("AMOUNT_PAID", amount);
                fd.append("PAYMENT_DATE", dttime);
                fd.append("USERNAME", USERNAME);
                fd.append("DOCDATE", docdate);
                fd.append("INSTALLMENT", installment);
                fd.append("INTEREST", interest);
                fd.append("PERIOD", period);
                fd.append("START_PERIOD", start_period);
                fd.append("FILENAME", FILENAME);
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    // processData: false, 
                    // contentType: false, 
                    // cache: false,
                    url: "<?php echo site_url('Kmk/savePaymentReqKMK'); ?>",
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $('#loader').removeClass('show');
                        $('#loader').hide();
                        if (response.status == 200) {
                            toastr.success(response.result.data.MESSAGE);
                            $('#ReceiveModalKMK').modal('hide');
                            $('#ReceiveModalKMK').on('hidden.bs.modal', function(e) {
                            $(this).find('#FORMPAYMENTKMK')[0].reset();
                            });
                            $('#FORMPAYMENTKMK').parsley().reset();
                            // $('.PAY').attr('disabled',true);
                            $('#amount_modal').val('');
                            table2.ajax.reload();
                        } else if (response.status == 504) {
                            alert(response.result.data.MESSAGE);
                            $('#ReceiveModal').modal('hide');
                            $('#ReceiveModal').on('hidden.bs.modal', function(e) {
                            $(this).find('#FORMPAYMENT')[0].reset();
                            });
                            $('#FORMPAYMENT').parsley().reset();
                            $('#amount_modal').val('');
                            table2.ajax.reload();
                        } else {
                            alert(response.result.data.MESSAGE);
                            $('#ReceiveModal').modal('hide');
                            $('#ReceiveModal').on('hidden.bs.modal', function(e) {
                            $(this).find('#FORMPAYMENT')[0].reset();
                            });
                            $('#FORMPAYMENT').parsley().reset();
                            $('#amount_modal').val('');
                        }
                    },
                    error: function (e) {
                        $('#loader').removeClass('show');
                        $('#loader').hide();
                        $('#ReceiveModal').modal('hide');
                        $('#ReceiveModal').on('hidden.bs.modal', function(e) {
                        $(this).find('#FORMPAYMENT')[0].reset();
                        });
                        $('#FORMPAYMENT').parsley().reset();
                        // console.info(e);
                        alert('Data Save Failed !!');
                        table2.ajax.reload();
                        $('#btnSave').removeAttr('disabled');
                    }
                });
            }
        }
    });

    

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
            table2 = $('#DtLeasingCompletion').DataTable();
            table2.ajax.reload();
            // LoadDataTable();
        }
    });
    $('#CREDIT_TYPE').on({
        'change': function () {
            // MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
            // YEAR = this.value.substr(4, 4);
            // COMPANY = $(this).val();
            table2 = $('#DtLeasingCompletion').DataTable();
            table2.ajax.reload();
            // LoadDataTable();
        }
    });

    // $('body').on('click','#saveModal',function(){
    //     var ID        = $("#id").text();
    //     var UUID      = $("#uuid").text();
    //     var COMPANY   = $('#COMPANY').val(); //$(this).attr('data-company');
    //     var DOCNUMBER = $("#mdocnumber").text();
    //     var LINENO    = $("#mlineno").text();
    //     var YEAR    = $("#myear").text();
    //     var MONTH    = $("#mmonth").text();
    //     var amount   = $('#amount_modal').val();
    //     if(amount == '' || amount == null){
    //         toastr.error("Amount Cant Empty");
    //     }else{
    //         $('#loader').addClass('show');
    //         // table2.ajax.reload();
    //         $.ajax({
    //             dataType: "JSON",
    //             type: "POST",
    //             url: "<?php echo site_url('Leasing/saveLeasingCompletion'); ?>",
    //             data: {
    //                 ID: ID,
    //                 UUID:UUID,
    //                 COMPANY: COMPANY,
    //                 DOCNUMBER: DOCNUMBER,
    //                 // DOCDATE: moment($('#DOCDATE').val()).format('MM-DD-YYYY'),
    //                 LINENO: LINENO,
    //                 MONTH:MONTH,
    //                 CREDIT_TYPE: $('#CREDIT_TYPE').val(),
    //                 COMDATE: $('#MPERIOD').val(),
    //                 AMOUNT_WITH_PENALTY:amount,
    //                 YEAR:YEAR,
    //                 CBTN:2,
    //                 USERNAME: USERNAME
    //             },
    //             success: function (response) {
    //                 $('#loader').removeClass('show');
    //                 if (response.status == 200) {
    //                     alert(response.result.data);
    //                     // $('.PAY').attr('disabled',true);
    //                     $('#amount_modal').val('');
    //                     LoadDataTable();
    //                 } else if (response.status == 504) {
    //                     alert(response.result.data);
    //                     $('#amount_modal').val('');
    //                     LoadDataTable();
    //                 } else {
    //                     alert(response.result.data);
    //                     $('#amount_modal').val('');
    //                 }
    //             },
    //             error: function (e) {
    //                 $('#loader').removeClass('show');
    //                 // console.info(e);
    //                 alert('Data Save Failed !!');
    //                 LoadDataTable();
    //                 $('#btnSave').removeAttr('disabled');
    //             }
    //         });
    //     }
        
    // });

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