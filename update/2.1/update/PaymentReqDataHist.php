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
                        <input type="text" class="form-control" name="PERIOD" id="PERIOD" autocomplete="off" >
                    </div>
                    <!-- <div class="col-4 mt-4">
                        <button type="button" class="btn btn-info" style="padding: 3px 10px;" onclick="searchLeasing()">Search</button>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="row ml-0 mr-0 mb-0 mt-2 table-responsive">
            <table id="DtPaymentReq" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                <thead>
                    <tr role="row">
                        <th class="text-center sorting align-middle" >Company</th>
                        <!-- <th class="text-center sorting align-middle" >Contract Number</th>
                        <th class="text-center sorting align-middle" >PK Number</th> -->
                        <th class="text-center sorting align-middle" >Doc Number</th>
                        <th class="text-center sorting align-middle" >Credit Type</th>
                        <th class="text-center sorting align-middle" >Period</th>
                        <th class="text-center sorting align-middle" >Currency</th>
                        <th class="text-center sorting align-middle" >Amount</th>
                        <th class="text-center sorting align-middle" >Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- modal -->
        <div class="modal fade" id="UploadAttachment" tabindex="-1" role="dialog" aria-labelledby="Upload Attachment" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Doc No - <span id="DOCNUMB"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="FORMUPLOAD" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                    <div class="row form-group">
                       <label for="inputValReal" class="col-3 col-form-label">Billing Value</label>
                       <div class="col-9">
                           <input type="text" class="form-control" data-type='currency' id='BILVAL' required>
                       </div>
                    </div>
                        </br>
                    <div class="row item_form">
                        <div class="col-md-12">
                            <p>Attachment File Payment Invoice</p>
                            <div class="custom-file">
                                <input type="file" class="upload-file" name="userfile" id="ATTACHMENT_FILE" onchange="setFiles(this)">
                                <label class="custom-file-label" for="ATTACHMENT_FILE" id="filename">Choose file</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 form-group align-items-end">
                            <button class="btn btn-primary m-r-3" id="DOWNLOAD">Download File</button>
                        </div>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="Submit" onclick="UploadAttachment()">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- end modal -->
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
    var DOCNUMBER, files, FILENAME, DATE_PAY ;

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
    var table;

    // $(document).ready(function () {
    //     LoadDataTable();
    // });
    var sDate,fDate;

    $(document).ready(function() {
        // function LoadDataTable() {
            if (!$.fn.DataTable.isDataTable('#DtPaymentReq')) {
                $('#DtPaymentReq').DataTable({
                    "processing": true,
                    // dom: 'Bfrtip',
                    // buttons: [
                    //     {
                    //         extend: 'excelHtml5',
                    //         title: 'Report Payment'
                    //     },  
                    // ],
                    "ajax": {
                        "url": "<?php echo site_url('Kmk/ShowPaymentDataHistRequest') ?>",
                        "type": "POST",
                        "datatype": "JSON",
                        "data": function (d) {
                            d.CREDIT_TYPE = $('#CREDIT_TYPE').val();
                            d.PERIOD = $('#PERIOD').val(),
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
                        {"data": "PAY_ID","className": "text-center",},
                        // {"data": "CONTRACT_NUMBER"},
                        // {"data": "PK_NUMBER"},
                        {"data": "CREDIT_TYPE","className": "text-center",},
                        {"data": "PERIOD","className": "text-center",},
                        {"data": "CURRENCY","className": "text-center",},
                        {"data": "AMOUNT","className": "text-center",render: $.fn.dataTable.render.number(',', '.', 2)},
                        {
                            "data": null,
                            "className": "text-center",
                            "orderable": false,
                            render: function(data, type, row, meta) {
                                var html = '';
                                html += '<button class="btn btn-indigo btn-sm upload"  id="UploadAttachmentBtn" title="Receive" data-toggle="modal" data-target="#UploadAttachment">Process</button>' ;
                                // html += '<button class="btn btn-info btn-sm COMPLETION" data-id="'+data.ID+'" data-year="'+data.PERIOD_YEAR+'" data-month="'+data.PERIOD_MONTH+'"  data-docnumber="'+data.DOCNUMBER+'" data-lineno="'+data.LINENO+'"  id="COMPLETION" title="Pay">Execute</button>';
                                return html;
                            }
                        }
                    ],
                });
    
                $('#DtPaymentReq_filter').remove()
    
                $('#search').on( 'input', function () {
                    table.search( this.value ).draw();
                });
    
                $('#DtPaymentReq thead th').addClass('text-center');
                table = $('#DtPaymentReq').DataTable();
                // $("#DtPaymentReq_filter").remove();
                // $("#DOCNUMBER").on({
                //     'keyup': function () {
                //         table.search(this.value, true, false, true).draw();
                //     }
                // });
    
            } else {
                table.ajax.reload();
            }
        // }
        table = $('#DtPaymentReq').DataTable();
        table.on('click', '.upload', function() {
           
            $tr = $(this).closest('tr') ;
            var data = table.row($tr).data();
            DOCNUMBER = data.PAY_ID ;
            FILENAME = data.FILENAME ;
            DATE_PAY = data.DATE_PAY ;
            // console.log(data);
            $('#exampleModalLabel').html("Doc No - " + data.PAY_ID) ;
            if(data.FILENAME == null) {
                $('#BILVAL').val('');
                $('#BILVAL').prop('disabled', false);
                $('#ATTACHMENT_FILE').prop('disabled', false);
                $('#filename').html('Choose File') ;
                $('#Submit').html('Submit');
                $('#Submit').attr('disabled', false);
                $('#DOWNLOAD').prop('disabled', true);
            }
            else {
                $('#BILVAL').val(fCurrency(data.BILLING_VALUE ? data.BILLING_VALUE : '0'));
                $('#BILVAL').attr('disabled', true);
                $('#ATTACHMENT_FILE').prop('disabled', true);
                $('#filename').html(data.FILENAME) ;
                $('#Submit').html('Submitted');
                $('#Submit').attr('disabled', true);
                $('#DOWNLOAD').prop('disabled', false);
            }
        })

        $('#PERIOD').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "format": "m-yyyy",
        "maxViewMode": "months",
        "minViewMode": "months"
    });
        
    })

    function setFiles(elm) {
        var extFile = $('.upload-file').val().split('.').pop().toUpperCase();
        if ($.inArray(extFile, filetypeUpload) === -1) {
            toastr.error('Format file tidak valid');
            files = '';
            $('.upload-file').val('');
            $('#loader').removeClass('show');
            return;
        }
        files = elm.files ;
        FILENAME = files[0].name;
        $('#filename').html(FILENAME) ;
    }

    function UploadAttachment() {
        console.log(files);
        if($('#FORMUPLOAD').parsley().validate()) {
            $('#loader').addClass('show');
            $('#loader').show();
            var extFile = $('.upload-file').val().split('.').pop().toUpperCase();
            if ($.inArray(extFile, filetypeUpload) === -1) {
                toastr.error('Format file tidak valid');
                files = '';
                $('.upload-file').val('');
                $('#loader').removeClass('show');
                return;
            }
            FILENAME = files[0].name;
            var fd = new FormData() ;
            $.each(files, function (i, data) {
                fd.append("userfile", data);
            });
            fd.append('DOCNUMBER', DOCNUMBER);
            fd.append('DATE_PAY', DATE_PAY);
            fd.append('USERNAME', USERNAME);
            fd.append('BILVAL', $('#BILVAL').val());

            // var fileName = $(this).val().split("\\").pop();
            $.ajax({
                dataType : 'JSON',
                type: 'POST',
                url:'<?php echo site_url('Kmk/UploadPaymentBillKMKKI')?>',
                data : fd,
                processData: false,
                contentType: false,
                success : function(response) {
                    if(response.status == 200) {
                        $('#loader').removeClass('show');
                        $('#loader').hide();
                        $('#FORMUPLOAD').parsley().reset();
                        toastr.success(response.result.data) ;
                        $('#filename').html(FILENAME) ;
                    }
                    else if (response.status == 500){
                        $('#loader').removeClass('show');
                        $('#loader').hide();
                        $('#FORMUPLOAD').parsley().reset();
                        toastr.error(response.result.data);
                    }
                    $('#UploadAttachment').modal('hide');
                    table.ajax.reload();
                },
                error : function(e) {
                    console.log(e);
                    $('#loader').removeClass('show');
                    $('#loader').hide();
                    $('#FORMUPLOAD').parsley().reset();
                    toastr.error('Error');
                }
            })
        }
    }
    
    $('#DOWNLOAD').on('click', function() {
        window.open("<?php echo 'http://10.10.10.94:8080/assets/file/'?>" +FILENAME,'_blank');
    })
    var searchLeasing = function () {
        // if ($('#DOCDATE').val() == '' || $('#DOCNUMBER').val() == '' || $('#DOCDATE').val() == null || $('#DOCNUMBER').val() == null) {
        //     alert("'cannot be empty!")
        //     return false
        // } else {
            
        // }
        LoadDataTable();
    }

    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    $('#COMPANY').on({
        'change': function () {
            // MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
            // YEAR = this.value.substr(4, 4);
            // COMPANY = $(this).val();
            table = $('#DtPaymentReq').DataTable();
            table.ajax.reload();
            // LoadDataTable();
        }
    });

    $('#PERIOD').on({
        'change': function () {
            // MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
            // YEAR = this.value.substr(4, 4);
            // COMPANY = $(this).val();
            table = $('#DtPaymentReq').DataTable();
            table.ajax.reload();
            // LoadDataTable();
        }
    });

    $('#CREDIT_TYPE').on({
        'change': function () {
            // MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
            // YEAR = this.value.substr(4, 4);
            // COMPANY = $(this).val();
            table = $('#DtPaymentReq').DataTable();
            table.ajax.reload();
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
    //         // table.ajax.reload();
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