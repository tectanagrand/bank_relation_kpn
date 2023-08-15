<link href="https://cdn.datatables.net/rowgroup/1.1.1/css/rowGroup.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/rowgroup/1.1.1/js/dataTables.rowGroup.min.js"></script>
<style>
    tr.dtrg-group.dtrg-start.dtrg-level-0 {
        border-top: 1px solid #fff;
    }
    tr.dtrg-group.dtrg-end.dtrg-level-0 {
        border-bottom: 10px solid #fff;
    }
    table.dataTable tr.dtrg-group.dtrg-level-1 td, table.dataTable tr.dtrg-group.dtrg-level-2 td {
        background-color: #848484 !important;
        color: white !important;
    }
    .dataTables_processing {
        top: 155px !important;
        z-index: 11000 !important;
    }
</style>
<!-- begin widget-chart -->
<div class="widget widget-rounded m-b-30">
    <!-- begin widget-header -->
    <div class="widget-header">
        <h4 class="widget-header-title">Monthly Forecast</h4>
        <div class="widget-header-icon"><a href="#" class="text-muted"><i class="fa fa-fw fa-upload"></i></a></div>
        <div class="widget-header-icon"><a href="#" class="text-muted"><i class="fa fa-fw fa-cog"></i></a></div>
    </div>
    <!-- end widget-header -->
    <!-- begin vertical-box -->
    <div class="vertical-box with-grid with-border-top">
        <!-- begin vertical-box-column -->
        <div class="vertical-box-column p-15" style="width: 15%;">
            <div class="widget-chart-info">
				<div class="widget-chart-info-progress">
                    <b>Year :</b>
                    <input type="text" class="form-control" name="PERIOD" id="PERIOD" autocomplete="off">
                </div>
				<!-- <div class="widget-chart-info-progress">
                    <b>Month :</b>
                    <select class="form-control category" data-size="10" id='category' data-plugin='select2' name='category' data-live-search="true" data-style="btn-primary">
                        <option value="ALL" selected>Select a Category</option>
                    </select>
                </div> -->
                <!-- <div class="widget-chart-info-progress">
                    <b>Business Group :</b>
                    <select class="form-control businessgroup" data-size="10" id='businessgroup' data-plugin='select2' name='businessgroup' data-live-search="true" data-style="btn-primary">
						<option value="" selected>Select a Business Group</option>
                    </select>
                </div> -->
				<!-- <div class="widget-chart-info-progress">
                    <b>Cashflow Type :</b>
                    <select class="form-control cashflowtype" data-size="10" id='cashflowtype' data-plugin='select2' name='cashflowtype' data-live-search="true" data-style="btn-primary">
                        <option value="ALL" selected>All</option>
                        <option value="0">Cash In</option>
                        <option value="1">Cash Out</option>
                    </select>
                </div> -->
				<!-- <div class="widget-chart-info-progress">
                    <b>Category :</b>
                    <select class="form-control category" data-size="10" id='category' data-plugin='select2' name='category' data-live-search="true" data-style="btn-primary">
                        <option value="ALL" selected>Select a Category</option>
                    </select>
                </div> -->
            </div>
            <!-- <div class="widget-chart-info">
                <button type="button" id="btnSave" onclick="loadEmployee()" class="btn btn-success btn-sm">Submit</button>
            </div> -->
        </div>
        <!-- end vertical-box-column -->
        <!-- begin vertical-box-column -->
        <div class="vertical-box-column widget-chart-content">
			<div class="alert alert-secondary fade show">
				<div class="tab-content" >
					<div class="row m-0 table-responsive">
					<table id="DtCash" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" aria-describedby="DtCash_info">
						<thead>
							<tr role="row">
								<!-- <th rowspan="2" class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th> -->
								<th rowspan="2" class="text-center no-sort">TYPE</th>								
								<th rowspan="2" class="text-center no-sort">CATEGORY</th>
								<th rowspan="2" class="text-center no-sort">Description</th>
								<th colspan="5" class="text-center no-sort">Forecast</th>
								<th colspan="5" class="text-center no-sort">Actual</th>
								<th colspan="5" class="text-center no-sort">Variance</th>
							</tr>
							<tr role="row">
								<th class="text-center no-sort">W1</th>
								<th class="text-center no-sort">W2</th>
								<th class="text-center no-sort">W3</th>
								<th class="text-center no-sort">W4</th>
								<th class="text-center no-sort">W5</th>
								<th class="text-center no-sort">W1</th>
								<th class="text-center no-sort">W2</th>
								<th class="text-center no-sort">W3</th>
								<th class="text-center no-sort">W4</th>
								<th class="text-center no-sort">W5</th>
								<th class="text-center no-sort">W1</th>
								<th class="text-center no-sort">W2</th>
								<th class="text-center no-sort">W3</th>
								<th class="text-center no-sort">W4</th>
								<th class="text-center no-sort">W5</th>
							</tr>
						</thead>
					</table>
					</div>
				</div>
			</div>
        </div>
        <!-- end vertical-box-column -->
    </div>
    <!-- end vertical-box -->
