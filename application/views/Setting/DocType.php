<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Document Type</li>
</ol>
<h1 class="page-header">Document Type</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Document Type</h4>
    </div>
    <div class="panel-body">
        <?php if (empty($_GET)) { ?>
            <div class="row mb-2">
                <div class="col-md-8 pull-left">
                    <?php if ($ACCESS['ADDS'] == 1) { ?>
                        <button onclick="Add()" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> Add</button> 
                    <?php } ?>
                </div>
                <div class="col-md-4 pull-right">
                    <div class="input-group">
                        <input type="text" id="search" name="search" class="form-control" placeholder="Cari.." >
                    </div>
                </div>
            </div>
            <div class="row m-0 table-responsive">
                <table id="DtType" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DtType_info" style="width: 100%;">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc" style="width: 30px;">No</th>
                            <th class="text-center sorting">Doc. Code</th>
                            <th class="text-center sorting">Doc. Name</th>
                            <th class="text-center sorting">Doc. Type</th>
                            <th class="text-center sorting">Description</th>
                            <th class="text-center sorting">Status</th>
                            <th class="text-center sorting_disabled" aria-label="Action"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        <?php } else { ?>
            <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="fccode">Doc. Code *</label>
                        <input type="text" class="form-control" name="FCCODE" id="FCCODE" placeholder="Doc. Code" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="fcname">Doc. Name *</label>
                        <input type="text" class="form-control" name="FCNAME" id="FCNAME" placeholder="Doc. Name" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="description">Description *</label>
                        <input type="text" class="form-control" name="DESCRIPTION" id="DESCRIPTION" placeholder="Description" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="cashflowtype">Doc. Type *</label>
                        <select class="form-control" name="CASHFLOWTYPE" id="CASHFLOWTYPE" required>
                            <option value="0">Cash In</option>
                            <option value="1">Cash Out</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="isactive">Status *</label>
                        <select class="form-control" name="ISACTIVE" id="ISACTIVE" required>
                            <option value="TRUE">Active</option>
                            <option value="FALSE">Non Active</option>
                        </select>
                    </div>
                </div>
                <fieldset class="well mb-0">
                    <legend class="well-legend">List Document Template</legend>
                    <div class="row m-0 table-responsive">
                        <table id="DtTemplate" class="table table-bordered table-hover dataTable" role="grid" width="100%">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_disabled" style="width: 30px;"><input type="checkbox" id="pil"></th>
                                    <th class="sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                                    <th class="sorting">Document Name</th>
                                    <th class="sorting">Description</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </fieldset>
            </form>
        <?php } ?>
    </div>
    <?php if (!empty($_GET)) { ?>
        <div class="panel-footer text-left">
            <button type="button" id="btnSave" onclick="Save()" class="btn btn-primary btn-sm m-l-5">Save</button>
            <button type="button" class="btn btn-warning btn-sm m-l-5" onclick="Cancel()">Cancel</button>
        </div>
    <?php } ?>
</div>

