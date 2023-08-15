<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<!-- <ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
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
        <h4 class="panel-title">Leasing</h4>
    </div>
    <div class="panel-body">
        <?php if (empty($_GET)) { ?>
            <div class="row mb-2">
                <div class="col-md-8 pull-left">
                    <?php if ($ACCESS['ADDS'] == 1) { ?>
                        <button onclick="Add()" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> Add</button> 
                    <?php } ?>
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
                            <th class="text-center sorting">Doc Id</th>
                            <th class="text-center sorting">Doc Number</th>
                            <th class="text-center sorting">Vendor</th>
                            <th class="text-center sorting">Business Unit</th>
                            <th class="text-center sorting">Transaction Method</th>
                            <!-- <th class="text-center sorting">Status</th> -->
                            <th class="text-center sorting_disabled" aria-label="Action"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        <?php } else { ?>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#leasingdata" data-toggle="tab" class="leasingdata nav-link active">
                    <span class="d-sm-none">Tab 1</span>
                    <span class="d-sm-block d-none">Leasing Data</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#import" data-toggle="tab" class="import nav-link">
                    <span class="d-sm-none">Tab 2</span>
                    <span class="d-sm-block d-none">Upload</span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active show" id="leasingdata">
                <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="fccode">Company *</label>
                            <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
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
                            <select class="form-control businessunit" id="BUSINESSUNIT" name="BUSINESSUNIT" required>
                                <!-- <option selected>Select Business Unit</option> -->
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="description">Department *</label>
                            <select class="form-control" name="DEPARTMENT" id="DEPARTMENT" required>
                                <!-- <option value="" selected>All Department</option> -->
                                <!-- <?php echo $CDepartment; ?> -->
                                <option value="BANK-RELATION" selected>BANK-RELATION</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="description">Doc Number *</label>
                            <input type="text" name="DOCNUMBER" id="DOCNUMBER" class="form-control" placeholder="Doc Number" required>
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group col-md-3">
                            <label for="description">Description </label>
                            <input type="text" name="DESCRIPTION" id="DESCRIPTION" class="form-control" placeholder="Description">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Docdate *</label>
                            <input type="date" name="DOCDATE" id="DOCDATE" class="form-control" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fcname">Vendor * <span id="BIC"></span></label>
                            <select class="form-control vendor" id="VENDOR" name="VENDOR" required>
                                <!-- <option disabled selected>Select Vendor</option> -->
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Valid From *</label>
                            <input type="date" name="VALID_FROM" id="VALID_FROM" class="form-control" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Valid Until *</label>
                            <input type="date" name="VALID_UNTIL" id="VALID_UNTIL" class="form-control" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Due Date Per Month *</label>
                            <input type="text" name="DUEDATE_PERMONTH" id="DUEDATE_PERMONTH" class="form-control" required disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Total Month *</label>
                            <input type="text" name="TOTAL_MONTH" id="TOTAL_MONTH" class="form-control" disabled required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Currency *</label>
                            <select class="form-control" name="CURRENCY" id="CURRENCY" required>
                                <option value="" disabled selected>--Choose Currency--</option>
                                <?php
                                foreach ($DtCurrency as $values) {
                                    echo '<option value=' . $values->DETAILID . '>' . $values->DETAILNAME . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Basic Amount * (Principal)</label>
                            <input type="text" data-type='currency' name="BASIC_AMOUNT" id="BASIC_AMOUNT" class="form-control" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Interest Percentage *</label>
                            <input type="text" name="INTEREST_PERCENTAGE" id="INTEREST_PERCENTAGE" class="form-control" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Denda Percentage *</label>
                            <input type="text" name="DENDA_PERCENTAGE" id="DENDA_PERCENTAGE" class="form-control" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Penalty Percentage *</label>
                            <input type="text" name="PENALTY_PERCENTAGE" id="PENALTY_PERCENTAGE" class="form-control" required>
                        </div>
                        <div class="form-group col-md-3" style="display: none;">
                            <label for="description">Total Interest Amount Per Year *</label>
                            <input type="text" data-type='currency' name="INTEREST_AMOUNT" id="INTEREST_AMOUNT" class="form-control" disabled required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Amount Before Conversion *</label>
                            <input type="text" data-type='currency' name="AMOUNT_BEFORE_CONV" id="AMOUNT_BEFORE_CONV" class="form-control" disabled required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Rate *</label>
                            <input type="text" name="RATE" id="RATE" class="form-control" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Amount After Conversion *</label>
                            <input type="text" name="AMOUNT_AFTER_CONV" id="AMOUNT_AFTER_CONV" class="form-control" disabled required>
                        </div>
                        <div class="form-group col-md-3" style="display: none;">
                            <label for="description">Amount Per Month *</label>
                            <input type="text" name="AMOUNT_PER_MONTH" id="AMOUNT_PER_MONTH" class="form-control" disabled required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Ext System *</label>
                            <select class="form-control EXTSYS" id="EXTSYS" name="EXTSYS" required>
                                <option disabled selected>Select ExtSys</option>
                                <option value="SAP">SAP</option>
                                <option value="IPLAS">IPLAS</option>
                                <option value="TIPTOP">TIPTOP</option>
                                <option value="SAPHANA">SAPHANA</option>

                            </select>
                        </div>
                        <!-- <div class="form-group col-md-3">
                            <label for="description">Item Code *</label>
                            <select class="form-control ITEM_CODE" id="ITEM_CODE" name="ITEM_CODE" required>
                            </select>
                        </div> -->
                        <div class="form-group col-md-3">
                                    <div class="form-group">
                                        <label for="TRANSACTIONMETHOD_BY">Transaction Method</label>
                                        <select name="TRANSACTIONMETHOD_BY" id="TRANSACTIONMETHOD_BY" class="form-control">
                                            <!-- <option>select transaction method</option> -->
                                            <option value="ANUITAS">ANUITAS</option>
                                            <option value="ANUITAS_SC">ANUITAS_SC</option>
                                            <option value="EFEKTIF">EFEKTIF</option>
                                            <option value="FLAT">FLAT</option>
                                        </select>
                                    </div>
                                </div>
                        <!-- <div class="form-group col-md-3">
                            <label for="isactive">Status *</label>
                            <select class="form-control" name="ISACTIVE" id="ISACTIVE" required>
                                <option value="TRUE">Active</option>
                                <option value="FALSE">Non Active</option>
                            </select>
                        </div> -->
                    </div>
                </form>
                    <?php if (!empty($_GET)) { ?>
                        <div class="panel-footer text-left">
                            <button type="button" id="btnSave" onclick="SaveMaster()" class="btn btn-primary btn-sm m-l-5">Save</button>
                        </div>
                    <?php } ?>
            </div>
            <div class="tab-pane fade" id="import">
            <div class="panel panel-success">
                <div class="panel-body">
                    <div class="row mb-2 fileupload-buttonbar">
                        <div class="col-md-4 pb-2">
                            <div class="alert alert-danger alert-dismissible fade show h-100 mb-0">
                            Give Notes First Before Upload!
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="text" class="form-control col-md-4 mb-2" id="notesUpload" name="notes" placeholder="Notes.." required>
                            <span class="btn btn-primary fileinput-button m-r-3">
                                <!--<i class="fa fa-plus"></i>-->
                                <span>Browse File</span>
                                <input type="file" class="upload-file" data-max-size="1048576" onchange="filesChange(this)">
                            </span>
                            <!-- <button id="btnSave" type="button" class="btn btn-primary m-r-3" onclick="SaveUpload()">
                                <i class="fa fa-upload"></i>
                                <span>Upload Data</span>
                            </button> -->
                            <button id="btnReset" type="button" class="btn btn-default m-r-3" onclick="ClearData()" disabled="disabled">
                                <!--<i class="fa fa-upload"></i>-->
                                <span>Clear Data</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row m-0 table-responsive">
                    <table id="DtUpload" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                        <thead>
                            <tr role="row">
                                <th class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                                <th class="text-center">Notes</th>
                                <th class="text-center">File</th>
                                <th class="text-center">Submit By</th>
                                <th class="text-center sorting">Created at</th>
                                <th class="text-center">#</th>
                                <!-- <th class="sorting_disabled"></th> -->
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        </div>
        <?php } ?>
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
                                    <input type="text" class="form-control" id="VCOMPANYNAME" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="DEPARTMENT">Department *</label>
                                    <input type="text" class="form-control" id="VDEPARTMENT" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="DOCNUMBER">Doc Number *</label>
                                    <input type="text" class="form-control" id="VDOCNUMBER" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="DOCDATE">Doc Date *</label>
                                    <input type="text" class="form-control" id="VDOCDATE" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="VENDOR">Vendor *</label>
                                    <input type="text" class="form-control" id="VVENDOR" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="DUEDATE">Due Date Per Month *</label>
                                    <input type="text" class="form-control" id="VDUEDATE_PERMONTH" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="VALID_FROM">Valid From </label>
                                    <input type="text" class="form-control" id="VVALID_FROM" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="VALID_UNTIL">Valid Until </label>
                                    <input type="text" class="form-control" id="VVALID_UNTIL" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="TOTAL_MONTH">Total Month </label>
                                    <input type="text" class="form-control" id="VTOTAL_MONTH" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="CURRENCY">Currency </label>
                                    <input type="text" class="form-control" id="VCURRENCY" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="RATE">Rate </label>
                                    <input type="text" class="form-control" id="VRATE" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="AMOUNT_BEFORE_CONV">Amount Before Conversion </label>
                                    <input type="text" class="form-control" id="VAMOUNT_BEFORE_CONV" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="AMOUNT_AFTER_CONV">Amount After Conversion</label>
                                    <input type="text" class="form-control" id="VAMOUNT_AFTER_CONV" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="BASIC_AMOUNT">Basic Amount</label>
                                    <input type="text" class="form-control" id="VBASIC_AMOUNT" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="INTEREST_PERCENTAGE">Interest Percentage</label>
                                    <input type="text" class="form-control" id="VINTERESTPERCENTAGE" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="INTEREST_PERCENTAGE">Denda Percentage</label>
                                    <input type="text" class="form-control" id="VDENDAPERCENTAGE" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="INTEREST_PERCENTAGE">Penalty Percentage</label>
                                    <input type="text" class="form-control" id="VPENALTYPERCENTAGE" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="INTEREST_AMOUNT">Total Interest Amount Per Year </label>
                                    <input type="text" class="form-control" id="VINTEREST_AMOUNT" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="EXTSYS">Extsys</label>
                                    <input type="text" class="form-control" id="VEXTSYS" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ITEM_CODE">Item</label>
                                    <input type="text" class="form-control" id="VITEM" disabled>
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
    <?php if (!empty($_GET)) { ?>
                        <div class="panel-footer text-left">
                            <button type="button" class="btn btn-warning btn-sm m-l-5" onclick="Cancel()">Back</button>
                        </div>
                    <?php } ?>
</div>

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
    var table, ACTION, ID;
    $(document).ready(function () {
        if (getUrlParameter('type') == "edit" || getUrlParameter('type') == "add") {
            UrlParam = getUrlParameter('type');
            if (getUrlParameter('type') == "add") {
                if (ADDS != 1) {
                    $('#btnSave').remove();
                }
                SetDataKosong();
            } else {
                if (EDITS != 1) {
                    $('#btnSave').remove();
                }
                var data = <?php echo json_encode($DtLeasing); ?>;
                SetData(data);
            }
        } else {
            if (!$.fn.DataTable.isDataTable('#DtLeasing')) {
                $('#DtLeasing').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('Leasing/ShowData') ?>",
                        "contentType": "application/json",
                        "type": "POST",
                        "data": function () {
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
                    {"data": "GENID"},
                    {"data": "DOCNUMBER"},
                    {"data": "FCNAME"},
                    {"data": "BUNAME"},
                    {"data": "TRANSACTIONMETHOD_BY"},
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            var html = '';
                            if (EDITS == 1) {
                                html += '<button class="btn btn-success btn-icon btn-circle btn-sm mr-2 edit" title="Edit" style="margin-right: 5px;">\n\
                                <i class="fa fa-edit" aria-hidden="true"></i>\n\
                                </button>';
                            }
                            if (EDITS == 1) {
                                html += '<button class="btn btn-info btn-icon btn-circle btn-sm mr-2 view" title="view data" data-id="'+data.UUID+'"><i class="fa fa-eye"></i></button>';
                            }
                            if (EDITS == 1) {
                                html += '<button class="btn btn-green btn-icon btn-circle btn-sm mr-2 export" title="export data" data-company="'+data.COMPANY+'" data-docnumber="'+data.DOCNUMBER+'"><i class="fa fa-print"></i></button>';
                            }
                            if (DELETES == 1) {
                                html += '<button class="btn btn-danger btn-icon btn-circle btn-sm ml-4 delete" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
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
                    window.location.href = window.location.href + '?type=edit&UUID=' + data.UUID;
                });
                table.on('click', '.view', function () {
                    var UUID = $(this).attr('data-id');
                    $('#loader').addClass('show');
                    $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('Leasing/viewDetail'); ?>",
                            data: {
                                ID: UUID},
                            success: function (response) {
                                $('#loader').removeClass('show');
                                $('#VCOMPANYNAME').val(response[0].COMPANYNAME);
                                $('#VDEPARTMENT').val(response[0].DEPARTMENT);
                                $('#VDOCNUMBER').val(response[0].DOCNUMBER);
                                $('#VDOCDATE').val(response[0].DOCDATE);
                                $('#VVENDOR').val(response[0].VENDORNAME);
                                $('#VDUEDATE_PERMONTH').val(response[0].DUEDATE_PER_MONTH);
                                $('#VVALID_FROM').val(response[0].VALID_FROM);
                                $('#VVALID_UNTIL').val(response[0].VALID_UNTIL);
                                $('#VTOTAL_MONTH').val(response[0].TOTAL_MONTH);
                                $('#VCURRENCY').val(response[0].CURRENCY);
                                $('#VRATE').val(response[0].RATE);
                                $('#VAMOUNT_BEFORE_CONV').val(fCurrency(response[0].AMOUNT_BEFORE_CONV));
                                $('#VAMOUNT_AFTER_CONV').val(fCurrency(response[0].AMOUNT_AFTER_CONV));
                                $('#VBASIC_AMOUNT').val(fCurrency(response[0].BASIC_AMOUNT));
                                $('#VINTERESTPERCENTAGE').val(response[0].INTEREST_PERCENTAGE);
                                $('#VDENDAPERCENTAGE').val(response[0].DENDA_PERCENTAGE);
                                $('#VPENALTYPERCENTAGE').val(response[0].PENALTY_PERCENTAGE);
                                $('#VINTEREST_AMOUNT').val(fCurrency(response[0].INTEREST_AMOUNT));
                                $('#VITEM').val(response[0].ITEM_NAME);
                                $('#VEXTSYS').val(response[0].EXTSYS);
                                $('#VTRANSACTIONMETHOD_BY').val(response[0].TRANSACTIONMETHOD_BY);
                                $("#MView").modal({
                                    backdrop: 'static',
                                    keyboard: false
                                });
                            },
                            error: function (e) {
                                $('#loader').removeClass('show');
                                alert('Error Get data !!');
                            }
                        });
                });
                table.on('click', '.delete', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    if (confirm('Are you sure delete this data ?')) {
                        $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('Leasing/DeleteMaster'); ?>",
                            data: {
                                DOCNUMBER: data.DOCNUMBER,
                                USERNAME: USERNAME
                            },
                            success: function (response) {
                                if (response.status == 200) {
                                    alert(response.result.data.MESSAGE);
                                    table.ajax.reload();
                                } else if (response.status == 504) {
                                    alert(response.result.data.MESSAGE);
                                    location.reload();
                                } else {
                                    alert(response.result.data.MESSAGE);
                                }
                            },
                            error: function (e) {
                                alert('Error deleting data !!');
                            }
                        });
                    }
                });
                table.on('click', '.export', function () {
                    var DOCNUM = $(this).attr('data-docnumber');
                    var COMP = $(this).attr('data-company');
                    var url = "<?php echo site_url('Leasing/exportTransaction'); ?>?COMPANY=PARAM1&DOCNUMBER=PARAM2";
                    url = url.replace("PARAM1", COMP);
                    url = url.replace("PARAM2", DOCNUM);
                    window.open(url, '_blank');
                });
                $("#DtLeasing_filter").remove();
                $("#search").on({
                    'keyup': function () {
                        table.search(this.value, true, false, true).draw();
                    }
                });
            }
        }
    });
    var Add = function () {
        window.location.href = window.location.href + '?type=add';
    };
    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }
    function Edit(ID){
        window.location.href = "<?php echo site_url('leasingmaster'); ?>" + '?type=edit&UUID=' + ID;
    }
    function SetDataKosong() {
        $('.panel-title').text('Add Data Leasing');
        ID = "0";
        $('#DEPARTMENT').val('');
        $('#DOCNUMBER').val('');
        $('#VENDOR').val('');
        $('#DUEDATE_PERMONTH').val();
        $('#TOTAL_MONTH').val('');
        $('#AMOUNT_BEFORE_CONV').val('');
        $('#AMOUNT_AFTER_CONV').val('');
        $('#AMOUNT_PER_MONTH').val('');
        $('#RATE').val('');
            // $('#ISACTIVE').val('TRUE');
            ACTION = 'ADD';
    }

    // function formatDate(date) {
    //     var d = new Date(date),
    //         month = '' + (d.getMonth() + 1),
    //         day = '' + d.getDate(),
    //         year = d.getFullYear();

    //     if (month.length < 2) 
    //         month = '0' + month;
    //     if (day.length < 2) 
    //         day = '0' + day;

    //     return [month,day,year].join('/');
    // }

    function SetData(data) {
        var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
        // console.log(data);
        $('.panel-title').text('Edit Data Leasing');
        ID = data.UUID;
        $('#COMPANY option[value='+data.COMPANY+']').attr('selected','selected');
        $('#COMPANY').attr('readonly', true);
        $('#BUSINESSUNIT').append($('<option>', {
            value: data.BUID,
            text: data.BUFCNAME
        }));
        $('#BUSINESSUNIT').attr('readonly', true);
        $('#DEPARTMENT').attr('readonly', true);
        $('#DOCNUMBER').val(data.DOCNUMBER);
        //moment($('#DOCDATE').val()).format('MM-DD-YYYY');
        $('#DESCRIPTION').val(data.DESCRIPTION);
        $('#DOCDATE').val(moment(data.DOCDATE).format('YYYY-MM-DD'));
        $('#VENDOR').append($('<option>', {
            value: data.SUPPID,
            text: data.VENDORNAME
        }));
        $('#VALID_FROM').val(moment(data.VALID_FROM).format('YYYY-MM-DD'));
        $('#VALID_UNTIL').val(moment(data.VALID_UNTIL).format('YYYY-MM-DD'));
        $('#DUEDATE_PERMONTH').val(data.DUEDATE_PER_MONTH);
        $('#TOTAL_MONTH').val(data.TOTAL_MONTH);
        $('#CURRENCY').val(data.CURRENCY);
        $('#BASIC_AMOUNT').val(numFormat(data.BASIC_AMOUNT));
        $('#INTEREST_PERCENTAGE').val(data.INTEREST_PERCENTAGE);
        $('#DENDA_PERCENTAGE').val(data.DENDA_PERCENTAGE);
        $('#PENALTY_PERCENTAGE').val(data.PENALTY_PERCENTAGE);
        $('#AMOUNT_BEFORE_CONV').val(numFormat(data.AMOUNT_BEFORE_CONV));
        $('#RATE').val(data.RATE);
        $('#AMOUNT_AFTER_CONV').val(numFormat(data.AMOUNT_AFTER_CONV));
        $('#AMOUNT_PER_MONTH').val(data.AMOUNT_PER_MONTH);
        $('#INTEREST_AMOUNT').val(data.INTEREST_AMOUNT);
        $('#EXTSYS').val(data.EXTSYS);
        $('#ITEM_CODE').append($('<option>', {
            value: data.MID,
            text: data.MFCNAME
        }));
        $('#TRANSACTIONMETHOD_BY').val(data.TRANSACTIONMETHOD_BY);
        ACTION = 'EDIT';
        $('.leasingdata').removeClass('active');
        $('#leasingdata').removeClass('active show');
        $('.import').addClass('active show');
        $('#import').addClass('active show');
    }

    var SaveMaster = function () {
        
        if ($('#FAddEditForm').parsley().validate()) {
                $("#loader").show();
                $('#btnSave').attr('disabled', true);
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Leasing/Save'); ?>",
                    data: {
                        ID: ID,
                        COMPANY: $('#COMPANY').val(),
                        BUSINESSUNIT: $('#BUSINESSUNIT').val(),
                        DEPARTMENT: $('#DEPARTMENT').val(),
                        DOCNUMBER: $('#DOCNUMBER').val(),
                        DESCRIPTION: $('#DESCRIPTION').val(),
                        DOCDATE: $('#DOCDATE').val(),
                        DOCNUMBER: $('#DOCNUMBER').val(),
                        VENDOR: $('#VENDOR').val(),
                        BIC: $('#BIC').text(), 
                        VALID_FROM: $('#VALID_FROM').val(),
                        VALID_UNTIL: $('#VALID_UNTIL').val(),
                        DUEDATE_PERMONTH: $('#DUEDATE_PERMONTH').val(),
                        TOTAL_MONTH: $('#TOTAL_MONTH').val(),
                        CURRENCY: $('#CURRENCY').val(),
                        BASIC_AMOUNT: $('#BASIC_AMOUNT').val(),
                        INTEREST_PERCENTAGE: $('#INTEREST_PERCENTAGE').val(),
                        DENDA_PERCENTAGE: $('#DENDA_PERCENTAGE').val(),
                        PENALTY_PERCENTAGE: $('#PENALTY_PERCENTAGE').val(),
                        INTEREST_AMOUNT: $('#INTEREST_AMOUNT').val(),
                        AMOUNT_BEFORE_CONV: $('#AMOUNT_BEFORE_CONV').val(),
                        RATE: $('#RATE').val(),
                        AMOUNT_AFTER_CONV: $('#AMOUNT_AFTER_CONV').val(),
                        AMOUNT_PER_MONTH: $('#AMOUNT_PER_MONTH').val(),
                        // ITEM_CODE: $('#ITEM_CODE').val(),
                        EXTSYS: $('#EXTSYS').val(),
                        TRANSACTIONMETHOD_BY: $('#TRANSACTIONMETHOD_BY').val(),
                        ACTION: ACTION,
                        USERNAME: USERNAME
                    },
                    success: function (response) {
                        $("#loader").hide();
                        $('#btnSave').removeAttr('disabled');
                        if (response.status == 200) {
                            alert("Data Successfully Saved");
                            Edit(response.result.data);
                        } else if (response.status == 500) {
                            alert(response.result.data);
                            // location.reload();
                        } else {
                            alert(response.result.data);
                        }
                    },
                    error: function (e) {
                        $("#loader").hide();
                        console.info(e);
                        alert('Data Save Failed !!');
                        $('#btnSave').removeAttr('disabled');
                    }
                });
        }
        
    };
