<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Master Role Access</li>
</ol>
<h1 class="page-header">Master Role Access</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Master Role Access</h4>
    </div>
    <div class="panel-body">
        <?php if (empty($_GET)) { ?>
            <div class="row mb-2">
                <div class="col-md-8 pull-left">
                    <button onclick="Add()" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> Add</button> 
                </div>
                <div class="col-md-4 pull-right">
                    <div class="input-group">
                        <input type="text" id="search" name="search" class="form-control" placeholder="Cari.." onkeyup="Search()">
                    </div>
                </div>
            </div>
            <div class="row m-0 table-responsive">
                <table id="DtPermission" class="table table-striped table-bordered dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DtPermission_info" style="width: 100%;">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc" style="width: 30px;">No</th>
                            <th class="text-center sorting">Permission</th>
                            <th class="text-center sorting">Status</th>
                            <th class="sorting_disabled" aria-label="Action"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        <?php } else { ?>
            <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="username">Role Name *</label>
                        <input type="text" class="form-control" name="USERGROUPNAME" id="USERGROUPNAME" placeholder="Role Name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="fullname">Status *</label>
                        <select class="form-control" name="ISACTIVE" id="ISACTIVE" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <fieldset class="well mb-0">
                    <legend class="well-legend">Menu Access</legend>
                    <div class="row m-0 table-responsive">
                        <table id="DtAccess" class="table table-bordered table-striped table-hover dataTable" role="grid" width="100%">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                                    <th class="sorting">Menu</th>
                                    <th class="sorting">Menu Parent</th>
                                    <th class="sorting_disabled"><input type="checkbox" id="vie"> View</th>
                                    <th class="sorting_disabled"><input type="checkbox" id="add"> Add</th>
                                    <th class="sorting_disabled"><input type="checkbox" id="edi"> Edit</th>
                                    <th class="sorting_disabled"><input type="checkbox" id="del"> Delete</th>
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

<script type="text/javascript">
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
    var table, USERGROUPID, ACTION, table2;
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
                var data = <?php echo json_encode($DtRole); ?>;
                SetData(data);
            }
        } else {
            if (!$.fn.DataTable.isDataTable('#DtPermission')) {
                $('#DtPermission').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('IPermission/ShowData') ?>",
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
                        {"data": "USERGROUPNAME"},
                        {
                            "data": null,
                            "className": "text-center",
                            "render": function (data, type, row, meta) {
                                var html = '';
                                if (data.ISACTIVE == 1) {
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
                                if (EDITS == 1) {
                                    html += '<button class="btn btn-success btn-icon btn-circle btn-sm edit" title="Edit" style="margin-right: 5px;">\n\
                                        <i class="fa fa-edit" aria-hidden="true"></i>\n\
                                     </button>';
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
                $('#DtPermission thead th').addClass('text-center');
                table = $('#DtPermission').DataTable();
                table.on('click', '.edit', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    window.location.href = window.location.href + '?type=edit&id=' + data.USERGROUPID;
                });
                $("#DtPermission_filter").remove();
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
        $('.panel-title').text('Add Menu Permission');
        USERGROUPID = 0;
        $('#USERGROUPNAME').val('');
        $('#ISACTIVE').val(1);
        LoadDtAccess();
        ACTION = 'ADD';
    }
    function SetData(data) {
        $('.panel-title').text('Edit Menu Permission');
        USERGROUPID = data.USERGROUPID;
        $('#USERGROUPNAME').val(data.USERGROUPNAME);
        $('#ISACTIVE').val(data.ISACTIVE);
        LoadDtAccess();
        ACTION = 'EDIT';
    }
    var LoadDtAccess = function () {
        if (!$.fn.DataTable.isDataTable('#DtAccess')) {
            $('#DtAccess').DataTable({
                "processing": true,
                "ajax": {
                    "url": "<?php echo site_url('IPermission/GetListAccess') ?>",
                    "contentType": "application/json",
                    "type": "POST",
                    "data": function () {
                        var d = {};
                        d.USERGROUPID = USERGROUPID;
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
                    {
                        "data": "MENUNAME"
                    },
                    {
                        "data": "MENUPARENTNAME"
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            if (data.VIEWS == 1) {
                                return '<input type="checkbox" class="views" checked>';
                            } else {
                                return '<input type="checkbox" class="views">';
                            }

                        }
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            if (data.ADDS == 1) {
                                return '<input type="checkbox" class="adds" checked>';
                            } else {
                                return '<input type="checkbox" class="adds">';
                            }
                        }
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            if (data.EDITS == 1) {
                                return '<input type="checkbox" class="edits" checked>';
                            } else {
                                return '<input type="checkbox" class="edits">';
                            }
                        }
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            if (data.DELETES == 1) {
                                return '<input type="checkbox" class="deletes" checked>';
                            } else {
                                return '<input type="checkbox" class="deletes">';
                            }
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
                "bFilter": false,
                "bPaginate": false,
                "bLengthChange": false,
                "bInfo": false
            });
            table2 = $('#DtAccess').DataTable();
            table2.on('change', '.views', function () {
                $tr = $(this).closest('tr');

                var data = table2.row($tr).data();
                if (this.checked) {
                    data.VIEWS = "1";
                } else {
                    data.VIEWS = "0";
                }
            });
            table2.on('change', '.adds', function () {
                $tr = $(this).closest('tr');

                var data = table2.row($tr).data();
                if (this.checked) {
                    data.ADDS = "1";
                } else {
                    data.ADDS = "0";
                }
            });
            table2.on('change', '.edits', function () {
                $tr = $(this).closest('tr');

                var data = table2.row($tr).data();
                if (this.checked) {
                    data.EDITS = "1";
                } else {
                    data.EDITS = "0";
                }
            });
            table2.on('change', '.deletes', function () {
                $tr = $(this).closest('tr');

                var data = table2.row($tr).data();
                if (this.checked) {
                    data.DELETES = "1";
                } else {
                    data.DELETES = "0";
                }
            });
        } else {
            table2.ajax.reload();
        }
        $('#vie').prop("checked", false);
        $('#add').prop("checked", false);
        $('#edi').prop("checked", false);
        $('#del').prop("checked", false);
    };
    $('#vie').on('change', function () {
        if (this.checked) {
            $('#DtAccess .views').prop("checked", true);
        } else {
            $('#DtAccess .views').prop("checked", false);
        }
        $('#DtAccess .views').change();
    });
    $('#add').on('change', function () {
        if (this.checked) {
            $('#DtAccess .adds').prop("checked", true);
        } else {
            $('#DtAccess .adds').prop("checked", false);
        }
        $('#DtAccess .adds').change();
    });
    $('#edi').on('change', function () {
        if (this.checked) {
            $('#DtAccess .edits').prop("checked", true);
        } else {
            $('#DtAccess .edits').prop("checked", false);
        }
        $('#DtAccess .edits').change();
    });
    $('#del').on('change', function () {
        if (this.checked) {
            $('#DtAccess .deletes').prop("checked", true);
        } else {
            $('#DtAccess .deletes').prop("checked", false);
        }
        $('#DtAccess .deletes').change();
    });
    var Save = function () {
        if ($('#FAddEditForm').parsley().validate()) {
            $("#loader").show();
            $('#btnSave').attr('disabled', true);
            var dt = dttable(table2.data());
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('IPermission/Save'); ?>",
                data: {
                    DATA: dt,
                    USERGROUPID: USERGROUPID,
                    USERGROUPNAME: $('#USERGROUPNAME').val(),
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
            if (data[index].MENUCODE == undefined || data[index].MENUCODE == null || data[index].MENUCODE == '') {
            } else {
                dt.push(data[index]);
            }
        }
        return dt;
    }
</script>