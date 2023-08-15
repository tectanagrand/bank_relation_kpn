<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link href="./assets/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" />
<link href="./assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.3.3/css/searchBuilder.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.1.2/css/dataTables.dateTime.min.css">
<script src="https://cdn.datatables.net/searchbuilder/1.3.3/js/dataTables.searchBuilder.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js"></script>
<style type="text/css">
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
// $CCompany = '';
// foreach ($DtCompany as $values) {
//     $CCompany .= '<option value=' . $values->ID . '>' . $values->COMPANYNAME . '</option>';
// }

$CDepartment = '';
foreach ($DtDepartment as $values) {
    $CDepartment .= '<option value="' . $values->DEPARTMENT . '">' . $values->DEPARTEMENTNAME . '</option>';
}
?>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Payment And Receive</li>
</ol>
<!-- <h1 class="page-header">Payment And Receive</h1> -->
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a href="#paymentreceive" data-toggle="tab" class="nav-link active">
            <span class="d-sm-none">Tab 1</span>
            <span class="d-sm-block d-none">Payment and Receive</span>
        </a>
    </li>
   <!--  <li class="nav-item">
        <a href="#import" data-toggle="tab" class="nav-link">
            <span class="d-sm-none">Tab 2</span>
            <span class="d-sm-block d-none">Upload</span>
        </a>
    </li> -->
