<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link href="https://cdn.datatables.net/rowgroup/1.1.1/css/rowGroup.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/rowgroup/1.1.1/js/dataTables.rowGroup.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.3.3/css/searchBuilder.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.1.2/css/dataTables.dateTime.min.css">
<script src="https://cdn.datatables.net/searchbuilder/1.3.3/js/dataTables.searchBuilder.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js"></script>
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

    #dtf {
        height: 582px;
        overflow: auto;
    }

    #dtf th {
        border: 2px solid #d3d3d3;
        background-color: #fff;
    }

    #DtForecast_filter {
        display: none;
    }

    #DtForecast_wrapper {
        position: relative;
        clear: both;
        width: auto;
        max-height: 582px;
        margin-left: 0px;
        /*        border-bottom: 1px solid black; .dataTables_wrapper
                border-top: 1px solid black;
                border-left: 1px solid black;
                border-right: 1px solid black;
                background-color: #9D9C9D; */
        /*zoom: 1;*/
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
    <li class="breadcrumb-item active">Forecast</li>
</ol>
<h1 class="page-header">Forecast</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Forecast</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="form-row">
                    <div class="col my-auto" style="width: 150px">
                        <?php if ($DtUser2->USERACCESS != '100003') { ?>
                            <button id="btnAdd" type="button" class="btn btn-primary btn-sm mb-2" onclick="Add()"><i class="fa fa-plus"></i><span> Forecast</span></button>
                            <button id="btnAddAll" type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i><span> Forecast All</span></button></br></br>
                            <!-- <button id="btnAddNoInv" type="button" class="btn btn-primary btn-sm" onclick="AddNoInv()"><i class="fa fa-plus"></i><span> Forecast Without Invoice</span></button> -->
                            <button id="btnEdit" type="button" class="btn btn-info btn-sm mb-2"><i class="fa fa-edit"></i><span> Edit Forecast</span></button>
                        <?php } ?>
                        <button id="btnExport" type="button" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i><span> Export</span></button>
                    </div>
                    <div class="col">
                        <label for="DEPARTMENT">Departement</label>
                        <select class="form-control" name="DEPARTMENT" id="DEPARTMENT">
                            <option value="" selected>All Department</option>
                            <?php echo $CDepartment; ?>
                        </select>
                    </div>
                    
                        
                            <div class="col">
                                <label for="CASHFLOWTYPE">Type</label>
                                <select class="form-control" name="CASHFLOWTYPE" id="CASHFLOWTYPE">
                                    <option value="" selected>All</option>
                                    <option value="0">Cash In</option>
                                    <option value="1">Cash Out</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="COMPANYGROUP">Group</label>
                                <select class="form-control mkreadonly" name="COMPANYGROUP" id="COMPANYGROUP">
                                    <option value="">All</option>
                                    <option value="0">Null</option>
                                    <option value="CMT">CEMENT</option><option value="MOTIVE">MOTIVE</option><option value="PLT">PLANTATION</option><option value="PROPERTY">PROPERTY</option><option value="WOOD">WOOD</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="SUBGROUP">Subgroup</label>
                                <select class="form-control" name="COMPANYSUBGROUP" id="COMPANYSUBGROUP">
                                    <option value="" selected="">All</option>
                                    <option value="0">Null</option>
                                    <option value="UPSTREAM">UPSTREAM</option>
                                    <option value="DOWNSTREAM">DOWNSTREAM</option></select>
                            </div>
                            <div class="col">
                                <label for="PERIOD">Period</label>
                                <input type="text" class="form-control" name="PERIOD" id="PERIOD" placeholder="MMM YYYY" autocomplete="off">
                            </div>
                            <div class="col">
                                <label for="search">Search</label>
                                <input type="text" class="form-control col-md-10" name="searchBox" id="searchBox">
                            </div>
                        
                    

                </div>
            </div>
        </div>
        <div class="row m-0 table-responsive" id="dtf">
            <table id="DtForecast" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                <thead>
                    <tr role="row">
                        <th class="text-center align-middle" rowspan="3">Position</th>
                        <th class="text-center align-middle" aria-sort="ascending" style="width: 30px;" rowspan="3">No</th>
                        <th class="text-center align-middle" rowspan="3">Company</th>
                        <th class="text-center align-middle" rowspan="3">Business Unit</th>
                        <th class="text-center align-middle" rowspan="3">Department</th>
                        <th class="text-center align-middle" rowspan="3">Doc Number</th>
                        <th class="text-center align-middle" rowspan="3">Vendor</th>
                        <th class="text-center align-middle" rowspan="3">Doc Invoice</th>
                        <th class="text-center align-middle" rowspan="3">Invoice Vendor No</th>
                        <th class="text-center align-middle" rowspan="3">Due Date</th>
                        <th class="text-center align-middle" rowspan="3">Currency</th>
                        <th class="text-center align-middle" rowspan="3">Amount Source</th>
                        <th class="text-center align-middle" rowspan="3">Amount Outstanding</th>
                        <th class="text-center" colspan="15">Week</th>
                    </tr>
                    <tr>
                        <th id="diBorder" class="text-center W1" colspan="3">W1</th>
                        <th id="diBorder" class="text-center W2" colspan="3">W2</th>
                        <th id="diBorder" class="text-center W3" colspan="3">W3</th>
                        <th id="diBorder" class="text-center W4" colspan="3">W4</th>
                        <th id="diBorder" class="text-center W5" colspan="3">W5</th>
                    </tr>
                    <tr>
                        <th id="diBorder" class="text-center">Request</th>
                        <th id="diBorder" class="text-center">Adjusted</th>
                        <th id="diBorder" class="text-center">Priority</th>
                        <th id="diBorder" class="text-center">Request</th>
                        <th id="diBorder" class="text-center">Adjusted</th>
                        <th id="diBorder" class="text-center">Priority</th>
                        <th id="diBorder" class="text-center">Request</th>
                        <th id="diBorder" class="text-center">Adjusted</th>
                        <th id="diBorder" class="text-center">Priority</th>
                        <th id="diBorder" class="text-center">Request</th>
                        <th id="diBorder" class="text-center">Adjusted</th>
                        <th id="diBorder" class="text-center">Priority</th>
                        <th id="diBorder" class="text-center">Request</th>
                        <th id="diBorder" class="text-center">Adjusted</th>
                        <th id="diBorder" class="text-center">Priority</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr role="row">
                        <th class="text-right align-middle" colspan="12" rowspan="2">Total Cash </th>
                        <th class="text-right">In :</th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                    </tr>
                    <tr>
                        <th class="text-right">Out :</th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="col-lg-8">
            <table class="table table-responsive table-valign-middle">
                <thead>
                    <tr role="row">
                        <th class="text-center" colspan="4">User Define</th>
                        <th></th>
                        <th class="text-center" colspan="4">Finance Control</th>
                    </tr>
                </thead>
                <tbody>
                    <tr role="row">
                        <td class="bg-warning text-white align-middle" rowspan="2">Requested</td>
                        <td class="bg-warning text-white">In</td>
                        <td class="bg-warning text-white text-center">:</td>
                        <td class="bg-warning text-white text-right" id="ReqIn">0.00</td>
                        <td> </td>
                        <td class="bg-info text-white align-middle" rowspan="2">Revision</td>
                        <td class="bg-info text-white">In</td>
                        <td class="bg-info text-white text-center">:</td>
                        <td class="bg-info text-white text-right" id="RevIn">0.00</td>
                    </tr>
                    <tr>
                        <td class="bg-warning text-white">Out</td>
                        <td class="bg-warning text-white text-center">:</td>
                        <td class="bg-warning text-white text-right" id="ReqOut">0.00</td>
                        <td> </td>
                        <td class="bg-info text-white">Out</td>
                        <td class="bg-info text-white text-center">:</td>
                        <td class="bg-info text-white text-right" id="RevOut">0.00</td>
                    </tr>
                    <tr role="row">
                        <td class="bg-danger text-white align-middle" rowspan="2">Adjusted</td>
                        <td class="bg-danger text-white">In</td>
                        <td class="bg-danger text-white text-center">:</td>
                        <td class="bg-danger text-white text-right" id="AdjIn">0.00</td>
                        <td> </td>
                        <td class="bg-success text-white align-middle" rowspan="2">Approved</td>
                        <td class="bg-success text-white">In</td>
                        <td class="bg-success text-white text-center">:</td>
                        <td class="bg-success text-white text-right" id="AppIn">0.00</td>
                    </tr>
                    <tr>
                        <td class="bg-danger text-white">Out</td>
                        <td class="bg-danger text-white text-center">:</td>
                        <td class="bg-danger text-white text-right" id="AdjOut">0.00</td>
                        <td> </td>
                        <td class="bg-success text-white">Out</td>
                        <td class="bg-success text-white text-center">:</td>
                        <td class="bg-success text-white text-right" id="AppOut">0.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel-footer text-left">
        <?php if ($DtUser2->USERACCESS != '100003') { ?>
            <button type="button" id="btnSave" onclick="Save(0)" class="btn btn-primary btn-sm m-l-5">Save</button>
            <button type="button" id="btnSubmit" onclick="Submit()" class="btn btn-info btn-sm m-l-5">Save & Submit to Finance</button>
            <button type="button" id="btnCancel" class="btn btn-danger btn-sm m-l-5">Cancel</button>
        <?php } ?>
        <?php if ($DtUser2->USERACCESS == '100005' || $DtUser2->USERACCESS == '100003') { ?>
            <button type="button" id="RevAppDana" onclick="RevAppDana()" class="btn btn-info btn-sm m-l-5">Fund Revision or Approved</button>
        <?php } ?>
    </div>
</div>
<!--model forecast outstanding-->
<div class="modal fade" id="MOutstanding">
    <div class="modal-dialog" style="max-width: 95%  !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Data Outstanding</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <button id="btnAddNoInv" type="button" class="btn btn-primary btn-sm" onclick="AddNoInv()"><i class="fa fa-plus"></i><span> Forecast Without Invoice</span></button><br /><br />
                <div class="table-responsive">
                    <table id="DtOutstanding" Document class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUploadDetail_info">
                        <thead>
                            <tr>
                                <th class="text-center"><input type="checkbox" id="pil"></th>
                                <th class="text-center">No</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Business Unit</th>
                                <th class="text-center">Doc Type</th>
                                <th class="text-center">Doc Number</th>
                                <th class="text-center">Vendor</th>
                                <th class="text-center">Due Date</th>
                                <th class="text-center">Doc Invoice</th>
                                <th class="text-center">Invoice Vendor No</th>
                                <th class="text-center">Currency</th>
                                <th class="text-center">Amount Source</th>
                                <th class="text-center">Amount Outstanding</th>
                                <th class="text-center">Category</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div id="overlay" style="display: none;">
                    <span class="spinner"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
</div>
<!--model forecast outstanding without invoice-->
<div class="modal fade" id="MOutstandingNoInv">
    <div class="modal-dialog" style="max-width: 95%  !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Data Outstanding Without Invoice</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="DtOutstandingNoInv" Document class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUploadDetail_info">
                        <thead>
                            <tr>
                                <th class="text-center"><input type="checkbox" id="pil"></th>
                                <th class="text-center">No</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Business Unit</th>
                                <th class="text-center">Doc Type</th>
                                <th class="text-center">Doc Number</th>
                                <th class="text-center">Vendor</th>
                                <th class="text-center">Due Date</th>
                                <th class="text-center">Doc Invoice</th>
                                <th class="text-center">Currency</th>
                                <th class="text-center">Amount</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
</div>
<!--model forecast edit nominal-->
<div class="modal fade" id="MAmount">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Amount </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="FEditAmount" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="AMOUNTOUTSTANDING">Amount Outstanding</label>
                            <input type="text" class="form-control text-right" name="AMOUNTOUTSTANDING" id="AMOUNTOUTSTANDING" placeholder="Amount Outstanding" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="AMOUNTUSE">Amount Use</label>
                            <input type="text" class="form-control text-right" name="AMOUNTUSE" id="AMOUNTUSE" placeholder="Amount Use" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="AMOUNTWEEK">Amount *</label>
                            <input type="text" class="form-control text-right" name="AMOUNTWEEK" id="AMOUNTWEEK" data-type='currency' placeholder="Amount Week" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="EditAmount()">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--model Revisi-->
<div class="modal fade" id="MRevAppDana">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Fund Revision or Approved Forecast</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="MRevAppDana" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="DtRevAppDana" Document class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUploadDetail_info">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle" rowspan="2">Position</th>
                                    <th class="text-center align-middle" rowspan="2">No</th>
                                    <th class="text-center align-middle" rowspan="2">Department</th>
                                    <th class="text-center" colspan="4">Amount</th>
                                    <th class="text-center" rowspan="2">Action</th>
                                    <th class="text-center" rowspan="2">Status</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Request</th>
                                    <th class="text-center">Adjusted</th>
                                    <th class="text-center">Revision</th>
                                    <th class="text-center">Approved</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="SaveRevApp()">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="MSRev">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Set Action </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="FSRev" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="modal-body">
                    <div class="row">
                        <label for="SREVAPP">Action</label>
                        <select class="form-control" name="SREVAPP" id="SREVAPP">
                            <option value=""></option>
                            <option value="1">Revision</option>
                            <option value="2">Approved</option>
                        </select>
                    </div>
                    <div class="row">
                        <label for="AMOUNTREVISION">Amount Revision or Approve *</label>
                        <input type="text" class="form-control text-right" name="AMOUNTREVISION" id="AMOUNTREVISION" data-type='currency' placeholder="Amount Revision or Approve">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="EditSRev()">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="MRevisi">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Amount Revision</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="FRevisi" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="AMOUNTREVISI">Amount *</label>
                            <input type="text" class="form-control text-right" name="AMOUNTREVISI" id="AMOUNTREVISI" data-type='currency' placeholder="Amount Revision" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="SaveRevisi()">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--model Priority-->
