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
        <h4 class="panel-title">Withdraw Data KI</h4>
    </div>
    <div class="panel-body">
        <?php if (empty($_GET)) { ?>
            <div class="row mb-2">
                <div class="col-md-8 pull-left">
                    <h3>Withdraw Data KI</h3>
                </div>
                <div class="col-md-4 pull-right">
                    <div class="input-group ">
                        <input type="text" id="search" name="search" class="form-control" placeholder="Cari.." >
                    </div>
                </div>
            </div>
            <div class="row m-0 table-responsive">
                <table id="DtLeasing" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DtLeasing_info">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc">#</th>
                            <th class="text-center sorting_asc">No</th>
                            <th class="text-center sorting">Company</th>
                            <th class="text-center sorting">Contract Number</th>
                            <th class="text-center sorting">PK Number</th>
                            <!-- <th class="text-center sorting">Drawdown Type</th> -->
                            <!-- <th class="text-center sorting">Sub Credit Type Details</th> -->
                            <th class="text-center sorting">Created</th>
                            <th class="text-center sorting">Status</th>
                            <th class="text-center sorting_disabled" aria-label="Action">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        <?php } else { ?>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#rekeningkoran" data-toggle="tab" class="leasingdata nav-link active">
                    <span class="d-sm-none">Tab 1</span>
                    <span class="d-sm-block d-none">Withdraw Kredit Investasi</span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active show" id="rekeningkoran">
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
                            <label for="description">Contract Number </label>
                            <input type="text" class="form-control" id="CONTRACT_NUMBER" required>
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
                                <option value="KI" selected>Kredit Investasi</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Sub Credit Type *</label>
                            <select name="SUB_CREDIT_TYPE" id="SUB_CREDIT_TYPE" class="form-control">
                                <!-- <option value="TL">Time Loan</option> -->
                                <option value="FINANCING">FINANCING</option>
                                <option value="REFINANCING">REFINANCING</option>
                                <!-- <option value="FINANCING">FINANCING</option>
                                <option value="REFINANCING">REFINANCING</option> -->
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">IDC Status</label>
                            <select name="IDC_STATUS" id="IDC_STATUS" class="form-control">
                                <option value="WITH_IDC">With IDC</option>
                                <option value="WITHOUT_IDC">Without IDC</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="fcname">Max Limit *</label>
                            <input type="text" class="form-control" name="AMOUNTLIMIT" id="AMOUNTLIMIT" disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fcname">Balance *</label>
                            <input type="text" class="form-control" name="AMOUNT_BALANCE" id="AMOUNT_BALANCE" disabled>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- RK Start -->
        
        <div class="panel panel-success bd d-none">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form id="FAddEditFormbd" data-parsley-validate="true" data-parsley-errors-messages-disabled="" onsubmit="return false" novalidate="">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="fcname">Tranche Number *</label>
                                <select class="form-control" name="TRANCHE_NUMBER" id="TRANCHE_NUMBER" required="">
                                    <option value="" selected="" disabled="">Choose</option>
                                    <?php
                                        foreach ($DtTranche as $values) {
                                            echo '<option value="' . $values->TRANCHE_NUMBER . '">' . $values->TRANCHE_NUMBER . '</option>';
                                        }
                                        ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="fcname">IDR to USD</label>
                                <input type="text" class="form-control" name="RATE_IDRUSD" id="RATE_IDRUSD" placeholder="Rate" data-type='currency'>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Drawdown Type</label>
                                <select name="DRAWDOWN_TYPE" id="DRAWDOWN_TYPE" class="form-control" disabled>
                                    <option value="" disabled="" selected="">--Choose--</option>
                                    <option value="DISBURSEMENT">DISBURSEMENT</option>
                                    <option value="REIMBURSEMENT">REIMBURSEMENT</option>
                                    <option value="COMBINE">COMBINE</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="fcname">CNY to USD </label>
                                <input type="text" class="form-control" name="RATE_CNYUSD" id="RATE_CNYUSD" placeholder="Rate" data-type='currency'>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="fcname">Drawdown Date *</label>
                                <input type="date" class="form-control" name="VALUE_DATE" id="VALUE_DATE" required="" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="fcname">SGD to USD </label>
                                <input type="text" class="form-control" name="RATE_SGDUSD" id="RATE_SGDUSD" placeholder="Rate" data-type='currency'>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="fcname">Drawdown Value *</label>
                                <input type="text" class="form-control" data-type='currency' name="DRAWDOWN_VALUE" id="DRAWDOWN_VALUE" placeholder="Amount" required="">
                            </div>
                        </div>
                        <button type="button" id="btnSave" onclick="SaveWD()" class="btn btn-primary btn-sm m-l-5">Save</button>
                        <hr>
                        
                    </form>
                    <hr>
                    <div class="row mt-2 fileupload-buttonbar">
                            <div class="col-md-4">
                                <label for="address">Attachment *</label>
                                <select name="tipe_file" id="tipe_file" class="form-control mb-2">
                                    <option value="WITHDRAWAL_LETTER">WITHDRAWAL LETTER</option>
                                    <option value="RECAP">RECAP</option>
                                    <option value="COPY_OF_BILL">COPY OF BILL</option>
                                    <option value="INVOICE">INVOICE</option>
                                </select>
                                <span class="btn btn-primary fileinput-button m-r-3">
                                    <!--<i class="fa fa-plus"></i>-->
                                    <span>Browse File</span>
                                    <input type="file" class="upload-file" data-max-size="1048576" onchange="filesChange(this)">
                                </span>
                                <!-- <button id="btnSave" type="button" class="btn btn-primary m-r-3" onclick="SaveUpload()">
                                    <i class="fa fa-upload"></i>
                                    <span>Upload Data</span>
                                </button> -->
                                <button id="btnReset" type="button" class="btn btn-default mt-2 mb-2" onclick="ClearData()" disabled="disabled">
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
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <button id="btnDetail" type="button" onclick="AddDetail()" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> Add</button>
                        </div>
                    </div>
                    <div class="row m-0 table-responsive">
                        <table id="DtDetail" class="table table-bordered table-hover dataTable" role="grid" width="100%" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <th class="text-center align-middle">Vendor</th>
                                    <th class="text-center align-middle">Vendor Bank</th>
                                    <th class="text-center">Vendor Bank Account</th>
                                    <th class="text-center align-middle">Drawdown Date</th>
                                    <th class="text-center align-middle">Invoice Date</th>
                                    <th class="text-center align-middle">Invoice Number</th>
                                    <th class="text-center align-middle">Docnumber</th>
                                    <th class="text-center">Invoice Amount</th>
                                    <th class="text-center align-middle">Drawdown Amount</th>
                                    <th class="text-center align-middle">Currency</th>
                                    <th class="text-center align-middle">Attachment</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                        </table>`
                    </div>
                </div>
            </div>
        </div>
        <!-- RK End -->
        <!-- modal -->
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
                                    <div class="form-group ">
                                        <label>Vendor *</label>
                                        <br>
                                        <select class="form-control vendor" id="IVENDOR" name="IVENDOR" required>
                                            <!-- <option disabled selected>Select Vendor</option> -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Bank *</label>
                                        <input type="text" class="form-control" id="IBANK" required>
                                        <!-- <select class="form-control vendor" id="VENDOR" name="VENDOR" required> -->
                                            <!-- <option disabled selected>Select Vendor</option> -->
                                        <!-- </select> -->
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Norek *</label>
                                        <input type="text" class="form-control" id="INOREK" required>
                                        <!-- <select class="form-control vendor" id="VENDOR" name="VENDOR" required> -->
                                            <!-- <option disabled selected>Select Vendor</option> -->
                                        <!-- </select> -->
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Drawdown Date *</label>
                                        <div class="input-group">
                                            <input type="date" class="form-control" id="IDRAWDOWN_DATE" name="IDRAWDOWN_DATE" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Invoice Date *</label>
                                        <div class="input-group">
                                            <input type="date" class="form-control" id="IINVOICE_DATE" name="IINVOICE_DATE" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Invoice Number *</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="IINVOICE_NUMBER" name="IINVOICE_NUMBER" placeholder="Invoice Number" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                       <label>PO / SPK Number *</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="IPO_NUMBER" name="IPO_NUMBER" placeholder="PO / SPK Number" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                       <label>Inv Value *</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" data-type='currency' id="IINVOICE_VALUE" name="IINVOICE_VALUE" placeholder="Inv Value" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Drawdown Value *</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" data-type='currency' id="IDRAWDOWN_VALUE" name="IDRAWDOWN_VALUE" placeholder="Drawdown Value" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="currency">Currency *</label>
                                        <select class="form-control" name="ICURRENCY" id="ICURRENCY" required="">
                                        <option value="" selected="" disabled="">Choose Currency</option>
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
                                        <label>Attachment *</label>
                                        <input type="file" class="form-control attachment" name="attachment" id="attachment">
                                    </div>
                                </div>
                                <input type="hidden" id="DETID">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="btnSaveFile" onclick="SaveWADetails(attachment)">Save</button>
                            <input style="display:none;" type="button" class="btn btn-info" id="updateTemp" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- RK End -->
        <!--  -->
        <?php } ?>
    </div>
    <!-- modal view  -->
    <?php if (!empty($_GET)) { ?>
                        <div class="panel-footer text-left">
                            <button type="button" class="btn btn-warning btn-sm m-l-5" onclick="Cancel()">Back</button>
                            <button type="button" class="btn btn-success btn-sm m-l-5" id="Done" onclick="Done()">Done</button>
                        </div>
                    <?php } ?>
</div>

<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var dtkmk = <?php echo json_encode($DtKmk);?> ;
    // console.log()
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
    var tempBalance = 0 ;
    var tempTNum = null;
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
                var data = <?php echo json_encode($DtKmk); ?>;
                SetData(data);

                if((data[0].STATUS == '1' || data[0].STATUS == '0' || data[0].STATUS == '2') && getUrlParameter('vm') == "vm"){
                    // $('#btnSave').hide();
                    // $('.panel-footer #btnSaveMaster').hide();
                    $('.fileupload-buttonbar').hide();
                    $('#btnSave').hide();
                    $('#btnDetail').remove();
                    $('#Done').remove();
                }
                // if((data[0].STATUS == '1' || data[0].STATUS == '0' || data[0].STATUS == '2') && getUrlParameter('vm') == null){
                //     $('#btnSave').hide();
                //     $('.panel-footer #btnSave').hide();
                //     $('#btnSaveFile').remove();
                // }
                setTimeout(function(){
                    if((data[0].STATUS == '1' || data[0].STATUS == '0' || data[0].STATUS == '2') && getUrlParameter('vm') == "vm"){
                        $('.delete').addClass('d-none');
                        $('.edit').addClass('d-none');
                    }
                },1000);
            }
        } else {
            if (!$.fn.DataTable.isDataTable('#DtLeasing')) {
                var renderer = $.fn.dataTable.render.number( ',', '.', 2 ).display ;
                $('#DtLeasing').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('Kmk/ShowWithdrawDataKI') ?>",
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
                    "columns": [
                    {
                        "className" : 'text-center showChild',
                        "orderable" : false,
                        render : function (data, type, row, meta) {
                            // console.log(data);
                            var html = '<button class="btn btn-primary btn-icon btn-circle btn-sm editdet" title="" style="margin-right: 5px;">\n\
                                                    <i class="fas fa-plus" aria-hidden="true"></i>\n\
                                                    </button>'     
                            return html ;
                        }
                    }
                    ,{
                        "data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": "COMPANYCODE",
                        "width" : "5%"
                    },
                    {"data": "CONTRACT_NUMBER"},
                    {
                        "data": "PK_NUMBER"
                    },
                    {"data": "CREATED_AT","className": "text-center"},
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            var html = '';
                            if (data.STATUS == '2') {
                                    html += '<span class="badge bg-danger">Decline</span>';
                            }else if(data.STATUS == '1'){
                                html += '<span class="badge bg-success">Approved</span>'
                            }else{
                                html += '<span class="badge bg-secondary">?</span>'
                            }
                            return html;
                        }
                    },
                    // {
                    //     "data": "AMOUNT_LIMIT",
                    //     "className": "text-right",
                    //     render: $.fn.dataTable.render.number(',', '.', 2)
                    // },
                    // {
                    //     "data": "AMOUNT_BALANCE",
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
                                    html += '<button class="btn btn-info btn-icon btn-circle btn-sm mr-2 edit" title="edit data" data-id="'+data.UUID+'" data-iddet="'+data.ID+'"><i class="fas fa-edit"></i></button>';
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
                    
                    // console.log(data);
                    // if(data.SUB_CREDIT_TYPE == 'RK'){
                        window.open(window.location.href + '?type=edit&UUID=' + data.UUID + '&IDDET='+data.CTRWD,'_self');    
                    // }
                    
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
        $('#MDetail select').css('width', '70%');
        $("#IVENDOR").select2({
            // theme: 'bootstrap4',
            dropdownParent: $('#MDetail .modal-content'),
            width: 'resolve',
            ajax: {
                url: "<?php echo site_url('Kmk/getVendorKI') ?>",
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
                    var companyCode = $('#COMPANY').val();
                    var companyName = $('#COMPANY option:selected').text();
                    data.push({
                        ID : companyCode,
                        TEXT : companyName,
                        BIC : " "
                    });
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
   
        $("#IVENDOR").on("change", function() {
        $.ajax({
            url: "<?php echo site_url('Kmk/setVendorKI')?>",
            dataType : 'JSON',
            type: "POST",
            data : {
                FCNAME :  $(this).find('option:selected').text()
            },
            success : function(response) {
            var data = response.result.data ;
                    if (response.status == 200) {
                        $("#IBANK").val(data.BANKNAME);
                        if(data.BANKNAME == null) {
                            $("#INOREK").val('');    
                        }
                        else
                        {$("#INOREK").val(data.BANKACCOUNT);}
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        location.reload();
                    } else if (response.status == 500) {
                        alert(response.result.data);
                        location.reload();
                    }  else {
                        alert(response.result.data);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Please Check Your Connection !!!');
                }

        });
    });
    var Add = function () {
        window.location.href = window.location.href + '?type=add';
    };
    function Cancel() {
        history.go(-1)
    }
    function Done() {
        toastr.success('Withdrawal Success');
         setTimeout(function() { window.location.href = "<?php echo site_url('Withdraw'); ?>"; },500);
    }
    function Edit(ID){
        window.location.href = "<?php echo site_url('Withdraw'); ?>" + '?type=edit&UUID=' + ID;
    }

    function SetDataKosong() {
        $('.panel-title').text('Add Data');
        ID = "0";
        $('#COMPANY').val('');
        $('#CREDIT_TYPE').val('');
        $('#SUB_CREDIT_TYPE').val('');
        $('#BANK').val();
        $('#BUSINESSUNIT').val('');
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

    var CREDIT_TYPE,SUB_CREDIT_TYPE,IDDET, IDHEADER, AMTMODALS;
    var Uid = <?php echo json_encode($Uid); ?>;
    var BEGIN = 1;
    var dummy_bal = new Map();
    var currentBalance = new Map() ;
    // console.log(currentBalance);
    var totalDrawdown = new Map() ;
    var tranches_number = <?php echo json_encode($DtTranche);?> ;
    var renderer = $.fn.dataTable.render.number( ',', '.', 2 ).display;
   
    function SetData(data) {
        var currency = data[0].CURRENCY;
        var curr ;
        var newTrNumber = [];
        $('#TRANCHE_NUMBER').empty();
        try {
            tranches_number.map((x, idx) => {
                // console.log(x.CURRENCY, x.TRANCHE_NUMBER, currency);
                if(x.CURRENCY == currency) {
                    currentBalance.set(String(x.TRANCHE_NUMBER), [0, x.CURRENCY]);
                    dummy_bal.set(String(x.TRANCHE_NUMBER), [0, x.CURRENCY]);
                    totalDrawdown.set(String(x.TRANCHE_NUMBER), 0);
                    newTrNumber.push(x.TRANCHE_NUMBER);
                } 
            });
            // console.log(currentBalance);
        } catch {

        }
        // console.log(newTrNumber);
        newTrNumber.map((x, idx) => {
            $('#TRANCHE_NUMBER').append($('<option>', {
                value : x,
                text  : x
            }));
        });
        var trnumber = data.TRANCHE_NUMBER;
        $('#TRANCHE_NUMBER option[value="'+trnumber+'"]').attr("selected", "selected");
        var limit_tranche = new Map();
        var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
        var lengthdata = data.length ;
        // console.log(data);
        $('.panel-title').text('Edit Data Withdraw KI');
        ID      = data[0].UUID;
        IDDET   = data[0].ID;
        IDHEADER = data[0].IDHEADER;
        if(data[0].STATUS == "1" ) {
            $('#btnSave').hide();
            $('#btnDetail').hide();
            $('body').find('.edit').hide();
            $('body').find('.delete').hide();
        }
        $('#COMPANY option[value='+data[0].COMPANY+']').attr('selected','selected');
        $('#BUSINESSUNIT').append($('<option>', {
            value: data[0].BUID,
            text: data[0].BUFCNAME
        }));
        $('#CREDIT_TYPE option[value='+data[0].CREDIT_TYPE+']').attr('selected','selected');
        $('#DRAWDOWN_TYPE option[value='+data[0].DRAWDOWN+']').attr('selected','selected');
        $('#TRANCHE_NUMBER option[value="'+data[0].TRANCHE_NUMBER+'"]').attr('selected','selected');
        $('#IDC_STATUS option[value='+data[0].IDC_STATUS+']').attr('selected','selected');
        $('#SUB_CREDIT_TYPE option[value='+data[0].SUB_CREDIT_TYPE+']').attr('selected','selected');
        $('#PK_NUMBER').val(data[0].PK_NUMBER);
        // if(data.DRAWDOWN_VALUE == null || data.DRAWDOWN_VALUE == 0){
        //     $('#DRAWDOWN_VALUE').val();    
        // }else{
        //     $('#DRAWDOWN_VALUE').val(fCurrency(data.DRAWDOWN_VALUE));
        // }
        // console.log(currentBalance);
        for (var idx = 0 ; idx < lengthdata ; idx++) {
            var cur_bal = currentBalance.get(data[idx].TRANCHE_NUMBER);
            if(cur_bal) {
                cur_bal[0] = ((data[idx].BALANCE != null) ? Number(data[idx].BALANCE) : Number(data[idx].LIMIT_TRANCHE) )
                // console.log(cur_bal);
                currentBalance.set(data[idx].TRANCHE_NUMBER, cur_bal);
                totalDrawdown.set(data[idx].TRANCHE_NUMBER, Number(data[idx].DRAWDOWN_VALUE));
                limit_tranche.set(data[idx].TRANCHE_NUMBER, Number(data[idx].LIMIT_TRANCHE));
            }
            // console.log(currentBalance);
        }
        // console.log(currentBalance);
        dummy_bal = setBalance(totalDrawdown, currentBalance);
        // console.log(dummy_bal);
        BEGIN = 0 ;
        var dum_bal = dummy_bal.get($('#TRANCHE_NUMBER option:selected').text())
        var cur_bal = currentBalance.get($('#TRANCHE_NUMBER option:selected').text())
        $('#DRAWDOWN_VALUE').val(fCurrency(String(totalDrawdown.get($('#TRANCHE_NUMBER option:selected').text()))));
        $('#AMOUNT_BALANCE').val(fCurrency(String(dum_bal[0])));
        if(dtkmk[0].STATUS == 1) {
            $('#AMOUNT_BALANCE').val(fCurrency(String(cur_bal[0])));
        }
        $('#AMOUNTLIMIT').val(fCurrency(String(limit_tranche.get($('#TRANCHE_NUMBER option:selected').text()))));
        if(data[0].RATE_IDRUSD == null || data[0].RATE_IDRUSD == 0){
            $('#RATE_IDRUSD').val();    
        }else{
            $('#RATE_IDRUSD').val(fCurrency(data[0].RATE_IDRUSD));
        }
        if(data[0].RATE_CNYUSD == null || data[0].RATE_CNYUSD == 0){
            $('#RATE_CNYUSD').val();    
        }else{
            $('#RATE_CNYUSD').val(fCurrency(data[0].RATE_CNYUSD));
        }
        if(data[0].RATE_SGDUSD == null || data[0].RATE_SGDUSD == 0){
            $('#RATE_SGDUSD').val();    
        }else{
            $('#RATE_SGDUSD').val(fCurrency(data[0].RATE_SGDUSD));
        }
        // if(data.LIMIT_TRANCHE == null || data.LIMIT_TRANCHE == 0){
        //     $('#AMOUNTLIMIT').val();    
        // }else{
        //     $('#AMOUNTLIMIT').val(fCurrency(data.LIMIT_TRANCHE));
        // }

        if (data[0].DRAWDOWN == 'REIMBURSEMENT') {
                $('#btnDetail').attr('disabled',true);
                $('#DRAWDOWN_VALUE').attr('disabled', false);
                $('#DRAWDOWN_VALUE').attr('required', true);
                $('#btnSave').attr('disabled', false);
        } 
        else if (data[0].DRAWDOWN == 'DISBURSEMENT') {
            $('#DRAWDOWN_VALUE').attr('disabled', true);
            $('#DRAWDOWN_VALUE').attr('required', false);
            $('#btnSave').attr('disabled', true);
            $('#btnDetail').attr('disabled',false);
        }
        else {
            $('#btnDetail').attr('disabled',false);
            $('#DRAWDOWN_VALUE').attr('disabled', false);
            $('#DRAWDOWN_VALUE').attr('required', true);
            $('#btnSave').attr('disabled', false);
        }
        // $.ajax({
        //         dataType: "JSON",
        //         type: "POST",
        //         url: "<?php echo site_url('Kmk/getLastBalanceKI'); ?>",
        //         data: {
        //             UUID: ID
        //         },
        //         success: function (response) {
        //             response.map((x) => {
        //                 currentBalance.set(x.TRANCHE_NUMBER, Number(x.BALANCE ? x.BALANCE : x.LIMIT_TRANCHE));
        //             }) ;
        //             tbHeaderDet.ajax.reload();
        //         }
        //     });


        $('#VALUE_DATE').val(moment(data[0].VALUE_DATE).format('YYYY-MM-DD'));
        // $('#END_AMOUNT').val(fCurrency(data.AMOUNT));
        $('#COMPANY').attr('readonly', true);
        $('#PK_NUMBER').attr('readonly', true);
        $('#BUSINESSUNIT').attr('readonly', true);
        $('#CONTRACT_NUMBER').attr('readonly', true);
        $('#CREDIT_TYPE').attr('readonly', true);
        $('#SUB_CREDIT_TYPE').attr('readonly', true);
        $('#IDC_STATUS').attr('readonly', true);
        $('#AMOUNT_BALANCE').attr('readonly', true);
        // $('#TRANCHE_NUMBER').attr('disabled', true);
        ACTION = 'EDIT';
        CREDIT_TYPE     = data.CREDIT_TYPE;
        SUB_CREDIT_TYPE = data.SUB_CREDIT_TYPE;
        AMOUNT_LIMIT    = data.AMOUNT_LIMIT;
        AMTMODALS = Number(formatDesimal($('#DRAWDOWN_VALUE').val()));
        if(data[0].DRAWDOWN == 'COMBINE' || data[0].DRAWDOWN == 'DISBURSEMENT') {
            $('#DRAWDOWN_VALUE').attr('disabled', 'disabled');
            $('#btnSave').attr('disabled', 'disabled');
        }
        // if((CREDIT_TYPE == "KMK" && SUB_CREDIT_TYPE == "BD") || (CREDIT_TYPE == "KMK" && SUB_CREDIT_TYPE == "RK") ){
        $('.bd').removeClass('d-none');
        // }
        // $('.btnSave').addClass('d-none');
    }

    var AddDetail= function() {
            ACTIONM = 'ADD';
            INOREK = '';
            ISUBTYPE = "";
            IDOCDATE = "";
            ILOAN_ACCOUNT_NUMBER = "";
            IFEE = "";
            IRATE = "";
            ICURRENCY = "";
            IINTEREST_PERIOD_FROM = "";
            IAMOUNTLIMIT = "";
            DETID = null ;
            tempTNum = null ;
            tempBalance = 0;
            $("#DETID").val(null); 
            $("#ISUBTYPE").val('');
            $('#IVENDOR').val(null).trigger('change');
            $('#INOREK').val('');
            $("#ILOAN_ACCOUNT_NUMBER").val('');
            $("#IFEE").val('');
            $("#IRATE").val('');
            $("#ICURRENCY").val('');
            $("#IINTEREST_PERIOD_FROM").val('');
            $("#IAMOUNT_LIMIT").val('');
            $('#FDetail').parsley().reset();
            $('#MDetail .modal-title').text("Add Data Detail");
            $("#MDetail").modal({
                backdrop: 'static',
                keyboard: false
            });
    };
    
    if (!$.fn.DataTable.isDataTable('#DtDetail')) {
        
                        $('#DtDetail').DataTable({
                            "bDestory" : true,
                            "bRetrieve" : true,
                            // "aaData": DtUpload,
                            "ajax": {
                                    "url": "<?php echo site_url('Kmk/WDDetailKI') ?>",
                                    "type": "POST",
                                    "datatype": "JSON",
                                    "data": function(d) {
                                        d.UUID = Uid;
                                        d.IDHEADER = dtkmk[0].IDHEADER;
                                        // d.DEPARTMENT = $('#DEPARTMENT').val();
                                    },
                                    "dataSrc": function(ext) {
                                        if (ext.status == 200) {
                                            ext.draw = ext.result.data;
                                            if($('#DRAWDOWN_TYPE').val() == 'DISBURSEMENT' ||$('#DRAWDOWN_TYPE').val() == 'COMBINE' )
                                            {
                                                    tranches_number.map((x) => {
                                                    totalDrawdown.set(String(x.TRANCHE_NUMBER), 0);
                                                });
                                                for (var index = 0 ; index < ext.result.data.length; index++) {
                                                    var trNum = String(ext.draw[index].TRANCHE_NUMBER) ;
                                                    var lastBalance = Number(totalDrawdown.get(trNum)) ;
                                                    totalDrawdown.set(trNum, lastBalance + Number(ext.draw[index].DRAWDOWN_VALUE));
                                                }
                                                dummy_bal = setBalance(totalDrawdown, currentBalance);
                                                // console.log(totalDrawdown);
                                                // console.log(dtkmk);
                                                BEGIN = 0 ;
                                                var dum_bal = dummy_bal.get($('#TRANCHE_NUMBER').val());
                                                var cur_bal = currentBalance.get($('#TRANCHE_NUMBER').val());
                                                if (BEGIN) {
                                                    $('#AMOUNT_BALANCE').val('');
                                                }
                                                else {
                                                    $('#AMOUNT_BALANCE').val(fCurrency(String(dum_bal[0])));
                                                }
                                                if(dtkmk[0].STATUS == 1) {
                                                    $('#AMOUNT_BALANCE').val(fCurrency(String(cur_bal[0])));
                                                }
                                                $('#DRAWDOWN_VALUE').val(fCurrency(String(totalDrawdown.get($('#TRANCHE_NUMBER').val())))); 
                                           
                                            }
                                            // console.log(totalDrawdown);

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
                            {"className": "text-center","data":"VENDORNAME"},
                            {"className": "text-center","data":"VENDOR_BANK"},
                            {"className": "text-center","data":"VENDOR_BANK_ACC"},
                            {"className": "text-center","data":"DRAWDOWN_DATE"},
                            {"className": "text-center","data":"INVOICE_DATE"},
                            {"className": "text-center","data":"INVOICE_NUMBER"},
                            {"className": "text-center","data":"PO_NUMBER"},
                            {
                                "data": "INVOICE_VALUE",
                                "className": "text-right",
                                render: $.fn.dataTable.render.number(',', '.', 2)
                            },
                            {
                                "data": "DRAWDOWN_VALUE",
                                "className": "text-right",
                                render: $.fn.dataTable.render.number(',', '.', 2)
                            },
                            {"className": "text-center","data":"CURRENCY"},
                            {"className": "text-center","data":"FILENAME"},
                            {
                                        "data": null,
                                        "className": "text-center",
                                        "orderable": false,
                                        render: function (data, type, row, meta) {
                                            var html = '';
                                            if (EDITS == 1) {
                                                html += '<button class="btn btn-success btn-icon btn-circle btn-sm edit" title="edit" style="margin-right: 5px;">\n\
                                                <i class="fas fa-edit" aria-hidden="true"></i>\n\
                                                </button>';
                                            }
                                            if (DELETES == 1) {
                                                html += '<button class="btn btn-danger btn-icon btn-circle btn-sm delete" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                                            }
                                            html += '<button class="btn btn-success btn-icon btn-circle btn-sm dwn" title="Download" style="margin-right: 5px;">\n\
                                                    <i class="fas fa-arrow-down" aria-hidden="true"></i>\n\
                                                    </button>';
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
                        tbHeaderDet.on('click', '.edit', function() {
                            $tr = $(this).closest('tr');
                            var data = tbHeaderDet.row($tr).data();
                            tempBalance = data.DRAWDOWN_VALUE;
                            tempTNum = data.TRANCHE_NUMBER;
                            // console.log(data);
                            DETID = data.CTRWD;
                            IDHEADER = data.IDHEADER ;
                            AMTMODALS = data.DRAWDOWN_VALUE ;

                            $('#DETID').val(DETID);
                            $('#IVENDOR').val('');
                            $('#IVENDOR').append('<option value="'+ data.SUPPID +'" selected="selected">'+data.VENDORNAME+'</option>');
                            $('#IBANK').val(data.VENDOR_BANK);
                            $('#INOREK').val(data.VENDOR_BANK_ACC);
                            $('#IDRAWDOWN_DATE').val(moment(data.DRAWDOWN_DATE).format('YYYY-MM-DD'));
                            $('#IINVOICE_DATE').val(moment(data.INVOICE_DATE).format('YYYY-MM-DD'));
                            $('#IINVOICE_NUMBER').val(data.INVOICE_NUMBER);
                            $('#IPO_NUMBER').val(data.PO_NUMBER);
                            $('#IINVOICE_VALUE').val(fCurrency(data.INVOICE_VALUE));
                            $('#IDRAWDOWN_VALUE').val(fCurrency(data.DRAWDOWN_VALUE));
                            $('#ICURRENCY option[value='+data.CURRENCY+']').attr('selected','selected');
                            $('#FDetail').parsley().reset();
                            $('#MDetail .modal-title').text("Edit Data Invoice");
                            // const cekDocval = IDOCNUMBER.includes("TMP");
                            // alert(IDOCNUMBER);
                            $("#MDetail").modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                        });
                        tbHeaderDet.on('click', '.dwn', function() {
                                    $tr = $(this).closest('tr');
                                    var data = tbHeaderDet.row($tr).data();
                                    window.open("<?php echo base_url('assets/file/')?>" + data.FILENAME,'_blank');
                                    });
                        tbHeaderDet.on('click', '.delete', function () {
                                    $tr = $(this).closest('tr');
                                    var data = tbHeaderDet.row($tr).data();
                                    if (confirm('Are you sure delete this data ?')) {
                                        $.ajax({
                                            dataType: "JSON",
                                            type: "POST",
                                            url: "<?php echo site_url('Kmk/DeleteFundsDetKI'); ?>",
                                            data: {
                                                ID: data.ID,
                                                FILENAME: data.FILENAME,
                                                TRANCHE_NUMBER : data.TRANCHE_NUMBER,
                                                AMTMODALS : data.DRAWDOWN_VALUE,
                                                USERNAME: USERNAME,
                                                IDHEADER: data.IDHEADER
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

    $("#END_AMOUNT").on({
        'keyup': function() {
            
            if (parseFloat(formatDesimal($('#AMOUNTLIMIT').val())) < parseFloat(formatDesimal($('#END_AMOUNT').val()))) {
                toastr.error("Over Limit");
                $('#btnSave').attr('disabled',true);
            }
            else if($('#AMOUNT_BALANCE').val() != null || $('#AMOUNT_BALANCE').val() != ''){
                if (parseFloat(formatDesimal($('#AMOUNT_BALANCE').val())) < parseFloat(formatDesimal($('#END_AMOUNT').val()))) {
                    toastr.error("Over Limit");
                    $('#btnSave').attr('disabled',true);
                }
                else{
                    $('#btnSave').attr('disabled',false);
                }   
            }
            else{
                $('#btnSave').attr('disabled',false);
            }
        }
    });
    $('#TRANCHE_NUMBER').on({
        'change': function() {
            $('#loader').addClass('show');
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Kmk/getLimitTranche'); ?>",
                data: {
                    UUID: ID,
                    TRANCHE_NUMBER:$('#TRANCHE_NUMBER option:selected').text()
                },
                success: function (response) {
                    // console.log(response.AMOUNT_BALANCE);
                    var trancheNum = String($('#TRANCHE_NUMBER option:selected').text());
                    console.log(dummy_bal);
                    console.log(currentBalance);
                    $('#AMOUNTLIMIT').val(fCurrency(response.LIMIT_TRANCHE));
                    if(BEGIN) {
                        var bal = currentBalance.get(trancheNum);
                    }
                    else{
                        var bal = dummy_bal.get(trancheNum);
                    }
                    var ddown = totalDrawdown.get(trancheNum);
                    $('#AMOUNT_BALANCE').val(fCurrency(String(bal[0])));
                    $('#DRAWDOWN_VALUE').val(fCurrency(String(ddown)));
                    // console.log(currentBalance);
                    $('#loader').removeClass('show');
                }
            });
            
        }
    });

    $('#DRAWDOWN_TYPE').on({
        'change blur': function() {
            if (this.value == 'DISBURSEMENT') {
                $('#btnDetail').attr('disabled',true);
                $('#DRAWDOWN_VALUE').attr('disabled', false);
                $('#DRAWDOWN_VALUE').attr('required', true);
                $('#btnSave').attr('disabled', false);
            } 
            else if (this.value == 'REIMBURSEMENT') {
                $('#DRAWDOWN_VALUE').attr('disabled', true);
                $('#DRAWDOWN_VALUE').attr('required', false);
                $('#btnSave').attr('disabled', true);
                $('#btnDetail').attr('disabled',false);
            }
            else if (this.value == 'COMBINE') {
                $('#DRAWDOWN_VALUE').attr('disabled', true);
                $('#DRAWDOWN_VALUE').attr('required', false);
                $('#btnSave').attr('disabled', true);
                $('#btnDetail').attr('disabled',false);
            }
        }
    });

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

    var edited = 0 ;
    $('#DRAWDOWN_VALUE').on('focus', function () {
        var check = tempTNum != $("#TRANCHE_NUMBER option:selected").text()
        // console.log(edited);
        if(edited == 0 || check) {
            AMTMODALS = Number(formatDesimal($("#DRAWDOWN_VALUE").val()));
            // console.log(AMTMODALS);
            tempBalance = formatDesimal($("#DRAWDOWN_VALUE").val()) ;
            tempTNum = $("#TRANCHE_NUMBER option:selected").text();
            edited = 1 ;
        }
        });

    var SaveWD = function () {
        var fd = new FormData();
        edited = 0;
        // console.log(edited);
        fd.append('UUID', ID);
        fd.append('ID', IDHEADER);
        fd.append('TRANCHE_NUMBER', $("#TRANCHE_NUMBER option:selected").text());
        fd.append('DRAWDOWN_TYPE', $('#DRAWDOWN_TYPE').val());
        fd.append('DRAWDOWN_VALUE', $('#DRAWDOWN_VALUE').val());
        fd.append('USERNAME', USERNAME);
        fd.append('IDHEADER1', IDHEADER);
        fd.append('AMTMODALS', AMTMODALS);
        fd.append('PK_NUMBER', $('#PK_NUMBER').val());
        // console.log(AMTMODALS);

        if ($('#FAddEditFormbd').parsley().validate()) {
                $("#loader").show();
                $('#btnSave').attr('disabled', true);
                if(!(IDHEADER)) {
                    $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Kmk/SaveWDKI'); ?>",
                    data: {
                        UUID: ID,
                        PK_NUMBER : $('#PK_NUMBER').val(),
                        // AMOUNT_LIMIT:AMOUNT_LIMIT,
                        // DRAWDOWN_VALUE:$('#DRAWDOWN_VALUE').val(),
                        DRAWDOWN_TYPE:$('#DRAWDOWN_TYPE').val(),
                        VALUE_DATE: $('#VALUE_DATE').val(),
                        TRANCHE_NUMBER: $("#TRANCHE_NUMBER option:selected").text(),
                        RATE_IDRUSD: $('#RATE_IDRUSD').val(),
                        RATE_CNYUSD: $('#RATE_CNYUSD').val(),
                        RATE_SGDUSD: $('#RATE_SGDUSD').val(),
                        // WD_TYPE: SUB_CREDIT_TYPE,
                        USERNAME: USERNAME
                    },
                    success: function (response) {
                        if (response.status == 200) {
                            // toastr.success("Data Successfully Saved");
                            // setTimeout(function() { window.location.href = "<?php echo site_url('Withdraw'); ?>"; },1000);
                            // Edit(response.result.data);
                            var data = response.result.data ;
                            var idHeader = data[1];
                            IDHEADER = data[1];
                            fd.append("IDHEADER", idHeader);
                            $.ajax({
                                dataType : "JSON",
                                type : "POST",
                                url : "<?php echo site_url('Kmk/SaveWDKIDet');?>",
                                data : fd,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    $('#page-container').addClass('page-sidebar-minified');
                                    $('#loader').removeClass('show');
                                    if(response.status == 200) {
                                        STATUS = true ;
                                        data = response.result.data ;
                                        Uid = <?php echo json_encode($Uid);?>;
                                        BEGIN = 0 ;
                                        totalDrawdown.set(data.TNUM, data.DDOWN);
                                        // console.log(currentBalance);
                                        dummy_bal = setBalance(totalDrawdown, currentBalance);
                                        var dum_bal = dummy_bal.get($("#TRANCHE_NUMBER option:selected").text())
                                        $('#DRAWDOWN_VALUE').val(fCurrency(String(totalDrawdown.get($("#TRANCHE_NUMBER option:selected").text()))));
                                        $('#AMOUNT_BALANCE').val(fCurrency(String(dum_bal[0])))
                                        toastr.success("Data Successfully Saved");
                                        $('#DRAWDOWN_TYPE').attr('disabled', 'disabled');
                                        $('#VALUE_DATE').attr('disabled', 'disabled');
                                        $('#FAddEditFormbd').parsley().reset();
                                        $("#loader").hide();
                                        $('#btnSave').removeAttr('disabled');
                                    }
                                    else if (response.status == 500) {
                                        toastr.error(response.result.data);
                                    }
                                    else {
                                        toastr.error(response.result.data) ;
                                    }
                                },
                                error : function (e) {
                                    console.info(e) ;
                                    $('#loader').removeClass('show');
                                    toastr.error('Error Upload Data !!!');
                                }
                            }) ;
                        } else if (response.status == 504) {
                            toastr.error(response.result.data);
                            location.reload();
                        } else {
                            toastr.error(response.result.data);
                        }
                    },
                    complete:function(){
                        setTimeout(function() {
                            $.ajax({
                                    dataType: "JSON",
                                    type: "POST",
                                    url: "<?php echo site_url('Kmk/getLastBalanceKI'); ?>",
                                    data: {
                                        UUID: ID
                                    },
                                    success: function (response) {
                                        response.map((x) => {
                                            // console.log(x);
                                            var arrBalance = currentBalance.get(x.TRANCHE_NUMBER);
                                            arrBalance[0] =  Number((x.BALANCE != null) ? x.BALANCE : x.LIMIT_TRANCHE);
                                            currentBalance.set(x.TRANCHE_NUMBER, arrBalance);
                                        }) ;
                                    }
                                });
                        },1000);
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
                    $.ajax({
                            dataType : "JSON",
                            type : "POST",
                            url : "<?php echo site_url('Kmk/SaveWDKIDet');?>",
                            data : fd,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                $('#page-container').addClass('page-sidebar-minified');
                                $('#loader').removeClass('show');
                                if(response.status == 200) {
                                    STATUS = true ;
                                    data = response.result.data ;
                                    Uid = <?php echo json_encode($Uid);?>;
                                    BEGIN = 0 ;
                                    totalDrawdown.set(data.TNUM, data.DDOWN);
                                    // console.log(currentBalance);
                                    dummy_bal = setBalance(totalDrawdown, currentBalance);
                                    var dum_bal = dummy_bal.get($("#TRANCHE_NUMBER option:selected").text());
                                    $('#DRAWDOWN_VALUE').val(fCurrency(String(totalDrawdown.get($("#TRANCHE_NUMBER option:selected").text()))));
                                    $('#AMOUNT_BALANCE').val(fCurrency(String(dum_bal[0])));
                                    toastr.success("Data Successfully Saved");
                                    $('#DRAWDOWN_TYPE').attr('disabled', 'disabled');
                                    $('#FAddEditFormbd').parsley().reset();
                                    $("#loader").hide();
                                    $('#btnSave').removeAttr('disabled');
                                }
                                else if (response.status == 500) {
                                    toastr.error(response.result.data);
                                }
                                else {
                                    toastr.error(response.result.data) ;
                                }
                            },
                            error : function (e) {
                                console.info(e) ;
                                $('#loader').removeClass('show');
                                toastr.error('Error Upload Data !!!');
                            }
                        }) ;
                }
               
        }
        
    };

    $('#MDetail').on('hidden.bs.modal', function(e) {
      $(this).find('#FDetail')[0].reset();
    });
    // $('#FDetail').parsley().reset();

    function SaveWADetails(elm) {
        
        if(ID == null || ID == '' || ID == 0){
            toastr.error('ID KOSONG');
            window.location.href = "<?php echo site_url('Withdraw'); ?>";
        }
        if($("#TRANCHE_NUMBER option:selected").text() == null || $("#TRANCHE_NUMBER option:selected").text() == ''){
            toastr.error('Empty Tranche Number');
        }
        if($('#DRAWDOWN_TYPE').val() == null || $('#DRAWDOWN_TYPE').val() == ''){
            toastr.error('Empty Drawdown Type');
        }
        
        if($('#VALUE_DATE').val() == null || $('#VALUE_DATE').val() == ''){
            toastr.error('Empty Drawdown Date');
        }
        else{
            var DETID     = $('#DETID').val();
                $('#loader').addClass('show');
                        // files = elm.files;
                        // FILENAME = files[0].name;
                        // $(".panel-title_").text('Document Upload : ' + FILENAME);

                        // DisableBtn();
                        var fd = new FormData();
                        if($('#attachment').val() == null || $('#attachment').val() == '' || $('#attachment').val() == 0){
                            fd.append("userfile", '');
                            fd.append("FILENAME", '');
                        }else{
                            fd.append("userfile", $('#attachment')[0].files[0]);
                            fd.append("FILENAME", $('#attachment')[0].files[0].name);
                        }
                        // $.each(files, function (i, data) {
                        // });
                        // console.log(fd);return 0;
                        fd.append("USERNAME", USERNAME);
                        fd.append("UUID",ID);
                        fd.append("ID",DETID);
                        fd.append("IDHEADER1", IDHEADER);
                        fd.append("VENDOR", $('#IVENDOR').val());
                        fd.append("BANK",$('#IBANK').val());
                        fd.append("NOREK",  $("#INOREK").val());
                        fd.append("DRAWDOWN_DATE", $("#IDRAWDOWN_DATE").val());
                        fd.append("DRAWDOWN_TYPE", $("#DRAWDOWN_TYPE").val());
                        fd.append("INVOICE_DATE", $("#IINVOICE_DATE").val());
                        fd.append("INVOICE_NUMBER", $("#IINVOICE_NUMBER").val());
                        fd.append("DOCNUMBER", $("#IPO_NUMBER").val());
                        fd.append("INVOICE_VALUE", $("#IINVOICE_VALUE").val());
                        fd.append("DRAWDOWN_VALUE", $("#IDRAWDOWN_VALUE").val());
                        fd.append("CURRENCY", $("#ICURRENCY").val());
                        fd.append("TRANCHE_NUMBER", $("#TRANCHE_NUMBER option:selected").text());
                        fd.append("AMTMODALS", AMTMODALS);
                        fd.append('PK_NUMBER', $('#PK_NUMBER').val());
                        // fd.append("EXTSYSTEM",$('#EXTSYSTEM').val());
                        // fd.append("DOCTYPE",$('#DOCTYPE').val());
                        // fd.append('UUID',UUID)
                        // fd.append('DATERELEASE',currentDate);
                        // console.log(IDHEADER);
                        if(!(IDHEADER) && $('#FDetail').parsley().validate()) {
                            $.ajax({
                                dataType : 'JSON',
                                type : 'POST',
                                url : "<?php echo site_url('Kmk/SaveWDKI');?>",
                                data: {
                                    UUID : ID,
                                    PK_NUMBER : $('#PK_NUMBER').val(),
                                    DRAWDOWN_TYPE : $('#DRAWDOWN_TYPE').val(),
                                    VALUE_DATE : $('#VALUE_DATE').val(),
                                    TRANCHE_NUMBER : $("#TRANCHE_NUMBER option:selected").text(),
                                    RATE_IDRUSD: $('#RATE_IDRUSD').val(),
                                    RATE_CNYUSD: $('#RATE_CNYUSD').val(),
                                    RATE_SGDUSD: $('#RATE_SGDUSD').val(),
                                    USERNAME : USERNAME
                                },
                                success : function(response) {
                                    $("#loader").hide();
                                    $('#btnSave').removeAttr('disabled');
                                    if (response.status == 200) {
                                        var data = response.result.data ;
                                        // console.log(data);
                                        var idHeader = data[1];
                                        IDHEADER = data[1];
                                        fd.append("IDHEADER", idHeader);
                                        // setTimeout(function() { window.location.href = "<?php echo site_url('Withdraw'); ?>"; },1000);
                                        // Edit(response.result.data);
                                        $.ajax({
                                            dataType: "JSON",
                                            type: 'POST',
                                            url: "<?php echo site_url('Kmk/SaveWDKIDet'); ?>",
                                            data: fd,
                                            processData: false,
                                            contentType: false,
                                            success: function (response) {
                                                $('#page-container').addClass('page-sidebar-minified');
                                                $('#loader').removeClass('show');
                                                if (response.status == 200) {
                                                    STATUS = true;
                                                    data = response.result.data ;
                                                    Uid = <?php echo json_encode($Uid); ?>;
                                                    // console.log(currentBalance);
                                                    // DtUpload = response.result.data;
                                                    $('#MDetail').on('hidden.bs.modal', function(e) {
                                                        $(this).find('#FDetail')[0].reset();
                                                    });
                                                    $('#FDetail').parsley().reset();
                                                    $('#DRAWDOWN_TYPE').attr('disabled', 'disabled');
                                                    BEGIN = 0;
                                                    tempBalance = 0 ;
                                                    tempTNum = null;
                                                    // $('#DtUpload').removeClass('d-none');
                                                    $('#MDetail').modal('hide');
                                                    tbHeaderDet.ajax.reload();
                                                    toastr.success('Upload Success');
                                                    toastr.success("Data Successfully Saved");
                                                    // reloadUpload();
                                                } else if (response.status == 500) {
                                                    toastr.error(response.result.data);
                                                    // $('#btnReset').removeAttr('disabled');
                                                    // reloadUpload();
                                                } else {
                                                    toastr.error(response.result.data);
                                                    files = '';
                                                    $('.attachment').val('');
                                                    // $(".panel-title_").text('Upload Document');
                                                    // $('#btnReset').removeAttr('disabled');
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
                                                $('.attachment').val('');
                                                // $(".panel-title_").text('Upload Document');
                                                // DisableBtn();
                                            }
                                        });
                                    } else if (response.status == 504) {
                                        toastr.error(response.result.data);
                                        location.reload();
                                    } else {
                                        toastr.error(response.result.data);
                                    }   
                                }
                            }) ;
                        }
                        else if ($('#FDetail').parsley().validate()) {
                            $.ajax({
                                dataType: "JSON",
                                type: 'POST',
                                url: "<?php echo site_url('Kmk/SaveWDKIDet'); ?>",
                                data: fd,
                                processData: false,
                                contentType: false,
                                success: function (response) {
                                    $('#page-container').addClass('page-sidebar-minified');
                                    $('#loader').removeClass('show');
                                    if (response.status == 200) {
                                        STATUS = true;
                                        Uid = <?php echo json_encode($Uid); ?>;
                                        BEGIN = 0;
                                        // DtUpload = response.result.data;
                                        toastr.success('Upload Success');
                                        $('#MDetail').on('hidden.bs.modal', function(e) {
                                            $(this).find('#FDetail')[0].reset();
                                        });
                                        $('#FDetail').parsley().reset();
                                        $('#DRAWDOWN_TYPE').attr('disabled', 'disabled');
                                        // $('#DtUpload').removeClass('d-none');
                                        $('#MDetail').modal('hide');
                                        tbHeaderDet.ajax.reload();
                                        // reloadUpload();
                                    } else if (response.status == 500) {
                                        toastr.error(response.result.data);
                                        // $('#btnReset').removeAttr('disabled');
                                        // reloadUpload();
                                    } else {
                                        toastr.error(response.result.data);
                                        files = '';
                                        $('.attachment').val('');
                                        // $(".panel-title_").text('Upload Document');
                                        // $('#btnReset').removeAttr('disabled');
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
                                    $('.attachment').val('');
                                    // $(".panel-title_").text('Upload Document');
                                    // DisableBtn();
                                }
                            });
                        } else {
                            toastr.error('Fill all required forms') ;
                            $('#loader').removeClass('show');
                        }

        }
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
                                    html += "<option value='" + CValue + "'>" + value.FCNAME + ' (Default) </option>';
                                } else {
                                    html += "<option value='" + CValue + "'>" + value.FCNAME +  '</option>';
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

        $('#IDRAWDOWN_VALUE').on('change keyup', function (){
        // console.log($(this).val());
        var tnum = tempTNum ? tempTNum : $("#TRANCHE_NUMBER option:selected").text();
        var dum_bal = dummy_bal.get(tnum) ;
        var cur_bal = currentBalance.get(tnum);
        if(Number(tempBalance) != 0) {
            var bal = (dum_bal[0] != 0) ? dum_bal[0] : cur_bal[0];
            bal += Number(tempBalance) ;
        }
        else {
            var bal = (dum_bal[0] != 0) ? dum_bal[0] : cur_bal[0];
        }
        
        var ddown = Number(formatDesimal($(this).val()));
        if( ddown > Number(bal)) {
            toastr.error('Drawdown Value Over Limit') ;
            $('#btnSaveFile').attr('disabled', true);
        } 
        else {
            $('#btnSaveFile').attr('disabled', false);
        }
    }) ;
    $('#DRAWDOWN_VALUE').on('change keyup', function (){
        // console.log($(this).val());
        var tnum = tempTNum ? tempTNum : $("#TRANCHE_NUMBER option:selected").text();
        var dum_bal = dummy_bal.get(tnum) ;
        var cur_bal = currentBalance.get(tnum);
        if(Number(tempBalance) != 0) {
            var bal = (dum_bal[0] != 0) ? dum_bal[0] : cur_bal[0];
            bal += Number(tempBalance) ;
        }
        else {
            var bal = (dum_bal[0] != 0) ? dum_bal[0] : cur_bal[0];
        }
        var ddown = Number(formatDesimal($(this).val()));
        // console.log(dummy_bal.get(tnum));
        if( ddown > Number(bal)) {
            toastr.error('Drawdown Value Over Limit') ;
            $('#btnSave').attr('disabled', true);
        } 
        else {
            $('#btnSave').attr('disabled', false);
        }
    }) ;

    
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
                    "url": "<?php echo site_url('Kmk/ShowFileData') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function(d) {
                        d.UUID = DtUpload;
                        d.SUB_CREDIT_TYPE = "KI";
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
                                SUB_CREDIT_TYPE: "KI",
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
                        $.each(files, function (i, data) {
                            fd.append("userfile", data);
                        });
                        fd.append("USERNAME", USERNAME);
                        fd.append("UUID",ID);
                        fd.append("TIPE",$('#tipe_file').val());
                        fd.append("SUB_CREDIT_TYPE","KI");
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

    var setBalance = function (totalDrawdown, currentBalance) {
        // console.log(currentBalance);
        var dummy_cb = new Map(JSON.parse(JSON.stringify(Array.from(currentBalance))));
        for(let [key,value] of dummy_cb) {
            var arr = value;
            arr[0] = arr[0] - totalDrawdown.get(key);
            dummy_cb.set(key, arr) ;
        }
        return dummy_cb ;
    }

    $(document).on('click', '.showChild', function () {
        var siteTable = $('#DtLeasing').DataTable();
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
        var table = $('<table class="display" width="100%"/>') ;
        // console.log(data);
        row.child(table).show();
        var childTable = table.DataTable({
            "processing": true,
            "ajax": {
                "url": "<?php echo site_url('Kmk/getOWTrancheKIbyID')?>",
                dataType: "JSON",
                type: "POST",
                "data": {
                    BATCHID : data.ID,
                    UUID : data.UUID
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
                        "title" : "Balance",
                        "data"  : "BALANCE",
                        render: function (data, type, row, meta) {
                            return row.CURRENCY + ' ' + renderer(row.BALANCE) ;
                        }
                    },
                    {
                        "title" : "Drawdown Amount",
                        "data"  : "DDOWN_AMT",
                        render: function (data, type, row, meta) {
                            return row.CURRENCY + ' ' + renderer(row.DDOWN_AMT) ;
                        }
                    },
                ],
                "bFilter": true,
                "bPaginate": true,
                "bLengthChange": false,
                "bInfo": true
        });
    }
    // DisableBtn();
</script>