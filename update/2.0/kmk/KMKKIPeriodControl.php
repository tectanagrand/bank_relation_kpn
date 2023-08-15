<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">KMKKI</li>
</ol>
<h1 class="page-header">KMKKI</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title">KMKKI Period Control</h4>
    </div>
    <div class="panel-body">
            <div class="alert alert-muted fade show" style="width:33%;color: #21282c;background-color: #d8dde0;border-color: #627884;">
            Change Period Control
            </div>
            <div class="row mb-2">
                <div class="col-md-12 mb-2">
                <div class="form-row">
                    <div class="col-4">
                        <label for="COMPANY">Company</label>
                        <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                            <option value="0">All Company</option>
                            <?php
                            foreach ($DtCompany as $values) {
                                echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="PERIOD">Period</label>
                        <input type="text" class="form-control" name="PERIOD" id="PERIOD" placeholder="MMM YYYY" autocomplete="off">
                    </div>
                    <div class="col-4 mt-4">
                        <button type="button" id="ProcessPeriod" class="btn btn-info" style="padding: 3px 10px;">Process</button>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <div class="alert alert-muted fade show" style="width:33%;color: #21282c;background-color: #d8dde0;border-color: #627884;">
            Period Control Table
            </div>
            <div class="col-md-4 pull-right">
                <div class="input-group">
                    <input type="text" id="search" name="search" class="form-control" placeholder="Cari.." >
                </div>
            </div>
            </div>
            <div class="row m-0 table-responsive">
                <table id="DtLeasing" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DtLeasing_info" style="width: 100%;">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc" style="width: 30px;">No</th>
                            <th class="text-center sorting">Company Name</th>
                            <th class="text-center sorting">Current Date</th>
                            <th class="text-center sorting">Current Accounting Year</th>
                            <th class="text-center sorting">Current Accounting Period</th>
                            <th class="text-center sorting">Close Accounting Year</th>
                            <th class="text-center sorting">Close Accounting Period</th>
                            <!-- <th class="text-center sorting_disabled" aria-label="Action"></th> -->
                        </tr>
                    </thead>
                </table>
            </div>
    </div>
    <!-- modal view  -->
    <div class="modal fade" id="MView">
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="COMPANYNAME">Company *</label>
                                    <input type="text" class="form-control" id="VCOMPANYNAME" >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="DEPARTMENT">Department *</label>
                                    <input type="text" class="form-control" id="VDEPARTMENT">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="DOCNUMBER">Doc Number *</label>
                                    <input type="text" class="form-control" id="VDOCNUMBER" >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="DOCDATE">Doc Date *</label>
                                    <input type="text" class="form-control" id="VDOCDATE">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="VENDOR">Vendor *</label>
                                    <input type="text" class="form-control" id="VVENDOR">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="DUEDATE">Due Date Per Month *</label>
                                    <input type="text" class="form-control" id="VDUEDATE_PERMONTH">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="VALID_FROM">Valid From </label>
                                    <input type="text" class="form-control" id="VVALID_FROM">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="VALID_UNTIL">Valid Until </label>
                                    <input type="text" class="form-control" id="VVALID_UNTIL">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="TOTAL_MONTH">Total Month </label>
                                    <input type="text" class="form-control" id="VTOTAL_MONTH">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="CURRENCY">Currency </label>
                                    <input type="text" class="form-control" id="VCURRENCY">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="RATE">Rate </label>
                                    <input type="text" class="form-control" id="VRATE">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="AMOUNT_BEFORE_CONV">Amount Before Conversion </label>
                                    <input type="text" class="form-control" id="VAMOUNT_BEFORE_CONV">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="AMOUNT_AFTER_CONV">Amount After Conversion</label>
                                    <input type="text" class="form-control" id="VAMOUNT_AFTER_CONV">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="BASIC_AMOUNT">Basic Amount</label>
                                    <input type="text" class="form-control" id="VBASIC_AMOUNT">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="INTEREST_PERCENTAGE">Interest Percentage</label>
                                    <input type="text" class="form-control" id="VINTERESTPERCENTAGE">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="INTEREST_AMOUNT">Total Interest Amount Per Year </label>
                                    <input type="text" class="form-control" id="VINTEREST_AMOUNT">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="EXTSYS">Extsys</label>
                                    <input type="text" class="form-control" id="VEXTSYS">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ITEM_CODE">Item</label>
                                    <input type="text" class="form-control" id="VITEM">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="TRANSACTIONMETHOD_BY">Transaction Method</label>
                                    <select id="VTRANSACTIONMETHOD_BY" class="form-control" disabled>
                                        <option value="ANUITAS">ANUITAS</option>
                                        <option value="EFEKTIF">EFEKTIF</option>
                                        <option value="FLAT">FLAT</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    // var DELETES = <?php echo $ACCESS['DELETES']; ?>;
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
    var table, ACTION, ID;
    var YEAR, MONTH, COMPANY;
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    $(document).ready(function () {
        if (!$.fn.DataTable.isDataTable('#DtLeasing')) {
                $('#DtLeasing').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('Kmk/ShowDataPeriod') ?>",
                        "contentType": "application/json",
                        "type": "POST",
                        "data": function (d) {
                            var d = {};
                            return JSON.stringify(d);
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
                        {"data": "COMPANYNAME"},
                        {"data": "CURRENTDATE",
                            "render": function (data) {
                            var d = new Date(data);
                            return d.toLocaleDateString();
                            }
                        },
                        {"data": "CURRENTACCOUNTINGYEAR"},
                        {"data": "CURRENTACCOUNTINGPERIOD"},
                        {"data": "CLOSEACCOUNTINGYEAR"},
                        {"data": "CLOSEACCOUNTINGPERIOD"},
                        
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
                $('#DtLeasing thead th').addClass('text-center');
                table = $('#DtLeasing').DataTable();
                // table.on('click', '.view', function () {
                //     var UUID = $(this).attr('data-id');
                //     $('#loader').addClass('show');
                //     $.ajax({
                //             dataType: "JSON",
                //             type: "POST",
                //             url: "<?php echo site_url('Leasing/viewDetail'); ?>",
                //             data: {
                //                 ID: UUID},
                //             success: function (response) {
                //                 $('#loader').removeClass('show');
                //                 $('#VCOMPANYNAME').val(response[0].COMPANYNAME);
                //                 $('#VDEPARTMENT').val(response[0].DEPARTMENT);
                //                 $('#VDOCNUMBER').val(response[0].DOCNUMBER);
                //                 $('#VDOCDATE').val(response[0].DOCDATE);
                //                 $('#VVENDOR').val(response[0].VENDORNAME);
                //                 $('#VDUEDATE_PERMONTH').val(response[0].DUEDATE_PER_MONTH);
                //                 $('#VVALID_FROM').val(response[0].VALID_FROM);
                //                 $('#VVALID_UNTIL').val(response[0].VALID_UNTIL);
                //                 $('#VTOTAL_MONTH').val(response[0].TOTAL_MONTH);
                //                 $('#VCURRENCY').val(response[0].CURRENCY);
                //                 $('#VRATE').val(response[0].RATE);
                //                 $('#VAMOUNT_BEFORE_CONV').val(fCurrency(response[0].AMOUNT_BEFORE_CONV));
                //                 $('#VAMOUNT_AFTER_CONV').val(fCurrency(response[0].AMOUNT_AFTER_CONV));
                //                 $('#VBASIC_AMOUNT').val(fCurrency(response[0].BASIC_AMOUNT));
                //                 $('#VINTERESTPERCENTAGE').val(response[0].INTEREST_PERCENTAGE);
                //                 $('#VINTEREST_AMOUNT').val(fCurrency(response[0].INTEREST_AMOUNT));
                //                 $('#VITEM').val(response[0].ITEM_NAME);
                //                 $('#VEXTSYS').val(response[0].EXTSYS);
                //                 $('#VTRANSACTIONMETHOD_BY').val(response[0].TRANSACTIONMETHOD_BY);
                //                 $("#MView").modal({
                //                     backdrop: 'static',
                //                     keyboard: false
                //                 });
                //             },
                //             error: function (e) {
                //                 $('#loader').removeClass('show');
                //                 alert('Error Get data !!');
                //             }
                //         });
                // });
                // table.on('click', '.delete', function () {
                //     $tr = $(this).closest('tr');
                //     var data = table.row($tr).data();
                //     if (confirm('Are you sure delete this data "' + data.FCNAME + '" ?')) {
                //         $.ajax({
                //             dataType: "JSON",
                //             type: "POST",
                //             url: "<?php echo site_url('IRegional/Delete'); ?>",
                //             data: {
                //                 FCCODE: data.FCCODE,
                //                 USERNAME: USERNAME
                //             },
                //             success: function (response) {
                //                 if (response.status == 200) {
                //                     alert(response.result.data);
                //                     table.ajax.reload();
                //                 } else if (response.status == 504) {
                //                     alert(response.result.data);
                //                     location.reload();
                //                 } else {
                //                     alert(response.result.data);
                //                 }
                //             },
                //             error: function (e) {
                //                 alert('Error deleting data !!');
                //             }
                //         });
                //     }
                // });
                $("#DtLeasing_filter").remove();
                $("#search").on({
                    'keyup': function () {
                        table.search(this.value, true, false, true).draw();
                    }
                });
            }
    });
        
</script>
<script>
    $('#PERIOD').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "viewMode": "months",
        "minViewMode": "months",
        "format": "M yyyy",
        // "startDate": '-1m',
        "orientation": 'bottom'
    });
    $('#PERIOD').on({
        'change': function () {
            MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
            YEAR = this.value.substr(4, 4);
            COMPANY = $('#COMPANY').val();
        }
    });

    $('#COMPANY').on({
        'change': function () {
            COMPANY = $(this).val();
        }
    });

    $('body').on('click','#ProcessPeriod',function(){
        
        $("#loader").show();
            // table2.ajax.reload();
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Kmk/saveProcessPeriod'); ?>",
                data: {
                    UUID: ID,
                    COMPANY: COMPANY,
                    // INTEREST_PERCENTAGE: $('#INTEREST_PERCENTAGE').val(),
                    YEAR: YEAR,
                    MONTH: MONTH,
                    USERNAME: USERNAME
                },
                success: function (response) {
                    $("#loader").hide();
                    if (response.status == 200) {
                        alert(response.result.data);
                        // $('.PAY').attr('disabled',true);
                        table.ajax.reload();
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        location.reload();
                    } else {
                        alert(response.result.data);
                    }
                },
                error: function (e) {
                    $("#loader").hide();
                    // console.info(e);
                    alert('Data Save Failed !!');
                    $('#btnSave').removeAttr('disabled');
                }
            });
    });
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