</div>
<!-- end widget-chart -->

<script>

    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
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
    var files, filetypeUpload = ['XLS', 'XLSX'];
    var DtCash = [];
    var STATUS = true;
    var MONTH, YEAR;
    const queryString = window.location.href;
    const type = queryString.substr(queryString.lastIndexOf('/') + 1);

    if (type === 'CashOut') {
        $('.title').text('Cash Out');
        var newtype = 1
    } else {
        $('.title').text('Yearly Forecast');
        var newtype = 0
    }

    //$('#EXTSYSTEM').val('');
    if (!$.fn.DataTable.isDataTable('#DtCash')) {
        $('#DtCash').DataTable({
            processing: true,
			"dom": "Bfrtip",
				"buttons": [{
					extend: "copy",
					className: "btn-xs btn-grey"
				}, {
					extend: "csv",
					className: "btn-xs btn-indigo"
				}, {
					extend: "excel",
					className: "btn-xs btn-green"
				}, {
					extend: "pdf",
					className: "btn-xs btn-danger"
				}, {
					extend: "print",
					className: "btn-xs btn-success"
				}],
                "language": {
                                    processing: '<span class="spinner"></span>'},
            "aaData": DtCash,
            "columns": [
				{ "data": "CASHFLOWTYPE", "orderable": false },
				{ "data": "FORECAST_CATEGORY", "orderable": false },
                { "data": "GROUPS", "orderable": false, 'className': 'pl-5' },
				{ "data": "PROPW1", "className": "text-right", render: $.fn.dataTable.render.number(',', '.', 0), "orderable": false },
                { "data": "PROPW2", "className": "text-right", render: $.fn.dataTable.render.number(',', '.', 0), "orderable": false },
                { "data": "PROPW3", "className": "text-right", render: $.fn.dataTable.render.number(',', '.', 0), "orderable": false },
                { "data": "PROPW4", "className": "text-right", render: $.fn.dataTable.render.number(',', '.', 0), "orderable": false },
                { "data": "PROPW5", "className": "text-right", render: $.fn.dataTable.render.number(',', '.', 0), "orderable": false },
				{ "data": "WACTUAL1", "className": "text-right", render: $.fn.dataTable.render.number(',', '.', 0), "orderable": false },
				{ "data": "WACTUAL2", "className": "text-right", render: $.fn.dataTable.render.number(',', '.', 0), "orderable": false },
				{ "data": "WACTUAL3", "className": "text-right", render: $.fn.dataTable.render.number(',', '.', 0), "orderable": false },
				{ "data": "WACTUAL4", "className": "text-right", render: $.fn.dataTable.render.number(',', '.', 0), "orderable": false },
				{ "data": "WACTUAL5", "className": "text-right", render: $.fn.dataTable.render.number(',', '.', 0), "orderable": false },
				{ "data": "WAVAR1", "className": "text-right", render: $.fn.dataTable.render.number(',', '.', 0), "orderable": false },
				{ "data": "WAVAR2", "className": "text-right", render: $.fn.dataTable.render.number(',', '.', 0), "orderable": false },
				{ "data": "WAVAR3", "className": "text-right", render: $.fn.dataTable.render.number(',', '.', 0), "orderable": false },
				{ "data": "WAVAR4", "className": "text-right", render: $.fn.dataTable.render.number(',', '.', 0), "orderable": false },
				{ "data": "WAVAR5", "className": "text-right", render: $.fn.dataTable.render.number(',', '.', 0), "orderable": false }
            ],
            "bFilter": true,
            "bPaginate": false,
            "bLengthChange": false,
            "bProcessing": true,
            "bInfo": false,
            "responsive": false,
            // "order": [[ 0, 'asc' ], [ 1, 'asc' ]],
            "columnDefs": [
                {"visible": false, "targets": [0, 1]}
            ],
            rowGroup: {
                dataSrc: ["CASHFLOWTYPE", "FORECAST_CATEGORY"],
                startRender: function (rows, group) {
                    return group;
                },
                endRender: function (rows, group) {
                    console.info(rows.data());
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                    };
                    var PROPW1 = rows.data().pluck('PROPW1').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var PROPW2 = rows.data().pluck('PROPW2').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var PROPW3 = rows.data().pluck('PROPW3').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var PROPW4 = rows.data().pluck('PROPW4').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var PROPW5 = rows.data().pluck('PROPW5').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var WACTUAL1 = rows.data().pluck('WACTUAL1').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var WACTUAL2 = rows.data().pluck('WACTUAL2').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var WACTUAL3 = rows.data().pluck('WACTUAL3').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var WACTUAL4 = rows.data().pluck('WACTUAL4').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var WACTUAL5 = rows.data().pluck('WACTUAL5').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var WAVAR1 = rows.data().pluck('WAVAR1').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var WAVAR2 = rows.data().pluck('WAVAR2').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var WAVAR3 = rows.data().pluck('WAVAR3').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var WAVAR4 = rows.data().pluck('WAVAR4').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var WAVAR5 = rows.data().pluck('WAVAR5').reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
					/*
                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
					
                    return $('<tr/>')
                            .append('<td>TOTAL ' + group + '</td>')
                            .append('<td class="text-right">' + numFormat(PROPW1) + '</td>')
                            .append('<td class="text-right">' + numFormat(PROPW2) + '</td>')
                            .append('<td class="text-right">' + numFormat(PROPW3) + '</td>')
                            .append('<td class="text-right">' + numFormat(PROPW4) + '</td>')
                            .append('<td class="text-right">' + numFormat(PROPW5) + '</td>')
                            .append('<td class="text-right">' + numFormat(WACTUAL1) + '</td>')
                            .append('<td class="text-right">' + numFormat(WACTUAL2) + '</td>')
                            .append('<td class="text-right">' + numFormat(WACTUAL3) + '</td>')
                            .append('<td class="text-right">' + numFormat(WACTUAL4) + '</td>')
                            .append('<td class="text-right">' + numFormat(WACTUAL5) + '</td>')
                            .append('<td class="text-right">' + numFormat(WAVAR1) + '</td>')
                            .append('<td class="text-right">' + numFormat(WAVAR2) + '</td>')
                            .append('<td class="text-right">' + numFormat(WAVAR3) + '</td>')
                            .append('<td class="text-right">' + numFormat(WAVAR4) + '</td>')
                            .append('<td class="text-right">' + numFormat(WAVAR5) + '</td>');
							*/
                },
        },

        });
        table = $('#DtCash').DataTable();
    }

    $('#PERIOD').datepicker({
            "autoclose": true,
            "todayHighlight": true,
            "viewMode": "months",
            "minViewMode": "months",
            "format": "M yyyy"
        });

    $('#PERIOD').on({
        'change': function () {
            MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
            YEAR = this.value.substr(4, 4);
            $('#DtCash_processing').removeAttr("style");
            // $("#DtCash_processing").show();
            $.ajax({
                dataType: "JSON",
                type: 'POST',
                url: "<?php echo site_url('Cash/ShowData'); ?>",
                data: {
                            MONTH: parseInt(MONTH),
                            YEAR: YEAR
                        },
                success: function (response) {
                    // $('#loader').addClass('show');
                    //alert(response.data);
                    if (response.status == 200) {
                        STATUS = true;
                        DtCash = response.result.data;
                        $('#DtCash_processing').hide();
                        table.clear();
                        table.rows.add(DtCash);
                        table.draw();
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        location.reload();
                    } else {
                        alert(response.result.data);
                        files = '';
                        $('.upload-file').val('');
                    }
                    $('#DtCash_processing').hide();
                },
                error: function (e) {
                    console.info(e);
                    $('#DtCash_processing').hide();
                    alert('Error Get Data !!');
                    files = '';
                    $('.upload-file').val('');
                }
            });
            
        }
    });
</script>