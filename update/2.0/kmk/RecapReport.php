<link rel="stylesheet" href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css">
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" type="text/css">

<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Recap Report</li>
</ol>

<h1 class="page-header">Recap Report</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript" data-click="panel-expand" class="btn btn-xs btn-icon btn-circle btn-default">
                <i class="fa fa-expand"></i>
            </a>
        </div>
        <h4 class="panel-title">Recap Report</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="COMPANY">Company</label>
                <select name="COMPANY" id="COMPANY" class="form-control selectpicker" required="required" multiple="multiple">
                    <option value="" >--- All Company ---</option>
                    <?php
                        foreach($DtCompany as $value) {
                            echo "<option value='{$value->COMPANYCODE}'>{$value->COMPANYCODE} - {$value->COMPANYNAME}</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="CREDIT_TYPE">Credit Type</label>
                <select name="CREDIT_TYPE" id="CREDIT_TYPE" required="required" class="form-control">
                    <option value="" selected="selected">--- All Credit Type ---</option>
                    <option value="KMK">KMK</option>
                    <option value="KI">KI</option>
                </select>
            </div>
            <div class="col-md-2 pull-left">
                <label for="PERIOD">Period</label>
                <div class="input-group input-daterange">
                    <input type="text" class="form-control" id="PERIODFROM" autocomplete="off">
                        <div class="input-group-addon" style="padding:4px 0 0 0 !important;">-</div>
                        <input type="text" class="form-control" id="PERIODTO"  autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <button id="btnExport" class="btn btn-success btn-sm mt-4" type="button">
                    <i class="fa fa-file-excel"></i><span>Export</span>
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#btnExport').on('click', function(){
            var url = "<?php echo site_url('ReportFacility/ExportRecapReport'); ?>?COMPANY=PARAM1&START=PARAM2&END=PARAM3";
            url = url.replace("PARAM1", $('#COMPANY').val() );
            url = url.replace("PARAM2", $('#PERIODFROM').val());
            url = url.replace("PARAM3", $('#PERIODTO').val() );
            // url = url.replace("PARAM2", DOCNUM);
            window.open(url, '_blank');
        }) ;
        
        $("#COMPANY").on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            var selectedOptions = $(this).val() || []; 
            if (selectedOptions[0] == '') {
                $(this).val(selectedOptions.slice(0, 1)); 
                $(this).selectpicker('refresh'); 
            }
        })
    });
    
    
</script>