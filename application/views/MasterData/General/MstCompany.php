<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Master Company</li>
</ol>
<h1 class="page-header">Master Company</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Master Company</h4>
    </div>
    <div class="panel-body">
        <?php if (empty($_GET)) { ?>
            <div class="row mb-2">
                <?php if ($DtUser2->USERACCESS == '100004') { ?>
                <?php }else{ ?>
                    <div class="col-md-8 pull-left">
                    <button onclick="Add()" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> Add</button> 
                </div>
                <?php } ?>
                <div class="col-md-4 pull-right">
                    <div class="input-group">
                        <input type="text" id="search" name="search" class="form-control" placeholder="Cari.." onkeyup="Search()">
                    </div>
                </div>
            </div>
            <div class="row m-0 table-responsive">
                <table id="DtCompany" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtCompany_info">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                            <th class="text-center sorting">Company Code</th>
                            <th class="text-center sorting">Company Name</th>
                            <th class="text-center sorting">Company No</th>
                            <th class="text-center sorting">Company Subgroup</th>
                            <th class="text-center sorting">Status</th>
                            <th class="text-center sorting_disabled"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        <?php } else { ?>
            <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="companycode">Company Subgroup * <label style="color: red">Ex: DOWNSTREAM, UPSTREAM, CEMENT</label></label>
                        <input type="text" class="form-control" name="COMPANY_SUBGROUP" id="COMPANY_SUBGROUP" placeholder="Company Subgroup" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="companycode">Company Code *</label>
                        <input type="text" class="form-control" name="COMPANYCODE" id="COMPANYCODE" placeholder="Company Code" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="companyname">Company Name *</label>
                        <input type="text" class="form-control" name="COMPANYNAME" id="COMPANYNAME" placeholder="Company Name" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="companyno">Company No *</label>
                        <input type="text" class="form-control" name="COMPANYNO" id="COMPANYNO" placeholder="Company No" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="fcpermission">Company Type *</label>
                        <select class="form-control" id="COMPANYTYPE" required>
                            <option value="" selected disabled>Choose Company Type</option>
                            <?php
                            foreach ($DtCompanyType as $values) {
                                echo '<option value=' . $values->DETAILID . '>' . $values->DETAILNAME . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="fullname">Status *</label>
                        <select class="form-control" name="ISACTIVE" id="ISACTIVE" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-secondary fade show">
                            <div class="panel panel-default panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1" >
                                <div class="panel-heading p-0">
                                    <!-- begin nav-tabs -->
                                    <div class="tab-overflow">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item"><a href="#nav-tab-1" data-toggle="tab" class="nav-link active">Company System</a></li>
                                            <li class="nav-item"><a href="#nav-tab-2" data-toggle="tab" class="nav-link">Company Department</a></li>
                                        </ul>
                                    </div>
                                    <!-- end nav-tabs -->
                                </div>  
                                <div class="tab-content" >
                                    <div class="tab-pane fade active show" id="nav-tab-1" >
                                        <table id="DtSystem" class="table table-bordered table-hover dataTable" role="grid" width="100%">
                                            <thead>
                                                <tr role="row">
                                                    <th class="text-center sorting_disabled" style="width: 30px;"><input type="checkbox" id="pil"></th>
                                                    <th class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                                                    <th class="text-center sorting">System Name</th>
                                                    <th class="text-center sorting">System Code</th>
                                                    <th class="text-center sorting">Remark</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade show" id="nav-tab-2" >
                                        <table id="DtDepart" class="table table-bordered table-hover dataTable" role="grid" width="100%">
                                            <thead>
                                                <tr role="row">
                                                    <th class="text-center sorting_disabled" style="width: 30px;"><input type="checkbox" id="pil1"></th>
                                                    <th class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                                                    <th class="text-center sorting">Departement Name</th>
                                                    <th class="text-center sorting">Remark</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>              
                        </div>
                    </div>                  
                </div>
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
    var table, ACTION, table2, table3, ID;
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
                var data = <?php echo json_encode($DtCompany); ?>;
                SetData(data);
            }
        } else {
            if (!$.fn.DataTable.isDataTable('#DtCompany')) {
                $('#DtCompany').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('ICompany/ShowData') ?>",
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
                        {"data": "COMPANYCODE"},
                        {"data": "COMPANYNAME"},
                        {"data": "COMPANYNO"},
                        {"data": "COMPANY_SUBGROUP"},
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
                            targets: -1
                        }
                    ]
                });
                $('#DtCompany thead th').addClass('text-center');
                table = $('#DtCompany').DataTable();
                table.on('click', '.edit', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    window.location.href = window.location.href + '?type=edit&id=' + data.ID;
                });
                table.on('click', '.delete', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    if (confirm('Are you sure delete this data "' + data.COMPANYNAME + '" ?')) {
                        $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('ICompany/Delete'); ?>",
                            data: {
                                ID: data.ID,
                                COMPANYCODE: data.COMPANYCODE,
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
                $("#DtCompany_filter").remove();
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
        $('.panel-title').text('Add Data Company');
        ID = "0";
        $('#COMPANY_SUBGROUP').val('');
        $('#COMPANYCODE').val('');
        $('#COMPANYNAME').val('');
        $('#COMPANYNO').val('');
        $('#COMPANYTYPE').val('');
        $('#ISACTIVE').val(1);
        LoadDtAccess();
        ACTION = 'ADD';
    }
    function SetData(data) {
        $('.panel-title').text('Edit Data Company');
        ID = data.ID;
        $('#COMPANY_SUBGROUP').val(data.COMPANY_SUBGROUP);
        $('#COMPANYCODE').val(data.COMPANYCODE);
        $('#COMPANYNAME').val(data.COMPANYNAME);
        $('#COMPANYNO').val(data.COMPANYNO);
        $('#COMPANYTYPE').val(data.COMPANYTYPE);
        $('#ISACTIVE').val(data.ISACTIVE);
        LoadDtAccess();
        ACTION = 'EDIT';
    }
    var LoadDtAccess = function () {
        if (!$.fn.DataTable.isDataTable('#DtSystem')) {
            $('#DtSystem').DataTable({
                "processing": true,
                "ajax": {
                    "url": "<?php echo site_url('ICompany/GetListSystem') ?>",
                    "contentType": "application/json",
                    "type": "POST",
                    "data": function () {
                        var d = {};
                        d.ID = ID;
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
                    {
                        "data": null,
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            if (data.EXTSYSCOMPANYCODE == null) {
                                data.EXTSYSCOMPANYCODE = '';
                            }
                            return '<input type="text" class="form-control" name="EXTCODE[]" value="' + data.EXTSYSCOMPANYCODE + '" style="width:100%">';
                        }
                    },
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
            table2 = $('#DtSystem').DataTable();
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
        if (!$.fn.DataTable.isDataTable('#DtDepart')) {
            $('#DtDepart').DataTable({
                "processing": true,
                "ajax": {
                    "url": "<?php echo site_url('ICompany/GetListDepartement') ?>",
                    "contentType": "application/json",
                    "type": "POST",
                    "data": function () {
                        var d = {};
                        d.ID = ID;
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
            table3 = $('#DtDepart').DataTable();
            table3.on('change', '.pils', function () {
                $tr = $(this).closest('tr');
                var data = table3.row($tr).data();
                if (this.checked) {
                    data.ISACTIVE = "1";
                } else {
                    data.ISACTIVE = "0";
                }
            });
        } else {
            table3.ajax.reload();
        }
        $('#pil1').prop("checked", false);
    };
    $('#pil').on('change', function () {
        if (this.checked) {
            $('#DtSystem .pils').prop("checked", true);
        } else {
            $('#DtSystem .pils').prop("checked", false);
        }
        $('#DtSystem .pils').change();
    });
    $('#pil1').on('change', function () {
        if (this.checked) {
            $('#DtDepart .pils').prop("checked", true);
        } else {
            $('#DtDepart .pils').prop("checked", false);
        }
        $('#DtDepart .pils').change();
    });
    var Save = function () {
        var tablekondisi = true;
        $('#DtSystem tbody tr').each(function (index, value) {
            if (table2.data()[index].ISACTIVE == 1) {
                var val = $(this).find('input[name="EXTCODE[]"]').first().val();
                if (val == '' || val == null || val == undefined) {
                    tablekondisi = false;
                    return false;
                } else {
                    table2.data()[index].EXTSYSCOMPANYCODE = $(this).find('input[name="EXTCODE[]"]').first().val();
                }
            }
        });
        if (tablekondisi == false) {
            alert('System Code yang di pilih mohon di isi !!!');
        } else if ($('#FAddEditForm').parsley().validate()) {
            $("#loader").show();
            $('#btnSave').attr('disabled', true);
            var dt = dttable(table2.data());
            var dt1 = dttable(table3.data());
//            console.info(dt);
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('ICompany/Save'); ?>",
                data: {
                    DATA: dt,
                    DATA1: dt1,
                    ID: ID,
                    COMPANY_SUBGROUP: $('#COMPANY_SUBGROUP').val(),
                    COMPANYCODE: $('#COMPANYCODE').val(),
                    COMPANYNAME: $('#COMPANYNAME').val(),
                    COMPANYNO: $('#COMPANYNO').val(),
                    COMPANYTYPE: $('#COMPANYTYPE').val(),
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