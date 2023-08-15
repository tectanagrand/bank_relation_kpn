<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<link href="./assets/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css" rel="stylesheet" />
<script src="./assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="./assets/plugins/DataTables/extensions/Buttons/js/jszip.min.js"></script>
<script src="./assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
<script src="./assets/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js"></script>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">E-Log</li>
</ol>
<h1 class="page-header">E-Log History Doc</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">E-Log History Doc</h4>
    </div>
    <div class="panel-body">
        <?php if (empty($_GET)) { ?>
            <div class="row mb-2">
                <div class="col-md-4">
                    <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                                <option value="0" selected>All Company</option>
                                <?php
                                foreach ($DtCompany as $values) {
                                    echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                }
                                ?>
                            </select>
                </div>
                <div class="col-md-4 pull-right">
                    <div class="input-group">
                        <input type="text" id="search" name="search" class="form-control" placeholder="Cari.." >
                    </div>
                </div>
            </div>
            <div class="row m-0 table-responsive">
                <table id="DtElog" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" width="100%" aria-describedby="DtElog_info" style="width: 100%;">
                    <thead>
                        <tr role="row">
                            <!-- <th class="text-center sorting_asc" style="width: 30px;">No</th> -->
                            <th class="text-center sorting">Company</th>
                            <!-- <th class="text-center sorting">Receipt Doc</th> -->
                            <th class="text-center sorting">Invoice Code</th>
                            <th class="text-center sorting">No PO</th>
                            <th class="text-center sorting">Vendor</th>
                            <th class="text-center sorting">Currency</th>
                            <th class="text-center sorting">Amount</th>
                            <th class="text-center sorting">Dept</th>
                            <th class="text-center sorting">Updated By</th>
                            <th class="text-center sorting">Date</th>
                            <!-- <th class="text-center sorting">Status</th> -->
                            <th class="text-center sorting_disabled" aria-label="Action">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        <?php } ?>
    </div>
    <!-- modal view  -->
    <div class="modal fade" id="MView">
        <div class="modal-dialog modal-lg" style="max-width: 95%  !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">View Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row m-0 table-responsive">
                        <table id="DtElogDetails" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" width="100%" aria-describedby="DtElog_info" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <!-- <th class="text-center sorting_asc" style="width: 30px;">No</th> -->
                                    <th class="text-center sorting_disabled">No</th>
                                    <!-- <th class="text-center sorting">Receipt Doc</th> -->
                                    <th class="text-center sorting_disabled">Invoice Code</th>
                                    <th class="text-center sorting_disabled">No PO</th>
                                    <th class="text-center sorting_disabled">Vendor</th>
                                    <th class="text-center sorting_disabled">Currency</th>
                                    <th class="text-center sorting_disabled">Amount</th>
                                    <th class="text-center sorting_disabled">Dept</th>
                                    <th class="text-center sorting_disabled">Updated By</th>
                                    <th class="text-center sorting_disabled">Send To</th>
                                    <th class="text-center sorting">Date</th>
                                    <th class="text-center sorting_disabled">Remarks</th>
                                    <th class="text-center sorting_disabled">Status</th>
                                    <!-- <th class="text-center sorting_disabled" aria-label="Action">#</th> -->
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary xModal" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php if (!empty($_GET)) { ?>
        <div class="panel-footer text-left">
            <button type="button" id="btnSave" onclick="SaveMaster()" class="btn btn-primary btn-sm m-l-5">Save</button>
            <button type="button" class="btn btn-warning btn-sm m-l-5" onclick="Cancel()">Cancel</button>
        </div>
    <?php } ?>
</div>

