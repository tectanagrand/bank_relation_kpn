<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Master Bank</li>
</ol>
<h1 class="page-header">Master Bank</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Master Bank</h4>
    </div>
    <div  class="panel panel-default" style="white-space: nowrap; height: 100%; overflow-x: scroll; overflow-y: hidden;">
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
								<th class="text-center sorting">Company</th>
								<th class="text-center sorting">Company Group</th>
								<th class="text-center sorting">Bank Code</th>
								<th class="text-center sorting">Bank Name</th>
								<th class="text-center sorting">Bank Account</th>
								<th class="text-center sorting">Address</th>
								<th class="text-center sorting">City</th>
								<th class="text-center sorting">Monthly Forecast</th>
								<th class="text-center sorting">Remarks</th>
								<th class="text-center sorting">Activation</th>
								<th class="text-center sorting_disabled" aria-label="Action"></th>
							</tr>
						</thead>
					</table>
				</div>
			<?php } else { ?>
				<form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
					<div class="row">
						<div class="form-group col-md-6">
							<label for="company">Company *</label>
							<select class="form-control" name="COMPANY" id="COMPANY" required>
								<option value="" selected disabled>Choose Company</option>
								<?php
								foreach ($DtCompany as $values) {
									echo '<option value=' . $values->ID . '>' . $values->COMPANYNAME . '</option>';
								}
								?>
							</select>
						</div>
						<div class="form-group col-md-6">
							<label for="companygroup">Company Group *</label>
							<select class="form-control" name="COMPANYGROUP" id="COMPANYGROUP" required>
								<option value="" selected disabled>Choose Company Group</option>
								<?php
								foreach ($DtCompanygroup as $values) {
									echo '<option value=' . $values->FCCODE . '>' . $values->FCNAME . '</option>';
								}
								?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<label for="fccode">Bank Code *</label>
							<input type="text" class="form-control" name="FCCODE" id="FCCODE" placeholder="Bank Code" required>
						</div>
						<div class="form-group col-md-6">
							<label for="fcname">Bank Name *</label>
							<input type="text" class="form-control" name="FCNAME" id="FCNAME" placeholder="Bank Name" required>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<label for="bankaccount">Bank Account *</label>
							<input type="text" class="form-control" name="BANKACCOUNT" id="BANKACCOUNT" placeholder="Bank Account" required>
						</div>
                        <div class="form-group col-md-6">
							<label for="currency">Currency *</label>
							<select class="form-control" name="CURRENCY" id="CURRENCY" required>
								<option value="" selected disabled>Choose Currency</option>
								<?php
								foreach ($DtCurrency as $values) {
									echo '<option value=' . $values->DETAILID . '>' . $values->DETAILID . '</option>';
								}
								?>
							</select>
						</div>
                    </div>
                    <div class="row">
						<div class="form-group col-md-12">
							<label for="address">Address *</label>
							<input type="text" class="form-control" name="ADDRESS" id="ADDRESS" placeholder="Address" required>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<label for="city">City *</label>
							<input type="text" class="form-control" name="CITY" id="CITY" placeholder="City" required>
						</div>
						<div class="form-group col-md-6">
							<label for="state">State *</label>
							<input type="text" class="form-control" name="STATE" id="STATE" placeholder="State">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<label for="remarks">Remarks</label>
							<input type="text" class="form-control" name="REMARKS" id="REMARKS" placeholder="Remarks">
						</div>
						<div class="form-group col-md-6">
							<label for="activation">Activation *</label>
							<select class="form-control" name="ACTIVATION" id="ACTIVATION" required >
								<option value="" selected disabled>Choose Activation</option>
								<option value="Y">Yes</option>
								<option value="N">No</option>
							</select>
						</div>
					</div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="activation">Monthly Forecast *</label>
                            <select class="form-control" name="ISUSEFORMONTHLYFORECAST" id="ISUSEFORMONTHLYFORECAST" required >
                                <option value="" selected disabled>Choose</option>
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select>
                        </div>
                    </div>
				</form>
			<?php } ?>
		</div>
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
                var data = <?php echo json_encode($DtBank); ?>;
                SetData(data);
            }
        } else {
            if (!$.fn.DataTable.isDataTable('#DtSystem')) {
                $('#DtSystem').DataTable({
                    "processing": true,
                    "ajax": {
                        "url": "<?php echo site_url('IBank/ShowData') ?>",
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
                        {"data": "COMPANYNAME"},
                        {"data": "COMPANYGROUPNAME"},
                        {"data": "FCCODE"},
                        {"data": "FCNAME"},
                        {"data": "BANKACCOUNT"},
                        {"data": "ADDRESS"},
                        {"data": "CITY"},
                        {
                            "data": null,
                            "className": "text-center",
                            "render": function (data, type, row, meta) {
                                var html = '';
                                if (data.ISUSEFORMONTHLYFORECAST == 'Y') {
                                    html += '<span class="badge badge-pill badge-success">Yes</span>';
                                } else {
                                    html += '<span class="badge badge-pill badge-danger">No</span>';
                                }
                                return html;
                            }
                        },
                        {"data": "REMARKS"},
                        {
                            "data": null,
                            "className": "text-center",
                            "render": function (data, type, row, meta) {
                                var html = '';
                                if (data.ACTIVATION == 'Y') {
                                    html += '<span class="badge badge-pill badge-success">Yes</span>';
                                } else {
                                    html += '<span class="badge badge-pill badge-danger">No</span>';
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
                $('#DtSystem thead th').addClass('text-center');
                table = $('#DtSystem').DataTable();
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
                            url: "<?php echo site_url('IBank/Delete'); ?>",
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
                $("#DtSystem_filter").remove();
                let timeOutonKeyup = null;
                $("#search").on({
                    'input': function () {
                        let dataKeywords = this.value
                        clearTimeout(timeOutonKeyup);
                        timeOutonKeyup = setTimeout(function() {
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
    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }
    function SetDataKosong() {
        $('.panel-title').text('Add Data Bank');
        $('#FCCODE').val('');
        $('#FCNAME').val('');
        $('#BANKACCOUNT').val('');
        $('#CURRENCY').val('');
        $('#ADDRESS').val('');
        $('#CITY').val('');
        $('#STATE').val('');
        $('#REMARKS').val('');
        $('#ACTIVATION').val('');
        $('#COMPANY').val('');
        $('#COMPANYGROUP').val('');
        $('#ISUSEFORMONTHLYFORECAST').val();
        ACTION = 'ADD';
    }
    function SetData(data) {
        $('.panel-title').text('Edit Data Bank');
        $('#FCCODE').attr('readonly', true);
        $('#FCCODE').val(data.FCCODE);
        $('#FCNAME').val(data.FCNAME);
        $('#BANKACCOUNT').val(data.BANKACCOUNT);
        $('#CURRENCY').val(data.CURRENCY);
        $('#ADDRESS').val(data.ADDRESS);
        $('#CITY').val(data.CITY);
        $('#REMARKS').val(data.REMARKS);
        $('#ACTIVATION').val(data.ACTIVATION);
        $('#COMPANY').val(data.COMPANY);
        $('#COMPANYGROUP').val(data.COMPANYGROUP);
        $('#ISUSEFORMONTHLYFORECAST').val(data.ISUSEFORMONTHLYFORECAST);
        ACTION = 'EDIT';
    }
    var Save = function () {
        if ($('#FAddEditForm').parsley().validate()) {
            $("#loader").show();
            $('#btnSave').attr('disabled', true);
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('IBank/Save'); ?>",
                data: {
                    FCCODE: $('#FCCODE').val(),
                    FCNAME: $('#FCNAME').val(),
                    BANKACCOUNT: $('#BANKACCOUNT').val(),
                    CURRENCY: $('#CURRENCY').val(),
                    ADDRESS: $('#ADDRESS').val(),
                    CITY: $('#CITY').val(),
                    STATE: $('#STATE').val(),
                    REMARKS: $('#REMARKS').val(),
                    ACTIVATION: $('#ACTIVATION').val(),
                    COMPANY: $('#COMPANY').val(),
                    COMPANYGROUP: $('#COMPANYGROUP').val(),
                    ISUSEFORMONTHLYFORECAST: $('#ISUSEFORMONTHLYFORECAST').val(),
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