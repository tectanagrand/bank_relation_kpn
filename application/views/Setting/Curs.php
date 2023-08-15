<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Kurs Setting</li>
</ol>
<h1 class="page-header">Kurs Setting</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Kurs Setting</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-2">
            <div class="col-md-2 pull-left">
                <label for="PERIOD">Period</label>
                <input type="text" class="form-control" name="PERIOD" id="PERIOD" placeholder="MMM YYYY" autocomplete="off">
            </div>
        </div>
        <div class="row m-0 table-responsive">
            <table id="DtCurs" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                <thead>
                    <tr role="row">
                        <th class="text-center sorting">No</th>
                        <th class="text-center sorting">Kurs</th>
                        <th class="text-center sorting">Rate</th>
                        <th class="text-center sorting_disabled" aria-label="Action">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="MAddEditForm">
    <div class="modal-dialog" style="max-width: 50%  !important;">>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Kurs</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="CURSCODE">Kurs Code</label>
                                <input type="text" class="form-control" id="CURSCODE" name="CURSCODE" placeholder="Curs Code" required disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="RATE">Rate</label>
                                <input type="text" class="form-control" id="RATE" name="RATE" placeholder="Rate" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="Save()">Save</button>
                </div>
            </form>
        </div>
    </div>
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
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var table, ACTION, ID, MONTH = '',
        YEAR = '', DETAILID;
    if (!$.fn.DataTable.isDataTable('#DtCurs')) {
        table = $('#DtCurs').DataTable({
            "processing": true,
            "ajax": {
                "url": "<?php echo site_url('IKurs/ShowData') ?>",
                "type": "POST",
                "datatype": "JSON",
                "data": function(d) {
                    d.YEAR = YEAR;
                    d.MONTH = MONTH;
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
                    "data": "CURSCODE"
                },
                {
                    "data": "RATE",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": null,
                    "className": "text-center",
                    "render": function(data, type, row, meta) {
                        var html = '';
                        if (EDITS == 1) {
                            html += '<button class="btn btn-success btn-icon btn-circle btn-sm edit edit' + meta.row + '" title="Edit" style="margin-right: 5px;" id="' + meta.row + '">\n\
                                            <i class="fa fa-edit" aria-hidden="true"></i>\n\
                                            </button>';
                        }
                        return html;
                    }
                }
            ],
            "bFilter": true,
            "bPaginate": true,
            "bLengthChange": true,
            "bInfo": true,
            "responsive": false,
            "search": {
                "regex": true
            }
        });

        table = $('#DtCurs').DataTable();
        table.on('click', ".edit", function() {
            $tr = $(this).closest('tr');
            var data = table.row($tr).data();
            ACTION = "EDIT";
            $('#FAddEditForm').parsley().reset();
            $('#CURSCODE').val(data.CURSCODE);
            $('#RATE').val(data.RATE);
            $('#MAddEditForm').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        //    Load Date Picker Period
        $('#PERIOD').datepicker({
            "autoclose": true,
            "todayHighlight": true,
            "viewMode": "months",
            "minViewMode": "months",
            "format": "M yyyy"
        });

        $('#PERIOD').on({
            'change': function() {
                MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
                YEAR = this.value.substr(4, 4);
                table.ajax.reload();
            }
        });
    }

    var Save = function(data) {
        if ($('#FAddEditForm').parsley().validate()) {
            var data = {
                "CURSCODE": $('#CURSCODE').val(),
                "RATE": $('#RATE').val(),
                USERNAME:USERNAME,
                YEAR:YEAR,
                MONTH:MONTH,
            };
            $.ajax({
                "dataType": "JSON",
                "type": "POST",
                url: "<?php echo site_url('IKurs/Save') ?>",
                data: data,
                success: function(response, textStatus, jqXHR) {
                    if (response.status == 200) {
                        alert(response.result.data);
                        table.ajax.reload();
                        $('#MAddEditForm').modal('hide');
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        location.reload();
                    } else {
                        alert(response.result.data);
                    }
                },
                error: function(jqXHR, textStatus, errorThown) {
                    alert('Data Save Failed !!');
                }
            });
        }
    };
</script>