<div class="modal fade" id="MPriority">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Priority Payment </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="FPriority" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="modal-body">
                    <div class="row">
                        <select class="form-control" name="PRIORITYWEEK" id="PRIORITYWEEK">
                            <option value=""></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="EditPriority()">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--model Export-->
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
                                <label for="EDEPARTMENT">Department</label>
                                <select class="form-control" id="EDEPARTMENT" name="EDEPARTMENT">
                                    <option value="" selected>All Department</option>
                                    <?php echo $CDepartment; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ECASHFLOWTYPE">Forecast Type</label>
                                <select class="form-control" name="ECASHFLOWTYPE" id="ECASHFLOWTYPE">
                                    <option value="" selected>All</option>
                                    <option value="0">Cash In</option>
                                    <option value="1">Cash Out</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                                <label for="COMPANYGROUP">Group</label>
                                <select class="form-control mkreadonly" name="ECOMPANYGROUP" id="ECOMPANYGROUP">
                                    <option value="">All</option>
                                    <option value="CMT">CEMENT</option><option value="MOTIVE">MOTIVE</option><option value="PLT">PLANTATION</option><option value="PROPERTY">PROPERTY</option><option value="WOOD">WOOD</option>
                                </select>
                        </div>
                        <div class="col-md-12">
                                <label for="SUBGROUP">Subgroup</label>
                                <select class="form-control" name="ECOMPANYSUBGROUP" id="ECOMPANYSUBGROUP">
                                    <option value="" selected="">All</option>
                                    <option value="UPSTREAM">UPSTREAM</option>
                                    <option value="DOWNSTREAM">DOWNSTREAM</option></select>
                            </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="EPERIOD">Period</label>
                                <input type="text" class="form-control" name="EPERIOD" id="EPERIOD" placeholder="MMM YYYY" required>
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
<script type="text/javascript">
    let statusCard = document.querySelector('#dtf');
    // add scroll event listener for change head's position 
    statusCard.addEventListener('scroll', e => {
        let tableHead = document.querySelector('thead');
        let scrollTop = statusCard.scrollTop;
        tableHead.style.transform = 'translateY(' + scrollTop + 'px)';
    })

    $(document).on("keyup", "#searchBox", function() {
        var tables = $("#DtForecast").DataTable({
            retrieve: true,
            paging: false,
            dom: "t"
        });
        tables.search($(this).val()).draw();
        $('#dtf').animate({
            scrollTop: 0
        }, 1000);
        // var toPos = $("#dtf").position().top;
        //   $("#dtf").scrollTop(toPos);
    });
