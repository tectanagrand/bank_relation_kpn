<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
		<li class="breadcrumb-item active">Dashboard Vehicle</li>
	</ol>
<!-- end breadcrumb -->
<h1 class="page-header">Vehicle<small></small></h1>
		<div class="panel panel-success">
			<!-- begin panel-heading -->
			<div class="panel-heading">
				<div class="panel-heading-btn">
					<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
				</div>
				<h4 class="panel-title">Chart</h4>
			</div>
			<div class="panel-body">
				<div class="note note-secondary m-b-15">
					<div class="btn-group">
						<select class="form-control tahun" data-size="10" id ='tahun' data-plugin='select2' name ='tahun' data-live-search="true" data-style="btn-primary">
						</select>
					</div>
					<div class="btn-group">
						<select class="form-control bulan" data-size="10" id ='bulan' data-plugin='select2' name ='bulan' data-live-search="true" data-style="btn-primary">
							<option value="01">Jan</option>
							<option value="02">Feb</option>
							<option value="03">Mar</option>
							<option value="04">Apr</option>
							<option value="05">May</option>
							<option value="06">Jun</option>
							<option value="07">Jul</option>
							<option value="08">Aug</option>
							<option value="09">Sep</option>
							<option value="10">Oct</option>
							<option value="11">Nov</option>
							<option value="12">Dec</option>
						</select>
					</div>
					<div class="btn-group">
						<select class="form-control company" data-size="10" id ='company' data-plugin='select2' name ='company' data-live-search="true" data-style="btn-primary">
							<option value="" selected>Select a Company</option>
						</select>
					</div>
					<div class="btn-group">
						<select class="form-control businessunit" data-size="10" id ='businessunit' name ='businessunit' data-live-search="true" data-style="btn-info">
							<option value="" selected>Select a Business Unit</option>
						</select>
					</div>
					<div class="btn-group">
						<select class="form-control division" data-size="10" id ='division' name ='division' data-live-search="true" data-style="btn-success">
							<option value="" selected>Select a Division</option>
						</select>
					</div>
					<div class="btn-group">
						<button type="button" id="btnSave" onclick="save()" class="btn btn-success btn-sm">Submit</button>
					</div>
				</div>
				<div class="row row-space-30">
					<div class="col-lg-8">
						<div id="nv-pie-chart" class="height-sm"></div>
					</div>
					<div class="col-lg-4">
						<div class="table-responsive">
								<table class="table table-valign-middle">
									<thead>
										<tr>	
											<th>Vehicle Name</th>
											<th>Percentage</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><label class="label label-danger">Contractor</label></td>
											<td><label id="lblKTR"></label></td>
										</tr>
										<tr>
											<td><label class="label label-yellow" style="color:white">Dump Truck</label></td>
											<td><label id="lblDT"></label></td>
										</tr>
										<tr>
											<td><label class="label label-green">Triton</label></td>
											<td><label id="lblTN"></label></td>
										</tr>
									</tbody>
								</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	<!-- ================== END PAGE LEVEL JS ================== -->	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.2/d3.min.js"></script>
    <script src="<?php echo base_url()?>/assets/plugins/nvd3/build/nv.d3.js"></script>
	<script src="<?php echo base_url()?>/assets/js/demo/chart-d3.demo.min.js"></script>
	<script src="<?php echo base_url()?>/assets/js/dashboard/vehicle.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->	
	
	<script>
		$("#company").change(function(){
			if ($(this).val() != ''){
				$('#businessunit').empty();
				/*dropdown post *///  
				$.ajax({
					url : "<?php echo site_url('dashboardvehicle/ajax_businessunit/')?>/" +  $(this).val(),
					type: "POST",
					dataType: "JSON",
					success: function(businessunit) {
						$.each(businessunit, function(i, businessunit) {
							$('#businessunit').append("<option value='" + businessunit.FCCODE + "'>" + businessunit.FCCODE + "</option>");
						});
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
					alert('Error get data from ajax');
					}
				});
			}
		});
		
		$("#businessunit").change(function(){
			if ($(this).val() != ''){
				$('#division').empty();
				/*dropdown post *///  
				$('#division').append("<option value='ALL'> -- All -- </option>");
				$.ajax({
					url : "<?php echo site_url('dashboardvehicle/ajax_division/')?>/" +  $(this).val(),
					type: "POST",
					dataType: "JSON",
					success: function(division) {
						$.each(division, function(i, division) {
							$('#division').append("<option value='" + division.DIVISION + "'>" + division.DIVISION + "</option>");
						});
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
					alert('Error get data from ajax');
					}
				});
			}
		});
		
		$(document).ready(function() {
			App.init();
			ChartNvd3.init();
			$.ajax({
				  url : "<?php echo site_url().'/dashboardvehicle/ajax_company';?>",
				  type: "POST",
				  dataType: "JSON",
				  success: function(company) {
					$('#businessunit').empty();
					$('#division').empty();
					$('#field').empty();
					$.each(company, function(i, company) {
						 $('#company').append("<option value='" + company.COMPANYCODE + "'>" + company.COMPANYCODE + "</option>");
					});						
				  },
				  error: function (jqXHR, textStatus, errorThrown)
				  {
					alert('Error get data from ajax');
				  }
			});
			
			$.ajax({
				  url : "<?php echo site_url().'/dashboardvehicle/ajax_year';?>",
				  type: "POST",
				  dataType: "JSON",
				  success: function(tahun) {
					$.each(tahun, function(i, tahun) {
						 $('#tahun').append("<option value='" + tahun.YRS + "'>" + tahun.YRS + "</option>");
					});						
				  },
				  error: function (jqXHR, textStatus, errorThrown)
				  {
					alert('Error get data from ajax');
				  }
			});
		});
	</script>