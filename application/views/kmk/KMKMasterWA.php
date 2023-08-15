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
        <h4 class="panel-title">Kredit Modal Kerja WA</h4>
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
                            <th class="text-center sorting">Company</th>
                            <th class="text-center sorting">Doc</th>
                            <th class="text-center sorting">Bank</th>
                            <th class="text-center sorting">Type</th>
                            <th class="text-center sorting">Docdate</th>
                            <!-- <th class="text-center sorting">Currency</th> -->
                            <th class="text-center sorting">Limit</th>
                            <th class="text-center sorting">Status</th>
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
                    <span class="d-sm-block d-none">KMK Data</span>
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
                            <label for="description">Bank *</label>
                            <select class="form-control" name="BANK" id="BANK" required="">
                                <option value="" disabled="" selected="">--Choose--</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Reference Contract </label>
                            <select class="form-control" name="REF_CON" id="REF_CON" >
                                <option value="" disabled="" selected="">--Choose--</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="description">PK Number </label>
                            <input type="text" class="form-control" id="PK_NUMBER" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Credit Type </label>
                            <select name="CREDIT_TYPE" id="CREDIT_TYPE" class="form-control">
                                <option value="KMK" selected>Kredit Modal Kerja</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Sub Credit Type *</label>
                            <select name="SUB_CREDIT_TYPE" id="SUB_CREDIT_TYPE" class="form-control">
                                <!-- <option value="" disabled="" selected="">--Choose--</option> -->
                                <!-- <option value="BD">BD</option>
                                <option value="RK">RK</option> -->
                                <option value="WA" selected>Withdrawal Approval</option>
                                <!-- <option value="FINANCING">FINANCING</option>
                                <option value="REFINANCING">REFINANCING</option> -->
                            </select>
                        </div>
                    </div>
                </form>
                    <?php if (!empty($_GET)) { ?>
                        <div class="panel-footer text-left">
                            <button type="button" id="btnSaveMaster" onclick="SaveMaster()" class="btn btn-primary btn-sm m-l-5 btnSave">Save</button>
                        </div>
                    <?php } ?>
            </div>
        </div>

        <!-- WA Start -->
        
        <div class="panel panel-success WA d-none" id="WA">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                </div>
                <h4 class="" style="color: white;">Contract Detail</h4>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <form id="FAddEditFormbd" data-parsley-validate="true" data-parsley-errors-messages-disabled="" onsubmit="return false" novalidate="">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="company">Limit *</label>
                                <input class="form-control" data-type='currency' type="text" name="AMOUNTLIMIT" id="AMOUNTLIMIT" placeholder="Limit">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="currency">Currency *</label>
                                <select class="form-control" name="CURRENCY" id="CURRENCY" required="">
                                    <option value="" selected="" disabled="">Choose Currency</option>
                                    <?php
                                        foreach ($DtCurrency as $values) {
                                            echo '<option value=' . $values->DETAILID . '>' . $values->DETAILNAME . '</option>';
                                        }
                                        ?>
                                    </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="company">Doc Date *</label>
                                <input type="date" class="form-control" name="DOCDATE" id="DOCDATE">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fcname">Interest % *</label>
                                <input type="text" class="form-control" name="INTEREST" id="INTEREST" placeholder="Interest %" required="" data-parsley-pattern="^[0-9-.]+$" data-parsley-group="block1">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="company">Maturity Date *</label>
                                <input type="date" class="form-control" name="MATURITY_DATE" id="MATURITY_DATE">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fccode">Loan Account Number *</label>
                                <input type="text" class="form-control" name="LOAN_ACCOUNT_NUMBER" id="LOAN_ACCOUNT_NUMBER" placeholder="Loan Account Number" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="fccode">Tenor *</label>
                                <input type="text" class="form-control" name="TENOR" id="TENOR" disabled>
                            </div>
                            <!-- <div class="form-group col-md-6">
                                <label for="address">INSTALLMENT PAYMENT PERIOD *</label>
                                <select name="INSTALLMENT_PERIOD" id="INSTALLMENT_PERIOD" class="form-control mb-2">
                                    <option value="" selected>--Choose--</option>
                                    <option value="Monthly">Monthly</option>
                                    <option value="Quarterly">Quarterly</option>
                                </select>
                            </div> -->
                            <div class="form-group col-md-3">
                                <label for="fcname">Late Payment Fee % *</label>
                                <input type="text" class="form-control" name="FEE" id="FEE" placeholder="Fee %" required="" data-parsley-pattern="^[0-9-.]+$" data-parsley-group="block1">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="fcname">Pre-Payment Penalty % *</label>
                                <input type="text" class="form-control" name="PRE_PAYMENT_PENALTY" id="PRE_PAYMENT_PENALTY" placeholder="Pre-Payment Penalty %" required="" data-parsley-pattern="^[0-9-.]+$" data-parsley-group="block1">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>First Date Payment Interest </label>
                                        <input type="date" class="form-control mb-2" name="FIRST_DATE_INTEREST_PAYMENT" id="FIRST_DATE_INTEREST_PAYMENT" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Interest Payment Schedule Date *</label>
                                        <input type="text" class="form-control mb-2" name="INTEREST_PAYMENT_SCHEDULE_DATE" id="INTEREST_PAYMENT_SCHEDULE_DATE" required disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="address">Interest Payment Schedule *</label>
                                    <select name="INTEREST_PAYMENT_SCHEDULE" id="INTEREST_PAYMENT_SCHEDULE" class="form-control mb-2">
                                        <option value="" disabled="" selected="">--Choose--</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Quarterly">Quarterly</option>
                                    </select>
                            </div>
                        </div>
                        <?php if($DtKmk->IS_ACC == 1) {  ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for='remarks_addendum'> Remarks Addendum </label>
                                                <textarea class="form-control" id="ADD_REMARKS" name="ADD_REMARKS" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                        <?php } ?>
                        <div class="row">
                            <div class="panel-footer text-left">
                                <button type="button" id="btnSaveDet" onclick="SaveWAHeader()" class="btn btn-primary btn-sm m-l-5">Save</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <!-- detail -->
                    <div class="row">
                        <div class="col-md-12">
                            <button id="btnDetail" type="button" onclick="AddDetail()" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> Add</button>
                        </div>
                    </div>
                    <div class="row m-0 table-responsive">
                        <table id="DtDetail" class="table table-bordered table-hover dataTable DtDetail" role="grid" width="100%" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <th class="text-center align-middle">SubCredit</th>
                                    <th class="text-center align-middle">Limit</th>
                                    <th class="text-center align-middle">Docdate</th>
                                    <th class="text-center align-middle">Tenor</th>
                                    <th class="text-center">LoanAccountNumber</th>
                                    <th class="text-center">LatePaymentFee</th>
                                    <th class="text-center align-middle">Currency</th>
                                    <th class="text-center align-middle">Interest</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                        </table>`
                    </div>
                    <hr>
                    <div class="row mt-2 fileupload-buttonbar">
                        <div class="col-md-4">
                            <label for="address">Attachment *</label>
                            <select name="tipe_file" id="tipe_file" class="form-control mb-2">
                                <option value="SPK">SPK</option>
                                <option value="AKTA_PK">AKTA PK</option>
                                <option value="REFERENCE_DOCUMENT">REFERENCE DOCUMENT</option>
                                <option value="OTHER_DOCUMENT">OTHER DOCUMENT</option>
                            </select>
                            <span class="btn btn-primary fileinput-button m-r-3">
                                <!--<i class="fa fa-plus"></i>-->
                                <span>Browse File</span>
                                <input type="file" class="upload-file" data-max-size="31457280" onchange="filesChange(this)">
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
                                    <!-- <th class="sorting_disabled"></th> -->
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- WA End -->
        <!--  -->
        <?php } ?>
    </div>

    <!-- modal detail -->
    <div class="modal fade" id="MDetail">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Data Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <form id="FDetail" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sub Credit Type *</label>
                                    <select class="form-control" name="ISUBTYPE" id="ISUBTYPE" required>
                                        <option value="" selected disabled>Choose</option>
                                        <option value="KMK_SCF_AP">KMK_SCF_AP</option>
                                        <option value="KMK_SCF_AR">KMK_SCF_AR</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Limit *</label>
                                    <input type="text" class="form-control" id="IAMOUNT_LIMIT" name="IAMOUNT_LIMIT" data-type="currency" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Docdate *</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="IDOCDATE" name="IDOCDATE" required disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Maturity Date *</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="IMATURITY_DATE" name="IMATURITY_DATE" required disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tenor *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="ITENOR" name="ITENOR" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Loan Account Number *</label>
                                    <input type="text" class="form-control" name="ILOAN_ACCOUNT_NUMBER" id="ILOAN_ACCOUNT_NUMBER" placeholder="Loan Account Number" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Late Payment Fee % *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="IFEE" name="IFEE" placeholder="Fee %" required data-parsley-pattern="^[0-9-.]+$" data-parsley-group="block2">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Currency *</label>
                                    <select class="form-control" name="ICURRENCY" id="ICURRENCY" required>
                                    <option value="" selected>Choose Currency</option>
                                    <?php
                                    foreach ($DtCurrency as $values) {
                                        echo '<option value=' . $values->DETAILID . '>' . $values->DETAILNAME . '</option>';
                                    }
                                    ?>
                                </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Interest % *</label>
                                    <input type="text" class="form-control" id="IINTEREST" name="IINTEREST" required data-parsley-pattern="^[0-9-.]+$" data-parsley-group="block2">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="SaveWADetails()">Save</button>
                        <input style="display:none;" type="button" class="btn btn-info" id="updateTemp" value="Update">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal detail -->
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
                        <table id="tbDetails" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" width="100%" aria-describedby="tbDetails_info" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <!-- <th class="text-center sorting_asc" style="width: 30px;">No</th> -->
                                    <th class="text-center sorting_disabled">No</th>
                                    <th class="text-center sorting_disabled">Limit</th>
                                    <th class="text-center sorting_disabled">Docdate</th>
                                    <th class="text-center sorting_disabled">Fee</th>
                                    <th class="text-center sorting_disabled">Rate</th>
                                    <th class="text-center sorting_disabled">Currency</th>
                                    <th class="text-center sorting_disabled">Maturity</th>
                                    <th class="text-center sorting_disabled">Interest</th>
                                    <th class="text-center sorting_disabled">Loan Number</th>
                                    <th class="text-center sorting_disabled">Interest Period</th>
                                    <th class="text-center sorting_disabled">Tenor</th>
                                    <th class="text-center sorting_disabled">Installment Period</th>
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
    <!-- end modal view -->
    <?php if (!empty($_GET)) { ?>
                        <div class="panel-footer text-left">
                            <!-- <button type="button" id="btnSaveDet" onclick="SaveWAHeader()" class="btn btn-primary btn-sm m-l-5">Save</button> -->
                            <button type="button" class="btn btn-warning btn-sm m-l-5" onclick="Cancel()">Back</button>
                        </div>
                    <?php } ?>
</div>

<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var DtUpload = <?php echo json_encode($DtUpload); ?>;
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
    var table, ACTION,UUID,ID,DtHeaderDetail,tbHeaderDet,SETAMOUNT_LIMIT,ACTIONM;
    var IDXD, DETID, ISUBTYPE, IDOCDATE, ILOAN_ACCOUNT_NUMBER, IFEE, IRATE,ICURRENCY,IINTEREST_PERIOD_FROM,IAMOUNT_LIMIT;

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
                $('#btnSaveMaster').remove();
                var data = <?php echo json_encode($DtKmk); ?>;
                // console.log(DtHeaderDetail)
                SetData(data);
                if((data.IS_ACC == '1' || data.IS_ACC == '0' || data.IS_ACC == '2') && getUrlParameter('vm') == "vm"){
                    $('#btnSaveMaster').hide();
                    $('#btnDetail').hide();
                    $('.panel-footer #btnSaveDet').hide();
                    $('.fileupload-buttonbar').hide();
                    // $('#btnHapus').remove();
                }
                if((data.IS_ACC == '1' || data.IS_ACC == '0' || data.IS_ACC == '2') && getUrlParameter('vm') == null){
                    $('#btnSaveMaster').hide();
                    
                    // $('.panel-footer #btnSaveDet').hide();
                    // $('.fileupload-buttonbar').hide();
                }
                if(data.IS_ACC == 1 || data.IS_ACC == 2) {
                    $('#btnDetail').hide();
                }
                    setTimeout(function(){
                        if((data.IS_ACC == '1' || data.IS_ACC == '0' || data.IS_ACC == '2') && getUrlParameter('vm') == "vm"){
                            $('.delete').addClass('d-none');
                            $('.editdet').addClass('d-none');
                        }
                    },1000);
                $('#WA').removeClass('d-none');
            }
        } else {
            if (!$.fn.DataTable.isDataTable('#DtLeasing')) {
                $('#DtLeasing').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('Kmk/ShowData') ?>",
                        dataType: "JSON",
                        type: "POST",
                        data: {
                            URL:"WA"
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
                    {"data": "COMPANYCODE"},
                    {"data": "PK_NUMBER"},
                    {"data": "FCNAME"},
                    {"data": "SUB_CREDIT_TYPE"},
                    {"data": "DOCDATE"},
                    // {"data": "CURRENCY"},
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
                            if (EDITS == 1) {
                                    if(data.IS_ACC == '1'){
                                        html += '<span class="badge bg-success">Approved</span>'
                                    }else if(data.IS_ACC == '2'){
                                        html += '<span class="badge bg-danger">Decline</span>';
                                    }else{
                                        html += '<span class="badge bg-secondary">?</span>'
                                    }
                                    // else if(){
                                    //     html += '<button type="button" class="btn btn-danger btn-xs decline" title="edit data" data-id="'+data.UUID+'">Decline</button>';
                                    // }
                            }
                            return html;
                        }
                    },
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
                                html += '<button class="btn btn-warning btn-icon btn-circle btn-sm mr-2 btnView" title="view data" data-id="'+data.UUID+'" data-uuid="'+data.UUID+'" data-company="'+data.COMPANY+'"><i class="fa fa-eye"></i></button>';
                            }
                            // if (EDITS == 1) {
                            //     html += '<button class="btn btn-green btn-icon btn-circle btn-sm mr-2 export" title="export data" data-company="'+data.COMPANY+'" data-docnumber="'+data.DOCNUMBER+'"><i class="fa fa-print"></i></button>';
                            // }
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
                            url: "<?php echo site_url('Kmk/DeleteMaster'); ?>",
                            data: {
                                UUID: data.UUID,
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

    $('body').on('click','.btnView',function(){
        $("#tbDetails").dataTable().fnDestroy();
        var UUID = $(this).attr('data-uuid');
        var COMP = $(this).attr('data-company');
        // var NO_RECEIPT_DOC   = $(this).attr('data-norec');
        $('#loader').addClass('show');
        
        $('#tbDetails').DataTable({
            dom: 'Bfrtip',
                    // "buttons": [{
                    //         extend: "excel",
                    //         title: 'Data History Elog',
                    //         className: "btn-xs btn-green mb-2",
                    //         text: 'Export To Excel'
                    //     }],
                "processing": true,
                "bDestroy": true,
                "retrieve": true,
                "ajax": {
                    "url": "<?php echo site_url('Kmk/getHistoryDocWA'); ?>",
                    "datatype": "JSON",
                    "type": "POST",
                    "data": function (d) {
                        d.UUID = UUID;
                        d.COMPANY = COMP;
                        // d.NO_RECEIPT_DOC = NO_RECEIPT_DOC;
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
                {
                    "data": "AMOUNT_LIMIT",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {"data": "DOCDATE"},
                {
                    "data": "FEE",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "RATE",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {"data": "CURRENCY"},
                {"data": "MATURITY_DATE"},
                {"data": "INTEREST"},
                {"data": "LOAN_ACCOUNT_NUMBER"},
                {"data": "INTEREST_PERIOD"},
                {"data": "TENOR"},
                {"data": "INSTALLMENT_PERIOD"}
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
        $('#tbDetails thead th').addClass('text-center');
        $('#loader').removeClass('show');
        $('#MView').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    var Add = function () {
        window.location.href = window.location.href + '?type=add';
    };
    function Cancel() {
        history.go(-1)
    }
    function Edit(ID){
        window.location.href = "<?php echo site_url('KMKMasterWA'); ?>" + '?type=edit&UUID=' + ID;
    }

    $("#DOCDATE").on({
        'change': function() {
            // $('#loader').addClass('show');
            // return dateTo.getMonth() - dateFrom.getMonth() + (12 * (dateTo.getFullYear() - dateFrom.getFullYear()))
            var dt_from = new Date( $(this).val());
            var month_from =  (dt_from.getMonth() < 10 ? '0' : '') + (dt_from.getMonth()+1);

            var dt_until = new Date( $('#MATURITY_DATE').val());
            var month_until =  (dt_until.getMonth() < 10 ? '0' : '') + (dt_until.getMonth()+1);

            let total = (dt_until.getFullYear() - dt_from.getFullYear()) * 12 + (dt_until.getMonth() - dt_from.getMonth()) ;
            $('#TENOR').val(total);
            // $('#DUEDATE_PERMONTH').val(dt_from.getDate());

        }
    });

    $("#MATURITY_DATE").on({
        'change': function() {
            // $('#loader').addClass('show');
            // return dateTo.getMonth() - dateFrom.getMonth() + (12 * (dateTo.getFullYear() - dateFrom.getFullYear()))
            var dt_from = new Date( $('#DOCDATE').val());
            var month_from =  (dt_from.getMonth() < 10 ? '0' : '') + (dt_from.getMonth()+1);

            var dt_until = new Date( $(this).val());
            var month_until =  (dt_until.getMonth() < 10 ? '0' : '') + (dt_until.getMonth()+1);

            let total = (dt_until.getFullYear() - dt_from.getFullYear()) * 12 + (dt_until.getMonth() - dt_from.getMonth()) ;
            $('#TENOR').val(total);

        }
    });

    // $("#IDOCDATE").on({
    //     'change': function() {
    //         // $('#loader').addClass('show');
    //         // return dateTo.getMonth() - dateFrom.getMonth() + (12 * (dateTo.getFullYear() - dateFrom.getFullYear()))
    //         var dt_from = new Date( $(this).val());
    //         var month_from =  (dt_from.getMonth() < 10 ? '0' : '') + (dt_from.getMonth()+1);

    //         var dt_until = new Date( $('#IMATURITY_DATE').val());
    //         var month_until =  (dt_until.getMonth() < 10 ? '0' : '') + (dt_until.getMonth()+1);

    //         let total = (dt_until.getFullYear() - dt_from.getFullYear()) * 12 + (dt_until.getMonth() - dt_from.getMonth()) + 1;
    //         $('#ITENOR').val(total);
    //         // $('#DUEDATE_PERMONTH').val(dt_from.getDate());

    //     }
    // });

    $("#IDOCDATE").on(
        'change blur', function() {
            // $('#loader').addClass('show');
            // return dateTo.getMonth() - dateFrom.getMonth() + (12 * (dateTo.getFullYear() - dateFrom.getFullYear()))
            var dt_from = new Date( $(this).val());
            var month_from =  (dt_from.getMonth() < 10 ? '0' : '') + (dt_from.getMonth()+1);

            var dt_until = new Date( $('#IMATURITY_DATE').val());
            var month_until =  (dt_until.getMonth() < 10 ? '0' : '') + (dt_until.getMonth()+1);

            let total = (dt_until.getFullYear() - dt_from.getFullYear()) * 12 + (dt_until.getMonth() - dt_from.getMonth()) ;
            $('#ITENOR').val(total);
            // $('#DUEDATE_PERMONTH').val(dt_from.getDate());

        }
    );

    

    // $("#IMATURITY_DATE").on({
    //     'change': function() {
    //         // $('#loader').addClass('show');
    //         // return dateTo.getMonth() - dateFrom.getMonth() + (12 * (dateTo.getFullYear() - dateFrom.getFullYear()))
    //         var dt_from = new Date( $('#IDOCDATE').val());
    //         var month_from =  (dt_from.getMonth() < 10 ? '0' : '') + (dt_from.getMonth()+1);

    //         var dt_until = new Date( $(this).val());
    //         var month_until =  (dt_until.getMonth() < 10 ? '0' : '') + (dt_until.getMonth()+1);

    //         let total = (dt_until.getFullYear() - dt_from.getFullYear()) * 12 + (dt_until.getMonth() - dt_from.getMonth()) + 1;
    //         $('#ITENOR').val(total);

    //     }
    // });
    $("#IMATURITY_DATE").on(
        'change blur', function() {
            // $('#loader').addClass('show');
            // return dateTo.getMonth() - dateFrom.getMonth() + (12 * (dateTo.getFullYear() - dateFrom.getFullYear()))
            var dt_from = new Date( $('#IDOCDATE').val());
            var month_from =  (dt_from.getMonth() < 10 ? '0' : '') + (dt_from.getMonth()+1);

            var dt_until = new Date( $(this).val());
            var month_until =  (dt_until.getMonth() < 10 ? '0' : '') + (dt_until.getMonth()+1);

            let total = (dt_until.getFullYear() - dt_from.getFullYear()) * 12 + (dt_until.getMonth() - dt_from.getMonth()) ;
            $('#ITENOR').val(total);

        }
    );

    function SetDataKosong() {
        $('.panel-title').text('Add Data');
        ID = "0";
        $('#COMPANY').val('');
        $('#BUSINESSUNIT').val('');
            // $('#ISACTIVE').val('TRUE');
        ACTION = 'ADD';

    }

    var CREDIT_TYPE,SUB_CREDIT_TYPE,AMOUNT_CONVERT,IS_ACC;

    function SetData(data) {
        var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
        // console.log(data);
        $('.panel-title').text('Edit Data Kmk');
        UUID = data.LID;
        $('#COMPANY option[value='+data.COMPANY+']').attr('selected','selected');
        // $('#COMPANY').attr('readonly', true);
        $('#BUSINESSUNIT').append($('<option>', {
            value: data.BUID,
            text: data.BUFCNAME
        }));
        // $('#BUSINESSUNIT').attr('readonly', true);
        $('#PK_NUMBER').val(data.PK_NUMBER);
        $('#CREDIT_TYPE option[value='+data.CREDIT_TYPE+']').attr('selected','selected');
        $('#SUB_CREDIT_TYPE option[value='+data.SUB_CREDIT_TYPEMASTER+']').attr('selected','selected');
        // $('#CREDIT_TYPE').attr('readonly', true);
        // $('#SUB_CREDIT_TYPE').attr('readonly', true);
        // $('#BANK').val(data.BANK);
        html = "<option value='" + data.GETBANK + "'>" + data.GETBANK + '</option>';
        $("#BANK").html(html);
        htmlRefCon = "<option value='" + data.REF_CONTRACT + "'>" + data.REF_CONTRACT + '</option>';
        $("#REF_CON").html(htmlRefCon);
        // $('#BANK').attr('readonly', true); 
        if(data.AMOUNT_LIMIT == 0 || data.AMOUNT_LIMIT == '' || data.AMOUNT_LIMIT == null){
            $('#AMOUNTLIMIT').val();
        }else{
            $('#AMOUNTLIMIT').val(fCurrency(data.AMOUNT_LIMIT));
        }
        $('#RATE').val(data.RATE);
        $('#CURRENCY').val(data.CURRENCY);
        $('#FEE').val(data.FEE);
        $('#INTEREST').val(data.INTEREST);
        $('#TENOR').val(data.TENOR);

        $('#LOAN_ACCOUNT_NUMBER').val(data.LOAN_ACCOUNT_NUMBER);
        $('#DOCDATE').val(moment(data.DOCDATE).format('YYYY-MM-DD'));
        $('#INTEREST_PERIOD_FROM').val(moment(data.INTEREST_PERIOD_FROM).format('YYYY-MM-DD'));
        $('#INTEREST_PERIOD_TO').val(moment(data.INTEREST_PERIOD_TO).format('YYYY-MM-DD'));
        $('#MATURITY_DATE').val(moment(data.MATURITY_DATE).format('YYYY-MM-DD'));
        $('#INSTALLMENT_PERIOD option[value='+data.INSTALLMENT_PERIOD+']').attr('selected','selected');
        $('#FIRST_DATE_INTEREST_PAYMENT').val(moment(data.FIRST_DATE_INTEREST_PAYMENT).format('YYYY-MM-DD'));
        $('#INTEREST_PAYMENT_SCHEDULE_DATE').val(data.INTEREST_PAYMENT_SCHEDULE_DATE);
        $('#INTEREST_PAYMENT_SCHEDULE').val(data.INTEREST_PAYMENT_SCHEDULE);
        $('#IMATURITY_DATE').val(moment(data.MATURITY_DATE).format('YYYY-MM-DD'));
        $('#PRE_PAYMENT_PENALTY').val(data.PRE_PAYMENT_PENALTY);
        var dt_from = new Date( $('#IDOCDATE').val());
        var month_from =  (dt_from.getMonth() < 10 ? '0' : '') + (dt_from.getMonth()+1);

        var dt_until = new Date( $('#IMATURITY_DATE').val());
        var month_until =  (dt_until.getMonth() < 10 ? '0' : '') + (dt_until.getMonth()+1);

        let total = (dt_until.getFullYear() - dt_from.getFullYear()) * 12 + (dt_until.getMonth() - dt_from.getMonth()) + 1;
        $('#ITENOR').val(total);
        
        ACTION = 'EDIT';
        CREDIT_TYPE = data.CREDIT_TYPE;
        SUB_CREDIT_TYPE = data.SUB_CREDIT_TYPE;
        AMOUNT_CONVERT = data.AMOUNT_CONVERT;
        // data.IS_ACC == '2' || 
        // if(data.IS_ACC == '1'){
        //     $('#btnSave').hide();
        //     $('#btnDetail').hide();
        //     $('.panel-footer #btnSave').hide();
        //     $('.fileupload-buttonbar').hide();
        //     // $('#DtUpload tr').find("td.delete").remove();
        //     // $('#btnHapus').remove();
        // }
        IS_ACC = data.IS_ACC;
        if(data.ID_DETAIL == null) {
            $('#btnDetail').attr('disabled', 'disabled');
        }
        // if((CREDIT_TYPE == "KMK" && SUB_CREDIT_TYPE == "BD") || (CREDIT_TYPE == "KMK" && SUB_CREDIT_TYPE == "RK") ){
        // }
        $('#WA').removeClass('d-none');
        // $('.btnSave').addClass('d-none');
    }

    $("#BANK").on({
        'change': function() {
            if (this.value == '' || this.value == null || this.value == undefined) {
                BANKCODE = "";
                // BANKCURRENCY = "";
                // $("#CURRENCY").change();
            } else {
                var DBANK = JSON.parse(this.value);
                BANKCODE = DBANK.BANKCODE;
                // BANKCURRENCY = DBANK.CURRENCY;
                // $("#CURRENCY").change();
            }
        }
    });

    var AddDetail= function() {
            DtKmk = <?php echo json_encode($DtKmk); ?> ;
            ACTIONM = 'ADD';
            ISUBTYPE = "";
            IDOCDATE = "";
            ILOAN_ACCOUNT_NUMBER = "";
            IFEE = "";
            IRATE = "";
            ICURRENCY = "";
            IINTEREST_PERIOD_FROM = "";
            IAMOUNTLIMIT = "";
            $("#ISUBTYPE").val('');
            $("#ILOAN_ACCOUNT_NUMBER").val('');
            $("#IFEE").val('');
            $("#IRATE").val('');
            $("#ICURRENCY").val('');
            $("#IINTEREST_PERIOD_FROM").val('');
            $("#IAMOUNT_LIMIT").val('');
            $("#IMATURITY_DATE").val(moment(DtKmk.MATURITY_DATE).format('YYYY-MM-DD'));
            $("#IDOCDATE").val(moment(DtKmk.DOCDATE).format('YYYY-MM-DD'));
            $('#FDetail').parsley().reset();
            //
            var dt_from = new Date( $('#IDOCDATE').val());
            var month_from =  (dt_from.getMonth() < 10 ? '0' : '') + (dt_from.getMonth()+1);

            var dt_until = new Date( $('#IMATURITY_DATE').val());
            var month_until =  (dt_until.getMonth() < 10 ? '0' : '') + (dt_until.getMonth()+1);

            let total = (dt_until.getFullYear() - dt_from.getFullYear()) * 12 + (dt_until.getMonth() - dt_from.getMonth()) + 1;
            $('#ITENOR').val(total);
            //
            $('#MDetail .modal-title').text("Add Data Detail");
            $("#MDetail").modal({
                backdrop: 'static',
                keyboard: false
            });
    };

    var SaveMaster = function () {
        
        if ($('#FAddEditForm').parsley().validate()) {
                $("#loader").show();
                $('#btnSave').attr('disabled', true);
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Kmk/Save'); ?>",
                    data: {
                        UUID: UUID,
                        COMPANY: $('#COMPANY').val(),
                        PK_NUMBER: $('#PK_NUMBER').val(),
                        BUSINESSUNIT: $('#BUSINESSUNIT').val(),
                        BANK: BANKCODE,
                        CREDIT_TYPE: $('#CREDIT_TYPE').val(),
                        SUB_CREDIT_TYPE: $('#SUB_CREDIT_TYPE').val(),
                        CONTRACT_REF: $('#REF_CON').val(),
                        ACTION: ACTION,
                        USERNAME: USERNAME
                    },
                    success: function (response) {
                        $("#loader").hide();
                        $('#btnSave').removeAttr('disabled');
                        if (response.status == 200) {
                            alert("Data Successfully Saved");
                            Edit(response.result.data);
                        } else if (response.status == 504) {
                            alert(response.result.data);
                            location.reload();
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

    $('#MDetail').on('hidden.bs.modal', function(e) {
          $(this).find('#FDetail')[0].reset();
        });

    var SaveWAHeader = function () {
        
        if ($('#FAddEditFormbd').parsley().validate()) {
                $("#loader").show();
                $('#btnSave').attr('disabled', true);
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Kmk/SaveWA'); ?>",
                    data: {
                        UUID: UUID,
                        COMPANY: $('#COMPANY').val(),
                        AMOUNTLIMIT: $('#AMOUNTLIMIT').val(),
                        DOCDATE: $('#DOCDATE').val(),
                        FEE: $('#FEE').val(),
                        TENOR: $('#TENOR').val(),
                        MATURITY_DATE: $('#MATURITY_DATE').val(),
                        INTEREST: $('#INTEREST').val(),
                        LOAN_ACCOUNT_NUMBER: $('#LOAN_ACCOUNT_NUMBER').val(),
                        RATE: $('#RATE').val(),
                        CURRENCY: $('#CURRENCY').val(),
                        FIRST_DATE_INTEREST_PAYMENT: $('#FIRST_DATE_INTEREST_PAYMENT').val(),
                        INTEREST_PAYMENT_SCHEDULE_DATE : $('#INTEREST_PAYMENT_SCHEDULE_DATE').val(),
                        INTEREST_PAYMENT_SCHEDULE : $('#INTEREST_PAYMENT_SCHEDULE').val(),
                        INSTALLMENT_PERIOD:$('#INSTALLMENT_PERIOD').val(),
                        ADD_REMARK : $('#ADD_REMARKS').val(),
                        PRE_PAYMENT_PENALTY:$('#PRE_PAYMENT_PENALTY').val(),
                        POS: $('#pos').val(),
                        ACTION: ACTION,
                        USERNAME: USERNAME
                    },
                    success: function (response) {
                        $("#loader").hide();
                        $('#btnSave').removeAttr('disabled');
                        if (response.status == 200) {
                            toastr.success("Data Successfully Saved");
                            $('#FAddEditFormbd').parsley().reset();
                            SETAMOUNT_LIMIT = $('#AMOUNTLIMIT').val();
                            location.reload();
                            // setTimeout(function() { window.location.href = window.location.href.split("?")[0]; },1000);
                            // Edit(response.result.data);
                        } else if (response.status == 504) {
                            toastr.error(response.result.data);
                            location.reload();
                        } else {
                            toastr.error(response.result.data);
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
    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

    var SaveWADetails = function () {
        // if($('#DtDetail tr').find("td.colAmountLimit").html()){
        var selectCol = intVal($('#DtDetail tr').find("td.colAmountLimit").html());
        if(selectCol == 0){
            if(intVal($('#IAMOUNT_LIMIT').val()) > AMOUNT_CONVERT){
                toastr.error("Limit Details Amount Over Limit");
                $('#FDetail').parsley().reset();
            }
            else{
                $("#loader").show();
                $('#btnSave').attr('disabled', true);
                if($('#FDetail').parsley().validate()) {
                        $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('Kmk/SaveWA'); ?>",
                            data: {
                                ID: DETID,
                                UUID: UUID,
                                COMPANY: $('#COMPANY').val(),
                                SUB_CREDIT_TYPE:$('#ISUBTYPE').val(),
                                AMOUNTLIMIT:$('#IAMOUNT_LIMIT').val(),
                                DOCDATE:$('#IDOCDATE').val(),
                                TENOR:$('#ITENOR').val(),
                                MATURITY_DATE:$('#IMATURITY_DATE').val(),
                                LOAN_ACCOUNT_NUMBER:$('#ILOAN_ACCOUNT_NUMBER').val(),
                                FEE:$('#IFEE').val(),
                                RATE:$('#IRATE').val(),
                                CURRENCY:$('#ICURRENCY').val(),
                                INTEREST:$('#IINTEREST').val(),
                                INTEREST_PERIOD_FROM:$('#IINTEREST_PERIOD_FROM').val(),
                                INTEREST_PERIOD_TO:$('#IINTEREST_PERIOD_TO').val(),
                                INSTALLMENT_PERIOD:$('#IINSTALLMENT_PERIOD').val(),
                                OPT:1,
                                ACTION: ACTION,
                                ACTIONM:ACTIONM,
                                USERNAME: USERNAME
                            },
                            success: function (response) {
                                $("#loader").hide();
                                $('#btnSave').removeAttr('disabled');
                                if (response.status == 200) {
                                    toastr.success("Data Successfully Saved");
                                    $('.modal-content').find('#FDetail')[0].reset();  
                                    $('#MDetail').modal('hide');
                                    tbHeaderDet.ajax.reload();
                                    // Edit(response.result.data);
                                } else if (response.status == 504) {
                                    toastr.error(response.result.data);
                                    location.reload();
                                } else {
                                    toastr.error(response.result.data);
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
                else {
                    $("#loader").hide();
                }
            }
        }else{
            
            var diffCol = AMOUNT_CONVERT - selectCol;    
            
            
            // alert(sumCol);
            // alert(selectCol);
            if(intVal($('#IAMOUNT_LIMIT').val()) > diffCol/*intVal($('#IAMOUNT_LIMIT').val()) > sumCol*/){
                toastr.error("Limit Details Amount Over Limit");
                $('#FDetail').parsley().reset();
            }
            else{
                $("#loader").show();
                $('#btnSave').attr('disabled', true);
                if($("#FDetail").parsley().validate()) {
                    
                    $.ajax({
                        dataType: "JSON",
                        type: "POST",
                        url: "<?php echo site_url('Kmk/SaveWA'); ?>",
                        data: {
                            ID: DETID,
                            UUID: UUID,
                            COMPANY: $('#COMPANY').val(),
                            SUB_CREDIT_TYPE:$('#ISUBTYPE').val(),
                            AMOUNTLIMIT:$('#IAMOUNT_LIMIT').val(),
                            DOCDATE:$('#IDOCDATE').val(),
                            TENOR:$('#ITENOR').val(),
                            MATURITY_DATE:$('#IMATURITY_DATE').val(),
                            LOAN_ACCOUNT_NUMBER:$('#ILOAN_ACCOUNT_NUMBER').val(),
                            FEE:$('#IFEE').val(),
                            RATE:$('#IRATE').val(),
                            CURRENCY:$('#ICURRENCY').val(),
                            INTEREST:$('#IINTEREST').val(),
                            INTEREST_PERIOD_FROM:$('#IINTEREST_PERIOD_FROM').val(),
                            INTEREST_PERIOD_TO:$('#IINTEREST_PERIOD_TO').val(),
                            INSTALLMENT_PERIOD:$('#IINSTALLMENT_PERIOD').val(),
                            OPT:1,
                            ACTION: ACTION,
                            ACTIONM:ACTIONM,
                            USERNAME: USERNAME
                        },
                        success: function (response) {
                            $("#loader").hide();
                            $('#btnSave').removeAttr('disabled');
                            if (response.status == 200) {
                                toastr.success("Data Successfully Saved");
                                $('.modal-content').find('#FDetail')[0].reset();  
                                $('#MDetail').modal('hide');
                                tbHeaderDet.ajax.reload();
                                // Edit(response.result.data);
                            } else if (response.status == 504) {
                                toastr.error(response.result.data);
                                location.reload();
                            } else {
                                toastr.error(response.result.data);
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
                else {
                    $("#loader").hide();
                }
            }
        }
        
        // alert(sumCol);
        // if(intVal($('#AMOUNTLIMIT').val()) < intVal($('#IAMOUNT_LIMIT').val())){
        //     toastr.error("Limit Details Amount Over Limit");
        //     $('#FDetail').parsley().reset();
        // }
        
        
        
    };

    if (!$.fn.DataTable.isDataTable('#DtDetail')) {
                        $('#DtDetail').DataTable({
                            "bDestory" : true,
                            "bRetrieve" : true,
                            // "aaData": DtUpload,
                            "ajax": {
                                    "url": "<?php echo site_url('Kmk/HeaderDetailWA') ?>",
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
                            {"className": "text-center","data":"SCT"},
                            {
                                "data": "AMOUNT_LIMIT",
                                "className": "text-right colAmountLimit",
                                render: $.fn.dataTable.render.number(',', '.', 2)
                            },
                            {"className": "text-center","data":"DOCDATE"},
                            {"className": "text-center","data":"TENOR"},
                            {"className": "text-center","data":"LOAN_ACCOUNT_NUMBER"},
                            {"className": "text-center","data":"FEE"},
                            {"className": "text-center","data":"CURRENCY"},
                            {"className": "text-center","data":"INTEREST"},
                            {
                                        "data": null,
                                        "className": "text-center",
                                        "orderable": false,
                                        render: function (data, type, row, meta) {
                                            var html = '';
                                            if (EDITS == 1) {
                                                html += '<button class="btn btn-success btn-icon btn-circle btn-sm editdet" title="Download" style="margin-right: 5px;">\n\
                                                <i class="fas fa-edit" aria-hidden="true"></i>\n\
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
                                "responsive": false
                            });
                        tbHeaderDet = $('#DtDetail').DataTable();
                        tbHeaderDet.on('click', '.editdet', function() {
                            $tr = $(this).closest('tr');
                            var data = tbHeaderDet.row($tr).data();
                            ACTIONM = "EDIT";
                            DETID = data.ID;
                            ISUBTYPE = data.SCT;
                            IDOCDATE = data.DOCDATE;
                            ILOAN_ACCOUNT_NUMBER = data.LOAN_ACCOUNT_NUMBER;
                            IFEE = data.FEE;
                            IRATE = data.RATE;
                            ICURRENCY = data.CURRENCY;
                            IINTEREST_PERIOD_FROM = data.INTEREST_PERIOD_FROM;
                            IAMOUNTLIMIT = data.AMOUNT_LIMIT;
                            // $("#ISUBTYPE").val(data.SUB_CREDIT_TYPE);
                            $('#ISUBTYPE option[value='+data.SCT+']').attr('selected','selected');
                            $("#IAMOUNT_LIMIT").val(data.AMOUNT_LIMIT);
                            formatCurrency($('#IAMOUNT_LIMIT'), "blur");
                            $("#ILOAN_ACCOUNT_NUMBER").val(data.LOAN_ACCOUNT_NUMBER);
                            $("#ITENOR").val(data.TENOR);
                            $("input[name=IDOCDATE]").val(data.DOCDATE);
                            $("input[name=IMATURITY_DATE]").val(data.MATURITY_DATE);
                            $("#IFEE").val(data.FEE);
                            $("#IRATE").val(data.RATE);
                            $("#ICURRENCY").val(data.CURRENCY);
                            $("#IINTEREST").val(data.INTEREST);
                            $("input[name=IINTEREST_PERIOD_FROM]").val(data.INTEREST_PERIOD_FROM);
                            $("input[name=IINTEREST_PERIOD_TO]").val(data.INTEREST_PERIOD_TO);
                            $('#IINSTALLMENT_PERIOD option[value='+data.INSTALLMENT_PERIOD+']').attr('selected','selected');
                            $('#FDetail').parsley().reset();
                            $('#MDetail .modal-title').text("Edit Data Invoice");
                            // const cekDocval = IDOCNUMBER.includes("TMP");
                            // alert(IDOCNUMBER);
                            $("#MDetail").modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                        });
                        tbHeaderDet.on('click', '.delete', function () {
                                    $tr = $(this).closest('tr');
                                    var data = tbHeaderDet.row($tr).data();
                                    if (confirm('Are you sure delete this data ?')) {
                                        $.ajax({
                                            dataType: "JSON",
                                            type: "POST",
                                            url: "<?php echo site_url('Kmk/DeleteDetailWA'); ?>",
                                            data: {
                                                ID: data.ID,
                                                USERNAME: USERNAME
                                            },
                                            success: function (response) {
                                                if (response.status == 200) {
                                                    toastr.success(response.result.data);
                                                    tbHeaderDet.ajax.reload();
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
                        tbHeaderDet.ajax.reload();
                    }

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
                    url: "<?php echo site_url('Kmk/DtBankCompanyKI'); ?>",
                    data: {
                        COMPANY: COMPANY1
                    },
                    success: function(response, textStatus, jqXHR) {
                        $('#loader').removeClass('show');
                        if (response.status == 200) {
                            // var html = '';
                            // DValue = '';
                            // $.each(response.result.data, function(index, value) {
                            //     var CValue = JSON.stringify({
                            //         "BANKCODE": value.FCCODE,
                            //         "CURRENCY": value.CURRENCY
                            //     });
                            //     if (value.ISDEFAULT == "1") {
                            //         DValue = CValue;
                            //         html += "<option value='" + CValue + "'>" + value.BANKACCOUNT + ' - ' + value.FCNAME + ' - ' + value.CURRENCY + ' (Default) </option>';
                            //     } else {
                            //         html += "<option value='" + CValue + "'>" + value.BANKACCOUNT + ' - ' + value.FCNAME + ' - ' + value.CURRENCY + '</option>';
                            //     }

                            // });
                            // $(html).insertAfter("#BANK option:first");
                            // $("#BANK").val(DValue);
                            // $("#BANK").change();
                            var html = '';
                            DValue = '';
                            $.each(response.result.data, function(index, value) {
                                var CValue = JSON.stringify({
                                    "BANKCODE": value.FCCODE,
                                    "CURRENCY": value.CURRENCY
                                });
                                if (value.ISDEFAULT == "1") {
                                    DValue = CValue;
                                    html += "<option value='" + CValue + "'>" + value.FCNAME + '  </option>';
                                } else {
                                    html += "<option value='" + CValue + "'>" + value.FCNAME + '</option>';
                                }

                            });
                            $(html).insertAfter("#BANK option:first");
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
                url : "<?php echo site_url('Kmk/loadBusinessUnit');?>",
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
            $.ajax({
                url : "<?php echo site_url('Kmk/getReferenceContract');?>",
                method : "POST",
                data : {
                    COMPANY: gID,
                    CREDIT_TYPE : 'KMK'},
                async : true,
                dataType : 'json',
                success: function(data){
                    // console.log(data);
                    var listBusinessUnit = '';
                    listBusinessUnit += '<option value=\'\'></option>'
                    var i;
                    for(i=0; i<data.length; i++){
                        listBusinessUnit += '<option value='+data[i].UUID+'>'+data[i].PK_NUMBER+'</option>';
                    }
                    $('#REF_CON').html(listBusinessUnit);


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

        //check if subcredit (AP n AR) over the limit of main WA
        $("#IAMOUNT_LIMIT").on(
            'change keyup', function () {
                var selectCol = intVal($('#DtDetail tr').find("td.colAmountLimit").html());
                if(selectCol == 0){
                    if(intVal($('#IAMOUNT_LIMIT').val()) > AMOUNT_CONVERT){
                        toastr.error("Limit Details Amount Over Limit");
                        $('.btn-primary').attr('disabled', true);
                    }
                    else {
                        $('.btn-primary').attr('disabled', false);
                    }
                }
                else {
                    var diffCol = AMOUNT_CONVERT - selectCol;
                    // console.log(diffCol);
                    if(intVal($('#IAMOUNT_LIMIT').val()) > diffCol/*intVal($('#IAMOUNT_LIMIT').val()) > sumCol*/){
                        toastr.error("Limit Details Amount Over Limit");
                        $('.btn-primary').attr('disabled', true);
                    }
                    else {
                        $('.btn-primary').attr('disabled', false);
                    }
                }
            }
        )
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
<script type="text/javascript">
    var files, filetypeUpload = ['XLS','XLSX','PDF'];
    var STATUS = true;
    var tbl_upload;
    //FILENAME, tbl_uploadOthers;

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
            {"className": "text-center","data":"TIPE"},
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
                            url: "<?php echo site_url('Kmk/DeleteFile'); ?>",
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
        if(UUID == null || UUID == '' || UUID == 0){
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
                        $.each(files, function (i, data) {
                            fd.append("userfile", data);
                        });
                        fd.append("USERNAME", USERNAME);
                        fd.append("UUID",UUID);
                        fd.append("TIPE",$('#tipe_file').val());
                        // fd.append("EXTSYSTEM",$('#EXTSYSTEM').val());
                        // fd.append("DOCTYPE",$('#DOCTYPE').val());
                        // fd.append('UUID',UUID)
                        // fd.append('DATERELEASE',currentDate);
                        $.ajax({
                            dataType: "JSON",
                            type: 'POST',
                            url: "<?php echo site_url('Kmk/uploadFile'); ?>",
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
        // $('#notesUpload').val('');
        $('.upload-file').val('');
        tbl_upload.ajax.reload();
    };

    var ClearData = function () {
        $('#btnReset').removeAttr('disabled');
        $('.upload-file').val('');
        $('#page-container').removeClass('page-sidebar-minified');
    };

    $('#FIRST_DATE_INTEREST_PAYMENT').on('change', function () {
        var dt_first = new Date($('#FIRST_DATE_INTEREST_PAYMENT').val());
        var first_date = dt_first.getDate();
        $('#INTEREST_PAYMENT_SCHEDULE_DATE').val(first_date);
    });

     try {
        $('#FAddEditFormbd').parsley().on('form:validate', function(formInstance) {
            var check = formInstance.isValid({group : 'block1', force: true});
            // console.log(check);
            if(!check) {
                toastr.error("This field must content digits and \'.\' character");
            }
        });
    } catch {
        
    }
    try {
        $('#FDetail').parsley().on('form:validate', function(formInstance) {
            var check = formInstance.isValid({group : 'block2', force: true});
            // console.log(check);
            if(!check) {
                toastr.error("This field must content digits and \'.\' character");
            }
        });
    } catch {
        
    }
    // DisableBtn();
</script>