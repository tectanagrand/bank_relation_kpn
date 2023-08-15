
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Master Departement Category</li>
</ol>
<h1 class="page-header">Master Departement Category</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Master Departement Category</h4>
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
                <table id="DtSystem" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtSystem_info">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc" style="width: 30px;">No</th>
                            <th class="text-center sorting">Departement</th>
                            <th class="text-center sorting">Material Group</th>
                            <th class="text-center sorting">Forecast Category</th>
                            <th class="text-center sorting_disabled" aria-label="Action"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        <?php } else { ?>
            <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="fccode">Departement *</label>
                        <select class="form-control" name="DEPARTMENT" id="DEPARTMENT" required>
                            <option value="" selected disabled>Choose Department</option>
                            <?php
                                foreach ($departement as $values) {
                                    echo '<option value=' . $values->FCCODE . '>' . $values->FCNAME . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="fcname">Material Group *</label>
                        <select class="form-control" name="MATERIALGROUP" id="MATERIALGROUP" required>
                            <option value="" selected disabled>Choose Mat. Group</option>
                            <?php
                                foreach ($matgroup as $values) {
                                    echo '<option value=' . $values->ID . '>' . $values->FCNAME . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="description">Forecast Category *</label>
                        <select class="form-control" name="FORECAST_CATEGORY" id="FORECAST_CATEGORY" required>
                            <option value="" selected disabled>Choose Forecast Cat.</option>
                            <?php
                                foreach ($forcat as $values) {
                                    echo '<option value=' . $values->FCCODE . '>' . $values->FCNAME . '</option>';
                                }
                            ?>
                        </select>
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
<script src="./assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
    <script src="./assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
    <script src="./assets/plugins/DataTables/extensions/Buttons/js/buttons.flash.min.js"></script>
    <script src="./assets/plugins/DataTables/extensions/Buttons/js/jszip.min.js"></script>
    <script src="./assets/plugins/DataTables/extensions/Buttons/js/vfs_fonts.min.js"></script>
    <script src="./assets/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js"></script>
    <script src="./assets/plugins/DataTables/extensions/Buttons/js/buttons.print.min.js"></script>
<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var OLD_DEPARTMENT, OLD_MATERIALGROUP, OLD_FORECAST_CATEGORY;

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
    var table, ACTION;
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
                var data = <?php echo json_encode($DtDepartement); ?>;
                SetData(data);
            }
        } else {
            if (!$.fn.DataTable.isDataTable('#DtSystem')) {
                $('#DtSystem').DataTable({
                    "processing": true,
                    "dom": "Bfrtip",
                    "buttons": [{
                        extend: "excel",
                        className: "btn-xs btn-green"
                    }],
                    "ajax": {
                        "url": "<?php echo site_url('IDepartement/ShowDataCat') ?>",
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
                        {"data": "DEPARTMENT"},
                        {"data": "MATERIALGROUP"},
                        {"data": "FORECAST_CATEGORY"},
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
                    "bFilter": true,
                    "bPaginate": true,
                    "bLengthChange": true,
                    "paging": true,
                    "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
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
                $('#DtSystem thead th').addClass('text-center');
                table = $('#DtSystem').DataTable();
                table.on('click', '.edit', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    window.location.href = window.location.href + '?type=edit&depart=' + data.DEPARTMENT + '&matgroup=' + data.MATERIALGROUP + '&forcat=' + data.FORECAST_CATEGORY;
                });
                table.on('click', '.delete', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    if (confirm('Are you sure delete this data?')) {
                        $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('IDepartement/DeleteCat'); ?>",
                            data: {
                                DEPARTMENT: data.DEPARTMENT,
                                MATERIALGROUP: data.MATERIALGROUP,
                                FORECAST_CATEGORY: data.FORECAST_CATEGORY,
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
                $("#DtSystem_filter").remove();
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
        $('.panel-title').text('Add Data Departement Category');
        $('#DEPARTMENT').val('');
        $('#MATERIALGROUP').val('');
        $('#FORECAST_CATEGORY').val('');
        ACTION = 'ADD';
    }

    function SetData(data) {
        console.log(data)
        OLD_DEPARTMENT = data.DEPARTMENT;
        OLD_MATERIALGROUP = data.MATERIALGROUP;
        OLD_FORECAST_CATEGORY = data.FORECAST_CATEGORY;
        $('.panel-title').text('Edit Data Departement Category');
        $('#DEPARTMENT').val(data.DEPARTMENT);
        $('#MATERIALGROUP').val(data.MATERIALGROUP);
        $('#FORECAST_CATEGORY').val(data.FORECAST_CATEGORY);
        ACTION = 'EDIT';
    }
    
    var Save = function () {
        if ($('#FAddEditForm').parsley().validate()) {
            $("#loader").show();
            $('#btnSave').attr('disabled', true);
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('IDepartement/SaveCat'); ?>",
                data: {
                    DEPARTMENT: $('#DEPARTMENT').val(),
                    MATERIALGROUP: $('#MATERIALGROUP').val(),
                    FORECAST_CATEGORY: $('#FORECAST_CATEGORY').val(),
                    OLD_DEPARTMENT: OLD_DEPARTMENT,
                    OLD_MATERIALGROUP: OLD_MATERIALGROUP,
                    OLD_FORECAST_CATEGORY: OLD_FORECAST_CATEGORY,
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
</script>