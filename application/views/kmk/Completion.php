<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">KMK</a></li>
    <li class="breadcrumb-item active">Completion</li>
</ol>
<h1 class="page-header">Completion</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Completion</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="form-row">
                    <div class="col-4">
                        <label for="COMPANY">Company</label>
                        <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                            <option value="0">All</option>
                            <?php
                            foreach ($DtCompany as $values) {
                                echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="COMPANY">Credit Type</label>
                        <select class="form-control mkreadonly" name="CREDIT_TYPE" id="CREDIT_TYPE">
                            <option value="0">All</option>
                            <option value="KMK">KMK</option>
                            <option value="KI">KI</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="DOCNUMBER">Search</label>
                        <input type="text" class="form-control" name="search" id="search" autocomplete="off" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="row ml-0 mr-0 mb-0 mt-2 table-responsive">
            <table id="DtCompletion" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                <thead>
                    <tr role="row">
                        <th class="text-center sorting align-middle" >PK Number</th>
                        <th class="text-center sorting align-middle" >Contract Number</th>
                        <th class="text-center sorting align-middle" >Company</th>
                        <th class="text-center sorting align-middle" >Bank</th>
                        <th class="text-center sorting align-middle" >Credit Type</th>
                        <th class="text-center sorting align-middle" >Document Date</th>
                        <th class="text-center sorting align-middle" >Currency</th>
                        <th class="text-center sorting align-middle" >Outstanding</th>
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
                <h5 class="modal-title" id="exampleModalLabel">Contract Number - <span id="CONTRACTNUMBER"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <form id="CompletionForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="amountpenalty">Amount Penalty</label>
                        <input type="text" data-type='currency' class="form-control" name="AMOUNT_PENALTY" id="AMOUNT_PENALTY" autocomplete="off" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="amountpenalty">Outstanding</label>
                        <input type="text" data-type='currency'  class="form-control" name="AMOUNT_OUTSTANDING" id="AMOUNT_OUTSTANDING" autocomplete="off" disabled>
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" id="id">
                    <input type="hidden" id="uuid">
                    <input type="hidden" id="mcontractnumber">
                    <input type="hidden" id="mcompany">
                    <input type="hidden" id="mwdtype">
                    <input type="hidden" id="mbank">
                    <input type="hidden" id="mcredittype">
                    <input type="hidden" id="mdocdate">
                    <input type="hidden" id="mcurrency">
                    <input type="hidden" id="moutstanding">
                    <input type="hidden" id="mamountcompletion">
                    <input type="hidden" id="mdatecompletion">
                    <div class="form-group col-md-6">
                        <label for="INTEREST">Amount Interest</label>
                        <input type="text" data-type='currency' placeholder="" class="form-control" name="AMOUNT_INTEREST" id="AMOUNT_INTEREST" autocomplete="off" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="IDC_INTEREST">Amount IDC Interest</label>
                        <input type="text" data-type='currency' placeholder="" class="form-control" name="AMOUNT_IDC_INTEREST" id="AMOUNT_IDC_INTEREST" autocomplete="off" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="IDC">Amount IDC</label>
                        <input type="text" data-type='currency' placeholder="" class="form-control" name="AMOUNT_IDC" id="AMOUNT_IDC" autocomplete="off" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="COMPANY">Amount By System</label>
                        <input type="text" data-type='currency' placeholder="amount from system" class="form-control" name="from_system" id="from_system" autocomplete="off" disabled>
                    </div>
                    <div class="form-group col-md-6 mb-2">
                        <label for="COMPANY">Date Completion</label>
                        <input type="text" class="form-control" name="MPERIOD" id="MPERIOD" autocomplete="off" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="COMPANY">Amount By User</label>
                        <input type="text" data-type='currency' placeholder="amount" class="form-control" name="amount_modal" id="amount_modal" autocomplete="off" required>
                    </div>
                </div>
            </form>
            </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveModal">Save changes</button>
              </div>
            </div>
          </div>
        </div>
        <!-- end modal -->
    </div>
</div>
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
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


    $(document).ready(function(e){
        $('button.dt-button').addClass('btn');
        $('button.dt-button').addClass('btn-primary');
    });

    var COMPANY = 0;
    var CREDIT_TYPE = 0;
    var DtCompletion = [];

    // function LoadDataTable() {
        if (!$.fn.DataTable.isDataTable('#DtCompletion')) {
            $('#DtCompletion').DataTable({
                "processing": true,
                "ajax": {
                    "url": "<?php echo site_url('Completion/ShowDataCompletion') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function (d) {
                        d.CREDIT_TYPE = $('#CREDIT_TYPE').val();
                        d.COMPANY   = $("#COMPANY").val();
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
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading..n.</span> '
                },
                "columns": [
                    {"data": "PK_NUMBER"},
                    {"data": "CONTRACT_NUMBER"},
                    {"data": "COMPANYNAME"},
                    {"data": "BANK"},
                    {"data": "SUB_CREDIT_TYPE"},
                    {"data": "DOCDATE","className": "text-center",},
                    {"data": "CURRENCY","className": "text-center",},
                    {"data": "OUTSTANDING","className": "text-right",render: $.fn.dataTable.render.number(',', '.', 2)},
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            var html = '';html += '<button class="btn btn-indigo btn-sm ReceiveModal" data-uuid="'+data.UUID+'" data-contractnumber="'+data.CONTRACT_NUMBER+'" data-company="'+data.COMPANYCODE+'" data-credittype="'+data.CREDIT_TYPE+'" data-wdtype="'+data.SUB_CREDIT_TYPE+'" data-docdate="'+data.DOCDATE+'" data-bank="'+data.BANKCODE+'" data-currency="'+data.CURRENCY+'" data-OUTSTANDING="'+data.OUTSTANDING+'" data-TOTALPAID="'+data.TOTAL_PAID+'" id="ReceiveModals" title="Receive" data-toggle="modal" data-target="#ReceiveModal">Create Request</button>';
                            return html;
                        }
                    }
                ],
            });

            $('#DtCompletion_filter').remove()

            $('#search').on( 'input', function () {
                table2.search( this.value ).draw();
            });

            $('#DtCompletion thead th').addClass('text-center');
            table2 = $('#DtCompletion').DataTable();
        } else {
            table2.ajax.reload();
        }
    // }
        var UUID ;
        var CONTRACTNUMBER ;
        var COMPANYCODE ;
        var CREDIT_TYPE ;
        var wdtype ;
        var BANKCODE ;
        var CURRENCY ;
        var DATE_COMPLETION ;
        var OUTSTANDING ;
        var AMOUNT_COMPLETION ;
        var DOCDATE ;
        var AMOUNT_PENALTY ;
        var LASTUPDATE ;

    $('body').on('click','#ReceiveModals',function(){
        $('#AMOUNT_PENALTY').val('') ;
        $('#AMOUNT_INTEREST').val('');
        $('#AMOUNT_PENALTY').val('') ;
        $('#AMOUNT_IDC_INTEREST').val('') ;
        $('#AMOUNT_IDC').val('');
        $('#AMOUNT_OUTSTANDING').val('');
        $('#from_system').text('');
        $('#MPERIOD').val('');
        $('#amount_modal').val('');
        UUID              = $(this).attr('data-uuid');
        CONTRACTNUMBER    = $(this).attr('data-contractnumber');
        COMPANYCODE       = $(this).attr('data-company');
        wdtype            = $(this).attr('data-wdtype');
        BANKCODE          = $(this).attr('data-bank');
        CREDIT_TYPE       = $(this).attr('data-credittype');
        DOCDATE           = $(this).attr('data-docdate');
        CURRENCY          = $(this).attr('data-currency');
        OUTSTANDING       = $(this).attr('data-outstanding');
        $('#uuid').text(UUID);
        $('#CONTRACTNUMBER').text(CONTRACTNUMBER);
        $('#mcompany').text(COMPANYCODE);
        $('#mwdtype').text(wdtype);
        $('#mbank').text(BANKCODE);
        $('#mcredittype').text(CREDIT_TYPE);
        $('#mdocdate').text(DOCDATE);
        $('#mcurrency').text(CURRENCY);
        $('#moutstanding').text(OUTSTANDING);
        $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Completion/GetIDFundsPayment'); ?>",
                data: {
                    UUID: UUID,
                    CONTRACTNUMBER: CONTRACTNUMBER,
                    COMPANYCODE: COMPANYCODE,
                    SUB_CREDIT_TYPE: wdtype,
                    BANKCODE: BANKCODE,
                    CREDIT_TYPE: CREDIT_TYPE,
                    DOCDATE: DOCDATE,
                    OUTSTANDING: OUTSTANDING,
                    CURRENCY: CURRENCY
                },
                success: function (response) {
                    var data = response.result.data;
                    $('#loader').removeClass('show');
                     var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };
                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                    var total_1 = 0 ;
                    var penalty = 0;
                    var outstanding = 0;
                    var interest = 0 ;
                    var idc_interest = 0;
                    var idc = 0;
                    var interest = data.INTEREST;
                    var idc_interest = data.IDC_INTEREST;
                    var idc = data.IDC;
                    // var total = Number(parseFloat(data.PRE_PAYMENT_PENALTY ? data.PRE_PAYMENT_PENALTY : 0)/100 * Number(data.OUTSTANDING)) + Number(data.AMOUNT_COMPLETION) + Number(interest) + Number(idc_interest) + Number(idc);    
                    var total_1 = parseInt(parseFloat(data.PRE_PAYMENT_PENALTY ? data.PRE_PAYMENT_PENALTY : 0)/100 * parseInt(OUTSTANDING)) + parseInt(OUTSTANDING) ;    
                    AMOUNT_COMPLETION = $('#mamountcompletion').text(total_1);
                    var penalty = String(parseFloat(data.PRE_PAYMENT_PENALTY != null ? data.PRE_PAYMENT_PENALTY : 0)/100 * parseInt(OUTSTANDING));
                    var outstanding = OUTSTANDING;
                    $('#from_system').val(CURRENCY+" "+numFormat(total_1));

                    $('#AMOUNT_PENALTY').val(CURRENCY+" "+fCurrency(String(penalty)))
                    $('#AMOUNT_OUTSTANDING').val(CURRENCY+" "+fCurrency(String(outstanding)));
                    $('#AMOUNT_IDC').val(CURRENCY+" "+fCurrency(String(idc)));
                    // $('#AMOUNT_INTEREST').val(fCurrency(String(interest)));
                    // $('#AMOUNT_IDC_INTEREST').val(fCurrency(String(idc_interest)));
                    // $('#AMOUNT_IDC').val(fCurrency(String(idc)));
                    // console.log(total);
                    // $('#from_system').val(numFormat(total));
                },
                error: function (e) {
                    $('#loader').removeClass('show');
                    // console.info(e);
                    alert('Data Save Failed !!');
                    // LoadDataTable();
                    $('#btnSave').removeAttr('disabled');
                }
            });
        // $.ajax({
        //     dataType: "JSON",
        //     type: "POST",
        //     url: "<?php echo site_url('Completion/GetIDFundsPayment'); ?>",
        //     data: {
        //         UUID: UUID,
        //         CONTRACTNUMBER: CONTRACTNUMBER,
        //         COMPANYCODE: COMPANYCODE,
        //         SUB_CREDIT_TYPE: wdtype,
        //         BANKCODE: BANKCODE,
        //         CREDIT_TYPE: CREDIT_TYPE,
        //         DOCDATE: DOCDATE,
        //         OUTSTANDING: OUTSTANDING,
        //         CURRENCY: CURRENCY
        //     },
        //     "dataSrc": function (ext) {
        //         if (ext.status == 200) {
        //             $("#loader").hide();
        //             console.log(ext);
        //             alert(ext.result.data.AMOUNT_COMPLETION);
        //             AMOUNT_COMPLETION = $('#mamountcompletion').text(ext.result.data.AMOUNT_COMPLETION);
        //             return ext.result.data;
        //         } else if (ext.status == 504) {
        //             alert(ext.result.data);
        //             location.reload();
        //             return [];
        //         } else {
        //             console.info(ext.result.data);
        //             return [];
        //         }
        //     },
        // });
    });

    $('body').on('click','#saveModal',function(){

        UUID              = $("#uuid").text();
        CONTRACTNUMBER    = $("#CONTRACTNUMBER").text();
        COMPANYCODE       = $("#mcompany").text();
        CREDIT_TYPE       = $("#mcredittype").text();
        wdtype            = $("#mwdtype").text();
        BANKCODE          = $("#mbank").text();
        CURRENCY          = $("#mcurrency").text();
        //DATE_COMPLETION   = $("#MPERIOD").val();
        OUTSTANDING       = $("#moutstanding").text();
        TOTAL_INSTALLMENT = $("#AMOUNT_OUTSTANDING").val();
        AMOUNT_INTEREST   = $('#AMOUNT_INTEREST').val();
        AMOUNT_IDC_INTEREST = $('#AMOUNT_IDC_INTEREST').val();
        AMOUNT_IDC          = $('#AMOUNT_IDC').val();
        AMOUNT_COMPLETION = $("#amount_modal").val();
        DOCDATE           = $("#mdocdate").text();
        AMOUNT_PENALTY    = $('#AMOUNT_PENALTY').val();
        if($('#CompletionForm').parsley().validate()) {
            if(AMOUNT_COMPLETION == '' || AMOUNT_COMPLETION == null){
                toastr.error("Amount Cant Empty");
            }else{
                if(confirm("Are you sure want to complete this contract?")) {
                    $('#loader').addClass('show');
                    // table2.ajax.reload();
                    $.ajax({
                        dataType: "JSON",
                        type: "POST",
                        url: "<?php echo site_url('Completion/SaveCompletion'); ?>",
                        data: {
                            UUID: UUID,
                            COMPANYCODE: COMPANYCODE,
                            CURRENCY: CURRENCY,
                            CONTRACTNUMBER: CONTRACTNUMBER,
                            BANKCODE: BANKCODE,
                            CREDIT_TYPE: CREDIT_TYPE,
                            SUB_CREDIT_TYPE: wdtype,
                            DOCDATE: DOCDATE,
                            OUTSTANDING: OUTSTANDING,
                            AMOUNT_COMPLETION: AMOUNT_COMPLETION,
                            TOTAL_INSTALLMENT : TOTAL_INSTALLMENT,
                            AMOUNT_PENALTY: AMOUNT_PENALTY,
                            AMOUNT_INTEREST : $('#AMOUNT_INTEREST').val(),
                            AMOUNT_IDC : $('#AMOUNT_IDC').val(),
                            AMOUNT_IDC_INTEREST : $('#AMOUNT_IDC_INTEREST').val(),
                            USERNAME: USERNAME,
                            DATE_COMPLETION: $("#MPERIOD").val()
                        },
                        success: function (response) {
                            $('#loader').removeClass('show');
                            if (response.status == 200) {
                                alert(response.result.data);
                                $('#ReceiveModal').modal('hide');
                                table2.ajax.reload();
                            } else if (response.status == 504) {
                                alert(response.result.data);
                                $('#amount_modal').val('');
                                table2.ajax.reload();
                            } else {
                                alert(response.result.data);
                                table2.ajax.reload();
                                $('#amount_modal').val('');
                            }
                        },
                        error: function (e) {
                            $('#loader').removeClass('show');
                            // console.info(e);
                            alert('Data Save Failed !!');
                            table2.ajax.reload();
                            $('#btnSave').removeAttr('disabled');
                        }
                    });
                }
            }
        }
    });

    var searchLeasing = function () {
        LoadDataTable();
    }

    $('#COMPANY').on({
        'change': function () {
            var table = $('#DtCompletion').DataTable();
            table.ajax.reload();
        }
    });

    $('#CREDIT_TYPE').on({
        'change': function () {
            var table = $('#DtCompletion').DataTable();
            table.ajax.reload();
        }
    });

    $('#MPERIOD').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "format": "mm/dd/yyyy",
    });

    $('#MPERIOD').on('change', function(){
        $DATE = $('#MPERIOD').val();
        $.ajax({
            dataType : 'JSON',
            type : 'POST',
            url : "<?php echo site_url('Completion/GetRemainingInterestByDate')?>",
            data : {
                DATE_COMPLETION : $DATE,
                CONTRACT_NUMBER : CONTRACTNUMBER
            },
            success : function(response) {
                var data = response.result.data ;
                if(data.REMAIN_COUNT != 0) {
                    var remain_int = parseInt(data.REMAINING_INTEREST ? data.REMAINING_INTEREST : 0 ) ;
                    var remain_idcint = parseInt(data.REMAIN_IDCINT ? data.REMAIN_IDCINT : 0) ;
                    $('#AMOUNT_INTEREST').val(CURRENCY+" "+fCurrency(String(remain_int)));
                    $('#AMOUNT_IDC_INTEREST').val(CURRENCY+" "+fCurrency(String(remain_idcint)));
                    var amt_system = parseInt(formatDesimal($('#AMOUNT_PENALTY').val())) + parseInt(formatDesimal($('#AMOUNT_OUTSTANDING').val())) + parseInt(formatDesimal($('#AMOUNT_IDC').val())) 
                    var total = amt_system + remain_int + remain_idcint ;
                    $('#from_system').val(CURRENCY+" "+fCurrency(String(total)));
                    $('#saveModal').attr('disabled', false);
                }
                else {
                    toastr.error('Date Completion Below Latest Paid Installment Date') ;
                    $('#saveModal').attr('disabled', true);
                }
                
            }
        });
    })
</script>


<script type="text/javascript">
    function formatNumber(n) {
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
            right_side = formatDesimal(right_side);
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