<link href="./assets/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" />
<link href="./assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" />

<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Upload First Receipt</li>
</ol>
<h1 class="page-header">Upload First Receipt</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Upload First Receipt <span id=""></span></h4>
    </div>
    <div class="panel-body">
        <div class="note note-yellow m-b-10">
            <div class="note-icon f-s-20">
                <i class="fa fa-lightbulb fa-2x"></i>
            </div>
            <div class="note-content">
                <h4 class="m-t-5 m-b-5 p-b-2">Upload Notes</h4>
                <ul class="m-b-5 p-l-20">
                    <!-- <li>The maximum file size for uploads in this transaction is <strong> 1 MB</strong></li>
                    <li>Only Excel files (<strong>xls, xlsx</strong>) are allowed in this transaction </li> -->
                    <li>HINDARI PEMAKAIAN KUTIP PADA PENGINPUTAN INVOICE DAN PO. Cth : <b>MAR'22</b> </li>
                </ul>
            </div>
        </div>
        <div class="row mb-2 fileupload-buttonbar">
            <div class="col-md-12">
                <!-- <select class="form-control-sm" name="DEPARTMENT" id="DEPARTMENT" required>
                    <option value="" selected disabled>Choose Department</option>
                    <?php
                    foreach ($DtDepart as $values) {
                        echo '<option value=' . $values->DEPARTMENT . '>' . $values->DEPARTEMENTNAME . '</option>';
                    }
                    ?>
                </select> -->
                <span class="btn btn-primary fileinput-button m-r-3">
                    <!--<i class="fa fa-plus"></i>-->
                    <span>Browse File</span>
                    <input type="file" class="upload-file" data-max-size="1048576" onchange="filesChange(this)"> <!--10485760-->
                </span>
                <button id="btnSave" type="button" class="btn btn-primary m-r-3" onclick="Save()">
                    <!--<i class="fa fa-upload"></i>-->
                    <span>Upload Data</span>
                </button>
                <button id="btnReset" type="button" class="btn btn-danger m-r-3" onclick="ClearData()">
                    <!--<i class="fa fa-upload"></i>-->
                    <span>Clear Data</span>
                </button>

                <button id="btnTmp" type="button" class="btn btn-green m-r-3">
                    <!--<i class="fa fa-upload"></i>-->
                    <span>Download Template</span>
                </button>
            </div>
        </div>
        <div class="row m-0 table-responsive">
            <table id="DtUpload" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                <thead>
                    <tr role="row">
                        <th class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                        <th class="text-center sorting">Status</th>
                        <th class="text-center sorting">Company</th>
                        <th class="text-center sorting">Invoice Code</th>
                        <th class="text-center sorting">No Po</th>
                        <th class="text-center sorting">Vendor</th>
                        <th class="text-center sorting">Currency</th>
                        <th class="text-center sorting">Amount</th>
                        <!-- <th class="text-center sorting">Voucher</th>
                        <th class="text-center sorting">Dpp</th>
                        <th class="text-center sorting">Ppn</th>
                        <th class="text-center sorting">Pph</th>
                        <th class="text-center sorting">Net</th> -->
                        <!-- <th class="sorting_disabled"></th> -->
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<!-- Modal Upload Detial -->
<!-- <div class="modal fade" id="MUploadDetail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail PO : </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="DtUploadDetail" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUploadDetail_info">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Doc Date</th>
                                <th class="text-center">Vendor</th>
                                <th class="text-center">Remark</th>
                                <th class="text-center">Material</th>
                                <th class="text-center">Amount Include VAT</th>
                                <th class="text-center">Amount PPH</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="6" class="text-right">Total:</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> -->
