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
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Upload to Forecast</li>
</ol>
<h1 class="page-header">Upload to Forecast</h1>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a href="#import" data-toggle="tab" class="nav-link">
            <span class="d-sm-none">Tab 2</span>
            <span class="d-sm-block d-none">Upload to Forecast</span>
        </a>
    </li>
</ul>
<div class="tab-content">
        <div class="tab-pane fade active show" id="import">
            <div class="panel panel-success">
                <div class="row">
                    <select class="form-control-sm mr-2" name="EXTSYSTEM" id="EXTSYSTEM" required>
                        <option value="" selected disabled>Choose Source System</option>
                        <?php
                        foreach ($DtExtSystem as $values) {
                            echo '<option value=' . $values->FCCODE . '>' . $values->FCNAME . '</option>';
                        }
                        ?>
                    </select>
                    <select class="form-control-sm" name="DOCTYPE" id="DOCTYPE" required>
                        <option value="" selected disabled>Choose Doc Type</option>
                        <?php
                        foreach ($DtDocType as $values) {
                            echo '<option value=' . $values->FCCODE . '>' . $values->FCNAME . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <!-- <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    </div>
                    <h4 class="panel-title_">Import</h4>
                </div> -->
                <div class="panel-body">
                    <div class="row mb-2 fileupload-buttonbar">
                        <div class="col-md-12">
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
                </div>
                <div class="row m-0 table-responsive">
                    <table id="DtUpload" class="table table-striped table-bordered d-none" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                        <thead>
                            <tr role="row">
                                <th class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Dept</th>
                                <th class="text-center">BU</th>
                                <th class="text-center">DocNumber</th>
                                <th class="text-center">DocRef</th>
                                <th class="text-center">Forecast</th>
                                <th class="text-center sorting">Amount</th>
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
    var files, filetypeUpload = ['XLS', 'XLSX'];
    var DtUpload = [];
    var DtUploadOthers = []
    var STATUS = true;
    var tbl_upload, FILENAME, tbl_uploadOthers;
    
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
                    if (data.ERROR_MSG == null) {
                        html += '<span class="badge badge-pill badge-success">OK</span>';
                    } else {
                        html += '<span class="badge badge-pill badge-danger" title="' + data.ERROR_MSG + '">' + data.ERROR_MSG + '</span>';
                        STATUS = false;
                    }
                    return html;
                }
            },
            {"className": "text-center","data":"COMPANY"},
            {"className": "text-center","data":"DEPARTMENT"},
            {"className": "text-center","data":"BUSINESSUNIT"},
            {"className": "text-center","data":"DOCNUMBER"},
            {"className": "text-center","data":"DOCREF"},
            {"data":null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            return 'YEAR : ' + data.YEAR + ' MONTH : ' + data.MONTH + ' WEEK : ' + data.WEEK + ' PRIORITY : '+data.PRIORITY;
                        }
            },
            {"className": "text-center","data": "AMOUNT_INCLUDE_VAT",
                render: $.fn.dataTable.render.number(',', '.', 2)
            }
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
                    fd.append("EXTSYSTEM",$('#EXTSYSTEM').val());
                    // fd.append("DOCTYPE",$('#DOCTYPE').val());
                    // fd.append('UUID',UUID)
                    // fd.append('DATERELEASE',currentDate);
                    $.ajax({
                        dataType: "JSON",
                        type: 'POST',
                        url: "<?php echo site_url('Upload/uploadForecast'); ?>",
                        data: fd,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#page-container').addClass('page-sidebar-minified');
                            $('#loader').removeClass('show');
                            if (response.status == 200) {
                                STATUS = true;
                                DtUpload = response.result.data;
                                $('#DtUpload').removeClass('d-none');
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
    var SaveUpload = function () {
        if(DtUpload.length <= 0){
            alert('Data yang di upload tidak ada !!!');
        }else{
            if (STATUS == false) {
                alert('Data masih ada yang error !!!');
            } else {
                $('#loader').addClass('show');
                $('#btnSave').attr('disabled', true);
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Upload/saveUpForecast'); ?>",
                    data: {
                        DATA: JSON.stringify(DtUpload),
                        FILENAME: FILENAME,
                        EXTSYSTEM: $('#EXTSYSTEM').val(),
                        DOCTYPE: $('#DOCTYPE').val(),
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
        }
        
    };
    // var ClearData = function () {
    //     STATUS = true;
    //     files = '';
    //     var DOCTYPE = $('#DOCTYPE').val();
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
                url: "<?php echo site_url('Upload/clearForecast'); ?>",
                // data: {
                //     DOCTYPE: $('#DOCTYPE').val()
                // },
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