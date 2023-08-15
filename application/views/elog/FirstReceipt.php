<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">E-Log</li>
</ol>
<h1 class="page-header">E-Log First Receipt</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">E-Log </h4>
    </div>
    <div class="panel-body">
        <?php if (empty($_GET)) { ?>
            <div class="row">
                <div class="col-md-4 pull-left">
                    <?php if ($ACCESS['ADDS'] == 1) { ?>
                        <button onclick="Add()" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> Add Data</button> 
                        <!-- <button class="btn btn-sm btn-info">Under Maintenance, silakan pakai fitur upload first receipt</button> -->
                        
                    <?php } ?>
                </div>
                <div class="col-md-4">
                    <!-- <label for="PERIOD">Period</label> -->
                        <input type="text" class="form-control" name="PERIOD" id="PERIOD" autocomplete="off" placeholder="period">
                </div>
                <div>
                    <button id="btnExport" type="button" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i><span> Export</span></button>
                </div>
                <!-- <div class="col-md-4 pull-right">
                    <div class="input-group mt-4">
                        <input type="text" id="search" name="search" class="form-control" placeholder="Cari.." >
                    </div>
                </div> -->
            </div>
            <div class="row m-0 table-responsive">
                <table id="DtElog" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DtElog_info" style="width: 100%;">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc" style="width: 30px;">No</th>
                            <th class="text-center sorting">Company</th>
                            <!-- <th class="text-center sorting_disabled">Receipt Doc</th> -->
                            <th class="text-center sorting_disabled">Invoice Vendor</th>
                            <th class="text-center sorting_disabled">No. PO/SPO/Memo</th>
                            <th class="text-center sorting_disabled">Vendor</th>
                            <th class="text-center sorting_disabled">Currency</th>
                            <th class="text-center sorting_disabled">Amount</th>
                            <th class="text-center sorting_disabled">Notes</th>
                            <th class="text-center sorting">Created</th>
                            <!-- <th class="text-center sorting">Transaction Method</th> -->
                            <!-- <th class="text-center sorting">Status</th> -->
                            <th class="text-center sorting_disabled" aria-label="Action"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        <?php } else { ?>
            <div class="table-responsive">
                      <table class="table table-bordered table-striped" id="DtFirst" style="">
                            <thead>                  
                              <tr>
                                <th style="width: 3%;">No</th>
                                <th style="width: 15%;">Invoice Vendor</th>
                                <th style="width: 15%;">No. PO/SPO/Memo</th>
                                <th style="width: 10%;">Vendor</th>
                                <th style="width: 10%;">Company</th>
                                <th style="width: 10%;">Currency</th>
                                <th style="width: 20%;">Basic Amount</th>
                                <th style="width: 30%;">Notes</th>
                                <th >Attachment</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php $no = 0; $lastValue = 0; ?>
                                <tr id="rowid<?php echo $no ?>">
                                  <td>
                                    <input class="form-control" type="text" name="no_urut[]" id="no_urut1"  value="1">
                                  </td>
                                  <td>
                                    <input class="form-control" type="text" name="INVOICE_CODE[]" id="INVOICE_CODE1"  >
                                  </td>
                                  <td>
                                    <input class="form-control" type="text" name="NO_PO[]" id="NO_PO1"  >
                                  </td>
                                  <td>
                                    <select class="form-control vendor" id="VENDOR1" name="VENDOR[]" >
                                        <option disabled selected>Select</option>
                                    </select>
                                  </td>
                                  <td>
                                    <select class="form-control mkreadonly" name="COMPANY[]" id="COMPANY1">
                                        <option selected="">Select</option>
                                        <?php
                                        foreach ($DtCompany as $values) {
                                            echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                        }
                                        ?>
                                    </select>
                                  </td>
                                  <td>
                                    <select class="form-control" name="CURRENCY[]" id="CURRENCY1">
                                        <option  disabled selected>Choose</option>
                                        <?php
                                        foreach ($DtCurrency as $values) {
                                            echo '<option value=' . $values->DETAILID . '>' . $values->DETAILNAME . '</option>';
                                        }
                                        ?>
                                    </select>
                                  </td>
                                  <td><input class="form-control" data-type='currency' type="text" name="AMOUNT[]" id="AMOUNT1" ></td>
                                  <td><input type="text" class="form-control" name="NOTES[]" id="NOTES1" /></td>
                                  <!-- <td><input type="file" id="myfile" name="myfile" multiple></td> -->
                                  <td><a class="btn btn-danger removeRow" data-url="<?php echo site_url('Elog/deleteRow')?>" data-rowid="<?php echo $no ?>" data-id=""><i class="fas fa-times"></i></a></td>
                                </tr>
                              <?php $no++; $lastValue=$no; ?>
                            </tbody>
                            <!-- <?php echo $lastValue?> -->
                            <tfoot id="tfoot" data-value="<?php echo $lastValue?>">
                                <tr>
                                    <td>
                                    <button type="button" class="btn btn-info" id="addNewRow"><i class="fa fa-plus"></i></button>
                                    </td>
                                </tr>
                            </tfoot>
                          </table>
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
                            <div class="form-group col-md-3">
                                <label for="fcname">Current Company <span id="compnow" style="color:red"></span></label>
                                <select class="form-control mkreadonly" name="VCOMPANY" id="VCOMPANY">
                                        <option selected=""></option>
                                        <?php
                                        foreach ($DtCompany as $values) {
                                            echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                        }
                                        ?>
                                    </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="fcname">Invoice Code *</label>
                                <input type="text" class="form-control VINVOICE_CODE" id="VINVOICE_CODE" name="INVOICE_CODE">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="fcname">NO PO *</label>
                                <input type="text" class="form-control NO_PO" id="VNO_PO" name="VNO_PO">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="fcname">Current Vendor <span id="vendornow" style="color:red"></span></label>
                            </br>
                                <select class="form-control" id="VENDORchange" name="VENDORchange" >
                                        <option disabled selected>Select</option>
                                    </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="description"> Amount *</label>
                                <input type="text" data-type='currency' name="VAMOUNT" id="VAMOUNT" class="form-control">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="fcname">Current Currency <span id="currnow" style="color:red"></span></label>
                                 <select class="form-control" name="VCURRENCY" id="VCURRENCY">
                                        <option  disabled selected></option>
                                        <?php
                                        foreach ($DtCurrency as $values) {
                                            echo '<option value=' . $values->DETAILID . '>' . $values->DETAILNAME . '</option>';
                                        }
                                        ?>
                                    </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="description"> Notes *</label>
                                <input type="text" name="VNOTES" id="VNOTES" class="form-control">
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" onclick="UpdateFR()">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="MUpload">
        <div class="modal-dialog" style="max-width: 95%  !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Attach Files to <span id="spanPo"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="FView" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                        <div class="modal-body">
                            <div class="col-md-10">
                            <div class="note note-yellow m-b-10">
                                <div class="note-icon f-s-20">
                                    <i class="fa fa-lightbulb fa-2x"></i>
                                </div>
                                <div class="note-content">
                                    <h4 class="m-t-5 m-b-5 p-b-2">Upload Notes</h4>
                                    <ul class="m-b-5 p-l-20">
                                        <li>The maximum file size for uploads in this transaction is <strong> 5 MB</strong>.</li>
                                        <li>Only Accept files (<strong>zip</strong>) are allowed in this Upload.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="row mt-2 fileupload-buttonbar">
                                    <div class="col-md-4">
                                        <label for="address">Attachment *</label>
                                        <!-- <span class="btn btn-primary fileinput-button m-r-3"> -->
                                            <!--<i class="fa fa-plus"></i>-->
                                            <!-- <span>Browse File</span> -->
                                            <input type="file" class="myfile" id="myfile"  data-max-size="5120" accept=".zip" onchange="filesChange(this)">
                                        <!-- </span> -->
                                    </div>
                                </div>

                                <div class="row m-0 table-responsive">
                                    <table id="DtUpload" class="table table-striped table-bordered" cellspacing="0" role="grid" width="100%" aria-describedby="DtUpload_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="text-center sorting_asc" aria-sort="ascending" >No</th>
                                                <th class="text-center" >File</th>
                                                <th class="text-center">Submit By</th>
                                                <th class="text-center sorting">Created at</th>
                                                <th class="text-center">#</th>
                                                <!-- <th class="sorting_disabled"></th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" onclick="UpdateFR()">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div> -->
                </form>
            </div>
        </div>
    </div>
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
                            <!-- <div class="col-md-12">
                                <div class="form-group">
                                    <label for="ECOMPANY">Company</label>
                                    <select class="form-control mkreadonly" name="ECOMPANY" id="ECOMPANY">
                                        <option value="0" selected>All Company</option>
                                        <?php
                                        foreach ($DtCompany as $values) {
                                            echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-md-12 mb-2">
                                <?php
                                    $CDepartment = '';
                                    foreach ($DtDepartment as $values) {
                                        $CDepartment .= '<option value="' . $values->DEPARTMENT . '">' . $values->DEPARTEMENTNAME . '</option>';
                                    }
                                ?>
                                <label>Dept</label>
                                <select class="form-control w-100" name="DEPT" id="DEPT">
                                    <option value="0" selected>All Department</option>
                                    <?php echo $CDepartment; ?>
                                </select>
                            </div>
                            <!-- <div class="col-md-12 mt-4">
                                <?php
                                    $CDepartment = '';
                                    foreach ($DtDepartment as $values) {
                                        $CDepartment .= '<option value="' . $values->DEPARTMENT . '">' . $values->DEPARTEMENTNAME . '</option>';
                                    }
                                ?>
                                <label>Send To</label>
                                <select class="form-control w-100" name="SEND_TO" id="SEND_TO">
                                    <option value="0" selected>All Department</option>
                                    <?php echo $CDepartment; ?>
                                </select>
                            </div> -->
                            <!-- <div class="col-md-12 mt-4">
                                <label>Vendor</label>
                                <select class="form-control w-100 EVENDOR" id="EVENDOR" name="EVENDOR" style="width: 100%">
                                    <option disabled selected>Select</option>
                                </select>
                            </div> -->
                            <!-- <div class="col-md-12 mt-4">
                                <div class="form-group">
                                    <label for="EPERIOD">Period</label>
                                    <input type="text" class="form-control" name="EPERIOD" id="EPERIOD" placeholder="MM YYYY" autocomplete="off">
                                </div>
                            </div> -->
                            <!-- <div class="col-md-12">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="checkDate">
                                    <label class="form-check-label" for="checkDate">
                                    By Date
                                    </label>
                                </div>
                            </div> -->

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="EPERIOD">From Date</label>
                                    <input type="text" class="form-control" name="FROMDATE" id="FROMDATE" placeholder="MM/DD/YYYY" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="EPERIOD">To Date</label>
                                    <input type="text" class="form-control" name="TODATE" id="TODATE" placeholder="MM/DD/YYYY" autocomplete="off">
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
    <div class="modal fade" id="MViewDetails">
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
                                    <th class="text-center sorting_disabled">Date</th>
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
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

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
    var table, ACTION, ID, VUUID, VINVOICE_CODE,mNO_RECEIPT_DOC;
    var no_urut = []; var INVOICE_CODE = [] ; var NO_PO = []; var VENDOR = [];
            var AMOUNT = []; var COMPANY = []; var CURRENCY = []; var NOTES = [];

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

    $('#FROMDATE').datepicker({
            "autoclose": true,
            "todayHighlight": true,
            "setDate": new Date()
        });

        $('#TODATE').datepicker({
            "autoclose": true,
            "todayHighlight": true,
            "setDate": new Date()
        });

    var Export = function(type) {
        if ($('#FExport').parsley().validate()) {
            
                var url = "<?php echo site_url('Elog/ExportFirst'); ?>?FROMDATE=PARAM3&TODATE=PARAM4&DEPT=PARAM7";
                var FROMDATE = $('#FROMDATE').val();
                var TODATE   = $('#TODATE').val();
                url = url.replace("PARAM7", $("#DEPT").val());
                url = url.replace("PARAM3", FROMDATE);
                url = url.replace("PARAM4", TODATE);

            
            window.open(url, '_blank');
        }
    }

    $(document).ready(function () {
        if (getUrlParameter('type') == "edit" || getUrlParameter('type') == "add") {
            UrlParam = getUrlParameter('type');
            if (getUrlParameter('type') == "add") {
                // if(DEPT != 'IT'){
                //     alert('Under Maintenance');
                //     window.history.back();
                // }else{
                    if (ADDS != 1) {
                        $('#btnSave').remove();
                    }
                    SetDataKosong();    
                // }
                
            } else {
                if (EDITS != 1) {
                    $('#btnSave').remove();
                }
                var data = <?php echo json_encode($DtElog); ?>;
                SetData(data);
            }
        } else {

            MONTH = ListBulan.indexOf($('#PERIOD').val().substr(0, 3)) + 1;

            $('#PERIOD').datepicker({
                "autoclose": true,
                "todayHighlight": true,
                "todayHighlight": true,
                "viewMode": "months",
                "minViewMode": "months",
                "format": "M yyyy",
            });

            // $("#PERIOD").datepicker().datepicker("setDate", new Date());

            var fDate = new Date();

            if (!$.fn.DataTable.isDataTable('#DtElog')) {
                $('#DtElog').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('Elog/ShowData') ?>",
                        "type": "POST",
                        "datatype": "JSON",
                        "data": function (d) {
                            d.fDate = moment(fDate).format('MM-YYYY');
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
                        },
                        "beforeSend": function() {
                            $('#loader').addClass('show');
                        },
                        "complete": function() {
                            $('#loader').removeClass('show');
                        }
                    },
                    "language": {
                                        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading..n.</span> '
                    },
                    "columns": [{
                        "data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
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
                    {"data": "NOTES"},
                    {"data": "CREATED_AT"},
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            var html = '';
                            // if (EDITS == 1) {
                            //     html += '<button class="btn btn-success btn-icon btn-circle btn-sm mr-2 edit" title="Edit" style="margin-right: 5px;">\n\
                            //     <i class="fa fa-edit" aria-hidden="true"></i>\n\
                            //     </button>';
                            // }
                            if (EDITS == 1) {
                                html += '<div style="display: inline-flex;padding:5px;"><button class="mr-2 btn btn-info btn-icon btn-circle btn-sm view" title="view data" data-id="'+data.UUID+'"><i class="fa fa-pencil-alt"></i></button>';
                                html += '<button class="mr-2 btn btn-green btn-icon btn-circle btn-sm btnUpload" title="upload / reupload data" data-id="'+data.UUID+'"><i class="fa fa-upload"></i></button>';
                            }
                            if (DEPT == 'IT') {
                                html += '<button class="mr-2 btn btn-danger btn-icon btn-circle btn-sm delete" data-norec="'+data.NO_RECEIPT_DOC+'" data-nopo="'+data.NO_PO+'" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                                html += '<button class="btn btn-info btn-icon btn-circle btn-sm mr-2 btnView" title="view data" data-company="'+data.COMPANY+'" data-no_inv="'+data.INVOICE_CODE+'" data-norec="'+data.NO_RECEIPT_DOC+'"><i class="fa fa-eye"></i></button></div>';
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
                $('#DtElog thead th').addClass('text-center');
                table = $('#DtElog').DataTable();
                table.on('click', '.edit', function() {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    window.location.href = window.location.href + '?type=edit&UUID=' + data.UUID;
                });
                table.on('click', '.btnUpload', function () {
                    
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    mNO_RECEIPT_DOC = data.NO_RECEIPT_DOC;
                    $('#spanPo').text(data.NO_PO);
                    $("#DtUpload").dataTable().fnDestroy();
                    $("#MUpload").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    reloadUpload(mNO_RECEIPT_DOC);
                });
                table.on('click', '.view', function () {
                    var UUID = $(this).attr('data-id');
                    ACTION = "EDIT";
                    $('#loader').addClass('show');
                    $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('Elog/viewDetail'); ?>",
                            data: {
                                ID: UUID},
                            success: function (response) {
                                $('#loader').removeClass('show');
                                VNO_RECEIPT_DOC = response[0].NO_RECEIPT_DOC;
                                VUUID           = UUID;
                                $('#compnow').text(response[0].COMPANYNAME);
                                $('#vendornow').text(response[0].VENDORNAME);
                                $('#currnow').text(response[0].CURRENCY);
                                $('#VCOMPANYNAME').val(response[0].COMPANYNAME);
                                $('#VNO_PO').val(response[0].NO_PO);
                                $('#VINVOICE_CODE').val(response[0].INVOICE_CODE);
                                $('#VAMOUNT').val(fCurrency(response[0].AMOUNT));
                                $('#VNOTES').val(response[0].NOTES);
                                // formatCurrency($('#VAMOUNT'), "blur");
                                $('#VVENDOR').val(response[0].VENDORNAME);
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
                            url: "<?php echo site_url('Elog/DeleteMaster'); ?>",
                            data: {
                                UUID: data.UUID,
                                NO_RECEIPT_DOC: data.NO_RECEIPT_DOC,
                                NO_PO: data.NO_PO,
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
                    var url = "<?php echo site_url('Elog/exportTransaction'); ?>?COMPANY=PARAM1&DOCNUMBER=PARAM2";
                    url = url.replace("PARAM1", COMP);
                    url = url.replace("PARAM2", DOCNUM);
                    window.open(url, '_blank');
                });
                // $("#DtElog_filter").remove();
                // $("#search").on({
                //     'keyup': function () {
                //         table.search(this.value, true, false, true).draw();
                //     }
                // });
            }

            $('#PERIOD').on({
                'change': function() {
                    fDate = this.value;
                    $('#loader').addClass('show');
                    table.ajax.reload();
                    $('#loader').removeClass('show');
                }
            });
        }

    var selectCompany = <?php echo json_encode($DtCompany) ?>;
    var selectCurr = <?php echo json_encode($DtCurrency) ?>;
    

  $("#addNewRow").on("click",function(e){
      
            var table = $('#DtFirst').children('tbody');
            var rowid = $('table#DtFirst > tbody > tr:last').index() + 1;
            
            var lId   = parseInt($("#tfoot").attr("data-value"));
            var rowid = lId + 1;

            var mSel = '<select name="COMPANY[]" id="COMPANY'+rowid+'" class="form-control">';
            for (i in selectCompany) {
              
                mSel+= '<option value="'+ selectCompany[i].ID +'">'+ selectCompany[i].COMPANYCODE + ' - ' + selectCompany[i].COMPANYNAME +'</option>';
            }
            mSel+= '<option >Select Company</option></select>';

            var CurrSel = '<select name="CURRENCY[]" id="CURRENCY'+rowid+'" class="form-control">';
            for (i in selectCurr) {
              
                CurrSel+= '<option value="'+ selectCurr[i].DETAILID +'">'+ selectCurr[i].DETAILNAME +'</option>';
            }
            CurrSel+= '<option >Choose Currency</option></select>';

            var VendorSel = '<select class="form-control VENDOR'+rowid+'" id="VENDOR'+rowid+'" name="VENDOR[]" required>';
            VendorSel+= '</select>';

            //var table = tbody.length ? tbody : $('#listDiscount');
            var row = '<tr id="rowid'+ rowid +'">';
                row+= '<td><input type="text" id="no_urut'+rowid+'" name="no_urut[]" value="'+rowid+'" class="form-control no_urut" data-value="no_urut'+ rowid +'"/></td>';
                row+= '<td><input type="text" id="INVOICE_CODE'+rowid+'" name="INVOICE_CODE[]" class="form-control INVOICE_CODE" data-value="INVOICE_CODE'+ rowid +'"/></td>';
                row+= '<td><input type="text" id="NO_PO'+rowid+'" name="NO_PO[]"  class="form-control NO_PO" data-value="NO_PO'+ rowid +'"/></td>';
                row+= '<td>'+ VendorSel + '</td>';
                row+= '<td>'+ mSel + '</td>';           
                row+= '<td>'+ CurrSel + '</td>'; 
                row+= '<td><input type="text" id="AMOUNT'+rowid+'" name="AMOUNT[]" data-type="currency" class="form-control AMOUNT" data-value="AMOUNT'+ rowid +'"/></td>';
                row+= '<td><input id="NOTES'+rowid+'" type="text" name="NOTES[]" class="form-control NOTES" data-value="NOTES'+ rowid +'"/></td>';
                row+= '<td><a class="btn btn-danger removeRow" data-url="" data-rowid="'+ rowid +'" data-id="0"><i class="fas fa-times"></i></a></td></tr>';           

            table.append(row);
            $("#tfoot").attr("data-value",rowid); 
            $(".VENDOR"+rowid).select2({
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
                                text:item.TEXT
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
            $("input[data-type='currency']").on({
                keyup: function() {
                    formatCurrency($(this));
                },
                blur: function() {
                    formatCurrency($(this), "blur");
                }
            });
    });

        $("#DtFirst").on("click",".removeRow",function(e){
                var delurl    = $(this).attr("data-url");
                var rowrowid  = $(this).attr("data-rowid");
                var dataid    = $(this).attr("data-id");
               
                if (dataid > 0){
                    $.ajax({
                        type:"post",
                        data:"id="+dataid,
                        url : delurl,
                        dataType : "json",
                        success:function(data){
                            if (data.error < 1){
                                $("#row" + rowrowid).remove();
                            }
                        }
                    })
                }else{
                    $("#rowid" + rowrowid).remove();
                    
                }
        });
    });
    var Add = function () {
        var url = window.location.href + '?type=add';
        window.open(url);
    };
    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }
    function SetDataKosong() {
        $('.panel-title').text('Add Data Receipt');
        ID = "0";
        $('#INVOICE_CODE').val('');
        $('#NO_PO').val('');
        $('#VENDOR').val('');
        $('#AMOUNT').val('');
        // $('#ISACTIVE').val('TRUE');
        ACTION = 'ADD';
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
        $('#MViewDetails').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    function SetData(data) {
        var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
        console.log(data);
        $('.panel-title').text('Edit Data Receipt ' + data.NO_RECEIPT_DOC);
        ID = data.UUID;
        $('#INVOICE_CODE').val(data.INVOICE_CODE);
        //moment($('#DOCDATE').val()).format('MM-DD-YYYY');
        $('#NO_PO').val(data.NO_PO);
        $('#AMOUNT').val(numFormat(data.AMOUNT));
        $('#VENDOR').append($('<option>', {
            value: data.SUPPID,
            text: data.VENDORNAME
        }));
        ACTION = 'EDIT';
    }

    var SaveMaster = function () {
              var DtElog = [];
              var dt = {};
              var lastRowId = parseInt($("#tfoot").attr("data-value"));
              var cno_urut = []; var cINVOICE_CODE = [] ; var cNO_PO = []; var cVENDOR = [];
              var cAMOUNT = []; var cCOMPANY = []; var cCURRENCY = []; var cNOTES = [];
              for ( var i = 1; i <= lastRowId; i++) {
                    dt[i] =
                        {
                            no_urut:$("#no_urut"+i).val(),
                            INVOICE_CODE: $("#INVOICE_CODE"+i).val(),
                            NO_PO: $("#NO_PO"+i).val(),
                            VENDOR: $("#VENDOR"+i).val(), 
                            COMPANY: $("#COMPANY"+i).val(), 
                            CURRENCY: $("#CURRENCY"+i).val(),
                            AMOUNT: $("#AMOUNT"+i).val(),
                            NOTES: $("#NOTES"+i).val()
                        }
                        cno_urut.push($("#no_urut"+i).val());  
                        cINVOICE_CODE.push($("#INVOICE_CODE"+i).val()); 
                        cNO_PO.push($("#NO_PO"+i).val()); 
                        cVENDOR.push($("#VENDOR"+i).val()); 
                        cCOMPANY.push($("#COMPANY"+i).val()); 
                        cCURRENCY.push($("#CURRENCY"+i).val()); 
                        cAMOUNT.push($("#AMOUNT"+i).val()); 
                        cNOTES.push($("#NOTES"+i).val()); 
                    
                }
            $("#loader").show();

            // $('#btnSave').attr('disabled', true);
            var cekINV = cINVOICE_CODE.sort(); 
            var cekPO  = cNO_PO.sort();
            var countINV = [];
            var countPO  = [];
            // console.log(cekINV);
            // dt = {
            //     no_urut: valNo,
            //     INVOICE_CODE: valInv,
            //     NO_PO: valNopo,
            //     VENDOR: valVendor,
            //     COMPANY: valComp,
            //     CURRENCY: valCurr,
            //     AMOUNT: valAmount, 
            //     NOTES: valNotes
            // }

            // DtElog.push(dt);
            // console.log(DtElog);
                for (var i = 0; i < cekINV.length - 1; i++) {
                    if (cekINV[i + 1] == cekINV[i]) {
                        countINV.push(cekINV[i]);
                    }
                }
                for (var i = 0; i < cekPO.length - 1; i++) {
                    if (cekPO[i + 1] == cekPO[i]) {
                        countPO.push(cekPO[i]);
                    }
                }
                if (DEPT != 'HRD'){
                    if(countINV.length > 0){
                        toastr.error('Duplicate Invoice '+countINV);
                        countINV = [];
                        no_urut = [];
                                INVOICE_CODE = [];
                                NO_PO = [];
                                VENDOR = [];
                                AMOUNT = [];
                                COMPANY = [];
                                CURRENCY = [];
                                NOTES = [];
                        $("#loader").hide();
                        $('#btnSave').removeAttr('disabled');
                    }
                    if(countPO.length > 0){
                        toastr.error('Duplicate SPO '+countPO);
                        countPO = [];
                        no_urut = [];
                                INVOICE_CODE = [];
                                NO_PO = [];
                                VENDOR = [];
                                AMOUNT = [];
                                COMPANY = [];
                                CURRENCY = [];
                                NOTES = [];
                        $("#loader").hide();
                        $('#btnSave').removeAttr('disabled');
                    }else{
                        $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('Elog/Save'); ?>",
                                data: {
                                    DtElog: dt,
                                    // no_urut:no_urut,
                                    // INVOICE_CODE: INVOICE_CODE,
                                    // NO_PO: NO_PO,
                                    // VENDOR: VENDOR,
                                    // AMOUNT: AMOUNT,
                                    // COMPANY: COMPANY,
                                    // CURRENCY: CURRENCY,
                                    // NOTES:NOTES,
                                    DEPARTMENT: DEPT,
                                    ACTION: ACTION,
                                    USERNAME: USERNAME
                            },
                            success: function (response) {
                                $("#loader").hide();
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
                                no_urut = [];
                                    INVOICE_CODE = [];
                                    NO_PO = [];
                                    VENDOR = [];
                                    AMOUNT = [];
                                    COMPANY = [];
                                    CURRENCY = [];
                                    NOTES = [];
                            },
                            error: function (e) {
                                $("#loader").hide();
                                console.info(e);
                                alert('Data Save Failed !!');
                                no_urut = [];
                                INVOICE_CODE = [];
                                NO_PO = [];
                                VENDOR = [];
                                AMOUNT = [];
                                COMPANY = [];
                                CURRENCY = [];
                                NOTES = [];
                                $('#btnSave').removeAttr('disabled');
                            }
                        });
                    }
                }else{
                    $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('Elog/Save'); ?>",
                            data: {
                                DtElog: dt,
                                // no_urut:no_urut,
                                // INVOICE_CODE: INVOICE_CODE,
                                // NO_PO: NO_PO,
                                // VENDOR: VENDOR,
                                // AMOUNT: AMOUNT,
                                // COMPANY: COMPANY,
                                // CURRENCY: CURRENCY,
                                // NOTES:NOTES,
                                DEPARTMENT: DEPT,
                                ACTION: ACTION,
                                USERNAME: USERNAME
                        },
                        success: function (response) {
                            $("#loader").hide();
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
                            no_urut = [];
                                INVOICE_CODE = [];
                                NO_PO = [];
                                VENDOR = [];
                                AMOUNT = [];
                                COMPANY = [];
                                CURRENCY = [];
                                NOTES = [];
                        },
                        error: function (e) {
                            $("#loader").hide();
                            console.info(e);
                            alert('Data Save Failed !!');
                            no_urut = [];
                            INVOICE_CODE = [];
                            NO_PO = [];
                            VENDOR = [];
                            AMOUNT = [];
                            COMPANY = [];
                            CURRENCY = [];
                            NOTES = [];
                            $('#btnSave').removeAttr('disabled');
                        }
                    });
                }
                

                
            
    };

    var UpdateFR = function() {
            if ($('#FView').parsley().validate()) {
                if (parseFloat(formatDesimal($("#VAMOUNT").val())) <= 0) {
                    alert("Amount Can't be Zero !!!");
                } else {
                    $.ajax({
                        dataType: "JSON",
                        type: "POST",
                        url: "<?php echo site_url('Elog/Save'); ?>",
                        data: {
                            NO_RECEIPT_DOC: VNO_RECEIPT_DOC,
                            UUID: VUUID,
                            COMPANY: $('#VCOMPANY').val(),
                            INVOICE_CODE: $('#VINVOICE_CODE').val(),
                            AMOUNT: $('#VAMOUNT').val(),
                            NO_PO: $('#VNO_PO').val(),
                            VENDOR: $('#VENDORchange').val(),
                            NOTES: $('#VNOTES').val(),
                            CURRENCY: $('#VCURRENCY').val(),
                            DEPARTMENT: DEPT,
                            ACTION: ACTION,
                            USERNAME: USERNAME
                        },
                        success: function (response) {
                            if (response.status == 200) {
                                toastr.success(response.result.data);
                            } else if (response.status == 504) {
                                toastr.error(response.result.data);
                                // $('#DtElog').DataTable().ajax.reload();
                            } else {
                                toastr.error(response.result.data);
                            }
                            $('#DtElog').DataTable().ajax.reload();
                        }
                    });
                        table.ajax.reload();
                        $("#MView").modal("hide");
                }
            }
        };
</script>
<script type="text/javascript">
    $(document).ready(function() {

        $("#VENDOR1").select2({
            // theme: 'bootstrap4',
            ajax: {
                url: "<?php echo site_url('Elog/getVendor') ?>",
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
                            text:item.TEXT
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

        $("#VENDORchange").select2({
             dropdownParent: $("#MView"),
            // theme: 'bootstrap4',
            ajax: {
                url: "<?php echo site_url('Elog/getVendor') ?>",
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
                            text:item.TEXT
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

</script>

<script type="text/javascript">
    var filetypeUpload = ['ZIP'];

    function filesChange(elm) {
        var fileInput = $('.myfile');
        // console.log(fileInput);
        var extFile = $('.myfile').val().split('.').pop().toUpperCase();
        // console.log(extFile);
        var maxSize = 5242880;
        if ($.inArray(extFile, filetypeUpload) === -1) {
            toastr.error('Format file tidak valid');
            files = '';
            $('.myfile').val('');
            return;
        }else {
            if (fileInput.get(0).files.length) {
                var fileSize = fileInput.get(0).files[0].size;
                if (fileSize > maxSize) {
                    toastr.error('Ukuran file terlalu besar');
                    files = '';
                    $('.myfile').val('');
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
                        fd.append("userfile", data);
                    });
                    fd.append("USERNAME", USERNAME);
                    fd.append("NO_RECEIPT_DOC",mNO_RECEIPT_DOC);
                    // fd.append("EXTSYSTEM",$('#EXTSYSTEM').val());
                    // fd.append("DOCTYPE",$('#DOCTYPE').val());
                    // fd.append('UUID',UUID)
                    // fd.append('DATERELEASE',currentDate);
                    $.ajax({
                        dataType: "JSON",
                        type: 'POST',
                        url: "<?php echo site_url('Elog/uploadElogFile'); ?>",
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
                                // $('.myfile').hide();
                                reloadUpload(mNO_RECEIPT_DOC);
                            } else if (response.status == 504) {
                                toastr.error(response.result.data);
                                $('#btnReset').removeAttr('disabled');
                                
                                reloadUpload(mNO_RECEIPT_DOC);
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

    
    

    function reloadUpload(mNO_RECEIPT_DOC){
        $("#DtUpload").dataTable().fnDestroy();
        // $('#notesUpload').val('');
        $('.myfile').val('');
        if (!$.fn.DataTable.isDataTable('#DtUpload')) {
            $('#DtUpload').DataTable({
                "bDestory" : true,
                "bRetrieve" : true,
                // "aaData": DtUpload,
                "ajax": {
                        "url": "<?php echo site_url('Elog/ShowFileData') ?>",
                        "type": "POST",
                        "datatype": "JSON",
                        "data": function(d) {
                            d.NO_RECEIPT_DOC = mNO_RECEIPT_DOC;
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
                                // location.reload();
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
                window.open("<?php echo base_url('assets/elogfiles/')?>" + data.FILENAME,'_blank');
            });
            tbl_upload.on('click', '.delete', function () {
                        $tr = $(this).closest('tr');
                        var data = tbl_upload.row($tr).data();
                        if (confirm('Are you sure delete this data ?')) {
                            $.ajax({
                                dataType: "JSON",
                                type: "POST",
                                url: "<?php echo site_url('Elog/DeleteFile'); ?>",
                                data: {
                                    ID: data.ID,
                                    FILENAME: data.FILENAME,
                                    USERNAME: USERNAME
                                },
                                success: function (response) {
                                    if (response.status == 200) {
                                        toastr.success(response.result.data);
                                        // $('.myfile').show();
                                        reloadUpload(data.NO_RECEIPT_DOC);
                                    } else if (response.status == 504) {
                                        toastr.error(response.result.data);
                                        reloadUpload(data.NO_RECEIPT_DOC);
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
        }
    }
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