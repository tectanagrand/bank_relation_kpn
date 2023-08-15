<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
		<li class="breadcrumb-item active">Dashboard Production</li>
	</ol>
<!-- end breadcrumb -->
<h1 class="page-header">Estate Production<small> header small text goes here...</small></h1>
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
				<div id="interactive-chart" class="height-sm"></div>
			</div>
		</div>

	<!-- ================== BEGIN PAGE LEVEL JS ================== -->	
	<script src="<?php echo base_url()?>/assets/js/demo/dashboard.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->	
	
	<script>
		$("#company").change(function(){
			if ($(this).val() != ''){
				$('#businessunit').empty();
				/*dropdown post *///  
				$.ajax({
					url : "<?php echo site_url('dashboardproduction/ajax_businessunit/')?>/" +  $(this).val(),
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
					url : "<?php echo site_url('dashboardproduction/ajax_division/')?>/" +  $(this).val(),
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
		
		$("#division").change(function(){
			if ($(this).val() != ''){
				$('#field').empty();
				/*dropdown post *///
				$('#field').append("<option value='ALL'> -- All -- </option>");				
				$.ajax({
					url : "<?php echo site_url('dashboardproduction/ajax_field/')?>/" +  $('.businessunit').val() + "/"+ $(this).val(),
					type: "POST",
					dataType: "JSON",
					success: function(field) {
						$.each(field, function(i, field) {
							$('#field').append("<option value='" + field.FCCODE + "'>" + field.FCCODE + "</option>");
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
			Dashboard.init();
			$.ajax({
				  url : "<?php echo site_url().'/dashboardproduction/ajax_company';?>",
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
		});
	</script>