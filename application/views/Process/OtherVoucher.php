<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link href="./assets/css/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet">

<style>
    .listdataAjaxVendor {
        max-height: 200px;
        width: 100%;
        background-color: white;
        border: 1px solid #d3d8de;
        white-space: nowrap;
        position: relative;
        overflow-y: scroll;
    }

    .listDataVENDOR {
        padding: 0;
        margin: 0;
    }

    li.AjaxAddedVendor {
        list-style-type: none;
        padding: 3px 10px;
    }

    li.AjaxAddedVendor:hover {
        background-color: #ecdcdc;
        cursor: pointer;
    }
</style>

<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Other Payment</li>
</ol>
<h1 class="page-header">Other Payment</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Other Payment</h4>
    </div>
    <div class="panel-body">
        <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
            <div class="row">
                <div class="form-group col-md-9">
                    <button class="btn btn-primary" onclick="addPayment()">Add</button>
                </div>
                <div class="col-md-2 offset-md-1">
                    <input type="text" id="searchInvoice" class="form-control" placeholder="Cari..">
                </div>
            </div>
            <div class="row ml-0 mr-0 mb-0 mt-2 table-responsive">
                <table id="DtInvoice" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                            <th class="text-center sorting">Payment Type</th>
                            <th class="text-center sorting">Bank</th>
                            <th class="text-center sorting">Vendor</th>
                            <th class="text-center sorting">Voucher</th>
                            <th class="text-center sorting">Giro</th>
                            <th class="text-center sorting">Remark</th>
                            <th class="text-center sorting">Amount</th>
                            <th class="text-center sorting"></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr role="row">
                            <th class="text-right" colspan="7">Total :</th>
                            <th class="text-right"></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="PaymentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Payment Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            </div>
            <div class="modal-body">
                <form id="formAddPayment" data-toggle="validator" role="form">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="DATE_PAID">Date Paid *</label>
                            <div class="input-group date" id="DATE_PAID">
                                <input type="text" class="form-control" name="DATE_PAID" id="DATE_PAID2" required>
                                <div class="input-group-addon input-group-append">
                                    <div class="input-group-text">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PAY_BANK">Company *</label>
                            <select class="form-control" name="COMPANY" id="COMPANY" required>
                                <option value="" selected>Choose Company</option>
                                <?php
                                foreach ($DtCompany as $values) {
                                    echo '<option value=' . $values->ID . '>' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="CASHFLOWTYPE">Forecast Type</label>
                            <select class="form-control" name="CASHFLOWTYPE" id="CASHFLOWTYPE">
                                <option value="" selected>All</option>
                                <option value="0">Receive</option>
                                <option value="1">Payment</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="PAY_BANK">Bank *</label>
                            <select class="form-control mkreadonly" name="PAY_BANK" id="PAY_BANK" required disabled>
                                <option value="" selected>Choose Bank</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="Vendor">Vendor *</label>
                            <input type="text" class="form-control roleaccess" id="VENDORNAME" autocomplete="off">
                            <input type="text" class="form-control" name="VENDOR" id="VENDOR" readonly hidden>
                            <div class="listdataAjaxVendor" style="display: none;">
                                <ul id="listVENDOR" class="listDataVENDOR"></ul>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="PAY_VOUCHER">Voucher *</label>
                            <input type="text" class="form-control" name="PAY_VOUCHER" id="PAY_VOUCHER" placeholder="Voucher">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="PAY_GIRO">Cek/Giro/Transfer *</label>
                            <input type="text" class="form-control" name="PAY_GIRO" id="PAY_GIRO" placeholder="Cek/Giro/Transfer" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="PAY_REMARK">Remark</label>
                            <input type="text" class="form-control" name="PAY_REMARK" id="PAY_REMARK" placeholder="Remark">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="PAY_PAID_AMOUNT">Paid Amount *</label>
                            <input type="text" class="form-control text-right" name="PAY_PAID_AMOUNT" id="PAY_PAID_AMOUNT" data-type='currency' placeholder="Pay Amount" required disabled>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="SavePayment()">Save</button>
            </div>
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
    var AMOUNTSKRG = 0;
    var dataYear = [];
    var PAYMENTID = '';
    var defaultBank;

    // var USED_BALANCE = 0;
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
    var YEAR = '',
        MONTH = '';

    $(document).ready(function() {

        $('#DATE_PAID').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "MM/DD/YYYY",
        });

        $(document).on('change', '#PAY_BANK', function() {
            // getBankBalance($(this).val())
            $('#PAY_PAID_AMOUNT').removeAttr("disabled");
        })

        LoadDataTable()

        $('#DATE_PAID').on('dp.change', function(e) {
            defaultBank = false;
            dataYear = moment(e.date._d).format('M-YYYY').split("-", 2)

            // if (navigator.userAgent.indexOf("Chrome") !== -1){
            //     dataYear = moment(e.date._d).format('M-YYYY').split("-", 2)
            // } else {
            //     /* FOR MOZILA USERS */
            //     dataYear = moment('01 '+e.date._d.replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3")).format('M-YYYY').split("-", 2)
            // }

            $('#COMPANY').removeAttr("disabled");
            $('#COMPANY').val('')

            $('#PAY_PAID_AMOUNT').prop("disabled", true);
            $('#PAY_BANK').prop("disabled", true);
            $('.dataBankOptions').remove()
        })

        $(document).on('change', '#COMPANY', function() {
            showBank($(this).val())
        });

        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            }
        });

        /* SEARCH VENDOR */
        let timeOutonKeyupVendor = null
        $(document).on('input', '#VENDORNAME', function() {
            let dataKeywords = $(this).val()

            clearTimeout(timeOutonKeyupVendor);
            timeOutonKeyupVendor = setTimeout(function() {
                if (dataKeywords) {
                    $.ajax({
                        url: "<?php echo site_url('IVendor/GetDataAjax'); ?>",
                        method: "POST",
                        data: {
                            keywords: dataKeywords
                        },
                        success: function(response) {
                            $('.listdataAjaxVendor').show()
                            $('.AjaxAddedVendor').remove()
                            let data = JSON.parse(response)
                            let options = ''
                            if (data.result.data.length > 0) {
                                data.result.data.forEach(function(value, key) {
                                    options += '<li class="AjaxAddedVendor" data-attr="' + value.ID + '" >' + value.FCNAME + '</li>'
                                })
                            } else {
                                options = '<li class="AjaxAddedVendor">Tidak ada data.</li>'
                            }

                            $('ul#listVENDOR').append(options)
                        }
                    })
                } else {
                    alert('Input supplier tidak boleh kosong!')
                    $('.listdataAjaxVendor').hide()
                    return false
                }
            }, 1000);
        })

        /* SET VENDOR */
        $(document).on('click', '.AjaxAddedVendor', function() {
            SelectedCat = []
            $('.listdataAjaxVendor').hide()
            $('#VENDORNAME').val($(this).text())
            $('#VENDOR').val($(this).attr('data-attr'))
        })

        /* REMOVE MATERIAL CONTAINER */
        $(document).on('click', 'body', function() {
            $('.AjaxAddedVendor').remove()
            $('.listdataAjaxVendor').hide()
        })

    });

    function showBank(CompanyID) {
        $('.dataBankOptions').remove()
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Payment/DtBankCompany'); ?>",
            data: {
                COMPANY: CompanyID
            },
            success: function(response) {
                let data = JSON.parse(response)
                let options = ''
                let selected = '';

                data.result.data.forEach(function(value, key) {
                    selected = (value.ISDEFAULT == 1) ? value.FCCODE : selected;
                    let Default = (value.ISDEFAULT == 1) ? '(Default)' : '';
                    options += '<option class="dataBankOptions" value="' + value.FCCODE + '" ' + selected + '>' + value.BANKACCOUNT + ' - ' + value.FCNAME + ' - ' + value.CURRENCY + ' ' + Default + '</option>'
                })

                $(options).insertAfter("#PAY_BANK option:first");
                $('#PAY_BANK').removeAttr("disabled");
                $('#PAY_PAID_AMOUNT').removeAttr("disabled");

                if (defaultBank) {
                    $('#PAY_BANK').val(defaultBank)
                } else {
                    $('#PAY_BANK').val(selected)
                }
            },
            error: function(e) {
                alert('Please Check Your Connection !!!');
            }
        });
    }

    function addPayment() {
        $('#formAddPayment').parsley().reset();
        $('.dataBankOptions').remove()

        $('#PAY_PAID_AMOUNT').prop("disabled", true);
        $('#PAY_BANK').prop("disabled", true);
        //$('#COMPANY').prop("disabled", true);

        $('#VENDOR').val('');
        $('#VENDORNAME').val('');
        $('#PAY_BANK').val('')
        $('#COMPANY').val('')
        $('#CASHFLOWTYPE').val('')
        $('#PAY_VOUCHER').val('')
        $('#PAY_GIRO').val('')
        $('#DATE_PAID2').val('')
        $('#PAY_REMARK').val('')
        formatCurrency($('#PAY_PAID_AMOUNT').val(''), 'blur')

        $('#PaymentModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    function LoadDataTable() {
        if (!$.fn.DataTable.isDataTable('#DtInvoice')) {
            $('#DtInvoice').DataTable({
                "processing": true,
                "ajax": {
                    "url": "<?php echo site_url('Payment/SearchOtherVoucher') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function(d) {
                        d.USERNAME = USERNAME;
                    },
                    "dataSrc": function(ext) {
                        if (ext.status == 200) {
                            return ext.result.data;
                        } else if (ext.status == 504) {
                            alert(ext.result.data);
                            location.reload();
                            return [];
                        } else {
                            console.info(ext.result.data);
                            return [];
                        }
                    }
                },
                "columns": [{
                        "data": null,
                        "className": "text-center",
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": "CASHFLOWTYPENAME"
                    },
                    {
                        "data": "BANKCODE"
                    },
                    {
                        "data": "VENDORNAME"
                    },
                    {
                        "data": "VOUCHERNO"
                    },
                    {
                        "data": "NOCEKGIRO"
                    },
                    {
                        "data": "REMARKS"
                    },
                    {
                        "className": "text-right",
                        "data": "AMOUNT",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            var html = '';
                            if (EDITS == 1) {
                                html += '<button class="btn btn-success btn-icon btn-circle btn-sm edit" title="Edit" style="margin-right: 5px;">\n\
                                            <i class="fa fa-edit" aria-hidden="true"></i>\n\
                                            </button>';
                            }
                            if (DELETES == 1) {
                                html += '<button class="btn btn-danger btn-icon btn-circle btn-sm delete" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                            }
                            return html;
                        }
                    }
                ],
                "bFilter": true,
                "bPaginate": true,
                "bLengthChange": false,
                "bInfo": true,
                // "bFilter": false,
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;

                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                    // Total over all pages
                    totalAmountSource = api.column(7).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;

                    $(api.column(7).footer()).html(numFormat(totalAmountSource));
                }
            });

            $('#DtInvoice_filter').remove()

            $('#searchInvoice').on('input', function() {
                table2.search(this.value).draw();
            });

            $('#DtInvoice thead th').addClass('text-center');
            table2 = $('#DtInvoice').DataTable();

            table2.on('click', '.edit', function() {
                $tr = $(this).closest('tr');
                var data = table2.row($tr).data();

                $('#formAddPayment').parsley().reset();
                $('.dataBankOptions').remove()

                $('#COMPANY').removeAttr("disabled");

                defaultBank = data.BANKCODE
                PAYMENTID = data.PAYMENTID
                showBank(data.COMPANY)
                if (navigator.userAgent.indexOf("Chrome") !== -1) {
                    dataYear = moment(data.DATERELEASE).format('M-YYYY').split("-", 2)
                } else {
                    /* FOR MOZILA USERS */
                    dataYear = moment('01 ' + data.DATERELEASE.replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3")).format('M-YYYY').split("-", 2)
                }

                $('#COMPANY').val(data.COMPANY)
                $('#CASHFLOWTYPE').val(data.CASHFLOWTYPE)
                $('#PAY_VOUCHER').val(data.VOUCHERNO)
                $('#PAY_GIRO').val(data.NOCEKGIRO)
                $('#VENDOR').val(data.VENDOR);
                $('#VENDORNAME').val(data.VENDORNAME);
                $('#DATE_PAID2').val(moment(data.DATERELEASE).format('MM/DD/YYYY'))
                $('#PAY_REMARK').val(data.REMARKS)
                formatCurrency($('#PAY_PAID_AMOUNT').val(data.AMOUNT), 'blur')

                $('#PaymentModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

            })

            table2.on('click', '.delete', function() {
                $tr = $(this).closest('tr');
                var data = table2.row($tr).data();
                if (confirm('Are you sure delete this data?')) {
                    $.ajax({
                        dataType: "JSON",
                        type: "POST",
                        url: "<?php echo site_url('Payment/DeleteOtherPayment'); ?>",
                        data: {
                            PAYMENTID: data.PAYMENTID
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                alert(response.result.data);
                                table2.ajax.reload();
                            } else if (response.status == 504) {
                                alert(response.result.data);
                                location.reload();
                            } else {
                                alert(response.result.data);
                            }
                        },
                        error: function(e) {
                            alert('Error deleting data !!');
                        }
                    });
                }
            })

        } else {
            table2.ajax.reload();
        }
    }

    var SavePayment = function() {
        if ($('#formAddPayment').parsley().validate()) {
            let dataAmount = formatDesimal($('#PAY_PAID_AMOUNT').val())
            if (dataAmount < 0) {
                alert("Paid Amount must bigger than 0!")
                return false
            } else {
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Payment/SaveOtherPayment'); ?>",
                    data: {
                        PAYMENTID: PAYMENTID,
                        BANKCODE: $('#PAY_BANK').val(),
                        VOUCHERNO: $('#PAY_VOUCHER').val(),
                        NOCEKGIRO: $('#PAY_GIRO').val(),
                        DATERELEASE: $('#DATE_PAID2').val(),
                        REMARK: $('#PAY_REMARK').val(),
                        COMPANY: $('#COMPANY').val(),
                        CASHFLOWTYPE: $('#CASHFLOWTYPE').val(),
                        VENDOR: $('#VENDOR').val(),
                        AMOUNT: dataAmount,
                        USERNAME: USERNAME
                    },
                    success: function(response) {
                        $("#loader").hide();
                        $('#btnSave').removeAttr('disabled');
                        PAYMENTID = ''
                        if (response.status == 200) {

                            alert(response.result.data);
                            $('#PaymentModal').modal("hide");
                            table2.ajax.reload();

                        } else if (response.status == 504) {
                            alert(response.result.data);
                            location.reload();
                        } else {
                            alert(response.result.data);
                        }
                    },
                    error: function(e) {
                        console.info(e);
                        alert('Data Save Failed !!');
                    }
                });
            }
        }
    };

    function formatNumber(n) {
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function formatDesimal(n) {
        return n.replace(/[^0-9.-]+/g, "");
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
</script>