</script>
<script>
    //    window.addEventListener('beforeunload', (event) => {
    //        // Cancel the event as stated by the standard.
    //        event.preventDefault();
    //        // Chrome requires returnValue to be set.
    //        event.returnValue = 'aa';
    //    });
    //    window.onbeforeunload = function (e) {
    //        e = e || window.event;
    //        console.info(e);
    //
    //        // For IE and Firefox prior to version 4
    ////        if (e) {
    ////            e.returnValue = 'Sure?';
    ////        }
    //
    //        // For Safari
    //        return 'Sure?';
    //    };
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var USERACCESS = "<?php echo $DtUser2->USERACCESS; ?>";
    var DEPT = "<?php echo $SESSION->DEPARTMENT; ?>";
    var YEAR = "",
        MONTH = "",
        timeOutonKeyup = null;
    var PSTATUS = '0',
        LOCKS = '0',
        POUTSTAND = "",
        AMOUNTUSE = 0,
        idx, WAMOUNT, LAMOUNT, WPRIORITY;
    var table, table2, table3;
    var DtForecast = [],
        DtRevApp = [];
    <?php if ($DtUser2->USERACCESS == '100005') { ?>
        var Disable = "hidden";
    <?php } else { ?>
        var Disable = "disabled";
    <?php } ?>
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var DtPriority = [{
            "DETAILCODE": "1",
            "DETAILNAME": "1"
        },
        {
            "DETAILCODE": "2",
            "DETAILNAME": "2"
        },
        {
            "DETAILCODE": "3",
            "DETAILNAME": "3"
        },
        {
            "DETAILCODE": "4",
            "DETAILNAME": "4"
        },
        {
            "DETAILCODE": "5",
            "DETAILNAME": "5"
        }
    ];
    var CashInIdx = 0,
        CashOutIdx = 0;
    var REQUESTW1, REQUESTW2, REQUESTW3, REQUESTW4, REQUESTW5, REQUESTIN = 0,
        REQUESTOUT = 0;
    var REQUESTINW1, REQUESTINW2, REQUESTINW3, REQUESTINW4, REQUESTINW5, REQUESTINTOT;
    var REQUESTOUTW1, REQUESTOUTW2, REQUESTOUTW3, REQUESTOUTW4, REQUESTOUTW5, REQUESTOUTTOT;
    var ADJSW1, ADJSW2, ADJSW3, ADJSW4, ADJSW5, ADJSIN = 0,
        ADJSOUT = 0;
    var ADJSINW1, ADJSINW2, ADJSINW3, ADJSINW4, ADJSINW5, ADJSINTOT;
    var ADJSOUTW1, ADJSOUTW2, ADJSOUTW3, ADJSOUTW4, ADJSOUTW5, ADJSOUTTOT;
    var REVISIONIN, REVISIONOUT, APPROVEDIN, APPROVEDOUT;
    var FCLOCK = "",
        FCSTATUS = "";
    var LOCKIN = 0,
        LOCKOUT = 0,
        STATUSIN = 0,
        STATUSOUT = 0,
        ISEDIT = 0,
        KEYSAVE = "";
    //    Load Datatable Awal JSON.stringify(DtForecast)
    if (!$.fn.DataTable.isDataTable('#DtForecast')) {
        $('#DtForecast').DataTable({
            "aaData": DtForecast,
            "preDrawCallback": function(settings) {
                pageScrollPos = $('div.dataTables_scrollBody').scrollTop();
            },
            "drawCallback": function(settings) {
                $('div.dataTables_scrollBody').scrollTop(pageScrollPos);
            },
            "columns": [{
                    "data": "CASHFLOWTYPE"
                },
                {
                    "data": null,
                    "className": "text-center",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        if (row.CASHFLOWTYPE == "0") {
                            CashInIdx++;
                            return CashInIdx;
                        } else {
                            CashOutIdx++;
                            return CashOutIdx;
                        }
                        //                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "COMPANYCODE"
                },
                {
                    "data": "BUSINESSUNITCODE"
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
                    "data": "AMOUNT_INCLUDE_VAT",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "AMOUNTOUTSTANDING",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "REQUESTW1",
                    "orderable": false,
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '0' && FCLOCK == '0' && USERACCESS != '100003' && ISEDIT == '1') {
                            html = '<a href="javascript:void(0);" class="REQUESTW1" role="button" title="Request W1">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "ADJSW1",
                    "orderable": false,
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '1' && FCLOCK == '0' && USERACCESS != '100003' && ISEDIT == '1') {
                            html = '<a href="javascript:void(0);" class="ADJSW1" role="button" title="Adjusted W1">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "PRIORITYW1",
                    //                    "orderable": false,
                    render: function(data, type, row, meta) {
                        var html = data;
                        if (data == null || data == '' || data == undefined) {
                            data = 'Klik';
                        }
                        if (FCLOCK == '0' && USERACCESS != '100003' && ISEDIT == '1') {
                            html = '<a href="javascript:void(0);" class="PRIORITYW1" role="button" title="Priority W1">' + data + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "REQUESTW2",
                    "orderable": false,
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '0' && FCLOCK == '0' && USERACCESS != '100003' && ISEDIT == '1') {
                            html = '<a href="javascript:void(0);" class="REQUESTW2" role="button" title="Request W2">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "ADJSW2",
                    "orderable": false,
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '1' && FCLOCK == '0' && USERACCESS != '100003' && ISEDIT == '1') {
                            html = '<a href="javascript:void(0);" class="ADJSW2" role="button" title="Adjusted W2">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "PRIORITYW2",
                    //                    "orderable": false,
                    render: function(data, type, row, meta) {
                        var html = data;
                        if (data == null || data == '' || data == undefined) {
                            data = 'Klik';
                        }
                        if (FCLOCK == '0' && USERACCESS != '100003' && ISEDIT == '1') {
                            html = '<a href="javascript:void(0);" class="PRIORITYW2" role="button" title="Priority W2">' + data + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "REQUESTW3",
                    "orderable": false,
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '0' && FCLOCK == '0' && USERACCESS != '100003' && ISEDIT == '1') {
                            html = '<a href="javascript:void(0);" class="REQUESTW3" role="button" title="Request W3">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "ADJSW3",
                    "orderable": false,
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '1' && FCLOCK == '0' && USERACCESS != '100003' && ISEDIT == '1') {
                            html = '<a href="javascript:void(0);" class="ADJSW3" role="button" title="Adjusted W3">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "PRIORITYW3",
                    //                    "orderable": false,
                    render: function(data, type, row, meta) {
                        var html = data;
                        if (data == null || data == '' || data == undefined) {
                            data = 'Klik';
                        }
                        if (FCLOCK == '0' && USERACCESS != '100003' && ISEDIT == '1') {
                            html = '<a href="javascript:void(0);" class="PRIORITYW3" role="button" title="Priority W3">' + data + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "REQUESTW4",
                    "orderable": false,
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '0' && FCLOCK == '0' && USERACCESS != '100003' && ISEDIT == '1') {
                            html = '<a href="javascript:void(0);" class="REQUESTW4" role="button" title="Request W4">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "ADJSW4",
                    "orderable": false,
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '1' && FCLOCK == '0' && USERACCESS != '100003' && ISEDIT == '1') {
                            html = '<a href="javascript:void(0);" class="ADJSW4" role="button" title="Adjusted W4">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "PRIORITYW4",
                    //                    "orderable": false,
                    render: function(data, type, row, meta) {
                        var html = data;
                        if (data == null || data == '' || data == undefined) {
                            data = 'Klik';
                        }
                        if (FCLOCK == '0' && USERACCESS != '100003' && ISEDIT == '1') {
                            html = '<a href="javascript:void(0);" class="PRIORITYW4" role="button" title="Priority W4">' + data + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "REQUESTW5",
                    "orderable": false,
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '0' && FCLOCK == '0' && USERACCESS != '100003' && ISEDIT == '1') {
                            html = '<a href="javascript:void(0);" class="REQUESTW5" role="button" title="Request W5">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "ADJSW5",
                    "orderable": false,
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '1' && FCLOCK == '0' && USERACCESS != '100003' && ISEDIT == '1') {
                            html = '<a href="javascript:void(0);" class="ADJSW5" role="button" title="Adjusted W5">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "PRIORITYW5",
                    //                    "orderable": false,
                    render: function(data, type, row, meta) {
                        var html = data;
                        if (data == null || data == '' || data == undefined) {
                            data = 'Klik';
                        }
                        if (FCLOCK == '0' && USERACCESS != '100003' && ISEDIT == '1') {
                            html = '<a href="javascript:void(0);" class="PRIORITYW5" role="button" title="Priority W5">' + data + '</a>';
                        }
                        return html;
                    }
                }
            ],
            "responsive": false,
            "bFilter": true,
            "bPaginate": false,
            "bLengthChange": false,
            "bInfo": false,
            "columnDefs": [{
                "visible": false,
                "targets": 0
            }],
            orderFixed: [0, 'asc'],
            "rowGroup": {
                startRender: function(rows, group) {
                    var html = "";
                    if (group == '0') {
                        html = "Cash In";
                    } else {
                        html = "Cash Out";
                    }
                    return html;
                },
                endRender: function(rows, group) {
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };
                    REQUESTW1 = 0;
                    REQUESTW2 = 0;
                    REQUESTW3 = 0;
                    REQUESTW4 = 0;
                    REQUESTW5 = 0;
                    ADJSW1 = 0;
                    ADJSW2 = 0;
                    ADJSW3 = 0;
                    ADJSW4 = 0;
                    ADJSW5 = 0;
                    $.each(rows.data(), function(index, value) {
                        REQUESTW1 = REQUESTW1 + (intVal(value["REQUESTW1"]) * intVal(value["RATE"]));
                        REQUESTW2 = REQUESTW2 + (intVal(value["REQUESTW2"]) * intVal(value["RATE"]));
                        REQUESTW3 = REQUESTW3 + (intVal(value["REQUESTW3"]) * intVal(value["RATE"]));
                        REQUESTW4 = REQUESTW4 + (intVal(value["REQUESTW4"]) * intVal(value["RATE"]));
                        REQUESTW5 = REQUESTW5 + (intVal(value["REQUESTW5"]) * intVal(value["RATE"]));
                        ADJSW1 = ADJSW1 + (intVal(value["ADJSW1"]) * intVal(value["RATE"]));
                        ADJSW2 = ADJSW2 + (intVal(value["ADJSW2"]) * intVal(value["RATE"]));
                        ADJSW3 = ADJSW3 + (intVal(value["ADJSW3"]) * intVal(value["RATE"]));
                        ADJSW4 = ADJSW4 + (intVal(value["ADJSW4"]) * intVal(value["RATE"]));
                        ADJSW5 = ADJSW5 + (intVal(value["ADJSW5"]) * intVal(value["RATE"]));
                    });
                    REQUESTW1 = rows.data().pluck('REQUESTW1').reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    REQUESTW2 = rows.data().pluck('REQUESTW2').reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    REQUESTW3 = rows.data().pluck('REQUESTW3').reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    REQUESTW4 = rows.data().pluck('REQUESTW4').reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    REQUESTW5 = rows.data().pluck('REQUESTW5').reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    ADJSW1 = rows.data().pluck('ADJSW1').reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    ADJSW2 = rows.data().pluck('ADJSW2').reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    ADJSW3 = rows.data().pluck('ADJSW3').reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    ADJSW4 = rows.data().pluck('ADJSW4').reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    ADJSW5 = rows.data().pluck('ADJSW5').reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;

                    var label = "";
                    if (group == '0') {
                        label = "Cash In";
                    } else {
                        label = "Cash Out";
                    }
                    return $('<tr/>')
                        .append('<td colspan="11">Total ' + label + '</td>')
                        .append('<td></td>')
                        .append('<td class="text-right reqw1">' + numFormat(REQUESTW1) + '</td>')
                        .append('<td class="text-right adjw1">' + numFormat(ADJSW1) + '</td>')
                        .append('<td></td>')
                        .append('<td class="text-right reqw2">' + numFormat(REQUESTW2) + '</td>')
                        .append('<td class="text-right adjw2">' + numFormat(ADJSW2) + '</td>')
                        .append('<td></td>')
                        .append('<td class="text-right reqw3">' + numFormat(REQUESTW3) + '</td>')
                        .append('<td class="text-right adjw3">' + numFormat(ADJSW3) + '</td>')
                        .append('<td></td>')
                        .append('<td class="text-right reqw4">' + numFormat(REQUESTW4) + '</td>')
                        .append('<td class="text-right adjw4">' + numFormat(ADJSW4) + '</td>')
                        .append('<td></td>')
                        .append('<td class="text-right reqw5">' + numFormat(REQUESTW5) + '</td>')
                        .append('<td class="text-right adjw5">' + numFormat(ADJSW5) + '</td>')
                        .append('<td></td>');
                },
                dataSrc: "CASHFLOWTYPE"
            },
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
                REQUESTINW1 = 0, REQUESTINW2 = 0, REQUESTINW3 = 0, REQUESTINW4 = 0, REQUESTINW5 = 0, REQUESTINTOT = 0;
                REQUESTOUTW1 = 0, REQUESTOUTW2 = 0, REQUESTOUTW3 = 0, REQUESTOUTW4 = 0, REQUESTOUTW5 = 0, REQUESTOUTTOT = 0;
                ADJSINW1 = 0, ADJSINW2 = 0, ADJSINW3 = 0, ADJSINW4 = 0, ADJSINW5 = 0, ADJSINTOT = 0;
                ADJSOUTW1 = 0, ADJSOUTW2 = 0, ADJSOUTW3 = 0, ADJSOUTW4 = 0, ADJSOUTW5 = 0, ADJSOUTTOT = 0;

                $.each(data, function(index, value) {
                    if (value['CASHFLOWTYPE'] == '0') {
                        REQUESTINW1 = REQUESTINW1 + (intVal(value['REQUESTW1']) * intVal(value["RATE"]));
                        REQUESTINW2 = REQUESTINW2 + (intVal(value['REQUESTW2']) * intVal(value["RATE"]));
                        REQUESTINW3 = REQUESTINW3 + (intVal(value['REQUESTW3']) * intVal(value["RATE"]));
                        REQUESTINW4 = REQUESTINW4 + (intVal(value['REQUESTW4']) * intVal(value["RATE"]));
                        REQUESTINW5 = REQUESTINW5 + (intVal(value['REQUESTW5']) * intVal(value["RATE"]));
                        ADJSINW1 = ADJSINW1 + (intVal(value['ADJSW1']) * intVal(value["RATE"]));
                        ADJSINW2 = ADJSINW2 + (intVal(value['ADJSW2']) * intVal(value["RATE"]));
                        ADJSINW3 = ADJSINW3 + (intVal(value['ADJSW3']) * intVal(value["RATE"]));
                        ADJSINW4 = ADJSINW4 + (intVal(value['ADJSW4']) * intVal(value["RATE"]));
                        ADJSINW5 = ADJSINW5 + (intVal(value['ADJSW5']) * intVal(value["RATE"]));
                    } else {
                        REQUESTOUTW1 = REQUESTOUTW1 + (intVal(value['REQUESTW1']) * intVal(value["RATE"]));
                        REQUESTOUTW2 = REQUESTOUTW2 + (intVal(value['REQUESTW2']) * intVal(value["RATE"]));
                        REQUESTOUTW3 = REQUESTOUTW3 + (intVal(value['REQUESTW3']) * intVal(value["RATE"]));
                        REQUESTOUTW4 = REQUESTOUTW4 + (intVal(value['REQUESTW4']) * intVal(value["RATE"]));
                        REQUESTOUTW5 = REQUESTOUTW5 + (intVal(value['REQUESTW5']) * intVal(value["RATE"]));
                        ADJSOUTW1 = ADJSOUTW1 + (intVal(value['ADJSW1']) * intVal(value["RATE"]));
                        ADJSOUTW2 = ADJSOUTW2 + (intVal(value['ADJSW2']) * intVal(value["RATE"]));
                        ADJSOUTW3 = ADJSOUTW3 + (intVal(value['ADJSW3']) * intVal(value["RATE"]));
                        ADJSOUTW4 = ADJSOUTW4 + (intVal(value['ADJSW4']) * intVal(value["RATE"]));
                        ADJSOUTW5 = ADJSOUTW5 + (intVal(value['ADJSW5']) * intVal(value["RATE"]));
                    }
                });
                REQUESTINTOT = REQUESTINW1 + REQUESTINW2 + REQUESTINW3 + REQUESTINW4 + REQUESTINW5;
                REQUESTOUTTOT = intVal(REQUESTOUTW1) + intVal(REQUESTOUTW2) + intVal(REQUESTOUTW3) + intVal(REQUESTOUTW4) + intVal(REQUESTOUTW5);
                ADJSINTOT = ADJSINW1 + ADJSINW2 + ADJSINW3 + ADJSINW4 + ADJSINW5;
                ADJSOUTTOT = ADJSOUTW1 + ADJSOUTW2 + ADJSOUTW3 + ADJSOUTW4 + ADJSOUTW5;
                var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                $("tr:eq(0) th:eq(2)", api.table().footer()).html(numFormat(REQUESTINW1));
                $("tr:eq(0) th:eq(3)", api.table().footer()).html(numFormat(ADJSINW1));
                $("tr:eq(0) th:eq(5)", api.table().footer()).html(numFormat(REQUESTINW2));
                $("tr:eq(0) th:eq(6)", api.table().footer()).html(numFormat(ADJSINW2));
                $("tr:eq(0) th:eq(8)", api.table().footer()).html(numFormat(REQUESTINW3));
                $("tr:eq(0) th:eq(9)", api.table().footer()).html(numFormat(ADJSINW3));
                $("tr:eq(0) th:eq(11)", api.table().footer()).html(numFormat(REQUESTINW4));
                $("tr:eq(0) th:eq(12)", api.table().footer()).html(numFormat(ADJSINW4));
                $("tr:eq(0) th:eq(14)", api.table().footer()).html(numFormat(REQUESTINW5));
                $("tr:eq(0) th:eq(15)", api.table().footer()).html(numFormat(ADJSINW5));
                $("tr:eq(1) th:eq(1)", api.table().footer()).html(numFormat(REQUESTOUTW1));
                $("tr:eq(1) th:eq(2)", api.table().footer()).html(numFormat(ADJSOUTW1));
                $("tr:eq(1) th:eq(4)", api.table().footer()).html(numFormat(REQUESTOUTW2));
                $("tr:eq(1) th:eq(5)", api.table().footer()).html(numFormat(ADJSOUTW2));
                $("tr:eq(1) th:eq(7)", api.table().footer()).html(numFormat(REQUESTOUTW3));
                $("tr:eq(1) th:eq(8)", api.table().footer()).html(numFormat(ADJSOUTW3));
                $("tr:eq(1) th:eq(10)", api.table().footer()).html(numFormat(REQUESTOUTW4));
                $("tr:eq(1) th:eq(11)", api.table().footer()).html(numFormat(ADJSOUTW4));
                $("tr:eq(1) th:eq(13)", api.table().footer()).html(numFormat(REQUESTOUTW5));
                $("tr:eq(1) th:eq(14)", api.table().footer()).html(numFormat(ADJSOUTW5));
                $("#ReqIn").text(numFormat(REQUESTINTOT));
                $("#ReqOut").text(numFormat(REQUESTOUTTOT));
                $("#AdjIn").text(numFormat(ADJSINTOT));
                $("#AdjOut").text(numFormat(ADJSOUTTOT));
            }
        });
        table = $('#DtForecast').DataTable();
        //Function di Datatable 
        table.on('click', ".REQUESTW1", function() {
            $tr = $(this).closest('tr');
            idx = table.row($tr).index();
            WAMOUNT = 'REQUESTW1';
            WPRIORITY = 'PRIORITYW1';
            LAMOUNT = 'Request W1';
            if (parseFloat(DtForecast[idx].REQUESTW1) > 0) {
                $('#FEditAmount').parsley().reset();
                var lbl = DtForecast[idx].DOCREF;
                if (DtForecast[idx].DOCNUMBER != null && DtForecast[idx].DOCNUMBER != undefined && DtForecast[idx].DOCNUMBER != '') {
                    lbl = lbl + " - " + DtForecast[idx].DOCNUMBER;
                }
                $('#MAmount .modal-title').text('Edit Amount ' + LAMOUNT + ' (' + lbl + ')');
                $('#AMOUNTOUTSTANDING').val(DtForecast[idx].AMOUNTOUTSTANDING);
                formatCurrency($('#AMOUNTOUTSTANDING'), "blur");
                AMOUNTUSE = parseFloat(DtForecast[idx].REQUESTW2) + parseFloat(DtForecast[idx].REQUESTW3) + parseFloat(DtForecast[idx].REQUESTW4) + parseFloat(DtForecast[idx].REQUESTW5);
                $('#AMOUNTUSE').val(AMOUNTUSE.toString());
                formatCurrency($('#AMOUNTUSE'), "blur");
                $('#AMOUNTWEEK').val(DtForecast[idx].REQUESTW1);
                formatCurrency($('#AMOUNTWEEK'), "blur");
                $('#MAmount label[for="AMOUNTWEEK"]').text('Amount ' + LAMOUNT + ' *');
                $('#MAmount').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                DtForecast[idx].REQUESTW1 = parseFloat(DtForecast[idx].AMOUNTOUTSTANDING) - (parseFloat(DtForecast[idx].REQUESTW2) + parseFloat(DtForecast[idx].REQUESTW3) + parseFloat(DtForecast[idx].REQUESTW4) + parseFloat(DtForecast[idx].REQUESTW5));
                DtForecast[idx].REQUESTW1 = DtForecast[idx].REQUESTW1.toString();
                ReloadTable();
            }
        });
        table.on('click', ".REQUESTW2", function() {
            $tr = $(this).closest('tr');
            idx = table.row($tr).index();
            WAMOUNT = 'REQUESTW2';
            WPRIORITY = 'PRIORITYW2';
            LAMOUNT = 'Request W2';
            if (parseFloat(DtForecast[idx].REQUESTW2) > 0) {
                $('#FEditAmount').parsley().reset();
                var lbl = DtForecast[idx].DOCREF;
                if (DtForecast[idx].DOCNUMBER != null && DtForecast[idx].DOCNUMBER != undefined && DtForecast[idx].DOCNUMBER != '') {
                    lbl = lbl + " - " + DtForecast[idx].DOCNUMBER;
                }
                $('#MAmount .modal-title').text('Edit Amount ' + LAMOUNT + ' (' + lbl + ')');
                $('#AMOUNTOUTSTANDING').val(DtForecast[idx].AMOUNTOUTSTANDING);
                formatCurrency($('#AMOUNTOUTSTANDING'), "blur");
                AMOUNTUSE = parseFloat(DtForecast[idx].REQUESTW1) + parseFloat(DtForecast[idx].REQUESTW3) + parseFloat(DtForecast[idx].REQUESTW4) + parseFloat(DtForecast[idx].REQUESTW5);
                $('#AMOUNTUSE').val(AMOUNTUSE.toString());
                formatCurrency($('#AMOUNTUSE'), "blur");
                $('#AMOUNTWEEK').val(DtForecast[idx].REQUESTW2);
                formatCurrency($('#AMOUNTWEEK'), "blur");
                $('#MAmount label[for="AMOUNTWEEK"]').text('Amount ' + LAMOUNT + ' *');
                $('#MAmount').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                DtForecast[idx].REQUESTW2 = parseFloat(DtForecast[idx].AMOUNTOUTSTANDING) - (parseFloat(DtForecast[idx].REQUESTW1) + parseFloat(DtForecast[idx].REQUESTW3) + parseFloat(DtForecast[idx].REQUESTW4) + parseFloat(DtForecast[idx].REQUESTW5));
                DtForecast[idx].REQUESTW2 = DtForecast[idx].REQUESTW2.toString();
                ReloadTable();
            }
        });
        table.on('click', ".REQUESTW3", function() {
            $tr = $(this).closest('tr');
            idx = table.row($tr).index();
            WAMOUNT = 'REQUESTW3';
            WPRIORITY = 'PRIORITYW3';
            LAMOUNT = 'Request W3';
            if (parseFloat(DtForecast[idx].REQUESTW3) > 0) {
                $('#FEditAmount').parsley().reset();
                var lbl = DtForecast[idx].DOCREF;
                if (DtForecast[idx].DOCNUMBER != null && DtForecast[idx].DOCNUMBER != undefined && DtForecast[idx].DOCNUMBER != '') {
                    lbl = lbl + " - " + DtForecast[idx].DOCNUMBER;
                }
                $('#MAmount .modal-title').text('Edit Amount ' + LAMOUNT + ' (' + lbl + ')');
                $('#AMOUNTOUTSTANDING').val(DtForecast[idx].AMOUNTOUTSTANDING);
                formatCurrency($('#AMOUNTOUTSTANDING'), "blur");
                AMOUNTUSE = parseFloat(DtForecast[idx].REQUESTW2) + parseFloat(DtForecast[idx].REQUESTW1) + parseFloat(DtForecast[idx].REQUESTW4) + parseFloat(DtForecast[idx].REQUESTW5);
                $('#AMOUNTUSE').val(AMOUNTUSE.toString());
                formatCurrency($('#AMOUNTUSE'), "blur");
                $('#AMOUNTWEEK').val(DtForecast[idx].REQUESTW3);
                formatCurrency($('#AMOUNTWEEK'), "blur");
                $('#MAmount label[for="AMOUNTWEEK"]').text('Amount ' + LAMOUNT + ' *');
                $('#MAmount').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                DtForecast[idx].REQUESTW3 = parseFloat(DtForecast[idx].AMOUNTOUTSTANDING) - (parseFloat(DtForecast[idx].REQUESTW2) + parseFloat(DtForecast[idx].REQUESTW1) + parseFloat(DtForecast[idx].REQUESTW4) + parseFloat(DtForecast[idx].REQUESTW5));
                DtForecast[idx].REQUESTW3 = DtForecast[idx].REQUESTW3.toString();
                ReloadTable();
            }
        });
        table.on('click', ".REQUESTW4", function() {
            $tr = $(this).closest('tr');
            idx = table.row($tr).index();
            WAMOUNT = 'REQUESTW4';
            WPRIORITY = 'PRIORITYW4';
            LAMOUNT = 'Request W4';
            if (parseFloat(DtForecast[idx].REQUESTW4) > 0) {
                $('#FEditAmount').parsley().reset();
                var lbl = DtForecast[idx].DOCREF;
                if (DtForecast[idx].DOCNUMBER != null && DtForecast[idx].DOCNUMBER != undefined && DtForecast[idx].DOCNUMBER != '') {
                    lbl = lbl + " - " + DtForecast[idx].DOCNUMBER;
                }
                $('#MAmount .modal-title').text('Edit Amount ' + LAMOUNT + ' (' + lbl + ')');
                $('#AMOUNTOUTSTANDING').val(DtForecast[idx].AMOUNTOUTSTANDING);
                formatCurrency($('#AMOUNTOUTSTANDING'), "blur");
                AMOUNTUSE = parseFloat(DtForecast[idx].REQUESTW2) + parseFloat(DtForecast[idx].REQUESTW3) + parseFloat(DtForecast[idx].REQUESTW1) + parseFloat(DtForecast[idx].REQUESTW5);
                $('#AMOUNTUSE').val(AMOUNTUSE.toString());
                formatCurrency($('#AMOUNTUSE'), "blur");
                $('#AMOUNTWEEK').val(DtForecast[idx].REQUESTW4);
                formatCurrency($('#AMOUNTWEEK'), "blur");
                $('#MAmount label[for="AMOUNTWEEK"]').text('Amount ' + LAMOUNT + ' *');
                $('#MAmount').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                DtForecast[idx].REQUESTW4 = parseFloat(DtForecast[idx].AMOUNTOUTSTANDING) - (parseFloat(DtForecast[idx].REQUESTW2) + parseFloat(DtForecast[idx].REQUESTW3) + parseFloat(DtForecast[idx].REQUESTW1) + parseFloat(DtForecast[idx].REQUESTW5));
                DtForecast[idx].REQUESTW4 = DtForecast[idx].REQUESTW4.toString();
                ReloadTable();
            }
        });
        table.on('click', ".REQUESTW5", function() {
            $tr = $(this).closest('tr');
            idx = table.row($tr).index();
            WAMOUNT = 'REQUESTW5';
            WPRIORITY = 'PRIORITYW5';
            LAMOUNT = 'Request W5';
            if (parseFloat(DtForecast[idx].REQUESTW5) > 0) {
                $('#FEditAmount').parsley().reset();
                var lbl = DtForecast[idx].DOCREF;
                if (DtForecast[idx].DOCNUMBER != null && DtForecast[idx].DOCNUMBER != undefined && DtForecast[idx].DOCNUMBER != '') {
                    lbl = lbl + " - " + DtForecast[idx].DOCNUMBER;
                }
                $('#MAmount .modal-title').text('Edit Amount ' + LAMOUNT + ' (' + lbl + ')');
                $('#AMOUNTOUTSTANDING').val(DtForecast[idx].AMOUNTOUTSTANDING);
                formatCurrency($('#AMOUNTOUTSTANDING'), "blur");
                AMOUNTUSE = parseFloat(DtForecast[idx].REQUESTW2) + parseFloat(DtForecast[idx].REQUESTW3) + parseFloat(DtForecast[idx].REQUESTW4) + parseFloat(DtForecast[idx].REQUESTW1);
                $('#AMOUNTUSE').val(AMOUNTUSE.toString());
                formatCurrency($('#AMOUNTUSE'), "blur");
                $('#AMOUNTWEEK').val(DtForecast[idx].REQUESTW5);
                formatCurrency($('#AMOUNTWEEK'), "blur");
                $('#MAmount label[for="AMOUNTWEEK"]').text('Amount ' + LAMOUNT + ' *');
                $('#MAmount').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                DtForecast[idx].REQUESTW5 = parseFloat(DtForecast[idx].AMOUNTOUTSTANDING) - (parseFloat(DtForecast[idx].REQUESTW2) + parseFloat(DtForecast[idx].REQUESTW3) + parseFloat(DtForecast[idx].REQUESTW4) + parseFloat(DtForecast[idx].REQUESTW1));
                DtForecast[idx].REQUESTW5 = DtForecast[idx].REQUESTW5.toString();
                ReloadTable();
            }
        });
        table.on('click', ".ADJSW1", function() {
            $tr = $(this).closest('tr');
            idx = table.row($tr).index();
            WAMOUNT = 'ADJSW1';
            WPRIORITY = 'PRIORITYW1';
            LAMOUNT = 'Adjusted W1';
            if (parseFloat(DtForecast[idx].ADJSW1) > 0) {
                $('#FEditAmount').parsley().reset();
                var lbl = DtForecast[idx].DOCREF;
                if (DtForecast[idx].DOCNUMBER != null && DtForecast[idx].DOCNUMBER != undefined && DtForecast[idx].DOCNUMBER != '') {
                    lbl = lbl + " - " + DtForecast[idx].DOCNUMBER;
                }
                $('#MAmount .modal-title').text('Edit Amount ' + LAMOUNT + ' (' + lbl + ')');
                $('#AMOUNTOUTSTANDING').val(DtForecast[idx].AMOUNTOUTSTANDING);
                formatCurrency($('#AMOUNTOUTSTANDING'), "blur");
                AMOUNTUSE = parseFloat(DtForecast[idx].ADJSW2) + parseFloat(DtForecast[idx].ADJSW3) + parseFloat(DtForecast[idx].ADJSW4) + parseFloat(DtForecast[idx].ADJSW5);
                $('#AMOUNTUSE').val(AMOUNTUSE.toString());
                formatCurrency($('#AMOUNTUSE'), "blur");
                $('#AMOUNTWEEK').val(DtForecast[idx].ADJSW1);
                formatCurrency($('#AMOUNTWEEK'), "blur");
                $('#MAmount label[for="AMOUNTWEEK"]').text('Amount ' + LAMOUNT + ' *');
                $('#MAmount').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                DtForecast[idx].ADJSW1 = parseFloat(DtForecast[idx].AMOUNTOUTSTANDING) - (parseFloat(DtForecast[idx].ADJSW2) + parseFloat(DtForecast[idx].ADJSW3) + parseFloat(DtForecast[idx].ADJSW4) + parseFloat(DtForecast[idx].ADJSW5));
                DtForecast[idx].ADJSW1 = DtForecast[idx].ADJSW1.toString();
                ReloadTable();
            }
        });
        table.on('click', ".ADJSW2", function() {
            $tr = $(this).closest('tr');
            idx = table.row($tr).index();
            WAMOUNT = 'ADJSW2';
            WPRIORITY = 'PRIORITYW2';
            LAMOUNT = 'Adjusted W2';
            if (parseFloat(DtForecast[idx].ADJSW2) > 0) {
                $('#FEditAmount').parsley().reset();
                var lbl = DtForecast[idx].DOCREF;
                if (DtForecast[idx].DOCNUMBER != null && DtForecast[idx].DOCNUMBER != undefined && DtForecast[idx].DOCNUMBER != '') {
                    lbl = lbl + " - " + DtForecast[idx].DOCNUMBER;
                }
                $('#MAmount .modal-title').text('Edit Amount ' + LAMOUNT + ' (' + lbl + ')');
                $('#AMOUNTOUTSTANDING').val(DtForecast[idx].AMOUNTOUTSTANDING);
                formatCurrency($('#AMOUNTOUTSTANDING'), "blur");
                AMOUNTUSE = parseFloat(DtForecast[idx].ADJSW1) + parseFloat(DtForecast[idx].ADJSW3) + parseFloat(DtForecast[idx].ADJSW4) + parseFloat(DtForecast[idx].ADJSW5);
                $('#AMOUNTUSE').val(AMOUNTUSE.toString());
                formatCurrency($('#AMOUNTUSE'), "blur");
                $('#AMOUNTWEEK').val(DtForecast[idx].ADJSW2);
                formatCurrency($('#AMOUNTWEEK'), "blur");
                $('#MAmount label[for="AMOUNTWEEK"]').text('Amount ' + LAMOUNT + ' *');
                $('#MAmount').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                DtForecast[idx].ADJSW2 = parseFloat(DtForecast[idx].AMOUNTOUTSTANDING) - (parseFloat(DtForecast[idx].ADJSW1) + parseFloat(DtForecast[idx].ADJSW3) + parseFloat(DtForecast[idx].ADJSW4) + parseFloat(DtForecast[idx].ADJSW5));
                DtForecast[idx].ADJSW2 = DtForecast[idx].ADJSW2.toString();
                ReloadTable();
            }
        });
        table.on('click', ".ADJSW3", function() {
            $tr = $(this).closest('tr');
            idx = table.row($tr).index();
            WAMOUNT = 'ADJSW3';
            WPRIORITY = 'PRIORITYW3';
            LAMOUNT = 'Adjusted W3';
            if (parseFloat(DtForecast[idx].ADJSW3) > 0) {
                $('#FEditAmount').parsley().reset();
                var lbl = DtForecast[idx].DOCREF;
                if (DtForecast[idx].DOCNUMBER != null && DtForecast[idx].DOCNUMBER != undefined && DtForecast[idx].DOCNUMBER != '') {
                    lbl = lbl + " - " + DtForecast[idx].DOCNUMBER;
                }
                $('#MAmount .modal-title').text('Edit Amount ' + LAMOUNT + ' (' + lbl + ')');
                $('#AMOUNTOUTSTANDING').val(DtForecast[idx].AMOUNTOUTSTANDING);
                formatCurrency($('#AMOUNTOUTSTANDING'), "blur");
                AMOUNTUSE = parseFloat(DtForecast[idx].ADJSW2) + parseFloat(DtForecast[idx].ADJSW1) + parseFloat(DtForecast[idx].ADJSW4) + parseFloat(DtForecast[idx].ADJSW5);
                $('#AMOUNTUSE').val(AMOUNTUSE.toString());
                formatCurrency($('#AMOUNTUSE'), "blur");
                $('#AMOUNTWEEK').val(DtForecast[idx].ADJSW3);
                formatCurrency($('#AMOUNTWEEK'), "blur");
                $('#MAmount label[for="AMOUNTWEEK"]').text('Amount ' + LAMOUNT + ' *');
                $('#MAmount').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                DtForecast[idx].ADJSW3 = parseFloat(DtForecast[idx].AMOUNTOUTSTANDING) - (parseFloat(DtForecast[idx].ADJSW2) + parseFloat(DtForecast[idx].ADJSW1) + parseFloat(DtForecast[idx].ADJSW4) + parseFloat(DtForecast[idx].ADJSW5));
                DtForecast[idx].ADJSW3 = DtForecast[idx].ADJSW3.toString();
                ReloadTable();
            }
        });
        table.on('click', ".ADJSW4", function() {
            $tr = $(this).closest('tr');
            idx = table.row($tr).index();
            WAMOUNT = 'ADJSW4';
            WPRIORITY = 'PRIORITYW4';
            LAMOUNT = 'Adjusted W4';
            if (parseFloat(DtForecast[idx].ADJSW4) > 0) {
                $('#FEditAmount').parsley().reset();
                var lbl = DtForecast[idx].DOCREF;
                if (DtForecast[idx].DOCNUMBER != null && DtForecast[idx].DOCNUMBER != undefined && DtForecast[idx].DOCNUMBER != '') {
                    lbl = lbl + " - " + DtForecast[idx].DOCNUMBER;
                }
                $('#MAmount .modal-title').text('Edit Amount ' + LAMOUNT + ' (' + lbl + ')');
                $('#AMOUNTOUTSTANDING').val(DtForecast[idx].AMOUNTOUTSTANDING);
                formatCurrency($('#AMOUNTOUTSTANDING'), "blur");
                AMOUNTUSE = parseFloat(DtForecast[idx].ADJSW2) + parseFloat(DtForecast[idx].ADJSW3) + parseFloat(DtForecast[idx].ADJSW1) + parseFloat(DtForecast[idx].ADJSW5);
                $('#AMOUNTUSE').val(AMOUNTUSE.toString());
                formatCurrency($('#AMOUNTUSE'), "blur");
                $('#AMOUNTWEEK').val(DtForecast[idx].ADJSW4);
                formatCurrency($('#AMOUNTWEEK'), "blur");
                $('#MAmount label[for="AMOUNTWEEK"]').text('Amount ' + LAMOUNT + ' *');
                $('#MAmount').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                DtForecast[idx].ADJSW4 = parseFloat(DtForecast[idx].AMOUNTOUTSTANDING) - (parseFloat(DtForecast[idx].ADJSW2) + parseFloat(DtForecast[idx].ADJSW3) + parseFloat(DtForecast[idx].ADJSW1) + parseFloat(DtForecast[idx].ADJSW5));
                DtForecast[idx].ADJSW4 = DtForecast[idx].ADJSW4.toString();
                ReloadTable();
            }
        });
        table.on('click', ".ADJSW5", function() {
            $tr = $(this).closest('tr');
            idx = table.row($tr).index();
            WAMOUNT = 'ADJSW5';
            WPRIORITY = 'PRIORITYW5';
            LAMOUNT = 'Adjusted W5';
            if (parseFloat(DtForecast[idx].ADJSW5) > 0) {
                $('#FEditAmount').parsley().reset();
                var lbl = DtForecast[idx].DOCREF;
                if (DtForecast[idx].DOCNUMBER != null && DtForecast[idx].DOCNUMBER != undefined && DtForecast[idx].DOCNUMBER != '') {
                    lbl = lbl + " - " + DtForecast[idx].DOCNUMBER;
                }
                $('#MAmount .modal-title').text('Edit Amount ' + LAMOUNT + ' (' + lbl + ')');
                $('#AMOUNTOUTSTANDING').val(DtForecast[idx].AMOUNTOUTSTANDING);
                formatCurrency($('#AMOUNTOUTSTANDING'), "blur");
                AMOUNTUSE = parseFloat(DtForecast[idx].ADJSW2) + parseFloat(DtForecast[idx].ADJSW3) + parseFloat(DtForecast[idx].ADJSW4) + parseFloat(DtForecast[idx].ADJSW1);
                $('#AMOUNTUSE').val(AMOUNTUSE.toString());
                formatCurrency($('#AMOUNTUSE'), "blur");
                $('#AMOUNTWEEK').val(DtForecast[idx].ADJSW5);
                formatCurrency($('#AMOUNTWEEK'), "blur");
                $('#MAmount label[for="AMOUNTWEEK"]').text('Amount ' + LAMOUNT + ' *');
                $('#MAmount').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                DtForecast[idx].ADJSW5 = parseFloat(DtForecast[idx].AMOUNTOUTSTANDING) - (parseFloat(DtForecast[idx].ADJSW2) + parseFloat(DtForecast[idx].ADJSW3) + parseFloat(DtForecast[idx].ADJSW4) + parseFloat(DtForecast[idx].ADJSW1));
                DtForecast[idx].ADJSW5 = DtForecast[idx].ADJSW5.toString();
                ReloadTable();
            }
        });
        table.on('click', ".PRIORITYW1", function() {
            $tr = $(this).closest('tr');
            idx = table.row($tr).index();
            WAMOUNT = 'PRIORITYW1';
            LAMOUNT = 'Priority W1';
            WPRIORITY = 'W1';
            if (parseFloat(DtForecast[idx].REQUESTW1) <= 0 && parseFloat(DtForecast[idx].ADJSW1) <= 0) {
                alert("Amount Request or Amount Adjusted Can't Zero !!!");
            } else {
                $("#PRIORITYWEEK").val(DtForecast[idx].PRIORITYW1);
                $('#FPriority').parsley().reset();
                var lbl = DtForecast[idx].DOCREF;
                if (DtForecast[idx].DOCNUMBER != null && DtForecast[idx].DOCNUMBER != undefined && DtForecast[idx].DOCNUMBER != '') {
                    lbl = lbl + " - " + DtForecast[idx].DOCNUMBER;
                }
                $('#MPriority .modal-title').text('Set ' + LAMOUNT + ' (' + lbl + ')');
                $('#MPriority').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        });
        table.on('click', ".PRIORITYW2", function() {
            $tr = $(this).closest('tr');
            idx = table.row($tr).index();
            WAMOUNT = 'PRIORITYW2';
            LAMOUNT = 'Priority W2';
            WPRIORITY = 'W2';
            if (parseFloat(DtForecast[idx].REQUESTW2) <= 0 && parseFloat(DtForecast[idx].ADJSW2) <= 0) {
                alert("Amount Request or Amount Adjusted Can't Zero !!!");
            } else {
                $("#PRIORITYWEEK").val(DtForecast[idx].PRIORITYW2);
                $('#FPriority').parsley().reset();
                var lbl = DtForecast[idx].DOCREF;
                if (DtForecast[idx].DOCNUMBER != null && DtForecast[idx].DOCNUMBER != undefined && DtForecast[idx].DOCNUMBER != '') {
                    lbl = lbl + " - " + DtForecast[idx].DOCNUMBER;
                }
                $('#MPriority .modal-title').text('Set ' + LAMOUNT + ' (' + lbl + ')');
                $('#MPriority').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        });
        table.on('click', ".PRIORITYW3", function() {
            $tr = $(this).closest('tr');
            idx = table.row($tr).index();
            WAMOUNT = 'PRIORITYW3';
            LAMOUNT = 'Priority W3';
            WPRIORITY = 'W3';
            if (parseFloat(DtForecast[idx].REQUESTW3) <= 0 && parseFloat(DtForecast[idx].ADJSW3) <= 0) {
                alert("Amount Request or Amount Adjusted Can't Zero !!!");
            } else {
                $("#PRIORITYWEEK").val(DtForecast[idx].PRIORITYW3);
                $('#FPriority').parsley().reset();
                var lbl = DtForecast[idx].DOCREF;
                if (DtForecast[idx].DOCNUMBER != null && DtForecast[idx].DOCNUMBER != undefined && DtForecast[idx].DOCNUMBER != '') {
                    lbl = lbl + " - " + DtForecast[idx].DOCNUMBER;
                }
                $('#MPriority .modal-title').text('Set ' + LAMOUNT + ' (' + lbl + ')');
                $('#MPriority').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        });
        table.on('click', ".PRIORITYW4", function() {
            $tr = $(this).closest('tr');
            idx = table.row($tr).index();
            WAMOUNT = 'PRIORITYW4';
            LAMOUNT = 'Priority W4';
            WPRIORITY = 'W4';
            if (parseFloat(DtForecast[idx].REQUESTW4) <= 0 && parseFloat(DtForecast[idx].ADJSW4) <= 0) {
                alert("Amount Request or Amount Adjusted Can't Zero !!!");
            } else {
                $("#PRIORITYWEEK").val(DtForecast[idx].PRIORITYW4);
                $('#FPriority').parsley().reset();
                var lbl = DtForecast[idx].DOCREF;
                if (DtForecast[idx].DOCNUMBER != null && DtForecast[idx].DOCNUMBER != undefined && DtForecast[idx].DOCNUMBER != '') {
                    lbl = lbl + " - " + DtForecast[idx].DOCNUMBER;
                }
                $('#MPriority .modal-title').text('Set ' + LAMOUNT + ' (' + lbl + ')');
                $('#MPriority').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        });
        table.on('click', ".PRIORITYW5", function() {
            $tr = $(this).closest('tr');
            idx = table.row($tr).index();
            WAMOUNT = 'PRIORITYW5';
            LAMOUNT = 'Priority W5';
            WPRIORITY = 'W5';
            if (parseFloat(DtForecast[idx].REQUESTW5) <= 0 && parseFloat(DtForecast[idx].ADJSW5) <= 0) {
                alert("Amount Request or Amount Adjusted Can't Zero !!!");
            } else {
                $("#PRIORITYWEEK").val(DtForecast[idx].PRIORITYW5);
                $('#FPriority').parsley().reset();
                var lbl = DtForecast[idx].DOCREF;
                if (DtForecast[idx].DOCNUMBER != null && DtForecast[idx].DOCNUMBER != undefined && DtForecast[idx].DOCNUMBER != '') {
                    lbl = lbl + " - " + DtForecast[idx].DOCNUMBER;
                }
                $('#MPriority .modal-title').text('Set ' + LAMOUNT + ' (' + lbl + ')');
                $('#MPriority').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        });
        /*table.on('change', "input[name='REQUESTW1[]']", function () {
         $tr = $(this).closest('tr');
         //            var data = table.row($tr).data();
         var index = table.row($tr).index();
         DtForecast[index].REQUESTW1 = formatDesimal($(this).val());
         table.clear();
         table.rows.add(DtForecast);
         table.draw();
         });*/
        //        $('#DtForecast_filter input').unbind();
        //        $('#DtForecast_filter input').bind('keyup', function (e) {
        //            table.search($(this).val(), true, false, true).draw();
        ////            $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
        //        });
    }
    var ReloadTable = function() {
        //        alert('test');
        CashInIdx = 0;
        CashOutIdx = 0;
        table.clear();
        table.rows.add(DtForecast);
        table.draw();
    };
    var ReloadTable1 = function() {
        //        alert('test1');
        CashInIdx = 0;
        CashOutIdx = 0;
        table.clear();
        table.rows.add(DtForecast);
        table.draw();
    };
    var EditAmount = function() {
        if ($('#FEditAmount').parsley().validate()) {
            var AMOUNTWEEK = formatDesimal($('#AMOUNTWEEK').val());
            if (parseFloat(DtForecast[idx].AMOUNTOUTSTANDING) < (parseFloat(AMOUNTUSE) + parseFloat(AMOUNTWEEK))) {
                alert("Total Amount Use dan Amount " + LAMOUNT + " can't above Amount Outstanding!!");
            } else {
                DtForecast[idx][WAMOUNT] = AMOUNTWEEK;
                if (parseFloat(AMOUNTWEEK) <= 0) {
                    DtForecast[idx][WPRIORITY] = null;
                }
                ReloadTable();
                $('#MAmount').modal("hide");
            }
        }
    };
    var EditPriority = function() {
        if ($("#PRIORITYWEEK").val() == "" || $("#PRIORITYWEEK").val() == null || $("#PRIORITYWEEK").val() == undefined) {
            DtForecast[idx][WAMOUNT] = null;
        } else {
            DtForecast[idx][WAMOUNT] = $("#PRIORITYWEEK").val();
        }
        ReloadTable();
        $('#MPriority').modal("hide");
    };

    //    Load Date Picker Period
    $('#PERIOD').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "viewMode": "months",
        "minViewMode": "months",
        "format": "M yyyy"
    });

    //    Change Data
    $('#PERIOD').on({
        'change': function() {
            POUTSTAND = "";
            MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
            YEAR = this.value.substr(4, 4);
            if (YEAR == null || YEAR == '' || YEAR == undefined) {
                DtForecast = [];
                FCLOCK = "", FCSTATUS = "";
                DisableButton();
                $("#RevIn").text("0.00");
                $("#RevOut").text("0.00");
                $("#AppIn").text("0.00");
                $("#AppOut").text("0.00");
            } else {
                $('#loader').addClass('show');
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Forecast/ShowDtForecast'); ?>",
                    data: {
                        YEAR: YEAR,
                        MONTH: MONTH,
                        DEPARTMENT: $("#DEPARTMENT").val(),
                        CASHFLOWTYPE: $("#CASHFLOWTYPE").val(),
                        COMPANYSUBGROUP: $('#COMPANYSUBGROUP').val(),
                        COMPANYGROUP: $('#COMPANYGROUP').val(),
                        USERNAME: USERNAME,
                        USERACCESS: USERACCESS
                    },
                    success: function(response, textStatus, jqXHR) {
                        $.ajax({
                                dataType: "JSON",
                                type: "POST",
                                url: "<?php echo site_url('Forecast/WeekHeader'); ?>",
                                data: {
                                    YEAR: YEAR,
                                    MONTH: MONTH
                                },success: function(response) {
                                    // console.log(response);
                                    $('.W1').text(response[0].DATEFROM + '-' + response[0].DATEUNTIL);
                                    $('.W2').text(response[1].DATEFROM + '-' + response[1].DATEUNTIL);
                                    $('.W3').text(response[2].DATEFROM + '-' + response[2].DATEUNTIL);
                                    $('.W4').text(response[3].DATEFROM + '-' + response[3].DATEUNTIL);
                                    $('.W5').text(response[4].DATEFROM + '-' + response[4].DATEUNTIL);
                                }
                        });
                        $('#page-container').addClass('page-sidebar-minified');
                        $('#loader').removeClass('show');
                        $('html, body').animate({
                            scrollTop: $("#dtf").offset().top
                        }, 1000);
                        if (response.status == 200) {
                            var data = response.result.data;
                            DtForecast = data.DtForecast;
                            if (
                                ($("#DEPARTMENT").val() == '' || $("#DEPARTMENT").val() == null || $("#DEPARTMENT").val() == undefined) ||
                                ($("#CASHFLOWTYPE").val() == '' || $("#CASHFLOWTYPE").val() == null || $("#CASHFLOWTYPE").val() == undefined) || $("#COMPANYGROUP").val() == '' || $("#COMPANYGROUP").val() == null || $("#COMPANYGROUP").val() == undefined || $("#COMPANYSUBGROUP").val() == '' || $("#COMPANYSUBGROUP").val() == null || $("#COMPANYSUBGROUP").val() == undefined) {
                                FCLOCK = "", FCSTATUS = "";
                                DisableButton();
                            } else {
                                if (data['DtRevisi'].length <= 0) {
                                    FCLOCK = 0, FCSTATUS = 0;
                                    DisableButton();
                                } else {
                                    FCLOCK = data['DtRevisi'][0].LOCKS;
                                    FCSTATUS = data['DtRevisi'][0].ISACTIVE;
                                    DisableButton();
                                }
                            }
                            REVISIONIN = '0', REVISIONOUT = '0', APPROVEDIN = '0', APPROVEDOUT = '0';
                            $.each(data['DtRevisi'], function(index, value) {
                                if (value['CASHFLOWTYPE'] == '0') {
                                    REVISIONIN = value['AMOUNTREVISI'].toString();
                                    APPROVEDIN = value['AMOUNTAPPROVE'].toString();
                                } else {
                                    REVISIONOUT = value['AMOUNTREVISI'].toString();
                                    APPROVEDOUT = value['AMOUNTAPPROVE'].toString();
                                }
                            });
                            $("#RevIn").text(fCurrency(REVISIONIN));
                            $("#RevOut").text(fCurrency(REVISIONOUT));
                            $("#AppIn").text(fCurrency(APPROVEDIN));
                            $("#AppOut").text(fCurrency(APPROVEDOUT));
                        } else if (response.status == 504) {
                            alert(response.result.data);
                            location.reload();
                        } else {
                            alert(response.result.data);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Please Check Your Connection !!!');
                        $('#loader').removeClass('show');
                    }
                });
            }
        }
    });
    $('#DEPARTMENT').on({
        'change': function() {
            $('#PERIOD').change();
        }
    });
    $('#COMPANYSUBGROUP').on({
        'change': function() {
            $('#PERIOD').change();
        }
    });
    $('#CASHFLOWTYPE').on({
        'change': function() {
            $('#PERIOD').change();
        }
    });
    var DisableButton = function() {
        if ((USERACCESS == '100003' || USERACCESS == '100005') /* && FCSTATUS != '2'*/ ) {
            $('#RevAppDana').removeAttr(Disable);
        } else {
            $('#RevAppDana').attr(Disable, true);
        }
        $('#btnEdit').prop(Disable, true);
        if (FCLOCK == '0' && FCSTATUS != '2') {
            $('#btnEdit').removeAttr(Disable);
        }
        ReloadTable1();
    };
    $('#btnEdit').on({
        "click": function() {
            if ($('#PERIOD').val() == null || $('#PERIOD').val() == '' || $('#PERIOD').val() == undefined) {
                alert("Please, Choose Period First !!");
            } else if ($("#DEPARTMENT").val() == null || $('#DEPARTMENT').val() == '' || $('#DEPARTMENT').val() == undefined) {
                alert("Please, Choose Departmet First !!");
            } else if ($("#CASHFLOWTYPE").val() == null || $('#CASHFLOWTYPE').val() == '' || $('#CASHFLOWTYPE').val() == undefined) {
                alert("Please, Choose Forecast Type First !!");
            } else {
                $('#btnEdit').prop("disabled", true);
                $('#loader').addClass('show');
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Forecast/CekValidation'); ?>",
                    data: {
                        YEAR: YEAR,
                        MONTH: MONTH,
                        DEPARTMENT: $("#DEPARTMENT").val(),
                        CASHFLOWTYPE: $("#CASHFLOWTYPE").val(),
                        COMPANYGROUP: $('#COMPANYGROUP').val(),
                        COMPANYSUBGROUP: $('#COMPANYSUBGROUP').val(),
                        USERNAME: USERNAME
                    },
                    success: function(response, textStatus, jqXHR) {
                        $('#loader').removeClass('show');
                        $('#btnEdit').removeAttr('disabled');
                        if (response.status == 200) {
                            KEYSAVE = response.result.data;
                            $('#btnEdit').prop("hidden", true);
                            $('#btnAdd').removeAttr("hidden");
                            $('#btnAddAll').removeAttr("hidden");
                            $('#btnAddNoInv').removeAttr("hidden");
                            $('#btnSave').removeAttr("hidden");
                            $('#btnSubmit').removeAttr("hidden");
                            $('#btnCancel').removeAttr("hidden");
                            $('#PERIOD').prop("disabled", true);
                            $('#DEPARTMENT').prop("disabled", true);
                            $('#CASHFLOWTYPE').prop("disabled", true);
                            $('#COMPANYGROUP').prop("disabled", true);
                            $('#COMPANYSUBGROUP').prop("disabled", true);
                            ISEDIT = 1;
                            ReloadTable();
                        } else if (response.status == 504) {
                            alert(response.result.data);
                            location.reload();
                        } else {
                            alert(response.result.data);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $('#loader').removeClass('show');
                        $('#btnEdit').removeAttr('disabled');
                        //                        console.info(e);
                        alert('Cannot null !!!');
                    }
                });
            }
        }
    });
    $('#btnCancel').on({
        "click": function() {
            $('#btnCancel').prop("disabled", true);
            $('#loader').addClass('show');
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Forecast/CancelValidation'); ?>",
                data: {
                    YEAR: YEAR,
                    MONTH: MONTH,
                    DEPARTMENT: $("#DEPARTMENT").val(),
                    CASHFLOWTYPE: $("#CASHFLOWTYPE").val(),
                    COMPANYGROUP: $('#COMPANYGROUP').val(),
                    COMPANYSUBGROUP: $('#COMPANYSUBGROUP').val(),
                    USERNAME: USERNAME,
                    KEYSAVE: KEYSAVE
                },
                success: function(response, textStatus, jqXHR) {
                    $('#loader').removeClass('show');
                    $('#btnCancel').removeAttr('disabled');
                    if (response.status == 200) {
                        $('#PERIOD').val('');
                        POUTSTAND = "";
                        YEAR = '';
                        MONTH = '';
                        DtForecast = [];
                        KEYSAVE = "";
                        $('#btnEdit').removeAttr("hidden");
                        $('#btnEdit').prop(Disable, true);
                        $('#btnAdd').prop("hidden", true);
                        $('#btnAddAll').prop("hidden", true);
                        $('#btnAddNoInv').prop("hidden", true);
                        $('#btnSave').prop("hidden", true);
                        $('#btnSubmit').prop("hidden", true);
                        $('#btnCancel').prop("hidden", true);
                        $('#PERIOD').removeAttr("disabled");
                        $('#DEPARTMENT').removeAttr("disabled");
                        $('#CASHFLOWTYPE').removeAttr("disabled");
                        $('#COMPANYGROUP').removeAttr("disabled");
                        $('#COMPANYSUBGROUP').removeAttr("disabled");
                        ISEDIT = 0;
                        ReloadTable();
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        location.reload();
                    } else {
                        alert(response.result.data);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#loader').removeClass('show');
                    $('#btnCancel').removeAttr('disabled');
                    alert('Please, Check Your Connection !!!');
                }
            });
        }
    });
    $('#btnEdit').prop(Disable, true);
    $('#btnAdd').prop("hidden", true);
    $('#btnAddAll').prop("hidden", true);
    $('#btnAddNoInv').prop("hidden", true);
    $('#btnSave').prop("hidden", true);
    $('#btnSubmit').prop("hidden", true);
    $('#btnCancel').prop("hidden", true);

    //    Add Data Forecast
    var Add = function() {
        if ($('#PERIOD').val() == null || $('#PERIOD').val() == '' || $('#PERIOD').val() == undefined) {
            alert("Please, Choose Period First !!");
        } else if ($("#DEPARTMENT").val() == null || $('#DEPARTMENT').val() == '' || $('#DEPARTMENT').val() == undefined) {
            alert("Please, Choose Department First !!");
        } else if ($("#CASHFLOWTYPE").val() == null || $('#CASHFLOWTYPE').val() == '' || $('#CASHFLOWTYPE').val() == undefined) {
            alert("Please, Choose Forecast Type First !!");
        } else {
            $('#pil').removeAttr('checked');
            if (!$.fn.DataTable.isDataTable('#DtOutstanding')) {
                $('#DtOutstanding').DataTable({
                    "processing": true,
                    buttons:[
                        {
                            extend: 'searchBuilder',
                            config: {
                                depthLimit: 2
                            }
                        }
                    ],
                    dom: 'Bfrtip',
                    "ajax": {
                        "url": "<?php echo site_url('Forecast/DtOutstanding') ?>",
                        "type": "POST",
                        "datatype": "JSON",
                        "data": function(d) {
                            d.MONTH = MONTH;
                            d.YEAR = YEAR;
                            d.POUTSTAND = POUTSTAND;
                            d.DEPARTMENT = $("#DEPARTMENT").val();
                            d.CASHFLOWTYPE = $("#CASHFLOWTYPE").val();
                            d.COMPANYGROUP = $('#COMPANYGROUP').val();
                            d.COMPANYSUBGROUP = $('#COMPANYSUBGROUP').val();
                            d.USERNAME = USERNAME;
                            d.USERACCESS = USERACCESS;
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
                                return '<input type="checkbox" class="pils">';
                            }
                        },
                        {
                            "data": null,
                            "className": "text-center",
                            "orderable": false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            "data": "COMPANYCODE"
                        },
                        {
                            "data": "BUSINESSUNITCODE"
                        },
                        {
                            "data": "DOCTYPE"
                        },
                        {
                            "data": "DOCREF"
                        },
                        {
                            "data": "VENDORNAME"
                        },
                        {
                            "data": "DUEDATE"
                        },
                        {
                            "data": "DOCNUMBER"
                        },
                        {
                            "data": "INVOICEVENDORNO"
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
                            "data": "AMOUNTOUTSTANDING",
                            "className": "text-right",
                            render: $.fn.dataTable.render.number(',', '.', 2)
                        },
                        {
                            "data": "CASHFLOWTYPE",
                            render: function(data, type, row, meta) {
                                var html = "";
                                if (data == "0") {
                                    html = "Cash In";
                                } else {
                                    html = "Cash Out";
                                }
                                return html;
                            }
                        }
                    ]
                });
                table2 = $('#DtOutstanding').DataTable();
                table2.on('change', '.pils', function() {
                    $tr = $(this).closest('tr');
                    var data = table2.row($tr).data();
                    if (this.checked) {
                        data.FLAG = "1";
                    } else {
                        data.FLAG = "0";
                    }
                });
            } else {
                table2.ajax.reload();
            }
            $("#MOutstanding button[type='submit']").removeAttr('disabled');
            $('#MOutstanding').modal({
                backdrop: 'static',
                keyboard: false
            });
        }
    };

    // Region Addition For Forecast Without Invoice
    var AddNoInv = function() {
        if ($('#PERIOD').val() == null || $('#PERIOD').val() == '' || $('#PERIOD').val() == undefined) {
            alert("Please, Choose Period First !!");
        } else if ($("#DEPARTMENT").val() == null || $('#DEPARTMENT').val() == '' || $('#DEPARTMENT').val() == undefined) {
            alert("Please, Choose Departmet First !!");
        } else if ($("#CASHFLOWTYPE").val() == null || $('#CASHFLOWTYPE').val() == '' || $('#CASHFLOWTYPE').val() == undefined) {
            alert("Please, Choose Forecast Type First !!");
        } else {
            $('#pil').removeAttr('checked');
            if (!$.fn.DataTable.isDataTable('#DtOutstandingNoInv')) {
                $('#DtOutstandingNoInv').DataTable({
                    "processing": true,
                    buttons:[
                        {
                            extend: 'searchBuilder',
                            config: {
                                depthLimit: 2
                            }
                        }
                    ],
                    dom: 'Bfrtip',
                    "ajax": {
                        "url": "<?php echo site_url('Forecast/DtOutstandingNoInv') ?>",
                        "type": "POST",
                        "datatype": "JSON",
                        "data": function(d) {
                            d.MONTH = MONTH;
                            d.YEAR = YEAR;
                            d.POUTSTAND = POUTSTAND;
                            d.DEPARTMENT = $("#DEPARTMENT").val();
                            d.CASHFLOWTYPE = $("#CASHFLOWTYPE").val();
                            d.USERNAME = USERNAME;
                            d.USERACCESS = USERACCESS;
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
                            "orderable": false,
                            render: function(data, type, row, meta) {
                                return '<input type="checkbox" class="pils">';
                            }
                        },
                        {
                            "data": null,
                            "className": "text-center",
                            "orderable": false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            "data": "COMPANY"
                        },
                        {
                            "data": "BUSINESSUNIT"
                        },
                        {
                            "data": "DOCTYPE"
                        },
                        {
                            "data": "DOCNUMBER"
                        },
                        {
                            "data": "VENDOR"
                        },
                        {
                            "data": "DUEDATE"
                        },
                        {
                            "data": "INVNO"
                        },
                        {
                            "data": "CURRENCY"
                        },
                        {
                            "data": "AMOUNT",
                            "className": "text-right",
                            render: $.fn.dataTable.render.number(',', '.', 2)
                        }
                    ]
                });
                table3 = $('#DtOutstandingNoInv').DataTable();
                table3.on('change', '.pils', function() {
                    $tr = $(this).closest('tr');
                    var data = table3.row($tr).data();
                    if (this.checked) {
                        data.FLAG = "1";
                    } else {
                        data.FLAG = "0";
                    }
                });
            } else {
                table3.ajax.reload();
            }
            $("#MOutstandingNoInv button[type='submit']").removeAttr('disabled');
            $('#MOutstandingNoInv').modal({
                backdrop: 'static',
                keyboard: false
            });
        }
    };
    // End Region
    $('#pil').on('change', function() {
        if (this.checked) {
            $('#DtOutstanding .pils').prop("checked", true);
        } else {
            $('#DtOutstanding .pils').prop("checked", false);
        }
        $('#DtOutstanding .pils').change();
    });
    $("#MOutstanding button[type='submit']").on('click', function() {
        $(this).prop('disabled', true);
        $('#loader').addClass('show');
        $.each(table2.data(), function(index, value) {
            if (value.ID == undefined || value.ID == null || value.ID == '') {} else {
                if (value.FLAG == 1) {
                    value.REQUESTW1 = '0';
                    value.REQUESTW2 = '0';
                    value.REQUESTW3 = '0';
                    value.REQUESTW4 = '0';
                    value.REQUESTW5 = '0';
                    value.ADJSW1 = '0';
                    value.ADJSW2 = '0';
                    value.ADJSW3 = '0';
                    value.ADJSW4 = '0';
                    value.ADJSW5 = '0';
                    value.PRIORITYW1 = null;
                    value.PRIORITYW2 = null;
                    value.PRIORITYW3 = null;
                    value.PRIORITYW4 = null;
                    value.PRIORITYW5 = null;
                    DtForecast.push(value);
                    if (POUTSTAND == '' || POUTSTAND == null || POUTSTAND == undefined) {
                        POUTSTAND = "'" + value.ID + "'";
                    } else {
                        POUTSTAND += ", '" + value.ID + "'";
                    }
                }
            }
        });
        ReloadTable1();
        $('#loader').removeClass('show');
        $('#MOutstanding').modal("hide");
    });
    // Region Addition For Forecast Without Invoice
    $("#MOutstandingNoInv button[type='submit']").on('click', function() {

        $(this).prop('disabled', true);
        $('#loader').addClass('show');
        $.each(table3.data(), function(index, value) {
			if (value.ID == undefined || value.ID == null || value.ID == '') {} else {
				 if (value.FLAG == 1) {
					$.ajax({
						url: "<?php echo site_url('Forecast/AddDummyInv') ?>",
						type: "POST",
						data: {
							ID: value.ID
						},
						dataType: "JSON",
						success: function(data) {
							alert("Data has been Successfully Saved !!");

							$('#DtOutstanding').DataTable().ajax.reload();
						},
						error: function(jqXHR, textStatus, errorThrown) {
							alert('Error Create Dummy Invoice!! Please Contact Administrator');
						}
					});
				 }
				
			}
			
        });
        ReloadTable1();
        $('#loader').removeClass('show');
        $('#MOutstandingNoInv').modal("hide");
    });
    // End Region												   
    $('#btnAddAll').on('click', function() {
        if ($('#PERIOD').val() == null || $('#PERIOD').val() == '' || $('#PERIOD').val() == undefined) {
            alert("Please, Choose Period First !!");
        } else if ($("#DEPARTMENT").val() == null || $('#DEPARTMENT').val() == '' || $('#DEPARTMENT').val() == undefined) {
            alert("Please, Choose Departmet First !!");
        } else if ($("#CASHFLOWTYPE").val() == null || $('#CASHFLOWTYPE').val() == '' || $('#CASHFLOWTYPE').val() == undefined) {
            alert("Please, Choose Forecast Type First !!");
        } else {
            $('#loader').addClass('show');
            $("#btnAdd").attr("disabled", true);
            $("#btnAddAll").attr("disabled", true);
            $("#btnAddNoInv").attr("disabled", true);
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Forecast/DtOutstanding'); ?>",
                data: {
                    MONTH: MONTH,
                    YEAR: YEAR,
                    POUTSTAND: POUTSTAND,
                    DEPARTMENT: $("#DEPARTMENT").val(),
                    CASHFLOWTYPE: $("#CASHFLOWTYPE").val(),
                    USERNAME: USERNAME,
                    USERACCESS: USERACCESS
                },
                success: function(response, textStatus, jqXHR) {
                    $('#loader').removeClass('show');
                    $('#btnAdd').removeAttr('disabled');
                    $('#btnAddAll').removeAttr('disabled');
                    if (response.status == 200) {
                        $.each(response.result.data, function(index, value) {
                            if (value.ID == undefined || value.ID == null || value.ID == '') {} else {
                                value.REQUESTW1 = '0';
                                value.REQUESTW2 = '0';
                                value.REQUESTW3 = '0';
                                value.REQUESTW4 = '0';
                                value.REQUESTW5 = '0';
                                value.ADJSW1 = '0';
                                value.ADJSW2 = '0';
                                value.ADJSW3 = '0';
                                value.ADJSW4 = '0';
                                value.ADJSW5 = '0';
                                value.PRIORITYW1 = null;
                                value.PRIORITYW2 = null;
                                value.PRIORITYW3 = null;
                                value.PRIORITYW4 = null;
                                value.PRIORITYW5 = null;
                                DtForecast.push(value);
                                if (POUTSTAND == '' || POUTSTAND == null || POUTSTAND == undefined) {
                                    POUTSTAND = "'" + value.ID + "'";
                                } else {
                                    POUTSTAND += ", '" + value.ID + "'";
                                }
                            }
                        });
                        ReloadTable1();
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        location.reload();
                    } else {
                        alert(response.result.data);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#loader').removeClass('show');
                    $('#btnAdd').removeAttr('disabled');
                    $('#btnAddAll').removeAttr('disabled');
                    alert('Failed to get data !!');
                }
            });
        }
    });

    //    Button Save dan Submit
    var Save = function(ACTION) {
        if (YEAR == null || YEAR == '' || YEAR == undefined) {
            alert("Please, Choose Period First !!");
        } else if (DtForecast.length <= 0) {
            alert('Detail Record Not Set !!!');
        } else if ($("#DEPARTMENT").val() == null || $('#DEPARTMENT').val() == '' || $('#DEPARTMENT').val() == undefined) {
            alert("Please, Choose Departmet First !!");
        } else {
            $('#btnSave').attr('disabled', true);
            $('#btnSubmit').attr('disabled', true);
            $('#loader').addClass('show');
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Forecast/SaveForecast'); ?>",
                data: {
                    YEAR: YEAR,
                    MONTH: MONTH,
                    DtForecast: JSON.stringify(DtForecast),
                    FCSTATUS: FCSTATUS,
                    FCLOCK: FCLOCK,
                    DEPARTMENT: $("#DEPARTMENT").val(),
                    CASHFLOWTYPE: $("#CASHFLOWTYPE").val(),
                    COMPANYGROUP: $('#COMPANYGROUP').val(),
                    COMPANYSUBGROUP: $('#COMPANYSUBGROUP').val(),
                    REQUESTINTOT: REQUESTINTOT,
                    REQUESTOUTTOT: REQUESTOUTTOT,
                    ADJSINTOT: ADJSINTOT,
                    ADJSOUTTOT: ADJSOUTTOT,
                    ACTION: ACTION,
                    KEYSAVE: KEYSAVE,
                    USERNAME: USERNAME
                },
                success: function(response, textStatus, jqXHR) {
                    $('#loader').removeClass('show');
                    $('#btnSave').removeAttr('disabled');
                    $('#btnSubmit').removeAttr('disabled');
                    if (response.status == 200) {
                        alert(response.result.data);
                        $('#PERIOD').val('');
                        POUTSTAND = "";
                        YEAR = '';
                        MONTH = '';
                        DtForecast = [];
                        KEYSAVE = "";
                        $('#btnEdit').removeAttr("hidden");
                        $('#btnEdit').prop(Disable, true);
                        $('#btnAdd').prop("hidden", true);
                        $('#btnAddAll').prop("hidden", true);
                        $('#btnSave').prop("hidden", true);
                        $('#btnSubmit').prop("hidden", true);
                        $('#btnCancel').prop("hidden", true);
                        $('#PERIOD').removeAttr("disabled");
                        $('#DEPARTMENT').removeAttr("disabled");
                        $('#CASHFLOWTYPE').removeAttr("disabled");
                        $('#COMPANYGROUP').removeAttr("disabled");
                        $('#COMPANYSUBGROUP').removeAttr("disabled");
                        ISEDIT = 0;
                        ReloadTable();
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        location.reload();
                    } else {
                        alert(response.result.data);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#loader').removeClass('show');
                    // console.info(e);
                    alert('Data Save Failed !!!');
                    $('#btnSave').removeAttr('disabled');
                    $('#btnSubmit').removeAttr('disabled');
                }
            });
        }
    };
    var Submit = function() {
        if (FCSTATUS == 1) {
            if ($("#CASHFLOWTYPE").val() == '0') {
                if (parseFloat(ADJSINTOT) > parseFloat(REVISIONIN)) {
                    alert("Adjusted In can't above Revision In");
                } else {
                    FCLOCK = 1;
                    Save(2);
                }
            } else if ($("#CASHFLOWTYPE").val() == '1') {
                if (parseFloat(ADJSOUTTOT) > parseFloat(REVISIONOUT)) {
                    alert("Adjusted Out can't above Revision Out");
                } else {
                    FCLOCK = 1;
                    Save(2);
                }
            }
        } else {
            FCLOCK = 1;
            Save(1);
        }
    };

    //    Revision Or Approved Dana
    var RevAppDana = function() {

        var COMPANYGROUP = $('#COMPANYGROUP').val();
        var COMPANYSUBGROUP = $('#COMPANYSUBGROUP').val();

        if (YEAR == null || YEAR == '' || YEAR == undefined) {
            alert("Please, Choose Period First !!");
        }
        else if (COMPANYGROUP == null || COMPANYGROUP == '' || COMPANYSUBGROUP == null || COMPANYSUBGROUP == '') {
            alert('Please do not choose all on group / subgroup');
        } else if (DtForecast.length <= 0) {
            alert('Detail Record Not Set !!!');
        } else {
            $('#loader').addClass('show');
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Forecast/DtRevAppDana'); ?>",
                data: {
                    YEAR: YEAR,
                    MONTH: MONTH,
                    COMPANYGROUP: COMPANYGROUP,
                    COMPANYSUBGROUP: COMPANYSUBGROUP,
                    USERNAME: USERNAME
                },
                success: function(response, textStatus, jqXHR) {
                    $('#loader').removeClass('show');
                    if (response.status == 200) {
                        DtRevApp = response.result.data;
                        LoadDtRevApp();
                        $('#MRevAppDana').modal({
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
        }
    };

    function LoadDtRevApp() {
        if (!$.fn.DataTable.isDataTable('#DtRevAppDana')) {
            $('#DtRevAppDana').DataTable({
                "aaData": DtRevApp,
                "columns": [{
                        "data": "CASHFLOWTYPE"
                    },
                    {
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
                        "data": "AMOUNTREQUEST",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNTADJS",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNTREVISI",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNTAPPROVE",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "SREVAPP",
                        "className": "text-center",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            var html = '',
                                slabel;
                            if (row.LOCKS == '1' && row.ISACTIVE != '2') {
                                if (data == '' || data == null || data == undefined) {
                                    html = '<a href="javascript:void(0);" class="STATUS" role="button" title="Status Action">Klik</a>';
                                } else {
                                    if (data == '2') {
                                        slabel = 'Approved';
                                    } else if (data == '1') {
                                        slabel = 'Revision';
                                    }
                                    html = '<a href="javascript:void(0);" class="STATUS" role="button" title="Status Action">' + slabel + '</a>';
                                }

                            }
                            return html;
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row, meta) {
                            var html = '',
                                Lock = '';
                            if (data.CASHFLOWTYPE != "" && data.CASHFLOWTYPE != null && data.CASHFLOWTYPE != undefined) {
                                if (data.ISACTIVE == '2') {
                                    html = 'Approve By Finance';
                                } else {
                                    if (data.LOCKS == '0') {
                                        Lock = 'User';
                                    } else if (data.LOCKS == '1') {
                                        Lock = 'Finance';
                                    }
                                    if (data.CASHFLOWTYPE == '0') {
                                        if (data.ISACTIVE == '1') {
                                            html = 'Adjusted In On ' + Lock;
                                        } else {
                                            html = 'Request In On ' + Lock;
                                        }
                                    } else {
                                        if (data.ISACTIVE == '1') {
                                            html = 'Adjusted Out On ' + Lock;
                                        } else {
                                            html = 'Request Out On ' + Lock;
                                        }
                                    }
                                }
                            }
                            return html;
                        }
                    }
                ],
                "responsive": false,
                "bFilter": false,
                "bPaginate": false,
                "bLengthChange": false,
                "bInfo": false,
                "columnDefs": [{
                    "visible": false,
                    "targets": 0
                }],
                orderFixed: [0, 'asc'],
                "rowGroup": {
                    startRender: function(rows, group) {
                        var html = "";
                        if (group == '0') {
                            html = "Cash In";
                        } else if (group == '1') {
                            html = "Cash Out";
                        } else {
                            html = "Not Send";
                        }
                        return html;
                    },
                    endRender: function(rows, group) {
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };
                        var ReqAmount = rows.data().pluck('AMOUNTREQUEST').reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        var AdjAmount = rows.data().pluck('AMOUNTADJS').reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        var RevAmount = rows.data().pluck('AMOUNTREVISI').reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        var AppAmount = rows.data().pluck('AMOUNTAPPROVE').reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        var label = "";
                        if (group == '0') {
                            label = "Cash In";
                        } else if (group == '1') {
                            label = "Cash Out";
                        } else {
                            label = "Not Send";
                        }
                        var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                        return $('<tr/>')
                            .append('<td colspan="2">Total ' + label + '</td>')
                            .append('<td class="text-right">' + numFormat(ReqAmount) + '</td>')
                            .append('<td class="text-right">' + numFormat(AdjAmount) + '</td>')
                            .append('<td class="text-right">' + numFormat(RevAmount) + '</td>')
                            .append('<td class="text-right">' + numFormat(AppAmount) + '</td>')
                            .append('<td colspan="2"></td>');
                    },
                    dataSrc: "CASHFLOWTYPE"
                }
            });
            table3 = $('#DtRevAppDana').DataTable();
            table3.on('click', ".AMOUNTREVISION", function() {
                $tr = $(this).closest('tr');
                idx = table3.row($tr).index();
                if (DtRevApp[idx].AMOUNTREVISI > 0) {
                    $('#FEditRev').parsley().reset();
                    $('#MRev .modal-title').text('Edit Amount Revision (' + DtRevApp[idx].DEPARTMENT + ')');
                    $('#AMOUNTREVISION').val(DtRevApp[idx].AMOUNTREVISI);
                    formatCurrency($('#AMOUNTREVISION'), "blur");
                    $('#MRev').on('hidden.bs.modal', function(event) {
                        $('body').addClass('modal-open');
                    }).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else {
                    if (DtRevApp[idx].AMOUNTADJS > 0) {
                        DtRevApp[idx].AMOUNTREVISI = DtRevApp[idx].AMOUNTADJS;
                    } else {
                        DtRevApp[idx].AMOUNTREVISI = DtRevApp[idx].AMOUNTREQUEST;
                    }
                }
                table3.clear();
                table3.rows.add(DtRevApp);
                table3.draw();
            });
            table3.on('click', ".STATUS", function() {
                $tr = $(this).closest('tr');
                idx = table3.row($tr).index();
                $("#SREVAPP").val(DtRevApp[idx].SREVAPP);
                $('#SREVAPP').change();
                $('#FSRev').parsley().reset();
                $('#MSRev .modal-title').text('Set Action (' + DtRevApp[idx].DEPARTMENT + ')');
                $('#MSRev').on('hidden.bs.modal', function(event) {
                    $('body').addClass('modal-open');
                }).modal({
                    backdrop: 'static',
                    keyboard: false
                });
            });
        } else {
            table3.clear();
            table3.rows.add(DtRevApp);
            table3.draw();
        }
    }
    var EditRev = function() {
        if ($('#FEditRev').parsley().validate()) {
            var AMOUNTREVISION = formatDesimal($('#AMOUNTREVISION').val());
            DtRevApp[idx].AMOUNTREVISI = AMOUNTREVISION;
            if (parseFloat(AMOUNTREVISION) <= 0) {
                DtRevApp[idx].SREVAPP = "";
            }
            table3.clear();
            table3.rows.add(DtRevApp);
            table3.draw();
            $('#MRev').modal("hide");
        }
    };
    $('#SREVAPP').on({
        'change': function() {
            if (this.value == "" || this.value == undefined || this.value == null || this.value == '2') {
                $('#AMOUNTREVISION').attr('disabled', true);
                $('#AMOUNTREVISION').removeAttr('required');
                if (this.value == "" || this.value == undefined || this.value == null) {
                    $('#AMOUNTREVISION').val(0);
                    formatCurrency($('#AMOUNTREVISION'), "blur");
                } else {
                    if (parseFloat(DtRevApp[idx].AMOUNTADJS) > 0) {
                        $('#AMOUNTREVISION').val(DtRevApp[idx].AMOUNTADJS);
                        formatCurrency($('#AMOUNTREVISION'), "blur");
                    } else {
                        $('#AMOUNTREVISION').val(DtRevApp[idx].AMOUNTREQUEST);
                        formatCurrency($('#AMOUNTREVISION'), "blur");
                    }
                }
            } else if (this.value == '1') {
                $('#AMOUNTREVISION').attr('required', true);
                $('#AMOUNTREVISION').removeAttr('disabled');
                if (parseFloat(formatDesimal($('#AMOUNTREVISION').val())) > 0) {} else {
                    if (parseFloat(DtRevApp[idx].AMOUNTADJS) > 0) {
                        $('#AMOUNTREVISION').val(DtRevApp[idx].AMOUNTADJS);
                        formatCurrency($('#AMOUNTREVISION'), "blur");
                    } else {
                        $('#AMOUNTREVISION').val(DtRevApp[idx].AMOUNTREQUEST);
                        formatCurrency($('#AMOUNTREVISION'), "blur");
                    }
                }
            }
        }
    });
    var EditSRev = function() {
        DtRevApp[idx].SREVAPP = $("#SREVAPP").val();
        if ($("#SREVAPP").val() == '1') {
            DtRevApp[idx].AMOUNTREVISI = formatDesimal($('#AMOUNTREVISION').val());
            DtRevApp[idx].AMOUNTAPPROVE = '0';
        } else if ($("#SREVAPP").val() == '2') {
            DtRevApp[idx].AMOUNTREVISI = '0';
            DtRevApp[idx].AMOUNTAPPROVE = formatDesimal($('#AMOUNTREVISION').val());
        } else {
            DtRevApp[idx].AMOUNTREVISI = '0';
            DtRevApp[idx].AMOUNTAPPROVE = '0';
        }
        table3.clear();
        table3.rows.add(DtRevApp);
        table3.draw();
        $('#MSRev').modal("hide");
    };
    var SaveRevApp = function() {
        var COMPANYGROUP = $('#COMPANYGROUP').val();
        var COMPANYSUBGROUP = $('#COMPANYSUBGROUP').val();

        var dt = dtRevApp(DtRevApp);
        if (dt.length <= 0) {
            alert('Data Save Not Set !!');
        } else {
            $('#loader').addClass('show');
            $("#MRevAppDana button[type='submit']").attr('disabled', true);
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Forecast/SaveRevApp'); ?>",
                data: {
                    YEAR: YEAR,
                    MONTH: MONTH,
                    COMPANYGROUP: COMPANYGROUP,
                    COMPANYSUBGROUP: COMPANYSUBGROUP,
                    DATA: JSON.stringify(dt),
                    USERNAME: USERNAME
                },
                success: function(response) {
                    $('#loader').removeClass('show');
                    $("#MRevAppDana button[type='submit']").removeAttr('disabled');
                    if (response.status == 200) {
                        alert(response.result.data);
                        $('#MRevAppDana').modal("hide");
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        location.reload();
                    } else {
                        alert(response.result.data);
                    }
                },
                error: function(e) {
                    $('#loader').removeClass('show');
                    console.info(e);
                    alert('Data Save Failed !!');
                    $("#MRevAppDana button[type='submit']").removeAttr('disabled');
                }
            });
        }
    };

    //    Export Data
    $('#EPERIOD').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "viewMode": "months",
        "minViewMode": "months",
        "format": "M yyyy"
    });
    $('#btnExport').on({
        'click': function() {
            // if(USERNAME != 'ADMIN'){
            //     alert('Maintenance, Comeback Later.');    
            // }else{
                $('#FExport').parsley().reset();
                $("#MExport").modal({
                    backdrop: 'static',
                    keyboard: false
                });
            // }
        }
    });
    var Export = function(type) {
        if ($('#FExport').parsley().validate()) {
            var url = "<?php echo site_url('Process/ForecastExport'); ?>?type=PARAM1&DEPARTMENT=PARAM2&CASHFLOWTYPE=PARAM3&MONTH=PARAM4&YEAR=PARAM5&USERNAME=PARAM6&COMPANYGROUP=PARAM7&COMPANYSUBGROUP=PARAM8";
            url = url.replace("PARAM1", type);
            if ($("#EDEPARTMENT").val() == "" || $("#EDEPARTMENT").val() == null || $("#EDEPARTMENT").val() == undefined) {
                url = url.replace("PARAM2", 'ALL');
            } else {
                url = url.replace("PARAM2", $("#EDEPARTMENT").val());
            }
            if ($("#ECASHFLOWTYPE").val() == "" || $("#ECASHFLOWTYPE").val() == null || $("#ECASHFLOWTYPE").val() == undefined) {
                url = url.replace("PARAM3", 'ALL');
            } else {
                url = url.replace("PARAM3", $("#ECASHFLOWTYPE").val());
            }
            if ($("#ECOMPANYGROUP").val() == "" || $("#ECOMPANYGROUP").val() == null || $("#ECOMPANYGROUP").val() == undefined) {
                url = url.replace("PARAM7", '');
            } else {
                url = url.replace("PARAM7", $("#ECOMPANYGROUP").val());
            }
            if ($("#ECOMPANYSUBGROUP").val() == "" || $("#ECOMPANYSUBGROUP").val() == null || $("#ECOMPANYSUBGROUP").val() == undefined) {
                url = url.replace("PARAM8", '');
            } else {
                url = url.replace("PARAM8", $("#ECOMPANYSUBGROUP").val());
            }
            MONTH = ListBulan.indexOf($('#EPERIOD').val().substr(0, 3)) + 1;
            YEAR = $('#EPERIOD').val().substr(4, 4);
            url = url.replace("PARAM4", MONTH);
            url = url.replace("PARAM5", YEAR);
            url = url.replace("PARAM6", USERNAME);
            window.open(url, '_blank');
        }
    }

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

    function dtRevApp(data) {
        var dt = [];
        for (var index = 0; index < data.length; ++index) {
            if (data[index].SREVAPP == undefined || data[index].SREVAPP == null || data[index].SREVAPP == '') {} else {
                dt.push(data[index]);
            }
        }
        return dt;
    }
</script>