<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link href="./assets/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" />
<link href="./assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
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
    <li class="breadcrumb-item active">Payment Interco</li>
</ol>
<h1 class="page-header">Payment Interco</h1>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a href="#import" data-toggle="tab" class="nav-link">
            <span class="d-sm-none">Tab 2</span>
            <span class="d-sm-block d-none">Upload</span>
        </a>
    </li>
</ul>
<div class="tab-content">
        <div class="tab-pane fade active show" id="import">
            <div class="panel panel-success">
                <!-- <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    </div>
                    <h4 class="panel-title_">Import</h4>
                </div> -->
                <div class="panel-body">
                    <div class="row mb-2 fileupload-buttonbar">
                        <div class="col-md-12">
                            <select class="form-control-sm" name="DOCTYPE" id="DOCTYPE" required="">
                                <option value="" selected="" disabled="">Choose Doc Type</option>
                                <option value="INTERCO">INTERCO</option>
                            </select>
                            <span class="btn btn-primary fileinput-button m-r-3">
                                <!--<i class="fa fa-plus"></i>-->
                                <span>Browse File</span>
                                <input type="file" class="upload-file" data-max-size="1048576" onchange="filesChange(this)">
                            </span>
                            <button id="btnSave" type="button" class="btn btn-primary m-r-3" onclick="SaveUpload()">
                                <!--<i class="fa fa-upload"></i>-->
                                <span>Upload Data</span>
                            </button>
                            <button id="btnReset" type="button" class="btn btn-default m-r-3" onclick="ClearData()" disabled="disabled">
                                <!--<i class="fa fa-upload"></i>-->
                                <span>Clear Data</span>
                            </button>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-12">
                            <div class="form-row">
                                <div class="row">
                                    <div class="form-group col-md-4">
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
                                        <label for="EXTSYS">Extsys </label>
                                        <select class="form-control-sm" name="EXTSYSTEM" id="EXTSYSTEM" required="">
                                            <option value="" selected="" disabled="">Choose Source System</option>
                                            <option value="IPLAS">IPLAS</option><option value="SAP">SAP</option><option value="SAPHANA">SAPHANA</option><option value="TIPTOP">TIPTOP</option>                
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
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
                                            <i class="fa fa-plus"></i>
                                            <span>Browse File</span>
                                            <input type="file" class="upload-file" data-max-size="1048576" onchange="filesChange(this)">
                                        </span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <button id="btnSave" type="button" class="btn btn-primary m-r-3" onclick="SaveUpload()">
                                            <i class="fa fa-upload"></i>
                                            <span>Upload Data</span>
                                        </button>
                                    </div>
                                    <div class="form-group col-md-4 ">
                                        <button id="btnReset" type="button" class="btn btn-default m-r-3" onclick="ClearData()">
                                            <i class="fa fa-upload"></i>
                                            <span>Clear Data</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="row m-0 table-responsive">
                    <table id="DtUpload" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                        <thead>
                            <tr role="row">
                                <th class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Date Release</th>
                                <th class="text-center">Company Source</th>
                                <th class="text-center">Bank Source</th>
                                <th class="text-center sorting">Company Target</th>
                                <th class="text-center sorting">Bank Target</th>
                                <th class="text-center sorting">Voucher</th>
                                <th class="text-center sorting">Giro</th>
                                <th class="text-center sorting">Source Amount</th>
                                <th class="text-center sorting">Rate</th>
                                <th class="text-center sorting">Amount</th>
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
    if (dd <= 10) {
        dd = '0' + dd;
    }
    if (mm <= 10) {
        mm = '0' + mm;
    }
    
    var tgl = mm + '/' + dd + '/' + today.getFullYear();
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
            {"data":"DATERELEASE"},
            {"data":"COMPANYSOURCENAME"},
            {"data":"BANKSOURCE"},
            {"data":"COMPANYTARGETNAME"},
            {"data": "BANKTARGET"},
            {"data": "VOUCHERNO"},
            {"data": "NOCEKGIRO"},
            {"data": "SOURCEAMOUNT",
                render: $.fn.dataTable.render.number(',', '.', 2)
            },
            {
                "data": "RATE",
                render: $.fn.dataTable.render.number(',', '.', 2)
            },
            {
                "data": "AMOUNT",
                render: $.fn.dataTable.render.number(',', '.', 2)
            }, 
            {"data": "REMARKS"},
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

        var EXTSYSTEM           = $('#EXTSYSTEM').val();
        var DOCTYPE             = $('#DOCTYPE').val();

        if ( month < 10 ){
            month = "0" + month;
        }
        if( day < 10){
            day = "0" + day;
        }
        var currentDate = month + '/' + day + '/' + curDate.getFullYear();
        if (DOCTYPE == '' || DOCTYPE == null || DOCTYPE == undefined) {
            alert('Please, Choose Doctype First!!!');
            files = '';
            $('.upload-file').val('');
        }
        else {
            var fileInput = $('.upload-file');
            var extFile = $('.upload-file').val().split('.').pop().toUpperCase();
            var maxSize = fileInput.data('max-size');
            if ($.inArray(extFile, filetypeUpload) === -1) {
                alert('Format file tidak valid!!');
                files = '';
                $('.upload-file').val('');
                return;
            }else {
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
                        fd.append("DOCTYPE", DOCTYPE);
                        // fd.append("EXTSYSTEM", EXTSYSTEM);
                        $.ajax({
                            dataType: "JSON",
                            type: 'POST',
                            url: "<?php echo site_url('Upload/UploadPaymentInterco'); ?>",
                            data: fd,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $('#page-container').addClass('page-sidebar-minified');
                                $('#loader').removeClass('show');
                                if (response.status == 200) {
                                    STATUS = true;
                                    DtUpload = response.result.data;
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
                url: "<?php echo site_url('Upload/saveUpPaymentInterco'); ?>",
                data: {
                    DATA: JSON.stringify(DtUpload),
                    DOCTYPE: $('#DOCTYPE').val(),
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
    // var ClearData = function () {
    //     STATUS = true;
    //     files = '';
    //     $('.upload-file').val('');
    //     $(".panel-title").text('Upload Document');
    //     DtUpload = [];
    //     tbl_upload.clear();
    //     tbl_upload.rows.add(DtUpload);
    //     tbl_upload.draw();
    //     DisableBtn();
    // };
    var ClearData = function () {
        STATUS = true;
        files = '';

        $('.upload-file').val('');
        $(".panel-title").text('Upload Document');
        $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Upload/clearPaymentInterco'); ?>",
                data: {
                    USERNAME:USERNAME
                },
                success: function (response) {
                    $('#loader').removeClass('show');
                    $('#btnSave').removeAttr('disabled');
                    if (response.status == 200) {
                        DtUpload = [];
                        tbl_upload.clear();
                        tbl_upload.rows.add(DtUpload);
                        tbl_upload.draw();
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
                    alert('Data Clear Failed !!');
                    $('#btnSave').removeAttr('disabled');
                }
            });
        $('#page-container').removeClass('page-sidebar-minified');
    };
    DisableBtn();
</script>