<link href="./assets/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" />
<link href="./assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" />

<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Upload Item</li>
</ol>
<h1 class="page-header">Upload Item</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Upload Item</h4>
    </div>
    <div class="panel-body">
        <div class="note note-yellow m-b-10">
            <div class="note-icon f-s-20">
                <i class="fa fa-lightbulb fa-2x"></i>
            </div>
            <div class="note-content">
                <h4 class="m-t-5 m-b-5 p-b-2">Upload Notes</h4>
                <ul class="m-b-5 p-l-20">
                    <li>The maximum file size for uploads in this transaction is <strong> 10 MB</strong> (default file size is unlimited).</li>
                    <li>Only Excel files (<strong>xls, xlsx</strong>) are allowed in this transaction (by default there is no file type restriction).</li>
                </ul>
            </div>
        </div>
        <div class="row mb-2 fileupload-buttonbar">
            <div class="col-md-12">
                <select class="form-control-sm" name="EXTSYSTEM" id="EXTSYSTEM" required>
                    <option value="" selected disabled>Choose Source System</option>
                    <?php
                    foreach ($DtExtSystem as $values) {
                        echo '<option value=' . $values->FCCODE . '>' . $values->FCNAME . '</option>';
                    }
                    ?>
                </select>
                <span class="btn btn-primary fileinput-button m-r-3">
                    <!--<i class="fa fa-plus"></i>-->
                    <span>Browse File</span>
                    <input type="file" class="upload-file" data-max-size="10485760" onchange="filesChange(this)">
                </span>
                <button id="btnSave" type="button" class="btn btn-primary m-r-3" onclick="Save()">
                    <!--<i class="fa fa-upload"></i>-->
                    <span>Upload Data</span>
                </button>
                <button id="btnReset" type="button" class="btn btn-default m-r-3" onclick="ClearData()">
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
                        <th class="text-center sorting">Status</th>
                        <th class="text-center sorting">Code Item</th>
                        <th class="text-center sorting">Nama Item</th>
                        <th class="text-center sorting">Description</th>
                        <th class="text-center sorting">Part No</th>
                        <th class="text-center sorting">Item Type</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    //default ngecek hak akses ()
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    //nge lihat user login siapa
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    //nge get parameter di di halaman website url
    /*var getUrlParameter = function getUrlParameter(sParam) {
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
    };*/
    //parameter untuk upload
    var files, filetypeUpload = ['XLS', 'XLSX'];
    //variable penampung untuk data upload
    var DtUpload = [];
    var STATUS = true;
    var table, FILENAME;
    $('#EXTSYSTEM').val('');
    $('#DOCTYPE').val('');

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
                        if (data.STATUS == 0) {
                            html += '<span class="badge badge-pill badge-success">Done</span>';
                        } else {
                            html += '<span class="badge badge-pill badge-danger" title="' + data.MESSAGE + '">Error</span>';
                            STATUS = false;
                        }
                        return html;
                    }
                },
                {"data": "CODEITEM"},
                {"data": "NAMAITEM"},
                {"data": "DESCRIPTION"},
                {"data": "PARTNO"},
                {"data": "ITEMTYPENAME"}
            ],
            "bFilter": true,
            "bPaginate": true,
            "bLengthChange": true,
            "bInfo": true,
            "responsive": false
        });
        //table untuk menampung datatable 
        table = $('#DtUpload').DataTable();
    }
    //sintact setelah dokumen setelah di upload 
    function filesChange(elm) {
        if ($('#EXTSYSTEM').val() == '' || $('#EXTSYSTEM').val() == null || $('#EXTSYSTEM').val() == undefined) {
            alert('Please, Choose Source System Firts!!!');
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
                        $(".panel-title").text('Item Upload : ' + FILENAME);
                        DisableBtn();
                        var fd = new FormData();
                        $.each(files, function (i, data) {
                            fd.append("uploads", data);
                        });
                        fd.append("EXTSYSTEM", $('#EXTSYSTEM').val());
                        /*fd.append("UUID", UUID);*/
                        //fd.append("DOCTYPE", $('#DOCTYPE').val());
                        $.ajax({
                            dataType: "JSON",
                            type: 'POST',
                            url: "<?php echo site_url('IMaterialUpload/ListDataUpload'); ?>",
                            data: fd,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $('#loader').removeClass('show');
                                if (response.status == 200) {
                                    STATUS = true;
                                    DtUpload = response.result.data;
                                    table.clear();
                                    table.rows.add(DtUpload);
                                    table.draw();
                                } else if (response.status == 504) {
                                    alert(response.result.data);
                                    location.reload();
                                } else {
                                    alert(response.result.data);
                                    files = '';
                                    $('.upload-file').val('');
                                    $(".panel-title").text('Upload Item');
                                    DisableBtn();
                                }
                            },
                            error: function (e) {
                                console.info(e);
                                $('#loader').removeClass('show');
                                alert('Error Upload Data !!');
                                files = '';
                                $('.upload-file').val('');
                                $(".panel-title").text('Upload Item');
                                DisableBtn();
                            }
                        });
                    }
                }
            }
        }
    }
    var Save = function () {
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
                url: "<?php echo site_url('IMaterialUpload/Save'); ?>",
                data: {
                    DATA: DtUpload,
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
                        table.clear();
                        table.rows.add(DtUpload);
                        table.draw();
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
        files = '';
        $('.upload-file').val('');
        $(".panel-title").text('Upload Item');
        DtUpload = [];
        table.clear();
        table.rows.add(DtUpload);
        table.draw();
        DisableBtn();
    };
    DisableBtn();
</script>