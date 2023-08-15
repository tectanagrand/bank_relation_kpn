<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://nightly.datatables.net/buttons/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://nightly.datatables.net/buttons/js/buttons.html5.min.js"></script>
<style type="text/css">
    .dt-button {
        transition-duration: 0.4s;
        background-color: #76b6d6;
    }

    .dt-button:hover {
        background-color: #138496;
        color: #138496;
    }
</style>
<!--<link href="./assets/css/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<script src="./assets/js/datetime/bootstrap-datetimepicker.min.js"></script>-->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Report Outstanding & Aging Invoice</li>
</ol>
<h1 class="page-header">Report Outstanding & Aging Invoice</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Report Outstanding & Aging Invoice</h4>
    </div>
    <div class="panel-body">
        <form id="FVIEW" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-row">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
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
                            </div>
                            <div class="col">
                                <label for="COMPANYGROUP">Group</label>
                                <select class="form-control mkreadonly" name="COMPANYGROUP" id="COMPANYGROUP">
                                    <option value="">All</option>
                                    <option value="CMT">CEMENT</option><option value="MOTIVE">MOTIVE</option><option value="PLT">PLANTATION</option><option value="PROPERTY">PROPERTY</option><option value="WOOD">WOOD</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="SUBGROUP">Subgroup</label>
                                <select class="form-control" name="COMPANYSUBGROUP" id="COMPANYSUBGROUP">
                                    <option value="" selected="">All</option>
                                    <option value="UPSTREAM">UPSTREAM</option>
                                    <option value="DOWNSTREAM">DOWNSTREAM</option></select>
                            </div>
                            <div class="col">
                                <label for="COMPANY">Company</label>
                                <select class="form-control" name="COMPANY" id="COMPANY" required>
                                <option value="" selected disabled> Company</option>
                                <?php
                                foreach ($DtCompany as $values) {
                                    echo '<option value=' . $values->ID . '>' . $values->COMPANYNAME . '</option>';
                                }
                                ?>
                            </select>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="DOCDATETO">Document Date Until</label>
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
                            <div class="col">
                                <div class="form-group">
                                    <label for="PAIDDATE">Paid Date Until</label>
                                    <div class="input-group date" id="PAIDDATE1">
                                        <input type="text" class="form-control" name="PAIDDATE" id="PAIDDATE" placeholder="MM/DD/YYYY" required />
                                        <div class="input-group-addon input-group-append">
                                            <div class="input-group-text">
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="OVERDATE">Over Due Date</label>
                                    <div class="input-group date" id="OVERDATE1">
                                        <input type="text" class="form-control" name="OVERDATE" id="OVERDATE" placeholder="MM/DD/YYYY" required />
                                        <div class="input-group-addon input-group-append">
                                            <div class="input-group-text">
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col my-auto">
                            <button type="button" class="btn btn-info" style="padding: 3px 10px;" onclick="Show()"><i class="fa fa-eye"></i> Show</button>
                            <button type="button" class="btn btn-primary" style="padding: 3px 10px;" onclick="VSAging()"><i class="fa fa-cog"></i> Setting Aging</button>
                            <!-- <button onclick="VExport()" class="btn btn-success" style="padding: 3px 10px;"><i class="fa fa-file-excel"></i> Export</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="row ml-0 mr-0 mb-0 mt-2 table-responsive">
            <table id="DtReportOSnAging" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                <thead>
                    <tr role="row">
                        <th class="text-center align-middle" rowspan="2" style="max-width: 30px;">No</th>
                        <th class="text-center align-middle" rowspan="2">Department</th>
                        <th class="text-center align-middle" rowspan="2">Company</th>
                        <th class="text-center align-middle" rowspan="2">Business Unit</th>
                        <th class="text-center align-middle" rowspan="2">Vendor</th>
                        <th class="text-center align-middle" colspan="2">Document</th>
                        <th class="text-center align-middle" rowspan="2">AP/AR Date</th>
                        <th class="text-center align-middle" colspan="3">Amount</th>
                        <th class="text-center align-middle" rowspan="2">TOP</th>
                        <th class="text-center align-middle" rowspan="2">Due Date</th>
                        <th class="text-center align-middle" rowspan="2">Overdue (Day)</th>
                        <th class="text-center align-middle" colspan="8">Aging</th>
                    </tr>
                    <tr>
                        <th class="text-center align-middle">PO/SO/STO/SPO/PDO</th>
                        <th class="text-center align-middle">Invoice AP/AR</th>
                        <th class="text-center align-middle" style="min-width: 90px;">Invoice</th>
                        <th class="text-center align-middle" style="min-width: 90px;">Paid</th>
                        <th class="text-center align-middle" style="min-width: 90px;">OS</th>
                        <th class="text-center align-middle" style="min-width: 90px;">Current</th>
                        <th class="text-center align-middle" style="min-width: 90px;" id="LS1">1 - 30</th>
                        <th class="text-center align-middle" style="min-width: 90px;" id="LS2">31 - 60</th>
                        <th class="text-center align-middle" style="min-width: 90px;" id="LS3">61 - 90</th>
                        <th class="text-center align-middle" style="min-width: 90px;" id="LS4">91 - 120</th>
                        <th class="text-center align-middle" style="min-width: 90px;" id="LS5">121 - 180</th>
                        <th class="text-center align-middle" style="min-width: 90px;" id="LS6">180 - 365</th>
                        <th class="text-center align-middle" style="min-width: 90px;" id="LS7">> 365</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr role="row">
                        <th class="text-right" colspan="8">Total</th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right" colspan="3"></th>
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
    </div>