</ul>
<div class="tab-content">
    <div class="tab-pane fade active show" id="paymentreceive">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                </div>
                <h4 class="panel-title">Payment And Receive</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="COMPANYGROUP">Company Group *</label>
                                    <select class="form-control mkreadonly" name="COMPANYGROUP" id="COMPANYGROUP">
                                        <option value="">All Company Group</option>
                                        <option value="CMT">CEMENT</option><option value="MOTIVE">MOTIVE</option><option value="PLT">PLANTATION</option><option value="PROPERTY">PROPERTY</option><option value="WOOD">WOOD</option>                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="COMPANYSUBGROUP">Company Sub Group *</label>
                                    <select class="form-control mkreadonly" name="COMPANYSUBGROUP" id="COMPANYSUBGROUP">
                                        <option value="">All Company Subgroup</option>
                                        <option value="UPSTREAM">UPSTREAM</option><option value="DOWNSTREAM">DOWNSTREAM</option></select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="COMPANY">Company *</label>
                                        <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                                            <option value="">All Company</option>
                                            <?php
                                            foreach ($DtCompany as $values) {
                                                echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="DEPARTMENT">Department </label>
                                        <select class="form-control" name="DEPARTMENT" id="DEPARTMENT">
                                            <option value="" selected>All Department</option>
                                            <?php echo $CDepartment; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="CASHFLOWTYPE">Payment Type</label>
                                        <select class="form-control" name="CASHFLOWTYPE" id="CASHFLOWTYPE">
                                            <option value="" selected>All</option>
                                            <option value="0">Receive</option>
                                            <option value="1">Payment</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="PERIOD">Period</label>
                                        <input type="text" class="form-control" name="PERIOD" id="PERIOD" placeholder="MMM YYYY" autocomplete="off">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="WEEK">Week</label>
                                        <select class="form-control" name="WEEK" id="WEEK">
                                            <option value="" selected>--All Week--</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <hr style="background:#e2e7eb!important;margin:.3rem 0!important;">
                                    </div>
                                </div>
                                <div class="col my-auto mb-2">
                                    <button id="btnAdd" type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i><span> Payment/Receive</span></button>
                                    <button onclick="VExport()" class="btn btn-sm btn-success"><i class="fa fa-file-excel"></i> Export</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row ml-0 mr-0 mb-0 mt-2 table-responsive">
                        <table id="DtPayment" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr role="row">
                                    <th class="text-center align-middle" style="width: 30px;" rowspan="2">No</th>
                                    <th class="text-center align-middle" rowspan="2">Department</th>
                                    <th class="text-center align-middle" rowspan="2">Company</th>
                                    <th class="text-center align-middle" rowspan="2">Business Unit</th>
                                    <th class="text-center align-middle" rowspan="2">Doc Number</th>
                                    <th class="text-center align-middle" rowspan="2">Vendor</th>
                                    <th class="text-center align-middle" rowspan="2">Doc Invoice</th>
                                    <th class="text-center align-middle" rowspan="2">Invoice Vendor No</th>
                                    <th class="text-center align-middle" rowspan="2">Due Date</th>
                                    <th class="text-center align-middle" rowspan="2">Week</th>
                                    <th class="text-center align-middle" rowspan="2">Priority</th>
                                    <th class="text-center align-middle" rowspan="2">Currency</th>
                                    <th class="text-center" colspan="4">Amount</th>
                                    <th class="text-center align-middle" rowspan="2"></th>
                                </tr>
                                <tr>
                                    <th class="text-center">Invoice</th>
                                    <th class="text-center">Forecast</th>
                                    <th class="text-center">Paid</th>
                                    <th class="text-center">OS</th>
                                </tr>
                            </thead>
                        </table>
                        <div id="overlay" style="display: none;">
                            <span class="spinner"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="MAddEdit">
                <div class="modal-dialog" style="max-width: 95%  !important;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Payment Or Receive</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="FAddEdit" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="PAYMENTTYPE">Payment Type *</label>
                                            <select class="form-control" name="PAYMENTTYPE" id="PAYMENTTYPE" required>
                                                <option value="" disabled selected>--Choose Type--</option>
                                                <option value="0">Receive</option>
                                                <option value="1">Payment</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="COMPANYCODE">Company *</label>
                                            <select class="form-control" name="COMPANYCODE" id="COMPANYCODE" required>
                                                <option value="" disabled selected>--Choose Company--</option>
                                                <?php
                                                foreach ($DtCompany as $values) {
                                                    echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="BANK">Bank *</label>
                                            <select class="form-control" name="BANK" id="BANK" required>
                                                <option value="" disabled selected>--Choose Company--</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="DATERELEASE">Date Paid *</label>
                                            <input type="text" class="form-control" name="DATERELEASE" id="DATERELEASE" placeholder="MM/DD/YYYY" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="VOUCHER">Voucher *</label>
                                            <input type="text" class="form-control" name="VOUCHER" id="VOUCHER" placeholder="Voucher" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="GIRO">Cek/Giro *</label>
                                            <input type="text" class="form-control" name="GIRO" id="GIRO" placeholder="Cek/Giro" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="CURRENCY">Currency *</label>
                                            <select class="form-control" name="CURRENCY" id="CURRENCY" required>
                                                <option value="" disabled selected>--Choose Currency--</option>
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
                                            <label for="RATE">Rate *</label>
                                            <input type="text" class="form-control" name="RATE" id="RATE" data-type='currency' placeholder="Rate">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-row">
                                            <div class="col my-auto">
                                                <button id="AddData" type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i><span> Add Data</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive mt-2">
                                    <table id="DtPayment1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr role="row">
                                                <th class="text-center align-middle" style="width: 30px;" rowspan="2">No</th>
                                                <th class="text-center align-middle" rowspan="2">Data By</th>
                                                <th class="text-center align-middle" rowspan="2">Department</th>
                                                <th class="text-center align-middle" rowspan="2">Doc Number</th>
                                                <th class="text-center align-middle" rowspan="2">Vendor</th>
                                                <th class="text-center align-middle" rowspan="2">Doc Invoice</th>
                                                <th class="text-center align-middle" rowspan="2">Invoice Vendor No</th>
                                                <th class="text-center align-middle" rowspan="2">Due Date</th>
                                                <th class="text-center align-middle" rowspan="2">Currency</th>
                                                <th class="text-center align-middle" rowspan="2">Remark</th>
                                                <th class="text-center" colspan="4">Amount</th>
                                                <th class="text-center align-middle" rowspan="2"></th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Invoice</th>
                                                <th class="text-center">Source</th>
                                                <th class="text-center">Paid/Receive</th>
                                                <th class="text-center">Conversi</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr role="row">
                                                <th class="text-right" colspan="11">Total :</th>
                                                <th class="text-right"></th>
                                                <th class="text-right"></th>
                                                <th class="text-right"></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
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

            <div class="modal fade" id="MDataPR">
                <div class="modal-dialog" style="max-width: 95%  !important;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Data Payment</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="DATABY">Data By </label>
                                                <select class="form-control" name="DATABY" id="DATABY">
                                                    <option value="" selected>--All Data--</option>
                                                    <option value="100001">Forecast</option>
                                                    <option value="100002">Non Forecast</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                             <div class="form-group">
                                                <label for="DATEFROM">Date From</label>
                                                <input type="text" class="form-control" name="DATEFROM" id="DATEFROM" placeholder="M YYYY">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                             <div class="form-group">
                                                <label for="DATETO">Date To</label>
                                                <input type="text" class="form-control" name="DATETO" id="DATETO" placeholder="M YYYY">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                             <div class="form-group">
                                                <label for="NODOC">No Document</label>
                                                <input type="text" class="form-control" name="NODOC" id="NODOC" placeholder="No Document">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Show</label>
                                                <button type="button" class="form-control btn btn-info" style="padding: 3px 10px;" onclick="showDtOs()">Show</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt-2">
                                <table id="DtOS" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr role="row">
                                            <th class="text-center align-middle" style="width: 30px;" rowspan="2"><input type="checkbox" id="pil"></th>
                                            <th class="text-center align-middle" style="width: 30px;" rowspan="2">No</th>
                                            <th class="text-center align-middle" rowspan="2">Data By</th>
                                            <th class="text-center align-middle" rowspan="2">Business Unit</th>
                                            <th class="text-center align-middle" rowspan="2">Doc Number</th>
                                            <th class="text-center align-middle" rowspan="2">Vendor</th>
                                            <th class="text-center align-middle" rowspan="2">Doc Invoice</th>
                                            <th class="text-center align-middle" rowspan="2">Invoice Vendor No</th>
                                            <th class="text-center align-middle" rowspan="2">Due Date</th>
                                            <th class="text-center align-middle" rowspan="2">Year</th>
                                            <th class="text-center align-middle" rowspan="2">Month</th>
                                            <th class="text-center align-middle" rowspan="2">Week</th>
                                            <th class="text-center align-middle" rowspan="2">Priority</th>
                                            <th class="text-center align-middle" rowspan="2">Currency</th>
                                            <th class="text-center" colspan="2">Amount</th>
                                            <!--<th class="text-center align-middle" rowspan="2"></th>-->
                                        </tr>
                                        <tr>
                                            <th class="text-center">Invoice</th>
                                            <th class="text-center">Source</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="AddDtPaid()">Add</button>
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
            <div class="modal fade" id="MAPaid">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Set Amount Paid or Receive </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="FAPaid" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                            <div class="modal-body">
                                <div class="row">
                                    <label for="SOURCE">Source *</label>
                                    <input type="text" class="form-control text-right" name="SOURCE" id="SOURCE" data-type='currency' placeholder="Source" disabled>
                                </div>
                                <div class="row">
                                    <label for="AMOUNTPAID">Amount Paid or Receive *</label>
                                    <input type="text" class="form-control text-right" name="AMOUNTPAID" id="AMOUNTPAID" data-type='currency' placeholder="Amount Paid or Receive" required>
                                </div>
                                <div class="row">
                                    <label for="REMARK">Remark</label>
                                    <input type="text" class="form-control" name="REMARK" id="REMARK" placeholder="REMARK">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" onclick="EditAPaid()">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="import">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    </div>
                    <h4 class="panel-title_">Import</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-row">
                                <div class="row">
                                    <!-- <div class="form-group col-md-4">
                                        <label for="COMPANY">Company *</label>
                                        <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                                            <option value="">All Company</option>
                                            <?php
                                            foreach ($DtCompany as $values) {
                                                echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div> -->
                                    <!-- <div class="form-group col-md-3">
                                        <label for="DEPARTMENT">Department </label>
                                        <select class="form-control" name="DEPARTMENT" id="DEPARTMENT">
                                            <option value="" selected>All Department</option>
                                            <?php echo $CDepartment; ?>
                                        </select>
                                    </div> -->
                                    <div class="form-group col-md-12">
                                        <label for="CASHFLOWTYPE">Payment Type</label>
                                        <select class="form-control" name="CASHFLOWTYPE_2" id="CASHFLOWTYPE_2">
                                            <option value="" selected>None</option>
                                            <option value="0">Receive</option>
                                            <option value="1">Payment</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <span class="btn btn-primary fileinput-button m-r-3">
                                            <!--<i class="fa fa-plus"></i>-->
                                            <span>Browse File</span>
                                            <input type="file" class="upload-file" data-max-size="1048576" onchange="filesChange(this)"> <!--10485760-->
                                        </span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <button id="btnSave" type="button" class="btn btn-primary m-r-3" onclick="SaveUpload()">
                                            <!--<i class="fa fa-upload"></i>-->
                                            <span>Upload Data</span>
                                        </button>
                                    </div>
                                    <div class="form-group col-md-4 ">
                                        <button id="btnReset" type="button" class="btn btn-default m-r-3" onclick="ClearData()">
                                            <!--<i class="fa fa-upload"></i>-->
                                            <span>Clear Data</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-0 table-responsive">
                    <table id="DtUpload" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                        <thead>
                            <tr role="row">
                                <th class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Vendor</th>
                                <th class="text-center">Doc Ref</th>
                                <th class="text-center">Doc Number</th>
                                <th class="text-center sorting">Forecast ID</th>
                                <th class="text-center sorting">Bank Code</th>
                                <th class="text-center sorting">Paid Date</th>
                                <th class="text-center sorting">Paid/Receive ( Amount )</th>
                                <th class="text-center sorting">Rate</th>
                                <th class="text-center sorting">Amount Conversi</th>
                                <th class="text-center sorting">Voucher</th>
                                <th class="text-center sorting">Giro</th>
                                <th class="text-center sorting">Remarks</th>
                                <!-- <th class="sorting_disabled"></th> -->
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var USERACCESS = "<?php echo $DtUser2->USERACCESS; ?>";
    var table, COMPANY1, DValue, table2, DTPAY = '',
    table3, DtPaid = [],
    idx;
    var DATABY = 1,
    IDXIN = 0,
    IDXOUT = 0;
    var YEAR = "",
    MONTH = "",
    AMOUNTPAID, AMOUNTSOURCE, BANKCODE, BANKCURRENCY;
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }

    $.ajax({
                    url: "<?php echo site_url('Payment/getFlag'); ?>",
                    method: "POST",
                    dataType: 'json',
                    data: {masterid: '000014'},
                    success:function(response)
                    {
                        var res = response.result.data.FLAG_ACTIVE;
                        getFlag = res;
                    }
                });
    
    var tgl = mm + '/' + dd + '/' + today.getFullYear();
    var dd  =  mm + '/' + today.getFullYear();
    $('#DATEFROM').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "format": "yyyy/mm",
        "viewMode": "months",
        "minViewMode": "months"
    });
    // $("#DATEFROM").datepicker('setDate', dd);
    $('#DATETO').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "format": "yyyy/mm",
        "viewMode": "months",
        "minViewMode": "months"
    });
    // $("#DATETO").datepicker('setDate', dd);
    if (!$.fn.DataTable.isDataTable('#DtPayment')) {
        table = $('#DtPayment').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('Payment/ShowDataForecast') ?>",
                "type": "POST",
                "datatype": "JSON",
                "data": function(d) {
                    d.YEAR = YEAR;
                    d.MONTH = MONTH;
                    d.USERNAME = USERNAME;
                    d.WEEK = $('#WEEK').val();
                    d.DEPARTMENT = $('#DEPARTMENT').val();
                    d.COMPANYGROUP = $('#COMPANYGROUP').val();
                    d.COMPANYSUBGROUP = $('#COMPANYSUBGROUP').val();
                    d.COMPANY = $('#COMPANY').val();
                    d.CASHFLOWTYPE = $('#CASHFLOWTYPE').val();
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
                    $("#loader").addClass('show');
                },
                "complete": function() {
                    $("#loader").removeClass('show');
                }
            },
            "columns": [{
                "data": null,
                "className": "text-center",
                "orderable": false,
                render: function(data, type, row, meta) {
                        //                        if (row.CASHFLOWTYPE == "0") {
                        //                            IDXIN++;
                        //                            return IDXIN;
                        //                        } else {
                        //                            IDXOUT++;
                        //                            return IDXOUT;
                        //                        }
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
                    "data": "DOCREF"
                },
                {
                    "data": "VENDORNAME"
                },
                {
                    "data": "DOCNUMBER"
                },
                {
                    "data": "INVOICEVENDORNO"
                },
                {
                    "data": "DUEDATE"
                },
                {
                    "data": "WEEK"
                },
                {
                    "data": "PRIORITY"
                },
                {
                    "data": "CURRENCY"
                },
                {
                    "data": "AMOUNTINVOICE",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "AMOUNTFORECAST",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
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
                    render: function(data, type, row, meta) {
                        var html = '';
                        html += '<button class="btn btn-info btn-sm edit" title="Pay">Pay</button>';
                        return html;
                    }
                }
                ],
                "search": {
                    "regex": true
                }
            });
            table.on('click', '.edit', function() {
                $tr = $(this).closest('tr');
                var data = table.row($tr).data();
                DtPaid = [];
                $('#FAddEdit').parsley().reset();
                $("#PAYMENTTYPE").val(data.CASHFLOWTYPE);
                $("#COMPANYCODE").val(data.COMPANY);
                $("#CURRENCY").val(data.CURRENCY);
                $("#RATE").val("1");
                formatCurrency($('#RATE'), "blur");
                // $("#DATERELEASE").datepicker('setDate', tgl);
                $("#VOUCHER").val('');
                $("#GIRO").val('');
                data.AMOUNTPAID = data.AMOUNTFORECAST;
                data.AMOUNTCONVERSI = data.AMOUNTFORECAST;
                data.REMARK = "";
                DTPAY = "";
                if (data.FORECASTID == '' || data.FORECASTID == null || data.FORECASTID == undefined) {
                    DTPAY = "'" + data.ID + "'";
                } else {
                    DTPAY = "'" + data.ID + data.FORECASTID + "'";
                }
                DtPaid.push(data);
                DisablePC();
                LoadData();
                if (COMPANY1 != data.COMPANY) {
                    COMPANY1 = data.COMPANY;
                    $('#BANK').find('option:not(:first)').remove().end().val('');
                    $('#loader').addClass('show');
                    $.ajax({
                        dataType: "JSON",
                        type: "POST",
                        url: "<?php echo site_url('Payment/DtBankCompany'); ?>",
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
                        error: function(jqXHR, textStatus, errorThrown) {
                            $('#loader').removeClass('show');
                            alert('Please Check Your Connection !!!');
                        }
                    });
                } else {
                    $("#BANK").val(DValue);
                    $("#BANK").change();
                    $("#MAddEdit").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            });
}
var DataReload = function() {
    table.ajax.reload();
};
$("#DEPARTMENT").on({
    'change': function() {
        DataReload();
    }
});
$("#COMPANYGROUP").on({
    'change': function() {
        // $('#loader').addClass('show');
        // var gFCCODE = $(this).val();
        
        // $.ajax({
        //     url : "<?php echo site_url('Payment/getSubGroup');?>",
        //     method : "POST",
        //     data : {FCCODE: gFCCODE},
        //     async : true,
        //     dataType : 'json',
        //     success: function(data){
        //                 // console.log(data.result);
        //                 var listSubGroup = '';
        //                 var listCompany = '';
        //                 var i;
        //                 for(i=0; i<data.result.data.length; i++){
        //                     listSubGroup += '<option value='+data.result.data[i].FCCODE+'>'+data.result.data[i].FCNAME+'</option>';
        //                 }
        //                 for(i=0; i<data.result.data_2.length; i++){
        //                     listCompany += '<option value='+data.result.data_2[i].ID+'>'+data.result.data_2[i].COMPANYCODE+' - '+data.result.data_2[i].COMPANYNAME+'</option>';
        //                 }
        //                 $('#COMPANYSUBGROUP').html(listSubGroup);
        //                 $('#COMPANY').html(listCompany);

                        
        //                 $('#loader').removeClass('show');
                        
        //             },
        //             error: function(jqXHR, textStatus, errorThrown) {
        //                 $('#loader').removeClass('show');
        //                 alert('Please Check Your Connection !!!');
        //             }
        //         });
        // return false;
        DataReload();
    }
});
$("#COMPANYSUBGROUP").on({
    'change': function() {
        DataReload();
    }
});
$("#COMPANY").on({
    'change': function() {
        DataReload();
    }
});
$("#CASHFLOWTYPE").on({
    'change': function() {
        DataReload();
    }
});

