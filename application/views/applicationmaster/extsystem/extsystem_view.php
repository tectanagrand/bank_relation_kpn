<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item"><a href="javascript:;">Tables</a></li>
    <li class="breadcrumb-item active">External System</li>
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
<h5 class="page-header">
<!--<small><span class="label label-success">Business Unit : AJP</span></small>
<small><span class="label label-success">PT. Alam Jaya Persada</span></small>-->
    <small><span class="label label-success">Accounting Period : 02-2020</span></small>
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
        <h4 class="panel-title">External System</h4>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <?php
        if (empty($_GET)) {
            ?>
            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>External System Code</th>
                        <th>External System Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th style="width:125px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <?php
        } else {
            ?>
            <form action="#" id="form_edit" data-parsley-validate="true">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group m-b-2">
                                <label class="col-form-label col-md-4">Ext. System Code * :</label>
                                <div class="col-md-8">
                                    <input name="fccode" id="fccode" class="form-control" type="text" data-parsley-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group m-b-2">
                                <label class="col-form-label col-md-4">Ext. System Name :</label>
                                <div class="col-md-8">
                                    <input name="fcname" id="fcname" class="form-control" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group m-b-2">
                                <label class="col-form-label col-md-4">Description :</label>
                                <div class="col-md-8">
                                    <textarea name="description" id="description" class="form-control" type="text"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group m-b-2">
                                <label class="col-form-label col-md-4">Activation :</label>
                                <div class="col-md-8">
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="isactive" name="isactive" />
                                        <label for="isactive"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-horizonal">
                                <div class="row form-group m-b-10">
                                    <button type="submit" id="btnSave" data-click="swal-primary" class="btn btn-info btn-xs m-l-5 btn-sm">Save</button>
                                    <button type="button" id="btnBack" data-click="swal-primary" class="btn btn-white btn-xs m-l-5 btn-sm">Back</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>			
            </form>
            <?php
        }
        ?>
    </div>
</div>