<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<!--<script src="./assets/js/datetime/moment-with-locales.min.js"></script>-->
<style>
    #overlay {
        position: fixed;
        /* Sit on top of the page content */
        display: none;
        /* Hidden by default */
        width: 100%;
        /* Full width (cover the whole page) */
        height: 100%;
        /* Full height (cover the whole page) */
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        /* Black background with opacity */
        z-index: 2;
        /* Specify a stack order in case you're using a different order for other elements */
        cursor: pointer;
        /* Add a pointer on hover */
    }
</style>
<?php
$CDepartment = '';
foreach ($DtDepartment as $values) {
    $CDepartment .= '<option value="' . $values->DEPARTMENT . '">' . $values->DEPARTEMENTNAME . '</option>';
}
?>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Entry Transaction</li>
</ol>
<h1 class="page-header">Entry Transaction</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Entry Transaction</h4>
    </div>

    <div class="panel panel-default" style="white-space: nowrap; height: 100%; overflow-x: scroll; overflow-y: hidden;">
        <div class="panel-body">
            <?php if (empty($_GET)) { ?>
                <div class="row mb-2">
                    <div class="col-md-8 pull-left">
                        <?php if ($ACCESS['ADDS'] == 1) { ?>
                            <button onclick="Add()" class="btn btn-sm btn-info" style="margin-right: 5px;"><i class="fa fa-plus"></i> Add</button>
                        <?php } ?>
                        <button onclick="VExport()" class="btn btn-sm btn-success"><i class="fa fa-file-excel"></i> Export</button>
                    </div>
                    <div class="col-md-2 pull-right">
                        <div class="input-group">
                            <select class="form-control" id="DEPARTMENT">
                                <option value="" selected>All Department</option>
                                <?php echo $CDepartment; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 pull-right">
                        <div class="input-group">
                            <input type="text" id="search" name="search" class="form-control" placeholder="Cari..">
                        </div>
                    </div>
                </div>
                <div class="row m-0 table-responsive">
                    <table id="EntryPO" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="EntryPO_info" style="width: 100%;">
                        <thead>
                            <tr role="row">
                                <th class="text-center" style="max-width: 30px;">No</th>
                                <th class="text-center">Department</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Business Unit</th>
                                <th class="text-center">Doc Type</th>
                                <th class="text-center">Doc Number</th>
                                <th class="text-center">Doc Date</th>
                                <th class="text-center">Vendor</th>
                                <th class="text-center">Currency</th>
                                <th class="text-center">Amount Include PPn</th>
                                <th class="text-center">Amount PPh</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                    </table>
                    <div id="overlay" style="display: none;">
                        <span class="spinner"></span>
                    </div>
                </div>
            <?php } else { ?>
                <!-- Add/Edit Page -->
                <form id="SaveEntryPO" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DEPARTMENT">Department *</label>
                                <select class="form-control roleaccess" name="DEPARTMENT" id="DEPARTMENT" required>
                                    <option value="" selected disabled>Choose Department</option>
                                    <?php echo $CDepartment; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="COMPANY">Company(PT) *</label>
                                <select class="form-control roleaccess" name="COMPANY" id="COMPANY" required>
                                    <option value="" selected disabled>Choose Company</option>
                                    <?php
                                    foreach ($DtCompany as $values) {
                                        echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="BUSINESSUNIT">Business Unit(BA) *</label>
                                <select class="form-control roleaccess" name="BUSINESSUNIT" id="BUSINESSUNIT" required>
                                    <option value="" selected disabled>Choose Business Unit</option>
                                    <?php
                                    foreach ($DtBusiness as $values) {
                                        echo '<option value="' . $values->ID . '">' . $values->FCCODE . ' - ' . $values->FCNAME . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DOCNUMBER">Document Number *</label>
                                <input type="text" class="form-control roleaccess" name="DOCNUMBER" id="DOCNUMBER" placeholder="Document Number" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="DOCTYPE">Document Type *</label>
                                <select class="form-control roleaccess" name="DOCTYPE" id="DOCTYPE" required>
                                    <option value="" selected readonly>Choose Doc. Type</option>
                                    <?php
                                    foreach ($DtDocType as $values) {
                                        echo '<option value=' . $values->FCCODE . '>' . $values->FCCODE . ' - ' . $values->FCNAME . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="DOCDATE">Doc Date *</label>
                                <div class="input-group date" id="DOCDATE2">
                                    <input type="text" class="form-control roleaccess" name="DOCDATE" id="DOCDATE" placeholder="MM/DD/YYYY" required />
                                    <div class="input-group-addon input-group-append">
                                        <div class="input-group-text">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="VENDORNAME">Vendor *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="VENDORNAME" name="VENDORNAME" readonly required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-default no-caret btn-sm" onclick="VVendor()"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="CURRENCY">Currency *</label>
                                <select class="form-control roleaccess" name="CURRENCY" id="CURRENCY" required>
                                    <option value="" selected disabled>Choose Currency</option>
                                    <?php
                                    foreach ($DtCurrency as $values) {
                                        echo '<option value=' . $values->DETAILID . '>' . $values->DETAILNAME . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="EXTSYS">EXTSYSTEM *</label>
                                <select class="form-control roleaccess" name="EXTSYS" id="EXTSYS" required>
                                    <option value="" selected disabled>Choose Extsystem</option>
                                    <?php
                                    foreach ($DtExtSystem as $values) {
                                        echo '<option value=' . $values->FCCODE . '>' . $values->FCNAME . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="VAT">PPn *</label>
                                <select class="form-control roleaccess" name="VAT" id="VAT" required>
                                    <option value="0">Exclude</option>
                                    <option value="1">Include</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="REMARK">Remark</label>
                                <input type="text" class="form-control roleaccess" name="REMARK" id="REMARK" placeholder="Remark">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="INVOICEVENDORNO">Invoice Vendor No</label>
                                <input type="text" class="form-control roleaccess" name="INVOICEVENDORNO" id="INVOICEVENDORNO" placeholder="Invoice Vendor No">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="AMOUNT_SOURCE">Amount Source</label>
                                <input type="text" class="form-control text-right" id="AMOUNT_SOURCE" name="AMOUNT_SOURCE" data-type="currency" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="AMOUNT_PPN">Amount PPn</label>
                                <input type="text" class="form-control text-right" id="AMOUNT_PPN" name="AMOUNT_PPN" data-type="currency">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="AMOUNT_PPH">Amount PPh</label>
                                <input type="text" class="form-control text-right" id="AMOUNT_PPH" name="AMOUNT_PPH" data-type="currency" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="AMOUNT_TOTAL">Amount Total</label>
                                <input type="text" class="form-control text-right" id="AMOUNT_TOTAL" name="AMOUNT_TOTAL" data-type="currency" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-secondary fade show">
                                <div class="panel panel-default panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
                                    <div class="panel-heading p-0">
                                        <div class="tab-overflow">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item"><a href="#nav-tab-1" data-toggle="tab" class="nav-link active" id="nav1">Detail PO</a></li>
                                                <li class="nav-item"><a href="#nav-tab-2" data-toggle="tab" class="nav-link" id="nav2">Invoice & PPh</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="nav-tab-1">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button id="btnMaterial" type="button" class="btn btn-sm btn-info" onclick="AddMaterial()"><i class="fa fa-plus"></i> Add</button>
                                                </div>
                                            </div>
                                            <div class="row m-0 table-responsive">
                                                <table id="DetailPOList" class="table table-bordered table-hover dataTable no-footer dtr-inline" role="grid" width="100%" style="width: 100%;">
                                                    <thead>
                                                        <tr role="row">
                                                            <th class="text-center align-middle" rowspan="2" style="width: 30px;">No</th>
                                                            <th class="text-center" colspan="2">Material</th>
                                                            <th class="text-center align-middle" rowspan="2">Remark</th>
                                                            <th class="text-center" colspan="3">Amount</th>
                                                            <th class="text-center" rowspan="2"></th>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-center">Code</th>
                                                            <th class="text-center">Name</th>
                                                            <th class="text-center">Source</th>
                                                            <th class="text-center">PPn</th>
                                                            <!--<th class="text-center">PPh</th>-->
                                                            <th class="text-center">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr role="row">
                                                            <th class="text-right" colspan="4">Total</th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade show" id="nav-tab-2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button id="btnInvoice" type="button" onclick="AddInvoice()" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> Add</button>
                                                </div>
                                            </div>
                                            <div class="row m-0 table-responsive">
                                                <table id="DtInvoice" class="table table-bordered table-hover dataTable" role="grid" width="100%" style="width: 100%;">
                                                    <thead>
                                                        <tr role="row">
                                                            <th class="text-center align-middle" rowspan="2" style="width: 30px;">No</th>
                                                            <th class="text-center align-middle" rowspan="2">Vendor</th>
                                                            <th class="text-center align-middle" rowspan="2">Invoice</th>
                                                            <th class="text-center" colspan="3">Date</th>
                                                            <th class="text-center" colspan="4">Amount</th>
                                                            <th class="text-center align-middle" rowspan="2">Remark</th>
                                                            <th class="text-center align-middle" rowspan="2">Faktur</th>
                                                            <th class="text-center align-middle" rowspan="2"></th>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-center">Document</th>
                                                            <th class="text-center">Baseline</th>
                                                            <th class="text-center">Due</th>
                                                            <th class="text-center">Source</th>
                                                            <th class="text-center">PPn</th>
                                                            <th class="text-center">PPh</th>
                                                            <th class="text-center">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr role="row">
                                                            <th class="text-right" colspan="6"> Total</th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th colspan="3"></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>`
                                            </div>
                                        </div>
                                        <div class="tab-pane fade show roleaccess" id="nav-tab-3">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="AMOUNT_PPH">Amount PPh *</label>
                                                    <input type="text" class="form-control text-right" name="AMOUNT_PPH" id="AMOUNT_PPH" data-type='currency' placeholder="Amount PPh">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="PPH_FAKTUR_PAJAK">Faktur Pajak *</label>
                                                    <input type="text" class="form-control" name="PPH_FAKTUR_PAJAK" id="PPH_FAKTUR_PAJAK" placeholder="Faktur Pajak">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>
        </div>
        <?php if (!empty($_GET)) { ?>
            <div class="panel-footer text-left">
                <button id="btnSave" type="submit" class="btn btn-primary btn-sm m-l-5" onclick="Save()">Save</button>
                <button type="button" class="btn btn-warning btn-sm m-l-5" onclick="Cancel()">Cancel</button>
            </div>
        <?php } ?>
    </div>
</div>

<?php if (empty($_GET)) { ?>
    <!-- Modal for View -->
    <div class="modal fade" id="PODetailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 95%  !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="PODetailTital">Detail Transactions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="COMPANYNAME">Company</label>
                                <input type="text" class="form-control" name="COMPANYNAME" id="COMPANYNAME" placeholder="Company" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="BUSINESSUNITNAME">Business Unit</label>
                                <input type="text" class="form-control" name="BUSINESSUNITNAME" id="BUSINESSUNITNAME" placeholder="Business Unit" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="DOCTYPE">Doc Type</label>
                                <input type="text" class="form-control" name="DOCTYPE" id="DOCTYPE" placeholder="Doc Type" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="DOCDATE">Doc Date</label>
                                <input type="text" class="form-control" name="DOCDATE" id="DOCDATE" placeholder="Doc Date" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="CURRENCY">Currency</label>
                                <input type="text" class="form-control" name="CURRENCY" id="CURRENCY" placeholder="Doc Type" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="VENDORNAME">Vendor</label>
                                <input type="text" class="form-control" name="VENDORNAME" id="VENDORNAME" placeholder="Vendor" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="REMARK">Remark</label>
                                <input type="text" class="form-control" name="REMARK" id="REMARK" placeholder="Remark" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="INVOICEVENDORNO">Invoice Vendor No</label>
                                <input type="text" class="form-control" name="INVOICEVENDORNO" id="INVOICEVENDORNO" placeholder="Invoice Vendor No" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="AMOUNTSOUCE">Amount Source</label>
                                <input type="text" class="form-control text-right" name="AMOUNTSOUCE" id="AMOUNTSOUCE" placeholder="Amount Source" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="AMOUNTPPN">Amount PPn</label>
                                <input type="text" class="form-control text-right" name="AMOUNTPPN" id="AMOUNTPPN" placeholder="Amount PPn" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="AMOUNTPPH">Amount PPh</label>
                                <input type="text" class="form-control text-right" name="AMOUNTPPH" id="AMOUNTPPH" placeholder="Amount PPh" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="AMOUNTTOTAL">Amount Total</label>
                                <input type="text" class="form-control text-right" name="AMOUNTTOTAL" id="AMOUNTTOTAL" placeholder="Amount Total" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-secondary fade show">
                        <div class="panel panel-default panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
                            <div class="panel-heading p-0">
                                <!-- begin nav-tabs -->
                                <div class="tab-overflow">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item"><a href="#nav-tab-index-1" data-toggle="tab" class="nav-link active">Detail PO</a></li>
                                        <li class="nav-item"><a href="#nav-tab-index-2" data-toggle="tab" class="nav-link">Invoice & PPh</a></li>
                                    </ul>
                                </div>
                                <!-- end nav-tabs -->
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="nav-tab-index-1">
                                    <div class="row m-0 table-responsive">
                                        <table id="DetailPOList" class="table table-bordered table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DetailPOList_info" style="width: 100%;">
                                            <thead>
                                                <tr role="row">
                                                    <th class="text-center align-middle" rowspan="2" style="width: 30px;">No</th>
                                                    <th class="text-center" colspan="3">Material</th>
                                                    <th class="text-center" colspan="4">Amount</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center">Code</th>
                                                    <th class="text-center">Name</th>
                                                    <th class="text-center">Remarks</th>
                                                    <th class="text-center">Source</th>
                                                    <th class="text-center">PPn</th>
                                                    <th class="text-center">PPh</th>
                                                    <th class="text-center">Total</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-right" colspan="4">Total :</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade show" id="nav-tab-index-2">
                                    <div class="row m-0 table-responsive">
                                        <table id="DtInvoice" class="table table-bordered table-hover dataTable" role="grid" width="100%">
                                            <thead>
                                                <tr role="row">
                                                    <th class="text-center align-middle" rowspan="2" style="width: 30px;">No</th>
                                                    <th class="text-center align-middle" rowspan="2">Vendor</th>
                                                    <th class="text-center align-middle" rowspan="2">Invoice</th>
                                                    <th class="text-center" colspan="3">Date</th>
                                                    <th class="text-center" colspan="4">Amount</th>
                                                    <th class="text-center align-middle" rowspan="2">Remark</th>
                                                    <th class="text-center align-middle" rowspan="2">Faktur</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center">Document</th>
                                                    <th class="text-center">Baseline</th>
                                                    <th class="text-center">Due</th>
                                                    <th class="text-center">Source</th>
                                                    <th class="text-center">PPn</th>
                                                    <th class="text-center">PPh</th>
                                                    <th class="text-center">Total</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-right" colspan="6">Total :</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th colspan="2"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
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
    <!-- Modal MExport -->
    <div class="modal fade" id="MExport" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Export Data</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <form id="FExport" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="DOCDATEFROM">Department</label>
                                    <select class="form-control" id="EDEPARTMENT" name="EDEPARTMENT">
                                        <option value="" selected>All Department</option>
                                        <?php echo $CDepartment; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="DOCDATEFROM">Doc Date From *</label>
                                    <div class="input-group date" id="DOCDATEFROM1">
                                        <input type="text" class="form-control" name="DOCDATEFROM" id="DOCDATEFROM" placeholder="MM/DD/YYYY" required />
                                        <div class="input-group-addon input-group-append">
                                            <div class="input-group-text">
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="DOCDATETO">Doc Date To *</label>
                                    <div class="input-group date" id="DOCDATETO1">
                                        <input type="text" class="form-control" name="DOCDATETO" id="DOCDATETO" placeholder="MM/DD/YYYY" required />
                                        <div class="input-group-addon input-group-append">
                                            <div class="input-group-text">
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="Export('EXCEL')"><i class="fa fa-file-excel"></i> Excel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } else { ?>
    <!-- Modal Vendor -->
    <div class="modal fade" id="MVendor">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">List Vendor</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-row">
                                <div class="col"></div>
                                <div class="col"></div>
                                <div class="col">
                                    <input type="text" id="SeVendor" name="SeVendor" class="form-control" placeholder="Cari..">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0 table-responsive">
                        <table id="TVendor" class="table table-bordered table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DetailPOList_info" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <th class="text-center" style="width: 30px;">No</th>
                                    <th class="text-center">Vendor Code</th>
                                    <th class="text-center">Vendor Name</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal PO Detail -->
    <div class="modal fade" id="MPoDetail">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Detial Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <form id="FPoDetail" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ITEMCODE">Item Code *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="ITEMCODE" name="ITEMCODE" placeholder="Item Code" readonly required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-default no-caret btn-sm" onclick="VItem()"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ITEMNAME">Item Name</label>
                                    <input type="text" class="form-control" id="ITEMNAME" name="ITEMNAME" placeholder="Item Name" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="REMARKS">Remark</label>
                                    <input type="text" class="form-control" id="REMARKS" name="REMARKS" placeholder="Remark">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="MAMOUNT_SOURCE">Amount Source *</label>
                                    <input type="text" class="form-control text-right" id="MAMOUNT_SOURCE" name="MAMOUNT_SOURCE" data-type="currency" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="MAMOUNT_PPN">Amount PPn</label>
                                    <input type="text" class="form-control text-right" id="MAMOUNT_PPN" name="MAMOUNT_PPN" data-type="currency" readonly>
                                </div>
                            </div>
                            <!--                            <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="MAMOUNT_PPH">Amount PPh</label>
                                                                <input type="text" class="form-control text-right" id="MAMOUNT_PPH" name="MAMOUNT_PPH" data-type="currency" readonly>
                                                            </div>
                                                        </div>-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="MAMOUNT_TOTAL">Amount Total</label>
                                    <input type="text" class="form-control text-right" id="MAMOUNT_TOTAL" name="MAMOUNT_TOTAL" data-type="currency" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="SaveMaterial()">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal List Material -->
    <div class="modal fade" id="MListItem">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">List Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-row">
                                <div class="col"></div>
                                <div class="col"></div>
                                <div class="col">
                                    <input type="text" id="SeMaterial" name="search" class="form-control" placeholder="Cari..">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0 table-responsive">
                        <table id="TListItem" class="table table-bordered table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DetailPOList_info" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <th class="text-center" style="width: 30px;">No</th>
                                    <th class="text-center">Item Code</th>
                                    <th class="text-center">Item Name</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Invoice -->
    <div class="modal fade" id="MInvoice">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Data Invoice</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <form id="FInvoice" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="IDEPARTMENT">Department *</label>
                                    <select class="form-control roleaccess" name="IDEPARTMENT" id="IDEPARTMENT" required>
                                        <option value="" selected disabled>Choose Department</option>
                                        <?php echo $CDepartment; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="IVENDORNAME">Vendor *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="IVENDORNAME" name="IVENDORNAME" readonly required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-default no-caret btn-sm" onclick="VVendor1()"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="IDOCNUMBER">Invoice Number *</label>
                                    <input type="text" class="form-control" name="IDOCNUMBER" id="IDOCNUMBER" placeholder="Invoice Number" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="IITEM">Invoice Item *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="IITEM" name="IITEM" placeholder="Invoice Item" readonly required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-default no-caret btn-sm" onclick="VItem1()"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="IDOCDATE">Invoice Date *</label>
                                    <div class="input-group date" id="IDOCDATE2">
                                        <input type="text" class="form-control" name="IDOCDATE" id="IDOCDATE" placeholder="MM/DD/YYYY" required />
                                        <div class="input-group-addon input-group-append">
                                            <div class="input-group-text">
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="IBASELINEDATE">Baseline Date *</label>
                                    <div class="input-group date" id="IBASELINEDATE2">
                                        <input type="text" class="form-control" name="IBASELINEDATE" id="IBASELINEDATE" placeholder="MM/DD/YYYY" required />
                                        <div class="input-group-addon input-group-append">
                                            <div class="input-group-text">
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="IPAYTERM">Pay Term *</label>
                                    <input type="text" class="form-control" name="IPAYTERM" id="IPAYTERM" placeholder="Pay Term" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="IDUEDATE">Due Date </label>
                                    <div class="input-group date" id="IDUEDATE2">
                                        <input type="text" class="form-control" name="IDUEDATE" id="IDUEDATE" placeholder="MM/DD/YYYY" readonly />
                                        <div class="input-group-addon input-group-append">
                                            <div class="input-group-text">
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="IAMOUNT_SOURCE">Amount Invoice *</label>
                                    <input type="text" class="form-control text-right" id="IAMOUNT_SOURCE" name="IAMOUNT_SOURCE" data-type="currency" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="IAMOUNT_PPN">Amount PPn</label>
                                    <input type="text" class="form-control text-right" id="IAMOUNT_PPN" name="IAMOUNT_PPN" data-type="currency">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="IAMOUNT_PPH">Amount PPh</label>
                                    <input type="text" class="form-control text-right" id="IAMOUNT_PPH" name="IAMOUNT_PPH" data-type="currency">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="IAMOUNT_TOTAL">Amount Total</label>
                                    <input type="text" class="form-control text-right" id="IAMOUNT_TOTAL" name="IAMOUNT_TOTAL" data-type="currency" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="IREMARK">Remark</label>
                                    <input type="text" class="form-control" id="IREMARK" name="IREMARK">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="IFAKTUR_PAJAK">Faktur Pajak</label>
                                    <input type="text" class="form-control" id="IFAKTUR_PAJAK" name="IFAKTUR_PAJAK">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        	<div class="col-md-6">
                        		<div class="form-group">
                        			<label for="ICURRENCY">CURRENCY</label>
                        			<select class="form-control roleaccess" name="ICURRENCY" id="ICURRENCY" required>
                                    <option value="" selected disabled>Choose Currency</option>
                                    <?php
                                    foreach ($DtCurrency as $values) {
                                        echo '<option value=' . $values->DETAILID . '>' . $values->DETAILNAME . '</option>';
                                    }
                                    ?>
                                </select>
                        		</div>
                        	</div>
                        	<div class="col-md-6">
                        		<div class="form-group">
                        			<label for="INVOICEVENDORNO">INVOICE VENDOR NO</label>
                        			<input type="text" class="form-control" name="IINVOICEVENDORNO" id="IINVOICEVENDORNO" placeholder="Invoice Vendor No">
                        		</div>
                        	</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="SaveInvoice()">Save</button>
                        <input style="display:none;" type="button" class="btn btn-info" id="updateTemp" value="Update">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Vendor Invoice -->
    <div class="modal fade" id="MVendor1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">List Vendor</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-row">
                                <div class="col"></div>
                                <div class="col"></div>
                                <div class="col">
                                    <input type="text" id="SeVendor1" name="SeVendor" class="form-control" placeholder="Cari..">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0 table-responsive">
                        <table id="TVendor1" class="table table-bordered table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DetailPOList_info" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <th class="text-center" style="width: 30px;">No</th>
                                    <th class="text-center">Vendor Code</th>
                                    <th class="text-center">Vendor Name</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal List Material Invoice -->
    <div class="modal fade" id="MListItem1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">List Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-row">
                                <div class="col"></div>
                                <div class="col"></div>
                                <div class="col">
                                    <input type="text" id="SeMaterial1" name="search" class="form-control" placeholder="Cari..">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0 table-responsive">
                        <table id="TListItem1" class="table table-bordered table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DetailPOList_info" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <th class="text-center" style="width: 30px;">No</th>
                                    <th class="text-center">Item Code</th>
                                    <th class="text-center">Item Name</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var ROLEACCESS = "<?php echo $DtUser2->USERACCESS; ?>";
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
    var table, table2, table3, ID;
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
    <?php if (empty($_GET)) { ?>
        if (!$.fn.DataTable.isDataTable('#EntryPO')) {
            table = $('#EntryPO').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?php echo site_url('EntryPO/ShowData') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function(d) {
                        d.USERNAME = USERNAME;
                        d.DEPARTMENT = $('#DEPARTMENT').val();
                    },
                    "dataSrc": function(ext) {
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
                        $("#overlay").show();
                    },
                    "complete": function() {
                        $("#overlay").hide();
                    }
                },
                "columns": [{
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        "data": "DEPARTMENT"
                    },
                    {
                        "data": "COMPANYNAME"
                    },
                    {
                        "data": "BUSINESSUNITNAME"
                    },
                    {
                        "data": "DOCTYPE"
                    },
                    {
                        "data": "DOCNUMBER"
                    },
                    {
                        "data": "DOCDATE"
                    },
                    {
                        "data": "VENDORNAME"
                    },
                    {
                        "data": "CURRENCY"
                    },
                    {
                        "data": "AMOUNT_INCLUDE_VAT",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNT_PPH",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        render: function(data, type, row, meta) {
                            var html = '';
                            html += '<button class="btn btn-info btn-icon btn-circle btn-sm viewPO" title="View" style="margin-right: 5px;"><i class="fa fa-eye"></i></button>';
                            if (EDITS == 1) {
                                html += '<button class="btn btn-success btn-icon btn-circle btn-sm edit" title="Edit" style="margin-right: 5px;"><i class="fa fa-edit"></i></button>';
                            }
                            if (DELETES == 1) {
                                html += '<button class="btn btn-danger btn-icon btn-circle btn-sm delete" title="Delete"><i class="fa fa-trash"></i></button>';
                            }
                            return html;
                        }
                    }
                ],
                responsive: false,
                deferRender: true,
                scrollY: 350,
                scrollX: true,
                scrollCollapse: true,
                scroller: true,
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
                        targets: 5
                    },
                    {
                        responsivePriority: 3,
                        targets: -1
                    }
                ]
            });
            $('#EntryPO thead th').addClass('text-center');
            table.on('click', '.viewPO', function() {
                $tr = $(this).closest('tr');
                var data = table.row($tr).data();
                // console.log(data);
                $("#COMPANYNAME").val(data.COMPANYNAME);
                $("#BUSINESSUNITNAME").val(data.BUSINESSUNITNAME);
                $("#DOCTYPE").val(data.DOCTYPE);
                $("#DOCDATE").val(data.DOCDATE);
                $("#CURRENCY").val(data.CURRENCY);
                $("#VENDORNAME").val(data.VENDORNAME);
                $("#REMARK").val(data.REMARK);
                $("#INVOICEVENDORNO").val(data.INVOICEVENDORNO);
                var AMOUNTSOURCE = parseFloat(data.AMOUNT_INCLUDE_VAT);
                if (data.VAT == '1') {
                    var dDate = new Date($('#DOCDATE').val());
                    var gDate = new Date('01/04/2022');
                    if(dDate > gDate){
                        AMOUNTSOURCE = AMOUNTSOURCE * 100 / 111;
                    }else{
                        AMOUNTSOURCE = AMOUNTSOURCE * 100 / 110;
                    }
                }
                $("#AMOUNTSOUCE").val(AMOUNTSOURCE.toString());
                formatCurrency($('#AMOUNTSOUCE'), "blur");
                $("#AMOUNTPPN").val((parseFloat(data.AMOUNT_INCLUDE_VAT) - AMOUNTSOURCE).toString());
                formatCurrency($('#AMOUNTPPN'), "blur");
                $("#AMOUNTPPH").val(data.AMOUNT_PPH);
                formatCurrency($('#AMOUNTPPH'), "blur");
                $("#AMOUNTTOTAL").val((parseFloat(data.AMOUNT_INCLUDE_VAT) - parseFloat(data.AMOUNT_PPH)).toString());
                formatCurrency($('#AMOUNTTOTAL'), "blur");
                $('#PODetailModal .modal-title').html("Detail Transaction (" + data.DEPARTMENTNAME + " - " + data.DOCNUMBER + ")");
                if (ID != data.ID) {
                    ID = data.ID;
                    if (!$.fn.DataTable.isDataTable('#DetailPOList')) {
                        table2 = $('#DetailPOList').DataTable({
                            "processing": true,
                            "ajax": {
                                "url": "<?php echo site_url('EntryPO/ShowDataDetail') ?>",
                                "type": "POST",
                                "datatype": "JSON",
                                "data": function(d) {
                                    d.ID = ID;
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
                                        return meta.row + meta.settings._iDisplayStart + 1;
                                    }
                                },
                                {
                                    "data": "MATERIALCODE"
                                },
                                {
                                    "data": "MATERIALNAME"
                                },
                                {
                                    "data": "REMARKS"
                                },
                                {
                                    "data": "AMOUNT_SOURCE",
                                    "className": "text-right",
                                    render: $.fn.dataTable.render.number(',', '.', 2)
                                },
                                {
                                    "data": "AMOUNT_PPN",
                                    "className": "text-right",
                                    render: $.fn.dataTable.render.number(',', '.', 2)
                                },
                                {
                                    "data": "AMOUNT_PPH",
                                    "className": "text-right",
                                    render: $.fn.dataTable.render.number(',', '.', 2)
                                },
                                {
                                    "data": "AMOUNT_TOTAL",
                                    "className": "text-right",
                                    render: $.fn.dataTable.render.number(',', '.', 2)
                                }
                            ],
                            "bFilter": true,
                            "bPaginate": true,
                            "bLengthChange": false,
                            "bInfo": true,
                            "footerCallback": function(row, data, start, end, display) {
                                var api = this.api(),
                                    data;
                                var intVal = function(i) {
                                    return typeof i === 'string' ?
                                        i.replace(/[\$,]/g, '') * 1 :
                                        typeof i === 'number' ?
                                        i : 0;
                                };
                                MAMOUNTSOURCE = api.column(4).data().reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                MAMOUNTPPN = api.column(5).data().reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                MAMOUNTPPH = api.column(6).data().reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                MAMOUNTTOTAL = api.column(7).data().reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                                $(api.column(4).footer()).html(numFormat(MAMOUNTSOURCE));
                                $(api.column(5).footer()).html(numFormat(MAMOUNTPPN));
                                $(api.column(6).footer()).html(numFormat(MAMOUNTPPH));
                                $(api.column(7).footer()).html(numFormat(MAMOUNTTOTAL));
                            },
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
                    } else {
                        table2.ajax.reload();
                    }
                    if (!$.fn.DataTable.isDataTable('#DtInvoice')) {
                        table3 = $('#DtInvoice').DataTable({
                            "processing": true,
                            "ajax": {
                                "url": "<?php echo site_url('EntryPO/ShowDataInvoice') ?>",
                                "type": "POST",
                                "datatype": "JSON",
                                "data": function(d) {
                                    d.ID = ID;
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
                                        return meta.row + meta.settings._iDisplayStart + 1;
                                    }
                                },
                                {
                                    "data": "VENDORNAME"
                                },
                                {
                                    "data": "DOCNUMBER"
                                },
                                {
                                    "data": "DOCDATE",
                                    "className": "text-center"
                                },
                                {
                                    "data": "BASELINEDATE",
                                    "className": "text-center"
                                },
                                {
                                    "data": "DUEDATE",
                                    "className": "text-center"
                                },
                                {
                                    "data": "AMOUNT_SOURCE",
                                    "className": "text-right",
                                    render: $.fn.dataTable.render.number(',', '.', 2)
                                },
                                {
                                    "data": "AMOUNT_PPN",
                                    "className": "text-right",
                                    render: $.fn.dataTable.render.number(',', '.', 2)
                                },
                                {
                                    "data": "AMOUNT_PPH",
                                    "className": "text-right",
                                    render: $.fn.dataTable.render.number(',', '.', 2)
                                },
                                {
                                    "data": "AMOUNT_TOTAL",
                                    "className": "text-right",
                                    render: $.fn.dataTable.render.number(',', '.', 2)
                                },
                                {
                                    "data": "REMARK"
                                },
                                {
                                    "data": "FAKTUR_PAJAK"
                                }
                            ],
                            "bFilter": true,
                            "bPaginate": true,
                            "bLengthChange": false,
                            "bInfo": true,
                            "footerCallback": function(row, data, start, end, display) {
                                var api = this.api(),
                                    data;
                                var intVal = function(i) {
                                    return typeof i === 'string' ?
                                        i.replace(/[\$,]/g, '') * 1 :
                                        typeof i === 'number' ?
                                        i : 0;
                                };
                                IAMOUNTSOURCE = api.column(6).data().reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                IAMOUNTPPN = api.column(7).data().reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                IAMOUNTPPH = api.column(8).data().reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                IAMOUNTTOTAL = api.column(9).data().reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                                $(api.column(6).footer()).html(numFormat(IAMOUNTSOURCE));
                                $(api.column(7).footer()).html(numFormat(IAMOUNTPPN));
                                $(api.column(8).footer()).html(numFormat(IAMOUNTPPH));
                                $(api.column(9).footer()).html(numFormat(IAMOUNTTOTAL));
                            },
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
                    } else {
                        table3.ajax.reload();
                    }
                }
                $("#PODetailModal").modal({
                    backdrop: 'static',
                    keyboard: false
                });
            });
            table.on('click', '.edit', function() {
                $tr = $(this).closest('tr');
                var data = table.row($tr).data();
                window.location.href = window.location.href + '?type=edit&id=' + data.ID;
            });
            table.on('click', '.delete', function() {
                $tr = $(this).closest('tr');
                var dat = table.row($tr).data();
                if (confirm('Are you sure delete data document ' + dat.DOCNUMBER + ' ?')) {
                    $('#loader').addClass('show');
                    $.ajax({
                        dataType: "JSON",
                        type: "POST",
                        url: "<?php echo site_url('EntryPO/Delete'); ?>",
                        data: {
                            ID: dat.ID,
                            USERNAME: USERNAME,
                            DOCNUMBER: dat.DOCNUMBER
                        },
                        success: function(response) {
                            $('#loader').removeClass('show');
                            if (response.status == 200) {
                                alert(response.result.data);
                                ReloadData();
                            } else if (response.status == 504) {
                                alert(response.result.data);
                                location.reload();
                            } else {
                                alert(response.result.data);
                            }
                        },
                        error: function(e) {
                            $('#loader').removeClass('show');
                            alert('Error deleting data !!');
                        }
                    });
                }
            });
            $("#EntryPO_filter").remove();
            var timeOutonKeyup = null;
            $("#search").on({
                'input': function() {
                    var dataKeywords = this.value;
                    clearTimeout(timeOutonKeyup);
                    timeOutonKeyup = setTimeout(function() {
                        table.search(dataKeywords, true, false, true).draw();
                    }, 1000);
                }
            });
        }
        var ReloadData = function() {
            table.ajax.reload();
        };
        $("#DEPARTMENT").on({
            'change': function() {
                ReloadData();
            }
        });
        var Add = function() {
            window.location.href = window.location.href + '?type=add';
        };
        var VExport = function() {
            $('#FExport').parsley().reset();
            $("#MExport").modal({
                backdrop: 'static',
                keyboard: false
            });
        };
        $('#DOCDATEFROM1').datepicker({
            "autoclose": true,
            "todayHighlight": true,
            "format": "mm/dd/yyyy"
        });
        $('#DOCDATETO1').datepicker({
            "autoclose": true,
            "todayHighlight": true,
            "format": "mm/dd/yyyy"
        });
        $("#DOCDATEFROM1").datepicker('setDate', tgl);
        $("#DOCDATETO1").datepicker('setDate', tgl);

        var Export = function(type) {
            if ($('#FExport').parsley().validate()) {
                var url = "<?php echo site_url('Process/EntryDataExport'); ?>?type=PARAM1&DOCDATEFROM=PARAM2&DOCDATETO=PARAM3&DEPARTMENT=PARAM4&USERNAME=PARAM5";
                url = url.replace("PARAM1", type);
                url = url.replace("PARAM2", ConvertYYYYMMDD($("#DOCDATEFROM").val()));
                url = url.replace("PARAM3", ConvertYYYYMMDD($("#DOCDATETO").val()));
                if ($("#EDEPARTMENT").val() == "" || $("#EDEPARTMENT").val() == null || $("#EDEPARTMENT").val() == undefined) {
                    url = url.replace("PARAM4", 'ALL');
                } else {
                    url = url.replace("PARAM4", $("#EDEPARTMENT").val());
                }
                url = url.replace("PARAM5", USERNAME);
                window.open(url, '_blank');
            }
        }
        var ConvertYYYYMMDD = function(data) {
            if (data == "" || data == null || data == undefined) {
                return "";
            } else {
                var dd = data.substr(3, 2);
                var mm = data.substr(0, 2);
                var yyyy = data.substr(6, 4);
                return yyyy + mm + dd;
            }
        };
    <?php } else { ?>
        var table4, table5, table6;
        var ID, VENDOR, MATERIAL, DOCTYPEOLD = "",
            FORECAST = 0,
            ACTION, ACTIONM;
        var IDXD, INVID, INVVENDOR, INVVENDORCODE, INVVENDORNAME, IMATERIAL, IMATERIALNAME;
        var AMOUNTSOURCE = 0,
            AMOUNTPPN = 0,
            AMOUNTPPH = 0,
            AMOUNTTOTAL = 0;
        var MAMOUNTSOURCE, MAMOUNTPPN, MAMOUNTPPH, MAMOUNTTOTAL;
        var DtPoDetail = <?php echo json_encode($DtPODetail); ?>;
        var DtInvoice = <?php echo json_encode($DtInvoice); ?>;
        var SVENDOR = "",
            SITEM = "",
            SVENDOR1 = "",
            SITEM1 = "";
        $('#DOCDATE2').datepicker({
            "autoclose": true,
            "todayHighlight": true,
            "format": "mm/dd/yyyy"
        });
        var SetDataKosong = function() {
            ID = "";
            VENDOR = "";
            ACTION = "ADD";
            $("#DEPARTMENT").val('');
            $("#COMPANY").val('');
            $("#BUSINESSUNIT").val('');
            $("#DOCNUMBER").val('');
            $("#DOCTYPE").val('');
            $("#DOCDATE2").datepicker('setDate', tgl);
            $("#VENDORNAME").val('');
            $("#CURRENCY").val('');
            $("#EXTSYS").val('');
            $("#VAT").val(0);
            $("#REMARK").val('');
            $("#INVOICEVENDORNO").val('');
            $("#AMOUNT_SOURCE").val('0.00');
            $("#AMOUNT_PPN").val('0.00');
            $("#AMOUNT_PPH").val('0.00');
            $("#AMOUNT_TOTAL").val('0.00');
            AMOUNTSOURCE = 0;
            AMOUNTPPN = 0;
            AMOUNTPPH = 0;
        };
        var SetData = function(dat) {
            id = dat.ID;
            ID = dat.ID;
            VENDOR = dat.VENDOR;
            ACTION = "EDIT";
            $("#DEPARTMENT").val(dat.DEPARTMENT);
            $("#COMPANY").val(dat.COMPANY);
            if ($("#BUSINESSUNIT") == null || $("#BUSINESSUNIT") == "" || $("#BUSINESSUNIT") == undefined) {
                $("#BUSINESSUNIT").val("");
            } else {
                $("#BUSINESSUNIT").val(dat.BUSINESSUNIT);
            }
            $("#DOCNUMBER").val(dat.DOCNUMBER);
            $("#DOCTYPE").val(dat.DOCTYPE);
            DOCTYPEOLD = dat.DOCTYPE;
            $("#DOCDATE2").datepicker('setDate', dat.DOCDATE);
            $("#VENDORNAME").val(dat.VENDORNAME + " (" + dat.VENDORCODE + ")");
            $("#CURRENCY").val(dat.CURRENCY);
            $("#EXTSYS").val(dat.EXTSYS);
            $("#VAT").val(dat.VAT);
            $("#REMARK").val(dat.REMARK);
            $("#INVOICEVENDORNO").val(dat.INVOICEVENDORNO);
            $("#AMOUNT_SOURCE").val(dat.AMOUNT_SOURCE);
            formatCurrency($('#AMOUNT_SOURCE'), "blur");
            $("#AMOUNT_PPN").val(dat.AMOUNT_PPN);
            formatCurrency($('#AMOUNT_PPN'), "blur");
            $("#AMOUNT_PPH").val(dat.AMOUNT_PPH);
            formatCurrency($('#AMOUNT_PPH'), "blur");
            $("#AMOUNT_TOTAL").val(dat.AMOUNT_TOTAL);
            formatCurrency($('#AMOUNT_TOTAL'), "blur");
            AMOUNTSOURCE = parseFloat(dat.AMOUNT_SOURCE);
            AMOUNTPPN = parseFloat(dat.AMOUNT_PPN);
            AMOUNTPPH = parseFloat(dat.AMOUNT_PPH);
            DisableEXT();
            ChangeAmount()
            if (dat.DOCTYPE == 'PDO') {
                $("#nav2").addClass('disabled');
            }
            FORECAST = dat.FORECAST;
            if (dat.FORECAST > 0) {
                $("#DEPARTMENT").attr('disabled', true);
            }
            $("#COMPANY").attr('disabled', true);
            $("#BUSINESSUNIT").attr('disabled', true);
            //            $("#DOCNUMBER").attr('disabled', true);
        };
        if (getUrlParameter('type') == "add") {
            if (ADDS != 1) {
                $('#btnSave').remove();
            }
            SetDataKosong();
        } else {
            if (EDITS != 1) {
                $('#btnSave').remove();
            }

            SetData(<?php echo json_encode($DtPO); ?>);
        }
        if (ROLEACCESS == '100001' || ROLEACCESS == '100002') {
            $(".roleaccess").attr('disabled', true);
            $("#VAT").attr('disabled', false);
            $("#AMOUNT_PPN").attr('disabled', true);
            $("#btnMaterial").hide();
            if (ROLEACCESS == '100001' || ROLEACCESS == '100005') {
                $('#IDOCDATE2').datepicker({
                    "autoclose": true,
                    "todayHighlight": true,
                    "format": "mm/dd/yyyy"
                });
                $('#IBASELINEDATE2').datepicker({
                    "autoclose": true,
                    "todayHighlight": true,
                    "format": "mm/dd/yyyy"
                });
            }
            if (ROLEACCESS == '100001') {
                $("#IAMOUNT_PPH").attr('disabled', true);
                $("#IFAKTUR_PAJAK").attr('disabled', true);
            } else if (ROLEACCESS == '100002') {
                $("#btnInvoice").hide();
                $("#IVENDORNAME").attr('disabled', true);
                $("#IDOCNUMBER").attr('disabled', true);
                $("#IDOCDATE").attr('disabled', true);
                $("#IBASELINEDATE").attr('disabled', true);
                $("#IPAYTERM").attr('disabled', true);
                $("#IDUEDATE").attr('disabled', true);
                $("#IAMOUNT_SOURCE").attr('disabled', true);
                $("#IAMOUNT_PPN").attr('disabled', true);
                $("#IREMARK").attr('disabled', true);
            }
        } else {
            if (ROLEACCESS != '100005') {
                $("#btnInvoice").hide();
            }
        }
        //        Material Or Item
        if (!$.fn.DataTable.isDataTable('#DetailPOList')) {
            table2 = $('#DetailPOList').DataTable({
                "aaData": DtPoDetail,
                "columns": [{
                        "data": null,
                        "className": "text-center",
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        "data": "MATERIALCODE"
                    },
                    {
                        "data": "MATERIALNAME"
                    },
                    {
                        "data": "REMARKS"
                    },
                    {
                        "data": "AMOUNT_SOURCE",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNT_PPN",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    //                    {
                    //                        "data": "AMOUNT_PPH",
                    //                        "className": "text-right",
                    //                        render: $.fn.dataTable.render.number(',', '.', 2)
                    //                    },
                    {
                        "data": "AMOUNT_TOTAL",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        render: function(data, type, row, meta) {
                            var html = '';
                            if (ROLEACCESS != '100001' && ROLEACCESS != '100002') {
                                html += '<button class="btn btn-info btn-icon btn-circle btn-sm copy" title="Copy" style="margin-right: 5px;"><i class="fa fa-copy"></i></button>';
                                html += '<button class="btn btn-success btn-icon btn-circle btn-sm edit" title="Edit" style="margin-right: 5px;"><i class="fa fa-edit"></i></button>';
                                html += '<button class="btn btn-danger btn-icon btn-circle btn-sm delete" title="Delete"><i class="fa fa-trash"></i></button>';
                            }
                            return html;
                        }
                    }
                ],
                "bFilter": true,
                "bPaginate": true,
                "bLengthChange": false,
                "bInfo": true,
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;
                    // Remove the formatting to get integer data for summation
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };
                    MAMOUNTSOURCE = api.column(4).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    MAMOUNTPPN = api.column(5).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    MAMOUNTTOTAL = api.column(6).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                    $(api.column(4).footer()).html(numFormat(MAMOUNTSOURCE));
                    $(api.column(5).footer()).html(numFormat(MAMOUNTPPN));
                    $(api.column(6).footer()).html(numFormat(MAMOUNTTOTAL));
                    //                    ChangeAmount();
                    //only read 2 number behind comma
                    var numFormat2 = $.fn.dataTable.render.number('', '.', 2).display;
                    MAMOUNTSOURCE = parseFloat(numFormat2(MAMOUNTSOURCE));
                    MAMOUNTPPN = parseFloat(numFormat2(MAMOUNTPPN));
                }
            });
            table2.on('click', '.copy', function() {
                $tr = $(this).closest('tr');
                var data = table2.row($tr).data();
                IDXD = table2.row($tr).index();
                AMOUNTSOURCE = AMOUNTSOURCE + parseFloat(DtPoDetail[IDXD].AMOUNT_SOURCE);
                AMOUNTPPN = AMOUNTPPN + parseFloat(DtPoDetail[IDXD].AMOUNT_PPN);
                ChangeAmount();
                DtPoDetail.push(DtPoDetail[IDXD]);
                ReloadMaterial();
            });
            table2.on('click', '.edit', function() {
                $tr = $(this).closest('tr');
                var data = table2.row($tr).data();
                IDXD = table2.row($tr).index();
                ACTIONM = "EDIT";
                MATERIAL = DtPoDetail[IDXD].MATERIAL;
                $("#ITEMNAME").val(DtPoDetail[IDXD].MATERIALNAME);
                $("#ITEMCODE").val(DtPoDetail[IDXD].MATERIALCODE);
                $("#REMARKS").val(DtPoDetail[IDXD].REMARKS);
                $("#MAMOUNT_SOURCE").val(DtPoDetail[IDXD].AMOUNT_SOURCE);
                formatCurrency($('#MAMOUNT_SOURCE'), "blur");
                $("#MAMOUNT_PPN").val(DtPoDetail[IDXD].AMOUNT_PPN);
                formatCurrency($('#MAMOUNT_PPN'), "blur");
                $("#MAMOUNT_TOTAL").val(DtPoDetail[IDXD].AMOUNT_TOTAL);
                formatCurrency($('#MAMOUNT_TOTAL'), "blur");
                $('#FPoDetail').parsley().reset();
                $('#MPoDetail .modal-title').text("Edit Detial Item");
                $("#MPoDetail").modal({
                    backdrop: 'static',
                    keyboard: false
                });
            });
            table2.on('click', '.delete', function() {
                $tr = $(this).closest('tr');
                var data = table2.row($tr).data();
                IDXD = table2.row($tr).index();
                AMOUNTSOURCE = AMOUNTSOURCE - parseFloat(DtPoDetail[IDXD].AMOUNT_SOURCE);
                AMOUNTPPN = AMOUNTPPN - parseFloat(DtPoDetail[IDXD].AMOUNT_PPN);
                ChangeAmount();
                DtPoDetail.splice(IDXD, 1);
                ReloadMaterial();
            });
        }
        var ReloadMaterial = function() {
            table2.clear();
            table2.rows.add(DtPoDetail);
            table2.draw();
            DisableEXT();
        };
        var AddMaterial = function() {
            ACTIONM = 'ADD';
            MATERIAL = "";
            $("#ITEMCODE").val('');
            $("#ITEMNAME").val('');
            $("#REMARKS").val('');
            $("#MAMOUNT_SOURCE").val('');
            $("#MAMOUNT_PPN").val('');
            $("#MAMOUNT_TOTAL").val('');
            $('#FPoDetail').parsley().reset();
            $('#MPoDetail .modal-title').text("Add Detial Item");
            $("#MPoDetail").modal({
                backdrop: 'static',
                keyboard: false
            });
        };
        var VItem = function() {
            if (!$.fn.DataTable.isDataTable('#TListItem')) {
                table4 = $('#TListItem').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "<?php echo site_url('EntryPO/GetItem') ?>",
                        "type": "POST",
                        "datatype": "JSON",
                        "data": function(d) {
                            d.SITEM = SITEM;
                            d.EXTSYSTEM = $("#EXTSYS").val();
                        },
                        "dataSrc": function(ext) {
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
                        }
                    },
                    "columns": [{
                            "data": null,
                            "className": "text-center",
                            "orderable": false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            "data": "FCCODE"
                        },
                        {
                            "data": "FCNAME"
                        },
                        {
                            "data": null,
                            "className": "text-center",
                            render: function(data, type, row, meta) {
                                var html = '';
                                html += '<button class="btn btn-info btn-icon btn-circle btn-sm Assign" title="Assign"><i class="fa fa-share-square"></i></button>';
                                return html;
                            }
                        }
                    ],
                    "bFilter": false,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bInfo": true
                });
                table4.on('click', '.Assign', function() {
                    $tr = $(this).closest('tr');
                    var data = table4.row($tr).data();
                    MATERIAL = data.ID;
                    $("#ITEMNAME").val(data.FCNAME);
                    $("#ITEMCODE").val(data.FCCODE);
                    $("#MListItem").modal("hide");
                });
                var timeOutMaterial = null;
                $("#SeMaterial").on({
                    'input': function() {
                        SITEM = this.value;
                        clearTimeout(timeOutMaterial);
                        timeOutMaterial = setTimeout(function() {
                            table4.ajax.reload();
                        }, 1000);
                    }
                });
            } else {

            }
            $("#MListItem").on('hidden.bs.modal', function(event) {
                $('body').addClass('modal-open');
            }).modal({
                backdrop: 'static',
                keyboard: false
            });
        };
        $("#MAMOUNT_SOURCE").on({
            'change': function() {
                var AMOUNT = parseFloat(formatDesimal(this.value));
                var PPN = 0;
                var dDate = new Date($('#DOCDATE').val());
                var gDate = new Date('01/04/2022');
                    

                if ($("#VAT").val() == '1') {
                    if(dDate > gDate){
                        PPN = AMOUNT * 11 / 100;
                    }
                    else{
                        PPN = AMOUNT * 10 / 100;
                    }
                    
                }
                var TOTAL = AMOUNT + PPN;
                $("#MAMOUNT_SOURCE").val(AMOUNT.toFixed(2));
                formatCurrency($('#MAMOUNT_PPN'), "blur");
                $("#MAMOUNT_PPN").val(PPN.toFixed(2));
                formatCurrency($('#MAMOUNT_PPN'), "blur");
                $("#MAMOUNT_TOTAL").val(TOTAL.toFixed(2));
                formatCurrency($('#MAMOUNT_TOTAL'), "blur");
            }
        });
        var SaveMaterial = function() {
            if ($('#FPoDetail').parsley().validate()) {
                if (parseFloat(formatDesimal($("#MAMOUNT_SOURCE").val())) <= 0) {
                    alert("Amount Source Can't be Zero !!!");
                } else {
                    if (ACTIONM == "ADD") {
                        AMOUNTSOURCE = AMOUNTSOURCE + parseFloat(formatDesimal($("#MAMOUNT_SOURCE").val()));
                        AMOUNTPPN = AMOUNTPPN + parseFloat(formatDesimal($("#MAMOUNT_PPN").val()));
                        ChangeAmount();
                        dt = {
                            MATERIAL: MATERIAL,
                            MATERIALCODE: $("#ITEMCODE").val(),
                            MATERIALNAME: $("#ITEMNAME").val(),
                            REMARKS: $("#REMARKS").val(),
                            AMOUNT_SOURCE: formatDesimal($("#MAMOUNT_SOURCE").val()),
                            AMOUNT_PPN: formatDesimal($("#MAMOUNT_PPN").val()),
                            AMOUNT_TOTAL: formatDesimal($("#MAMOUNT_TOTAL").val())
                        }
                        DtPoDetail.push(dt);
                        ReloadMaterial();
                        $("#MPoDetail").modal("hide");
                    } else {
                        AMOUNTSOURCE = AMOUNTSOURCE - parseFloat(DtPoDetail[IDXD].AMOUNT_SOURCE) + parseFloat(formatDesimal($("#MAMOUNT_SOURCE").val()));
                        AMOUNTPPN = AMOUNTPPN - parseFloat(DtPoDetail[IDXD].AMOUNT_PPN) + parseFloat(formatDesimal($("#MAMOUNT_PPN").val()));
                        ChangeAmount();
                        DtPoDetail[IDXD].MATERIAL = MATERIAL;
                        DtPoDetail[IDXD].MATERIALCODE = $("#ITEMCODE").val();
                        DtPoDetail[IDXD].MATERIALNAME = $("#ITEMNAME").val();
                        DtPoDetail[IDXD].REMARKS = $("#REMARKS").val();
                        DtPoDetail[IDXD].AMOUNT_SOURCE = formatDesimal($("#MAMOUNT_SOURCE").val());
                        DtPoDetail[IDXD].AMOUNT_PPN = formatDesimal($("#MAMOUNT_PPN").val());
                        DtPoDetail[IDXD].AMOUNT_TOTAL = formatDesimal($("#MAMOUNT_TOTAL").val());
                        ReloadMaterial();
                        $("#MPoDetail").modal("hide");
                    }
                }
            }
        };
        //        End Material Or Item
        //        Data Invoice
        if (!$.fn.DataTable.isDataTable('#DtInvoice')) {
            table3 = $('#DtInvoice').DataTable({
                "aaData": DtInvoice,
                "columns": [{
                        "data": null,
                        "className": "text-center",
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        "data": "VENDORNAME"
                    },
                    {
                        "data": "DOCNUMBER"
                    },
                    {
                        "data": "DOCDATE",
                        "className": "text-center"
                    },
                    {
                        "data": "BASELINEDATE",
                        "className": "text-center"
                    },
                    {
                        "data": "DUEDATE",
                        "className": "text-center"
                    },
                    {
                        "data": "AMOUNT_SOURCE",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNT_PPN",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNT_PPH",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNT_TOTAL",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "REMARK"
                    },
                    {
                        "data": "FAKTUR_PAJAK"
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        render: function(data, type, row, meta) {
                            var html = '';
                            if (ROLEACCESS == '100001' || ROLEACCESS == '100002' || ROLEACCESS == '100005') {
                                html += '<button class="btn btn-success btn-icon btn-circle btn-sm edit" title="Edit" style="margin-right: 5px;"><i class="fa fa-edit"></i></button>';
                            }
                            if ((ROLEACCESS == '100001' || ROLEACCESS == '100005') && data.FORECAST <= 0) {
                                html += '<button class="btn btn-danger btn-icon btn-circle btn-sm delete" title="Delete"><i class="fa fa-trash"></i></button>';
                            }
                            return html;
                        }
                    }
                ],
                responsive: {
                    details: {
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col, i) {
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
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };
                    IAMOUNTSOURCE = api.column(6).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    IAMOUNTPPN = api.column(7).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    IAMOUNTPPH = api.column(8).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    IAMOUNTTOTAL = api.column(9).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                    $(api.column(6).footer()).html(numFormat(IAMOUNTSOURCE));
                    $(api.column(7).footer()).html(numFormat(IAMOUNTPPN));
                    $(api.column(8).footer()).html(numFormat(IAMOUNTPPH));
                    $(api.column(9).footer()).html(numFormat(IAMOUNTTOTAL));
                },
                "columnDefs": [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    }
                ]
            });
            table3.on('click', '.edit', function() {
                $tr = $(this).closest('tr');

                var data = table3.row($tr).data();
                IDXD = table3.row($tr).index();
                ACTIONM = "EDIT";
                INVID = DtInvoice[IDXD].ID;
                INVVENDOR = DtInvoice[IDXD].VENDOR;
                INVVENDORCODE = DtInvoice[IDXD].VENDORCODE;
                INVVENDORNAME = DtInvoice[IDXD].VENDORNAME;
                IMATERIAL = DtInvoice[IDXD].MATERIAL;
                IMATERIALNAME = DtInvoice[IDXD].MATERIALNAME;
                // console.log(DtInvoice);
                $("#IDEPARTMENT").val(DtInvoice[IDXD].DEPARTMENT);
                $("#IVENDORNAME").val(DtInvoice[IDXD].VENDORNAME + ' (' + DtInvoice[IDXD].VENDORCODE + ')');
                $("#IDOCNUMBER").val(DtInvoice[IDXD].DOCNUMBER);
                $("#IITEM").val(DtInvoice[IDXD].MATERIALNAME);
                if (ROLEACCESS == '100001' || ROLEACCESS == '100005') {
                    $("#IDOCDATE2").datepicker('setDate', DtInvoice[IDXD].DOCDATE);
                    $("#IBASELINEDATE2").datepicker('setDate', DtInvoice[IDXD].BASELINEDATE);
                } else {
                    $("#IDOCDATE").val(DtInvoice[IDXD].DOCDATE);
                    $("#IBASELINEDATE").val(DtInvoice[IDXD].BASELINEDATE);
                }
                $("#IPAYTERM").val(DtInvoice[IDXD].PAYTERM);
                $("#IDUEDATE").val(DtInvoice[IDXD].DUEDATE);
                $("#IAMOUNT_SOURCE").val(DtInvoice[IDXD].AMOUNT_SOURCE);
                formatCurrency($('#IAMOUNT_SOURCE'), "blur");
                $("#IAMOUNT_PPN").val(DtInvoice[IDXD].AMOUNT_PPN);
                formatCurrency($('#IAMOUNT_PPN'), "blur");
                $("#IAMOUNT_PPH").val(DtInvoice[IDXD].AMOUNT_PPH);
                formatCurrency($('#IAMOUNT_PPH'), "blur");
                $("#IAMOUNT_TOTAL").val(DtInvoice[IDXD].AMOUNT_TOTAL);
                formatCurrency($('#IAMOUNT_TOTAL'), "blur");
                $("#IREMARK").val(DtInvoice[IDXD].REMARK);
                $("#IFAKTUR_PAJAK").val(DtInvoice[IDXD].FAKTUR_PAJAK);
                $("#ICURRENCY").val(DtInvoice[IDXD].CURRENCY);
                $("#IINVOICEVENDORNO").val(DtInvoice[IDXD].INVOICEVENDORNO);
                $('#FInvoice').parsley().reset();
                $('#MInvoice .modal-title').text("Add Data Invoice");
                // const cekDocval = IDOCNUMBER.includes("TMP");
                // alert(IDOCNUMBER);
                $("#MInvoice").modal({
                    backdrop: 'static',
                    keyboard: false
                });
            });
            table3.on('click', '.delete', function() {
				$tr = $(this).closest('tr');
                var data = table3.row($tr).data();
                // console.log(data);
                $.ajax({
                        dataType: "JSON",
                        type: "POST",
                        url: "<?php echo site_url('Payment/cekBeforeDel'); ?>",
                        data: {
                            ID: data.ID,
                            AMOUNT_TOTAL: data.AMOUNT_TOTAL
                        },
                        beforeSend: function() {
                              $('#loader').addClass('show');
                        },
                        success: function(response) {
                           $('#loader').removeClass('show');
                           if(response.result.data.payment == 1 && response.result.data.forecast == 1){
                                alert('data sudah dibayar dan diforecast');
                           }
                           else if(response.result.data.forecast == 1 && response.result.data.payment == 0){
                                alert('data sudah diforecast');
                           }
                           else if(response.result.data.payment == 1 && response.result.data.forecast == 0){
                                alert('data sudah dibayar');
                           }
                           else{
                                IDXD = table3.row($tr).index();
                                AMOUNTPPH = AMOUNTPPH - parseFloat(DtInvoice[IDXD].AMOUNT_PPH);
                                ChangeAmount();
                                DtInvoice.splice(IDXD, 1);
                                ReloadInvoice();
                           }
                        }
                });
            });
        }
        var ReloadInvoice = function() {
            table3.clear();
            table3.rows.add(DtInvoice);
            table3.draw();
        };
        var AddInvoice = function() {
            ACTIONM = 'ADD';
            INVID = "";
            INVVENDOR = "";
            INVVENDORCODE = "";
            INVVENDORNAME = "";
            IMATERIAL = "";
            IMATERIALNAME = "";
            $("#IDEPARTMENT").val('');
            $("#IVENDORNAME").val('');
            $("#IDOCNUMBER").val('');
            $("#IITEM").val('');
            $("#IDOCDATE2").datepicker('setDate', tgl);
            $("#IBASELINEDATE2").datepicker('setDate', tgl);
            $("#IPAYTERM").val('0');
            $("#IDUEDATE").val(tgl);
            //            $("#IDUEDATE2").datepicker('setDate', tgl);
            $("#IAMOUNT_SOURCE").val('');
            $("#IAMOUNT_PPN").val('');
            $("#IAMOUNT_PPH").val('');
            $("#IAMOUNT_TOTAL").val('');
            $("#IREMARK").val('');
            $("#IFAKTUR_PAJAK").val('');
            $("#IINVOICEVENDORNO").val('');
            $('#FInvoice').parsley().reset();
            $('#MInvoice .modal-title').text("Add Data Invoice");
            $("#MInvoice").modal({
                backdrop: 'static',
                keyboard: false
            });
        };
        var VVendor1 = function() {
            if (!$.fn.DataTable.isDataTable('#TVendor1')) {
                table5 = $('#TVendor1').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "<?php echo site_url('EntryPO/GetVendor') ?>",
                        "type": "POST",
                        "datatype": "JSON",
                        "data": function(d) {
                            d.SVENDOR = SVENDOR1;
                        },
                        "dataSrc": function(ext) {
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
                        }
                    },
                    "columns": [{
                            "data": null,
                            "className": "text-center",
                            "orderable": false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            "data": "FCCODE"
                        },
                        {
                            "data": "FCNAME"
                        },
                        {
                            "data": null,
                            "className": "text-center",
                            render: function(data, type, row, meta) {
                                var html = '';
                                html += '<button class="btn btn-info btn-icon btn-circle btn-sm Assign" title="Assign"><i class="fa fa-share-square"></i></button>';
                                return html;
                            }
                        }
                    ],
                    "bFilter": false,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bInfo": true
                });
                table5.on('click', '.Assign', function() {
                    $tr = $(this).closest('tr');
                    var data = table5.row($tr).data();
                    INVVENDOR = data.ID;
                    INVVENDORCODE = data.FCCODE;
                    INVVENDORNAME = data.FCNAME
                    $("#IVENDORNAME").val(data.FCNAME + " (" + data.FCCODE + ")");
                    $("#MVendor1").modal("hide");
                });
                var timeOutVendor = null;
                $("#SeVendor1").on({
                    'input': function() {
                        SVENDOR1 = this.value;
                        clearTimeout(timeOutVendor);
                        timeOutVendor = setTimeout(function() {
                            table5.ajax.reload();
                        }, 1000);
                    }
                });
            }
            $("#MVendor1").on('hidden.bs.modal', function(event) {
                $('body').addClass('modal-open');
            }).modal({
                backdrop: 'static',
                keyboard: false
            });
        };
        var VItem1 = function() {
            if (!$.fn.DataTable.isDataTable('#TListItem1')) {
                table6 = $('#TListItem1').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "<?php echo site_url('EntryPO/GetItemInvoice') ?>",
                        "type": "POST",
                        "datatype": "JSON",
                        "data": function(d) {
                            d.SITEM = SITEM1;
                            d.EXTSYSTEM = $("#EXTSYS").val();
                        },
                        "dataSrc": function(ext) {
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
                        }
                    },
                    "columns": [{
                            "data": null,
                            "className": "text-center",
                            "orderable": false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            "data": "FCCODE"
                        },
                        {
                            "data": "FCNAME"
                        },
                        {
                            "data": null,
                            "className": "text-center",
                            render: function(data, type, row, meta) {
                                var html = '';
                                html += '<button class="btn btn-info btn-icon btn-circle btn-sm Assign" title="Assign"><i class="fa fa-share-square"></i></button>';
                                return html;
                            }
                        }
                    ],
                    "bFilter": false,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bInfo": true
                });
                table6.on('click', '.Assign', function() {
                    $tr = $(this).closest('tr');
                    var data = table6.row($tr).data();
                    IMATERIAL = data.ID;
                    IMATERIALNAME = data.FCNAME;
                    $("#IITEM").val(data.FCNAME);
                    $("#MListItem1").modal("hide");
                });
                var timeOutMaterial1 = null;
                $("#SeMaterial1").on({
                    'input': function() {
                        SITEM1 = this.value;
                        clearTimeout(timeOutMaterial1);
                        timeOutMaterial1 = setTimeout(function() {
                            table6.ajax.reload();
                        }, 1000);
                    }
                });
            }
            $("#MListItem1").on('hidden.bs.modal', function(event) {
                $('body').addClass('modal-open');
            }).modal({
                backdrop: 'static',
                keyboard: false
            });
        };
        $('#IBASELINEDATE').on({
            'change': function() {
                if ($('#IPAYTERM').val() != "") {
                    var LDUEDATE = new Date(parseInt(this.value.substr(6, 4)), (parseInt(this.value.substr(0, 2)) - 1), (parseInt(this.value.substr(3, 2)) + parseInt($('#IPAYTERM').val())));
                    var Ldd = LDUEDATE.getDate();
                    var Lmm = LDUEDATE.getMonth() + 1;
                    if (Ldd < 10) {
                        Ldd = '0' + Ldd;
                    }
                    if (Lmm < 10) {
                        Lmm = '0' + Lmm;
                    }
                    $("#IDUEDATE").val((Lmm + '/' + Ldd + '/' + LDUEDATE.getFullYear()));
                    //                    $("#IDUEDATE2").datepicker('setDate', (Lmm + '/' + Ldd + '/' + LDUEDATE.getFullYear()));
                } else {
                    $("#IDUEDATE").val("");
                    //                    $("#IDUEDATE2").datepicker('setDate', "");
                }
            }
        });
        $('#IPAYTERM').on({
            'change': function() {
                $('#IBASELINEDATE').change();
            }
        });
        $("#IAMOUNT_SOURCE").on({
            'change': function() {
                var IAMOUNT = parseFloat(formatDesimal(this.value));
                var IPPN = formatDesimal($("#IAMOUNT_PPN").val());
                if (IPPN == "") {
                    IPPN = "0";
                }
                var IPPH = formatDesimal($("#IAMOUNT_PPH").val());
                if (IPPH == "") {
                    IPPH = "0";
                }
                var ITOTAL = IAMOUNT + parseFloat(IPPN) - parseFloat(IPPH);
                $("#IAMOUNT_TOTAL").val(ITOTAL.toString());
                formatCurrency($('#IAMOUNT_TOTAL'), "blur");
            }
        });
        $("#IAMOUNT_PPN").on({
            'change': function() {
                $("#IAMOUNT_SOURCE").change();
            }
        });
        $("#IAMOUNT_PPH").on({
            'change': function() {
                $("#IAMOUNT_SOURCE").change();
            }
        });
        var SaveInvoice = function() {
            if ($('#FInvoice').parsley().validate()) {
                if (parseFloat(formatDesimal($("#IAMOUNT_SOURCE").val())) <= 0) {
                    alert("Amount Source Can't be Zero !!!");
                } else {
                    var IPPN = formatDesimal($("#IAMOUNT_PPN").val());
                    if (IPPN == "") {
                        IPPN = "0";
                    }
                    var IPPH = formatDesimal($("#IAMOUNT_PPH").val());
                    if (IPPH == "") {
                        IPPH = "0";
                    }
                    if (ACTIONM == "ADD") {
                        AMOUNTPPH = AMOUNTPPH + parseFloat(formatDesimal($("#IAMOUNT_PPH").val()));
                        ChangeAmount();
                        dt = {
                            ID: INVID,
                            VENDOR: INVVENDOR,
                            VENDORCODE: INVVENDORCODE,
                            VENDORNAME: INVVENDORNAME,
                            MATERIAL: IMATERIAL,
                            MATERIALNAME: IMATERIALNAME,
                            DEPARTMENT: $("#IDEPARTMENT").val(),
                            DOCNUMBER: $("#IDOCNUMBER").val(),
                            DOCREF: $("#DOCNUMBER").val(),
                            DOCDATE: $("#IDOCDATE").val(),
                            BASELINEDATE: $("#IBASELINEDATE").val(),
                            PAYTERM: $("#IPAYTERM").val(),
                            DUEDATE: $("#IDUEDATE").val(),
                            REMARK: $("#IREMARK").val(),
                            FAKTUR_PAJAK: $("#IFAKTUR_PAJAK").val(),
                            CURRENCY: $("#ICURRENCY").val(),
                            INVOICEVENDORNO: $("#IINVOICEVENDORNO").val(),
                            AMOUNT_SOURCE: formatDesimal($("#IAMOUNT_SOURCE").val()),
                            AMOUNT_PPN: IPPN,
                            AMOUNT_PPH: IPPH,
                            AMOUNT_TOTAL: formatDesimal($("#IAMOUNT_TOTAL").val()),
                            FORECAST: 0
                        }
                        DtInvoice.push(dt);
                        DisableEXT();
                        ReloadInvoice();
                        $("#MInvoice").modal("hide");
                    } else {
                        AMOUNTPPH = AMOUNTPPH - parseFloat(DtInvoice[IDXD].AMOUNT_PPH) + parseFloat(formatDesimal($("#IAMOUNT_PPH").val()));
                        ChangeAmount();
                        DtInvoice[IDXD].ID = INVID;
                        DtInvoice[IDXD].VENDOR = INVVENDOR;
                        DtInvoice[IDXD].VENDORCODE = INVVENDORCODE;
                        DtInvoice[IDXD].VENDORNAME = INVVENDORNAME;
                        DtInvoice[IDXD].MATERIAL = IMATERIAL;
                        DtInvoice[IDXD].MATERIALNAME = IMATERIALNAME;
                        DtInvoice[IDXD].DEPARTMENT = $("#IDEPARTMENT").val();
                        DtInvoice[IDXD].DOCNUMBER = $("#IDOCNUMBER").val();
                        DtInvoice[IDXD].DOCREF = $("#DOCNUMBER").val();
                        DtInvoice[IDXD].DOCDATE = $("#IDOCDATE").val();
                        DtInvoice[IDXD].BASELINEDATE = $("#IBASELINEDATE").val();
                        DtInvoice[IDXD].PAYTERM = $("#IPAYTERM").val();
                        DtInvoice[IDXD].DUEDATE = $("#IDUEDATE").val();
                        DtInvoice[IDXD].REMARK = $("#IREMARK").val();
                        DtInvoice[IDXD].FAKTUR_PAJAK = $("#IFAKTUR_PAJAK").val();
                        DtInvoice[IDXD].AMOUNT_SOURCE = formatDesimal($("#IAMOUNT_SOURCE").val());
                        DtInvoice[IDXD].AMOUNT_PPN = IPPN;
                        DtInvoice[IDXD].AMOUNT_PPH = IPPH;
                        DtInvoice[IDXD].AMOUNT_TOTAL = formatDesimal($("#IAMOUNT_TOTAL").val());
                        DtInvoice[IDXD].CURRENCY = $("#ICURRENCY").val();
                        DtInvoice[IDXD].INVOICEVENDORNO = $("#IINVOICEVENDORNO").val();
                        DisableEXT();
                        ReloadInvoice();
                        $("#MInvoice").modal("hide");
                    }
                }
            }
        };
        //        End Data Invoice
        $("#COMPANY").on({
            'change': function() {
                $("#loader").addClass('show');
                $('#BUSINESSUNIT').find('option:not(:first)').remove().end().val('');
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('IBusinessUnit/GetDataAjax'); ?>",
                    data: {
                        COMPANY: $('#COMPANY').val()
                    },
                    success: function(response, textStatus, jqXHR) {
                        $('#loader').removeClass('show');
                        if (response.status == 200) {
                            var html = '';
                            $.each(response.result.data, function(index, value) {
                                html += "<option value='" + value.ID + "'>" + value.FCCODE + ' - ' + value.FCNAME + '</option>';
                            });
                            $(html).insertAfter("#BUSINESSUNIT option:first");
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
        $("#DOCTYPE").on({
            'change': function() {
                if (this.value == "PDO") {
                    if (DtInvoice.length > 0) {
                        alert("Can't change this Doc Type");
                        $("#DOCTYPE").val(DOCTYPEOLD);
                    } else {
                        $("#nav2").addClass('disabled');
                        $("#nav2").removeClass('active');
                        $("#nav-tab-2").removeClass('active');
                        $("#nav-tab-2").removeClass('show');
                        $("#nav1").addClass("active");
                        $("#nav-tab-1").addClass("active");
                        $("#nav-tab-1").addClass("show");
                        DOCTYPEOLD = this.value;
                    }
                } else {
                    if (FORECAST > 0) {
                        alert("Can't change this Doc Type");
                        $("#DOCTYPE").val(DOCTYPEOLD);
                    } else {
                        DOCTYPEOLD = this.value;
                        $("#nav2").removeClass('disabled');
                    }
                }
            }
        });

        function DisableEXT() {
            if (DtPoDetail.length > 0 || DtInvoice.length > 0) {
                $("#EXTSYS").attr('disabled', true);
                if (DtInvoice.length > 0) {
                    $("#DOCNUMBER").attr('disabled', true);
                } else {
                    $("#DOCNUMBER").removeAttr('disabled');
                }
            } else {
                $("#EXTSYS").removeAttr('disabled');
            }
        }
        var VVendor = function() {
            if (!$.fn.DataTable.isDataTable('#TVendor')) {
                table = $('#TVendor').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "<?php echo site_url('EntryPO/GetVendor') ?>",
                        "type": "POST",
                        "datatype": "JSON",
                        "data": function(d) {
                            d.SVENDOR = SVENDOR;
                        },
                        "dataSrc": function(ext) {
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
                        }
                    },
                    "columns": [{
                            "data": null,
                            "className": "text-center",
                            "orderable": false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            "data": "FCCODE"
                        },
                        {
                            "data": "FCNAME"
                        },
                        {
                            "data": null,
                            "className": "text-center",
                            render: function(data, type, row, meta) {
                                var html = '';
                                html += '<button class="btn btn-info btn-icon btn-circle btn-sm Assign" title="Assign"><i class="fa fa-share-square"></i></button>';
                                return html;
                            }
                        }
                    ],
                    responsive: {
                        details: {
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col, i) {
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
                    "bFilter": false,
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
                table.on('click', '.Assign', function() {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    VENDOR = data.ID;
                    $("#VENDORNAME").val(data.FCNAME + " (" + data.FCCODE + ")");
                    $("#MVendor").modal("hide");
                });
                var timeOutVendor = null;
                $("#SeVendor").on({
                    'input': function() {
                        SVENDOR = this.value;
                        clearTimeout(timeOutVendor);
                        timeOutVendor = setTimeout(function() {
                            table.ajax.reload();
                        }, 1000);
                    }
                });
            }
            $("#MVendor").modal({
                backdrop: 'static',
                keyboard: false
            });
        };

        function ChangeAmount() {
            $("#AMOUNT_SOURCE").val(AMOUNTSOURCE.toFixed(2));
            formatCurrency($('#AMOUNT_SOURCE'), "blur");
            $("#AMOUNT_PPN").val(AMOUNTPPN.toFixed(2));
            formatCurrency($('#AMOUNT_PPN'), "blur");
            $("#AMOUNT_PPH").val(AMOUNTPPH.toFixed(2));
            formatCurrency($('#AMOUNT_PPH'), "blur");
            AMOUNTTOTAL = AMOUNTSOURCE + AMOUNTPPN - AMOUNTPPH;
            $("#AMOUNT_TOTAL").val(AMOUNTTOTAL.toFixed(2));
            formatCurrency($('#AMOUNT_TOTAL'), "blur");
        }
        $("#VAT").on({
            'change': function() {
                AMOUNTSOURCE = 0;
                AMOUNTPPN = 0;
                AMOUNTPPH = 0;
                $.each(DtPoDetail, function(index, value) {
                    var AMOUNT = parseFloat(DtPoDetail[index].AMOUNT_SOURCE);
                    AMOUNTSOURCE = AMOUNTSOURCE + AMOUNT;
                    var PPN = 0;
                    var dDate = new Date($('#DOCDATE').val());
                    var gDate = new Date('01/04/2022');
                    if ($("#VAT").val() == '1') {
                        if(dDate > gDate){
                            // alert('ok')
                            PPN = AMOUNT * 11 / 100;    
                        }
                        else{
                            // alert('oks')
                            PPN = AMOUNT * 10 / 100;
                        }
                        
                    }
                    AMOUNTPPN = AMOUNTPPN + PPN;
                    var TAMOUNT = AMOUNT + PPN;
                    DtPoDetail[index].AMOUNT_PPN = PPN;
                    DtPoDetail[index].AMOUNT_TOTAL = TAMOUNT;
                });
                $.each(DtInvoice, function(index, value) {
                    var PPH = parseFloat(DtInvoice[index].AMOUNT_PPH);
                    AMOUNTPPH = AMOUNTPPH + PPH;
                });
                ReloadMaterial();
                ChangeAmount();
            }
        });
        var Save = function() {
            console.log(DtInvoice);
            if ($('#SaveEntryPO').parsley().validate()) {
                if (DtPoDetail.length <= 0) {
                    alert("Detail Record Not Set !!!");
                } else if ((parseFloat(AMOUNTSOURCE) + parseFloat(AMOUNTPPN)) != (parseFloat(MAMOUNTSOURCE) + parseFloat(MAMOUNTPPN))) {
                    alert("Amount Header And Amount Detail Not Same !!!");
                }
                //  else if ((AMOUNTSOURCE + AMOUNTPPN - AMOUNTPPH) < (IAMOUNTSOURCE + IAMOUNTPPN - IAMOUNTPPH)) {
                //     alert("Invoice Amount can't  above Source Amount !!!");
                // } 
                else {
                    $("#loader").addClass('show');
                    $('#btnSave').attr('disabled', true);
                    var DtInv = DtInvoice;
                    if (DtInvoice.length <= 0) {
                        DtInv = "0";
                    }
                    $.ajax({
                        dataType: "JSON",
                        type: "POST",
                        url: "<?php echo site_url('EntryPO/Save'); ?>",
                        data: {
                            ACTION: ACTION,
                            ROLEACCESS: ROLEACCESS,
                            ID: ID,
                            VENDOR: VENDOR,
                            DEPARTMENT: $("#DEPARTMENT").val(),
                            COMPANY: $("#COMPANY").val(),
                            BUSINESSUNIT: $("#BUSINESSUNIT").val(),
                            DOCNUMBER: $("#DOCNUMBER").val(),
                            DOCTYPE: $("#DOCTYPE").val(),
                            DOCDATE: $("#DOCDATE").val(),
                            CURRENCY: $("#CURRENCY").val(),
                            EXTSYS: $("#EXTSYS").val(),
                            VAT: $("#VAT").val(),
                            REMARK: $("#REMARK").val(),
                            INVOICEVENDORNO: $("#INVOICEVENDORNO").val(),
                            AMOUNT_SOURCE: formatDesimal($("#AMOUNT_SOURCE").val()),
                            AMOUNT_PPN: formatDesimal($("#AMOUNT_PPN").val()),
                            AMOUNT_PPH: formatDesimal($("#AMOUNT_PPH").val()),
                            AMOUNT_TOTAL: formatDesimal($("#AMOUNT_TOTAL").val()),
                            DtPoDetail: DtPoDetail,
                            DtInvoice: DtInv,
                            USERNAME: USERNAME
                        },
                        success: function(response, textStatus, jqXHR) {
                            $("#loader").removeClass('show');
                            $('#btnSave').removeAttr('disabled');
                            if (response.status == 200) {
                                alert(response.result.data);
                                Cancel();
                            } else if (response.status == 504) {
                                alert(response.result.data);
                                location.reload();
                            } else {
                                alert(response.result.data);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $("#loader").removeClass('show');
                            $('#btnSave').removeAttr('disabled');
                            alert('Data Save Failed !!');
                        }
                    });
                }
            }
        };
        var Cancel = function() {
            window.location.href = window.location.href.split("?")[0];
        }
    <?php } ?>
    //    Function - Function Formater Numberic
    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            formatCurrency($(this), "blur");
        }
    });

    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

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
            right_side = formatDesimal(right_side);
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
    //    End Formater Numberic
</script>