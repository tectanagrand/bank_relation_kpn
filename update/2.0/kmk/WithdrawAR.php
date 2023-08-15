<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<!-- <ol class="breadcrumb pull-right">
anel-tianel-titletle    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Leasing</li>
</ol>
<h1 class="page-header">Leasing</h1> -->
<?php 
$CDepartment = '';
foreach ($DtDepartment as $values) {
    $CDepartment .= '<option value="' . $values->DEPARTMENT . '">' . $values->DEPARTEMENTNAME . '</option>';
}
?>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Withdraw Funds</h4>
    </div>
    <div class="panel-body">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#scfndk" data-toggle="tab" class="leasingdata nav-link active">
                    <span class="d-sm-none">Tab 1</span>
                    <span class="d-sm-block d-none">KMK SCF AR </span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active show" id="scfndk">
                <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="fccode">Company *</label>
                            <select class="form-control mkreadonly" name="COMPANY" id="COMPANY" disabled>
                                <option value="0">Select Company</option>
                                <?php
                                foreach ($DtCompany as $values) {
                                    echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fcname">Business Unit *</label>
                            <select class="form-control businessunit" id="BUSINESSUNIT" name="BUSINESSUNIT" required disabled>
                                <!-- <option selected>Select Business Unit</option> -->
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="description">Contract Number </label>
                            <input type="text" class="form-control" id="CONTRACT_NUMBER" required disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="description">PK Number </label>
                            <input type="text" class="form-control" id="PK_NUMBER" required disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Credit Type </label>
                            <select name="CREDIT_TYPE" id="CREDIT_TYPE" class="form-control" disabled>
                                <option value="KMK" selected>Kredit Modal Kerja</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Sub Credit Type *</label>
                            <select name="SUB_CREDIT_TYPE" id="SUB_CREDIT_TYPE" class="form-control" disabled>
                                <option value="" disabled="" selected="">--Choose--</option>
                                <option value="BD">Non Diskonto</option>
                                <option value="RK">Rekening Koran</option>
                                <option value="TL">Time Loan</option>
                                <option value="WA">Withdrawal Approval</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="fcname">Max Limit *</label>
                            <input type="text" class="form-control" name="AMOUNTLIMIT" id="AMOUNTLIMIT" disabled >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fcname">Balance *</label>
                            <input type="text" class="form-control" name="BALANCE" id="BALANCE" disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fcname">Limit WA</label>
                            <div class="input-group">
                                <span class="input-group-addon"><?php echo $DtKmk->CURR_WA?></span>
                                <input type="text" class="form-control" name="LIMIT_WA" id="LIMIT_WA" disabled>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fcname">->  Converted</label>
                            <div class="input-group">
                                <span class="input-group-addon"><?php echo $DtKmk->CURRENCY?></span>
                                <input type="text" class="form-control" name="CONVERTED" id="CONVERTED" disabled>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- create and edit form ar -->
        <!-- <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-primary active">
                <input type="radio" name="options" id="NEW_FORM" value="new" autocomplete="off" checked> NEW FORM
            </label>
            <label class="btn btn-primary">
                <input type="radio" name="options" id="EDIT_FORM" value="edit" autocomplete="off"> EDIT FORM
            </label>
        </div> -->
        <div class="panel panel-success bd d-none">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form id="FSCFAR" data-parsley-validate="true" data-parsley-errors-messages-disabled="" onsubmit="return false" novalidate="">
                    <input id="DATA_EXIST" hidden/>
                        <div class="row">
                            <div class="form-group col-md-4">
                                    <label for="fcname">Value Date</label>
                                    <input type="date" class="form-control" name="VALUE_DATE" id="VALUE_DATE" placeholder="value date" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                    <label for="fcname">Exc. Rate *</label>
                                    <input type="text" class="form-control" name="EXC_RATE" id="EXC_RATE" placeholder="exc. rate" data-type="currency" required/>
                            </div>
                            <div class="form-group col-md-4">
                                    <label for="fcname">Interest *</label>
                                    <input type="number" class="form-control" name="INTEREST" id="INTEREST" placeholder="interest" step="0.000001" required/>
                            </div>
                            <div class="form-group col-md-4">
                                    <label for="fcname">Provision *</label>
                                    <input type="number" class="form-control" name="PROVISION" id="PROVISION" placeholder="provision"  step="0.000001" required/>
                            </div>
                        </div>
                        <div class="row">
                            <!-- <button type="button" id="btnSave" onclick="SaveWD()" class="btn btn-primary btn-sm m-l-5" disabled>Save</button> -->
                            <!-- <button type="button" id="btnEdit" onclick="SaveWD()" class="btn btn-warning btn-sm m-l-5" disabled>Edit</button> -->
                        </div>
                    </form>
                
            </div>
        </div>
        <hr>
        <!-- form detail -->
            <div class="row">
                <div class="col-md-12">
                    <button id="btnDetail" type="button" onclick="AddForm()" class="btn btn-sm btn-info" disabled><i class="fa fa-plus"></i> Add</button>
                </div>
            </div>
            <div class="row m-0 table-responsive">
                <table id="Table_DtWDAR" class="table table-bordered table-hover dataTable" role="grid" width="100%" style="width: 100%;">
                    <thead>
                        <tr role="row">
                            <th class="text-center align-middle">No</th>
                            <th class="text-center align-middle">Vendor</th>
                            <th class="text-center align-middle">Invoice Date</th>
                            <th class="text-center align-middle">Invoice Number</th>
                            <th class="text-center align-middle">BAST Date</th>
                            <th class="text-center align-middle">BAST Number</th>
                            <th class="text-center align-middle">Billing Value</th>
                            <th class="text-center align-middle">Drawdown Amount</th>
                            <th class="text-center align-middle">Payment Date</th>
                            <th class="text-center align-middle">Due Date</th>
                            <th class="text-center align-middle">Total Days</th>
                            <th class="text-center align-middle">Diskonto</th>
                            <th class="text-center align-middle">Net Disbursement</th>
                            <th class="text-center align-middle">Attachment</th>
                            <th>#</th>
                        </tr>
                    </thead>
                </table>`
            </div>
        <hr>
        <!--  -->
        <!-- attachment file -->
        <div class="row mt-2 fileupload-buttonbar">
                        <div class="col-md-4">
                            <strong>Please insert Withdrawal Letter and Invoice</strong>
                            <br/>
                            <label for="address">Attachment *</label>
                            <select name="tipe_file" id="tipe_file" class="form-control mb-2">
                                <option value="WITHDRAWAL_LETTER">WITHDRAWAL LETTER</option>
                                <option value="INVOICE">INVOICE</option>
                            </select>
                            <span class="btn btn-primary fileinput-button m-r-3">
                                <span>Browse File</span>
                                <input type="file" class="upload-file" data-max-size="1048576" onchange="filesChange(this)">
                            </span>
                            <button id="btnReset" type="button" class="btn btn-default m-r-3" onclick="ClearData()" disabled="disabled">
                                <span>Clear Data</span>
                            </button>
                        </div>
                    </div>
                    <div class="row m-0 table-responsive">
                        <table id="DtUpload" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                            <thead>
                                <tr role="row">
                                    <th class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                                    <th class="text-center">Tipe</th>
                                    <th class="text-center">File</th>
                                    <th class="text-center">Submit By</th>
                                    <th class="text-center sorting">Created at</th>
                                    <th class="text-center">#</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
    </div>

     <!-- START MODAL DETAIL -->
     <div class="modal fade" id="MDetail">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Withdrawal Form Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <form id="SCFARDetail" data-parsley-validate="true" data-parsley-errors-messages-disabled  enctype="multipart/form-data" onsubmit="return false">
                    <div class="modal-body">
                        <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="fcname">Vendor <span id="BIC"></span></label>
                                    <select id="VENDOR" name="VENDOR" class="form-control vendor" required> 
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group pull-right">
                                        <label>Invoice Date</label>
                                        <input type="date" class="form-control" id="INV_DATE" name="INV_DATE" required/>
                                    </div>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <div class="form-group">
                                        <label>Invoice Number</label>
                                        <input type="text" class="form-control" id="INV_NUM" name="INV_NUM" required/>
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>BAST Date</label>
                                    <input type="date" class="form-control" id="BAST_DATE" name="BAST_DATE"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>BAST Number</label>
                                    <input type="text" class="form-control" id="BAST_NUM" name="BAST_NUM"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Billing Value</label>
                                    <input type="text" class="form-control" id="BILLING_VAL" name="BILLING_VAL" data-type="currency" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Drawdown Amount</label>
                                    <input type="text" class="form-control" id="DDOWN_AMT" name="DDOWN_AMT" disabled required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>Payment Date</label>
                                <input type="date" class="form-control text-right" id="PAY_DATE" name="PAY_DATE" required/>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Due Date</label>
                                <input type="date" class="form-control text-right" id="DUE_DATE" name="DUE_DATE" required/>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Total Days</label>
                                <input type="text" class="form-control text-right" id="TOTAL_DAYS" name="TOTAL_DAYS" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>Diskonto</label>
                                <input type="text" class="form-control" id="DISKONTO" name="DISKONTO" data-type="currency" required/>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Net Disbursement</label>
                                <input type="text" class="form-control" id="NET_DISBUR" name="NET_DISBUR" data-type="currency" disabled required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Attachment File</label>
                                <select name="FILE_TYPE" id="FILE_TYPE" class="form-control">
                                    <option value="BATCH_INVOICE">Batch Invoice</option>
                                </select>
                                <input type="file" name="ATTACHMENT_WDAR" class="upload-file-wdar"/>
                                <input type="hidden" id="DETID">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="CLOSE" data-dismiss="modal" >Close</button>
                        <button type="submit" class="btn btn-primary" id="SAVE_WDAR" name="SAVE_WDAR" onclick=saveWDAR(ATTACHMENT_WDAR)>Save</button>
                        <input style="display:none;" type="button" class="btn btn-info" id="updateTemp" value="Update">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END MODAL DETAIL -->
    <!-- modal view  -->
    <?php if (!empty($_GET)) { ?>
                        <div class="row panel-footer text-left">
                            <button type="button" class="btn btn-warning btn-sm m-l-5" onclick="Cancel()">Back</button>
                        </div>
                    <?php } ?>
</div>
<!-- -------------------------SCRIPT SECTION-------------------------- -->

<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var DATASCFAR = <?php echo json_encode($DtKmk); ?>;
    var AMOUNT ;
    var BATCHID ;
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
    $(document).ready(function () {
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};
        if (getUrlParameter('type') == "edit" || getUrlParameter('type') == "add") {
            UrlParam = getUrlParameter('type');
            if (getUrlParameter('type') == "add") {
                if (ADDS != 1) {
                    $('#btnSave').remove();
                }
                SetDataKosong(); //placing holder for new add form to empty
            } else {
                if (EDITS != 1) {
                    $('#btnSave').remove();
                }
                var data = <?php echo json_encode($DtKmk); ?>;
                SetData(data); //display existed data on view for editing
                $('#MDetail select').css('width', '100%');
                $('#VENDOR').select2({
                    dropdownParent: $('#MDetail'),
                    placeholder:"Select a vendor",
            // theme: 'bootstrap4',
            ajax: {
                    url: "<?php echo site_url('Leasing/getVendor') ?>",
                    dataType: 'JSON',
                    delay: 250,
                    type: 'GET',
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function(data1, page) {
                        // parse the results into the format expected by Select2.
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data
                        return {
                            results: $.map(data1, function (item) {
                                return {
                                    id:item.ID,
                                    text:item.TEXT,
                                    bic:item.BIC
                                }
                            })
                        };
                    },
                    cache: true,
                    error: function(e){
                        console.info(e);
                    },
                },
                escapeMarkup: function(markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                //templateResult: formatRepo,
                //templateSelection: formatRepoSelection
            });
            $('#VENDOR').on('select2:select', function (e) {
                    var data1 = e.params.data;
                    $('#BIC').text(data.bic);
                });
            }
        } else { //start table display all data here
            if (!$.fn.DataTable.isDataTable('#DtLeasing')) { //not used here
                $('#DtLeasing').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('Kmk/ShowDataWithdraw') ?>",
                        dataType: "JSON",
                        type: "POST",
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
                    {"data": "COMPANYCODE"},
                    {"data": "CONTRACT_NUMBER"},
                    {"data": "PK_NUMBER"},
                    {"data": "CREDIT_TYPE"},
                    {"data": "SUB_CREDIT_TYPE"},
                    {
                        "data": "AMOUNT_LIMIT",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNT_LIMIT",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            var html = '';
                            // console.log(data);
                            // console.log(EDITS);
                            EDITS = 1;
                            if (EDITS == 1) {
                                    html += '<button class="btn btn-info btn-icon btn-circle btn-sm mr-2 edit" title="edit data" data-id="'+data.UUID+'"><i class="fas fa-edit"></i></button>';
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
                table.on('click', '.edit', function() {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    if(data.SUB_CREDIT_TYPE == 'RK' || data.SUB_CREDIT_TYPE ==  'WA' || data.SUB_CREDIT_TYPE ==  'TL'){
                        window.location.href = window.location.href + '?type=edit&UUID=' + data.UUID;    
                    }else{
                        window.location.href = "<?=site_url('WithdrawNDK')?>" + '?type=edit&UUID=' + data.UUID;
                    }
                    
                });
                $("#DtLeasing_filter").remove();
                $("#search").on({
                    'keyup': function () {
                        table.search(this.value, true, false, true).draw();
                    }
                });

            }
        }//end table display all data here
    });

    $('#EXC_RATE').on("change keyup", function() {
        if($("#EXC_RATE").val() != null && $("#EXC_RATE").val() != 0 && $("#INTEREST").val() != null && $("#INTEREST").val() != 0 && $("#PROVISION").val() != null && $("#PROVISION").val() != 0 && $("#VALUE_DATE").val() != null && $("#VALUE_DATE").val() != 0 ) {
            $("#btnDetail").attr('disabled', false);
        }
        else if (($("#EXC_RATE").val() == null || $("#EXC_RATE").val() == 0) && ($("#INTEREST").val() == null || $("#INTEREST").val() == 0 )&& ($("#PROVISION").val() == null || $("#PROVISION").val() == 0) && ($("#VALUE_DATE").val() != null || $("#VALUE_DATE").val() != 0) ){
            $("#btnDetail").attr('disabled', true);
        }
    });
    $('#INTEREST').on("change keyup", function() {
        if($("#EXC_RATE").val() != null && $("#EXC_RATE").val() != 0 && $("#INTEREST").val() != null && $("#INTEREST").val() != 0 && $("#PROVISION").val() != null && $("#PROVISION").val() != 0 && $("#VALUE_DATE").val() != null && $("#VALUE_DATE").val() != 0 ) {
            $("#btnDetail").attr('disabled', false);
                }
        else if (($("#EXC_RATE").val() == null || $("#EXC_RATE").val() == 0) && ($("#INTEREST").val() == null || $("#INTEREST").val() == 0 )&& ($("#PROVISION").val() == null || $("#PROVISION").val() == 0) && ($("#VALUE_DATE").val() != null || $("#VALUE_DATE").val() != 0) ){
            $("#btnDetail").attr('disabled', true);
            }
        });
    $('#PROVISION').on("change keyup", function() {
        if($("#EXC_RATE").val() != null && $("#EXC_RATE").val() != 0 && $("#INTEREST").val() != null && $("#INTEREST").val() != 0 && $("#PROVISION").val() != null && $("#PROVISION").val() != 0 && $("#VALUE_DATE").val() != null && $("#VALUE_DATE").val() != 0 ) {
            $("#btnDetail").attr('disabled', false);
        }
        else if (($("#EXC_RATE").val() == null || $("#EXC_RATE").val() == 0) && ($("#INTEREST").val() == null || $("#INTEREST").val() == 0 )&& ($("#PROVISION").val() == null || $("#PROVISION").val() == 0) && ($("#VALUE_DATE").val() != null || $("#VALUE_DATE").val() != 0) ) {
            $("#btnDetail").attr('disabled', true);
        }
    });
    $('#VALUE_DATE').on("change keyup", function() {
        if($("#EXC_RATE").val() != null && $("#EXC_RATE").val() != 0 && $("#INTEREST").val() != null && $("#INTEREST").val() != 0 && $("#PROVISION").val() != null && $("#PROVISION").val() != 0 && $("#VALUE_DATE").val() != null && $("#VALUE_DATE").val() != 0 ) {
            $("#btnDetail").attr('disabled', false);
        }
        else if (($("#EXC_RATE").val() == null || $("#EXC_RATE").val() == 0) && ($("#INTEREST").val() == null || $("#INTEREST").val() == 0 )&& ($("#PROVISION").val() == null || $("#PROVISION").val() == 0) && ($("#VALUE_DATE").val() != null || $("#VALUE_DATE").val() != 0) ) {
            $("#btnDetail").attr('disabled', true);
        }
    });

    //list all date that have withdrawal data (value date not null in db)
    // $(document).ready(function () {
    //     var valueDateList = [];
    //     $.ajax({
    //         dataType    : "JSON",
    //         type        : "POST",
    //         url         : "KMK/GetAllDataWDAR",
    //         data        : ""

    //     })
    // });
    var Add = function () {
        window.location.href = window.location.href + '?type=add';
    };
    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }
    
    
    /* ---------------------SET OF FUNCTIONS--------------------- */
    function AddForm() {
        ACTIONM = 'ADD';
        VENDOR = "";
        BAST_DATE = "";
        BAST_NUM = "";
        INV_DATE = "";
        INV_NUM = "";
        BIL_VAL = "";
        DDOWN_AMT ="";
        DUE_DATE="";
        $("#BAST_DATE").val('');
        $("#BAST_NUM").val('');
        $("#INV_DATE").val('');
        $("#INV_NUM").val('');
        $('#BILLING_VAL').val('');
        $('#DDOWN_AMT').val('');
        $('#PAY_DATE').val('');
        $('#TOTAL_DAYS').val('');
        $('#NET_DISBUR').val('');
        $('#DUE_DATE').val('');
        $('#MDetail .modal-title').text("Add Data Detail");
        $("#MDetail").modal({
            backdrop: 'static',
            keyboard: false
        });
    }
    

    function SetDataKosong() {
        $('.panel-title').text('Form Withdraw');
        ID = "0";
        $('#COMPANY').val('');
        $('#CREDIT_TYPE').val('');
        $('#SUB_CREDIT_TYPE').val('');
        $('#BANK').val();
        $('#BUSINESSUNIT').val('');
            // $('#ISACTIVE').val('TRUE');
        ACTION = 'ADD';

    }

    function SetData(data) {
        var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
        $('.panel-title').text('Form Data Withdraw');
        ID = data.LID;
        $('#COMPANY option[value='+data.COMPANY+']').attr('selected','selected');
        $('#BUSINESSUNIT').append($('<option>', {
            value: data.BUID,
            text: data.BUFCNAME
        }));
        $('#CREDIT_TYPE option[value='+data.CREDIT_TYPE+']').attr('selected','selected');
        $('#SUB_CREDIT_TYPE option[value='+data.SUB_CREDIT_TYPEMASTER+']').attr('selected','selected');
        $('#PK_NUMBER').val(data.PK_NUMBER);
        $('#CONTRACT_NUMBER').val(data.CONTRACT_NUMBER);
        $('#AMOUNTLIMIT').val(fCurrency(data.AMOUNT_LIMIT));
        $('#LIMIT_WA').val(fCurrency(data.LIMIT_WA));
        
        //Preload existing data
        $.ajax({
            dataType: "JSON",
            type: "POST",
            url: "<?php echo site_url('Kmk/GetDataWDAR');?>",
            data: {
                UUID: DtKmk.UUID,
                WD_TYPE: DATASCFAR.SCT
            },
            success: function(response) {
                data = response.result.data;
                // console.log(data);
                // BATCHID = data.BATCHID ;
                if (data.SUB_BALANCE == null || data.SUB_BALANCE == 0 || data.SUB_BALANCE == '' ) {
                    var currLimit = DtKmk.AMOUNT_LIMIT ;
                    // console.log(currLimit);
                    $('#BALANCE').val(fCurrency(String(currLimit)));    
                } else {
                    var currLimit = data.SUB_BALANCE ;
                    console.log(currLimit);
                    $('#BALANCE').val(fCurrency(String(currLimit)));
                }
                // tbl_WDAR.ajax.reload();
                
                $('#loader').removeClass('show');
            },
            error: function(e) {
                console.log(e);
                alert('Error!');
                $('#loader').removeClass('show');
            }
        });

        ACTION = 'EDIT';
        CREDIT_TYPE = data.CREDIT_TYPE;
        SUB_CREDIT_TYPE = data.SUB_CREDIT_TYPE;
        $('.bd').removeClass('d-none');
            }

    var SaveWD = function () {
        if ($('#FSCFAR').parsley().validate()) {
                $("#loader").addClass('show');
                $('#btnSave').attr('disabled', true);
                $('#btnEdit').attr('disabled', true);
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Kmk/SaveWDAPAR'); ?>",
                    data: {
                        UUID: ID,
                        WD_TYPE: DATASCFAR.SCT,
                        USERNAME: USERNAME,
                        RATE: $('#EXC_RATE').val(),
                        PROVISION: $('#PROVISION').val(),
                        INTEREST: $('#INTEREST').val(),
                        AMOUNT: AMOUNT,
                        AMOUNT_LIMIT: DATASCFAR.AMOUNT_LIMIT,
                        VALUE_DATE: $('#VALUE_DATE').val(),
                        BATCHID : BATCHID,
                        EDITING : $('#DATA_EXIST').val()
                    },
                    success: function (response) {
                        $('#btnSave').removeAttr('disabled');
                        $('#btnEdit').removeAttr('disabled');
                        if (response.status == 200) {
                            toastr.success("Data Successfully Saved");
                            $('#FSCFAR').parsley().reset();
                            // Edit(response.result.data);
                            data = response.result.data;
                            BATCHID = data[1];
                            $.ajax({
                                dataType: "JSON",
                                type: "POST",
                                url: "<?php echo site_url('Kmk/GetDataWDAR');?>",
                                data: {
                                    UUID: DtKmk.UUID,
                                    VALUE_DATE: $('#VALUE_DATE').val(),
                                    WD_TYPE: DATASCFAR.SCT
                                },
                                success: function(o) {
                                    // console.log(o);
                                    d = o.result.data;
                                    var exc_rate = d.RATE ;
                                    var interest = d.INTEREST;
                                    var provision = d.PROVISION ;
                                    var data_exist = d.DATA_EXIST ;
                                    $('#EXC_RATE').val(fCurrency(String(exc_rate)));
                                    $('#INTEREST').val(Number(interest));
                                    $('#PROVISION').val(Number(provision));
                                    $('#DATA_EXIST').val(Number(data_exist));
                                    BATCHID = d.BATCHID ;
                                    // console.log(BATCHID);
                                    // console.log('success');
                                    // console.log($('#DATA_EXIST').val());
                                    tbl_WDAR.ajax.reload();
                                    var html = '';
                                    $('#alert-existed').remove();
                                    if($('#DATA_EXIST').val() == 0) {                                       //Checking whether there is a withdrawal in a date, if exist user may edit. if it isnt user must create a new data
                                        $('#btnSave').attr('disabled', false).removeClass('btn-outline-primary').addClass('btn-primary');
                                        $('#btnEdit').attr('disabled', true).removeClass('btn-warning').addClass('btn-outline-warning');
                                        $('#btnDetail').attr('disabled',true);
                                        $('#alert-existed').remove();
                                        html = '<div class="alert alert-secondary" id="alert-existed" role="alert"> This date doesn\'t have any data, you may save a new withdrawal </div>';
                                        $('#VALUE_DATE').after(html);
                                    } else if ($('#DATA_EXIST').val() == 1){
                                        $('#btnSave').attr('disabled', true).removeClass('btn-primary').addClass('btn-outline-primary');
                                        $('#btnEdit').attr('disabled', false).removeClass('btn-outline-warning').addClass('btn-warning');
                                        $('#btnDetail').attr('disabled',false);
                                        $('#alert-existed').remove();
                                        html = '<div class="alert alert-primary" id="alert-existed" role="alert"> This date already has a withdrawal, you may edit the data </div>';
                                        $('#VALUE_DATE').after(html);
                                    }
                                    $('#loader').removeClass('show');
                                },
                                error: function(e) {
                                    console.log(e);
                                    alert('Error!');
                                    $('#loader').removeClass('show');
                                }
                            });
                            } else if (response.status == 504) {
                                toastr.error(response.result.data);
                                location.reload();
                                $('#FSCFAR').parsley().reset();
                                $('#loader').removeClass('show');
                                $('.upload-file-wdar').val('');
                            } else if (response.status == 500) {
                                toastr.error(response.result.data);
                                $('#FSCFAR').parsley().reset();
                                $('#loader').removeClass('show');
                                $('.upload-file-wdar').val('');
                            } else {
                                toastr.error(response.result.data);
                                $('#FSCFAR').parsley().reset();
                                $('#loader').removeClass('show');
                                $('.upload-file-wdar').val('');
                            }
                        },
                        error : function (e) {
                        console.log(e);
                        toastr.error(response.result.data);
                                $('#FSCFAP').parsley().reset();
                                $('#loader').removeClass('show');
                    }
                });
        }
        
    };
</script>

<script type="text/javascript">
    $(document).ready(function() {

    $("#COMPANY").on({
        'change': function() {
            COMPANY1 = this.value;
            $('#BANK').find('option:not(:first)').remove().end().val('');
                // $('#loader').addClass('show');
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Kmk/DtBankCompany'); ?>",
                    data: {
                        COMPANY: COMPANY1
                    },
                    success: function(response, textStatus, jqXHR) {
                        $('#loader').removeClass('show');
                        if (response.status == 200) {
                            var html = '';
                            DValue = '';
                            $.each(response.result.data, function(index, value) {
                                var CValue = JSON.stringify({
                                    "BANKCODE": value.FCCODE,
                                    "CURRENCY": value.CURRENCY
                                });
                                if (value.ISDEFAULT == "1") {
                                    DValue = CValue;
                                    html += "<option value='" + CValue + "'>" + value.BANKACCOUNT + ' - ' + value.FCNAME + ' - ' + value.CURRENCY + ' (Default) </option>';
                                } else {
                                    html += "<option value='" + CValue + "'>" + value.BANKACCOUNT + ' - ' + value.FCNAME + ' - ' + value.CURRENCY + '</option>';
                                }

                            });
                            $(html).insertAfter("#BANK option:first");
                            $("#BANK").val(DValue);
                            $("#BANK").change();
                        } else if (response.status == 504) {
                            alert(response.result.data);
                            location.reload();
                        } else {
                            alert(response.result.data);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $('#loader').removeClass('show');
                        alert('Please Check Your Connection !!!');
                    }
                });
            }
    });
        
        $("#COMPANY").on({
            'change': function() {
                $('#loader').addClass('show');
                var gID = $(this).val();
            // alert(gFCCODE);
            $.ajax({
                url : "<?php echo site_url('Leasing/getBusinessUnit');?>",
                method : "POST",
                data : {COMPANY: gID},
                async : true,
                dataType : 'json',
                success: function(data){
                    // console.log(data);
                    var listBusinessUnit = '';
                    var i;
                    for(i=0; i<data.length; i++){
                        listBusinessUnit += '<option value='+data[i].ID+'>'+data[i].TEXT+'</option>';
                    }
                    $('#BUSINESSUNIT').html(listBusinessUnit);


                    $('#loader').removeClass('show');

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#loader').removeClass('show');
                    alert('Error!');
                }
            });
                return false;
                DataReload();
            }
        });

    });
</script>

<script type="text/javascript">
    var files, filetypeUpload = ['XLS','XLSX','PDF'];
    var DtUpload = <?php echo json_encode($DtUpload); ?>;
    var DtKmk = <?php echo json_encode($DtKmk); ?>;
    var DtWD_ByUUID = <?php echo json_encode($DtWD_ByUUID);?> ;
    var STATUS = true;
    var tbl_upload;
    var amtModals;
    //FILENAME, tbl_uploadOthers;
    // file upload table manager
    if (!$.fn.DataTable.isDataTable('#DtUpload')) {
        $('#DtUpload').DataTable({
            "bDestory" : true,
            "bRetrieve" : true,
            // "aaData": DtUpload,
            "ajax": {
                    "url": "<?php echo site_url('Kmk/ShowFileData') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function(d) {
                        d.UUID = DtUpload;
                        d.SUB_CREDIT_TYPE = DtKmk['SUB_CREDIT_TYPE'];   
                        // d.DEPARTMENT = $('#DEPARTMENT').val();
                    },
                    "dataSrc": function(ext) {
                        if (ext.status == 200) {
                            ext.draw = ext.result.data;
                            // ext.recordsTotal = ext.result.data.recordsTotal;
                            // ext.recordsFiltered = ext.result.data.recordsFiltered;
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
                        $("#overlay").show();
                    },
                    "complete": function() {
                        $("#overlay").hide();
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
            {"className": "text-center","data":"WD_TIPE"},
            {"className": "text-center","data":"FILENAME"},
            {"className": "text-center","data":"FCENTRY"},
            {"className": "text-center","data":"LASTUPDATE"},
            {
                "data": null,
                "className": "text-center",
                "orderable": false,
                render: function (data, type, row, meta) {
                    var html = '';
                    if (EDITS == 1 ) {
                        html += '<button class="btn btn-success btn-icon btn-circle btn-sm dwn" title="Download" style="margin-right: 5px;">\n\
                        <i class="fas fa-arrow-down" aria-hidden="true"></i>\n\
                        </button>';
                    }
                    if (DELETES == 1 && (getUrlParameter('ACT') == 'view')) {
                        html += '<button class="btn btn-danger btn-icon btn-circle btn-sm delete" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                    }
                    return html;
                }
            }
            ],
                "bFilter": true,
                "bPaginate": true,
                "bLengthChange": true,
                "bInfo": true,
                "responsive": false
            });
        tbl_upload = $('#DtUpload').DataTable();
        tbl_upload.on('click', '.dwn', function() {
            $tr = $(this).closest('tr');
            var data = tbl_upload.row($tr).data();
            window.open("<?php echo base_url('assets/file/')?>" + data.FILENAME,'_blank');
        });
        tbl_upload.on('click', '.delete', function () {
                    $tr = $(this).closest('tr');
                    var data = tbl_upload.row($tr).data();
                    if (confirm('Are you sure delete this data ?')) {
                        $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('Kmk/DeleteFileWD'); ?>",
                            data: {
                                ID: data.ID,
                                FILENAME: data.FILENAME,
                                USERNAME: USERNAME
                            },
                            success: function (response) {
                                if (response.status == 200) {
                                    toastr.success(response.result.data);
                                    reloadUpload();
                                } else if (response.status == 504) {
                                    toastr.error(response.result.data);
                                    location.reload();
                                } else {
                                    toastr.warning(response.result.data);
                                }
                            },
                            error: function (e) {
                                toastr.error('Error deleting data !!');
                            }
                        });
                    }
                });
    }else{
        tbl_upload.ajax.reload();
    }
    
    function DisableBtn() {
        if (files == '' || files == undefined || files == null) {
            $(".fileinput-button").removeClass('disabled');
            $(".upload-file").removeAttr('disabled');
            $("#btnReset").attr('disabled', true);
        } else {
            $(".fileinput-button").addClass('disabled');
            $(".upload-file").attr('disabled', true);
            $("#btnReset").removeAttr('disabled');
        }
    }

    function filesChange(elm) {
        if(ID == null || ID == '' || ID == 0){
            toastr.error('ID KOSONG');
            reloadUpload();
        }
        if($('#tipe_file').val() == null || $('#tipe_file').val() == '' || $('#tipe_file').val() == 0){
            toastr.error('Isi Tipe File');
            reloadUpload();
        }else{
            var fileInput = $('.upload-file');
            var extFile = $('.upload-file').val().split('.').pop().toUpperCase();
            var maxSize = fileInput.data('max-size');
            if ($.inArray(extFile, filetypeUpload) === -1) {
                toastr.error('Format file tidak valid');
                files = '';
                $('.upload-file').val('');
                return;
            }else {
                if (fileInput.get(0).files.length) {
                    var fileSize = fileInput.get(0).files[0].size;
                    if (fileSize > maxSize) {
                        toastr.error('Ukuran file terlalu besar');
                        files = '';
                        $('.upload-file').val('');
                        return;
                    } else {
                        $('#loader').addClass('show');
                        files = elm.files;
                        FILENAME = files[0].name;
                        $(".panel-title_").text('Document Upload : ' + FILENAME);

                        // DisableBtn();
                        var fd = new FormData();
                        // $.each(files, function (i, data) {
                        //     fd.append("userfile", data);
                        // });
                        if($('.upload-file').val() == null || $('.upload-file').val() == '' || $('.upload-file').val() == 0){
                            fd.append("userfile", '');
                            fd.append("FILENAME", '');
                        }else{
                            fd.append("userfile", $('.upload-file')[0].files[0]);
                            fd.append("FILENAME", $('.upload-file')[0].files[0].name);
                        }
                        fd.append("USERNAME", USERNAME);
                        fd.append("UUID",ID);
                        fd.append("TIPE",$('#tipe_file').val());
                        fd.append("SUB_CREDIT_TYPE", DtKmk['SUB_CREDIT_TYPE']);
                        // fd.append("EXTSYSTEM",$('#EXTSYSTEM').val());
                        // fd.append("DOCTYPE",$('#DOCTYPE').val());
                        // fd.append('UUID',UUID)
                        // fd.append('DATERELEASE',currentDate);
                        $.ajax({
                            dataType: "JSON",
                            type: 'POST',
                            url: "<?php echo site_url('Kmk/uploadWDFile'); ?>",
                            data: fd,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $('#page-container').addClass('page-sidebar-minified');
                                $('#loader').removeClass('show');
                                if (response.status == 200) {
                                    STATUS = true;
                                    // DtUpload = response.result.data;
                                    toastr.success('Upload Success');
                                    // $('#DtUpload').removeClass('d-none');
                                    $('#btnReset').removeAttr('disabled');
                                    reloadUpload();
                                } else if (response.status == 504) {
                                    toastr.error(response.result.data);
                                    $('#btnReset').removeAttr('disabled');
                                    reloadUpload();
                                } else {
                                    toastr.error(response.result.data);
                                    files = '';
                                    $('.upload-file').val('');
                                    $(".panel-title_").text('Upload Document');
                                    $('#btnReset').removeAttr('disabled');
                                    // DisableBtn();
                                }
                            },
                            error: function (e) {
                                console.info(e);
                                $('#loader').removeClass('show');
                                // alert('Error Upload Data !!');
                                toastr.error('Error Upload Data !!');
                                files = '';
                                $('.upload-file').val('');
                                $(".panel-title_").text('Upload Document');
                                // DisableBtn();
                            }
                        });
                        
                    }
                }
            }
        }
    }

    function saveWDAR(elm) {
        if($('#SCFARDetail').parsley().validate()){
            var current_limit = formatDesimal($('#AMOUNTLIMIT').val());
            var bil_val = formatDesimal($('#BILLING_VAL').val());
            if(ID == null || ID == '' || ID == 0){
                toastr.error('ID KOSONG');
                reloadUploadWDAR();
            }
            else if($('#tipe_file').val() == null || $('#tipe_file').val() == '' || $('#tipe_file').val() == 0){
                toastr.error('Isi Tipe File');
            }
            else if(Number(bil_val) > Number(current_limit)) {
                toastr.error('Penarikan Lebih Besar dari Limit');
            }
            else{
                var fileInput = $('.upload-file-wdar');
                var extFile = $('.upload-file-wdar').val().split('.').pop().toUpperCase();
                var maxSize = fileInput.data('max-size');
                if ($.inArray(extFile, filetypeUpload) === -1) {
                    toastr.error('Format file tidak valid');
                    files = '';
                    $('.upload-file-wdar').val('');
                    return;
                }else {
                    if (fileInput.get(0).files.length) {
                        var fileSize = fileInput.get(0).files[0].size;
                        if (fileSize > maxSize) {
                            toastr.error('Ukuran file terlalu besar');
                            files = '';
                            $('.upload-file-wdar').val('');
                            return;
                        } else {
                            $('#loader').addClass('show');
                            files = elm.files;
                            FILENAME = files[0].name;
                            $(".panel-title_").text('Document Upload : ' + FILENAME);

                            // DisableBtn();
                            var mnfd = new FormData();
                            var fd = new FormData();
                            $.each(files, function (i, data) {
                                fd.append("userfile", data);
                            });
                            
                            // fd.append("EXTSYSTEM",$('#EXTSYSTEM').val());
                            // fd.append("DOCTYPE",$('#DOCTYPE').val());
                            // fd.append('UUID',UUID)
                            // fd.append('DATERELEASE',currentDate);
                            fd.append("USERNAME", USERNAME);
                            fd.append('ID', $('#DETID').val());
                            fd.append("UUID",ID);
                            fd.append("VALUE_DATE", $('#VALUE_DATE').val());
                            fd.append("FILENAME", FILENAME);
                            fd.append("TIPE",$('#tipe_file').val());
                            fd.append("VENDOR",  $("#VENDOR").val());
                            fd.append("TOTAL_DAYS", $('#TOTAL_DAYS').val());
                            fd.append("PAY_DATE",  $("#PAY_DATE").val());
                            fd.append("BAST_DATE", $("#BAST_DATE").val());
                            fd.append("BAST_NUM", $("#BAST_NUM").val());
                            fd.append("INV_DATE", $("#INV_DATE").val());
                            fd.append("INV_NUM", $("#INV_NUM").val());
                            fd.append("BILLING_VAL", $('#BILLING_VAL').val());
                            fd.append("DDOWN_AMT", $('#DDOWN_AMT').val());
                            fd.append("DUE_DATE", $('#DUE_DATE').val());
                            fd.append("NET_DISBUR", $('#NET_DISBUR').val());
                            fd.append("DISKONTO", $('#DISKONTO').val());
                            fd.append("AMTMODALS", amtModals);
                            fd.append("WD_TYPE", "KMK_SCF_AR");
                            AMOUNT = Number(formatDesimal($('#DDOWN_AMT').val()));
                            // console.log(BATCHID);
                            if(BATCHID == null) {
                                $.ajax({
                                    dataType: "JSON",
                                    type: "POST",
                                    url: "<?php echo site_url('Kmk/SaveWDAPAR'); ?>",
                                    data: {
                                        UUID: ID,
                                        WD_TYPE: DATASCFAR.SCT,
                                        USERNAME: USERNAME,
                                        RATE: $('#EXC_RATE').val(),
                                        PROVISION: $('#PROVISION').val(),
                                        INTEREST: $('#INTEREST').val(),
                                        AMOUNT: AMOUNT,
                                        AMOUNT_LIMIT: DATASCFAR.AMOUNT_LIMIT,
                                        VALUE_DATE: $('#VALUE_DATE').val(),
                                        BATCHID : BATCHID,
                                        EDITING : $('#DATA_EXIST').val(),
                                        CONTRACT_NUMBER : $('#CONTRACT_NUMBER').val()
                                    },
                                    success: function (response) {
                                        $('#btnSave').removeAttr('disabled');
                                        $('#btnEdit').removeAttr('disabled');
                                        if (response.status == 200) {
                                            toastr.success("Data Successfully Saved");
                                            $('#FSCFAR').parsley().reset();
                                            // Edit(response.result.data);
                                            data = response.result.data;
                                            BATCHID = data[1];
                                            // console.log(data[1]);
                                            fd.append("BATCHID", BATCHID);
                                            $.ajax({
                                                dataType: "JSON",
                                                type: 'POST',
                                                url: "<?php echo site_url('Kmk/SaveWDARDet'); ?>",
                                                data: fd,
                                                processData: false,
                                                contentType: false,
                                                success: function (response) {
                                                    $('#page-container').addClass('page-sidebar-minified');
                                                    $('#loader').removeClass('show');
                                                    if (response.status == 200) {
                                                        STATUS = true;
                                                    $.ajax({
                                                        dataType: "JSON",
                                                        type: "POST",
                                                        url: "<?php echo site_url('Kmk/GetDataWDAR');?>",
                                                        data: {
                                                            UUID: DtKmk.UUID,
                                                            WD_TYPE: DATASCFAR.SCT
                                                        },
                                                        success: function(response) {
                                                            data = response.result.data;
                                                            var exc_rate = data.RATE ;
                                                            var interest = data.INTEREST;
                                                            var provision = data.PROVISION ;
                                                            var data_exist = data.DATA_EXIST ;
                                                            $('#EXC_RATE').val(fCurrency(String(exc_rate)));
                                                            $('#INTEREST').val(Number(interest));
                                                            $('#PROVISION').val(Number(provision));
                                                            $('#DATA_EXIST').val(Number(data_exist));
                                                            $('#VALUE_DATE').val(data.VAL_DATE);
                                                            // BATCHID = data.BATCHID ;
                                                            if (data.SUB_BALANCE == null || data.SUB_BALANCE == 0 || data.SUB_BALANCE == '') {
                                                                var currLimit = DtKmk.AMOUNT_LIMIT - (AMOUNT ? AMOUNT : 0) ;
                                                                $('#BALANCE').val(fCurrency(String(currLimit)));    
                                                            } else {
                                                                var currLimit = data.SUB_BALANCE - (AMOUNT ? AMOUNT : 0) ;
                                                                $('#BALANCE').val(fCurrency(String(currLimit)));
                                                            }
                                                            // console.log(BATCHID);
                                                            // console.log('success');
                                                            // console.log($('#DATA_EXIST').val());
                                                            tbl_WDAR.ajax.reload();
                                                            var html = '';
                                                            $('#loader').removeClass('show');
                                                        },
                                                        error: function(e) {
                                                            console.log(e);
                                                            alert('Error!');
                                                            $('#loader').removeClass('show');
                                                        }
                                                    });
                                                        // DtUpload = response.result.data;
                                                        $('#MDetail').on('hidden.bs.modal', function(e) {
                                                            $(this).find('#SCFARDetail')[0].reset();
                                                        });
                                                        $('#SCFARDetail').parsley().reset();
                                                        // $('#DtUpload').removeClass('d-none');
                                                        $('#MDetail').modal('hide');
                                                        toastr.success('Upload Success');
                                                        // $('#DtUpload').removeClass('d-none');
                                                        $('#btnReset').removeAttr('disabled');
                                                        reloadUploadWDAR();
                                                    } else if (response.status == 504) {
                                                        toastr.error(response.result.data);
                                                        $('#btnReset').removeAttr('disabled');
                                                        reloadUploadWDAR();
                                                    } else if (response.status == 500) {
                                                        // console.log(response.result.data);
                                                        toastr.error(response.result.data);
                                                        $('#btnReset').removeAttr('disabled');
                                                        reloadUploadWDAR();
                                                    } else {
                                                        toastr.error(response.result.data);
                                                        files = '';
                                                        $('.upload-file-wdar').val('');
                                                        $(".panel-title_").text('Upload Document');
                                                        $('#btnReset').removeAttr('disabled');
                                                        // DisableBtn();
                                                    }
                                                },
                                                error: function (e) {
                                                    console.info(e);
                                                    $('#loader').removeClass('show');
                                                    // alert('Error Upload Data !!');
                                                    toastr.error('Error Upload Data !!');
                                                    files = '';
                                                    for (var pair of fd.entries()) {
                                                        console.log(pair[0]+ ', ' + pair[1]); 
                                                    }
                                                    $('.upload-file-wdar').val('');
                                                    $(".panel-title_").text('Upload Document');
                                                    // DisableBtn();
                                                }
                                            });
                                        } else if (response.status == 504) {
                                            $('#loader').removeClass('show');
                                            toastr.error(response.result.data);
                                            $('#btnReset').removeAttr('disabled');
                                            $('#SCFARDetail').parsley().reset();
                                            // reloadUploadWDAR();
                                        } else if (response.status == 500) {
                                            $('#loader').removeClass('show');
                                            toastr.error(response.result.data);
                                            $('#btnReset').removeAttr('disabled');
                                            $('#SCFARDetail').parsley().reset();
                                            // reloadUploadWDAR();
                                        } else {
                                            toastr.error(response.result.data);
                                            files = '';
                                            $('.upload-file-wdar').val('');
                                            $(".panel-title_").text('Upload Document');
                                            $('#btnReset').removeAttr('disabled');
                                            $('#SCFARDetail').parsley().reset();
                                            // DisableBtn();
                                        }
                                    }, error : function (e) {
                                        console.info(e);
                                        $('#loader').removeClass('show');
                                        // alert('Error Upload Data !!');
                                        toastr.error('Error Upload Data !!');
                                        files = '';
                                        for (var pair of fd.entries()) {
                                            console.log(pair[0]+ ', ' + pair[1]); 
                                        }
                                        $('.upload-file-wdar').val('');
                                        $(".panel-title_").text('Upload Document');
                                        // DisableBtn();
                                    }
                                });
                            }
                            else {
                                //when it is already exist the main record on funds_withdraw
                                fd.append('BATCHID', BATCHID);
                                $.ajax({
                                    dataType: "JSON",
                                    type: 'POST',
                                    url: "<?php echo site_url('Kmk/SaveWDARDet'); ?>",
                                    data: fd,
                                    processData: false,
                                    contentType: false,
                                    success: function (response) {
                                        $('#page-container').addClass('page-sidebar-minified');
                                        $('#loader').removeClass('show');
                                        if (response.status == 200) {
                                            STATUS = true;
                                        $.ajax({
                                            dataType: "JSON",
                                            type: "POST",
                                            url: "<?php echo site_url('Kmk/GetDataWDAR');?>",
                                            data: {
                                                UUID: DtKmk.UUID,
                                                WD_TYPE: DATASCFAR.SCT
                                            },
                                            success: function(response) {
                                                data = response.result.data;
                                                var exc_rate = data.RATE ;
                                                var interest = data.INTEREST;
                                                var provision = data.PROVISION ;
                                                var data_exist = data.DATA_EXIST ;
                                                $('#EXC_RATE').val(fCurrency(String(exc_rate)));
                                                $('#INTEREST').val(Number(interest));
                                                $('#PROVISION').val(Number(provision));
                                                $('#DATA_EXIST').val(Number(data_exist));
                                                $('#VALUE_DATE').val(data.VAL_DATE);
                                                // BATCHID = data.BATCHID ;
                                                if (data.SUB_BALANCE == null || data.SUB_BALANCE == 0 || data.SUB_BALANCE == '') {
                                                    var currLimit = DtKmk.AMOUNT_LIMIT - (data.AMOUNT ? data.AMOUNT : 0) ;
                                                    $('#BALANCE').val(fCurrency(String(currLimit)));    
                                                } else {
                                                    var currLimit = data.SUB_BALANCE - (data.AMOUNT ? data.AMOUNT : 0) ;
                                                    $('#BALANCE').val(fCurrency(String(currLimit)));
                                            }
                                                // console.log(BATCHID);
                                                // console.log('success');
                                                // console.log($('#DATA_EXIST').val());
                                                tbl_WDAR.ajax.reload();
                                                var html = '';
                                               
                                                $('#loader').removeClass('show');
                                            },
                                            error: function(e) {
                                                console.log(e);
                                                alert('Error!');
                                                $('#loader').removeClass('show');
                                            }
                                        });
                                            // DtUpload = response.result.data;
                                            $('#MDetail').on('hidden.bs.modal', function(e) {
                                                $(this).find('#SCFARDetail')[0].reset();
                                            });
                                            $('#SCFARDetail').parsley().reset();
                                            // $('#DtUpload').removeClass('d-none');
                                            $('#MDetail').modal('hide');
                                            toastr.success('Upload Success');
                                            // $('#DtUpload').removeClass('d-none');
                                            $('#btnReset').removeAttr('disabled');
                                            reloadUploadWDAR();
                                        } else if (response.status == 504) {
                                            toastr.error(response.result.data);
                                            $('#btnReset').removeAttr('disabled');
                                            reloadUploadWDAR();
                                        } else {
                                            toastr.error(response.result.data);
                                            files = '';
                                            $('.upload-file-wdar').val('');
                                            $(".panel-title_").text('Upload Document');
                                            $('#btnReset').removeAttr('disabled');
                                            // DisableBtn();
                                        }
                                    },
                                    error: function (e) {
                                        console.info(e);
                                        $('#loader').removeClass('show');
                                        // alert('Error Upload Data !!');
                                        toastr.error('Error Upload Data !!');
                                        files = '';
                                        for (var pair of fd.entries()) {
                                            console.log(pair[0]+ ', ' + pair[1]); 
                                        }
                                        $('.upload-file-wdar').val('');
                                        $(".panel-title_").text('Upload Document');
                                        // DisableBtn();
                                    }
                                });
                            }
                            
                            
                        }
                    }
                }
            }
        }
    }

    $('#BILLING_VAL').on('change keyup', function () {
        var bilVal = Number(formatDesimal($('#BILLING_VAL').val()));
        var rem_limit = Number(formatDesimal($('#BALANCE').val())) ? Number(formatDesimal($('#BALANCE').val())) :Number(formatDesimal($('#AMOUNTLIMIT').val()));
        var ddownAmt = Number(bilVal) * 95 / 100 ;
        $('#DDOWN_AMT').val(fCurrency(String(ddownAmt)));
        if(ddownAmt > rem_limit) {
            $('#MDetail .btn-primary ').prop('disabled', true);
            toastr.error('Over Limit !!');
        }
        else {
            $('#MDetail .btn-primary').prop('disabled', false);
        }
        var perc_prov = Number($('#PROVISION').val()) / 100 ;
        var provision = ddownAmt - (perc_prov * ddownAmt); 
        $('#NET_DISBUR').val(fCurrency(String(provision)));
    });

    $('#DISKONTO').on('change keyup', function () {
        var bilVal = formatDesimal($('#BILLING_VAL').val());
        var perc_prov = Number($('#PROVISION').val()) / 100 ;
        var ddownAmt = Number(bilVal) * 95 / 100 ;
        var calc_prov = ddownAmt - (perc_prov * ddownAmt); 
        var diskonto = formatDesimal($('#DISKONTO').val());
        var NET_DISBUR = Number(calc_prov) - Number(diskonto) ;
        $('#NET_DISBUR').val(fCurrency(String(NET_DISBUR)));
    });

    $('#CLOSE').on('click', function () {
        $("#SCFARDetail").parsley().reset();
        // $("#MDetail").parsley().reset();
    });

    $('#EXC_RATE').on('change', function() {
        var rate = Number(formatDesimal(String($(this).val()))) ;
        var base = Number(DtKmk.LIMIT_WA) ;
        var converted = rate * base ;
        $('#CONVERTED').val(fCurrency(String(converted)));
    });


    var reloadUpload = function() {
        // $('#notesUpload').val('');
        $('.upload-file').val('');
        tbl_upload.ajax.reload();
    };

    var reloadUploadWDAR = function() {
        $('.upload-file-wdar').val('');
        tbl_WDAR.ajax.reload();
    };

    var ClearData = function () {
        $('#btnReset').removeAttr('disabled');
        $('.upload-file').val('');
        $('#page-container').removeClass('page-sidebar-minified');
    };

    
    $('#btnDetail').on("click", function() {
        $('#DETID').val(null);
        amtModals = 0;
        
    });

    // Table of invoice batch
    if (!$.fn.DataTable.isDataTable('#Table_DtWDAR')) {
        $('#Table_DtWDAR').DataTable({
            "bDestroy" : true,
            "bRetrieve" : true,
            "ajax": {
                    "url" : "<?php echo site_url('Kmk/GetDataWDARDet')?>",
                    "type": "POST",
                    "datatype" : "JSON",
                    "data": function(d) {
                        d.UUID = BATCHID;
                        d.VALUE_DATE = $('#VALUE_DATE').val();
                    },
                    "dataSrc": function(ext) {
                        if (ext.status == 200) {
                            ext.draw = ext.result.data;
                            return ext.result.data;
                        } else if (ext.status == 504) {
                            alert(ext.result.data);
                            location.reload();
                            return [];
                        } else {
                            console.info(ext.result.data);
                            return ;
                        }
                    },
                    "beforeSend" : function() {
                        $("#overlay").show();
                    },
                    "complete" : function() {
                        $("#overlay").hide();
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
                {"className" : "text-center", "data":"VENDORNAME"},
                {"className" : "text-center", "data":"INV_DATE"},
                {"className" : "text-center", "data":"INV_NUM"},
                {"className" : "text-center", "data":"BAST_DATE"},
                {"className" : "text-center", "data":"BAST_NUM"},
                {"className" : "text-center", "data":"BILLING_VAL",
                    render: $.fn.dataTable.render.number(',', '.', 2)},
                {"className" : "text-center", "data":"DDOWN_AMT",
                    render: $.fn.dataTable.render.number(',', '.', 2)},
                {"className" : "text-center", "data":"PAY_DATE"},
                {"className" : "text-center", "data":"DUE_DATE"},
                {"className" : "text-center", "data":"TOTAL_DAYS"},
                {"className" : "text-center", "data":"DISKONTO",
                    render: $.fn.dataTable.render.number(',', '.', 2)},
                {"className" : "text-center", "data":"NET_DISBUR",
                    render: $.fn.dataTable.render.number(',', '.', 2)},  
                {"className" : "text-center", "data":"FILE_NAME"},  
                {
                    "data": null,
                    "className": "text-center",
                    "orderable": false,
                    render: function (data, type, row, meta) {
                        var html = '';
                        // if (EDITS == 1) {
                        // html += '<button class="btn btn-success btn-icon btn-circle btn-sm edit" title="edit" style="margin-right: 5px;">\n\
                        // <i class="fas fa-edit" aria-hidden="true"></i>\n\
                        // </button>';
                        // }
                        // if (DELETES == 1) {
                        //     html += '<button class="btn btn-danger btn-icon btn-circle btn-sm delete" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                        // }
                        html += '<button class="btn btn-success btn-icon btn-circle btn-sm dwn" title="Download" style="margin-right: 5px;">\n\
                        <i class="fas fa-arrow-down" aria-hidden="true"></i>\n\
                        </button>';
                        return html;
                    }
                }
            ],
            "bFilter" : true,
            "bPaginate" : true,
            "bLengthChange" : false,
            "bInfo" : true,
            "responsive" : false
        });
        tbl_WDAR = $('#Table_DtWDAR').DataTable();
        // tbl_WDAR.on('click', '.edit', function() {
        //     $tr = $(this).closest('tr');
        //     var data = tbl_WDAR.row($tr).data();
        //     DETID = data.ID;
        //     amtModals = data.DDOWN_AMT;
        //     $('#DETID').val(DETID);
        //     $('#VENDOR').append($('<option>', {
        //         value: data.VENDOR,
        //         text: data.VENDORNAME
        //     }));
        //     $('#INV_DATE').val(moment(data.INV_DATE).format('YYYY-MM-DD'));
        //     $('#BAST_DATE').val(moment(data.BAST_DATE).format('YYYY-MM-DD'));
        //     $('#BAST_NUM').val(data.BAST_NUM);
        //     $('#INV_NUM').val(data.INV_NUM);
        //     $('#BILLING_VAL').val(fCurrency(data.BILLING_VAL));
        //     $('#DDOWN_AMT').val(fCurrency(data.DDOWN_AMT));
        //     $('#PAY_DATE').val(moment(data.INV_DATE).format('YYYY-MM-DD'));
        //     $('#DUE_DATE').val(moment(data.DUE_DATE).format('YYYY-MM-DD'));
        //     $('#TOTAL_DAYS').val(data.TOTAL_DAYS);
        //     $('#DISKONTO').val(fCurrency(data.DISKONTO));
        //     $('#NET_DISBUR').val(fCurrency(data.NET_DISBUR));
        //     $('#MDetail').parsley().reset();
        //     $('#MDetail .modal-title').text("Edit Data Invoice");
        //     // const cekDocval = IDOCNUMBER.includes("TMP");
        //     // alert(IDOCNUMBER);
        //     $("#MDetail").modal({
        //         backdrop: 'static',
        //         keyboard: false
        //     });
        // });
        tbl_WDAR.on('click', '.dwn', function() {
            $tr = $(this).closest('tr');
            var data = tbl_WDAR.row($tr).data();
            window.open("<?php echo base_url('assets/file/')?>" + data.FILE_NAME,'_blank');
        });
        // tbl_WDAR.on('click', '.delete', function () {
        //         $tr = $(this).closest('tr');
        //         var data = tbl_WDAR.row($tr).data();
        //         if (confirm('Are you sure delete this data ?')) {
        //             $.ajax({
        //                 dataType: "JSON",
        //                 type: "POST",
        //                 url: "<?php echo site_url('Kmk/DeleteAR'); ?>",
        //                 data: {
        //                     ID: data.ID,
        //                     FILENAME: data.FILE_NAME,
        //                     USERNAME: USERNAME,
        //                     AMOUNT: data.DDOWN_AMT,
        //                     VALUE_DATE : data.VALUE_DATE,
        //                     WD_TYPE: data.WD_TYPE
        //                 },
        //                 success: function (response) {
        //                     if (response.status == 200) {
        //                         toastr.success(response.result.data);
        //                         tbl_WDAR.ajax.reload();
        //                     } else if (response.status == 504) {
        //                         toastr.error(response.result.data);
        //                         location.reload();
        //                     } else {
        //                         toastr.warning(response.result.data);
        //                     }
        //                 },
        //                 error: function (e) {
        //                     toastr.error('Error deleting data !!');
        //                 }
        //             });
        //         }
        // });
    }

    function remainingLimit (withdrawal_data, DtKmk) {
        var total_bilval = 0;
        for (var i = 0 ; i < withdrawal_data.length ; i++) {
            total_bilval += Number(withdrawal_data[i]['BILLING_VAL']);
        }
        var amount_limit = $('#AMOUNTLIMIT').val();
        AMOUNT = total_bilval;
        var currentLimit = DtKmk.AMOUNT_LIMIT-total_bilval ;
        return currentLimit ;
    }
    // DisableBtn();
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
