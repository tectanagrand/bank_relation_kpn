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
        <h4 class="panel-title">Approval Withdrawal</h4>
    </div>
    <div class="panel-body">
        <?php if (empty($_GET)) { ?>
            <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#tb1" data-toggle="tab" class="leasingdata nav-link active">
                <span class="d-sm-none">RK / BD / TL / WA</span>
                <span class="d-sm-block d-none">RK / BD / TL / WA</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#wdki" data-toggle="tab" class="leasingdata nav-link">
                <span class="d-sm-none">Kredit Investasi</span>
                <span class="d-sm-block d-none">Kredit Investasi</span>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade active show" id="tb1">
            <div class="row mb-2">
                <div class="col-md-8 pull-left">
                    <h3>Approval Withdrawal</h3>
                </div>
                <div class="col-md-4 pull-right">
                    <div class="input-group ">
                        <input type="text" id="search" name="search" class="form-control" placeholder="Cari.." >
                    </div>
                </div>
            </div>
            <div class="row m-0 table-responsive">
                <table id="DtLeasing" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" width="100%" aria-describedby="DtLeasing_info">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc">No</th>
                            <th class="text-center sorting">Company</th>
                            <th class="text-center sorting">Contract Number</th>
                            <th class="text-center sorting">PK Number</th>
                            <th class="text-center sorting">Credit Type</th>
                            <th class="text-center sorting">Sub Credit Type</th>
                            <th class="text-center sorting">Status</th>
                            <th class="text-center sorting_disabled" aria-label="Action">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="wdki">
            <div class="row mb-2">
                <div class="col-md-8 pull-left">
                    <h3>Approval Withdrawal KI</h3>
                </div>
                <div class="col-md-4 pull-right">
                    <div class="input-group ">
                        <input type="text" id="searchki" name="searchki" class="form-control" placeholder="Cari.." >
                    </div>
                </div>
            </div>
            <div class="row m-0 table-responsive">
                <table id="dtKI" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" width="100%" aria-describedby="dtKI_info">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc">No</th>
                            <th class="text-center sorting">Company</th>
                            <th class="text-center sorting">Contract Number</th>
                            <th class="text-center sorting">PK Number</th>
                            <th class="text-center sorting">Credit Type</th>
                            <th class="text-center sorting">Sub Credit Type</th>
                            <th class="text-center sorting">Status</th>
                            <th class="text-center sorting_disabled" aria-label="Action">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
            <!-- <div class="row mb-2">
                <div class="col-md-8 pull-left">
                    <h3>Approval Withdrawal</h3>
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
                            <th class="text-center sorting_asc">No</th>
                            <th class="text-center sorting">Company</th>
                            <th class="text-center sorting">Contract Number</th>
                            <th class="text-center sorting">PK Number</th>
                            <th class="text-center sorting">Credit Type</th>
                            <th class="text-center sorting">Sub Credit Type</th>
                            <th class="text-center sorting">Status</th>
                            <th class="text-center sorting_disabled" aria-label="Action">#</th>
                        </tr>
                    </thead>
                </table>
            </div> -->
        <?php } else { ?>
            <!-- not used -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#rekeningkoran" data-toggle="tab" class="leasingdata nav-link active">
                    <span class="d-sm-none">Tab 1</span>
                    <span class="d-sm-block d-none">KMK Rekening Koran</span>
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
                                <option value="KMK" selected>Kredit Modal Kerja</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Sub Credit Type *</label>
                            <select name="SUB_CREDIT_TYPE" id="SUB_CREDIT_TYPE" class="form-control">
                                <option value="RK">Rekening Koran</option>
                                <!-- <option value="WA">WA</option>
                                <option value="FINANCING">FINANCING</option>
                                <option value="REFINANCING">REFINANCING</option> -->
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
                                <label for="fcname">End Balance *</label>
                                <input type="text" class="form-control" data-type='currency' name="END_AMOUNT" id="END_AMOUNT" placeholder="Amount" required="">
                            </div>
                        </div>
                        <button type="button" id="btnSave" onclick="SaveWD()" class="btn btn-primary btn-sm m-l-5">Save</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- not used -->
        <!-- RK End -->
        <!--  -->
        <?php } ?>
    </div>
    <!-- modal view  -->
    <?php if (!empty($_GET)) { ?>
                        <div class="panel-footer text-left">
                            <button type="button" class="btn btn-warning btn-sm m-l-5" onclick="Cancel()">Back</button>
                        </div>
                    <?php } ?>
