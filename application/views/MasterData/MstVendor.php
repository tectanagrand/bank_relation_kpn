<link href="./assets/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" />
<link href="./assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Master Supplier</li>
</ol>
<h1 class="page-header">Master Supplier</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Master Supplier</h4>
    </div>
    <div class="panel-body">
        <?php if (empty($_GET)) { ?>
            <div class="row mb-2">
                <div class="col-md-8 pull-left">
                    <?php if ($DtUser2->USERACCESS == '100004') { ?>
                <?php }else{ ?>
                    <button onclick="Add()" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> Add</button>
                    <button onclick="Upload()" class="btn btn-sm btn-info"><i class="fa fa-upload"></i> Upload</button>
                <?php } ?>
                </div>
                <div class="col-md-4 pull-right">
                    <div class="input-group">
                        <input type="text" id="search" name="search" class="form-control" placeholder="Cari..">
                    </div>
                </div>
            </div>
            <div class="row m-0 table-responsive">
                <table id="DtVendors" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtVendor_info">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                            <th class="text-center sorting">Supplier Code</th>
                            <th class="text-center sorting">Supplier Name</th>
                            <th class="text-center sorting">Bank Name</th>
                            <th class="text-center sorting">Status</th>
                            <th class="text-center sorting">Show</th>
                            <th class="text-center sorting_disabled">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        <?php } else { ?>
            <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="FCCODE">Supplier Code *</label>
                        <input type="text" class="form-control" name="FCCODE" id="FCCODE" placeholder="Supplier Code" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="FCNAME">Supplier Name *</label>
                        <input type="text" class="form-control" name="FCNAME" id="FCNAME" placeholder="Supplier Name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="ADDRESS">Address *</label>
                        <input type="text" class="form-control" name="ADDRESS" id="ADDRESS" placeholder="Address" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="CITY">City *</label>
                        <input type="text" class="form-control" name="CITY" id="CITY" placeholder="City" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="EMAIL">Email *</label>
                        <input type="type" class="form-control" name="EMAIL" id="EMAIL" placeholder="Email" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="BANKNAME">Bank Name *</label>
                        <input type="text" class="form-control" name="BANKNAME" id="BANKNAME" placeholder="Bank Name" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="BANKACCOUNT">Bank Account *</label>
                        <input type="text" class="form-control" name="BANKACCOUNT" id="BANKACCOUNT" placeholder="Bank Account" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="DESCRIPTION">Description</label>
                        <input type="text" class="form-control" name="DESCRIPTION" id="DESCRIPTION" placeholder="Description">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="fullname">Status *</label>
                        <select class="form-control" name="ISACTIVE" id="ISACTIVE" required>
                            <option value="TRUE">Active</option>
                            <option value="FALSE">Non Active</option>
                        </select>
                    </div>
                </div>
            </form>
        <?php } ?>
    </div>
    <?php if (!empty($_GET)) { ?>
        <div class="panel-footer text-left">
            <button type="button" id="btnSave" onclick="Save()" class="btn btn-primary btn-sm m-l-5">Save</button>
            <button type="button" id="btnSave" onclick="isiDefault()" class="btn btn-info btn-sm m-l-5">Default Input</button>
            <button type="button" class="btn btn-warning btn-sm m-l-5" onclick="Cancel()">Cancel</button>
        </div>
    <?php } ?>
</div>

<div class="modal fade" id="MUpload">
    <div class="modal-dialog" style="max-width: 90%  !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Data Upload Supplier</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="modal-body">
                    <fieldset class="well mb-0">
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
                                    <span>Browse File</span>
                                    <input type="file" class="upload-file" data-max-size="1048576" onchange="filesChange(this)">
                                </span>
                                <button id="btnReset" type="button" class="btn btn-default m-r-3" onclick="ClearData()">
                                    <span>Clear Data</span>
                                </button>
                            </div>
                        </div>
                        <div class="row ml-0 mr-0 mb-0 mt-2 table-responsive">
                            <table id="DtDetail" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                                        <th class="text-center sorting">Code Supplier</th>
                                        <th class="text-center sorting">Nama Supplier</th>
                                        <th class="text-center sorting">Address</th>
                                        <th class="text-center sorting">City</th>
                                        <th class="text-center sorting">Bank Name</th>
                                        <th class="text-center sorting">Bank Account</th>
                                        <th class="text-center sorting">Email</th>
                                        <th class="text-center sorting">Description</th>
                                        <th class="text-center sorting">Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer text-right">
                    <button type="button" id="btnSave" onclick="SaveUpload()" class="btn btn-primary btn-sm m-l-5">Upload</button>
                    <a href="javascript:;" class="btn btn-warning btn-sm m-l-5" data-dismiss="modal">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="./assets/md5.js"></script>
