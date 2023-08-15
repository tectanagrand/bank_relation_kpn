<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<?php

$CDepartment = '';
foreach ($DtDepartment as $values) {
    $CDepartment .= '<option value="' . $values->DEPARTMENT . '">' . $values->DEPARTEMENTNAME . '</option>';
}

?>

<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Update Payment And Receive</li>
</ol>
<h1 class="page-header">Update Payment And Receive</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Update Payment And Receive</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-row">
                    <div class="col">
                        <label for="COMPANY">Company</label>
                        <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                            <option value="0" selected>All Company</option>
                            <?php
                            foreach ($DtCompany as $values) {
                                echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="DEPARTMENT">Department </label>
                            <select class="form-control" name="DEPARTMENT" id="DEPARTMENT">
                                <option value="" selected>All Department</option>
                                <?php echo $CDepartment; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <label for="COMPANY">Search Vendor</label>
                        <div class="input-group">
                            <select class="form-control mb-2 sVendor" id="sVendor" name="sVendor" >
                                <!-- <option disabled selected>Choose</option> -->
                            </select>
                            <button class="btn btn-dark btn-sm" id="clearSelect2">Clear Vendor</button>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="CASHFLOWTYPE">Payment Type</label>
                                    <select class="form-control" name="CASHFLOWTYPE" id="CASHFLOWTYPE">
                                        <option value="" selected>All</option>
                                        <option value="0">Receive</option>
                                        <option value="1">Payment</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="DATEFROM">Date From</label>
                                    <input type="text" class="form-control" name="DATEFROM" id="DATEFROM" placeholder="MM/DD/YYYY">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="DATETO">Date To</label>
                                    <input type="text" class="form-control" name="DATETO" id="DATETO" placeholder="MM/DD/YYYY">
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row ml-0 mr-0 mb-0 mt-2 table-responsive">
            <table id="DtPayment" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr role="row">
                        <th class="text-center" style="width: 30px;">No</th>
                        <th class="text-center">Department</th>
                        <th class="text-center">Company</th>
                        <th class="text-center">Business Unit</th>
                        <th class="text-center">Doc Number</th>
                        <th class="text-center">Vendor</th>
                        <th class="text-center">Doc Invoice</th>
                        <th class="text-center">Invoice Vendor No</th>
                        <th class="text-center">Voucher</th>
                        <th class="text-center">Date Paid</th>
                        <th class="text-center">Bank</th>
                        <th class="text-center">Remark</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="MAddEdit">
    <div class="modal-dialog" style="max-width: 95%  !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Payment Or Receive</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="FAddEdit" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="COMPANYNAME">Company *</label>
                                <input type="text" class="form-control" name="COMPANYNAME" id="COMPANYNAME" placeholder="Company" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="BANK">Bank *</label>
                                <select class="form-control" name="BANK" id="BANK" required>
                                    <option value="" disabled selected>--Choose Company--</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="VOUCHERNO">Voucher *</label>
                                <input type="text" class="form-control" name="VOUCHERNO" id="VOUCHERNO" placeholder="Voucher" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DATERELEASE">Date Paid *</label>
                                <input type="text" class="form-control" name="DATERELEASE" id="DATERELEASE" placeholder="MM/DD/YYYY" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="NOCEKGIRO">Cek/Giro *</label>
                                <input type="text" class="form-control" name="NOCEKGIRO" id="NOCEKGIRO" placeholder="Cek/Giro" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="REMARK">Remark</label>
                                <input type="text" class="form-control" name="REMARK" id="REMARK" placeholder="Remark">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="AMOUNTINVOICE">Amount Invoice *</label>
                                <input type="text" class="form-control" name="AMOUNTINVOICE" id="AMOUNTINVOICE" placeholder="Amount Invoice" readonly>
                            </div>
                        </div>
                        <!--                        <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="REMARK">Amount Forecast</label>
                                                        <input type="text" class="form-control" name="AMOUNTFORECAST" id="AMOUNTFORECAST" placeholder="Amount Forecast" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="REMARK">Amount Use</label>
                                                        <input type="text" class="form-control" name="AMOUNTUSE" id="REMARK" placeholder="Amount Use" readonly>
                                                    </div>
                                                </div>-->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="REMARK">Amount Paid/Receive</label>
                                <input type="text" class="form-control" name="AMOUNTPAY" id="AMOUNTPAY" placeholder="Amount Paid/Receive">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="Save()">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var USERACCESS = "<?php echo $DtUser2->USERACCESS; ?>";
    var table, PAYMENTID, FORECASTID, CFTRANSID, AMOUNTPAYOLD, COMPANY1, COMPANY, CASHFLOWTYPE;
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
    var ConvertYYYYMMDD = function (data) {
        if (data == "" || data == null || data == undefined) {
            return "";
        } else {
            var dd = data.substr(3, 2);
            var mm = data.substr(0, 2);
            var yyyy = data.substr(6, 4);
            return yyyy + mm + dd;
        }
    };
    $('#DATERELEASE').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "format": "mm/dd/yyyy"
    });
    $('#DATEFROM').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "format": "mm/dd/yyyy"
    });
    $("#DATEFROM").datepicker('setDate', tgl);
    $('#DATETO').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "format": "mm/dd/yyyy"
    });
    var COMPANY = 0;
    var sVendor;
    $("#DATETO").datepicker('setDate', tgl);
    if (!$.fn.DataTable.isDataTable('#DtPayment')) {
        table = $('#DtPayment').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('Payment/ShowDataPaid') ?>",
                "type": "POST",
                "datatype": "JSON",
                "data": function (d) {
                    d.DATEFROM = ConvertYYYYMMDD($("#DATEFROM").val());
                    d.DATETO = ConvertYYYYMMDD($("#DATETO").val());
                    d.USERNAME = USERNAME;
                    d.DEPARTMENT = $('#DEPARTMENT').val();
                    d.CASHFLOWTYPE = $('#CASHFLOWTYPE').val();
                    d.VENDOR   = sVendor;
                    d.COMPANY  = COMPANY;
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
                    $("#loader").addClass('show');
                },
                "complete": function() {
                    $("#loader").removeClass('show');
                }
            },
            "columns": [
                {
                    "data": null,
                    "className": "text-center",
                    "orderable": false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {"data": "DEPARTMENT"},
                {"data": "COMPANYNAME"},
                {"data": "BUSINESSUNITNAME"},
                {"data": "DOCREF"},
                {"data": "VENDORNAME"},
                {"data": "DOCNUMBER"},
                {"data": "INVOICEVENDORNO"},
                {"data": "VOUCHERNO"},
                {"data": "DATERELEASE"},
                {"data": "BANKNAME"},
                {"data": "REMARK"},
                {
                    "data": "AMOUNTPAY",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": null,
                    "className": "text-center",
                    "orderable": false,
                    render: function (data, type, row, meta) {
                        var html = '';
                        if (EDITS == 1) {
                            html += '<button class="btn btn-info btn-sm edit" title="Edit Paid"><i class="fa fa-edit"></i></button>';
                        }
                        if (DELETES == 1) {
                            html += '<button class="btn btn-danger btn-sm delete" title="Delete Paid"><i class="fa fa-trash"></i></button>';
                        }
                        return html;
                    }
                }
            ],
            "search": {
                "regex": true
            }
        });
        table.on('click', '.edit', function () {
            $tr = $(this).closest('tr');
            var data = table.row($tr).data();
            PAYMENTID = data.PAYMENTID;
            FORECASTID = data.FORECASTID;
            CFTRANSID = data.CFTRANSID;
            COMPANY = data.COMPANY;
            CASHFLOWTYPE = data.CASHFLOWTYPE;
            $("#COMPANYNAME").val(data.COMPANYNAME);
            $("#VOUCHERNO").val(data.VOUCHERNO);
//            $("#DATERELEASE").val(data.DATERELEASE);
            $("#DATERELEASE").datepicker('setDate', data.DATERELEASE);
            $("#NOCEKGIRO").val(data.NOCEKGIRO);
            $("#REMARK").val(data.REMARK);
            $("#AMOUNTINVOICE").val(data.AMOUNTINVOICE);
//            $("#AMOUNTFORECAST").val(data.AMOUNTFORECAST);
//            $("#AMOUNTUSE").val(data.AMOUNTUSE);
            $("#AMOUNTPAY").val(data.AMOUNTPAY);
            AMOUNTPAYOLD = data.AMOUNTPAY;
            formatCurrency($('#AMOUNTINVOICE'), "blur");
            formatCurrency($('#AMOUNTPAY'), "blur");
            $('#FAddEdit').parsley().reset();
            $("#MAddEdit .modal-title").text("Edit Payment Or Receive");
            if (COMPANY1 != data.COMPANY) {
                COMPANY1 = data.COMPANY;
                $('#BANK').find('option:not(:first)').remove().end().val('');
                $('#loader').addClass('show');
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Payment/DtBankCompany'); ?>",
                    data: {COMPANY: COMPANY1},
                    success: function (response, textStatus, jqXHR) {
                        $('#loader').removeClass('show');
                        if (response.status == 200) {
                            var html = '';
//                            DValue = '';
                            $.each(response.result.data, function (index, value) {
                                if (value.ISDEFAULT == "1") {
//                                    DValue = value.FCCODE;
                                    html += "<option value='" + value.FCCODE + "'>" + value.BANKACCOUNT + ' - ' + value.FCNAME + ' - ' + value.CURRENCY + ' (Default) </option>';
                                } else {
                                    html += "<option value='" + value.FCCODE + "'>" + value.BANKACCOUNT + ' - ' + value.FCNAME + ' - ' + value.CURRENCY + '</option>';
                                }

                            });
                            $(html).insertAfter("#BANK option:first");
                            $("#BANK").val(data.BANKCODE);
                            $("#MAddEdit").modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                        } else if (response.status == 504) {
                            alert(response.result.data);
                            location.reload();
                        } else {
                            alert(response.result.data);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#loader').removeClass('show');
                        alert('Please Check Your Connection !!!');
                    }
                });
            } else {
                $("#BANK").val(data.BANKCODE);
                $("#MAddEdit").modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        });
        table.on('click', '.delete', function () {
            $tr = $(this).closest('tr');
            var data = table.row($tr).data();
            if (confirm('Are you sure delete this data?')) {
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Payment/DeletePayment'); ?>",
                    data: {
                        PAYMENTID: data.PAYMENTID,
                        COMPANY: data.COMPANY,
                        CASHFLOWTYPE: data.CASHFLOWTYPE,
                        DATERELEASE: data.DATERELEASE
                    },
                    success: function (response) {
                        if (response.status == 200) {
                            alert(response.result.data);
                            DataReload();
                        } else if (response.status == 504) {
                            alert(response.result.data);
                            location.reload();
                        } else {
                            alert(response.result.data);
                        }
                    },
                    error: function (e) {
                        alert('Error deleting data !!');
                    }
                });
            }
        });

        $('#sVendor').on('select2:select', function (e) {
                            sVendor = e.params.data.id;
                            table.ajax.reload();   
                        });

    }

    $(".sVendor").select2({
            allowClear: true,
            placeholder: "Choose Vendor",
            debug:true,
            ajax: {
                url: "<?php echo site_url('Elog/getVendorSend') ?>",
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
                            id:item.VENDORID,
                            text:item.VENDORNAME
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

    function clearSelectedOptions() {
      $('#sVendor').val(null).trigger('change');
    }

    $('#COMPANY').on({
            'change': function () {
                // MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
                // YEAR = this.value.substr(4, 4);
                COMPANY = $(this).val();
                table.ajax.reload();
            }
        });

    $("#clearSelect2").on("click",function(){
        clearSelectedOptions();
        sVendor = '';
        table.ajax.reload();
    });
    
    var DataReload = function () {
        table.ajax.reload();
    };
    $('#DATEFROM').on({
        'change': function () {
            DataReload();
        }
    });
    $('#DATETO').on({
        'change': function () {
            DataReload();
        }
    });
    $('#DEPARTMENT').on({
        'change': function () {
            DataReload();
        }
    });
    $('#CASHFLOWTYPE').on({
        'change': function () {
            DataReload();
        }
    });

    var Save = function () {
        if ($('#FAddEdit').parsley().validate()) {
            if (parseFloat(formatDesimal($('#AMOUNTINVOICE').val())) < parseFloat(formatDesimal($('#AMOUNTPAY').val()))) {
                alert("Amount Pay can't above Amount Invoice !!");
            }
            else if($('#BANK').val() == '' || $('#BANK').val() == '--Choose Company--'){
                alert("Can't empty");
            } else {
                $("#loader").addClass('show');
                $('#FAddEdit button[type="submit"]').attr('disabled', true);
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Payment/EditPayment'); ?>",
                    data: {
                        PAYMENTID: PAYMENTID,
                        CASHFLOWTYPE: CASHFLOWTYPE,
                        COMPANY: COMPANY,
                        VOUCHERNO: $("#VOUCHERNO").val(),
                        DATERELEASE: $("#DATERELEASE").val(),
                        NOCEKGIRO: $("#NOCEKGIRO").val(),
                        REMARK: $("#REMARK").val(),
                        BANKCODE: $("#BANK").val(),
                        AMOUNTPAIDOLD: AMOUNTPAYOLD,
                        AMOUNTPAID: formatDesimal($('#AMOUNTPAY').val()),
                        USERNAME: USERNAME
                    },
                    success: function (response, textStatus, jqXHR) {
                        $("#loader").removeClass('show');
                        $('#FAddEdit button[type="submit"]').removeAttr('disabled');
                        if (response.status == 200) {
                            alert(response.result.data);
                            DataReload();
                            $('#MAddEdit').modal("hide");
                        } else if (response.status == 504) {
                            alert(response.result.data);
                            location.reload();
                        } else {
                            alert(response.result.data);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $("#loader").removeClass('show');
                        $('#FAddEdit button[type="submit"]').removeAttr('disabled');
                        alert('Data Save Failed !!');
                    }
                });
            }
        }
    };
    //    Function - Function Formater Numberic
    $("input[data-type='currency']").on({
        keyup: function () {
            formatCurrency($(this));
        },
        blur: function () {
            formatCurrency($(this), "blur");
        }
    });
    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    function formatCurrency(input, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.
        // get input value
        var input_val = input.val();
        // don't validate empty input
        if (input_val === "") {
            return;
        }
        // original length
        var original_len = input_val.length;
        // initial caret position 
        var caret_pos = input.prop("selectionStart");
        // check for decimal
        if (input_val.indexOf(".") >= 0) {
            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");
            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);
            // add commas to left side of number
            left_side = formatNumber(left_side);
            // validate right side
            right_side = formatNumber(right_side);
            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }
            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);
            // join number by .
            input_val = left_side + "." + right_side;
        } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);
            input_val = input_val;
            // final formatting
            if (blur === "blur") {
                input_val += ".00";
            }
        }
        // send updated string to input
        input.val(input_val);
        // put caret back in the right position
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
//    End Formater Numberic
</script>