</div>

<script>
    toastr.options = {
        positionClass: 'toast-top-center'
    };
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
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
    var table,tableKI, ACTION, ID;
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
            }
        } else {
            if (!$.fn.DataTable.isDataTable('#DtLeasing')) {
                $('#DtLeasing').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('Kmk/showApprovalWithdrawal') ?>",
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
                    "columns": [{
                        "data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {"data": "COMPANYCODE","className": "text-center"},
                    {"data": "CONTRACT_NUMBER","className": "text-center"},
                    {"data": "PK_NUMBER","className": "text-center"},
                    {"data": "CREDIT_TYPE","className": "text-center"},
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            var html;
                            if (data.SUB_CREDIT_TYPE_1 != null) {
                                    html = data.SUB_CREDIT_TYPE_1;
                            }else{
                                html = data.SUB_CREDIT_TYPE;
                            }
                            return html;
                        }
                    },
                    // {"data": "SUB_CREDIT_TYPE_1"},
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            var html = '';
                            if (EDITS == 1) {
                                    if(data.STATUS == '1'){
                                        html += '<span class="badge bg-success">Approved</span>'
                                    }else if(data.STATUS == '2'){
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
                                    html += '<button type="button" class="btn btn-info btn-xs edit mr-2" title="edit data" data-id="'+data.UUID+'">View Details</button>';
                                    if(data.STATUS == '0'){
                                        html += '<button type="button" class="btn btn-yellow btn-xs mr-2 accept" title="edit data" data-id="'+data.UUID+'">Approve</button>';
                                        html += '<button type="button" class="btn btn-danger btn-xs decline" title="edit data" data-id="'+data.UUID+'">Decline</button>';
                                    }
                                    // else if(data.STATUS == '1'){
                                    //     html += '<button type="button" class="btn btn-danger btn-xs decline" title="edit data" data-id="'+data.UUID+'">Decline</button>';
                                    // }
                                    else if(data.STATUS == '2'){
                                        html += '<button type="button" class="btn btn-yellow btn-xs mr-2 accept" title="edit data" data-id="'+data.UUID+'">Approve</button>';
                                    }
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
                table.on('click', '.accept', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    if (confirm('Are you sure confirm this data ?')) {
                        $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('Kmk/acceptWD'); ?>",
                            data: {
                                ID:data.ID,
                                UUID: data.UUID,
                                SUB_CREDIT_TYPE: data.SUB_CREDIT_TYPE_1,
                                SUB_WD_TYPE: data.SUB_SCT_1,
                                STATUS:1,
                                USERNAME: USERNAME
                            },
                            success: function (response) {
                                $message = response.result.data;
                                console.log($message);
                                if (response.status == 200) {
                                    console.log($message);
                                    alert($message.MESSAGE);
                                    table.ajax.reload();
                                } else if (response.status == 504) {
                                    console.log($message);
                                    alert($message.MESSAGE);
                                    location.reload();
                                } else {
                                    console.log($message);
                                    alert($message.MESSAGE);
                                }
                            },
                            error: function (e) {
                                alert('Error Updating data !!');
                            }
                        });
                    }
                });
                table.on('click', '.decline', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    if (confirm('Are you sure confirm this data ?')) {
                        $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('Kmk/acceptWD'); ?>",
                            data: {
                                ID:data.ID,
                                UUID: data.UUID,
                                SUB_CREDIT_TYPE: data.SUB_CREDIT_TYPE,
                                STATUS:2,
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
                                alert('Error Updating data !!');
                            }
                        });
                    }
                });
                table.on('click', '.edit', function() {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    console.log(data);
                    if(data.SUB_CREDIT_TYPE == 'RK'){
                        //window.open(window.location.href + '?type=edit&UUID=' + data.UUID + '&IDDET='+data.ID,'_self');
                        window.open("<?php echo site_url('WithdrawData'); ?>" + '?type=edit&UUID=' + data.UUID + '&IDDET='+data.ID+ '&vm=vm','_self');    
                    }
                    if(data.SUB_CREDIT_TYPE == 'TL'){
                        window.open("<?php echo site_url('WithdrawDataTL'); ?>" + '?type=edit&UUID=' + data.UUID + '&IDDET='+data.ID+ '&vm=vm','_self');
                    }
                    if(data.SUB_CREDIT_TYPE == 'WA' && data.SUB_SCT_1 == null){
                        window.open("<?php echo site_url('WithdrawDataWA'); ?>" + '?type=edit&UUID=' + data.UUID + '&IDDET='+data.ID+ '&vm=vm','_self');   
                    }
                    if(data.SUB_CREDIT_TYPE == 'WA' && data.SUB_SCT_1 == 'KMK_SCF_AR'){
                        window.open("<?php echo site_url('WithdrawDataAR'); ?>" + '?type=edit&UUID=' + data.UUID+'&IDDET='+data.ID+'&IDDETAP='+data.IDDETAPAR+'&ACT='+'view','_self');   
                    }
                    if(data.SUB_CREDIT_TYPE == 'WA' && data.SUB_SCT_1 == 'KMK_SCF_AP'){
                        window.open("<?php echo site_url('WithdrawDataAP'); ?>" + '?type=edit&UUID=' + data.UUID+'&IDDET='+data.ID+'&IDDETAP='+data.IDDETAPAR+'&ACT='+'view','_self');   
                    }
                    if(data.SUB_CREDIT_TYPE == 'KI' ){
                        window.open("<?php echo site_url('WithdrawDataKI'); ?>" + '?type=edit&UUID=' + data.UUID+ '&vm=vm','_self');   
                    }
                    if(data.SUB_CREDIT_TYPE == 'BD'){
                        window.open("<?php echo site_url('WithdrawDataNDK'); ?>" + '?type=edit&UUID=' + data.UUID + '&IDDET='+ data.ID+ '&vm=vm','_self');    
                    }
                    
                    // else{
                    //     window.open("<?php echo site_url('WithdrawNDK'); ?>" + '?type=edit&UUID=' + data.UUID,'_self');
                    //     // window.location.href = "<?php echo site_url('WithdrawNDK'); ?>" + '?type=edit&UUID=' + data.UUID;
                    // }
                    
                });
                $("#DtLeasing_filter").remove();
                $("#search").on({
                    'keyup': function () {
                        table.search(this.value, true, false, true).draw();
                    }
                });
            }
            if (!$.fn.DataTable.isDataTable('#dtKI')) {
                $('#dtKI').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('Kmk/showApprovalWithdrawalKI') ?>",
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
                    "columns": [{
                        "data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {"data": "COMPANYCODE","className": "text-center"},
                    {"data": "CONTRACT_NUMBER","className": "text-center"},
                    {"data": "PK_NUMBER","className": "text-center"},
                    {"data": "CREDIT_TYPE","className": "text-center"},
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            var html;
                            if (data.SUB_CREDIT_TYPE_1 != null) {
                                    html = data.SUB_CREDIT_TYPE_1;
                            }else{
                                html = data.SUB_CREDIT_TYPE;
                            }
                            return html;
                        }
                    },
                    // {"data": "SUB_CREDIT_TYPE_1"},
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            var html = '';
                            if (EDITS == 1) {
                                    if(data.STATUS == '1'){
                                        html += '<span class="badge bg-success">Approved</span>'
                                    }else if(data.STATUS == '2'){
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
                                    html += '<button type="button" class="btn btn-info btn-xs edit mr-2" title="edit data" data-id="'+data.UUID+'">View Details</button>';
                                    if(data.STATUS == '0'){
                                        html += '<button type="button" class="btn btn-yellow btn-xs mr-2 acceptki" title="edit data" data-id="'+data.UUID+'">Approve</button>';
                                        html += '<button type="button" class="btn btn-danger btn-xs declineki" title="edit data" data-id="'+data.UUID+'">Decline</button>';
                                    }
                                    // else if(data.STATUS == '1'){
                                    //     html += '<button type="button" class="btn btn-danger btn-xs decline" title="edit data" data-id="'+data.UUID+'">Decline</button>';
                                    // }
                                    else if(data.STATUS == '2'){
                                        html += '<button type="button" class="btn btn-yellow btn-xs mr-2 acceptki" title="edit data" data-id="'+data.UUID+'">Approve</button>';
                                    }
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
                $('#dtKI thead th').addClass('text-center');
                tableKI = $('#dtKI').DataTable();
                tableKI.on('click', '.acceptki', function () {
                    $tr = $(this).closest('tr');
                    var data = tableKI.row($tr).data();
                    if (confirm('Are you sure confirm this data ?')) {
                        $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('Kmk/acceptWDKI'); ?>",
                            data: {
                                ID:data.ID,
                                UUID: data.UUID,
                                BATCHID: data.ID,
                                SUB_CREDIT_TYPE: data.SUB_CREDIT_TYPE,
                                STATUS:1,
                                USERNAME: USERNAME
                            },
                            success: function (response) {
                                if (response.status == 200) {
                                    alert(response.result.data[0].MESSAGE);
                                    tableKI.ajax.reload();
                                } else if (response.status == 504) {
                                    alert(response.result.data.MESSAGE);
                                    location.reload();
                                } else {
                                    alert(response.result.data.MESSAGE);
                                }
                            },
                            error: function (e) {
                                alert('Error Updating data !!');
                            }
                        });
                    }
                });
                tableKI.on('click', '.declineki', function () {
                    $tr = $(this).closest('tr');
                    var data = tableKI.row($tr).data();
                    if (confirm('Are you sure confirm this data ?')) {
                        $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('Kmk/acceptWDKI'); ?>",
                            data: {
                                ID:data.ID,
                                UUID: data.UUID,
                                SUB_CREDIT_TYPE: data.SUB_CREDIT_TYPE,
                                STATUS:2,
                                USERNAME: USERNAME
                            },
                            success: function (response) {
                                if (response.status == 200) {
                                    alert(response.result.data.MESSAGE);
                                    tableKI.ajax.reload();
                                } else if (response.status == 504) {
                                    alert(response.result.data.MESSAGE);
                                    location.reload();
                                } else {
                                    alert(response.result.data.MESSAGE);
                                }
                            },
                            error: function (e) {
                                alert('Error Updating data !!');
                            }
                        });
                    }
                });
                tableKI.on('click', '.edit', function() {
                    $tr = $(this).closest('tr');
                    var data = tableKI.row($tr).data();
                    console.log(data);
                    
                    if(data.SUB_CREDIT_TYPE == 'KI' ){
                        window.open("<?php echo site_url('WithdrawDataKI'); ?>" + '?type=edit&UUID=' + data.UUID+ '&IDDET='+ data.ID+ '&vm=vm','_self');   
                    }
                    
                });
                $("#dtKI_filter").remove();
                $("#searchki").on({
                    'keyup': function () {
                        tableKI.search(this.value, true, false, true).draw();
                    }
                });
            }
        }
    });
    var Add = function () {
        window.location.href = window.location.href + '?type=add';
    };
    function Cancel() {
        history.go(-1)
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

    var CREDIT_TYPE,SUB_CREDIT_TYPE;

    function SetData(data) {
        var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
        // console.log(data);
        $('.panel-title').text('Edit Data Kmk');
        ID = data.UUID;
        $('#COMPANY option[value='+data.COMPANY+']').attr('selected','selected');
        $('#BUSINESSUNIT').append($('<option>', {
            value: data.BUID,
            text: data.BUFCNAME
        }));
        $('#CREDIT_TYPE option[value='+data.CREDIT_TYPE+']').attr('selected','selected');
        $('#SUB_CREDIT_TYPE option[value='+data.SUB_CREDIT_TYPE+']').attr('selected','selected');
        $('#PK_NUMBER').val(data.PK_NUMBER);
        if(data.AMOUNT_LIMIT == null || data.AMOUNT_LIMIT == 0){
            $('#AMOUNTLIMIT').val();    
        }else{
            $('#AMOUNTLIMIT').val(fCurrency(data.AMOUNT_LIMIT));
        }
        if(data.AMOUNT_BALANCE == null || data.AMOUNT_BALANCE == 0){
            $('#AMOUNT_BALANCE').val();    
        }else{
            $('#AMOUNT_BALANCE').val(fCurrency(data.AMOUNT_BALANCE));
        }
        // $('#AMOUNT_BALANCE').val(data.AMOUNT_BALANCE);
        $('#COMPANY').attr('readonly', true);
        $('#PK_NUMBER').attr('readonly', true);
        $('#BUSINESSUNIT').attr('readonly', true);
        $('#CONTRACT_NUMBER').attr('readonly', true);
        $('#CREDIT_TYPE').attr('readonly', true);
        $('#SUB_CREDIT_TYPE').attr('readonly', true);
        $('#AMOUNT_BALANCE').attr('readonly', true);
        ACTION = 'EDIT';
        CREDIT_TYPE     = data.CREDIT_TYPE;
        SUB_CREDIT_TYPE = data.SUB_CREDIT_TYPE;
        AMOUNT_LIMIT    = data.AMOUNT_LIMIT;
        // if((CREDIT_TYPE == "KMK" && SUB_CREDIT_TYPE == "BD") || (CREDIT_TYPE == "KMK" && SUB_CREDIT_TYPE == "RK") ){
        $('.bd').removeClass('d-none');
        // }
        // $('.btnSave').addClass('d-none');
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

    var SaveWD = function () {
        
        if ($('#FAddEditFormbd').parsley().validate()) {
                $("#loader").show();
                $('#btnSave').attr('disabled', true);
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Kmk/SaveWD'); ?>",
                    data: {
                        UUID: ID,
                        AMOUNT_LIMIT:AMOUNT_LIMIT,
                        AMOUNT: $('#END_AMOUNT').val(),
                        WD_TYPE: SUB_CREDIT_TYPE,
                        USERNAME: USERNAME
                    },
                    success: function (response) {
                        $("#loader").hide();
                        $('#btnSave').removeAttr('disabled');
                        if (response.status == 200) {
                            toastr.success("Data Successfully Saved");
                            setTimeout(function() { window.location.href = "<?php echo site_url('Withdraw'); ?>"; },1000);
                            // Edit(response.result.data);
                        } else if (response.status == 500) {
                            toastr.error(response.result.data);
                            // location.reload();
                        } else {
                            toastr.error(response.result.data);
                        }
                    },
                    // complete:function(){
                    //     setTimeout(function() {
                    //         $.ajax({
                    //                 dataType: "JSON",
                    //                 type: "POST",
                    //                 url: "<?php echo site_url('Kmk/getLastBalance'); ?>",
                    //                 data: {
                    //                     UUID: ID
                    //                 },
                    //                 success: function (response) {
                    //                     // console.log(response.AMOUNT_BALANCE);
                    //                     $('#AMOUNT_BALANCE').val(response.AMOUNT_BALANCE);
                    //                 }
                    //             });
                    //     },1000);
                    // },
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
    // DisableBtn();
</script>