<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)), sURLVariables = sPageURL.split('&'), sParameterName, i;
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };

    var table, ACTION, ID;
    var table2, DATAUPLOAD = [], STATUS = true, FILENAME;
    var files, filetypeUpload = ['XLS', 'XLSX'];
//    Load Awal View dan (Add or Edit)
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
                var data = <?php echo json_encode($DtVendor); ?>;
                SetData(data);
            }
        } else {
            if (!$.fn.DataTable.isDataTable('#DtVendors')) {
                $('#DtVendors').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "<?php echo site_url('IVendor/ShowData') ?>",
                        "type": "POST",
                        "datatype": "JSON",
                        "data": function (d) {},
                        "dataSrc": function (ext) {
                            if (ext.status == 200) {
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
                        }
                    },
                    "columns": [{
                            "data": null,
                            "className": "text-center",
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            "data": "FCCODE"
                        },
                        {
                            "data": "FCNAME"
                        },
                        {
                            "data": "BANKNAME"
                        },
                        {
                            "data": null,
                            "className": "text-center",
                            "render": function (data, type, row, meta) {
                                var html = '';
                                if (data.ISACTIVE == 'TRUE') {
                                    html += '<span class="badge badge-pill badge-success">Active</span>';
                                } else {
                                    html += '<span class="badge badge-pill badge-danger">Non Active</span>';
                                }
                                return html;
                            }
                        },
                        {
                            "data": null,
                            "className": "text-center",
                            "render": function (data, type, row, meta) {
                                var html = '';
                                if (data.IS_SHOW == '1') {
                                    html += '<input type="checkbox" name="pils" class="pils" id="is_show" checked>';
                                } else {
                                    html += '<input type="checkbox" name="pils" class="pils" id="is_show">';
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
                                    html += '<button class="btn btn-success btn-icon btn-circle btn-sm edit" title="Edit" style="margin-right: 5px;">\n\
                                             <i class="fa fa-edit" aria-hidden="true"></i>\n\
                                             </button>';
                                }
                                if (DELETES == 1) {
                                    html += '<button class="btn btn-danger btn-icon btn-circle btn-sm delete" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
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
                $('#DtVendors thead th').addClass('text-center');
                table = $('#DtVendors').DataTable();
                table.on('click', '.edit', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    window.location.href = window.location.href + '?type=edit&id=' + data.ID;
                });
                table.on('change', '.pils', function () {
                            $tr = $(this).closest('tr');
                            var data = table.row($tr).data();
                            if (this.checked) {
                               $.ajax({
                                    dataType: "JSON",
                                    type: "POST",
                                    url: "<?php echo site_url('IVendor/isShow'); ?>",
                                    data: {
                                        ID: data.ID,
                                        SHOW:1,
                                        FCEDIT: USERNAME
                                    },
                                    success: function (response) {
                                        $('#loader').removeClass('show');
                                        if (response.status == 200) {
                                            toastr.success('Update Sukses');
                                            // table.ajax.reload();
                                        } else if (response.status == 504) {
                                            toastr.error('Update Gagal');
                                            // location.reload();
                                        } else {
                                            toastr.error('Update Gagal');
                                        }
                                    },
                                    error: function (e) {
                                        $('#loader').removeClass('show');
                                        alert('Error Updating data !!');
                                    }
                                });
                            } else {
                                $.ajax({
                                    dataType: "JSON",
                                    type: "POST",
                                    url: "<?php echo site_url('IVendor/isShow'); ?>",
                                    data: {
                                        ID: data.ID,
                                        SHOW:0,
                                        FCEDIT: USERNAME
                                    },
                                    success: function (response) {
                                        $('#loader').removeClass('show');
                                        if (response.status == 200) {
                                            toastr.success('Update Sukses');
                                            // table.ajax.reload();
                                        } else if (response.status == 504) {
                                            toastr.error('Update Gagal');
                                            // location.reload();
                                        } else {
                                            toastr.error('Update Gagal');
                                        }
                                    },
                                    error: function (e) {
                                        $('#loader').removeClass('show');
                                        alert('Error Updating data !!');
                                    }
                                });
                            }
                });
                table.on('click', '.delete', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    if (confirm('Are you sure delete this data "' + data.FCNAME + '" ?')) {
                        $('#loader').addClass('show');
                        $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('IVendor/Delete'); ?>",
                            data: {
                                ID: data.ID,
                                USERNAME: USERNAME
                            },
                            success: function (response) {
                                $('#loader').removeClass('show');
                                if (response.status == 200) {
                                    alert(response.result.data);
                                    table.ajax.reload();
                                } else if (response.status == 504) {
                                    alert(response.result.data);
                                    location.reload();
                                } else {
                                    alert(response.result.data);
                                }
                            },
                            error: function (e) {
                                $('#loader').removeClass('show');
                                alert('Error deleting data !!');
                            }
                        });
                    }
                });
                $("#DtVendors_filter").remove();
                let timeOutonKeyup = null;
                $("#search").on({
                    'input': function () {
                        var dataKeywords = this.value;
                        clearTimeout(timeOutonKeyup);
                        timeOutonKeyup = setTimeout(function () {
                            table.search(dataKeywords, true, false, true).draw();
                        }, 1000);
                    }
                });
            }
        }
    });

    var Add = function () {
        window.location.href = window.location.href + '?type=add';
    };

    var Cancel = function () {
        window.location.href = window.location.href.split("?")[0];
    };

    var SetDataKosong = function () {
        $('.panel-title').text('Add Data Supplier');
        ID = "";
        $('#FCCODE').val('');
        $('#FCNAME').val('');
        $('#ADDRESS').val('');
        $('#CITY').val('');
        $('#EMAIL').val('');
        $('#BANKNAME').val('');
        $('#BANKACCOUNT').val('');
        $('#DESCRIPTION').val('');
        $('#ISACTIVE').val('');
        ACTION = 'ADD';
    };

    var SetData = function (data) {
        $('.panel-title').text('Edit Data Supplier');
        ID = data.ID;
        $('#FCCODE').val(data.FCCODE);
        $('#FCNAME').val(data.FCNAME);
        $('#ADDRESS').val(data.ADDRESS);
        $('#CITY').val(data.CITY);
        $('#EMAIL').val(data.EMAIL);
        $('#BANKNAME').val(data.BANKNAME);
        $('#BANKACCOUNT').val(data.BANKACCOUNT);
        $('#DESCRIPTION').val(data.DESCRIPTION);
        $('#ISACTIVE').val(data.ISACTIVE);
        ACTION = 'EDIT';
    }

    var isiDefault = function () {
        $('#FCCODE').val(md5($('#FCNAME').val()));
        // $('#FCNAME').val("-");
        $('#ADDRESS').val("-");
        $('#CITY').val("-");
        $('#EMAIL').val("-");
        $('#BANKNAME').val("-");
        $('#BANKACCOUNT').val("-");
        $('#DESCRIPTION').val("-");
        $('#ISACTIVE').val("TRUE").change();
    }

    var Save = function () {
        if ($('#FAddEditForm').parsley().validate()) {
            $("#loader").show();
            $('#btnSave').attr('disabled', true);
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('IVendor/Save'); ?>",
                data: {
                    ID: ID,
                    FCCODE: $('#FCCODE').val(),
                    FCNAME: $('#FCNAME').val(),
                    ADDRESS: $('#ADDRESS').val(),
                    CITY: $('#CITY').val(),
                    EMAIL: $('#EMAIL').val(),
                    BANKNAME: $('#BANKNAME').val(),
                    BANKACCOUNT: $('#BANKACCOUNT').val(),
                    DESCRIPTION: $('#DESCRIPTION').val(),
                    ISACTIVE: $('#ISACTIVE').val(),
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
                },
                error: function (e) {
                    $("#loader").hide();
                    alert('Data Save Failed !!');
                    $('#btnSave').removeAttr('disabled');
                }
            });
        }
    };

    var Upload = function () {
        DATAUPLOAD = [];
        $('#EXTSYSTEM').val('');
        STATUS = true;
        LoadDtUpload();
        DisableBtn();
        $('#MUpload').modal({
            backdrop: 'static',
            keyboard: false
        });
    };

    var LoadDtUpload = function () {
        if (!$.fn.DataTable.isDataTable('#DtDetail')) {
            $('#DtDetail').DataTable({
                "aaData": DATAUPLOAD,
                "columns": [{
                        "data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {"data": "CODESUPPLIER"},
                    {"data": "NAMASUPPLIER"},
                    {"data": "ADDRESS"},
                    {"data": "CITY"},
                    {"data": "BANKNAME"},
                    {"data": "BANKACCOUNT"},
                    {"data": "EMAIL"},
                    {"data": "DESCRIPTION"},
                    {
                        "data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            var html = '';
                            if (data.STATUS == 0) {
                                html += '<span class="badge badge-pill badge-success">Done</span>';
                            } else {
                                html += '<span class="badge badge-pill badge-danger" title="' + data.MESSAGE + '">' + data.MESSAGE + '</span>';
                                STATUS = false;
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
            table2 = $('#DtDetail').DataTable();
        } else {
            table2.clear();
            table2.rows.add(DATAUPLOAD);
            table2.draw();
        }
    };

    var DisableBtn = function () {
        if (files == '' || files == undefined || files == null) {
            $(".fileinput-button").removeClass('disabled');
            $(".upload-file").removeAttr('disabled');
            $("#btnReset").attr('disabled', true);
        } else {
            $(".fileinput-button").addClass('disabled');
            $(".upload-file").attr('disabled', true);
            $("#btnReset").removeAttr('disabled');
        }
    };



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
                        $(".modal-title").text('Item Upload : ' + FILENAME);
                        DisableBtn();
                        var fd = new FormData();
                        $.each(files, function (i, data) {
                            fd.append("uploads", data);
                        });
                        fd.append("EXTSYSTEM", $('#EXTSYSTEM').val());
                        $.ajax({
                            dataType: "JSON",
                            type: 'POST',
                            url: "<?php echo site_url('IVendor/ListDataUpload'); ?>",
                            data: fd,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $('#loader').removeClass('show');
                                if (response.status == 200) {
                                    STATUS = true;
                                    DATAUPLOAD = response.result.data;
                                    LoadDtUpload();
                                } else if (response.status == 504) {
                                    alert(response.result.data);
                                    location.reload();
                                } else {
                                    alert(response.result.data);
                                    files = '';
                                    $('.upload-file').val('');
                                    $(".modal-title").text('Upload Item');
                                    DisableBtn();
                                }
                            },
                            error: function (e) {
                                $('#loader').removeClass('show');
                                alert('Error Upload Data !!');
                                files = '';
                                $('.upload-file').val('');
                                $(".modal-title").text('Upload Item');
                                DisableBtn();
                            }
                        });
                    }
                }
            }
        }
    }
    var ClearData = function () {
        files = '';
        $('.upload-file').val('');
        DATAUPLOAD = [];
        LoadDtUpload();
        DisableBtn();
    };



    var SaveUpload = function () {
        if (STATUS == false) {
            alert('Data masih ada yang error !!!');
        } else if (DATAUPLOAD.length <= 0) {
            alert('Data yang di upload tidak ada !!!');
        } else {
            $('#loader').addClass('show');
            $('#btnSave').attr('disabled', true);
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('IVendor/SaveUpload'); ?>",
                data: {
                    DATA: JSON.stringify(DATAUPLOAD),
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
                        DATAUPLOAD = [];
                        table2.clear();
                        table2.rows.add(DATAUPLOAD);
                        table2.draw();
                        DisableBtn();
                        $('#MUpload').modal('hide');
                        table.ajax.reload();

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
</script>