<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var DEPT = "<?php echo $SESSION->DEPARTMENT; ?>";

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
    var COMPANY = 0;
    $(document).ready(function () {
        if (getUrlParameter('type') == "edit" || getUrlParameter('type') == "add") {
            UrlParam = getUrlParameter('type');
            if (getUrlParameter('type') == "add") {
                if (ADDS != 1) {
                    $('#btnSave').remove();
                }
               
            } else {
                if (EDITS != 1) {
                    $('#btnSave').remove();
                }
            }
        } else {
            if (!$.fn.DataTable.isDataTable('#DtElog')) {
                $('#DtElog').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('Elog/HistoryDoc') ?>",
                        "datatype": "JSON",
                        "type": "POST",
                        "data": function (d) {
                            d.COMPANY = COMPANY;
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
                    "columns": [
                    // {
                    //     "data": null,
                    //     "className": "text-center",
                    //     render: function (data, type, row, meta) {
                    //         return meta.row + 1;
                    //     }
                    // },
                    {"data": "COMPANYNAME"},
                    // {"data": "NO_RECEIPT_DOC"},
                    {"data": "INVOICE_CODE"},
                    {"data": "NO_PO"},
                    {"data": "VENDORNAME"},
                    {"data": "CURRENCY"},
                    {
                        "data": "AMOUNT",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {"data": "DEPT"},
                    {"data": "UPDATED_BY"},
                    {"data": "DATE_RECEIPT"},
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            var html = '';
                            html += '<button class="btn btn-info btn-icon btn-circle btn-sm mr-2 btnView" title="view data" data-company="'+data.COMPANYID+'" data-no_inv="'+data.INVOICE_CODE+'" data-norec="'+data.NO_RECEIPT_DOC+'"><i class="fa fa-eye"></i></button>';
                            // if (EDITS == 1) {
                            //     html += '<button class="btn btn-success btn-icon btn-circle btn-sm mr-2 edit" title="Edit" style="margin-right: 5px;">\n\
                            //     <i class="fa fa-edit" aria-hidden="true"></i>\n\
                            //     </button>';
                            // }
                            // if (EDITS == 1) {
                            //     html += '<button class="btn btn-info btn-icon btn-circle btn-sm mr-2 view" title="view data" data-id="'+data.UUID+'"><i class="fa fa-eye"></i></button>';
                            // }
                            // if (DELETES == 1) {
                            //     html += '<button class="btn btn-danger btn-icon btn-circle btn-sm ml-4 delete" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                            // }
                            return html;
                        }
                    }
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
                $('#DtElog thead th').addClass('text-center');
                table = $('#DtElog').DataTable();
                table.on('click', '.export', function () {
                    var DOCNUM = $(this).attr('data-docnumber');
                    var COMP = $(this).attr('data-company');
                    var url = "<?php echo site_url('Elog/exportTransaction'); ?>?COMPANY=PARAM1&DOCNUMBER=PARAM2";
                    url = url.replace("PARAM1", COMP);
                    url = url.replace("PARAM2", DOCNUM);
                    window.open(url, '_blank');
                });
                $("#DtElog_filter").remove();
                $("#search").on({
                    'keyup': function () {
                        table.search(this.value, true, false, true).draw();
                    }
                });
            }
        }

        $('#COMPANY').on({
            'change': function () {
                // MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
                // YEAR = this.value.substr(4, 4);
                COMPANY = $(this).val();
                table.ajax.reload();
            }
        });

    });
    var Add = function () {
        window.location.href = window.location.href + '?type=add';
    };
    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }

    $('body').on('click','.btnView',function(){
        $("#DtElogDetails").dataTable().fnDestroy();
        var COMP = $(this).attr('data-company');
        var INV  = $(this).attr('data-no_inv');
        var NO_RECEIPT_DOC   = $(this).attr('data-norec');
        $('#loader').addClass('show');
        
        $('#DtElogDetails').DataTable({
            dom: 'Bfrtip',
                    "buttons": [{
                            extend: "excel",
                            title: 'Data History Elog',
                            className: "btn-xs btn-green mb-2",
                            text: 'Export To Excel'
                        }],
                "processing": true,
                "bDestroy": true,
                "retrieve": true,
                "ajax": {
                    "url": "<?php echo site_url('Elog/getHistoryDoc'); ?>",
                    "datatype": "JSON",
                    "type": "POST",
                    "data": function (d) {
                        d.COMPANY = COMP;
                        d.INVOICE_CODE = INV;
                        d.NO_RECEIPT_DOC = NO_RECEIPT_DOC;
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
                "columns": [
                {
                    "data": null,
                    "className": "text-center",
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {"data": "INVOICE_CODE"},
                {"data": "NO_PO"},
                {"data": "VENDORNAME"},
                {"data": "CURRENCY"},
                {
                    "data": "AMOUNT",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {"data": "DEPT"},
                {"data": "UPDATED_BY"},
                {"data": "SEND_TO"},
                {"data": "DATE_RECEIPT"},
                {"data": "REMARK"},
                {
                    "data": null,
                    "className": "text-center",
                    "orderable": false,
                        render: function (data, type, row, meta) {
                            var html = '';
                            if(data.POS == 1){
                                html += '<span>Created</span>';
                            }
                            if(data.POS == 2){
                                html += '<span>Sent</span>';
                            }
                            if(data.POS == 3){
                                html += '<span>Received</span>';
                            }
                            if(data.POS == 4){
                                html += '<span>Received and Closed</span>';
                            }
                            return html;
                        }
                }
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
                "bInfo": true
        });
        $('#DtElogDetails thead th').addClass('text-center');
        $('#loader').removeClass('show');
        $('#MView').modal({
            backdrop: 'static',
            keyboard: false
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