<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var UUID = "<?php echo $UUID; ?>";
    var files, filetypeUpload = ['XLS', 'XLSX'];
    var DtUpload = [], DtUploadDetail = [];
    var STATUS = true;
    var table, table1, FILENAME;

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
                            html += '<span class="badge badge-pill badge-success">Done</span>';
                        } else {
                            html += '<span class="badge badge-pill badge-danger" title="' + data.ERROR_MSG + '">' + data.ERROR_MSG + '</span>';
                            STATUS = false;
                        }
                        return html;
                    }
                },
                {"data": "COMPANYNAME"},
                {"data": "INVOICE_CODE"},
                {"data": "NO_PO"},
                {"data": "VENDORNAME"},
                {"data": "CURRENCY"},
                {
                    "data": "AMOUNT",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                }
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
        table = $('#DtUpload').DataTable();
        // table.on('click', '.view', function () {
        //     $tr = $(this).closest('tr');
        //     var data = table.row($tr).data();
        //     $(".modal-title").text("Detail PO : " + data.DOCNUMBER);
        //     DtUploadDetail = data.datadetail;
        //     if (!$.fn.DataTable.isDataTable('#DtUploadDetail')) {
        //         table1 = $('#DtUploadDetail').DataTable({
        //             "aaData": DtUploadDetail,
        //             "columns": [
        //                 {
        //                     "data": null,
        //                     "className": "text-center",
        //                     render: function (data, type, row, meta) {
        //                         return meta.row + 1;
        //                     }
        //                 },
        //                 {
        //                     "data": null,
        //                     "className": "text-center",
        //                     render: function (data, type, row, meta) {
        //                         var html = '';
        //                         if (data.STATUSD == 0) {
        //                             html += '<span class="badge badge-pill badge-success">Done</span>';
        //                         } else {
        //                             html += '<span class="badge badge-pill badge-danger" title="' + data.MESSAGED + '">' + data.MESSAGED + '</span>';
        //                             STATUS = false;
        //                         }
        //                         return html;
        //                     }
        //                 },
        //                 {"data": "DOCDATE"},
        //                 {"data": "VENDORCODE"},
        //                 {"data": "REMARKS"},
        //                 {"data": "MATERIALCODE"},
        //                 {
        //                     "className": "text-right",
        //                     "data": "AMOUNT_INCLUDE_VAT",
        //                     render: $.fn.dataTable.render.number(',', '.', 2)
        //                 },
        //                 {
        //                     "className": "text-right",
        //                     "data": "AMOUNT_PPH",
        //                     render: $.fn.dataTable.render.number(',', '.', 2)
        //                 }
        //             ],
        //             "bFilter": true,
        //             "bPaginate": true,
        //             "bLengthChange": true,
        //             "bInfo": true,
        //             "responsive": false,
        //             "footerCallback": function (row, data, start, end, display) {
        //                 var api = this.api(), data;
        //                 // Remove the formatting to get integer data for summation
        //                 var intVal = function (i) {
        //                     return typeof i === 'string' ?
        //                             i.replace(/[\$,]/g, '') * 1 :
        //                             typeof i === 'number' ?
        //                             i : 0;
        //                 };
        //                 // Total over all pages
        //                 totalVAT = api
        //                         .column(6)
        //                         .data()
        //                         .reduce(function (a, b) {
        //                             return intVal(a) + intVal(b);
        //                         }, 0);

        //                 totalPPH = api
        //                         .column(7)
        //                         .data()
        //                         .reduce(function (a, b) {
        //                             return intVal(a) + intVal(b);
        //                         }, 0);


        //                 // Update footer
        //                 var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
        //                 $(api.column(6).footer()).html(numFormat(totalVAT));
        //                 $(api.column(7).footer()).html(numFormat(totalPPH));
        //             }
        //         });
        //     } else {
        //         table1.clear();
        //         table1.rows.add(DtUploadDetail);
        //         table1.draw();
        //     }
        //     $('#MUploadDetail').modal({
        //         backdrop: 'static',
        //         keyboard: false
        //     });
        // });
    }
    function filesChange(elm) {
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
                    $(".panel-title").text('Document Upload : ' + FILENAME);
                    DisableBtn();
                    var fd = new FormData();
                    $.each(files, function (i, data) {
                        fd.append("uploads", data);
                    });
                    // fd.append("EXTSYSTEM", 'SAP');
                    fd.append("UUID", UUID);
                    fd.append("USERNAME", USERNAME);
                    $.ajax({
                        dataType: "JSON",
                        type: 'POST',
                        url: "<?php echo site_url('Elog/UploadFR'); ?>",
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
                                $(".panel-title").text('Upload Document');
                                DisableBtn();
                            }
                        },
                        error: function (e) {
                            console.info(e);
                            $('#loader').removeClass('show');
                            alert('Error Upload Data !!');
                            files = '';
                            $('.upload-file').val('');
                            $(".panel-title").text('Upload Document');
                            DisableBtn();
                        }
                    });
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
                url: "<?php echo site_url('Elog/Save_FR'); ?>",
                data: {
                    DATA: JSON.stringify(DtUpload),
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
    var ClearData = function () {
        STATUS = true;
        files = '';
        $('.upload-file').val('');
        $(".panel-title").text('Upload Document');
        DtUpload = [];
        $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Elog/deleteUpload'); ?>",
                data: {
                    USERNAME: USERNAME
                },
                success: function (response) {
                    if (response.status == 200) {
                        alert(response.result.data)
                    };
                    $('#loader').removeClass('show');
                    $('#btnSave').removeAttr('disabled');
                },
                error: function (e) {
                    $('#loader').removeClass('show');
                    // console.info(e);
                    alert('Delete Failed !!');
                    // $('#btnSave').removeAttr('disabled');
                }
            });
        table.clear();
        table.rows.add(DtUpload);
        table.draw();
        DisableBtn();
    };

    function downloadTmp(){
        var url = '/private/downloads/myfile123.pdf';
        $("a").on('mousedown', function () {
            $(this).attr("href", url);
        });
    }
    $(document).ready(function () {
        $('#btnTmp').click(function(e){
            e.preventDefault();
            var url = "<?= base_url('assets/template_fr.xlsx');?>"
            window.location.href = url;
        });
    });
    DisableBtn();
</script>