<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Item Group</li>
</ol>
<h1 class="page-header">Item Group</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Item Group</h4>
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
                        <input type="text" id="search" name="search" class="form-control" placeholder="Cari..">
                    </div>
                </div>
            </div>

            <div class="modal fade" id="PODetailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="PODetailTital">List Materials</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row m-0 table-responsive">
                                <table id="DetailPOList" class="table table-bordered table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DetailPOList_info" style="width: 100%;">
                                    <thead>
                                        <tr role="row">
                                            <th class="text-center sorting_asc" style="width: 30px;">No</th>
                                            <th class="text-center sorting">EXT System</th>
                                            <th class="text-center sorting">FCCODE</th>
                                            <th class="text-center sorting">FCNAME</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row m-0 table-responsive">
                <table id="DtMaterial" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtMaterial_info">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc" style="width: 30px;">No</th>
                            <th class="text-center sorting">Group Code</th>
                            <th class="text-center sorting">Group Name</th>
                            <th class="text-center sorting">Description</th>
                            <th class="text-center sorting">Status</th>
                            <th class="text-center sorting_disabled" aria-label="Action"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        <?php } else { ?>
            <div class="modal fade" id="AddMaterials" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="PODetailTital">List Materials</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row m-0 table-responsive">
                                <table id="DetailPOList" class="table table-bordered table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DetailPOList_info" style="width: 100%;">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting" style="width: 30px;">
                                                <input type="checkbox" id="pil">
                                            </th>
                                            <th class="text-center sorting_asc" style="width: 30px;">No</th>
                                            <th class="text-center sorting">External System</th>
                                            <th class="text-center sorting">Material Code</th>
                                            <th class="text-center sorting">Material Name</th>
                                            <th class="text-center sorting">Description</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="fccode">Material Group Code *</label>
                        <input type="text" class="form-control" name="FCCODE" id="FCCODE" placeholder="Material Group Code" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="fcname">Material Group Name *</label>
                        <input type="text" class="form-control" name="FCNAME" id="FCNAME" placeholder="Material Group Name" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="description">Description </label>
                        <input type="text" class="form-control" name="DESCRIPTION" id="DESCRIPTION" placeholder="Description">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="FORECAST_CATEGORY">Forecast Cat. *</label>
                        <select class="form-control" name="FORECAST_CATEGORY" id="FORECAST_CATEGORY" required>
                            <option value="" selected disabled>Choose Forecast Cat.</option>
                            <?php
                            foreach ($forecast as $values) {
                                echo '<option value=' . $values->FCCODE . '>' . $values->FCNAME . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="isactive">Status *</label>
                        <select class="form-control" name="ISACTIVE" id="ISACTIVE" required>
                            <option value="TRUE">Active</option>
                            <option value="FALSE">Non Active</option>
                        </select>
                    </div>
                </div>
                <?php if ($_GET['type'] == 'edit') { ?>
                    <fieldset class="well mb-0">
                        <legend class="well-legend">List Material</legend>
                        <div class="row m-0 table-responsive">
                            <table id="DtMaterialLegends" class="table table-bordered table-hover dataTable" role="grid" width="100%">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                                        <th class="sorting">Enternal System</th>
                                        <th class="sorting">Material Code</th>
                                        <th class="sorting">Material Name</th>
                                        <th class="sorting">Description</th>
                                        <th class="sorting"></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </fieldset>
                <?php } ?>
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

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const ID_Material = urlParams.get('id')

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
    var table, ACTION, table2, ID, AddMaterial;
    $(document).ready(function() {
        /* Data Table for Add Materials */
        let PenampungdataRusak = []
        $(document).on('click', '#AddMaterialModal', function() {
            let PenampungDataAsli = ''

            if (PenampungdataRusak.length > 0) {
                PenampungDataAsli = PenampungdataRusak[PenampungdataRusak.length - 1];
            }

            let dataMaterialss = {
                MATERIAL: [],
                EXTSYSTEM: []
            }

            AddMaterial = $('#DetailPOList').DataTable({
                "dom": '<"saveMaterial pull-left">frtip',
                data: [],
                columns: [{
                        "data": null,
                        "className": "text-center",
                        render: function(data, type, row, meta) {
                            return '<input type="checkbox" class="pils" value="' + data.ID + '" data-attr="' + data.EXTSYSTEM + '"><label class="pillb hide">0</label>';
                        }
                    },
                    // {"data": "ID"},
                    {
                        "data": null,
                        "className": "text-center",
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": "EXTSYSTEM"
                    },
                    {
                        "data": "FCCODE"
                    },
                    {
                        "data": "FCNAME"
                    },
                    {
                        "data": "DESCRIPTION"
                    },
                ],
                order: [
                    [1, "asc"]
                ],
                rowCallback: function(row, data) {},
                filter: true,
                info: true,
                processing: true,
                retrieve: true
            });

            $.ajax({
                url: "<?= site_url('IMaterialGroup/GetNotselectedMaterial') ?>",
                type: "POST",
                data: {
                    ID: ID_Material
                }
            }).done(function(result) {
                const resultJSON = JSON.parse(result)
                AddMaterial.clear().draw();
                AddMaterial.rows.add(resultJSON.result.data).draw();
            }).fail(function(jqXHR, textStatus, errorThrown) {
                // needs to implement if it fails
            });
            $('#AddMaterials').modal('show');
            $("div.saveMaterial").html('<input type="button" id="SaveMaterialModal" class="btn btn-sm btn-info" value="Save">');

            AddMaterial.on('change', '.pils', function() {
                let MATERIAL = $(this).val()
                let EXTSYSTEM = $(this).attr('data-attr')

                if (this.checked) {
                    dataMaterialss['MATERIAL'].push(MATERIAL)
                    dataMaterialss['EXTSYSTEM'].push(EXTSYSTEM)
                } else {
                    if (dataMaterialss['MATERIAL'].includes(MATERIAL)) {
                        dataMaterialss['MATERIAL'].splice(dataMaterialss['MATERIAL'].indexOf(MATERIAL), 1)
                        dataMaterialss['EXTSYSTEM'].splice(dataMaterialss['EXTSYSTEM'].indexOf(MATERIAL), 1)
                    }
                }
                PenampungdataRusak.push(dataMaterialss)
                return false
            })
            
        });

        /* Save Data Materials */
        $(document).on('click', '#SaveMaterialModal', function() {
            /* Modifikasi Data Yang di Pilih */
            let PenampungDataAsli = ''

            if (PenampungdataRusak.length > 0) {
                PenampungDataAsli = PenampungdataRusak[PenampungdataRusak.length - 1];
            }
            /* End Modifikasi Data */

            $("#loader").show();
            $('#SaveMaterialModal').attr('disabled', true);
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('IMaterialGroup/saveMaterial'); ?>",
                data: {
                    DATA: PenampungDataAsli,
                    ID: ID,
                    USERNAME: USERNAME
                },
                success: function(response) {
                    $("#loader").hide();
                    $('#SaveMaterialModal').removeAttr('disabled');
                    if (response.status == 200) {
                        alert(response.result.data);
                        $('#AddMaterials').modal('hide');
                        $('#DtMaterialLegends').DataTable().ajax.reload();
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        location.reload();
                    } else {
                        alert(response.result.data);
                    }
                },
                error: function(e) {
                    $("#loader").hide();
                    console.info(e);
                    alert('Data Save Failed !!');
                    $('#SaveMaterialModal').removeAttr('disabled');
                }
            });
        })

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
                var data = <?php echo json_encode($DtMaterialGroup); ?>;
                SetData(data);
            }
        } else {
            if (!$.fn.DataTable.isDataTable('#DtMaterial')) {
                $('#DtMaterial').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('IMaterialGroup/ShowData') ?>",
                        "contentType": "application/json",
                        "type": "POST",
                        "data": function() {
                            var d = {};
                            return JSON.stringify(d);
                        },
                        "dataSrc": function(ext) {
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
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            "data": "FCCODE"
                        },
                        {
                            "data": "FCNAME"
                        },
                        {
                            "data": "DESCRIPTION"
                        },
                        {
                            "data": null,
                            "className": "text-center",
                            "render": function(data, type, row, meta) {
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
                            render: function(data, type, row, meta) {
                                var html = '';
                                if (EDITS == 1) {
                                    html += '<button class="btn btn-success btn-icon btn-circle btn-sm edit" title="Edit" style="margin-right: 5px;">\n\
                                             <i class="fa fa-edit" aria-hidden="true"></i>\n\
                                             </button>';
                                }
                                html += '<button class="btn btn-info btn-icon btn-circle btn-sm viewPO" title="View" style="margin-right: 5px;">\n\
                                        <i class="fa fa-eye" aria-hidden="true"></i>\n\
                                        </button>';
                                if (DELETES == 1) {
                                    html += '<button class="btn btn-danger btn-icon btn-circle btn-sm delete" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                                }
                                return html;
                            }
                        }
                    ],
                    responsive: {
                        details: {
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col, i) {
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
                $('#DtMaterial thead th').addClass('text-center');
                table = $('#DtMaterial').DataTable();

                table.on('click', '.edit', function() {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    window.location.href = window.location.href + '?type=edit&id=' + data.ID;
                });

                table.on('click', '.viewPO', function() {
                    $tr = $(this).closest('tr');
                    let data = table.row($tr).data();

                    ViewPO = $('#DetailPOList').DataTable({
                        data: [],
                        columns: [{
                                "data": null,
                                "className": "text-center",
                                render: function(data, type, row, meta) {
                                    return meta.row + 1;
                                }
                            },
                            {
                                "data": "EXTSYSTEM"
                            },
                            {
                                "data": "FCCODE"
                            },
                            {
                                "data": "FCNAME"
                            },
                        ],
                        rowCallback: function(row, data) {},
                        filter: true,
                        info: true,
                        // ordering: false,
                        processing: true,
                        retrieve: true
                    });

                    $.ajax({
                        url: "<?= site_url('IMaterialGroup/ShowMaterial') ?>",
                        type: "POST",
                        data: {
                            ID: data.ID
                        }
                    }).done(function(result) {
                        const resultJSON = JSON.parse(result)
                        ViewPO.clear().draw();
                        ViewPO.rows.add(resultJSON.result.data).draw();
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        // needs to implement if it fails
                    });
                    $('#PODetailModal').modal('show');
                });

                table.on('click', '.delete', function() {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    if (confirm('Are you sure delete this data "' + data.FCNAME + '" ?')) {
                        $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('IMaterialGroup/Delete'); ?>",
                            data: {
                                ID: data.ID,
                                USERNAME: USERNAME
                            },
                            success: function(response) {
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
                            error: function(e) {
                                alert('Error deleting data !!');
                            }
                        });
                    }
                });

                $("#DtMaterial_filter").remove();
                $("#search").on({
                    'keyup': function() {
                        table.search(this.value, true, false, true).draw();
                    }
                });
            }
        }
    });
    var Add = function() {
        window.location.href = window.location.href + '?type=add';
    };

    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }

    function SetDataKosong() {
        $('.panel-title').text('Add Data Item Group');
        ID = 0;
        $('#FCCODE').val('');
        $('#FCNAME').val('');
        $('#DESCRIPTION').val('');
        $('#ISACTIVE').val('TRUE');
        LoadDtAccess();
        ACTION = 'ADD';
    }

    function SetData(data) {
        $('.panel-title').text('Edit Data Item Group');
        ID = data.ID;
        //$('#FCCODE').attr('readonly', true);
        $('#FCCODE').val(data.FCCODE);
        $('#FCNAME').val(data.FCNAME);
        $('#DESCRIPTION').val(data.DESCRIPTION);
        $('#ISACTIVE').val(data.ISACTIVE);
        $('#FORECAST_CATEGORY').val(data.FORECAST_CATEGORY)
        LoadDtAccess();
        ACTION = 'EDIT';
    }

    var LoadDtAccess = function() {
        if (!$.fn.DataTable.isDataTable('#DtMaterialLegends')) {
            $('#DtMaterialLegends').DataTable({
                "dom": '<"toolbar pull-left">frtip',
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?php echo site_url('IMaterialGroup/GetListMaterial') ?>",
                    // "contentType": "application/json",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function(d) {
                        d.ID = ID
                    },
                    "dataSrc": function(ext) {
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
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        "data": "EXTSYSTEMNAME",
                        "name": "M.EXTSYSTEM"
                    },
                    {
                        "data": "FCCODE"
                    },
                    {
                        "data": "FCNAME"
                    },
                    {
                        "data": "DESCRIPTION"
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            var html = '<button class="btn btn-danger btn-icon btn-circle btn-sm delete" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                            return html;
                        }
                    }
                ],
                responsive: {
                    details: {
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col, i) {
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
                "bLengthChange": true,
                "bInfo": true,
                "search": {
                    "regex": true
                },
                "order": [
                    [0, "asc"]
                ]
            });
            tablelegendmaterial = $('#DtMaterialLegends').DataTable()
            $("div.toolbar").html('<input type="button" id="AddMaterialModal" class="btn btn-sm btn-info" value="Add">');
        } else {
            table2.ajax.reload();
        }
        $('#pil').prop("checked", false);

        /* Delete Data Materials */
        tablelegendmaterial.on('click', '.delete', function() {
            $tr = $(this).closest('tr');
            let data2 = tablelegendmaterial.row($tr).data();
            if (confirm('Are you sure delete this data "' + data2.FCNAME + '" ?')) {
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('IMaterialGroup/DeleteGroupItem'); ?>",
                    data: {
                        ID: data2.ID
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            alert(response.result.data);
                            tablelegendmaterial.ajax.reload();
                        } else if (response.status == 504) {
                            alert(response.result.data);
                            location.reload();
                        } else {
                            alert(response.result.data);
                        }
                    },
                    error: function(e) {
                        alert('Error deleting data !!');
                    }
                });
            }
        });
    };

    $('#pil').on('change', function () {
        if (this.checked) {
            $('#DetailPOList .pils').prop("checked", true);
        } else {
            $('#DetailPOList .pils').prop("checked", false);
        }
        $('#DetailPOList .pils').change();
    });

    var Save = function() {
        if ($('#FAddEditForm').parsley().validate()) {
            $("#loader").show();
            $('#btnSave').attr('disabled', true);
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('IMaterialGroup/Save'); ?>",
                data: {
                    ID: ID,
                    FCCODE: $('#FCCODE').val(),
                    FCNAME: $('#FCNAME').val(),
                    DESCRIPTION: $('#DESCRIPTION').val(),
                    ISACTIVE: $('#ISACTIVE').val(),
                    FORECAST_CATEGORY: $('#FORECAST_CATEGORY').val(),
                    ACTION: ACTION,
                    USERNAME: USERNAME
                },
                success: function(response) {
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
                error: function(e) {
                    $("#loader").hide();
                    console.info(e);
                    alert('Data Save Failed !!');
                    $('#btnSave').removeAttr('disabled');
                }
            });
        }
    };
</script>