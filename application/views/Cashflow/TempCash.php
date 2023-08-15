<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Temp Cash</li>
</ol>
<h1 class="page-header">Temp Cash</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Temp Cash</h4>
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
                <table id="DtTempCash" class="table table-bordered table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DtTempCash_info" style="width: 100%;">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc" style="width: 30px;">No</th>
                            <th class="text-center sorting">Temp Code</th>
                            <th class="text-center sorting">Temp Name</th>
                            <th class="text-center sorting">Temp Group</th>
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
                        <label for="TEMPCODE">Temp Code *</label>
                        <input type="text" class="form-control" name="TEMPCODE" id="TEMPCODE" placeholder="Temp Code" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="TEMPNAME">Temp Name *</label>
                        <input type="text" class="form-control" name="TEMPNAME" id="TEMPNAME" placeholder="Temp Name" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="TEMPPARENT">Temp Parent</label>
                        <select class="form-control" name="TEMPPARENT" id="TEMPPARENT">
                            <option value="">Default</option>
                            <?php foreach ($tempCashParent as $key => $value) { ?>
                                <option value="<?= $value['TEMPCODE'] ?>"><?= $value['TEMPNAME'].' ('.$value['TEMPCODE'].')' ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="TEMPGROUP">Temp Group *</label>
                        <select class="form-control" name="TEMPGROUP" id="TEMPGROUP" required>
                            <option value="1">Cash In</option>
                            <option value="0">Cash Out</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="ISACTIVE">Status *</label>
                        <select class="form-control" name="ISACTIVE" id="ISACTIVE" required>
                            <option value="1">Active</option>
                            <option value="0">Non Active</option>
                        </select>
                    </div>
                </div>
    
                <?php if (!empty($_GET)) { ?>
                    <div class="panel-footer text-left">
                        <button type="submit" class="btn btn-primary btn-sm m-l-5 disableall">Save</button>
                        <button type="button" class="btn btn-warning btn-sm m-l-5" onclick="Cancel()">Cancel</button>
                    </div>
                <?php } ?>
            </form>
        
        <?php } ?>
    </div>
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
                var data = <?php echo json_encode($DtTempCash); ?>;
                SetData(data);
            }
        } else {
            if (!$.fn.DataTable.isDataTable('#DtTempCash')) {
                $('#DtTempCash').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('TempCash/ShowData') ?>",
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
                        {"data": "TEMPCODE"},
                        {"data": null,
                            "render": function (data, type, row) {
                                if (data.TEMPLEVEL > 1) {
                                    let space = ''
                                    for (let i=0; i<data.TEMPLEVEL*2 ; i++ ) {
                                        space += '&nbsp;'
                                    }
                                    return space + data.TEMPNAME
                                } else {
                                    return data.TEMPNAME
                                }
                            }
                        },
                        {"data": "TEMPGROUPNAME"},
                        {
                            "data": null,
                            "className": "text-center",
                            "render": function (data, type, row, meta) {
                                var html = '';
                                if (data.ISACTIVE) {
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
                $('#DtTempCash thead th').addClass('text-center');
                table = $('#DtTempCash').DataTable();
                table.on('click', '.edit', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    window.location.href = window.location.href + '?type=edit&id=' + data.TEMPCODE;
                });
                table.on('click', '.delete', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    if (confirm('Are you sure delete this data "' + data.TEMPNAME + '" ?')) {
                        $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('TempCash/Delete'); ?>",
                            data: {
                                TEMPCODE: data.TEMPCODE,
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
                $("#DtTempCash_filter").remove();
                $("#search").on({
                    'keyup': function () {
                        table.search(this.value, true, false, true).draw();
                    }
                });
            }
        }

        $("form").on("submit", function(event) {
            event.preventDefault()
            // let ID = ACTION === "EDIT" ? "&ID="+ID_PO : ""

            form_data = $('form input').serialize()+"&"+$('form select').serialize()+"&USERNAME="+USERNAME+"&ACTION="+ACTION

    	   	$.ajax({
    	        url:"<?php echo site_url('TempCash/Save'); ?>",
    	        method:"POST",
    	        data: form_data,
    	        success:function(response)
    	        {
                    let dataJSON = $.parseJSON(response)
                    
                    if (dataJSON.status == 200) {
                        alert(dataJSON.result.data);
                        location.replace("<?php echo site_url('TempCash'); ?>")
                    } else if (dataJSON.status == 504) {
                        alert(dataJSON.result.data);
                        location.reload();
                    } else {
                        alert(dataJSON.result.data);
                    }
    	        }  
    	   	});  
    	});

    });
    var Add = function () {
        window.location.href = window.location.href + '?type=add';
    };
    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }
    function SetDataKosong() {
        $('.panel-title').text('Add Data Temp Cash');
        ACTION = 'ADD';
    }
    function SetData(data) {
        $('.panel-title').text('Edit Data Temp Cash');
        $('#TEMPCODE').attr('readonly', true);
        $('#TEMPCODE').val(data.TEMPCODE);
        $('#TEMPNAME').val(data.TEMPNAME);
        $('#TEMPPARENT').val(data.TEMPPARENT);
        $('#TEMPGROUP').val(data.TEMPGROUP);
        $('#ISACTIVE').val(data.ISACTIVE);
        ACTION = 'EDIT';
    }
    
</script>