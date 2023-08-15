<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Week Setting</li>
</ol>
<h1 class="page-header">Week Setting</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Week Setting</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-2">
            <div class="col-md-8 pull-left"></div>
            <div class="col-md-4 pull-right">
                <div class="form-row">
                        <div class="col">
                            <select class="form-control" id="YEAR" required>
                                <option value="" selected disabled>Choose Year</option>
                                <?php
                                foreach ($DtYear as $values) {
                                    echo '<option value=' . $values->YEAR . '>' . $values->YEAR . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                <div class="col">
                    <input type="text" id="search" name="search" class="form-control" placeholder="Cari.." >
                </div>
            </div>
            </div>
        </div>
        <div class="row m-0 table-responsive">
            <table id="DtSystem" class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DtSystem_info" style="width: 100%;">
                <thead>
                    <tr role="row">
                        <th class="text-center sorting" style="width: 30px;">YEAR</th>
                        <th class="text-center sorting_asc" style="width: 30px;">MONTH</th>
                        <th class="text-center sorting">MONTHNAME</th>
                        <th class="text-center sorting">WEEK</th>
                        <th class="text-center sorting">DATEFROM</th>
                        <th class="text-center sorting">DATEUNTIL</th>
                        <th class="text-center sorting_disabled" aria-label="Action"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
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
    
    var YEAR = '';
    var table, ACTION;
    $(document).ready(function () {
        if (!$.fn.DataTable.isDataTable('#DtSystem')) {
            $('#DtSystem').DataTable({
                "processing": true,
                "ajax": {
                    "url": "<?php echo site_url('IWeek/ShowData') ?>",
                    //"contentType": "application/json",
                    "type": "POST",
                    "dataType": "JSON",
                    "data": function (d) {
                        d.YEAR = YEAR;
                        // var d = {};
                        // return JSON.stringify(d);
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
                    {"data": "YEAR"},
                    {"data": "MONTH"},
                    {"data": "MONTHNAME"},
                    {"data": "WEEK"},
                    {
                        "data": null,
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            var html = '';
                            if (EDITS == 1) {
                                html += '<select class="form-control form'+meta.row+'" id="DATEFROM'+meta.row+'" required style="width:100%" disabled>\n\
                                    <option value="" disabled>Choice Date</option>';
                                    for (let index = 1; index < 32; index++) {
                                        let selected = ''
                                        if (data.DATEFROM == index) {
                                            selected = ' selected'
                                        }
                                        html += '<option value="'+index+'"'+selected+'>'+index+'</option>';
                                    }
                                html += '</select>';
                            }
                            return html;
                        }
                    },
                    {
                        "data": null,
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            var html = '';
                            if (EDITS == 1) {
                                html += '<select class="form-control form'+meta.row+'" id="DATEUNTIL'+meta.row+'" required style="width:100%" disabled>\n\
                                    <option value="" disabled>Choice Date</option>';
                                    for (let index = 1; index < 32; index++) {
                                        let selected = ''
                                        if (data.DATEUNTIL == index) {
                                            selected = ' selected'
                                        }
                                        html += '<option value="'+index+'"'+selected+'>'+index+'</option>';
                                    }
                                html += '</select>';
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
                                html += '<button class="btn btn-success btn-icon btn-circle btn-sm edit edit'+meta.row+'" title="Edit" style="margin-right: 5px;" id="'+meta.row+'">\n\
                                            <i class="fa fa-edit" aria-hidden="true"></i>\n\
                                            </button>';
                                html += '<button class="btn btn-primary btn-icon btn-circle btn-sm save save'+meta.row+'" title="Edit" style="margin-right: 5px;" id="'+meta.row+'" data-month="'+data.MONTH+'" data-week="'+data.WEEK+'" hidden onclick="Save('+meta.row+')">\n\
                                            <i class="fa fa-save" aria-hidden="true"></i>\n\
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
            $('#DtSystem thead th').addClass('text-center');
            table = $('#DtSystem').DataTable();
            table.on('click', '.edit', function () {
                $('.form'+$(this).attr('id')).removeAttr("disabled")
                $('.save'+$(this).attr('id')).removeAttr("hidden")
                $('.edit'+$(this).attr('id')).attr("hidden", true)
            });
            table.on('click', '.save', function () {
                $tr = $(this).closest('tr');
                var data = table.row($tr).data();
                $('.form'+$(this).attr('id')).attr("disabled", true)
                $('.save'+$(this).attr('id')).attr("hidden", true)
                $('.edit'+$(this).attr('id')).removeAttr("hidden")
            });
            
            $("#DtSystem_filter").remove();
            $("#search").on({
                'keyup': function () {
                    table.search(this.value, true, false, true).draw();
                }
            });
        }
        $("#YEAR").on('change', function() {
                YEAR = $(this).val();
                table.ajax.reload();
        })
    });


    
    var Save = function (data) {
        $.ajax({
            dataType: "JSON",
            type: "POST",
            url: "<?php echo site_url('IWeek/Save'); ?>",
            data: {
                DATEFROM: $('#DATEFROM'+data).val(),
                DATEUNTIL: $('#DATEUNTIL'+data).val(),
                MONTH: $('.save'+data).attr('data-month'),
                WEEK: $('.save'+data).attr('data-week')
            },
            success: function (response) {
                if (response.status == 200) {
                    alert(response.result.data);
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
</script>