</div>
<!-- Modal Setting Aging -->
<div class="modal fade" id="MAging">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Setting Aging</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            </div>
            <form id="FAging" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="COLFROM1">Column 1 Again From</label>
                                <input type="number" class="form-control" name="COLFROM1" id="COLFROM1" placeholder="Column 1 Again From" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="COLTO1">Column 1 Again To</label>
                                <input type="number" class="form-control" name="COLTO1" id="COLTO1" placeholder="Column 1 Again To" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="COLFROM2">Column 2 Again From</label>
                                <input type="number" class="form-control" name="COLFROM2" id="COLFROM2" placeholder="Column 2 Again From" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="COLTO2">Column 2 Again To</label>
                                <input type="number" class="form-control" name="COLTO2" id="COLTO2" placeholder="Column 2 Again To" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="COLFROM3">Column 3 Again From</label>
                                <input type="number" class="form-control" name="COLFROM3" id="COLFROM3" placeholder="Column 3 Again From" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="COLTO3">Column 3 Again To</label>
                                <input type="number" class="form-control" name="COLTO3" id="COLTO3" placeholder="Column 3 Again To" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="COLFROM4">Column 4 Again From</label>
                                <input type="number" class="form-control" name="COLFROM4" id="COLFROM4" placeholder="Column 4 Again From" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="COLTO4">Column 4 Again To</label>
                                <input type="number" class="form-control" name="COLTO4" id="COLTO4" placeholder="Column 4 Again To" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="COLFROM5">Column 5 Again From</label>
                                <input type="number" class="form-control" name="COLFROM5" id="COLFROM5" placeholder="Column 5 Again From" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="COLTO5">Column 5 Again To</label>
                                <input type="number" class="form-control" name="COLTO5" id="COLTO5" placeholder="Column 5 Again To" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="COLFROM6">Column 6 Again From</label>
                                <input type="number" class="form-control" name="COLFROM6" id="COLFROM6" placeholder="Column 6 Again From" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="COLTO6">Column 6 Again To</label>
                                <input type="number" class="form-control" name="COLTO6" id="COLTO6" placeholder="Column 6 Again To" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" onclick="SetDefault()">Set Default</button>
                    <button type="submit" class="btn btn-primary" onclick="SaveAging()">Save & Reload</button>
                </div>
            </form>
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
                                <label for="EDEPARTMENT">Departement</label>
                                <select class="form-control" name="EDEPARTMENT" id="EDEPARTMENT">
                                    <option value="" selected>All Department</option>
                                    <?php
                                    foreach ($DtDepartment as $values) {
                                        echo '<option value=' . $values->DEPARTMENT . '>' . $values->DEPARTEMENTNAME . '</option>';
                                    }
                                    ?>
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
                                <div class="input-group date" id="DOCDATETO2">
                                    <input type="text" class="form-control" name="DOCDATETO" id="DOCDATETO" placeholder="MM/DD/YYYY" required />
                                    <div class="input-group-addon input-group-append">
                                        <div class="input-group-text">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-12">
                            <div class="form-group">
                                <label for="DOCDATETO">Document Date Until</label>
                                <div class="input-group date" id="DOCDATETO2">
                                    <input type="text" class="form-control" name="DOCDATETO" id="DOCDATETO" placeholder="MM/DD/YYYY" required />
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
                                <label for="PAIDDATE">Paid Date Until</label>
                                <div class="input-group date" id="PAIDDATE2">
                                    <input type="text" class="form-control" name="PAIDDATE" id="PAIDDATE" placeholder="MM/DD/YYYY" required />
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
                                <label for="OVERDATE">Over Due Date</label>
                                <div class="input-group date" id="OVERDATE2">
                                    <input type="text" class="form-control" name="OVERDATE" id="OVERDATE" placeholder="MM/DD/YYYY" required />
                                    <div class="input-group-addon input-group-append">
                                        <div class="input-group-text">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
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
<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var table, DEPARTMENT = "",
        DOCDATETO = "",
        PAIDDATE = "",
        OVERDATE = "", COMPANYGROUP = "", COMPANYSUBGROUP = "", COMPANY = "";
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
    var VALUE1 = 30,
        VALUE2 = 31,
        VALUE3 = 60,
        VALUE4 = 61,
        VALUE5 = 90,
        VALUE6 = 91,
        VALUE7 = 120,
        VALUE8 = 121,
        VALUE9 = 180,
        VALUE10 = 181,
        VALUE11 = 365;
    $("#COLFROM1").val(1);
    $("#COLTO1").val(VALUE1);
    $("#COLFROM2").val(VALUE2);
    $("#COLTO2").val(VALUE3);
    $("#COLFROM3").val(VALUE4);
    $("#COLTO3").val(VALUE5);
    $("#COLFROM4").val(VALUE6);
    $("#COLTO4").val(VALUE7);
    $("#COLFROM5").val(VALUE8);
    $("#COLTO5").val(VALUE9);
    $("#COLFROM6").val(VALUE10);
    $("#COLTO6").val(VALUE11);

    $(document).ready(function(e) {
        $('button.dt-button').addClass('btn');
        $('button.dt-button').addClass('btn-primary');
    });

    //    Load Data Awal
    if (!$.fn.DataTable.isDataTable('#DtReportOSnAging')) {
        table = $("#DtReportOSnAging").DataTable({
            "processing": true,
            "dom": "lBfrtip",
            "serverSide":true,
                "buttons": [{
                    extend: "excel",
                    className: "btn-xs btn-green",
                    title: 'Report Os Aging'
                }],
            "lengthMenu": [ [10, 25, 50, 100], [10, 25, 50, 100] ],
            "ajax": {
                "url": "<?php echo site_url("Report/GetROSnAging"); ?>",
                "type": "POST",
                "datatype": "JSON",
                "data": function(d) {
                    d.DEPARTMENT = DEPARTMENT;
                    d.COMPANYGROUP = COMPANYGROUP;
                    d.COMPANYSUBGROUP = COMPANYSUBGROUP;
                    d.COMPANY = COMPANY;
                    d.DOCDATETO = DOCDATETO;
                    d.PAIDDATE = PAIDDATE;
                    d.OVERDATE = OVERDATE;
                    d.VALUE1 = VALUE1;
                    d.VALUE2 = VALUE2;
                    d.VALUE3 = VALUE3;
                    d.VALUE4 = VALUE4;
                    d.VALUE5 = VALUE5;
                    d.VALUE6 = VALUE6;
                    d.VALUE7 = VALUE7;
                    d.VALUE8 = VALUE8;
                    d.VALUE9 = VALUE9;
                    d.VALUE10 = VALUE10;
                    d.VALUE11 = VALUE11;
                    d.USERNAME = USERNAME;
                },
                "dataSrc": function(ext) {
                    if (ext.status == 200) {
                                // sendAll = ext.result.data.data;
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
                    "data": "VENDORNAME"
                },
                {
                    "data": "DOCREF"
                },
                {
                    "data": "DOCNUMBER"
                },
                {
                    "data": "DOCDATE",
                    "className": "text-center"
                },
                {
                    "data": "AMOUNTDOCUMNET",
                    "className": "text-right",
                    "render": $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "AMOUNTPAID",
                    "className": "text-right",
                    "render": $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "AMOUNTOS",
                    "className": "text-right",
                    "render": $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "PAYTERM",
                    "className": "text-right"
                },
                {
                    "data": "DUEDATE",
                    "className": "text-center"
                },
                {
                    "data": "OVERDUE",
                    "className": "text-right"
                },
                {
                    "data": "LCURRENT",
                    "className": "text-right",
                    "render": $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "LOS1",
                    "className": "text-right",
                    "render": $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "LOS2",
                    "className": "text-right",
                    "render": $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "LOS3",
                    "className": "text-right",
                    "render": $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "LOS4",
                    "className": "text-right",
                    "render": $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "LOS5",
                    "className": "text-right",
                    "render": $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "LOS6",
                    "className": "text-right",
                    "render": $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "LOS7",
                    "className": "text-right",
                    "render": $.fn.dataTable.render.number(',', '.', 2)
                }
            ],
            deferRender: true,
            scrollY: 500,
            scrollX: true,
            scrollCollapse: true,
            scroller: true,
            "bFilter": true,
            "bPaginate": true,
            "bLengthChange": true,
            "bInfo": false,
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };
                AMOUNTDOCUMNET = api.column(8).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                AMOUNTPAID = api.column(9).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                AMOUNTOS = api.column(10).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                ACURRENT = api.column(14).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                ALOS1 = api.column(15).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                ALOS2 = api.column(16).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                ALOS3 = api.column(17).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                ALOS4 = api.column(18).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                ALOS5 = api.column(19).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                ALOS6 = api.column(20).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                ALOS7 = api.column(21).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                $(api.column(8).footer()).html(numFormat(AMOUNTDOCUMNET));
                $(api.column(9).footer()).html(numFormat(AMOUNTPAID));
                $(api.column(10).footer()).html(numFormat(AMOUNTOS));
                $(api.column(14).footer()).html(numFormat(ACURRENT));
                $(api.column(15).footer()).html(numFormat(ALOS1));
                $(api.column(16).footer()).html(numFormat(ALOS2));
                $(api.column(17).footer()).html(numFormat(ALOS3));
                $(api.column(18).footer()).html(numFormat(ALOS4));
                $(api.column(19).footer()).html(numFormat(ALOS5));
                $(api.column(20).footer()).html(numFormat(ALOS6));
                $(api.column(21).footer()).html(numFormat(ALOS7));
            }
        });
    }
    var ReloadData = function() {
        table.ajax.reload();
    };

    $('#DOCDATETO1').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "format": "mm/dd/yyyy"
    });
    $('#PAIDDATE1').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "format": "mm/dd/yyyy"
    });
    $('#OVERDATE1').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "format": "mm/dd/yyyy"
    });
    $("#DOCDATETO1").datepicker('setDate', tgl);
    $("#PAIDDATE1").datepicker('setDate', tgl);
    $("#OVERDATE1").datepicker('setDate', tgl);

    var Show = function() {
        if (
            ($('#DOCDATETO').val() == '' || $('#DOCDATETO').val() == null || $('#DOCDATETO').val() == undefined) ||
            ($('#PAIDDATE').val() == '' || $('#PAIDDATE').val() == null || $('#PAIDDATE').val() == undefined) ||
            ($('#OVERDATE').val() == '' || $('#OVERDATE').val() == null || $('#OVERDATE').val() == undefined)
        ) {
            alert("Document Date, Paid Date, And Over Date can't be empty !!");
        } else {
            var HDepart = "";
            if ($('#DEPARTMENT').val() == '' || $('#DEPARTMENT').val() == null || $('#DEPARTMENT').val() == undefined) {
                HDepart = "All Department";
            }
            $(".panel-title").text('Report Outstanding & Aging Invoice, Filter By Department : ' + HDepart + ', Document Date Until: ' + $('#DOCDATETO').val() +
                ', Paid Date: ' + $('#PAIDDATE').val() + ', Over Due Date: ' + $('#OVERDATE').val()
            );
            DEPARTMENT = $('#DEPARTMENT').val();
            COMPANYGROUP = $('#COMPANYGROUP').val();
            COMPANYSUBGROUP = $('#COMPANYSUBGROUP').val();
            COMPANY   = $('#COMPANY').val();
            DOCDATETO = ConvertYYYYMMDD($('#DOCDATETO').val());
            PAIDDATE = ConvertYYYYMMDD($('#PAIDDATE').val());
            OVERDATE = ConvertYYYYMMDD($('#OVERDATE').val());
            ReloadData();
        }
    };
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
    var SetDefault = function() {
        $("#COLFROM1").val(1);
        $("#COLTO1").val(30);
        $("#COLFROM2").val(31);
        $("#COLTO2").val(60);
        $("#COLFROM3").val(61);
        $("#COLTO3").val(90);
        $("#COLFROM4").val(91);
        $("#COLTO4").val(120);
        $("#COLFROM5").val(121);
        $("#COLTO5").val(180);
        $("#COLFROM6").val(181);
        $("#COLTO6").val(365);
    };
    var VSAging = function() {
        $('#FAging').parsley().reset();
        $("#MAging").modal({
            backdrop: 'static',
            keyboard: false
        });
    };
    $("#COLTO1").on({
        'change': function() {
            $("#COLFROM2").val(parseInt(this.value) + 1);
        }
    });
    $("#COLTO2").on({
        'change': function() {
            $("#COLFROM3").val(parseInt(this.value) + 1);
        }
    });
    $("#COLTO3").on({
        'change': function() {
            $("#COLFROM4").val(parseInt(this.value) + 1);
        }
    });
    $("#COLTO4").on({
        'change': function() {
            $("#COLFROM5").val(parseInt(this.value) + 1);
        }
    });
    $("#COLTO5").on({
        'change': function() {
            $("#COLFROM6").val(parseInt(this.value) + 1);
        }
    });
    var SaveAging = function() {
        if ($('#FAging').parsley().validate()) {
            VALUE1 = $("#COLTO1").val();
            VALUE2 = $("#COLFROM2").val();
            VALUE3 = $("#COLTO2").val();
            VALUE4 = $("#COLFROM3").val();
            VALUE5 = $("#COLTO3").val();
            VALUE6 = $("#COLFROM4").val();
            VALUE7 = $("#COLTO4").val();
            VALUE8 = $("#COLFROM5").val();
            VALUE9 = $("#COLTO5").val();
            VALUE10 = $("#COLFROM6").val();
            VALUE11 = $("#COLTO6").val();
            $("#LS1").text($("#COLFROM1").val() + " - " + $("#COLTO1").val());
            $("#LS2").text($("#COLFROM2").val() + " - " + $("#COLTO2").val());
            $("#LS3").text($("#COLFROM3").val() + " - " + $("#COLTO3").val());
            $("#LS4").text($("#COLFROM4").val() + " - " + $("#COLTO4").val());
            $("#LS5").text($("#COLFROM5").val() + " - " + $("#COLTO5").val());
            $("#LS6").text($("#COLFROM6").val() + " - " + $("#COLTO6").val());
            $("#LS7").text("> " + $("#COLTO6").val());
            ReloadData();
            $("#MAging").modal("hide");
        }
    };

    var VExport = function() {
        $('#FExport').parsley().reset();
        $("#MExport").modal({
            backdrop: 'static',
            keyboard: false
        });
    };

    // $('#DOCDATETO2').datepicker({
    //     "autoclose": true,
    //     "todayHighlight": true,
    //     "format": "mm/dd/yyyy"
    // });
    // $('#PAIDDATE2').datepicker({
    //     "autoclose": true,
    //     "todayHighlight": true,
    //     "format": "mm/dd/yyyy"
    // });
    // $('#OVERDATE2').datepicker({
    //     "autoclose": true,
    //     "todayHighlight": true,
    //     "format": "mm/dd/yyyy"
    // });
    // $("#DOCDATETO2").datepicker('setDate', tgl);
    // $("#PAIDDATE2").datepicker('setDate', tgl);
    // $("#OVERDATE2").datepicker('setDate', tgl);

    $('#DOCDATEFROM1').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "format": "mm/dd/yyyy"
    });
    $('#DOCDATETO2').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "format": "mm/dd/yyyy"
    });
    $("#DOCDATEFROM1").datepicker('setDate', tgl);
    $("#DOCDATETO2").datepicker('setDate', tgl);

    var Export = function(type) {
        if ($('#FExport').parsley().validate()) {
            var url = "<?php echo site_url('Process/OSnAgingExport'); ?>?type=PARAM1&DOCDATEFROM=PARAM2&DOCDATETO=PARAM3&DEPARTMENT=PARAM4&USERNAME=PARAM5";
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
</script>