$("#COMPANYCODE").on({
    'change': function() {
        COMPANY1 = this.value;
        $('#BANK').find('option:not(:first)').remove().end().val('');
            // $('#loader').addClass('show');
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Payment/DtBankCompany'); ?>",
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
                        $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('Payment/getPaymentPeriod'); ?>",
                            data: {
                                COMPANY: COMPANY1
                            },
                            success: function(response) {
                                $('#DATERELEASE').datepicker('destroy');
                                if(getFlag == 1){

                                    var pYear = response.result.data.CURRENTACCOUNTINGYEAR;
                                    var pMonth = parseInt(response.result.data.CURRENTACCOUNTINGPERIOD) - 1;
                                    if(pMonth < 10){
                                        pMonth = "0" + pMonth;
                                    }
                                    
                                    var mDate = new Date(pYear, pMonth, '01');
                                    // console.log(pMonth);
                                    $('#DATERELEASE').datepicker({
                                            "autoclose": true,
                                            "format": "mm/dd/yyyy",
                                            "startDate": mDate
                                    }); 
                                }else{
                                    
                                    $('#DATERELEASE').datepicker({
                                            "autoclose": true,
                                            "format": "mm/dd/yyyy",
                                            "setDate": tgl
                                    }); 
                                }
                                             
                                $('#DATERELEASE').datepicker('update');
                            }
                        });
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

$("#btnAdd").on({
    'click': function() {
        var selectedCompany     = $('#COMPANY').children("option:selected").val();
        var valueCompany        = $('#COMPANY').val();
        var selectedPaymentType = $('#CASHFLOWTYPE').children("option:selected").val();
        var valuePaymentType    = $('#CASHFLOWTYPE').val();

        COMPANY1 = valueCompany;
        
        $('#BANK').find('option:not(:first)').remove().end().val('');
        $('#loader').addClass('show');
        $.ajax({
            dataType: "JSON",
            type: "POST",
            url: "<?php echo site_url('Payment/DtBankCompany'); ?>",
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
        $("#PAYMENTTYPE").val(selectedPaymentType);
        $("#COMPANYCODE").val(selectedCompany);
            // $("#BANK").val('');
            // $("#DATERELEASE").datepicker('setDate', tgl);
            $("#VOUCHER").val('');
            $("#GIRO").val('');
            $("#CURRENCY").val('');
            $("#RATE").val("1");
            formatCurrency($('#RATE'), "blur");
            // $("#BANK").change();
            DTPAY = "";
            DtPaid = [];
            DisablePC();
            LoadData();
            $('#FAddEdit').parsley().reset();
            $("#MAddEdit").modal({
                backdrop: 'static',
                keyboard: false
            });
        }
    });
var LoadData = function() {

    if (!$.fn.DataTable.isDataTable("#DtPayment1")) {
        table3 = $("#DtPayment1").DataTable({
            "aaData": DtPaid,
            "columns": [{
                "data": null,
                "className": "text-center",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                "data": "DATABYNAME"
            },
            {
                "data": "DEPARTMENT"
            },
            {
                "data": "DOCREF"
            },
            {
                "data": "VENDORNAME"
            },
            {
                "data": "DOCNUMBER"
            },
            {
                "data": "INVOICEVENDORNO"
            },
            {
                "data": "DUEDATE"
            },
            {
                "data": "CURRENCY"
            },
            {
                "data": "REMARK"
            },
            {
                "data": "AMOUNTINVOICE",
                "className": "text-right",
                render: $.fn.dataTable.render.number(',', '.', 2)
            },
            {
                "data": "AMOUNTFORECAST",
                "className": "text-right",
                render: $.fn.dataTable.render.number(',', '.', 2)
            },
            {
                "data": "AMOUNTPAID",
                "className": "text-right",
                render: function(data, type, row, meta) {
                    var html = "";
                    html = '<a href="javascript:void(0);" class="AMOUNTPAID" role="button" title="Status Action">' + fCurrency(data) + '</a>';
                    return html;
                }
            },
            {
                "data": "AMOUNTCONVERSI",
                "className": "text-right",
                render: $.fn.dataTable.render.number(',', '.', 2)
            },
            {
                "data": null,
                "className": "text-center",
                render: function(data, type, row, meta) {
                    var html = "";
                    html = '<a href="javascript:void(0);" class="btn btn-danger btn-icon btn-circle btn-sm delete" role="button" title="Delete">\n\
                    <i class="fa fa-trash" aria-hidden="true"></i>\n\
                    </a>';
                    return html;
                }
            }
            ],
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
                    AMOUNTSOURCE = api.column(10).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    AMOUNTPAID = api.column(11).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    AMOUNTCONVERSI = api.column(12).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                    $(api.column(10).footer()).html(numFormat(AMOUNTSOURCE));
                    $(api.column(11).footer()).html(numFormat(AMOUNTPAID));
                    $(api.column(12).footer()).html(numFormat(AMOUNTCONVERSI));
                }
            });
        table3.on('click', ".AMOUNTPAID", function() {
            $tr = $(this).closest('tr');
            idx = table3.row($tr).index();
            $('#FAPaid').parsley().reset();
            $('#FAPaid .modal-title').text('Edit Amount Paid or Receive (' + DtPaid[idx].DOCREF + " - " + DtPaid[idx].DOCNUMBER + ')');
            $('#REMARK').val(DtPaid[idx].REMARK);
            $('#SOURCE').val(DtPaid[idx].AMOUNTFORECAST);
            formatCurrency($('#SOURCE'), "blur");
            $('#AMOUNTPAID').val(DtPaid[idx].AMOUNTPAID);
            formatCurrency($('#AMOUNTPAID'), "blur");
            $('#MAPaid').on('hidden.bs.modal', function(event) {
                $('body').addClass('modal-open');
            }).modal({
                backdrop: 'static',
                keyboard: false
            });
        });
        table3.on('click', ".delete", function() {
            $tr = $(this).closest('tr');
            idx = table3.row($tr).index();
            if (DtPaid[idx].FORECASTID == "" || DtPaid[idx].FORECASTID == null || DtPaid[idx].FORECASTID == undefined) {
                DTPAY = DTPAY.replace("'" + DtPaid[idx].ID + "', ", "").toString();
                DTPAY = DTPAY.replace(", '" + DtPaid[idx].ID + "'", "").toString();
                DTPAY = DTPAY.replace("'" + DtPaid[idx].ID + "'", "").toString();
                DtPaid.splice(idx, 1);
                DisablePC();
                table3.clear();
                table3.rows.add(DtPaid);
                table3.draw();
            } else {
                DTPAY = DTPAY.replace("'" + DtPaid[idx].ID + DtPaid[idx].FORECASTID + "', ", "").toString();
                DTPAY = DTPAY.replace(", '" + DtPaid[idx].ID + DtPaid[idx].FORECASTID + "'", "").toString();
                DTPAY = DTPAY.replace("'" + DtPaid[idx].ID + DtPaid[idx].FORECASTID + "'", "").toString();
                DtPaid.splice(idx, 1);
                DisablePC();
                table3.clear();
                table3.rows.add(DtPaid);
                table3.draw();
            }
        });
    } else {
        table3.clear();
        table3.rows.add(DtPaid);
        table3.draw();
    }
};
var EditAPaid = function() {
    if ($('#FAPaid').parsley().validate()) {
        if (parseFloat(formatDesimal($('#SOURCE').val())) < parseFloat(formatDesimal($('#AMOUNTPAID').val()))) {
            alert("Amount Source can't above Amount Paid!!");
        }else if (parseFloat(formatDesimal($('#AMOUNTPAID').val())) == 0 || parseFloat(formatDesimal($('#AMOUNTPAID').val())) == null || parseFloat(formatDesimal($('#AMOUNTPAID').val())) == '') {
            alert("Amount Source can't null");
        } else {
            DtPaid[idx].REMARK = $('#REMARK').val();
            DtPaid[idx].AMOUNTPAID = formatDesimal($('#AMOUNTPAID').val());
            DtPaid[idx].AMOUNTCONVERSI = formatDesimal($('#AMOUNTPAID').val()) * formatDesimal($("#RATE").val());
            table3.clear();
            table3.rows.add(DtPaid);
            table3.draw();
            $('#MAPaid').modal("hide");
        }
    }
};
$('#PERIOD').datepicker({
    "autoclose": true,
    "todayHighlight": true,
    "viewMode": "months",
    "minViewMode": "months",
    "format": "M yyyy"
});
$("#PERIOD").on({
    'change': function() {
        $('#loader').addClass('show');
        MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
        YEAR = this.value.substr(4, 4);
        $('#WEEK').find('option:not(:first)').remove().end().val('');
        $.ajax({
            dataType: "JSON",
            type: "POST",
            url: "<?php echo site_url('Forecast/GetWeek'); ?>",
            data: {
                PERIOD: $('#PERIOD').val()
            },
            success: function(response, textStatus, jqXHR) {
                $('#loader').removeClass('show');
                if (response.status == 200) {
                    var html = '';
                    $.each(response.result.data, function(index, value) {
                        html += "<option value='" + value.WEEK + "'>" + value.WEEKKET + '</option>';
                    });
                    $(html).insertAfter("#WEEK option:first");
                    DataReload();
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
$("#WEEK").on({
    'change': function() {
        DataReload();
    }
});
$("#BANK").on({
    'change': function() {
        if (this.value == '' || this.value == null || this.value == undefined) {
            BANKCODE = "";
            BANKCURRENCY = "";
            $("#CURRENCY").change();
        } else {
            var DBANK = JSON.parse(this.value);
            BANKCODE = DBANK.BANKCODE;
            BANKCURRENCY = DBANK.CURRENCY;
            $("#CURRENCY").change();
        }
    }
});
$("#CURRENCY").on({
    'change': function() {
        var dataKeywords = $(this).val();
        if ((BANKCURRENCY == '' || BANKCURRENCY == null || BANKCURRENCY == undefined) || (this.value == '' || this.value == null || this.value == undefined)) {
            $("#RATE").removeAttr("required");
            $("#RATE").prop("disabled", true);
        } else {
            if (this.value == BANKCURRENCY) {
                $("#RATE").val("1");
                formatCurrency($('#RATE'), "blur");
                $("#RATE").removeAttr("required");
                $("#RATE").prop("disabled", true);
                if (DtPaid.length > 0) {
                    $.each(DtPaid, function(index, value) {
                        DtPaid[index].AMOUNTCONVERSI = DtPaid[index].AMOUNTFORECAST * formatDesimal(dataKeywords);
                    });
                    table3.clear();
                    table3.rows.add(DtPaid);
                    table3.draw();
                }
            } else {
                $("#RATE").removeAttr("disabled");
                $("#RATE").prop("required", true);
            }
        }

    }
});
var timeOutonKeyup = null;
$("#RATE").on({
    'input': function() {
        var dataKeywords = $(this).val();
        clearTimeout(timeOutonKeyup);
        timeOutonKeyup = setTimeout(function() {
            if (DtPaid.length > 0) {
                $.each(DtPaid, function(index, value) {
                    DtPaid[index].AMOUNTCONVERSI = DtPaid[index].AMOUNTFORECAST * formatDesimal(dataKeywords);
                });
                table3.clear();
                table3.rows.add(DtPaid);
                table3.draw();
            }
        }, 1000);
    }
});
$("#AddData").on({
    'click': function() {
        if (
            ($('#PAYMENTTYPE').val() == '' || $('#PAYMENTTYPE').val() == null || $('#PAYMENTTYPE').val() == undefined) ||
            ($('#COMPANYCODE').val() == '' || $('#COMPANYCODE').val() == null || $('#COMPANYCODE').val() == undefined) ||
            ($('#CURRENCY').val() == '' || $('#CURRENCY').val() == null || $('#CURRENCY').val() == undefined)
            ) {
            alert('Please, Choose Payment Type, Company And Currency First !!!');return;
    } else {
        if ($.fn.DataTable.isDataTable('#DtOS')) {
            table2.ajax.reload();
        }
            $("#MDataPR").on('hidden.bs.modal', function(event) {
                $('body').addClass('modal-open');
            }).modal({
                backdrop: 'static',
                keyboard: false
            });
        }
    }
});

function showDtOs(){
    if (!$.fn.DataTable.isDataTable('#DtOS')) {
                table2 = $('#DtOS').DataTable(
                {
                    "deferRender":true,
                    "processing": true,
                            "ajax": {
                                "url": "<?php echo site_url('Payment/DtOsPayment1') ?>",
                                "type": "POST",
                                "datatype": "JSON",
                                "language": {
                                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading..n.</span> '
                                },
                                "data": function(d) {
                                    d.DATEFROM = $('#DATEFROM').val();
                                    d.DATETO = $('#DATETO').val();
                                    d.NODOC = $('#NODOC').val();
                                    d.USERNAME = USERNAME;
                                    d.CASHFLOWTYPE = $('#PAYMENTTYPE').val();
                                    d.COMPANY = $('#COMPANYCODE').val();
                                    d.DATABY = $('#DATABY').val();
                                    d.CURRENCY = $('#CURRENCY').val();
                                    d.DTPAY = DTPAY;
                                },
                                "dataSrc": function(ext) {
                                    if (ext.status == 200) {
                                        //                                    ext.draw = ext.result.data.draw;
                                        //                                    ext.recordsTotal = ext.result.data.recordsTotal;
                                        //                                    ext.recordsFiltered = ext.result.data.recordsFiltered;
                                        //                                    return ext.result.data.data;
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
                                    $("#loader").addClass('show');
                                },
                                "complete": function() {
                                    $("#loader").removeClass('show');
                                }

                            },
                            "columns": [{
                                "data": null,
                                "className": "text-center",
                                "orderable": false,
                                render: function(data, type, row, meta) {
                                    return '<input type="checkbox" class="pils">';
                                }
                            },
                            {
                                "data": null,
                                "className": "text-center",
                                render: function(data, type, row, meta) {
                                    return meta.row + meta.settings._iDisplayStart + 1;
                                }
                            },
                            {
                                "data": "DATABYNAME"
                            },
                            {
                                "data": "BUSINESSUNITNAME"
                            },
                            {
                                "data": "DOCREF"
                            },
                            {
                                "data": "VENDORNAME"
                            },
                            {
                                "data": "DOCNUMBER"
                            },
                            {
                                "data": "INVOICEVENDORNO"
                            },
                            {
                                "data": "DUEDATE"
                            },
                            {
                                "data": "YEAR"
                            },
                            {
                                "data": "MONTH"
                            },
                            {
                                "data": "WEEK"
                            },
                            {
                                "data": "PRIORITY"
                            },
                            {
                                "data": "CURRENCY"
                            },
                            {
                                "data": "AMOUNTINVOICE",
                                "className": "text-right",
                                render: $.fn.dataTable.render.number(',', '.', 2)
                            },
                            {
                                "data": "AMOUNTOS",
                                "className": "text-right",
                                render: $.fn.dataTable.render.number(',', '.', 2)
                            }
                            ],
                            "search": {
                                "regex": true
                            }
                        }
                );
                table2.on('change', '.pils', function() {
                    $tr = $(this).closest('tr');
                    var data = table2.row($tr).data();
                    if (this.checked) {
                        data.FLAG = "1";
                    } else {
                        data.FLAG = "0";
                    }
                });
            } 
            else {
                    table2.ajax.reload();
            }
}
$("#DATABY").on({
    'change': function() {
        table2.ajax.reload();
    }
});
$('#pil').on('change', function() {
    if (this.checked) {
        $('#DtOS .pils').prop("checked", true);
    } else {
        $('#DtOS .pils').prop("checked", false);
    }
    $('#DtOS .pils').change();
});
var AddDtPaid = function() {
    $.each(table2.data(), function(index, value) {
        if (value.ID == undefined || value.ID == null || value.ID == '') {} else {
            if (value.FLAG == 1) {
                value.AMOUNTPAID = value.AMOUNTINVOICE;
                value.AMOUNTCONVERSI = value.AMOUNTFORECAST * formatDesimal($("#RATE").val());
                value.REMARK = '';
                DtPaid.push(value);
                if (DTPAY == '' || DTPAY == null || DTPAY == undefined) {
                    if (value.FORECASTID == '' || value.FORECASTID == null || value.FORECASTID == undefined) {
                        DTPAY = "'" + value.ID + "'";
                    } else {
                        DTPAY = "'" + value.ID + value.FORECASTID + "'";
                    }
                } else {
                    if (value.FORECASTID == '' || value.FORECASTID == null || value.FORECASTID == undefined) {
                        DTPAY += ", '" + value.ID + "'";
                    } else {
                        DTPAY += ", '" + value.ID + value.FORECASTID + "'";
                    }
                }
            }
        }
    });
    DisablePC();
    LoadData();
    $('#MDataPR').modal("hide");
};
var DisablePC = function() {
    if (DtPaid.length > 0) {
        $('#PAYMENTTYPE').attr('disabled', true);
        $('#PAYMENTTYPE').removeAttr('required');
            //$('#COMPANYCODE').attr('disabled', true);
            //$('#COMPANYCODE').removeAttr('required');
            $('#CURRENCY').attr('disabled', true);
            $('#CURRENCY').removeAttr('required');
        } else {
            $('#PAYMENTTYPE').attr('required', true);
            $('#PAYMENTTYPE').removeAttr('disabled');
            //$('#COMPANYCODE').attr('required', true);
            //$('#COMPANYCODE').removeAttr('disabled');
            $('#CURRENCY').attr('required', true);
            $('#CURRENCY').removeAttr('disabled');
        }
    };
    var Save = function() {
        if ($('#FAddEdit').parsley().validate()) {
            if (DtPaid.length <= 0) {
                alert("Detail Record Not Set !!!");
            }else if($('#BANK').val() == '' || $('#BANK').val() == '--Choose Company--'){
                alert("Can't empty");
            } else {
                $("#loader").addClass('show');
                $('#FAddEdit button[type="submit"]').attr('disabled', true);
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Payment/SavePayment'); ?>",
                    data: {
                        CASHFLOWTYPE: $("#PAYMENTTYPE").val(),
                        COMPANY: $("#COMPANYCODE").val(),
                        BANKCODE: BANKCODE,
                        DATERELEASE: $("#DATERELEASE").val(),
                        VOUCHERNO: $("#VOUCHER").val(),
                        NOCEKGIRO: $("#GIRO").val(),
                        RATE: formatDesimal($("#RATE").val()),
                        AMOUNTPAID: AMOUNTPAID,
                        DtPaid: DtPaid,
                        USERNAME: USERNAME
                    },
                    success: function(response, textStatus, jqXHR) {
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
                    error: function(jqXHR, textStatus, errorThrown) {
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
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            formatCurrency($(this), "blur");
        }
    });

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
            var url = "<?php echo site_url('Process/PaymentReceiveExport'); ?>?type=PARAM1&DOCDATEFROM=PARAM2&DOCDATETO=PARAM3&DEPARTMENT=PARAM4&USERNAME=PARAM5";
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
    //    End Formater Numberic
</script>
<script>
    // var UUID = "<?php echo $UUID; ?>";
    var files, filetypeUpload = ['XLS', 'XLSX'];
    var DtUpload = [];
    var STATUS = true;
    var tbl_upload, FILENAME;
    
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
    if (!$.fn.DataTable.isDataTable('#DtUpload')) {
        $('#DtUpload').DataTable({
            "aaData": DtUpload,
            "columns": [
            {
                "data": null,
                "className": "text-center",
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {
                "data": null,
                "className": "text-center",
                render: function (data, type, row, meta) {
                    var html = '';
                    if (data.ERROR_MESSAGE == null) {
                        html += '<span class="badge badge-pill badge-success">Done</span>';
                    } else {
                        html += '<span class="badge badge-pill badge-danger" title="' + data.ERROR_MESSAGE + '">' + data.ERROR_MESSAGE + '</span>';
                        STATUS = false;
                    }
                    return html;
                }
            },
            {"data":"FCCODE"},
            {"data":"DOCREF"},
            {"data":"DOCNUMBER"},
            {"data": "FORECASTID"},
            {"data": "BANKCODE"},
            {"data": "DATERELEASE"},
            {"data": "AMOUNT",
            render: $.fn.dataTable.render.number(',', '.', 2)
        },
        {"data": "RATE",
        render: $.fn.dataTable.render.number(',', '.', 2)
    },
    {
        "data": "AMOUNTBANK",
        render: $.fn.dataTable.render.number(',', '.', 2)
    }, 
    {"data": "VOUCHERNO"},
    {"data": "NOCEKGIRO"},
    {"data": "REMARK"},
                // {
                //     "data": null,
                //     "className": "text-center",
                //     render: function (data, type, row, meta) {
                //         var html = '';
                //         html += '<button class="btn btn-success btn-icon btn-circle btn-sm view" title="View Detail" style="margin-right: 5px;">\n\
                //                         <i class="fa fa-eye" aria-hidden="true"></i>\n\
                //                      </button>';
                //         return html;
                //     }
                // }
                ],
                "bFilter": true,
                "bPaginate": true,
                "bLengthChange": true,
                "bInfo": true,
                "responsive": false
            });
        tbl_upload = $('#DtUpload').DataTable();
    }



    function filesChange(elm) {
        var curDate = new Date();
        var day   = curDate.getDate();
        var month = curDate.getMonth() + 1;

        if ( month < 10 ){
            month = "0" + month;
        }
        if( day < 10){
            day = "0" + day;
        }
        var currentDate = month + '/' + day + '/' + curDate.getFullYear();
        if ($('#CASHFLOWTYPE_2').val() == '' || $('#CASHFLOWTYPE_2').val() == null || $('#CASHFLOWTYPE_2').val() == undefined) {
            alert('Please, Choose Payment Type Firts!!!');
            files = '';
            $('.upload-file').val('');
        } else {
            var fileInput = $('.upload-file');
            var extFile = $('.upload-file').val().split('.').pop().toUpperCase();
            var maxSize = fileInput.data('max-size');
            if ($.inArray(extFile, filetypeUpload) === -1) {
                alert('Format file tidak valid!!');
                files = '';
                $('.upload-file').val('');
                return;
            } else {
                if (fileInput.get(0).files.length) {
                    var fileSize = fileInput.get(0).files[0].size;
                    if (fileSize > maxSize) {
                        alert('Ukuran file terlalu besar!!!');
                        files = '';
                        $('.upload-file').val('');
                        return;
                    } else {
                        $('#loader').addClass('show');
                        files = elm.files;
                        FILENAME = files[0].name;
                        $(".panel-title_").text('Document Upload : ' + FILENAME);
                        DisableBtn();
                        var fd = new FormData();
                        $.each(files, function (i, data) {
                            fd.append("uploads", data);
                        });
                        fd.append("USERNAME", USERNAME);
                        // fd.append('UUID',UUID)
                        fd.append('DATERELEASE',currentDate);
                        fd.append("CASHFLOWTYPE", $('#CASHFLOWTYPE_2').val());
                        $.ajax({
                            dataType: "JSON",
                            type: 'POST',
                            url: "<?php echo site_url('Upload/UploadPaymentReceive'); ?>",
                            data: fd,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $('#loader').removeClass('show');
                                if (response.status == 200) {
                                    STATUS = true;
                                    DtUpload = response.result.data;
                                    // console.log(response.result.data.error_message);
                                    tbl_upload.clear();
                                    tbl_upload.rows.add(DtUpload);
                                    tbl_upload.draw();
                                } else if (response.status == 504) {
                                    alert(response.result.data);
                                    location.reload();
                                } else {
                                    alert(response.result.data);
                                    files = '';
                                    $('.upload-file').val('');
                                    $(".panel-title_").text('Upload Document');
                                    DisableBtn();
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
                                DisableBtn();
                            }
                        });
                    }
                }
            }
        }
    }
    var SaveUpload = function () {
        if (STATUS == false) {
            alert('Data masih ada yang error !!!');
        } else if (DtUpload.length <= 0) {
            alert('Data yang di upload tidak ada !!!');
        } else {
            $('#loader').addClass('show');
            $('#btnSave').attr('disabled', true);
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Upload/saveUpPaymentReceive'); ?>",
                data: {
                    DATA: JSON.stringify(DtUpload),
                    FILENAME: FILENAME,
                    USERNAME: USERNAME
                },
                success: function (response) {
                    $('#loader').removeClass('show');
                    $('#btnSave').removeAttr('disabled');
                    if (response.status == 200) {
                        alert(response.result.data);
                        files = '';
                        $('.upload-file').val('');
                        DtUpload = [];
                        tbl_upload.clear();
                        tbl_upload.rows.add(DtUpload);
                        tbl_upload.draw();
                        STATUS = true;
                        DisableBtn();
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        location.reload();
                    } else {
                        alert(response.result.data);
                    }
                },
                error: function (e) {
                    $('#loader').removeClass('show');
                    console.info(e);
                    alert('Data Save Failed !!');
                    $('#btnSave').removeAttr('disabled');
                }
            });
        }
    };
    var ClearData = function () {
        STATUS = true;
        files = '';
        $('.upload-file').val('');
        $(".panel-title").text('Upload Document');
        DtUpload = [];
        tbl_upload.clear();
        tbl_upload.rows.add(DtUpload);
        tbl_upload.draw();
        DisableBtn();
    };
    DisableBtn();
</script>