<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item"><a href="javascript:;">Tables</a></li>
    <li class="breadcrumb-item active">Bank Master</li>
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
<h5 class="page-header">
<small><span class="label label-success">Business Unit : AJP</span></small>
<small><span class="label label-success">PT. Alam Jaya Persada</span></small>
<small><span class="label label-success">Accounting Period : 02-2019</span></small>
</h5>

<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
        </div>
			<h4 class="panel-title">Bank Master</h4>
    </div>
</div>
<div class="panel panel-default">
	<div class="panel-body">
		<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>Bank Code</th>
					<th>Bank Name</th>
					<th>Control Job</th>
					<th>Account Code</th>
					<th>Address</th>
					<th>Status</th>
					<th style="width:125px;">Action</th>
				</tr>
			</thead>
			<tbody>			
			</tbody>
		</table>
	</div>
	<div class="modal" id="modal-edit">
		<div class="modal-dialog"  style="max-width:800px;">
			<div class="modal-content" >
				<div class="modal-header">
					<strong>Add New Bank</strong>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="#" id="form_edit" data-parsley-validate="true">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6">
								<div class="row form-group m-b-2">
									<label class="col-form-label col-md-4">Bank Code * :</label>
									<div class="col-md-8">
										<input name="overtime" id="overtime" class="form-control" type="text" data-parsley-required="true">
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="row form-group m-b-2">
									<label class="col-form-label col-md-4">City :</label>
									<div class="col-md-8">
										<input name="overtime" id="overtime" class="form-control" type="text">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="row form-group m-b-2">
									<label class="col-form-label col-md-4">Description * :</label>
									<div class="col-md-8">
										<textarea  name="description" id="description" class="form-control" type="text" data-parsley-required="true"></textarea>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="row form-group m-b-2">
									<label class="col-form-label col-md-4">Address :</label>
									<div class="col-md-8">
										<textarea  name="reason" id="reason" class="form-control" type="text" ></textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="row form-group m-b-2">
									<label class="col-form-label col-md-4">Control Job * :</label>
									<div class="col-md-8">
										<select class="form-control selectpicker controljob" data-size="10" id ='controljob' name ='controljob' data-parsley-required="true" data-live-search="true" data-style="btn-white">
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="row form-group m-b-2">
									<label class="col-form-label col-md-4">State :</label>
									<div class="col-md-8">
										<input name="overtime" id="overtime" class="form-control" type="text">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="row form-group m-b-2">
									<label class="col-form-label col-md-4">Account Code :</label>
									<div class="col-md-8">
										<input name="overtime" id="overtime" class="form-control" type="text">
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="row form-group m-b-2">
									<label class="col-form-label col-md-4">Date Created :</label>
									<div class="col-md-8">
										<input type="text" class="form-control" name="datecreated" id="datecreated" value="" />
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="row form-group m-b-2">
									<label class="col-form-label col-md-4">Remarks :</label>
									<div class="col-md-8">
										<textarea  name="reason" id="reason" class="form-control" type="text" ></textarea>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="row form-group m-b-2">
									<label class="col-form-label col-md-4">Activation :</label>
									<div class="col-md-8">
										<div class="checkbox checkbox-css">
										  <input type="checkbox" id="cssCheckbox1" checked />
										  <label for="cssCheckbox1"></label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-info btn-sm">Save</button>
						<a href="javascript:;" class="btn btn-white btn-sm" data-dismiss="modal">Close</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>