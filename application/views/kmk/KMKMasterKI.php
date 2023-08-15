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
        <h4 class="panel-title">Kredit Investasi</h4>
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
                            <th class="text-center sorting">Contract Number</th>
                            <th class="text-center sorting">PK Number</th>
                            <th class="text-center sorting">Bank</th>
                            <th class="text-center sorting">Type</th>
                            <th class="text-center sorting">Docdate</th>
                            <!-- <th class="text-center sorting">Currency</th> -->
                            <!-- <th class="text-center sorting">Limit</th> -->
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
                    <span class="d-sm-block d-none">KI Data</span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active show" id="leasingdata">
                <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                    <div class="row">
                        <input type="hidden" id="UUID" value=""/>
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
                                <option value="KI" selected="true">Kredit Investasi</option>
                            </select>
                        </div>
                        <!-- <div class="form-group col-md-3">
                            <label for="description">IDC Status</label>
                            <select name="IDC_STATUS" id="IDC_STATUS" class="form-control">
                                <option value="" disabled="" selected="">--Choose--</option>
                                <option value="WITH_IDC">With IDC</option>
                                <option value="WITHOUT_IDC">Without IDC</option>
                            </select>
                        </div> -->
                        <div class="form-group col-md-3">
                            <label for="description">Contract Type *</label>
                            <select class="form-control" name="CTYPE" id="CTYPE" required="">
                                <option value="" disabled="" selected="">--Choose--</option>
                                <option value="SYNDICATION" >SYNDICATION</option>
                                <option value="SINGLE">SINGLE</option>
                            </select>
                        </div>
                        <!-- <div class="form-group col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="IS_ADDENDUM">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Addendum</label>
                            </div>
                        </div> -->
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
        
        <div class="panel panel-success WA d-none">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                </div>
                <h4 class="" style="color: white;">Contract Detail</h4>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                <!-- bank Syndication -->
                <?php if ($DtKmk->KI_TYPE == 'SYNDICATION') {?>
                <div class='row'>
                    <h5> Bank Syndication </h5>
                </div>    
                <div class='row' id="SYNDICATE">
                                <form id="BANK_SYND" data-parsley-validate="true" data-parsley-errors-messages-disabled="" onsubmit="return false" novalidate="">
                                    <?php if (empty($bankKI)) {?>
                                        <div class="row" id="template-bank">
                                                    <input type="hidden" id="ID_REC" name="ID_REC[]" value="">
                                                <div class="col-4">
                                                    <div class="form-group " id="field-bank">
                                                        <label for="BANK_SYNDICATION">Bank Syndication *</label>
                                                        <select class="form-control bank_synd" name="BANK_SYNDICATION[]" id="BANK_SYNDICATION" required>
                                                            <option value="" selected="" disabled=""> Choose Bank </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-2" >
                                                    <div id="curr_select">
                                                        <label for="currency">Bank Portion *</label>
                                                        <select class="form-control col" name="AG_SYND_CURRENCY[]" id="AG_SYND_CURRENCY" required="">
                                                            <option value="" selected="" disabled="">Choose Currency</option>
                                                            <?php
                                                                foreach ($DtCurrency as $values) {
                                                                    echo '<option value=' . $values->DETAILID . '>' . $values->DETAILNAME . '</option>';
                                                                }
                                                                ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-4">
                                                    <label for="bank_portion">-</label>
                                                    <input type="text" class="form-control" name="BANK_PORTION[]" id="BANK_PORTION" data-type="currency" required >
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="" >
                                                        <div class="btn btn-primary" id="field_adder" name="ID_REC[]" value="">
                                                            <div class="fa fa-plus">
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                        <div id="additional_field">
                                        </div>
                                        <?php } else {?>
                                        <div id="existing_field">
                                        </div>
                                        <div id="additional_field">
                                        </div>
                                        <?php } ?>   
                                </form>
                        </div>
                    </div>
                    <?php } ?> 
                <!-- - -->
                    <form id="FAddEditFormbd" data-parsley-validate="true" data-parsley-errors-messages-disabled="" onsubmit="return false" novalidate="">
                    <?php if($DtKmk->IS_ACC == 1 || $DtKmk->IS_ADDENDUM == 1) {  ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for='addendum_date'> Addendum Date </label>
                                    <input type="date" class="form-control" id="ADDENDUM_DATE" name="ADDENDUM_DATE" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for='remarks_addendum'> Remarks Addendum </label>
                                    <textarea class="form-control" id="ADD_REMARKS" name="ADD_REMARKS" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="company">Doc Date *</label>
                            <input type="date" class="form-control" name="DOCDATE" id="DOCDATE">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="company">Bank Receiver *</label>
                            <input class="form-control" type="text" name="TOBANK" id="TOBANK" placeholder="Bank Receiver">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="company">Maturity Date *</label>
                            <input type="date" class="form-control" name="MATURITY_DATE" id="MATURITY_DATE">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="fcname">Late Payment Fee % *</label>
                            <input type="text" class="form-control" name="FEE" id="FEE" placeholder="Fee %" required="" data-parsley-pattern="^[0-9-.]+$" data-parsley-group="block1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="fccode">Tenor *</label>
                            <input type="text" class="form-control" name="TENOR" id="TENOR" disabled>
                        </div>
                        <div class="col-md-3">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="bankaccount">Interest Rate % *</label>
                            <input type="text" class="form-control" name="INTEREST" id="INTEREST" placeholder="Rate" required="" data-parsley-pattern="^[0-9-.]+$" data-parsley-group="block1">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="fcname">Provisi % *</label>
                            <input type="text" class="form-control" name="PROVISI" id="PROVISI" placeholder="Provisi %" required="" data-parsley-pattern="^[0-9-.]+$" data-parsley-group="block1">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="fcname">Tipe Provisi </label>
                            <select name="PROVISI_TYPE" id="PROVISI_TYPE" class="form-control mb-2" required>
                                <option value="" disabled="" selected="">--Choose--</option>
                                <option value="PA">P.A.</option>
                                <option value="FLAT">FLAT</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
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
                        <div class="form-group col-md-3">
                            <label for="fcname">Annual Fee % *</label>
                            <input type="text" class="form-control" name="ANNUAL_FEE" id="ANNUAL_FEE" placeholder="Annual Fee %" required="" data-parsley-pattern="^[0-9-.]+$" data-parsley-group="block1">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fcname">Upfront Fee % *</label>
                            <input type="text" class="form-control" name="UPFRONT_FEE" id="UPFRONT_FEE" placeholder="Upfont Fee %" required="" data-parsley-pattern="^[0-9-.]+$" data-parsley-group="block1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>First Date Payment Interest </label>
                                    <input type="date" class="form-control mb-2" name="FIRST_DATE_INTEREST_PAYMENT" id="FIRST_DATE_INTEREST_PAYMENT" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fcname">Commitment Fee % *</label>
                                <input type="text" class="form-control" name="COMMIT_FEE" id="COMMIT_FEE" placeholder="Commitment Fee %" required="" data-parsley-pattern="^[0-9-.]+$" data-parsley-group="block1">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fcname">Pre-Payment Penalty % *</label>
                                <input type="text" class="form-control" name="PRE_PAYMENT_PENALTY" id="PRE_PAYMENT_PENALTY" placeholder="Pre-Payment Penalty %" required="" data-parsley-pattern="^[0-9-.]+$" data-parsley-group="block1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="address">INSTALLMENT TYPE *</label>
                            <select name="INSTALLMENT_TYPE" id="INSTALLMENT_TYPE" class="form-control">
                                <option value="" disabled="" selected="">--Choose--</option>
                                <option value="PERCENTAGE">PERCENTAGE BASED</option>
                                <option value="NOMINAL">NOMINAL BASED</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-auto">
                            <label for="currency">Agency Fee *</label>
                                <select class="form-control" name="AG_FEE_CURRENCY" id="AG_FEE_CURRENCY" required="">
                                    <option value="" selected="" disabled="">Choose Currency</option>
                                    <?php
                                        foreach ($DtCurrency as $values) {
                                            echo '<option value=' . $values->DETAILID . '>' . $values->DETAILNAME . '</option>';
                                        }
                                        ?>
                                </select>
                        </div>
                        <div class="form-group col-sm-auto">
                            <label for="currency">Agency Facility</label>
                            <select class="form-control" name="AG_FEE_FAC" id="AG_FEE_FAC" required="">
                                <option value="" selected="" disabled="">Choose Facility</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="currency">Nominal</label>
                            <input type="text" class="form-control" data-type='currency' name="AGENCY_FEE" id="AGENCY_FEE" placeholder="Agency Fee" required="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="address">Installment Payment Period *</label>
                            <select name="INSTALLMENT_PERIOD" id="INSTALLMENT_PERIOD" class="form-control">
                                <option value="" disabled="" selected="">--Choose--</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Quarterly">Quarterly</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-2">
                            <label for="currency">Administration Fee *</label>
                            <select class="form-control" name="ADM_FEE_CURRENCY" id="ADM_FEE_CURRENCY" required="">
                                <option value="" selected="" disabled="">Choose Currency</option>
                                <?php
                                    foreach ($DtCurrency as $values) {
                                        echo '<option value=' . $values->DETAILID . '>' . $values->DETAILNAME . '</option>';
                                    }
                                    ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="currency">Nominal</label>
                                <input type="text" class="form-control" data-type='currency' name="ADM_FEE" id="ADM_FEE" placeholder="Admin Fee" required="" >
                        </div>
                    </div>
                    <div class="row">
                        
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
                        <table id="DtDetail" class="table table-bordered table-hover dataTable" role="grid" width="100%" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <th class="text-center align-middle">Contract Number</th>
                                    <th class="text-center align-middle">Tranche Number</th>
                                    <th class="text-center align-middle">Effective Date</th>
                                    <th class="text-center">Loan Account Number</th>
                                    <!-- <th class="text-center align-middle">Interest Payment Schedule</th> -->
                                    <!-- <th class="text-center align-middle">Interest Payment Period Date</th> -->
                                    <th class="text-center align-middle">Currency</th>
                                    <th class="text-center align-middle">Limit Tranche</th>
                                    <th class="text-center align-middle">Avail Period</th>
                                    <th class="text-center">Grace Period</th>
                                    <!-- <th class="text-center align-middle">Payback Period</th> -->
                                    <th>#</th>
                                </tr>
                            </thead>
                        </table>
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
                                <option value="ADDENDUM_DOCUMENT">ADDENDUM DOCUMENT</option>
                            </select>
                            <span class="btn btn-primary fileinput-button m-r-3">
                                <!--<i class="fa fa-plus"></i>-->
                                <span>Browse File</span>
                                <input type="file" class="upload-file"  data-max-size="10485760" multiple="multiple" onchange="filesChange(this)">
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
                        <table id="DtUpload" class="table table-striped table-bordered" cellspacing="0" role="grid" width="100%" aria-describedby="DtUpload_info">
                            <thead>
                                <tr role="row">
                                    <th class="text-center sorting_asc" aria-sort="ascending" >No</th>
                                    <th class="text-center">Tipe</th>
                                    <th class="text-center" >File</th>
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
                    <button type="button" class="close closex" data-dismiss="modal" >x</button>
                </div>
                <div class="modal-body">
                    <form id="FDetail" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tranche Number *</label>
                                    <input class="form-control" type="text" name="ITRANCHE_NUMBER" id="ITRANCHE_NUMBER" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Effective Date *</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="IEFFECTIVE_DATE" name="IEFFECTIVE_DATE" placeholder="Effective Date" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="description">Purpose</label>
                                <select name="IPURPOSE" id="IPURPOSE" class="form-control">
                                    <option value="" disabled="" selected="">--Choose--</option>
                                    <option value="PKS">PKS</option>
                                    <option value="KEBUN">KEBUN</option>
                                    <option value="REFINANCING">REFINANCING</option>
                                    
                                </select>
                            </div><div class="col-md-2">
                                <label for="description">IDC</label>
                                <select name="IIDC" id="IIDC" class="form-control">
                                    <option value="" disabled="" selected="">--Choose--</option>
                                    <option value="WITH_IDC">IDC</option>
                                    <option value="WITHOUT_IDC">NON IDC</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="description">Sub Credit Type *</label>
                                <select name="ISUB_CREDIT_TYPE" id="ISUB_CREDIT_TYPE" class="form-control">
                                    <option value="" disabled="" selected="">--Choose--</option>
                                    <option value="FINANCING">FINANCING</option>
                                    <option value="REFINANCING">REFINANCING</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Loan Account Number *</label>
                                    <input type="text" class="form-control" name="ILOAN_ACCOUNT_NUMBER" id="ILOAN_ACCOUNT_NUMBER" placeholder="Loan Account Number" required>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                            <label for="currency">Currency *</label>
                            <select class="form-control" name="ICURRENCY" id="ICURRENCY" required>
                                <option value="" selected="" disabled="">Choose Currency</option>
                                <?php
                                    foreach ($DtCurrency as $values) {
                                        echo '<option value=' . $values->DETAILID . '>' . $values->DETAILNAME . '</option>';
                                    }
                                    ?>
                            </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="currency">Exc. Rate</label>
                                <input type="text" class="form-control" name="IRATE" id="IRATE" placeholder="Exc Rate" required>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Limit Tranche *</label>
                                    <input type="text" class="form-control" data-type='currency' name="ILIMIT_TRANCHE" id="ILIMIT_TRANCHE" placeholder="Limit Tranche" data-type='currency' required>
                                </div>
                            </div>
                            <!-- not used -->
                            <!-- <div class="col-md-3">
                                <div class="form-group">
                                    <label>Payback Period *</label>
                                    <input type="date" class="form-control mb-2" name="IPAYBACK_PERIOD" id="IPAYBACK_PERIOD" placeholder="Payback Period" required>
                                </div>
                            </div> -->
                            <!-- not used -->
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                    <label>Avail Period *</label>
                                    <input type="date" class="form-control mb-2" name="IAVAIL_PERIOD_FROM" id="IAVAIL_PERIOD_FROM" placeholder="Avail Period From" required>
                                    <label>To</label>
                                    <input type="date" class="form-control" name="IAVAIL_PERIOD_TO" id="IAVAIL_PERIOD_TO" placeholder="Avail Period To" required>
                            </div>
                            <div class="col-md-3">
                                    <label>Grace Period </label>
                                    <input type="date" class="form-control mb-2" name="IGRACE_PERIOD_FROM" id="IGRACE_PERIOD_FROM" placeholder="Grace Period From">
                                    <label>To</label>
                                    <input type="date" class="form-control" name="IGRACE_PERIOD_TO" id="IGRACE_PERIOD_TO" placeholder="Grace Period To" >
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for='remarks_addendum'>Facility Purposes</label>
                                    <textarea class="form-control" id="IADD_REMARKS" name="IADD_REMARKS" rows="6"></textarea>
                                </div>
                            </div>
                        </div>  
                        <hr>
                            <div class="d-flex align-items-center instIDC">
                                <div class="col-md-1 instIDC" hidden>
                                    <button id="InstIDC" data-target="#collapseTbIntIDC" class="btn btn-sm btn-primary fa fa-list" data-toggle="collapse" type='button' data-parsley-excluded="true" aria-expanded="false" aria-controls="collapseTbIntIDC">
                                        </button>
                                </div>
                                <div class="col-md-4 instIDC" hidden>
                                    <h5 >Installment IDC</h5>
                                </div>
                            </div>
                            <div id="collapseTbIntIDC" class="collapse">
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col mt-2 fileupload-buttonbar">
                                            <div class="col-md-4">
                                            <label> Upload Installment File </label>
                                                <span class="btn btn-primary fileinput-button m-r-3">
                                                    <!--<i class="fa fa-plus"></i>-->
                                                    <span>Browse File</span>
                                                    <input type="file" class="upload-file-installment-idc" data-max-size="31457280" onchange="uploadInstallmentidc(this)">
                                                </span>
                                                <button id="btnReset" type="button" class="btn btn-default m-r-3" onclick="ClearDataIDC()" disabled="disabled">
                                                    <!--<i class="fa fa-upload"></i>-->
                                                    <span>Clear Data</span>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- <input id="TNUMBER" type="hidden" value=''> -->
                                        </div>
                                        <div class="row m-0 table-responsive">
                                            <table id="tbInstallmentIDC" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" width="100%" aria-describedby="tbDetails_info" style="width: 100%;">
                                            <thead>
                                                <th class="text-center sorting-disabled">No</th>
                                                <th class="text-center sorting-disabled">Month</th>
                                                <th class="text-center sorting-disabled">Year</th>
                                                <th class="text-center sorting-disabled">Amount Installment</th>
                                            </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary closex" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="SaveWADetails()">Save</button>
                        <input style="display:none;" type="button" class="btn btn-info" id="updateTemp" value="Update">
                    </div>
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
                                    <th class="text-center sorting_disabled"></th>
                                    <th class="text-center sorting_disabled">No</th>
                                    <th class="text-center sorting_disabled">Addendum Date</th>
                                    <th class="text-center sorting_disabled">Remarks</th>
                                    <th class="text-center sorting_disabled">Docdate</th>
                                    <th class="text-center sorting_disabled">Bank</th>
                                    <th class="text-center sorting_disabled">Maturity</th>
                                    <th class="text-center sorting_disabled">Fee</th>
                                    <th class="text-center sorting_disabled">Tenor</th>
                                    <th class="text-center sorting_disabled">Currency</th>
                                    <th class="text-center sorting_disabled">Provisi</th>
                                    <th class="text-center sorting_disabled">Interest Rate</th>
                                    <th class="text-center sorting_disabled">Interest Payment Schedule</th>
                                    <th class="text-center sorting_disabled">Interest Payment Schedule Date</th>
                                    <th class="text-center sorting_disabled">Installment Period</th>
                                    <th class="text-center sorting_disabled">Annual Fee</th>
                                    <th class="text-center sorting_disabled">Upfront Fee</th>
                                    <th class="text-center sorting_disabled">Commit Fee</th>
                                    <th class="text-center sorting_disabled">Loan Number</th>
                                    <th class="text-center sorting_disabled">Admin Fee</th>
                                    <th class="text-center sorting_disabled">Agency Fee</th>
                                    <th class="text-center sorting_disabled">Installment Type</th>
                                    <th class="text-center sorting_disabled">Docum.</th>
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
    <!-- modal installment -->
    <div class="modal fade" id="MInstallment">
        <div class="modal-dialog modal-lg" style="max-width: 75%  !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Installment Tranche</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <!-- <?php if( $DtKmk->IS_ACC == 1 && $DtKmk->IS_ADDENDUM == 1)  { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning">PERINGATAN !!! Installment dapat diupdate setelah melakukan Addendum Detail</div>
                        </div>
                    </div>
                <?php } ?> -->
                <div class="row">
                    <div class="col mt-2 fileupload-buttonbar">
                        <div class="col-md-4">
                        <label> Upload Installment File </label>
                            <span class="btn btn-primary fileinput-button m-r-3">
                                <!--<i class="fa fa-plus"></i>-->
                                <span>Browse File</span>
                                <input type="file" class="upload-file-installment" data-max-size="31457280" onchange="uploadInstallment(this)">
                            </span>
                            <button id="btnReset" type="button" class="btn btn-default m-r-3" onclick="ClearData()" disabled="disabled">
                                <!--<i class="fa fa-upload"></i>-->
                                <span>Clear Data</span>
                            </button>
                        </div>
                    </div>
                    <input id="TNUMBER" type="hidden" value=''>
                    </div>
                    <div class="row m-0 table-responsive">
                        <table id="tbInstallment" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" width="100%" aria-describedby="tbDetails_info" style="width: 100%;">
                        <thead>
                            <th class="text-center sorting-disabled">No</th>
                            <th class="text-center sorting-disabled">Month</th>
                            <th class="text-center sorting-disabled">Year</th>
                            <th class="text-center sorting-disabled">Amount Installment</th>
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
    <!-- - -->
    <?php if (!empty($_GET)) { ?>
                        <div class="panel-footer text-left">
                            <button type="button" id="btnSaveDet" onclick="SaveWAHeader()" class="btn btn-primary btn-sm m-l-5">Save</button>
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
    var DATA = <?php echo json_encode($DtKmk); ?>;
    var COUNTER = DATA.COUNTER;
    var EDITTR = false ;
    var DtTrancheList_json = <?php echo json_encode($DtTranche); ?>;
    var DtTrancheList = [];
    var COUNTERBNK = 1 ;

    DtTrancheList_json.forEach((item, index, arr) => {
        DtTrancheList.push(item.TRANCHE_NUMBER);
    });
    
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
    var table, ACTION,UUID,ID,DtHeaderDetail,tbHeaderDet, tbInstallment;
    var IDXD, DETID, ISUBTYPE, IDOCDATE, IPERIODE, IFEE, IRATE,ICURRENCY,IPROVISI,IAMOUNT_LIMIT;
    var COMPANY1 ;
    $(document).ready(function () {
        if (getUrlParameter('type') == "edit" || getUrlParameter('type') == "add") {
            UrlParam = getUrlParameter('type');
            if (getUrlParameter('type') == "add") {
                if (ADDS != 1) {
                    $('#btnSave').remove();
                }
                $('#btnSaveDet').remove();
                $('#RATE').attr('disabled', 'disabled');
                SetDataKosong();
            } else {
                if (EDITS != 1) {
                    $('#btnSave').remove();
                }
                var data = <?php echo json_encode($DtKmk); ?>;
                var bankKI = <?php echo json_encode($bankKI) ; ?>;
                // $.ajax({
                //     dataType : 'JSON',
                //     type     : 'POST',
                //     url    : "<?php echo site_url('Kmk/GetDataKI'); ?>",
                //     data : {
                //         UUID : DATA.LID
                //     },
                //     success : function(response) {
                //         var data = response.result.data ;
                //         if(data.DOCDATE_DETAIL == null) {
                //             $('#btnDetail').attr('disabled','disabled');
                //         }
                //     }
                // }) ;
                // console.log(tranchenumlist);
                $('#FAddEditForm input').attr('disabled', 'disabled');
                $('#FAddEditForm select').attr('disabled', 'disabled');
                SetData(data, bankKI);
                // if(data.IS_ACC == '1') {
                //     $('body').find('.upload-file-installment').attr('disabled', 'disabled');
                // }
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
                    setTimeout(function(){
                        if((data.IS_ACC == '1' || data.IS_ACC == '0' || data.IS_ACC == '2') && getUrlParameter('vm') == "vm"){
                            $('.delete').addClass('d-none');
                            $('.editdet').addClass('d-none');
                        }
                    },1000);
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
                            URL:"KI"
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
                    {"data": "CONTRACT_NUMBER"},
                    {"data": "PK_NUMBER"},
                    {"data": "FCNAME"},
                    {"data": "SUB_CREDIT_TYPE"},
                    {"data": "DOCDATE"},
                    // {"data": "CURRENCY"},
                    // {
                    //     "data": "AMOUNT_LIMIT",
                    //     "className": "text-right",
                    //     render: $.fn.dataTable.render.number(',', '.', 2)
                    // },
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
                            //     html += '<button class="btn btn-info btn-icon btn-circle btn-sm mr-2 view" title="view data" data-id="'+data.UUID+'"><i class="fa fa-eye"></i></button>';
                            // }
                            // if (EDITS == 1) {
                            //     html += '<button class="btn btn-green btn-icon btn-circle btn-sm mr-2 export" title="export data" data-company="'+data.COMPANY+'" data-docnumber="'+data.DOCNUMBER+'"><i class="fa fa-print"></i></button>';
                            // }
                            if (DELETES == 1) {
                                html += '<button class="btn btn-danger btn-icon btn-circle btn-sm ml-4 delete" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                            }
                            return html;
                        },
                        "width" : "13%"
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
                    // EDITTR = true;
                    var data = table.row($tr).data();
                    window.location.href = window.location.href + '?type=edit&UUID=' + data.UUID;
                });
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
                //                 $('#VDENDAPERCENTAGE').val(response[0].DENDA_PERCENTAGE);
                //                 $('#VPENALTYPERCENTAGE').val(response[0].PENALTY_PERCENTAGE);
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
                                    alert(response.result.data);
                                    table.ajax.reload();
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
                        "url": "<?php echo site_url('Kmk/getHistoryDocKI'); ?>",
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
                        "className" : 'text-center showChild',
                        "orderable" : false,
                        "data" : null,
                        render : function (data, type, row, meta) {
                            var html = '<button class="btn btn-primary btn-icon btn-circle btn-sm editdet" title="" style="margin-right: 5px;">\n\
                                                <i class="fas fa-plus" aria-hidden="true"></i>\n\
                                                </button>'     
                            return html ;
                        }
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            // console.log(meta.row);
                            var valret ;
                            if(meta.row == 0) {
                                    valret = 'Master' 
                                } else {
                                    valret = 'Addendum ' + (meta.row)
                                }
                            return(
                                valret
                            ) ;
                        }
                    },
                    {"data": "ADDENDUM_DATE"},
                    {"data": "ADD_REMARK"},
                    {"data": "DOCDATE_DETAIL"},
                    {"data": "GETBANK"},
                    {"data": "MATURITY_DATE"},
                    {
                        "data": "FEE",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {"data": "TENOR"},
                    {"data": "CURRENCY"},
                    {
                        "data": "PROVISI",
                        "className": "text-right"
                    },
                    {
                        "data": "INTEREST",
                        "className": "text-right"
                    },
                    {"data": "INTEREST_PAYMENT_SCHEDULE"},
                    {"data": "INTEREST_PAYMENT_SCHEDULE_DATE"},
                    {"data": "INSTALLMENT_PERIOD"},
                    {
                        "data": "ANNUAL_FEE",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "UPFRONT_FEE",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "COMMIT_FEE",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {"data": "LOAN_ACCOUNT_NUMBER"},
                    {
                        "data": "ADM_FEE",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AGENCY_FEE",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {"data": "INSTALLMENT_TYPE"},
                    {render: function(data, type, row, meta) {
                        var html = ''
                        if(row.FILENAME != null) {
                            html = "<div id='link-file-dwn'><a href='assets/file/"+row.FILENAME+"' id='link-file-dwn'>"+row.FILENAME+"</a></div>"
                        }
                        return html
                    }}
                    ],
                    // responsive: {
                    //     details: {
                    //         renderer: function (api, rowIdx, columns) {
                    //             var data = $.map(columns, function (col, i) {
                    //                 return col.hidden ?
                    //                 '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                    //                 '<td>' + col.title + '</td> ' +
                    //                 '<td>:</td> ' +
                    //                 '<td>' + col.data + '</td>' +
                    //                 '</tr>' :
                    //                 '';
                    //             }).join('');
                    //             return data ? $('<table/>').append(data) : false;
                    //         }
                    //     }
                    // },
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
    tbl_adddet = $('#tbDetails').DataTable();
    tbl_adddet.on('click', '#link-file-dwn', function () {
        $tr = $(this).closest('tr');
        var data = tbl_adddet.row($tr).data();
        window.open("<?php echo base_url('assets/file/')?>" + data.FILENAME,'_blank');
    })
    var Add = function () {
        window.location.href = window.location.href + '?type=add';
    };
    function Cancel() {
        history.go(-1)
    }
    function Edit(ID){
        window.location.href = "<?php echo site_url('KMKMasterKI'); ?>" + '?type=edit&UUID=' + ID;
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

    function SetDataKosong() {
        $('.panel-title').text('Add Data');
        ID = "0";
        $('#COMPANY').val('');
        $('#BUSINESSUNIT').val('');
            // $('#ISACTIVE').val('TRUE');
        ACTION = 'ADD';

    }

    function SetBankKI(data, option) {
        var length = data.length ;
        var html = '' ;
        var port ;
        // console.log(length);
        // console.log(data);
        for (var index = 0 ; index < length ; index++) {
            port = fCurrency(data[index]['PORTION']);
            if(index == 0) {
                html += `
                            <div class="row" id="template-bank`+data[index]['ID']+`">
                                        <input type="hidden" id="ID_REC" name="ID_REC[]" value="`+data[index]['ID']+`">
                                    <div class="col-4">
                                        <div class="form-group " id="field-bank">
                                            <label for="BANK_SYNDICATION">Bank Syndication *</label>
                                            <select class="form-control bank_synd" name="BANK_SYNDICATION[]" id="BANK_SYNDICATION" required>
                                                <option value=""  disabled=""> Choose Bank </option>
                                                `+option+`
                                                <option value="`+data[index]['BANKNAME']+`"  selected=""> `+data[index]['FCNAME']+` </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-2" >
                                        <div id="curr_select">
                                            <label for="currency">Bank Portion *</label>
                                            <select class="form-control col" name="AG_SYND_CURRENCY[]" id="AG_SYND_CURRENCY" required="">
                                                <option value="" disabled="">Choose Currency</option>
                                                <option value="`+data[index]['CURRENCY']+`"  selected=""> `+data[index]['CURRENCY']+` </option>
                                                <?php
                                                    foreach ($DtCurrency as $values) {
                                                        echo '<option value=' . $values->DETAILID . '>' . $values->DETAILNAME . '</option>';
                                                    }
                                                    ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="bank_portion">Nominal</label>
                                        <input type="text" class="form-control" name="BANK_PORTION[]" id="BANK_PORTION" data-type="currency" value="`+port+`" required >
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="" >
                                            <div class="btn btn-primary" id="field_adder" name="ID_REC[]" value="`+data[index]['ID']+`">
                                                <div class="fa fa-plus">
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                    </div>
                `
            } 
            else {
                html += `
                            <div class="row" id="template-bank`+data[index]['ID']+`">
                                        <input type="hidden" id="ID_REC" name="ID_REC[]" value="`+data[index]['ID']+`">
                                    <div class="col-4">
                                        <div class="form-group " id="field-bank">
                                            <label for="BANK_SYNDICATION">Bank Syndication *</label>
                                            <select class="form-control bank_synd" name="BANK_SYNDICATION[]" id="BANK_SYNDICATION" required>
                                                `+option+`
                                                <option value="`+data[index]['BANKNAME']+`"  selected=""> `+data[index]['FCNAME']+` </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-2" >
                                        <div id="curr_select">
                                            <label for="currency">Bank Portion *</label>
                                            <select class="form-control col" name="AG_SYND_CURRENCY[]" id="AG_SYND_CURRENCY" required="">
                                                <option value="" disabled="">Choose Currency</option>
                                                <option value="`+data[index]['CURRENCY']+`"  selected=""> `+data[index]['CURRENCY']+` </option>
                                                <?php
                                                    foreach ($DtCurrency as $values) {
                                                        echo '<option value=' . $values->DETAILID . '>' . $values->DETAILNAME . '</option>';
                                                    }
                                                    ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="bank_portion">Nominal</label>
                                        <input type="text" class="form-control" name="BANK_PORTION[]" id="BANK_PORTION" data-type="currency" value="`+port+`" required >
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="" >
                                            <div class="btn btn-danger" id="field_deleter" name="ID_REC[]" value="`+data[index]['ID']+`">
                                                <div class="fa fa-minus">
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                    </div>
                `
            }
        }
        $('#existing_field').append(html);
        var option = "<option value=''>Select Facility</option>";
        for(var index = 0 ; index < length ; index++) {
            if(DATA.AG_FEE_FAC == data[index]['BANKNAME']) {
                option += `<option value="`+data[index]['BANKNAME']+`" selected>`+data[index]['FCNAME']+`</option>` ;    
            }
            else {
                option += `<option value="`+data[index]['BANKNAME']+`">`+data[index]['FCNAME']+`</option>` ;
            }
        }

        $('#AG_FEE_FAC').html(option);


    }
    var CREDIT_TYPE,SUB_CREDIT_TYPE,IS_ACC;

    function SetData(data, bankKI) {
        // console.log(data);
        COMPANY1 = data.COMPANY ;
        var interest = data.INTEREST != null ? data.INTEREST : '0';
        var triminterest = interest.trim(); 
        if(data.CTYPE == 'SINGLE')
        {
            $("#SYNDICATE").hide();
            $('#FAddEditFormbd').parent('.col-7').removeClass('col-7').addClass('col')
        }
        var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
        // console.log(data);
        $('.panel-title').text('Edit Data KI');
        UUID = data.LID;
        $('#COMPANY option[value='+data.COMPANY+']').attr('selected','selected');
        // $('#COMPANY').attr('readonly', true);
        $('#BUSINESSUNIT').append($('<option>', {
            value: data.BUID,
            text: data.BUFCNAME
        }));
        $('#PK_NUMBER').val(data.PK_NUMBER);
        $('#TOBANK').val(data.TOBANK);
        $('#CREDIT_TYPE option[value='+data.CREDIT_TYPE+']').attr('selected','selected');
        $('#PROVISI_TYPE option[value='+data.PROVISI_TYPE+']').attr('selected','selected');
        // $('#SUB_CREDIT_TYPE option[value='+data.SUB_CREDIT_TYPEMASTER+']').attr('selected','selected');
        $('#IDC_STATUS option[value='+data.IDC_STATUS+']').attr('selected','selected');
        $('#CTYPE option[value='+data.CTYPE+']').attr('selected', 'selected');
        html = "<option value='" + data.GETBANK + "'>" + data.GETBANK + '</option>';
        $("#BANK").html(html);
        htmlRefCon = "<option value='" + data.REF_CONTRACT + "'>" + data.REF_CONTRACT + '</option>';
        $("#REF_CON").html(htmlRefCon);
        // $('#BANK').attr('readonly', true); 
        $('#PERIODE').val(data.PERIODE);
        $('#INTEREST').val(triminterest);
        $('#RATE').val(fCurrency(data.RATE != null ? data.RATE : '0'))
        $('#CURRENCY').val(data.CURRENCY);
        $('#ADM_FEE_CURRENCY').val(data.ADM_FEE_CURRENCY);
        $('#AG_FEE_CURRENCY').val(data.AG_FEE_CURRENCY);
        $('#FEE').val(data.FEE);
        $('#ANNUAL_FEE').val(data.ANNUAL_FEE);
        $('#UPFRONT_FEE').val(data.UPFRONT_FEE);
        $('#COMMIT_FEE').val(data.COMMIT_FEE);
        $('#ADD_REMARKS').val(data.ADD_REMARK);
        $('#ADDENDUM_DATE').val(data.ADDENDUM_DATE);
        $('#FIRST_DATE_INTEREST_PAYMENT').val(data.FIRST_DATE_INTEREST_PAYMENT);
        $('#PRE_PAYMENT_PENALTY').val(data.PRE_PAYMENT_PENALTY);
        $('#UUID').val(UUID);
        // if(data.COUNTER == '0' || data.COUNTER == null) {
        //     $('#RATE').attr('disabled','disabled')
        // }
        if(data.ADM_FEE == null || data.ADM_FEE == ''){
            $('#ADM_FEE').val();
        }else{
            $('#ADM_FEE').val(fCurrency(data.ADM_FEE));
        }
        if( data.AGENCY_FEE == '' || data.AGENCY_FEE == null){
            $('#AGENCY_FEE').val();
        }else{
            $('#AGENCY_FEE').val(fCurrency(data.AGENCY_FEE));
        }
        if(data.CTYPE == 'SINGLE') {
            $("#AG_FEE_CURRENCY").attr('disabled', true);
            $("#AG_FEE_CURRENCY").attr('required', false);
            $("#AGENCY_FEE").attr('disabled', true);
            $("#AGENCY_FEE").attr('required', false);
            $("#AG_FEE_FAC").attr('disabled', true);
            $("#AG_FEE_FAC").attr('required', false);
        } else {
            $("#AG_FEE_CURRENCY").attr('disabled', false);
            $("#AG_FEE_FAC").attr('disabled', false);
            $("#AGENCY_FEE").attr('disabled', false);
        }
        $('#PROVISI').val(data.PROVISI);
        $('#TENOR').val(data.TENOR);
        $('#DOCDATE').val(moment(data.DOCDATE_DETAIL).format('YYYY-MM-DD'));
        $('#INTEREST_PAYMENT_SCHEDULE option[value='+data.INTEREST_PAYMENT_SCHEDULE+']').attr('selected','selected');
        $('#INTEREST_PAYMENT_SCHEDULE_DATE').val(data.INTEREST_PAYMENT_SCHEDULE_DATE);
        // $('#INTEREST_PERIOD_FROM').val(moment(data.INTEREST_PERIOD_FROM).format('YYYY-MM-DD'));
        // $('#INTEREST_PERIOD_TO').val(moment(data.INTEREST_PERIOD_TO).format('YYYY-MM-DD'));
        $('#MATURITY_DATE').val(moment(data.MATURITY_DATE).format('YYYY-MM-DD'));
        $('#INSTALLMENT_PERIOD option[value='+data.INSTALLMENT_PERIOD+']').attr('selected','selected');
        $('#INSTALLMENT_TYPE option[value='+data.INSTALLMENT_TYPE+']').attr('selected','selected');
        ACTION = 'EDIT';
        CREDIT_TYPE = data.CREDIT_TYPE;
        SUB_CREDIT_TYPE = data.SUB_CREDIT_TYPE;
        // if((CREDIT_TYPE == "KMK" && SUB_CREDIT_TYPE == "BD") || (CREDIT_TYPE == "KMK" && SUB_CREDIT_TYPE == "RK") ){
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
        $('.WA').removeClass('d-none');
        // }
        // $('.btnSave').addClass('d-none');
    $.ajax({
        dataType: "JSON",
        type: "POST",
        url: "<?php echo site_url('Kmk/DtBankCompanyKI'); ?>",
        data: {
            'COMPANY' : COMPANY1
        },
        success: function(response, textStatus, jqXHR) {
            if (response.status == 200) {
                var option = '';
                $.each(response.result.data, function(index, value) {
                    option += "<option value='" + value.FCCODE + "'>" + value.FCNAME + '</option>';
                });
                // console.log(bankKI);
                if(bankKI.length == 0) {
                    $(option).insertAfter("#BANK_SYNDICATION option:first");
                }
                else {
                    SetBankKI(bankKI, option);
                }
                // $("#BANK").val(DValue);
                // $("#BANK").change();
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
    $('#IIDC').on('change', function(){
        if($(this).val() == 'WITH_IDC') {
            $('.instIDC').removeAttr('hidden');
        }
        else {
            $('.instIDC').prop('hidden', true);
        }
    })
    var AddDetail= function() {
            EDITTR = false ;
            DETID = null ;
            ACTIONM = 'ADD';
            ISUBTYPE = "";
            IDOCDATE = "";
            IPERIODE = "";
            IFEE = "";
            IRATE = "";
            ICURRENCY = "";
            IPROVISI = "";
            IAMOUNTLIMIT = "";
            $("#ISUBTYPE").val('');
            $("#IPERIODE").val('');
            $("#IFEE").val('');
            $("#IRATE").val('');
            $("#ICURRENCY").val('');
            $("#IPROVISI").val('');
            $("#IAMOUNT_LIMIT").val('');
            $("#IADD_REMARKS").val('');
            $('#FDetail').parsley().reset();
            $('#MDetail .modal-title').text("Add Data Detail");
            $("#MDetail").modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#InstIDC').attr('disabled', true);
        };

        $('#MDetail').on('hidden.bs.modal', function(e) {
          $(this).find('#FDetail')[0].reset();
        });
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
                        BUSINESSUNIT: $('#BUSINESSUNIT').val(),
                        BANK: BANKCODE,
                        CREDIT_TYPE: $('#CREDIT_TYPE').val(),
                        CTYPE   : $('#CTYPE').val(),
                        PK_NUMBER: $('#PK_NUMBER').val(),
                        // IDC_STATUS: $('#IDC_STATUS').val()
                        SUB_CREDIT_TYPE: "KI",
                        CONTRACT_REF : $('#REF_CON').val(),
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

    var SaveWAHeader = function () {
        // var is_addendum ;
        // var continue_save = false ;
        // if($('#IS_ADDENDUM').hasClass('active') && DATA.IS_ACC == 1) {
        //     if(confirm('!! CURRENTLY YOU ARE GOING TO CREATE AN ADDENDUM !! \n Are you sure want to continue ?') === true){
        //         is_addendum = 1 ;
        //         continue_save = true ;
        //     } else {
        //         is_addendum = 0 ;
        //         continue_save = false ;
        //     }
        // }
        // else if (DATA.IS_ACC == 1) {
        //     if(confirm('!! CURRENTLY YOU ARE EDITING CONTRACT !! \n Are you sure want to continue ?') === true){
        //         is_addendum = 1 ;
        //         continue_save = true ;
        //     } else {
        //         is_addendum = 0 ;
        //         continue_save = false ;
        //     }
        // } else {
        //     continue_save = true ;
        // }
        if ($('#FAddEditFormbd').parsley().validate() && (($('#CTYPE').val() == 'SYNDICATION') ? ($('#BANK_SYND').parsley().validate()) : 1)) {
                $("#loader").show();
                $('#loader').addClass('show');
                $('#btnSave').attr('disabled', true);
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Kmk/SaveKI'); ?>",
                    data: {
                        UUID: UUID,
                        PK_NUMBER:$('#PK_NUMBER').val(),
                        COMPANY: $('#COMPANY').val(),
                        TOBANK: $('#TOBANK').val(),
                        DOCDATE: $('#DOCDATE').val(),
                        PROVISI: $('#PROVISI').val(),
                        FEE: $('#FEE').val(),
                        ANNUAL_FEE: $('#ANNUAL_FEE').val(),
                        UPFRONT_FEE: $('#UPFRONT_FEE').val(),
                        COMMIT_FEE: $('#COMMIT_FEE').val(),
                        ADM_FEE: $('#ADM_FEE').val(),
                        AGENCY_FEE: $('#AGENCY_FEE').val(),
                        TENOR: $('#TENOR').val(),
                        MATURITY_DATE: $('#MATURITY_DATE').val(),
                        INTEREST: $('#INTEREST').val(),
                        CURRENCY: $('#CURRENCY').val(),
                        ADM_FEE_CURRENCY: $('#ADM_FEE_CURRENCY').val(),
                        AG_FEE_CURRENCY: $('#AG_FEE_CURRENCY').val(),
                        INTEREST_PAYMENT_SCHEDULE: $('#INTEREST_PAYMENT_SCHEDULE').val(),
                        INTEREST_PAYMENT_SCHEDULE_DATE: $('#INTEREST_PAYMENT_SCHEDULE_DATE').val(),
                        FIRST_DATE_INTEREST_PAYMENT: $('#FIRST_DATE_INTEREST_PAYMENT').val(),
                        INSTALLMENT_PERIOD:$('#INSTALLMENT_PERIOD').val(),
                        INSTALLMENT_TYPE:$('#INSTALLMENT_TYPE').val(),
                        ADDENDUM_DATE : $('#ADDENDUM_DATE').val(),
                        AG_FEE_FAC : $('#AG_FEE_FAC').val(),
                        // SUB_CREDIT_TYPE: $('#SUB_CREDIT_TYPE').val(),
                        ADD_REMARK : $('#ADD_REMARKS').val(),
                        PRE_PAYMENT_PENALTY : $('#PRE_PAYMENT_PENALTY').val(),
                        POS: $('#pos').val(),
                        CTYPE : $('#CTYPE').val(),
                        ACTION: ACTION,
                        USERNAME: USERNAME,
                        PROVISI_TYPE : $('#PROVISI_TYPE').val()
                    },
                    success: function (response) {
                        $('#btnSave').removeAttr('disabled');
                        if (response.status == 200) {
                            // toastr.success("Data Successfully Saved");
                            $('#FAddEditFormbd').parsley().reset();
                            
                            if($('#CTYPE').val() == 'SYNDICATION')
                            {
                                    if($('#BANK_SYND').parsley().validate()) {
                                    $.ajax({
                                        dataType: "JSON",
                                        type: "POST",
                                        url: "<?php echo site_url('Kmk/saveBankKI'); ?>",
                                        data: 
                                        $("#BANK_SYND").serialize() + '&UUID='+UUID +'&USERNAME='+USERNAME+'&COUNTER='+DATA.LAT_ADD,
                                        success : function(response) {
                                            $("#loader").hide();
                                            // console.log(response);
                                            $('#BANK_SYND').parsley().reset();
                                            toastr.success("Data Successfully Saved");
                                            if(confirm("Do you still want to continue editing the data?") == true && DATA.IS_ADDENDUM == "1")  {
                                                $("#loader").hide();
                                                // console.log(response);
                                                $('#BANK_SYND').parsley().reset();
                                            }
                                            else {
                                                setTimeout(function() { window.location.href = window.location.href.split("?")[0]; },1000);
                                            }

                                            // location.reload();
                                        },
                                        error : function(e) {
                                            console.log(e);
                                            toastr.error('failed to save')
                                        }
                                    })
                                }
                            }
                            else {
                                // console.log('clear');
                                toastr.success("Data Successfully Saved");
                                 setTimeout(function() { window.location.href = window.location.href.split("?")[0]; },1000);
                            }
                            $('#loader').removeClass('show');
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
                        $('#loader').removeClass('show');
                        console.info(e);
                        alert('Data Save Failed !!');
                        $('#btnSave').removeAttr('disabled');
                    }
                });
        }
        
    };

    var SaveWADetails = function () {
        // var is_addendum = 0 ;
        // if($('#IS_ADDENDUM').hasClass('active') && DATA.IS_ACC == 1) {
        //     if(confirm('!! CURRENTLY YOU ARE GOING TO CREATE AN ADDENDUM !! \n Are you sure want to continue ?') === true){
        //         is_addendum = 1 ;
        //         continue_save = true ;
        //     } else {
        //         is_addendum = 0 ;
        //         continue_save = false ;
        //     }
        // }
        // else if (DATA.IS_ACC == 1){
        //     if(confirm('!! CURRENTLY YOU ARE EDITING CONTRACT !! \n Are you sure want to continue ?') === true){
        //         is_addendum = 1 ;
        //         continue_save = true ;
        //     } else {
        //         is_addendum = 0 ;
        //         continue_save = false ;
        //     }
        // }
        // else {
        //     continue_save = true ;
        // }
        $("#loader").show();
        $('#loader').addClass('show');
        $('#btnSave').attr('disabled', true);
        var checknameexist = false;
        console.log(DtTrancheList);
        if(DtTrancheList && !(EDITTR)) {

            DtTrancheList.forEach((item, index, arr) => {
                var name = $('#ITRANCHE_NUMBER').val();
                if(name == item && !(checknameexist)) {
                    checknameexist = true ;
                }
            }) ;
        }
        if($('#FDetail').parsley().validate() && !(checknameexist)){
            $.ajax({
            dataType: "JSON",
            type: "POST",
            url: "<?php echo site_url('Kmk/SaveKITranche'); ?>",
            data: {
                ID: DETID,
                UUID: UUID,
                COMPANY: $('#COMPANY').val(),
                TRANCHE_NUMBER:$('#ITRANCHE_NUMBER').val(),
                EFFECTIVE_DATE:$('#IEFFECTIVE_DATE').val(),
                LOAN_ACCOUNT_NUMBER:$('#ILOAN_ACCOUNT_NUMBER').val(),
                INTEREST_PERIOD_FROM: $('#IINTEREST_PERIOD_FROM').val(),
                INTEREST_PERIOD_TO: $('#IINTEREST_PERIOD_TO').val(),
                INSTALLMENT_PERIOD:$('#IINSTALLMENT_PERIOD').val(),
                LIMIT_TRANCHE:$('#ILIMIT_TRANCHE').val(),
                AVAIL_PERIOD_FROM:$('#IAVAIL_PERIOD_FROM').val(),
                AVAIL_PERIOD_TO:$('#IAVAIL_PERIOD_TO').val(),
                GRACE_PERIOD_FROM:$('#IGRACE_PERIOD_FROM').val(),
                GRACE_PERIOD_TO:$('#IGRACE_PERIOD_TO').val(),
                INSTALLMENT_PERIOD_FROM:$('#IINSTALLMENT_PERIOD_FROM').val(),
                INSTALLMENT_PERIOD_TO:$('#IINSTALLMENT_PERIOD_TO').val(),
                SUB_CREDIT_TYPE: $('#ISUB_CREDIT_TYPE').val(),
                EXCHANGE_RATE : $('#IRATE').val(),
                CURRENCY : $('#ICURRENCY').val(),
                IDC : $('#IIDC').val(),
                PURPOSE : $('#IPURPOSE').val(),
                // PAYBACK_PERIOD:$('#IPAYBACK_PERIOD').val(),
                BANK_PORTION:$('#IBANK_PORTION').val(),
                ADD_REMARKS:$('#IADD_REMARKS').val(),
                ACTION: ACTION,
                USERNAME: USERNAME
            },
            success: function (response) {
                $("#loader").hide();
                $('#loader').removeClass('show');
                DtTrancheList.push($('#ITRANCHE_NUMBER').val());
                var table = $('#DtDetail').DataTable();
                // console.log(DtTrancheList);
                // $('#btnSave').removeAttr('disabled');
                if (response.status == 200) {
                    var data = response.result.data[1] ;
                    var idtranche = data.IDT ;
                    toastr.success("Data Successfully Saved");
                    if(DATA.IS_ADDENDUM == "1") {
                        if(confirm("Do you still want to continue editing the data?") == true)
                        {
                            $('#FDetail').parsley().reset();
                            $('#InstIDC').attr('disabled', false);
                            table.ajax.reload();
                            $('#MDetail').modal('hide');
                        }
                        else {
                            setTimeout(function() { window.location.href = window.location.href.split("?")[0]; },1000);
                        }
                    }
                    $('#FDetail').parsley().reset();
                    $('#InstIDC').attr('disabled', false);
                    // $('#MDetail').modal('hide');
                    // $('#MDetail').on('hidden.bs.modal', function(e) {
                    //   $(this).find('#FDetail')[0].reset();
                    // });
                   
                    DETID = idtranche;
                    EDITTR = true ;
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
                $('#loader').removeClass('show');
                $("#loader").hide();
                console.info(e);
                alert('Data Save Failed !!');
                $('#btnSave').removeAttr('disabled');
            }
        });
    }
    else {
        if(checknameexist) {
            toastr.error('Tranche Number already Exist');
        }
        $('#FDetail').parsley().reset()
        $("#loader").hide();
        $('#loader').removeClass('show');
    }
        
    };

    if (!$.fn.DataTable.isDataTable('#DtDetail')) {
                        $('#DtDetail').DataTable({
                            "bDestory" : true,
                            "bRetrieve" : true,
                            // "aaData": DtUpload,
                            "ajax": {
                                    "url": "<?php echo site_url('Kmk/HeaderDetailKI') ?>",
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
                            {"className": "text-center","data":"CONTRACT_NUMBER"},
                            {"className": "text-center","data":"TRANCHE_NUMBER"},
                            {"className": "text-center","data":"EFFECTIVE_DATE"},
                            {"className": "text-center","data":"LOAN_ACCOUNT_NUMBER"},
                            // {"className": "text-center","data":"INTEREST_PAYMENT_SCHEDULE"},
                            // {"className": "text-center","data":"INTEREST_PERIOD"},
                            // {"className": "text-center","data":"INSTALLMENT_PERIOD"},
                            {"className": "text-center","data":"CURRENCY"},
                            {
                                "data": "LIMIT_TRANCHE",
                                "className": "text-right",
                                render: $.fn.dataTable.render.number(',', '.', 2)
                            },
                            {"className": "text-center","data":"AVAIL_PERIOD"},
                            {"className": "text-center","data":"GRACE_PERIOD"},
                            // {"className": "text-center","data":"INSTALLMENT_PERIOD_DATE"},
                            // {"className": "text-center","data":"BANK_PORTION"},
                            {
                                        "data": null,
                                        "className": "text-center",
                                        "orderable": false,
                                        "width" : "13%",
                                        render: function (data, type, row, meta) {
                                            var html = '';
                                            if (EDITS == 1) {
                                                html += '<button class="btn btn-success btn-icon btn-circle btn-sm editdet" title="edit" style="margin-right: 5px;">\n\
                                                <i class="fas fa-edit" aria-hidden="true"></i>\n\
                                                </button>';
                                                html += '<button class="btn btn-warning btn-icon btn-circle btn-sm addistlmt" title="add installment" style="margin-right: 5px;">\n\
                                                    <i class="fas fa-bars" aria-hidden="true"></i>\n\
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
                        // tbInstallment = $('#tbInstallment').DataTable();
                        tbHeaderDet.on('click', '.editdet', function() {
                            EDITTR = true;
                            $tr = $(this).closest('tr');
                            var data = tbHeaderDet.row($tr).data();
                            // console.log(data);
                            DETID = data.ID;
                            $('#ITRANCHE_NUMBER').val(data.TRANCHE_NUMBER);
                            $('#IEFFECTIVE_DATE').val(moment(data.EFFECTIVE_DATE).format('YYYY-MM-DD'));
                            $('#ILOAN_ACCOUNT_NUMBER').val(data.LOAN_ACCOUNT_NUMBER);
                            $('#IINTEREST_PERIOD_FROM').val(moment(data.INTEREST_PERIOD_FROM).format('YYYY-MM-DD'));
                            $('#IINTEREST_PERIOD_TO').val(moment(data.INTEREST_PERIOD_TO).format('YYYY-MM-DD'));
                            $('#IINSTALLMENT_PERIOD option[value='+data.INSTALLMENT_PERIOD+']').attr('selected','selected');
                            $('#ILIMIT_TRANCHE').val(fCurrency(data.LIMIT_TRANCHE));
                            $('#IAVAIL_PERIOD_FROM').val(moment(data.AVAIL_PERIOD_FROM).format('YYYY-MM-DD'));
                            $('#IAVAIL_PERIOD_TO').val(moment(data.AVAIL_PERIOD_TO).format('YYYY-MM-DD'));
                            $('#IGRACE_PERIOD_FROM').val(moment(data.GRACE_PERIOD_FROM).format('YYYY-MM-DD'));
                            $('#IGRACE_PERIOD_TO').val(moment(data.GRACE_PERIOD_TO).format('YYYY-MM-DD'));
                            $('#IINSTALLMENT_PERIOD_FROM').val(moment(data.INSTALLMENT_PERIOD_FROM).format('YYYY-MM-DD'));
                            $('#IINSTALLMENT_PERIOD_TO').val(moment(data.INSTALLMENT_PERIOD_TO).format('YYYY-MM-DD'));
                            $('#ICURRENCY').val(data.CURRENCY);
                            $('#IRATE').val(data.EXCHANGE_RATE);
                            $('#IIDC').val(data.IDC);
                            $('#IPURPOSE').val(data.PURPOSE);
                            // $('#IPAYBACK_PERIOD').val(moment(data.PAYBACK_PERIOD).format('YYYY-MM-DD'));
                            $('#IBANK_PORTION').val(data.BANK_PORTION);
                            $('#IADD_REMARKS').val(data.ADD_REMARKS);
                            $('#ISUB_CREDIT_TYPE').val(data.SUB_CREDIT_TYPE);
                            $('#FDetail').parsley().reset();
                            $('#MDetail .modal-title').text("Edit Data Invoice");
                            // const cekDocval = IDOCNUMBER.includes("TMP");
                            // alert(IDOCNUMBER);
                            $("#MDetail").modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            if(data.IDC == 'WITH_IDC') {
                                $(".instIDC").removeAttr('hidden');
                            } else {
                                $(".instIDC").prop('hidden', true);
                            }
                        });
                        tbHeaderDet.on('click', '.delete', function () {
                                    $tr = $(this).closest('tr');
                                    var data = tbHeaderDet.row($tr).data();
                                    if (confirm('Are you sure delete this data ?')) {
                                        $.ajax({
                                            dataType: "JSON",
                                            type: "POST",
                                            url: "<?php echo site_url('Kmk/DeleteDetailKI'); ?>",
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
                        tbHeaderDet.on('click', '.addistlmt', function () {
                            $tr = $(this).closest('tr');
                            $("#tbInstallment").dataTable().fnDestroy();
                            // var tableInst = $('#tbInstallment').DataTable();
                            // tableInst.ajax.reload();
                        
                            var data = tbHeaderDet.row($tr).data();
                            // console.log(data);
                            $('#TNUMBER').val(data.TRANCHE_NUMBER);
                            $("#MInstallment").modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            if(!$.fn.DataTable.isDataTable("#DtInstallment")){
                                $('#tbInstallment').DataTable({
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
                                    "url": "<?php echo site_url('Kmk/getInstallment'); ?>",
                                    "datatype": "JSON",
                                    "type": "POST",
                                    "data": function (d) {
                                        d.UUID = DATA.UUID;
                                        d.COUNTER = DATA.LAT_ADD;
                                        d.TRANCHE_NUMBER = data.TRANCHE_NUMBER;
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
                                        "title" : "No",
                                        render : function(data, type, row, meta) {
                                            var num = parseInt(row.ID) + 1 ;
                                            return num ; 
                                        }
                                    },
                                    {
                                        "data" : "PERIOD_MONTH",
                                        "title" : "Month"
                                    },
                                    {
                                        "data" : "PERIOD_YEAR",
                                        "title" : "Year"
                                    },
                                    {
                                        "title" : "Installment Amount",
                                        "data" : "INSTALLMENT_AMOUNT",
                                        "className" : "text-center",
                                        render: $.fn.dataTable.render.number(',', '.', 2)
                                    }
                                ],
                                "bFilter": true,
                                "bPaginate": true,
                                "bLengthChange": false,
                                "bInfo": true
                            });
                            }
                    });
    }else{
        tbHeaderDet.ajax.reload();
    }

    $('body').find('#InstIDC').on('click',function() {
        $("#tbInstallmentIDC").dataTable().fnDestroy();
        // var tableInst = $('#tbInstallment').DataTable();
        // tableInst.ajax.reload();
        if(!$.fn.DataTable.isDataTable("#tbInstallmentIDC")){
            $('#tbInstallmentIDC').DataTable({
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
                "url": "<?php echo site_url('Kmk/getInstallmentidc'); ?>",
                "datatype": "JSON",
                "type": "POST",
                "data": function (d) {
                    d.UUID = DATA.UUID;
                    d.COUNTER = DATA.LAT_ADD;
                    d.TRANCHE_NUMBER = $("#ITRANCHE_NUMBER").val();
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
                    "title" : "No",
                    render : function(data, type, row, meta) {
                        var num = parseInt(row.ID) + 1 ;
                        return num ; 
                    }
                },
                {
                    "data" : "PERIOD_MONTH",
                    "title" : "Month"
                },
                {
                    "data" : "PERIOD_YEAR",
                    "title" : "Year"
                },
                {
                    "title" : "Installment Amount",
                    "data" : "INSTALLMENT_AMOUNT",
                    "className" : "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                }
            ],
            "bFilter": true,
            "bPaginate": true,
            "bLengthChange": false,
            "bInfo": true
        });
        }
    })
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
                    CREDIT_TYPE : 'KI'},
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

    });

    

</script>
<!-- formatting -->
<script type="text/javascript">
    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    // $("input[data-type='currency']").on({
    //     keyup: function() {
    //         formatCurrency($(this));
    //     },
    //     blur: function() {
    //         formatCurrency($(this), "blur");
    //     }
    // });

    // $(body).on('keyup', "input[data-type='currency']", function () {
    //     formatCurrency($(this));
    // })
    // $(body).on('blur', "input[data-type='currency']", function () {
    //     formatCurrency($(this), "blur");
    // })
    $("body").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            // console.log('test');
            formatCurrency($(this), "blur");
        }
    }, "input[data-type='currency']");
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
            {"className": "text-center","data":"FILENAME", "width":"5%"},
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
                "bLengthChange": false,
                "bInfo": true,
                "responsive": false,
                "autoWidth": false
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
            // console.log(fileInput);
            var extFile = $('.upload-file').val().split('.').pop().toUpperCase();
            // console.log(extFile);
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
                        console.log(files);
                        FILENAME = files[0].name;
                        $(".panel-title_").text('Document Upload : ' + FILENAME);

                        // DisableBtn();
                        var fd = new FormData();
                        $.each(files, function (i, data) {
                            fd.append("userfile[]", data);
                        });
                        fd.append("USERNAME", USERNAME);
                        fd.append("UUID",UUID);
                        fd.append("TIPE",$('#tipe_file').val());
                        fd.append("ADDENDUM_NUM", COUNTER);
                        // fd.append("EXTSYSTEM",$('#EXTSYSTEM').val());
                        // fd.append("DOCTYPE",$('#DOCTYPE').val());
                        // fd.append('UUID',UUID)
                        // fd.append('DATERELEASE',currentDate);
                        $.ajax({
                            dataType: "JSON",
                            type: 'POST',
                            url: "<?php echo site_url('Kmk/multiUploadFile'); ?>",
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
    // DisableBtn();

    $("body").on('click', '#field_adder',function() {
        var html = $("#field-bank").clone().html() ;
        var curr = $("#curr_select").clone().html() ;
        
        // console.log(html);
        // var clonedHtml = '<div class="d-flex row">'+
        //                 '<div class="form-group col-md-6">' +
        //                 `<input type="hidden" id="ID_REC" name="ID_REC[]" value="">`+
        //                 html +
        //                 '</div>'+
        //                 `<div class="form-group col-sm-4"><label for="BANK_PORTION">Bank Portion % *</label><input type="text" class="form-control" name="BANK_PORTION[]" id="BANK_PORTION" required></div>
        //                 <div class="form-group col-sm-2" id="field_deleter"><div class="btn btn-warning"><div class="fa fa-minus"></div></div></div>
        //                 </div> `;
        var clonedHtml = 
        `
            <div class="row" id="template-bank">
                    <input type="hidden" id="ID_REC" name="ID_REC[]" value="">
                <div class="col-4">
                    `
                    +html+
                    `
                </div>
                <div class="col-2" >
                    `
                    +curr+
                    `
                </div>
                <div class="form-group col-4">
                    <label for="bank_portion">-</label>
                    <input type="text" class="form-control" name="BANK_PORTION[]" id="BANK_PORTION" data-type="currency" required >
                </div>
                <div class="d-flex align-items-center">
                    <div class="" >
                        <div class="btn btn-danger" id="field_deleter" name="ID_REC[]" value="">
                            <div class="fa fa-minus">
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        `

        $('#additional_field').append(clonedHtml);

    });
    // var isdeleted = 0;
    $("body").on('click','#field_deleter' ,function() {
        // console.log( $(this)[0]['parentElement']['parentElement']['parentElement']['attributes']['id']['value']);
        // console.log($(this)[0]['parentElement']['parentElement']);
        // console.log(($(this)[0]['attributes']['value']));
            if($(this)[0]['attributes']['value']['value'] != "") {
                $(this).attr('disabled', true);
                $('#loader').addClass('show');
                $.ajax({
                dataType : "JSON",
                type : "POST",
                url : "<?php echo site_url('Kmk/deleteBankKI') ?>",
                data : {
                    'ID' : $(this)[0]['attributes']['value']['value'],
                    'EL' :  $(this)[0]['parentElement']['parentElement']['parentElement']['attributes']['id']['value']
                },
                success : function (response) {
                    var d = response.result
                    toastr.success(response.result.data[0]);
                    var id = '#' + d.data[1];
                    $(id).remove();
                    $('#loader').removeClass('show');
                    // isdeleted = 1 ;
                    // $(this).parents('.d-flex').remove();
                },
                error : function (e) {
                    toastr.error(e.result.data);
                }
            }) ;
            }
        else {
            $(this).parents('#template-bank').remove();
        }
    });

    $('#FIRST_DATE_INTEREST_PAYMENT').on('change', function () {
        var dt_first = new Date($('#FIRST_DATE_INTEREST_PAYMENT').val());
        var first_date = dt_first.getDate();
        $('#INTEREST_PAYMENT_SCHEDULE_DATE').val(first_date);
    });

    $(document).on('click', '.showChild', function () {
        var siteTable = $('#tbDetails').DataTable();
        var tr = $(this).closest('tr');
        var data = siteTable.row(tr).data();
        var row = siteTable.row( tr );
        // console.log(data);
        if ( row.child.isShown() ) {
            // This row is already open - close it
            destroyChild(row);
            tr.removeClass('shown');
        }
        else {
            // Open this row
            createChild(row, data);
            tr.addClass('shown');
        }
    });

    function destroyChild(row) {
        var table = $("table", row.child());
        table.detach();
        table.DataTable().destroy();
    
        // And then hide the row
        row.child.hide();
    }

    function createChild(row, data) {
        var table = $('<table class="display" width="50%"/>') ;
        row.child(table).show();
        var childTable = table.DataTable({
            "processing": true,
            "ajax": {
                "url": "<?php echo site_url('Kmk/getOWTrancheKI')?>",
                dataType: "JSON",
                type: "POST",
                "data": {
                    UUID : data.UUID,
                    COUNTER : data.COUNTER
                },
                "dataSrc" : function (ext) {
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
                "columns" : [
                    {
                        "title" : "Tranche Number",
                        "data"  : "TRANCHE_NUMBER"
                    },
                    {
                        "title" : "Loan Account Number",
                        "data"  : "LOAN_ACCOUNT_NUMBER"
                    },
                    {
                        "title" : "Effective Date",
                        "data"  : "EFFECTIVE_DATE"
                    },
                    {
                        "title" : "Limit",
                        "data"  :"LIMIT_TRANCHE",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    }
                ],
                "bFilter": true,
                "bPaginate": true,
                "bLengthChange": false,
                "bInfo": true
        });
    }
    function uploadInstallment(elm) {
        files = '';
        var filetypeUploadInst = ['XLS', 'XLSX'];
        var fileInput = $('.upload-file-installment');
        var extFile = $('.upload-file-installment').val().split('.').pop().toUpperCase();
        var maxSize = fileInput.data('max-size') ;
        if ($.inArray(extFile, filetypeUploadInst) === -1) {
            alert('Format file tidak valid !!');
            files = '';
        } else {
            if (fileInput.get(0).files.length) {
                var fileSize = fileInput.get(0).files[0].size ;
                if(fileSize > maxSize) {
                    alert('Ukuran file terlalu besar !!');
                    files = '';
                    $('.upload-file').val('');
                    return ;
                } else {
                    $('#loader').addClass('show');
                    files = elm.files ;
                    FILENAME = files[0].name ;
                    var fd = new FormData();
                    $.each(files, function(i, data) {
                        fd.append('uploads', data);
                    });
                    fd.append('UUID', DATA.UUID ) ;
                    fd.append('TRANCHE_NUMBER', $('#TNUMBER').val());
                    fd.append('PK_NUMBER', $('#PK_NUMBER').val());
                    fd.append('CONTRACT_TYPE', $('#CTYPE').val());
                    fd.append('USERNAME', USERNAME);
                    fd.append('COUNTER', DATA.LAT_ADD);

                    $.ajax({
                        dataType : 'JSON',
                        type : 'POST',
                        url : "<?php echo site_url('Kmk/UploadInstallment'); ?>",
                        data: fd,
                        processData : false,
                        contentType : false,
                        success : function (response) {
                            // console.log('success');
                            $('#loader').removeClass('show');
                            $('.upload-file-installment').val('');
                            
                            if(response.status == 500) {
                                
                                toastr.error(response.result.data);
                            }
                            else {
                                $('#MInstallment').modal('toggle');
                                toastr.success(response.result.data);
                            }
                        },
                        error : function (error) {
                            console.log(error);
                            $('#loader').removeClass('show'); 
                            $('.upload-file-installment').val('');
                        }
                    });
                }
            }
            

        }
    }

    function uploadInstallmentidc(elm) {
        files = '';
        var filetypeUploadInst = ['XLS', 'XLSX'];
        var fileInput = $('.upload-file-installment-idc');
        var extFile = $('.upload-file-installment-idc').val().split('.').pop().toUpperCase();
        var maxSize = fileInput.data('max-size') ;
        if ($.inArray(extFile, filetypeUploadInst) === -1) {
            alert('Format file tidak valid !!');
            files = '';
        } else {
            if (fileInput.get(0).files.length) {
                var fileSize = fileInput.get(0).files[0].size ;
                if(fileSize > maxSize) {
                    alert('Ukuran file terlalu besar !!');
                    files = '';
                    $('.upload-file').val('');
                    return ;
                } else {
                    $('#loader').addClass('show');
                    files = elm.files ;
                    FILENAME = files[0].name ;
                    var fd = new FormData();
                    $.each(files, function(i, data) {
                        fd.append('uploads', data);
                    });
                    fd.append('UUID', DATA.UUID ) ;
                    fd.append('TRANCHE_NUMBER', $('#ITRANCHE_NUMBER').val());
                    fd.append('PK_NUMBER', $('#PK_NUMBER').val());
                    fd.append('CONTRACT_TYPE', $('#CTYPE').val());
                    fd.append('USERNAME', USERNAME);
                    fd.append('COUNTER', DATA.LAT_ADD);

                    $.ajax({
                        dataType : 'JSON',
                        type : 'POST',
                        url : "<?php echo site_url('Kmk/UploadInstallmentidc'); ?>",
                        data: fd,
                        processData : false,
                        contentType : false,
                        success : function (response) {
                            // console.log('success');
                            $('#loader').removeClass('show');
                            $('.upload-file-installment-idc').val('');
                            var tableidc = $('#tbInstallmentIDC').DataTable();
                            tableidc.ajax.reload();
                            toastr.success('Upload Success');
                        },
                        error : function (response) {
                            alert('failed to upload');
                            $('#loader').removeClass('show'); 
                            $('.upload-file-installment-idc').val('');
                        }
                    });
                }
            }
        }
    }

    $('.closex').on('click', function(){
        if($.fn.DataTable.isDataTable('#tbInstallmentIDC')) {
            $('#tbInstallmentIDC').dataTable().fnDestroy();
            $('#collapseTbIntIDC').collapse('hide');
        }
    })

    $('body').find("#BANK_SYND").on('click', function() {
        var selectdom = $('#AG_FEE_FAC option:selected') ;
        var objSelectDom = Object.values(selectdom);
        var elements = $('.bank_synd option:selected') ;
        var htmlbank = Object.values(elements) ;
        var maxlengthel = Object.values(elements).length;
        var option = `<option value = "">Select Facility</option>` ;
        for (var index = 0 ; index < (maxlengthel-2) ; index++) {
            if(objSelectDom[0].innerHTML.trim() == htmlbank[index].innerHTML.trim() ) {
                var clone = objSelectDom[0].cloneNode(true);
                option = clone.outerHTML;
            }
            var clone = htmlbank[index].cloneNode(true);
            clone.removeAttribute('selected');
            // htmlbank[index].removeAttribute('selected');
            option += clone.outerHTML;
        }
        // console.log(option);
        $('#AG_FEE_FAC').html(option);
        // Object.values(elements).forEach(item => {
        //     console.log(item);
        // })
    })

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
    


</script>