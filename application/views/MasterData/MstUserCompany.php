<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script> -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Master User Company</li>
</ol>
<h1 class="page-header">Master User Company</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Master User Company</h4>
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
            <div class="row m-0 table-responsive">
                <table id="DtUsers" class="table table-bordered table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DtUsers_info" style="width: 100%;">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc" style="width: 30px;">No</th>
                            <th class="text-center sorting">Username</th>
                            <th class="text-center sorting_disabled" aria-label="Action"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        <?php } else { ?>
            <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="fccode">Username *</label>
                        <select class="form-control USERCODE" id="USERCODE" name="USERCODE" >
                            <option disabled selected>Select</option>
                        </select>
                    </div>
                </div>
                <fieldset class="well mb-0">
                    <legend class="well-legend">List Company</legend>
                    <div class="row m-0 table-responsive">
                        <table id="DtDepartement" class="table table-bordered table-hover dataTable" role="grid" width="100%">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_disabled" style="width: 30px;"><input type="checkbox" id="pil"></th>
                                    <th class="sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                                    <th class="sorting">Company Code</th>
                                    <th class="sorting">Company Name</th>
                                    <!-- <th class="sorting"></th> -->
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
    var USERNAMEUPDATE = "<?php echo $SESSION->FCCODE; ?>";
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
    var table, ACTION, USERCODE;
        $(".USERCODE").select2({
        // theme: 'bootstrap4',
        ajax: {
            url: "<?php echo site_url('UserCompany/getUserCode') ?>",
            dataType: 'json',
            delay: 250,
            type: 'GET',
            data: function(params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data, page) {
                // parse the results into the format expected by Select2.
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data
                return {
                    results: $.map(data, function (item) {
                      return {
                        id:item.FCCODE,
                        text:item.FULLNAME
                    }
                })
                };
            },
            cache: true
        },
        escapeMarkup: function(markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1,
        //templateResult: formatRepo,
        //templateSelection: formatRepoSelection
    });

    $(document).ready(function() {
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
                var data = <?php echo json_encode($DtUserCompany); ?>;
                SetData(data);
            }
        } else {
            if (!$.fn.DataTable.isDataTable('#DtUsers')) {
                $('#DtUsers').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('UserCompany/ShowData') ?>",
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
                            "data": "USERCODE"
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
                });
                $('#DtUsers thead th').addClass('text-center');
                table = $('#DtUsers').DataTable();
                table.on('click', '.edit', function() {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    window.location.href = window.location.href + '?type=edit&id=' + data.USERCODE;
                });
                $("#DtUsers_filter").remove();
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
        $('.panel-title').text('Add Data Master User Company');
        USERCODE = '';
        LoadDtAccess()
        ACTION = 'ADD';
    }

    function SetData(data) {
        $('.panel-title').text('Edit Data Master User Company');
        USERCODE = data.USERCODE;
        var FULLNAME = data.FULLNAME;
        $("#USERCODE").select2("trigger", "select", {
            data: { id: USERCODE, text: FULLNAME }
        });
        LoadDtAccess()
        ACTION = 'EDIT';
        $('#USERCODE').attr('disabled',true);
    }

    var LoadDtAccess = function() {
        if (!$.fn.DataTable.isDataTable('#DtDepartement')) {
            $('#DtDepartement').DataTable({
                "processing": true,
                "ajax": {
                    "url": "<?php echo site_url('UserCompany/GetListAccess') ?>",
                    "contentType": "application/json",
                    "type": "POST",
                    "data": function() {
                        var d = {};
                        d.USERCODE = USERCODE;
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
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            if (data.ISACTIVE == 1) {
                                return '<input type="checkbox" name="COMPANYCODEUser[]" class="pils" value="' + data.COMPANYCODE + '" checked>';
                            } else {
                                return '<input type="checkbox" name="COMPANYCODEUser[]" class="pils" value="' + data.COMPANYCODE + '">';
                            }
                        }
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": "COMPANYCODE"
                    },
                    {
                        "data": "COMPANYNAME"
                    },
                    // {
                    //     "data": null,
                    //     "orderable": false,
                    //     render: function (data, type, row, meta) {
                    //         if (data.FCCODE == null) {
                    //             data.FCCODE = '';
                    //         }
                    //         return '<input type="text" class="form-control" name="COMPANYCODEUser[]" value="' + data.FCCODE + '" style="width:100%">';
                    //     }
                    // },
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
                "bFilter": false,
                "bPaginate": false,
                "bLengthChange": false,
                "bInfo": false
            });
            table2 = $('#DtDepartement').DataTable();
            table2.on('change', '.pils', function() {
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
    $('#pil').on('change', function() {
        if (this.checked) {
            $('#DtDepartement .pils').prop("checked", true);
        } else {
            $('#DtDepartement .pils').prop("checked", false);
        }
        $('#DtDepartement .pils').change();
    });

    var Save = function() {
        var tablekondisi = true;
        $('#DtDepartement tbody tr').each(function(index, value) {
            if (table2.data()[index].ISACTIVE == 1) {
                var val = $(this).find('input:checkbox:first').val();
                if (val == '' || val == null || val == undefined) {
                    tablekondisi = false;
                    return false;
                } else {
                    table2.data()[index].COMPANYCODEUser = $(this).find('input:checkbox:first').val();
                }
            }
        });
        if (tablekondisi == false) {
            alert('User Departement Harus Diisi!');
        } else if ($('#FAddEditForm').parsley().validate()) {
            $("#loader").show();
            $('#btnSave').attr('disabled', true);
            var dt = dttable(table2.data());
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('UserCompany/Save'); ?>",
                data: {
                    USERCODE: $('#USERCODE').val(),
                    USERDpt: dt,
                    ACTION: ACTION,
                    USERNAME: USERNAMEUPDATE
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

    function dttable(data) {
        var dt = [];
        for (var index = 0; index < data.length; ++index) {
            if (data[index].COMPANYCODEUser == undefined || data[index].COMPANYCODEUser == null || data[index].COMPANYCODEUser == '') {} else {
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