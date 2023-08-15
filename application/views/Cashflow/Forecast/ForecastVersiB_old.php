<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link href="https://cdn.datatables.net/rowgroup/1.1.1/css/rowGroup.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/rowgroup/1.1.1/js/dataTables.rowGroup.min.js"></script>
<style>
    .dataTables_wrapper {
        position: relative;
        clear: both;
        width: auto;
        max-height : 400px;
        margin-left: 0px;
/*        border-bottom: 1px solid black;
        border-top: 1px solid black;
        border-left: 1px solid black;
        border-right: 1px solid black;
        background-color: #9D9C9D;*/
        /*zoom: 1;*/
    }
</style>
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
                    <div class="col my-auto">
                        <?php if ($DtUser2->USERACCESS != '100003') { ?>
                            <button id="btnAdd" type="button" class="btn btn-primary btn-sm" onclick="Add()"><i class="fa fa-plus"></i><span> Forecast</span></button>
                            <button id="btnAddAll" type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i><span> Forecast All</span></button>
                        <?php } ?>
                    </div>
                    <div class="col">
                        <label for="DEPARTMENT">Departement</label>
                        <select class="form-control" name="DEPARTMENT" id="DEPARTMENT">
                            <option value="" selected>All Department</option>
                            <?php
                            foreach ($DtDepartment as $values) {
                                echo '<option value=' . $values->DEPARTMENT . '>' . $values->DEPARTEMENTNAME . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <div class="form-row">
                            <div class="col">
                                <label for="CASHFLOWTYPE">Forecast Type</label>
                                <select class="form-control" name="CASHFLOWTYPE" id="CASHFLOWTYPE">
                                    <option value="" selected>All</option>
                                    <option value="0">Cash In</option>
                                    <option value="1">Cash Out</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="PERIOD">Period</label>
                                <input type="text" class="form-control" name="PERIOD" id="PERIOD" placeholder="MMM YYYY">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-0 table-responsive">
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
                        <th class="text-center align-middle" rowspan="3">Due Date</th>
                        <th class="text-center align-middle" rowspan="3">Amount Source</th>
                        <th class="text-center align-middle" rowspan="3">Amount Outstanding</th>
                        <th class="text-center" colspan="15">Week</th>
                    </tr>
                    <tr>
                        <th class="text-center W1" colspan="3">W1</th>
                        <th class="text-center W2" colspan="3">W2</th>
                        <th class="text-center W3" colspan="3">W3</th>
                        <th class="text-center W4" colspan="3">W4</th>
                        <th class="text-center W5" colspan="3">W5</th>
                    </tr>
                    <tr>
                        <th class="text-center">Request</th>
                        <th class="text-center">Adjusted</th>
                        <th class="text-center">Priority</th>
                        <th class="text-center">Request</th>
                        <th class="text-center">Adjusted</th>
                        <th class="text-center">Priority</th>
                        <th class="text-center">Request</th>
                        <th class="text-center">Adjusted</th>
                        <th class="text-center">Priority</th>
                        <th class="text-center">Request</th>
                        <th class="text-center">Adjusted</th>
                        <th class="text-center">Priority</th>
                        <th class="text-center">Request</th>
                        <th class="text-center">Adjusted</th>
                        <th class="text-center">Priority</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr role="row">
                        <th class="text-right align-middle" colspan="10" rowspan="2">Total Cash </th>
                        <th class="text-right">In  :</th>
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
        <?php } ?>
        <?php if ($DtUser2->USERACCESS == '100005' || $DtUser2->USERACCESS == '100003') { ?>
            <button type="button" id="RevAppDana" onclick="RevAppDana()" class="btn btn-info btn-sm m-l-5">Fund Revision or Approved</button>
            <!--            <button type="button" id="btnRevisi" onclick="Revisi()" class="btn btn-warning btn-sm m-l-5">Fund Revision</button>
                        <button type="button" id="btnApprove" onclick="SApprove()" class="btn btn-success btn-sm m-l-5">Fund Approved</button>-->
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
                <div class="table-responsive">
                    <table id="DtOutstanding"Document class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUploadDetail_info">
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
                                <th class="text-center">Amount Source</th>
                                <th class="text-center">Amount Outstanding</th>
                                <th class="text-center">Category</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="AddForecast()">Add</button>
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
                        <table id="DtRevAppDana"Document class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUploadDetail_info">
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
<!--<div class="modal fade" id="MRev">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Amount Revision</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="FEditRev" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="AMOUNTREVISION">Amount Revision *</label>
                            <input type="text" class="form-control text-right" name="AMOUNTREVISION" id="AMOUNTREVISION" data-type='currency' placeholder="Amount Revision" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="EditRev()">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>-->
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
<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var USERACCESS = "<?php echo $DtUser2->USERACCESS; ?>";
    var YEAR = "", MONTH = "", timeOutonKeyup = null;
    var PSTATUS = '0', LOCKS = '0', POUTSTAND = "", AMOUNTUSE = 0, idx, WAMOUNT, LAMOUNT, WPRIORITY;
    var table, table2, table3;
    var DtForecast = [], DtRevApp = [];
<?php if ($DtUser2->USERACCESS == '100005') { ?>
        var Disable = "hidden";
<?php } else { ?>
        var Disable = "disabled";
<?php } ?>
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var DtPriority = [
        {"DETAILCODE": "1", "DETAILNAME": "1"},
        {"DETAILCODE": "2", "DETAILNAME": "2"},
        {"DETAILCODE": "3", "DETAILNAME": "3"},
        {"DETAILCODE": "4", "DETAILNAME": "4"},
        {"DETAILCODE": "5", "DETAILNAME": "5"}
    ];
    var CashInIdx = 0, CashOutIdx = 0;
    var REQUESTW1, REQUESTW2, REQUESTW3, REQUESTW4, REQUESTW5, REQUESTIN = 0, REQUESTOUT = 0;
    var REQUESTINW1, REQUESTINW2, REQUESTINW3, REQUESTINW4, REQUESTINW5, REQUESTINTOT;
    var REQUESTOUTW1, REQUESTOUTW2, REQUESTOUTW3, REQUESTOUTW4, REQUESTOUTW5, REQUESTOUTTOT;
    var ADJSW1, ADJSW2, ADJSW3, ADJSW4, ADJSW5, ADJSIN = 0, ADJSOUT = 0;
    var ADJSINW1, ADJSINW2, ADJSINW3, ADJSINW4, ADJSINW5, ADJSINTOT;
    var ADJSOUTW1, ADJSOUTW2, ADJSOUTW3, ADJSOUTW4, ADJSOUTW5, ADJSOUTTOT;
    var REVISIONIN, REVISIONOUT, APPROVEDIN, APPROVEDOUT;
    var FCLOCK = "", FCSTATUS = "";
    var LOCKIN = 0, LOCKOUT = 0, STATUSIN = 0, STATUSOUT = 0;
    
//    Load Datatable Awal JSON.stringify(DtForecast)
    if (!$.fn.DataTable.isDataTable('#DtForecast')) {
        $('#DtForecast').DataTable({
            "aaData": DtForecast,
            "columns": [
                {
                    "data": "CASHFLOWTYPE"
                },
                {
                    "data": null,
                    "className": "text-center",
                    "orderable": false,
                    render: function (data, type, row, meta) {
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
                {"data": "COMPANYCODE"},
                {"data": "BUSINESSUNITCODE"},
                {"data": "DEPARTMENT"},
                {"data": "DOCREF"},
                {"data": "VENDORNAME"},
                {"data": "DOCNUMBER"},
                {"data": "DUEDATE"},
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
                    render: function (data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '0' && FCLOCK == '0' && USERACCESS != '100003') {
                            html = '<a href="javascript:void(0);" class="REQUESTW1" role="button" title="Request W1">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "ADJSW1",
                    "orderable": false,
                    "className": "text-right",
                    render: function (data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '1' && FCLOCK == '0' && USERACCESS != '100003') {
                            html = '<a href="javascript:void(0);" class="ADJSW1" role="button" title="Adjusted W1">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "PRIORITYW1",
                    "orderable": true,
                    render: function (data, type, row, meta) {
                        var html = data;
                        if (data == null || data == '' || data == undefined) {
                            data = 'Klik';
                        }
                        if (FCLOCK == '0' && USERACCESS != '100003') {
                            html = '<a href="javascript:void(0);" class="PRIORITYW1" role="button" title="Priority W1">' + data + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "REQUESTW2",
                    "orderable": false,
                    "className": "text-right",
                    render: function (data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '0' && FCLOCK == '0' && USERACCESS != '100003') {
                            html = '<a href="javascript:void(0);" class="REQUESTW2" role="button" title="Request W2">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "ADJSW2",
                    "orderable": false,
                    "className": "text-right",
                    render: function (data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '1' && FCLOCK == '0' && USERACCESS != '100003') {
                            html = '<a href="javascript:void(0);" class="ADJSW2" role="button" title="Adjusted W2">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "PRIORITYW2",
                    "orderable": true,
                    render: function (data, type, row, meta) {
                        var html = data;
                        if (data == null || data == '' || data == undefined) {
                            data = 'Klik';
                        }
                        if (FCLOCK == '0' && USERACCESS != '100003') {
                            html = '<a href="javascript:void(0);" class="PRIORITYW2" role="button" title="Priority W2">' + data + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "REQUESTW3",
                    "orderable": false,
                    "className": "text-right",
                    render: function (data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '0' && FCLOCK == '0' && USERACCESS != '100003') {
                            html = '<a href="javascript:void(0);" class="REQUESTW3" role="button" title="Request W3">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "ADJSW3",
                    "orderable": false,
                    "className": "text-right",
                    render: function (data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '1' && FCLOCK == '0' && USERACCESS != '100003') {
                            html = '<a href="javascript:void(0);" class="ADJSW3" role="button" title="Adjusted W3">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "PRIORITYW3",
                    "orderable": true,
                    render: function (data, type, row, meta) {
                        var html = data;
                        if (data == null || data == '' || data == undefined) {
                            data = 'Klik';
                        }
                        if (FCLOCK == '0' && USERACCESS != '100003') {
                            html = '<a href="javascript:void(0);" class="PRIORITYW3" role="button" title="Priority W3">' + data + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "REQUESTW4",
                    "orderable": false,
                    "className": "text-right",
                    render: function (data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '0' && FCLOCK == '0' && USERACCESS != '100003') {
                            html = '<a href="javascript:void(0);" class="REQUESTW4" role="button" title="Request W4">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "ADJSW4",
                    "orderable": false,
                    "className": "text-right",
                    render: function (data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '1' && FCLOCK == '0' && USERACCESS != '100003') {
                            html = '<a href="javascript:void(0);" class="ADJSW4" role="button" title="Adjusted W4">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "PRIORITYW4",
                    "orderable": true,
                    render: function (data, type, row, meta) {
                        var html = data;
                        if (data == null || data == '' || data == undefined) {
                            data = 'Klik';
                        }
                        if (FCLOCK == '0' && USERACCESS != '100003') {
                            html = '<a href="javascript:void(0);" class="PRIORITYW4" role="button" title="Priority W4">' + data + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "REQUESTW5",
                    "orderable": false,
                    "className": "text-right",
                    render: function (data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '0' && FCLOCK == '0' && USERACCESS != '100003') {
                            html = '<a href="javascript:void(0);" class="REQUESTW5" role="button" title="Request W5">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "ADJSW5",
                    "orderable": false,
                    "className": "text-right",
                    render: function (data, type, row, meta) {
                        var html = fCurrency(data);
                        if (FCSTATUS == '1' && FCLOCK == '0' && USERACCESS != '100003') {
                            html = '<a href="javascript:void(0);" class="ADJSW5" role="button" title="Adjusted W5">' + html + '</a>';
                        }
                        return html;
                    }
                },
                {
                    "data": "PRIORITYW5",
                    "orderable": true,
                    render: function (data, type, row, meta) {
                        var html = data;
                        if (data == null || data == '' || data == undefined) {
                            data = 'Klik';
                        }
                        if (FCLOCK == '0' && USERACCESS != '100003') {
                            html = '<a href="javascript:void(0);" class="PRIORITYW5" role="button" title="Priority W5">' + data + '</a>';
                        }
                        return html;
                    }
                }
            ],
//            "sScrollY": "300px",
//            "bScrollCollapse": true,
//            "sScrollX": true,
            "responsive": false,
            "bFilter": true,
            "bPaginate": false,
            "bLengthChange": false,
            "bInfo": false,
            "columnDefs": [
                {"visible": false, "targets": 0}
            ],
            orderFixed: [0, 'asc'],
            "rowGroup": {
                startRender: function (rows, group) {
                    var html = "";
                    if (group == '0') {
                        html = "Cash In";
                    } else {
                        html = "Cash Out";
                    }
                    return html;
                },
                endRender: function (rows, group) {
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                    };
                    REQUESTW1 = rows.data().pluck('REQUESTW1').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    REQUESTW2 = rows.data().pluck('REQUESTW2').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    REQUESTW3 = rows.data().pluck('REQUESTW3').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    REQUESTW4 = rows.data().pluck('REQUESTW4').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    REQUESTW5 = rows.data().pluck('REQUESTW5').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    ADJSW1 = rows.data().pluck('ADJSW1').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    ADJSW2 = rows.data().pluck('ADJSW2').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    ADJSW3 = rows.data().pluck('ADJSW3').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    ADJSW4 = rows.data().pluck('ADJSW4').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    ADJSW5 = rows.data().pluck('ADJSW5').reduce(function (a, b) {
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
                            .append('<td colspan="10">Total ' + label + '</td>')
                            .append('<td class="text-right">' + numFormat(REQUESTW1) + '</td>')
                            .append('<td class="text-right">' + numFormat(ADJSW1) + '</td>')
                            .append('<td></td>')
                            .append('<td class="text-right">' + numFormat(REQUESTW2) + '</td>')
                            .append('<td class="text-right">' + numFormat(ADJSW2) + '</td>')
                            .append('<td></td>')
                            .append('<td class="text-right">' + numFormat(REQUESTW3) + '</td>')
                            .append('<td class="text-right">' + numFormat(ADJSW3) + '</td>')
                            .append('<td></td>')
                            .append('<td class="text-right">' + numFormat(REQUESTW4) + '</td>')
                            .append('<td class="text-right">' + numFormat(ADJSW4) + '</td>')
                            .append('<td></td>')
                            .append('<td class="text-right">' + numFormat(REQUESTW5) + '</td>')
                            .append('<td class="text-right">' + numFormat(ADJSW5) + '</td>')
                            .append('<td></td>');
                },
                dataSrc: "CASHFLOWTYPE"
            },
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;
                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                };
                REQUESTINW1 = 0, REQUESTINW2 = 0, REQUESTINW3 = 0, REQUESTINW4 = 0, REQUESTINW5 = 0, REQUESTINTOT = 0;
                REQUESTOUTW1 = 0, REQUESTOUTW2 = 0, REQUESTOUTW3 = 0, REQUESTOUTW4 = 0, REQUESTOUTW5 = 0, REQUESTOUTTOT = 0;
                ADJSINW1 = 0, ADJSINW2 = 0, ADJSINW3 = 0, ADJSINW4 = 0, ADJSINW5 = 0, ADJSINTOT = 0;
                ADJSOUTW1 = 0, ADJSOUTW2 = 0, ADJSOUTW3 = 0, ADJSOUTW4 = 0, ADJSOUTW5 = 0, ADJSOUTTOT = 0;

                $.each(data, function (index, value) {
                    if (value['CASHFLOWTYPE'] == '0') {
                        REQUESTINW1 = REQUESTINW1 + intVal(value['REQUESTW1']);
                        REQUESTINW2 = REQUESTINW2 + intVal(value['REQUESTW2']);
                        REQUESTINW3 = REQUESTINW3 + intVal(value['REQUESTW3']);
                        REQUESTINW4 = REQUESTINW4 + intVal(value['REQUESTW4']);
                        REQUESTINW5 = REQUESTINW5 + intVal(value['REQUESTW5']);
                        ADJSINW1 = ADJSINW1 + intVal(value['ADJSW1']);
                        ADJSINW2 = ADJSINW2 + intVal(value['ADJSW2']);
                        ADJSINW3 = ADJSINW3 + intVal(value['ADJSW3']);
                        ADJSINW4 = ADJSINW4 + intVal(value['ADJSW4']);
                        ADJSINW5 = ADJSINW5 + intVal(value['ADJSW5']);
                    } else {
                        REQUESTOUTW1 = REQUESTOUTW1 + intVal(value['REQUESTW1']);
                        REQUESTOUTW2 = REQUESTOUTW2 + intVal(value['REQUESTW2']);
                        REQUESTOUTW3 = REQUESTOUTW3 + intVal(value['REQUESTW3']);
                        REQUESTOUTW4 = REQUESTOUTW4 + intVal(value['REQUESTW4']);
                        REQUESTOUTW5 = REQUESTOUTW5 + intVal(value['REQUESTW5']);
                        ADJSOUTW1 = ADJSOUTW1 + intVal(value['ADJSW1']);
                        ADJSOUTW2 = ADJSOUTW2 + intVal(value['ADJSW2']);
                        ADJSOUTW3 = ADJSOUTW3 + intVal(value['ADJSW3']);
                        ADJSOUTW4 = ADJSOUTW4 + intVal(value['ADJSW4']);
                        ADJSOUTW5 = ADJSOUTW5 + intVal(value['ADJSW5']);
                    }
                });
                REQUESTINTOT = REQUESTINW1 + REQUESTINW2 + REQUESTINW3 + REQUESTINW4 + REQUESTINW5;
                REQUESTOUTTOT = REQUESTOUTW1 + REQUESTOUTW2 + REQUESTOUTW3 + REQUESTOUTW4 + REQUESTOUTW5;
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
        table.on('click', ".REQUESTW1", function () {
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
        table.on('click', ".REQUESTW2", function () {
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
        table.on('click', ".REQUESTW3", function () {
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
        table.on('click', ".REQUESTW4", function () {
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
        table.on('click', ".REQUESTW5", function () {
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
        table.on('click', ".ADJSW1", function () {
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
        table.on('click', ".ADJSW2", function () {
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
        table.on('click', ".ADJSW3", function () {
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
        table.on('click', ".ADJSW4", function () {
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
        table.on('click', ".ADJSW5", function () {
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
        table.on('click', ".PRIORITYW1", function () {
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
        table.on('click', ".PRIORITYW2", function () {
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
        table.on('click', ".PRIORITYW3", function () {
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
        table.on('click', ".PRIORITYW4", function () {
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
        table.on('click', ".PRIORITYW5", function () {
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
    var ReloadTable = function () {
//        alert('test');
        CashInIdx = 0;
        CashOutIdx = 0;
        table.clear();
        table.rows.add(DtForecast);
        table.draw();
    };
    var ReloadTable1 = function () {
//        alert('test1');
        CashInIdx = 0;
        CashOutIdx = 0;
        table.clear();
        table.rows.add(DtForecast);
        table.draw();
    };

    var EditAmount = function () {
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
        if (PANELEXPAND) {
            $("[data-click=panel-expand]").click();
            PANELEXPAND = false;
        }
    };
    var EditPriority = function () {
//        var PriorityOld = DtForecast[idx][WAMOUNT];
//        if (PriorityOld != '' && PriorityOld != null) {
//            var delidx = window['Del' + WPRIORITY].indexOf(PriorityOld);
//            window['Del' + WPRIORITY].splice(delidx, 1);
//        }
        if ($("#PRIORITYWEEK").val() == "" || $("#PRIORITYWEEK").val() == null || $("#PRIORITYWEEK").val() == undefined) {
            DtForecast[idx][WAMOUNT] = null;
        } else {
            DtForecast[idx][WAMOUNT] = $("#PRIORITYWEEK").val();
        }
        ReloadTable();
        $('#MPriority').modal("hide");
        if (PANELEXPAND) {
            $("[data-click=panel-expand]").click();
            PANELEXPAND = false;
        }
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
        'change': function () {
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
                        USERNAME: USERNAME,
                        USERACCESS: USERACCESS
                    },
                    success: function (response, textStatus, jqXHR) {
                        $('#loader').removeClass('show');
                        if (response.status == 200) {
                            var data = response.result.data;
                            console.log(data)
                            console.log(USERACCESS)
                            DtForecast = data.DtForecast;
                            if (
                                    ($("#DEPARTMENT").val() == '' || $("#DEPARTMENT").val() == null || $("#DEPARTMENT").val() == undefined) ||
                                    ($("#CASHFLOWTYPE").val() == '' || $("#CASHFLOWTYPE").val() == null || $("#CASHFLOWTYPE").val() == undefined)) {
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
                            $.each(data['DtRevisi'], function (index, value) {
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
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Please Check Your Connection !!!');
                        $('#loader').removeClass('show');
                    }
                });
            }
        }
    });
    $('#DEPARTMENT').on({
        'change': function () {
            $('#PERIOD').change();
        }
    });
    $('#CASHFLOWTYPE').on({
        'change': function () {
            $('#PERIOD').change();
        }
    });
    var DisableButton = function () {
        if ((USERACCESS == '100003' || USERACCESS == '100005') && FCSTATUS != '2') {
            $('#RevAppDana').removeAttr(Disable);
        } else {
            $('#RevAppDana').attr(Disable, true);
        }
        $('#btnAdd').attr(Disable, true);
        $('#btnAddAll').attr(Disable, true);
        $('#btnSave').attr(Disable, true);
        $('#btnSubmit').attr(Disable, true);
        if (FCLOCK == '0' && FCSTATUS != '2') {
            $('#btnAdd').removeAttr(Disable);
            $('#btnAddAll').removeAttr(Disable);
            $('#btnSave').removeAttr(Disable);
            $('#btnSubmit').removeAttr(Disable);
        }
        ReloadTable1();
    };

//    Add Data Forecast
    var Add = function () {
        if ($('#PERIOD').val() == null || $('#PERIOD').val() == '' || $('#PERIOD').val() == undefined) {
            alert("Please, Choose Period First !!");
        } else if ($("#DEPARTMENT").val() == null || $('#DEPARTMENT').val() == '' || $('#DEPARTMENT').val() == undefined) {
            alert("Please, Choose Departmet First !!");
        } else if ($("#CASHFLOWTYPE").val() == null || $('#CASHFLOWTYPE').val() == '' || $('#CASHFLOWTYPE').val() == undefined) {
            alert("Please, Choose Forecast Type First !!");
        } else {
            $('#pil').removeAttr('checked');
            if (!$.fn.DataTable.isDataTable('#DtOutstanding')) {
                $('#DtOutstanding').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('Forecast/DtOutstanding') ?>",
                        "type": "POST",
                        "datatype": "JSON",
                        "data": function (d) {
                            d.MONTH = MONTH;
                            d.YEAR = YEAR;
                            d.POUTSTAND = POUTSTAND;
                            d.DEPARTMENT = $("#DEPARTMENT").val();
                            d.CASHFLOWTYPE = $("#CASHFLOWTYPE").val();
                            d.USERNAME = USERNAME;
                            d.USERACCESS = USERACCESS;
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
                            "orderable": false,
                            render: function (data, type, row, meta) {
                                return '<input type="checkbox" class="pils">';
                            }
                        },
                        {
                            "data": null,
                            "className": "text-center",
                            "orderable": false,
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {"data": "COMPANYCODE"},
                        {"data": "BUSINESSUNITCODE"},
                        {"data": "DOCTYPE"},
                        {"data": "DOCREF"},
                        {"data": "VENDORNAME"},
                        {"data": "DUEDATE"},
                        {"data": "DOCNUMBER"},
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
                            render: function (data, type, row, meta) {
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
                table2.on('change', '.pils', function () {
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
            $('#MOutstanding').modal({
                backdrop: 'static',
                keyboard: false
            });
        }
    };
    $('#pil').on('change', function () {
        if (this.checked) {
            $('#DtOutstanding .pils').prop("checked", true);
        } else {
            $('#DtOutstanding .pils').prop("checked", false);
        }
        $('#DtOutstanding .pils').change();
    });
    var AddForecast = function () {
        $.each(table2.data(), function (index, value) {
            if (value.ID == undefined || value.ID == null || value.ID == '') {
            } else {
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
        $('#MOutstanding').modal("hide");
    };
    $('#btnAddAll').on('click', function () {
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
                success: function (response, textStatus, jqXHR) {
                    $('#loader').removeClass('show');
                    $('#btnAdd').removeAttr('disabled');
                    $('#btnAddAll').removeAttr('disabled');
                    if (response.status == 200) {
                        $.each(response.result.data, function (index, value) {
                            if (value.ID == undefined || value.ID == null || value.ID == '') {
                            } else {
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
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#loader').removeClass('show');
                    $('#btnAdd').removeAttr('disabled');
                    $('#btnAddAll').removeAttr('disabled');
                    alert('Failed to get data !!');
                }
            });
        }
    });

//    Button Save dan Submit
    var Save = function (ACTION) {
        if (YEAR == null || YEAR == '' || YEAR == undefined) {
            alert("Please, Choose Period First !!");
        } else if (DtForecast.length <= 0) {
            alert('Detail Record Not Set !!!');
        } else if ($("#DEPARTMENT").val() == null || $('#DEPARTMENT').val() == '' || $('#DEPARTMENT').val() == undefined) {
            alert("Please, Choose Departmet First !!");
        } else {
            $('#loader').addClass('show');
            $('#btnSave').attr('disabled', true);
            $('#btnSubmit').attr('disabled', true);
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
                    REQUESTINTOT: REQUESTINTOT,
                    REQUESTOUTTOT: REQUESTOUTTOT,
                    ADJSINTOT: ADJSINTOT,
                    ADJSOUTTOT: ADJSOUTTOT,
                    ACTION: ACTION,
                    USERNAME: USERNAME
                },
                success: function (response, textStatus, jqXHR) {
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
                        ReloadTable();
                        DANAREVISI = '0';
                        $("#RDana").text("Fund Revision : 0.00");
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        location.reload();
                    } else {
                        alert(response.result.data);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#loader').removeClass('show');
                    console.info(e);
                    alert('Data Save Failed !!');
                    $('#btnSave').removeAttr('disabled');
                    $('#btnSubmit').removeAttr('disabled');
                }
            });
        }
    };
    var Submit = function () {
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
    var RevAppDana = function () {
        if (YEAR == null || YEAR == '' || YEAR == undefined) {
            alert("Please, Choose Period First !!");
        } else if (DtForecast.length <= 0) {
            alert('Detail Record Not Set !!!');
        } else {
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Forecast/DtRevAppDana'); ?>",
                data: {
                    YEAR: YEAR,
                    MONTH: MONTH,
                    USERNAME: USERNAME
                },
                success: function (response, textStatus, jqXHR) {
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
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Please Check Your Connection !!!');
                }
            });
        }
    };
    function LoadDtRevApp() {
        if (!$.fn.DataTable.isDataTable('#DtRevAppDana')) {
            $('#DtRevAppDana').DataTable({
                "aaData": DtRevApp,
                "columns": [
                    {
                        "data": "CASHFLOWTYPE"
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {"data": "DEPARTMENT"},
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
                        render: function (data, type, row, meta) {
                            var html = '', slabel;
                            if (row.LOCKS == '1') {
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
                        render: function (data, type, row, meta) {
                            var html = '', Lock = '';
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
                "columnDefs": [
                    {"visible": false, "targets": 0}
                ],
                orderFixed: [0, 'asc'],
                "rowGroup": {
                    startRender: function (rows, group) {
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
                    endRender: function (rows, group) {
                        var intVal = function (i) {
                            return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '') * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                        };
                        var ReqAmount = rows.data().pluck('AMOUNTREQUEST').reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        var AdjAmount = rows.data().pluck('AMOUNTADJS').reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        var RevAmount = rows.data().pluck('AMOUNTREVISI').reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        var AppAmount = rows.data().pluck('AMOUNTAPPROVE').reduce(function (a, b) {
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
            table3.on('click', ".AMOUNTREVISION", function () {
                $tr = $(this).closest('tr');
                idx = table3.row($tr).index();
                if (DtRevApp[idx].AMOUNTREVISI > 0) {
                    $('#FEditRev').parsley().reset();
                    $('#MRev .modal-title').text('Edit Amount Revision (' + DtRevApp[idx].DEPARTMENT + ')');
                    $('#AMOUNTREVISION').val(DtRevApp[idx].AMOUNTREVISI);
                    formatCurrency($('#AMOUNTREVISION'), "blur");
                    $('#MRev').on('hidden.bs.modal', function (event) {
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
            table3.on('click', ".STATUS", function () {
                $tr = $(this).closest('tr');
                idx = table3.row($tr).index();
                $("#SREVAPP").val(DtRevApp[idx].SREVAPP);
                $('#SREVAPP').change();
                $('#FSRev').parsley().reset();
                $('#MSRev .modal-title').text('Set Action (' + DtRevApp[idx].DEPARTMENT + ')');
                $('#MSRev').on('hidden.bs.modal', function (event) {
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
    var EditRev = function () {
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
        'change': function () {
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
                if (parseFloat(formatDesimal($('#AMOUNTREVISION').val())) > 0) {
                } else {
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
    var EditSRev = function () {
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
    var SaveRevApp = function () {
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
                    DATA: JSON.stringify(dt),
                    USERNAME: USERNAME
                },
                success: function (response) {
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
                error: function (e) {
                    $('#loader').removeClass('show');
                    console.info(e);
                    alert('Data Save Failed !!');
                    $("#MRevAppDana button[type='submit']").removeAttr('disabled');
                }
            });
        }
    };

    var Revisi = function () {
        if (YEAR == null || YEAR == '' || YEAR == undefined) {
            alert("Please, Choose Period First !!");
        } else if (DtForecast.length <= 0) {
            alert('Detail Record Not Set !!!');
        } else if ($("#DEPARTMENT").val() == null || $('#DEPARTMENT').val() == '' || $('#DEPARTMENT').val() == undefined) {
            alert("Please, Choose Departmet First !!");
        } else {
            $('#FRevisi').parsley().reset();
            $('#AMOUNTREVISI').val('0');
            formatCurrency($('#AMOUNTREVISI'), "blur");
            $('#MRevisi').modal({
                backdrop: 'static',
                keyboard: false
            });
        }
    };
    var SaveRevisi = function () {
        if ($('#FRevisi').parsley().validate()) {
            $('#loader').addClass('show');
            $('#MRevisi button[type="submit"]').attr('disabled', true);
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Forecast/SaveRA'); ?>",
                data: {
                    YEAR: YEAR,
                    MONTH: MONTH,
                    DEPARTMENT: $("#DEPARTMENT").val(),
                    REQUESTWTOTAL: REQUESTWTOTAL,
                    ADJSWTOTAL: ADJSWTOTAL,
                    AMOUNTREVISI: formatDesimal($('#AMOUNTREVISI').val()),
                    RASTATUS: 1,
                    USERNAME: USERNAME
                },
                success: function (response) {
                    $('#loader').removeClass('show');
                    $('#MRevisi button[type="submit"]').removeAttr('disabled');
                    if (response.status == 200) {
                        alert(response.result.data);
                        $('#PERIOD').val('');
                        YEAR = '';
                        MONTH = '';
                        DtForecast = [];
                        ReloadTable();
                        DANAREVISI = '0';
                        $("#RDana").text("Fund Revision : 0.00");
                        $('#MRevisi').modal("hide");
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
                    $('#MRevisi button[type="submit"]').removeAttr('disabled');
                }
            });
        }
    };
    var SApprove = function () {
        if (YEAR == null || YEAR == '' || YEAR == undefined) {
            alert("Please, Choose Period First !!");
        } else if (DtForecast.length <= 0) {
            alert('Detail Record Not Set !!!');
        } else if ($("#DEPARTMENT").val() == null || $('#DEPARTMENT').val() == '' || $('#DEPARTMENT').val() == undefined) {
            alert("Please, Choose Departmet First !!");
        } else {
            $('#loader').addClass('show');
            $('#btnApprove').attr('disabled', true);
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Forecast/SaveRA'); ?>",
                data: {
                    YEAR: YEAR,
                    MONTH: MONTH,
                    DEPARTMENT: $("#DEPARTMENT").val(),
                    REQUESTWTOTAL: REQUESTWTOTAL,
                    ADJSWTOTAL: ADJSWTOTAL,
                    AMOUNTREVISI: 0,
                    RASTATUS: 2,
                    USERNAME: USERNAME
                },
                success: function (response) {
                    $('#loader').removeClass('show');
                    $('#btnApprove').removeAttr('disabled');
                    if (response.status == 200) {
                        alert(response.result.data);
                        $('#PERIOD').val('');
                        YEAR = '';
                        MONTH = '';
                        DtForecast = [];
                        ReloadTable();
                        $("#RDana").text("Fund Revision : 0.00");
                        $('#MRevisi').modal("hide");
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
                    $('#btnApprove').removeAttr('disabled');
                }
            });
        }
    };
//    Function - Function Formater Numberic
    $("input[data-type='currency']").on({
        keyup: function () {
            formatCurrency($(this));
        },
        blur: function () {
            formatCurrency($(this), "blur");
        }
    });
    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
            if (data[index].SREVAPP == undefined || data[index].SREVAPP == null || data[index].SREVAPP == '') {
            } else {
                dt.push(data[index]);
            }
        }
        return dt;
    }
</script>