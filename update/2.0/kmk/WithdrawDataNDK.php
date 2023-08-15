<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<!-- <ol class="breadcrumb pull-right">
anel-tianel-titletle    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
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
        <h4 class="panel-title">Withdraw Funds</h4>
    </div>
    <div class="panel-body">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#scfndk" data-toggle="tab" class="leasingdata nav-link active">
                    <span class="d-sm-none">Tab 1</span>
                    <span class="d-sm-block d-none">KMK SCF Non Diskonto (KMK Biodiesel)</span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active show" id="scfndk">
                <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="fccode">Company *</label>
                            <select class="form-control mkreadonly" name="COMPANY" id="COMPANY" disabled>
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
                            <select class="form-control businessunit" id="BUSINESSUNIT" name="BUSINESSUNIT" required disabled>
                                <!-- <option selected>Select Business Unit</option> -->
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="description">Contract Number </label>
                            <input type="text" class="form-control" id="CONTRACT_NUMBER" required disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="description">PK Number </label>
                            <input type="text" class="form-control" id="PK_NUMBER" required disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Credit Type </label>
                            <select name="CREDIT_TYPE" id="CREDIT_TYPE" class="form-control" disabled>
                                <option value="KMK" selected>Kredit Modal Kerja</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="description">Sub Credit Type *</label>
                            <select name="SUB_CREDIT_TYPE" id="SUB_CREDIT_TYPE" class="form-control" disabled>
                                <option value="" disabled="" selected="">--Choose--</option>
                                <option value="BD">Non Diskonto</option>
                                <option value="RK">Rekening Koran</option>
                                <option value="TL">Time Loan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <input id="DATA_EXIST" hidden/> 
                        <div class="form-group col-md-3">
                            <label for="fcname">Max Limit *</label>
                            <input type="text" class="form-control" name="AMOUNTLIMIT" id="AMOUNTLIMIT" disabled >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fcname">Balance *</label>
                            <input type="text" class="form-control" name="BALANCE" id="BALANCE" disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fcname">Interest</label>
                            <input type="text" class="form-control" name="INTEREST" id="INTEREST" disabled>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- BD Start -->
        
        <div class="panel panel-success bd d-none">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form id="FAddEditFormbd" data-parsley-validate="true" data-parsley-errors-messages-disabled="" onsubmit="return false" novalidate="">
                        <div class="row">
                            <div class="form-group col-md-4">
                                    <label for="fcname">Value Date</label>
                                    <input type="date" class="form-control" name="VALUE_DATE" id="VALUE_DATE" placeholder="value date" required/>
                            </div>
                            <div class="form-group col-md-4">
                                    <label for="fcname">SCF Non Diskonto Type</label>
                                    <select name="SCF_NONDISKONTO_TYPE" id="SCF_NONDISKONTO_TYPE" class="form-control">
                                        <option value="BDPPKS">BDPPKS </option>
                                        <option value="BU_BBM">BU BBM </option>
                                    </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- BD End -->
        <hr>
        <!-- form detail -->
            <!-- <div class="row">
                <div class="col-md-12">
                    <button id="btnDetail" type="button" onclick="AddForm()" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> Add</button>
                </div>
            </div> -->
            <div class="row m-0 table-responsive">
                <table id="Table_DtWDNDK" class="table table-bordered table-hover dataTable" role="grid" width="100%" style="width: 100%;">
                    <thead>
                        <tr role="row">
                            <th class="text-center align-middle">No</th>
                            <th class="text-center align-middle">Value Date</th>
                            <th class="text-center align-middle">SCF NDK Type</th>
                            <th class="text-center align-middle">Vendor</th>
                            <th class="text-center align-middle">Application Letter Date</th>
                            <th class="text-center align-middle">Application Letter Number</th>
                            <th class="text-center align-middle">BAST Date</th>
                            <th class="text-center align-middle">BAST Number</th>
                            <th class="text-center align-middle">Invoice Date</th>
                            <th class="text-center align-middle">Invoice Number</th>
                            <th class="text-center align-middle">Billing Value</th>
                            <th class="text-center align-middle">Drawdown Amount</th>
                            <th class="text-center align-middle">Due Date</th>
                            <th class="text-center align-middle">Attachment</th>
                            <th>#</th>
                        </tr>
                    </thead>
                </table>`
            </div>
            <hr/>
            <!-- attachment file -->
            <div class="row mt-2 fileupload-buttonbar">
                <div class="col-md-4">
                    <strong>Please insert Withdrawal Letter and Invoice</strong>
                    <br/>
                    <label for="address">Attachment *</label>
                    <select name="tipe_file" id="tipe_file" class="form-control mb-2">
                        <option value="WITHDRAWAL_LETTER">WITHDRAWAL LETTER</option>
                        <option value="INVOICE">INVOICE</option>
                    </select>
                    <span class="btn btn-primary fileinput-button m-r-3">
                        <span>Browse File</span>
                        <input type="file" class="upload-file" data-max-size="1048576" onchange="filesChange(this)">
                    </span>
                    <button id="btnReset" type="button" class="btn btn-default m-r-3" onclick="ClearData()" disabled="disabled">
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
                        </tr>
                    </thead>
                </table>
            </div>
        <hr>
        <!--  -->
    </div>

     <!-- START MODAL DETAIL -->
     <div class="modal fade" id="MDetail">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Withdrawal Form Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <form id="WDNDKDetail" data-parsley-validate="true" data-parsley-errors-messages-disabled  enctype="multipart/form-data" onsubmit="return false">
                    <div class="modal-body">
                        <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="fcname">Vendor <span id="BIC"></span></label>
                                    <select id="VENDOR" name="VENDOR" class="form-control vendor" required> 
                                
                                    </select>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Application Letter Date</label>
                                    <input type="date" class="form-control" id="APP_LETTER_DATE" name="APP_LETTER_DATE"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Application Letter Number</label>
                                    <input type="text" class="form-control" id="APP_LETTER_NUM" name="APP_LETTER_NUM"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>BAST Date</label>
                                    <input type="date" class="form-control" id="BAST_DATE" name="BAST_DATE" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>BAST Number</label>
                                    <input type="text" class="form-control" id="BAST_NUM" name="BAST_NUM" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Invoice Date</label>
                                    <input type="date" class="form-control" id="INV_DATE" name="INV_DATE" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Invoice Number</label>
                                    <input type="text" class="form-control" id="INV_NUM" name="INV_NUM" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Billing Value</label>
                                <input type="text" class="form-control text-right" id="BIL_VAL" name="BIL_VAL" data-type="currency" required/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Drawdown Amount</label>
                                <input type="text" class="form-control text-right" id="DDOWN_AMT" name="DDOWN_AMT" data-type="currency" disabled required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Due Date</label>
                                <input type="date" class="form-control" id="DUE_DATE" name="DUE_DATE"/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Attachment File</label>
                                <select name="FILE_TYPE" id="FILE_TYPE" class="form-control">
                                    <option value="BATCH_INVOICE">Batch Invoice</option>
                                </select>
                                <input type="file" name="ATTACHMENT_WDNDK" class="upload-file-wdndk"/>
                                <input type="hidden" id="DETID">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="CLOSE" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="SAVE_WDNDK" name="SAVE_WDNDK" onclick=saveWDNDK(ATTACHMENT_WDNDK)>Save</button>
                        <input style="display:none;" type="button" class="btn btn-info" id="updateTemp" value="Update">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END MODAL DETAIL -->
    <!-- modal view  -->
    <?php if (!empty($_GET)) { ?>
                        <div class="row panel-footer text-left">
                            <button type="button" class="btn btn-warning btn-sm m-l-5" onclick="Cancel()">Back</button>
                        </div>
                    <?php } ?>
</div>
<!-- -------------------------SCRIPT SECTION-------------------------- -->

<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var DtKmk = <?php echo json_encode($DtKmk); ?>;
    var DtWD_ByUUID = <?php echo json_encode($DtWD_ByUUID);?> ;
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
    var table, ACTION, ID, AMOUNT, BALANCE;
    $(document).ready(function () {
        
    //     $('#VALUE_DATE').val(function() {
    //     if(!$(this).val()) {
    //         $('#btnDetail').attr('disabled', true);
    //     }
    //     else {
    //         $('#btnDetail').attr('disabled', false);
    //     }
    // })

    //     $('#VALUE_DATE').on("change", function() {
    //     if(!$(this).val()) {
    //         $('#btnDetail').attr('disabled', true);
    //     }
    //     else {
    //         $('#btnDetail').attr('disabled', false);
    //     }
    // })
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};
        if (getUrlParameter('type') == "edit" || getUrlParameter('type') == "add") {
            UrlParam = getUrlParameter('type');
            if (getUrlParameter('type') == "add") {
                if (ADDS != 1) {
                    $('#btnSave').remove();
                }
                SetDataKosong(); //placing holder for new add form to empty
            } else {
                if(getUrlParameter('ACT') == 'view' || DtKmk.STATUS == 1 || DtKmk.STATUS == 2) {
                    $('#btnEdit').remove();
                    $('#upload-button').attr("disabled", true);
                    $('#btnDetail').remove();
                }
                if (EDITS != 1) {
                    $('#btnSave').remove();
                }
                var data = <?php echo json_encode($DtKmk); ?>;
                SetData(data); //display existed data on view for editing
                $('#MDetail select').css('width', '100%');
                $('#VENDOR').select2({
                    dropdownParent: $('#MDetail'),
                    placeholder:"Select a vendor",
                    width: 'resolve',
            // theme: 'bootstrap4',
            ajax: {
                    url: "<?php echo site_url('Leasing/getVendor') ?>",
                    dataType: 'JSON',
                    delay: 250,
                    type: 'GET',
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function(data1, page) {
                        // parse the results into the format expected by Select2.
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data
                        return {
                            results: $.map(data1, function (item) {
                                return {
                                    id:item.ID,
                                    text:item.TEXT,
                                    bic:item.BIC
                                }
                            })
                        };
                    },
                    cache: true,
                    error: function(e){
                        console.info(e);
                    },
                },
                escapeMarkup: function(markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                //templateResult: formatRepo,
                //templateSelection: formatRepoSelection
            });
            $('#VENDOR').on('select2:select', function (e) {
                    var data1 = e.params.data;
                    $('#BIC').text(data.bic);
                });
            }
        } else { //start table display all data here
            if (!$.fn.DataTable.isDataTable('#DtLeasing')) { //not used here
                $('#DtLeasing').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('Kmk/ShowDataWithdraw') ?>",
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
                    {"data": "COMPANYCODE"},
                    {"data": "CONTRACT_NUMBER"},
                    {"data": "PK_NUMBER"},
                    {"data": "CREDIT_TYPE"},
                    {"data": "SUB_CREDIT_TYPE"},
                    {
                        "data": "AMOUNT_LIMIT",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
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
                            // console.log(data);
                            // console.log(EDITS);
                            EDITS = 1;
                            if (EDITS == 1) {
                                    html += '<button class="btn btn-info btn-icon btn-circle btn-sm mr-2 edit" title="edit data" data-id="'+data.UUID+'"><i class="fas fa-edit"></i></button>';
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
                    if(data.SUB_CREDIT_TYPE == 'RK' || data.SUB_CREDIT_TYPE ==  'WA' || data.SUB_CREDIT_TYPE ==  'TL'){
                        window.location.href = window.location.href + '?type=edit&UUID=' + data.UUID;    
                    }else{
                        window.location.href = "<?=site_url('WithdrawNDK')?>" + '?type=edit&UUID=' + data.UUID;
                    }
                    
                });
                $("#DtLeasing_filter").remove();
                $("#search").on({
                    'keyup': function () {
                        table.search(this.value, true, false, true).draw();
                    }
                });

            }
        }//end table display all data here
        $("#MDetail").on('show.bs.modal', function () {
            var value_SCFNDKtype = $('#SCF_NONDISKONTO_TYPE').val();
            var modal = $(this);
            if (value_SCFNDKtype == "BU_BBM") {
                $('#APP_LETTER_NUM').prop('disabled',true);
                $('#APP_LETTER_DATE').prop('disabled',true);
            } else {
                $('#APP_LETTER_NUM').prop('disabled',false);
                $('#APP_LETTER_DATE').prop('disabled',false);
            }
        });
        $("#CLOSE").on('click', function () {
            $("#WDNDKDetail").parsley().reset();
        });
    });
    var Add = function () {
        window.location.href = window.location.href + '?type=add';
    };
    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }
    
    // function Edit(ID){
    //     window.location.href = "<?php echo site_url('Withdraw'); ?>" + '?type=edit&UUID=' + ID;
    // }
    
    /* ---------------------SET OF FUNCTIONS--------------------- */
    function AddForm() {
        ACTIONM = 'ADD';
        VENDOR = "";
        APP_LETTER_DATE = "";
        APP_LETTER_NUM = "";
        BAST_DATE = "";
        BAST_NUM = "";
        INV_DATE = "";
        INV_NUM = "";
        BIL_VAL = "";
        DDOWN_AMT ="";
        DUE_DATE="";
        $("#APP_LETTER_DATE").val('');
        $("#APP_LETTER_NUM").val('');
        $("#BAST_DATE").val('');
        $("#BAST_NUM").val('');
        $("#INV_DATE").val('');
        $("#INV_NUM").val('');
        $('#BIL_VAL').val('');
        $('#DDOWN_AMT').val('');
        $('#DUE_DATE').val('');
        $('#MDetail .modal-title').text("Add Data Detail");
        $("#MDetail").modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    $(document).ready(function() {
            // $("#VENDOR").select2({
            // // theme: 'bootstrap4',
            // ajax: {
                    // url: "<?php echo site_url('Leasing/getVendor') ?>",
            //         dataType: 'json',
            //         delay: 250,
            //         type: 'GET',
            //         data: function(params) {
            //             return {
            //                 q: params.term, // search term
            //                 page: params.page
            //             };
            //         },
            //         processResults: function(data, page) {
            //             // parse the results into the format expected by Select2.
            //             // since we are using custom formatting functions we do not need to
            //             // alter the remote JSON data
            //             return {
            //                 results: $.map(data, function (item) {
            //                     return {
            //                         id:item.ID,
            //                         text:item.TEXT,
            //                         bic:item.BIC
            //                     }
            //                 })
            //             };
            //         },
            //         cache: true,
            //         error: function(e){
            //             console.info(e);
            //         }
            //     },
            //     escapeMarkup: function(markup) {
            //         return markup;
            //     }, // let our custom formatter work
            //     minimumInputLength: 1,
            //     //templateResult: formatRepo,
            //     //templateSelection: formatRepoSelection
            // });
            // $('#VENDOR').on('select2:select', function (e) {
            //         var data = e.params.data;
            //         $('#BIC').text(data.bic);
            //     });
        });

    function SetDataKosong() {
        $('.panel-title').text('Form Withdraw');
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
        $('.panel-title').text('Form Data Withdraw');
        $('#COMPANY option[value='+data.COMPANY+']').attr('selected','selected');
        $('#BUSINESSUNIT').append($('<option>', {
            value: data.BUID,
            text: data.BUFCNAME
        }));
        $('#CREDIT_TYPE option[value='+data.CREDIT_TYPE+']').attr('selected','selected');
        $('#SUB_CREDIT_TYPE option[value='+data.SUB_CREDIT_TYPE+']').attr('selected','selected');
        $('#PK_NUMBER').val(data.PK_NUMBER);
        $('#CONTRACT_NUMBER').val(data.CONTRACT_NUMBER);
        $('#AMOUNTLIMIT').val(fCurrency(data.AMOUNT_LIMIT));
        $('#INTEREST').val(data.INTEREST);
        $('#VALUE_DATE').val(data.VALUE_DATE);
        $('#SCF_NONDISKONTO_TYPE').val(data.SCF_NDK_TYPE);
        $.ajax({
            dataType : "JSON",
            type : "POST",
            url : "<?php echo site_url('Kmk/GetDataWDNDK'); ?>",
            data : {
                UUID : data.UUID,
                WD_TYPE : data.SUB_CREDIT_TYPE
            },
            success : function(response) {
                data = response.result.data;
                // console.log(data);
        if (data.AMOUNT_BALANCE == null || data.AMOUNT_BALANCE == 0 || data.AMOUNT_BALANCE == '') {
                    var currLimit = DtKmk.AMOUNT_LIMIT - data.AMOUNT;
                    BALANCE = currLimit ;
            $('#BALANCE').val(fCurrency(String(currLimit)));    
        } else {
                    var currLimit = data.AMOUNT_BALANCE - DtKmk.AMOUNT;
            $('#BALANCE').val(fCurrency(data.AMOUNT_BALANCE));
                    BALANCE = data.AMOUNT_BALANCE ;
        }
                }
        })
        ACTION = 'EDIT';
        CREDIT_TYPE = data.CREDIT_TYPE;
        SUB_CREDIT_TYPE = data.SUB_CREDIT_TYPE;
        // if((CREDIT_TYPE == "KMK" && SUB_CREDIT_TYPE == "BD") || (CREDIT_TYPE == "KMK" && SUB_CREDIT_TYPE == "RK") ){
        $('.bd').removeClass('d-none');
        // }
        // $('.btnSave').addClass('d-none');
    }

    // $("#BANK").on({
    //     'change': function() {
    //         if (this.value == '' || this.value == null || this.value == undefined) {
    //             BANKCODE = "";
    //             // BANKCURRENCY = "";
    //             // $("#CURRENCY").change();
    //         } else {
    //             var DBANK = JSON.parse(this.value);
    //             BANKCODE = DBANK.BANKCODE;
    //             // BANKCURRENCY = DBANK.CURRENCY;
    //             // $("#CURRENCY").change();
    //         }
    //     }
    // });

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
                        AMOUNT: $('#END_AMOUNT').val(),
                        WD_TYPE: SUB_CREDIT_TYPE,
                        USERNAME: USERNAME,
                        CONTRACT_NUMBER: $('#CONTRACT_NUMBER').val()
                    },
                    success: function (response) {
                        $("#loader").hide();
                        $('#btnSave').removeAttr('disabled');
                        if (response.status == 200) {
                            toastr.success("Data Successfully Saved");
                            $('#FAddEditFormbd').parsley().reset();
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
    var DtKmk = <?php echo json_encode($DtKmk); ?>;
    var STATUS = true;
    var tbl_upload;
    var amtModals;
    //FILENAME, tbl_uploadOthers;
    // file upload table manager
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
                        d.SUB_CREDIT_TYPE = DtKmk['SUB_CREDIT_TYPE'];   
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
                            url: "<?php echo site_url('Kmk/DeleteFileWD'); ?>",
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
                        fd.append("SUB_CREDIT_TYPE", DtKmk['SUB_CREDIT_TYPE']);
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

    function saveWDNDK(elm) {
        ID = DtKmk.UUID ;
        if($('#WDNDKDetail').parsley().validate()){
            if(ID == null || ID == '' || ID == 0){
                toastr.error('ID KOSONG');
                reloadUploadWDNDK();
            }
            else if($('#tipe_file').val() == null || $('#tipe_file').val() == '' || $('#tipe_file').val() == 0){
                toastr.error('Isi Tipe File');
            }
            
            else{
                var fileInput = $('.upload-file-wdndk');
                var extFile = $('.upload-file-wdndk').val().split('.').pop().toUpperCase();
                var maxSize = fileInput.data('max-size');
                if ($.inArray(extFile, filetypeUpload) === -1) {
                    toastr.error('Format file tidak valid');
                    files = '';
                    $('.upload-file-wdndk').val('');
                    return;
                }else {
                    if (fileInput.get(0).files.length) {
                        var fileSize = fileInput.get(0).files[0].size;
                        if (fileSize > maxSize) {
                            toastr.error('Ukuran file terlalu besar');
                            files = '';
                            $('.upload-file-wdndk').val('');
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
                            fd.append('ID', $('#DETID').val());
                            fd.append("UUID",ID);
                            fd.append("WD_TYPE", DtKmk.SUB_CREDIT_TYPEMASTER);
                            fd.append("VALUE_DATE", $('#VALUE_DATE').val());
                            fd.append("FILENAME", FILENAME);
                            fd.append("CONTRACT_NUMBER", $('#CONTRACT_NUMBER').val());
                            fd.append("TIPE",$('#tipe_file').val());
                            fd.append("VENDOR",  $("#VENDOR").val());
                            fd.append("APP_LETTER_DATE", $("#APP_LETTER_DATE").val());
                            fd.append("APP_LETTER_NUM", $("#APP_LETTER_NUM").val());
                            fd.append("BAST_DATE", $("#BAST_DATE").val());
                            fd.append("BAST_NUM", $("#BAST_NUM").val());
                            fd.append("INV_DATE", $("#INV_DATE").val());
                            fd.append("INV_NUM", $("#INV_NUM").val());
                            fd.append("BIL_VAL", $('#BIL_VAL').val());
                            fd.append("DDOWN_AMT", $('#DDOWN_AMT').val());
                            fd.append("DUE_DATE", $('#DUE_DATE').val());
                            fd.append("SCF_NONDISKONTO_TYPE", $('#SCF_NONDISKONTO_TYPE').val());
                            fd.append("AMTMODALS", amtModals);
                            // fd.append("EDITING", $('#DATA_EXIST').val());
                            // fd.append("EXTSYSTEM",$('#EXTSYSTEM').val());
                            // fd.append("DOCTYPE",$('#DOCTYPE').val());
                            // fd.append('UUID',UUID)
                            // fd.append('DATERELEASE',currentDate);
                            $.ajax({
                                dataType: "JSON",
                                type: 'POST',
                                url: "<?php echo site_url('Kmk/SaveWDNDK'); ?>",
                                data: fd,
                                processData: false,
                                contentType: false,
                                success: function (response) {
                                    $('#page-container').addClass('page-sidebar-minified');
                                    $('#loader').removeClass('show');
                                    if (response.status == 200) {
                                        STATUS = true;
                                        // DtUpload = response.result.data;
                                        $('#MDetail').on('hidden.bs.modal', function(e) {
                                            $(this).find('#WDNDKDetail')[0].reset();
                                        });
                                        $('#WDNDKDetail').parsley().reset();
                                        // $('#DtUpload').removeClass('d-none');
                                        $('#MDetail').modal('hide');
                                        toastr.success('Upload Success');
                                        // $('#DtUpload').removeClass('d-none');
                                        $('#btnReset').removeAttr('disabled');
                                        // update Balance in header
                                        BALANCE = BALANCE + (Number(amtModals ? formatDesimal(amtModals) : 0)) - (Number(formatDesimal($('#DDOWN_AMT').val()))) ;
                                        $("#BALANCE").val(fCurrency(String(BALANCE)));
                                        reloadUploadWDNDK();
                                    } else if (response.status == 504) {
                                        toastr.error(response.result.data);
                                        $('#btnReset').removeAttr('disabled');
                                        reloadUploadWDNDK();
                                    } else {
                                        toastr.error(response.result.data);
                                        files = '';
                                        $('.upload-file-wdndk').val('');
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
                                    for (var pair of fd.entries()) {
                                        console.log(pair[0]+ ', ' + pair[1]); 
                                    }
                                    $('.upload-file-wdndk').val('');
                                    $(".panel-title_").text('Upload Document');
                                    // DisableBtn();
                                }
                            });
                            
                        }
                    }
                }
            }
        }
    }

    $('#BIL_VAL').on('change keyup', function () {
        var bilVal = Number(formatDesimal($('#BIL_VAL').val()));
        var rem_limit = Number(formatDesimal($('#AMOUNTLIMIT').val()));
        var ddownAmt = Number(bilVal) * 95 / 100 ;
        $('#DDOWN_AMT').val(fCurrency(String(ddownAmt)));
        if(ddownAmt > rem_limit) {
            $('#MDetail .btn-primary ').prop('disabled', true);
            toastr.error('Over Limit !!');
        }
        else {
            $('#MDetail .btn-primary').prop('disabled', false);
        }
    });
    var reloadUpload = function() {
        // $('#notesUpload').val('');
        $('.upload-file').val('');
        tbl_upload.ajax.reload();
    };

    var reloadUploadWDNDK = function() {
        $('.upload-file-wdndk').val('');
        tbl_wdndk.ajax.reload();
    }

    var ClearData = function () {
        $('#btnReset').removeAttr('disabled');
        $('.upload-file').val('');
        $('#page-container').removeClass('page-sidebar-minified');
    };
    // DisableBtn();
    // Table of invoice batch
    // console.log(DtWD_ByUUID);
    if (!$.fn.DataTable.isDataTable('#Table_DtWDNDK')) {
        $('#Table_DtWDNDK').DataTable({
            "bDestroy" : true,
            "bRetrieve" : true,
            "ajax": {
                    "url" : "<?php echo site_url('Kmk/GetDataWDNonDiskonto')?>",
                    "type": "POST",
                    "datatype" : "JSON",
                    "data": function(d) {
                        d.UUID = DtKmk.UUID ;
                        d.VALUE_DATE = DtKmk.VALUE_DATE ;
                    },
                    "dataSrc": function(ext) {
                        if (ext.status == 200) {
                            ext.draw = ext.result.data;
                            // var currentLimit = remainingLimit(ext.draw, dtkmk);
                            // $('#BALANCE').val(fCurrency(String(currentLimit)));
                            return ext.result.data;
                        } else if (ext.status == 504) {
                            alert(ext.result.data);
                            location.reload();
                            return [];
                        } else {
                            console.info(ext.result.data);
                            return ;
                        }
                    },
                    "beforeSend" : function() {
                        $("#overlay").show();
                    },
                    "complete" : function() {
                        $("#overlay").hide();
                    },
                },
            "columns": [
                {
                "data": null,
                "className": "text-center",
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
                },
                {"className" : "text-center", "data":"VALUE_DATE"},
                {"className" : "text-center", "data":"SCF_NDK_TYPE"},
                {"className" : "text-center", "data":"VENDORNAME"},
                {"className" : "text-center", "data":"APP_LETTER_DATE"},
                {"className" : "text-center", "data":"APP_LETTER_NUM"},
                {"className" : "text-center", "data":"BAST_DATE"},
                {"className" : "text-center", "data":"BAST_NUM"},
                {"className" : "text-center", "data":"INV_DATE"},
                {"className" : "text-center", "data":"INV_NUM"},
                {"className" : "text-center", "data":"BILLING_VAL",
                    render: $.fn.dataTable.render.number(',', '.', 2)},
                {"className" : "text-center", "data":"DDOWN_AMT",
                    render: $.fn.dataTable.render.number(',', '.', 2)},
                {"className" : "text-center", "data":"DUEDATE"},
                {"className" : "text-center", "data":"FILE_NAME"},
                {
                    "data": null,
                    "className": "text-center",
                    "orderable": false,
                    render: function (data, type, row, meta) {
                        var html = '';
                        if (EDITS == 1 && !(getUrlParameter('vm') == 'vm' || DtKmk.STATUS == 1 ||  DtKmk.STATUS == 2)) {
                        html += '<button class="btn btn-success btn-icon btn-circle btn-sm edit" title="edit" style="margin-right: 5px;">\n\
                        <i class="fas fa-edit" aria-hidden="true"></i>\n\
                        </button>';
                        }
                        if (DELETES == 1 && !(getUrlParameter('vm') == 'vm' || DtKmk.STATUS == 1 ||  DtKmk.STATUS == 2)) {
                            html += '<button class="btn btn-danger btn-icon btn-circle btn-sm delete" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                        }
                        html += '<button class="btn btn-success btn-icon btn-circle btn-sm dwn" title="Download" style="margin-right: 5px;">\n\
                        <i class="fas fa-arrow-down" aria-hidden="true"></i>\n\
                        </button>';
                        return html;
                    }
                }
            ],
            "bFilter" : true,
            "bPaginate" : true,
            "bLengthChange" : false,
            "bInfo" : true,
            "responsive" : false
        });
        $('#btnDetail').on('click', function() {
            $('#DETID').val(null);
            amtModals = 0;
        })
        tbl_wdndk = $('#Table_DtWDNDK').DataTable();
        tbl_wdndk.on('click', '.edit', function() {
            $tr = $(this).closest('tr');
            var data = tbl_wdndk.row($tr).data();
            DETID = data.ID;
            amtModals = data.DDOWN_AMT;
            $('#DETID').val(DETID);
            $('#VENDOR').append($('<option>', {
                value: data.VENDOR,
                text: data.VENDORNAME
            }));
            $('#APP_LETTER_DATE').val(moment(data.APP_LETTER_DATE).format('YYYY-MM-DD'));
            $('#DUE_DATE').val(moment(data.DUEDATE).format('YYYY-MM-DD'));
            $('#APP_LETTER_NUM').val(data.APP_LETTER_NUM);
            $('#BAST_DATE').val(moment(data.BAST_DATE).format('YYYY-MM-DD'));
            $('#BAST_NUM').val(data.BAST_NUM);
            $('#INV_DATE').val(moment(data.INV_DATE).format('YYYY-MM-DD'));
            $('#INV_NUM').val(data.INV_NUM);
            $('#BIL_VAL').val(fCurrency(data.BILLING_VAL));
            $('#DDOWN_AMT').val(fCurrency(data.DDOWN_AMT));
            $('#MDetail').parsley().reset();
            $('#MDetail .modal-title').text("Edit Data Invoice");
            // const cekDocval = IDOCNUMBER.includes("TMP");
            // alert(IDOCNUMBER);
            $("#MDetail").modal({
                backdrop: 'static',
                keyboard: false
            });
        });
        tbl_wdndk.on('click', '.delete', function () {
                $tr = $(this).closest('tr');
                var data = tbl_wdndk.row($tr).data();
                AMOUNT = data.DDOWN_AMT ;
                // console.log(data);
                if (confirm('Are you sure delete this data ?')) {
                    $.ajax({
                        dataType: "JSON",
                        type: "POST",
                        url: "<?php echo site_url('Kmk/DeleteWDNDK'); ?>",
                        data: {
                            ID: data.ID,
                            FILENAME: data.FILE_NAME,
                            USERNAME: USERNAME,
                            AMOUNT: data.DDOWN_AMT,
                            VALUE_DATE : data.VALUE_DATE
                        },
                        success: function (response) {
                            if (response.status == 200) {
                                toastr.success(response.result.data);
                                // update Balance in header
                                var queryString = window.location.search ;
                                // console.log(queryString);
                                var urlParams = new URLSearchParams(queryString);
                                BALANCE = BALANCE + Number(formatDesimal(AMOUNT));
                                // console.log(BALANCE);
                                $('#BALANCE').val(fCurrency(String(BALANCE)));
                                //
                                tbl_wdndk.ajax.reload();
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
    }
    tbl_wdndk.on('click', '.dwn', function() {
            $tr = $(this).closest('tr');
            var data = tbl_wdndk.row($tr).data();
            window.open("<?php echo base_url('assets/file/')?>" + data.FILE_NAME,'_blank');
        });

    function remainingLimit (withdrawal_data, DtKmk) {
        var total_bilval = 0;
        for (var i = 0 ; i < withdrawal_data.length ; i++) {
            total_bilval += Number(withdrawal_data[i]['BILLING_VAL']);
        }
        var amount_limit = $('#AMOUNTLIMIT').val();
        var currentLimit = DtKmk.AMOUNT_LIMIT-total_bilval ;
        return currentLimit ;
    }
</script>
