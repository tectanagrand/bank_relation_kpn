<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link href="./assets/css/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet">

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
        <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="DEPARTMENT">Department *</label>
                    <select class="form-control mkreadonly" name="DEPARTMENT" id="DEPARTMENT">
                        <?php
                        if (count($departement) > 0) {
                            echo '<option value="" selected>All Department</option>';
                        }
                        foreach ($departement as $values) {
                            echo '<option value=' . $values->DEPARTMENT . '>' . $values->DEPARTEMENTNAME .'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="PERIOD">Period *</label>
                    <input type="text" class="form-control" name="PERIOD" id="PERIOD" placeholder="Period">
                </div>
                <div class="form-group col-md-3">
                    <label for="WEEK">Week *</label>
                    <select class="form-control" name="WEEK" id="WEEK">
                        <option value="" selected>All Week</option>
                    </select>
                </div>
                <div class="col-md-2 offset-md-1">
                    <label for="searchInvoice" class="text-light">Search</label>
                    <input type="text" id="searchInvoice" class="form-control" placeholder="Cari.." >
                </div>
            </div>
            <div class="row ml-0 mr-0 mb-0 mt-2 table-responsive">
                <table id="DtInvoice" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                            <th class="text-center sorting">Department</th>
                            <th class="text-center sorting">Year</th>
                            <th class="text-center sorting">Month</th>
                            <th class="text-center sorting">Company</th>
                            <th class="text-center sorting">Business Unit</th>
                            <th class="text-center sorting">Doc Number</th>
                            <th class="text-center sorting">Vendor</th>
                            <th class="text-center sorting">Doc Invoice</th>
                            <th class="text-center sorting">Due Date</th>
                            <th class="text-center sorting">Amount Invoice</th>
                            <th class="text-center sorting">Week</th>
                            <th class="text-center sorting">Amount Forecast</th>
                            <th class="text-center sorting">Priority</th>
                            <th class="text-center sorting">Amount Paid</th>
                            <th class="text-center sorting">Amount OS</th>
                            <th class="text-center sorting"></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr role="row">
                            <th class="text-right" colspan="10">Total :</th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th></th>
                            <th class="text-right"></th>
                            <th></th>
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
                            <label for="PAY_BANK">Bank *</label>
                            <select class="form-control mkreadonly" name="PAY_BANK" id="PAY_BANK" required>
                                <option value="" selected disabled>Choose Bank</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PAY_GIRO">Cek/Giro/Transfer *</label>
                            <input type="text" class="form-control" name="PAY_GIRO" id="PAY_GIRO" placeholder="Cek/Giro/Transfer" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PAY_VOUCHER">Voucher *</label>
                            <input type="text" class="form-control" name="PAY_VOUCHER" id="PAY_VOUCHER" placeholder="Voucher" required>
                        </div>
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
                            <label for="PAY_PO_NUMBER">PO Number</label>
                            <input type="text" class="form-control" name="PAY_PO_NUMBER" id="PAY_PO_NUMBER" placeholder="PO Number" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PAY_INV_NO">INV No</label>
                            <input type="text" class="form-control" name="PAY_INV_NO" id="PAY_INV_NO" placeholder="INV No" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="PAY_VENDOR">Vendor</label>
                            <input type="text" class="form-control" name="PAY_VENDOR" id="PAY_VENDOR" placeholder="Vendor" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="PAY_AMOUNT_BILL">Amount Bill</label>
                            <input type="text" class="form-control text-right" name="PAY_AMOUNT_BILL" id="PAY_AMOUNT_BILL" placeholder="Amount Bill" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="PAY_PAID_AMOUNT">Paid Amount *</label>
                            <input type="text" class="form-control text-right" name="PAY_PAID_AMOUNT" id="PAY_PAID_AMOUNT" data-type='currency' placeholder="Pay Amount" required>
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

<!-- Modal for View -->
<div class="modal fade" id="PODetailModal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="PODetailTital">Detail Transactions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-secondary fade show">
                    <div class="panel panel-default panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1" >
                        <div class="panel-heading p-0">
                            <!-- begin nav-tabs -->
                            <div class="tab-overflow">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item"><a href="#nav-tab-index-1" data-toggle="tab" class="nav-link active">Detail PO</a></li>
                                    <li class="nav-item"><a href="#nav-tab-index-2" data-toggle="tab" class="nav-link">Invoice</a></li>
                                    <li class="nav-item"><a href="#nav-tab-index-3" data-toggle="tab" class="nav-link">PPh</a></li>
                                </ul>
                            </div>
                            <!-- end nav-tabs -->
                        </div>
                        <div class="tab-content" >
                            <div class="tab-pane fade active show" id="nav-tab-index-1">
                                <div class="row m-0 table-responsive">
                                    <table id="DetailPOList" class="table table-bordered table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DetailPOList_info" style="width: 100%;">
                                        <thead>
                                            <tr role="row">
                                                <th class="text-center sorting_asc" style="width: 30px;">No</th>
                                                <th class="text-center sorting">Material</th>
                                                <th class="text-center sorting">Amount</th>
                                                <th class="text-center sorting">Last Update</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="nav-tab-index-2">
                                <table id="DetailInvoiceList" class="table table-bordered table-hover dataTable" role="grid" width="100%">
                                    <thead>
                                        <tr role="row">
                                            <th class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                                            <th class="text-center sorting">Invoice Number</th>
                                            <th class="text-center sorting">Invoice Date</th>
                                            <th class="text-center sorting">Amount After PPn</th>
                                            <th class="text-center sorting">Remark</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="tab-pane fade show" id="nav-tab-index-3">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="DOCNUMBER">Amount PPh</label>
                                        <input type="text" class="form-control text-right" id="AMOUNT_PPH_IDX" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="DOCNUMBER">Faktur Pajak</label>
                                        <input type="text" class="form-control" id="FAKTUR_PAJAK_IDX" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    var YEAR = '', MONTH = '', WEEK = '', FORECASTID, DEPARTMENT = '';

    $(document).ready(function () {

        $('#DATE_PAID').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "MM/DD/YYYY",
        });

        $(document).on('change', '#DEPARTMENT', function () {            
            DEPARTMENT = $(this).val();
            LoadDataTable()
        });

        $(document).on('change', '#PAY_BANK', function () {        
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('Payment/getBankBalance'); ?>",
                data: {
                    BANK: $(this).val(),
                    YEAR: YEAR,
                    MONTH: MONTH
                },
                success: function (response) {
                    let dataJSON = JSON.parse(response);
                    if (dataJSON.status == 200) {
                        AMOUNTSKRG = dataJSON.result.data.AMOUNTSKRG
                        // USED_BALANCE = dataJSON.result.data.USED_BALANCE
                    } else if (dataJSON.status == 504) {
                        alert(dataJSON.result.data);
                        location.reload();
                    } else {
                        alert(dataJSON.result.data);
                    }
                },
                error: function (e) {
                    console.info(e);
                    alert('Please Check Your Connection !!!');
                }
            });
        });

        $('#PERIOD').datepicker({
            "autoclose": true,
            "todayHighlight": true,
            "viewMode": "months",
            "minViewMode": "months",
            "format": "M yyyy"
        });
        
        $('#PERIOD').on({
            'change': function () {
                var dataYear = $(this).val().split(" ").pop()
                $('#WEEK').find('option:not(:first)').remove().end().val('');
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Forecast/GetWeek'); ?>",
                    data: {
                        PERIOD: $('#PERIOD').val()
                    },
                    success: function (response) {

                        /* LOAD DATA */
                        YEAR = dataYear;
                        MONTH = response.result.data[0].MONTH;
                        
                        LoadDataTable()

                        var html = '';
                        if (response.status == 200) {
                            // html += "<option value='" + JSON.stringify({
                            //         "WEEK": '',
                            //         "YEAR": dataYear,
                            //         "MONTH": response.result.data[0].MONTH
                            //     }) + "'>All Week</option>";
                            $.each(response.result.data, function (index, value) {
                                html += "<option value='"+value.WEEK+"'>" + value.WEEKKET + '</option>';
                            });
                            $(html).insertAfter("#WEEK option:first");
                        } else if (response.status == 504) {
                            alert(response.result.data);
                            location.reload();
                        } else {
                            alert(response.result.data);
                        }
                    },
                    error: function (e) {
                        console.info(e);
                        alert('Please Check Your Connection !!!');
                    }
                });
            }
        });

        $(document).on('change', '#WEEK', function () {
            // var dataJson1 = $.parseJSON($(this).val());
            
            // WEEK = dataJson1.WEEK;
            WEEK = $(this).val();
            LoadDataTable()
        });

        $("input[data-type='currency']").on({
            keyup: function () {
                formatCurrency($(this));
            },
            blur: function () {
                formatCurrency($(this), "blur");
            }
        });

    });

    function LoadDataTable() {
        if (!$.fn.DataTable.isDataTable('#DtInvoice')) {
            $('#DtInvoice').DataTable({
                "processing": true,
                "ajax": {
                    "url": "<?php echo site_url('Payment/SearchInvoice') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function (d) {
                        d.YEAR = YEAR;
                        d.MONTH = MONTH;
                        d.WEEK = WEEK;
                        d.DEPARTMENT = DEPARTMENT;
                        d.USERNAME = USERNAME;
                    },
                    "dataSrc": function (ext) {
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
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {"data": "DEPARTMENT"},
                    {"data": "YEAR"},
                    {"data": "MONTH"},
                    {"data": "COMPANYCODE"},
                    {"data": "BUSINESSUNITCODE"},
                    {
                        "data": "DOCREF",
                        render: function (data, type, row, meta) {
                            return '<a href="javascript:void(0);" class="DOCNUMBER" role="button" title="DOCNUMBER">' + data + '</a>';
                        }
                    },
                    {"data": "VENDORNAME"},
                    {"data": "DOCNUMBER"},
                    {"data": "DUEDATE"},
                    {
                        "data": "AMOUNTINV",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {"data": "WEEK"},
                    {
                        "data": "AMOUNTADJS",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {"data": "PRIORITY"},
                    {
                        "data": "AMOUNTPAID",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNTOUTSTANDING",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            var html = '';
                            html += '<button class="btn btn-info btn-sm edit" title="Pay">\n\
                                        Pay\n\
                                     </button>';
                            return html;
                        }
                    }
                ],
                "bFilter": true,
                "bPaginate": true,
                "bLengthChange": false,
                "bInfo": true,
                // "bFilter": false,
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api(), data;
                    
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                    };

                    // Total over all pages
                    totalAmountSource = api.column(10).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    totalAmountAdjusment = api.column(12).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    totalAmountPaid = api.column(14).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    totalAmountOutstanding = api.column(15).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;

                    $(api.column(10).footer()).html(numFormat(totalAmountSource));
                    $(api.column(12).footer()).html(numFormat(totalAmountAdjusment));
                    $(api.column(14).footer()).html(numFormat(totalAmountPaid));
                    $(api.column(15).footer()).html(numFormat(totalAmountOutstanding));
                }
            });

            $('#DtInvoice_filter').remove()

            $('#searchInvoice').on( 'input', function () {
                table2.search( this.value ).draw();
                console.log(table2, this.value)
            });

            $('#DtInvoice thead th').addClass('text-center');
            table2 = $('#DtInvoice').DataTable();

            table2.on('click', '.edit', function() {
                $('#formAddPayment').parsley().reset();
                $('.dataBankOptions').remove()

                $tr = $(this).closest('tr');
                var data = table2.row($tr).data();
                FORECASTID = data.FFID

                $.ajax({
                    url:"<?= site_url('IBank/GetBankBaseCompany') ?>",
                    method: "POST",
                    data: {COMPANYID: data.COMPANY},
                    success:function(response)
                    {
                        let data = JSON.parse(response)
                        let options = ''
                        data.result.data.forEach( function(value, key) {
                            options += '<option class="dataBankOptions" value="'+ value.FCCODE +'">'+ value.BANKACCOUNT +' - '+ value.FCNAME +'</option>'
                        })
                        $(options).insertAfter("#PAY_BANK option:first");
                    }
                })

                $('#PAY_BANK').val('')
                $('#PAY_GIRO').val('')
                $('#PAY_VOUCHER').val('')
                $('#DATE_PAID2').val('')
                $('#PAY_PO_NUMBER').val(data.DOCREF)
                $('#PAY_INV_NO').val(data.DOCNUMBER)
                $('#PAY_VENDOR').val(data.VENDORNAME)
                formatCurrency($('#PAY_AMOUNT_BILL').val(data.AMOUNTOUTSTANDING), 'blur')
                formatCurrency($('#PAY_PAID_AMOUNT').val(data.AMOUNTOUTSTANDING), 'blur')

                $('#PaymentModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            })

            table2.on('click', '.DOCNUMBER', function() {
                $tr = $(this).closest('tr');
                let data = table2.row($tr).data();

                /* DETAIL PO */
                ViewPO = $('#DetailPOList').DataTable({
                    data:[],
                    columns: [
                        {"data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }},
                        {"data": "FCNAMEMtr"},
                        {
                            "data": "AMOUNT_INCLUDE_VAT",
                            render: $.fn.dataTable.render.number(',', '.', 2)
                        },
                        {"data": "LASTUPDATE"},
                    ],
                    rowCallback: function (row, data) {},
                    filter: true,
                    info: true,
                    // ordering: false,
                    processing: true,
                    retrieve: true
                });
                    
                $.ajax({
                    url: "<?= site_url('EntryPO/ShowDetailPO') ?>",
                    type: "POST",
                    data: {ID: data.POID}
                }).done(function (result) {
                    const resultJSON = JSON.parse(result)
                    ViewPO.clear().draw();
                    ViewPO.rows.add(resultJSON.result.data).draw();
                    }).fail(function (jqXHR, textStatus, errorThrown) { 
                        // needs to implement if it fails
                });
                    
                /* DETAIL INVOICE */
                ViewInvoice = $('#DetailInvoiceList').DataTable({
                    data:[],
                    columns: [
                        {"data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }},
                        {"data": "INV_DOC_NUMBER"},
                        {"data": "INV_DOC_DATE2"},
                        {
                            "data": "INV_AMOUNT",
                            render: $.fn.dataTable.render.number(',', '.', 2)
                        },
                        {"data": "INV_REMARK"},
                    ],
                    rowCallback: function (row, data) {},
                    filter: true,
                    info: true,
                    // ordering: false,
                    processing: true,
                    retrieve: true,
                    initComplete: function(settings, json){
                        if (!data.POVAT || data.POVAT == "0") {
                            var api = new $.fn.dataTable.Api(settings);
                            api.columns([4, 5]).visible(false);
                            $('#changeTextIdx').text('Amount')
                        }
                    }
                });

                $.ajax({
                    url: "<?= site_url('EntryPO/DetailInvoiceList') ?>",
                    type: "POST",
                    data: {ID: data.DOCREF}
                }).done(function (result) {
                    const resultJSON = JSON.parse(result)
                    ViewInvoice.clear().draw();
                    ViewInvoice.rows.add(resultJSON.result.data).draw();
                    }).fail(function (jqXHR, textStatus, errorThrown) { 
                        // needs to implement if it fails
                });                    

                /* DETAIL PPH */
                formatCurrency($('#AMOUNT_PPH_IDX').val(data.POAMOUNT_PPH), 'blur')
                $('#FAKTUR_PAJAK_IDX').val(data.POFAKTUR_PAJAK)

                $('#PODetailModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            })

        } else {
            table2.ajax.reload();
        }
    }

    var SavePayment = function () {
        if ($('#formAddPayment').parsley().validate()) {
            let dataAmount = parseInt(formatDesimal($('#PAY_PAID_AMOUNT').val()))
            let dataAmountBill = parseInt(formatDesimal($('#PAY_AMOUNT_BILL').val()))
            if (dataAmount > dataAmountBill) {
                alert("Paid Amount can't above Amount Bill")
                return false
            } else {
                if (dataAmount <= parseInt(AMOUNTSKRG)) {
                    $.ajax({
                        dataType: "JSON",
                        type: "POST",
                        url: "<?php echo site_url('Payment/Save2'); ?>",
                        data: {
                            FORECASTID: FORECASTID,
                            BANKCODE: $('#PAY_BANK').val(),
                            VOUCHERNO: $('#PAY_VOUCHER').val(),
                            NOCEKGIRO: $('#PAY_GIRO').val(),
                            DATERELEASE: $('#DATE_PAID2').val(),
                            AMOUNT: dataAmount,
                            USERNAME: USERNAME
                        },
                        success: function (response) {
                            $("#loader").hide();
                            $('#btnSave').removeAttr('disabled');
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
                        error: function (e) {
                            console.info(e);
                            alert('Data Save Failed !!');
                        }
                    });
                } else {
                    alert("Insufficient Balance!")
                    return false
                }
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