</script>
<script type="text/javascript">
    $(document).ready(function() {
        // $('#DUEDATE_PERMONTH').val('15');
        // $("#COMPANY").select2({
        //     // theme: 'bootstrap4',
        //     ajax: {
        //         url: "<?php echo site_url('Leasing/getCompany') ?>",
        //         dataType: 'json',
        //         delay: 250,
        //         type: 'GET',
        //         data: function(params) {
        //             return {
        //                 q: params.term, // search term
        //                 page: params.page
        //             };
        //         },
        //         processResults: function(data, page) {
        //             // parse the results into the format expected by Select2.
        //             // since we are using custom formatting functions we do not need to
        //             // alter the remote JSON data
        //             return {
        //                 results: $.map(data, function (item) {
        //                   return {
        //                     id:item.ID,
        //                     text:item.TEXT
        //                 }
        //             })
        //             };
        //         },
        //         cache: true
        //     },
        //     escapeMarkup: function(markup) {
        //         return markup;
        //     }, // let our custom formatter work
        //     minimumInputLength: 1,
        //     //templateResult: formatRepo,
        //     //templateSelection: formatRepoSelection
        // });
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

        $("#VENDOR").select2({
            // theme: 'bootstrap4',
            ajax: {
                url: "<?php echo site_url('Leasing/getVendor') ?>",
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
                                id:item.ID,
                                text:item.TEXT,
                                bic:item.BIC
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
    });

    $('#VENDOR').on('select2:select', function (e) {
        var data = e.params.data;
        $('#BIC').text(data.bic);
    });

    $("#VALID_FROM").on({
        'change': function() {
            // $('#loader').addClass('show');
            // return dateTo.getMonth() - dateFrom.getMonth() + (12 * (dateTo.getFullYear() - dateFrom.getFullYear()))
            var dt_from = new Date( $(this).val());
            var month_from =  (dt_from.getMonth() < 10 ? '0' : '') + (dt_from.getMonth()+1);

            var dt_until = new Date( $('#VALID_UNTIL').val());
            var month_until =  (dt_until.getMonth() < 10 ? '0' : '') + (dt_until.getMonth()+1);

            let total = (dt_until.getFullYear() - dt_from.getFullYear()) * 12 + (dt_until.getMonth() - dt_from.getMonth()) + 1;
            $('#TOTAL_MONTH').val(total);
            $('#DUEDATE_PERMONTH').val(dt_from.getDate());

        }
    });

    $("#VALID_UNTIL").on({
        'change': function() {
            // $('#loader').addClass('show');
            // return dateTo.getMonth() - dateFrom.getMonth() + (12 * (dateTo.getFullYear() - dateFrom.getFullYear()))
            var dt_from = new Date( $('#VALID_FROM').val());
            var month_from =  (dt_from.getMonth() < 10 ? '0' : '') + (dt_from.getMonth()+1);

            var dt_until = new Date( $(this).val());
            var month_until =  (dt_until.getMonth() < 10 ? '0' : '') + (dt_until.getMonth()+1);

            let total = (dt_until.getFullYear() - dt_from.getFullYear()) * 12 + (dt_until.getMonth() - dt_from.getMonth()) + 1;
            $('#TOTAL_MONTH').val(total);

        }
    });

    $("#BASIC_AMOUNT").on({
        'keyup': function() {
            // $('#loader').addClass('show');
            // return dateTo.getMonth() - dateFrom.getMonth() + (12 * (dateTo.getFullYear() - dateFrom.getFullYear()))
            var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

            var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
            var basic_amt   = intVal($(this).val());
            var int_percent = intVal($('#INTEREST_PERCENTAGE').val());


            let total = (basic_amt * (int_percent / 100) / 12) * intVal($('#TOTAL_MONTH').val());
            $('#INTEREST_AMOUNT').val(numFormat(total));

            var int_amount  = intVal($('#INTEREST_AMOUNT').val());

            let total_amt_before = basic_amt + int_amount;
            $('#AMOUNT_BEFORE_CONV').val(numFormat(total_amt_before));

            var amt_before_conv   = intVal($('#AMOUNT_BEFORE_CONV').val());
            var rate        = intVal($('#RATE').val());

            let total_amt_after = amt_before_conv * rate;
            $('#AMOUNT_AFTER_CONV').val(numFormat(total_amt_after));

            var total_month     = $('#TOTAL_MONTH').val();
            var amt_after_conv = $('#AMOUNT_AFTER_CONV').val();
            let total_amt_month = intVal(amt_after_conv) / total_month;
            $('#AMOUNT_PER_MONTH').val(numFormat(total_amt_month));

        }
    });

    $("#INTEREST_PERCENTAGE").on({
        'keyup': function() {
            // $('#loader').addClass('show');
            // return dateTo.getMonth() - dateFrom.getMonth() + (12 * (dateTo.getFullYear() - dateFrom.getFullYear()))
            var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

            var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
            var basic_amt   = intVal($('#BASIC_AMOUNT').val());
            var int_percent = intVal($(this).val());


            let total = (basic_amt * (int_percent / 100) / 12) * intVal($('#TOTAL_MONTH').val()-1);
            $('#INTEREST_AMOUNT').val(numFormat(total));

            var int_amount  = intVal($('#INTEREST_AMOUNT').val());

            let total_amt_before = basic_amt + int_amount;
            $('#AMOUNT_BEFORE_CONV').val(numFormat(total_amt_before));

            var amt_before_conv   = intVal($('#AMOUNT_BEFORE_CONV').val());
            var rate        = intVal($('#RATE').val());

            let total_amt_after = amt_before_conv * rate;
            $('#AMOUNT_AFTER_CONV').val(numFormat(total_amt_after));

            var total_month     = $('#TOTAL_MONTH').val();
            var amt_after_conv = $('#AMOUNT_AFTER_CONV').val();
            let total_amt_month = intVal(amt_after_conv) / total_month;
            $('#AMOUNT_PER_MONTH').val(numFormat(total_amt_month));

        }
    });

    $("#RATE").on({
        'keyup': function() {
           var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

            var numFormat   = $.fn.dataTable.render.number('\,', '.', 2).display;
            var amt_before_conv   = intVal($('#AMOUNT_BEFORE_CONV').val());
            var rate        = intVal($(this).val());

            let total = amt_before_conv * rate;
            $('#AMOUNT_AFTER_CONV').val(numFormat(total));

            var total_month     = $('#TOTAL_MONTH').val();
            var amt_after_conv = $('#AMOUNT_AFTER_CONV').val();
            let total_amt_month = intVal(amt_after_conv) / total_month;
            $('#AMOUNT_PER_MONTH').val(numFormat(total_amt_month));

        }
    });

    // $("#ITEM_CODE").select2({
    //     // theme: 'bootstrap4',
    //     ajax: {
    //         url: "<?php echo site_url('Leasing/getMaterialCode') ?>",
    //         dataType: 'json',
    //         delay: 250,
    //         type: 'GET',
    //         data: function(params) {
    //             return {
    //                 q: params.term,
    //                 EXTSYS:$('#EXTSYS').val(), // search term
    //                 page: params.page
    //             };
    //         },
    //         processResults: function(data, page) {
    //             // parse the results into the format expected by Select2.
    //             // since we are using custom formatting functions we do not need to
    //             // alter the remote JSON data
    //             return {
    //                 results: $.map(data, function (item) {
    //                   return {
    //                     id:item.ID,
    //                     text:item.TEXT + '-' +item.FCNAME
    //                 }
    //             })
    //             };
    //         },
    //         cache: true
    //     },
    //     escapeMarkup: function(markup) {
    //         return markup;
    //     }, // let our custom formatter work
    //     minimumInputLength: 1,
    //     //templateResult: formatRepo,
    //     //templateSelection: formatRepoSelection
    // });

    // $("#VALID_UNTIL").on({
    //     'change': function() {
    //         // $('#loader').addClass('show');
    //         // return dateTo.getMonth() - dateFrom.getMonth() + (12 * (dateTo.getFullYear() - dateFrom.getFullYear()))
    //         var dt_from = new Date( $('#VALID_FROM').val());
    //         var month_from =  (dt_from.getMonth() < 10 ? '0' : '') + (dt_from.getMonth()+1);

    //         var dt_until = new Date( $(this).val());
    //         var month_until =  (dt_until.getMonth() < 10 ? '0' : '') + (dt_until.getMonth()+1);

    //         let total = month_until - month_from + (13 * (dt_until.getFullYear() - dt_from.getFullYear()));
    //         $('#TOTAL_MONTH').val(total);

    //     }
    // });

    

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
<script type="text/javascript">
    var files, filetypeUpload = ['XLS','XLSX','PDF'];
    var DtUpload = <?php echo json_encode($DtUpload); ?>;
    var STATUS = true;
    var tbl_upload;
    //FILENAME, tbl_uploadOthers;

    if (!$.fn.DataTable.isDataTable('#DtUpload')) {
        $('#DtUpload').DataTable({
            "bDestory" : true,
            "bRetrieve" : true,
            // "aaData": DtUpload,
            "ajax": {
                    "url": "<?php echo site_url('Leasing/ShowFileData') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function(d) {
                        d.UUID = DtUpload;
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
            {"className": "text-center","data":"NOTES"},
            {"className": "text-center","data":"FILENAME"},
            {"className": "text-center","data":"FCENTRY"},
            {"className": "text-center","data":"LASTUPDATE"},
            {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            var html = '';
                            if (EDITS == 1) {
                                html += '<button class="btn btn-success btn-icon btn-circle btn-sm dwn" title="Download" style="margin-right: 5px;">\n\
                                <i class="fas fa-arrow-down" aria-hidden="true"></i>\n\
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
                            url: "<?php echo site_url('Leasing/DeleteFile'); ?>",
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
        if($('#notesUpload').val() == null || $('#notesUpload').val() == '' || $('#notesUpload').val() == 0){
            toastr.error('Isi Notes');
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
                        $.each(files, function (i, data) {
                            fd.append("userfile", data);
                        });
                        fd.append("USERNAME", USERNAME);
                        fd.append("UUID",ID);
                        fd.append("NOTES",$('#notesUpload').val());
                        // fd.append("EXTSYSTEM",$('#EXTSYSTEM').val());
                        // fd.append("DOCTYPE",$('#DOCTYPE').val());
                        // fd.append('UUID',UUID)
                        // fd.append('DATERELEASE',currentDate);
                        $.ajax({
                            dataType: "JSON",
                            type: 'POST',
                            url: "<?php echo site_url('Leasing/uploadFile'); ?>",
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
    var reloadUpload = function() {
        $('#notesUpload').val('');
        $('.upload-file').val('');
        tbl_upload.ajax.reload();
    };

    var ClearData = function () {
        $('#btnReset').removeAttr('disabled');
        $('.upload-file').val('');
        $('#page-container').removeClass('page-sidebar-minified');
    };
    // DisableBtn();
</script>