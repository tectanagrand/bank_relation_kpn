<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
		<li class="breadcrumb-item active">Dashboard Kernel</li>
	</ol>
<!-- end breadcrumb -->
<h1 class="page-header">KERNEL<small></small></h1>
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
						<button type="button" id="btnSave" onclick="save()" class="btn btn-success">Submit</button>
					</div>
				</div>				
				<div id="interactive-chart" class="height-sm"></div>
			</div>
		</div>
		
	<!-- ================== END PAGE LEVEL JS ================== -->	
	<script src="<?php echo base_url()?>/assets/js/dashboard/ker.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->	
	
	<script>
		$("#company").change(function(){
			if ($(this).val() != ''){
				$('#businessunit').empty();
				/*dropdown post *///  
				$.ajax({
					url : "<?php echo site_url('dashboardker/ajax_businessunit/')?>/" +  $(this).val(),
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
		

		$(document).ready(function() {
			App.init();
			Dashboard.init();
			$.ajax({
				  url : "<?php echo site_url().'/dashboardker/ajax_company';?>",
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