<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
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
    var table, ACTION, table2;
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
                var data = <?php echo json_encode($DtDocType); ?>;
                SetData(data);
            }
        } else {
            if (!$.fn.DataTable.isDataTable('#DtType')) {
                $('#DtType').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('IDocType/ShowData') ?>",
                        "contentType": "application/json",
                        "type": "POST",
                        "data": function () {
                            var d = {};
                            return JSON.stringify(d);
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
                    "columns": [{
                            "data": null,
                            "className": "text-center",
                            render: function (data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {"data": "FCCODE"},
                        {"data": "FCNAME"},
                        {
                            "data": null,
                            "className": "text-center",
                            "render": function (data, type, row, meta) {
                                var html = '';
                                if (data.CASHFLOWTYPE == 0) {
                                    html += '<span class="badge badge-pill badge-info">Cash In</span>';
                                } else if (data.CASHFLOWTYPE == 1) {
                                    html += '<span class="badge badge-pill badge-info">Cash Out</span>';
                                } else {
                                    html += '<span class="badge badge-pill badge-danger">No Type</span>';
                                }
                                return html;
                            }
                        },
                        {"data": "DESCRIPTION"},
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
                $('#DtType thead th').addClass('text-center');
                table = $('#DtType').DataTable();
                table.on('click', '.edit', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    window.location.href = window.location.href + '?type=edit&id=' + data.FCCODE;
                });
                table.on('click', '.delete', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    if (confirm('Are you sure delete this data "' + data.FCNAME + '" ?')) {
                        $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('IDocType/Delete'); ?>",
                            data: {
                                FCCODE: data.FCCODE,
                                USERNAME: USERNAME
                            },
                            success: function (response) {
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
                                alert('Error deleting data !!');
                            }
                        });
                    }
                });
                $("#DtType_filter").remove();
                $("#search").on({
                    'keyup': function () {
                        table.search(this.value, true, false, true).draw();
                    }
                });
            }
        }
    });
    var Add = function () {
        window.location.href = window.location.href + '?type=add';
    };
    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }
    function SetDataKosong() {
        $('.panel-title').text('Add Data Document Type');
        $('#FCCODE').val('');
        $('#FCNAME').val('');
        $('#DESCRIPTION').val('');
        $('#CASHFLOWTYPE').val('0');
        $('#ISACTIVE').val('TRUE');
        LoadDtAccess();
        ACTION = 'ADD';
    }
    function SetData(data) {
        $('.panel-title').text('Edit Data Document Type');
        $('#FCCODE').attr('readonly', true);
        $('#FCCODE').val(data.FCCODE);
        $('#FCNAME').val(data.FCNAME);
        $('#DESCRIPTION').val(data.DESCRIPTION);
        $('#CASHFLOWTYPE').val(data.CASHFLOWTYPE);
        $('#ISACTIVE').val(data.ISACTIVE);
        LoadDtAccess();
        ACTION = 'EDIT';
    }
    var LoadDtAccess = function () {
        if (!$.fn.DataTable.isDataTable('#DtTemplate')) {
            $('#DtTemplate').DataTable({
                "processing": true,
                "ajax": {
                    "url": "<?php echo site_url('IDocType/GetListSystem') ?>",
                    "contentType": "application/json",
                    "type": "POST",
                    "data": function () {
                        var d = {};
                        d.FCCODE = $('#FCCODE').val();
                        return JSON.stringify(d);
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
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            if (data.ISACTIVE == 1) {
                                return '<input type="checkbox" class="pils" checked>';
                            } else {
                                return '<input type="checkbox" class="pils">';
                            }
                        }
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {"data": "FCNAME"},
                    {"data": "DESCRIPTION"}
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
                "bFilter": false,
                "bPaginate": false,
                "bLengthChange": false,
                "bInfo": false
            });
            table2 = $('#DtTemplate').DataTable();
            table2.on('change', '.pils', function () {
                $tr = $(this).closest('tr');
                var data = table2.row($tr).data();
                if (this.checked) {
                    data.ISACTIVE = "1";
                } else {
                    data.ISACTIVE = "0";
                }
            });
        } else {
            table2.ajax.reload();
        }
        $('#pil').prop("checked", false);
    };
    $('#pil').on('change', function () {
        if (this.checked) {
            $('#DtTemplate .pils').prop("checked", true);
        } else {
            $('#DtTemplate .pils').prop("checked", false);
        }
        $('#DtTemplate .pils').change();
    });
    var Save = function () {
        if ($('#FAddEditForm').parsley().validate()) {
            $("#loader").show();
            $('#btnSave').attr('disabled', true);
            var dt = dttable(table2.data());
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('IDocType/Save'); ?>",
                data: {
                    DATA: dt,
                    FCCODE: $('#FCCODE').val(),
                    FCNAME: $('#FCNAME').val(),
                    CASHFLOWTYPE: $('#CASHFLOWTYPE').val(),
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
                    console.info(e);
                    alert('Data Save Failed !!');
                    $('#btnSave').removeAttr('disabled');
                }
            });
        }
    };
    function dttable(data) {
        var dt = [];
        for (var index = 0; index < data.length; ++index) {
            if (data[index].FCCODE == undefined || data[index].FCCODE == null || data[index].FCCODE == '') {
            } else {
                if (data[index].ISACTIVE == 1) {
                    dt.push(data[index]);
                }
            }
        }
        if (dt.length <= 0) {
            dt = 0;
        }
        return dt;
    }
</script>