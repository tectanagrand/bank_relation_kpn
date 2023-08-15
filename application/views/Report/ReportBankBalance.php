<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link href="./assets/css/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet">

<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Bank Balance</li>
</ol>
<h1 class="page-header">Bank Balance</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Bank Balance</h4>
    </div>
    <div class="panel-body">
        <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
            <div class="row">
                <div class="form-group col-md-3">
                    <!-- <label for="COMPANYGROUP">Company Group *</label> -->
                    <select class="form-control mkreadonly" name="COMPANYGROUP" id="COMPANYGROUP">
                        <option value="">All Company Group</option>
                        <?php
                        foreach ($Dtcompanygroup as $values) {
                            echo '<option value=' . $values->FCCODE . '>' . $values->FCNAME . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <!-- <label for="COMPANYSUBGROUP">Company Sub Group *</label> -->
                    <select class="form-control mkreadonly" name="COMPANYSUBGROUP" id="COMPANYSUBGROUP">
                        <option value="">All Company Sub Group</option>
                        <?php
                        foreach ($Dtcompanysubgroup as $values) {
                            echo '<option value=' . $values->FCCODE . '>' . $values->FCNAME . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <!-- <label for="COMPANY">Company *</label> -->
                    <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                        <option value="">All Company</option>
                        <?php
                        foreach ($DtCompany as $values) {
                            echo '<option value=' . $values->ID . '>' . $values->COMPANYNAME . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <!-- <label for="COMPANY">Bank</label> -->
                    <select class="form-control mkreadonly" name="BANKCODE" id="BANKCODE" required>
                        <option value="" selected>All Bank</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <!-- <label for="PERIOD">Period *</label> -->
                    <input type="text" class="form-control" name="PERIOD" id="PERIOD" placeholder="Period" autocomplete="off">
                </div>
                <div class="form-group col-md-1">
                    <button type="button" class="btn btn-info" style="padding: 3px 10px;" onclick="LoadDataTable()">Search</button>
                </div>
                <div class="form-group col-md-12">
                    <hr style="background:#e2e7eb!important;margin:.3rem 0!important;">
                </div>
                <div class="form-group col-md-3 offset-md-9">
                    <!-- <label>Cari</label> -->
                    <input type="text" id="searchInvoice" class="form-control" placeholder="Cari..">
                </div>
            </div>
            <div class="row ml-0 mr-0 mb-0 table-responsive">
                <table id="DtInvoice" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc align-middle" aria-sort="ascending" style="width: 30px;">No</th>
                            <!-- <th class="text-center sorting align-middle">Bank Code</th> -->
                            <th class="text-center sorting align-middle">Bank Name</th>
                            <th class="text-center sorting align-middle">Bank Account</th>
                            <th class="text-center sorting align-middle">Currency</th>
                            <th class="text-center sorting align-middle">Opening Balance</th>
                            <th class="text-center sorting align-middle">Cash In</th>
                            <th class="text-center sorting align-middle">Cash Out</th>
                            <th class="text-center sorting align-middle">Ending Balance</th>
                            <th class="text-center sorting align-middle">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </form>
    </div>
</div>

<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<script src="./assets/js/datetime/bootstrap-datetimepicker.min.js"></script>

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
    var table2;
    var YEAR = '', MONTH = '', COMPANYGROUP = '', COMPANYSUBGROUP= '', COMPANY = '', BANK = '';

    $(document).ready(function() {

        // LoadDataTable()

        $(document).on('change', '#COMPANYGROUP', function () {            
            COMPANYGROUP = $(this).val();
            // LoadDataTable()
        });

        $(document).on('change', '#COMPANYSUBGROUP', function () {            
            COMPANYSUBGROUP = $(this).val();
            // LoadDataTable()
        });

        $('#PERIOD').datepicker({
            "autoclose": true,
            "todayHighlight": true,
            "viewMode": "months",
            "minViewMode": "months",
            "format": "M yyyy"
        });

        $('#PERIOD').on({
            'change': function () {
                let dataYear = '';

                if (navigator.userAgent.indexOf("Chrome") !== -1){
                    dataYear = moment($(this).val()).format('M-YYYY').split("-", 2)
                } else {
                    /* FOR MOZILA USERS */
                    dataYear = moment('01 '+$(this).val().replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3")).format('M-YYYY').split("-", 2)
                }
                
                YEAR = dataYear[1]
                MONTH = dataYear[0]
            }
        });

        $(document).on('change', '#COMPANY', function() {
            COMPANY = $(this).val()
            // LoadDataTable()
            showBank(COMPANY)
        });

        $(document).on('change', '#BANKCODE', function() {
            BANK = $(this).val()
            // LoadDataTable()
        });

    });

    function showBank(COMPANYBANK) {
        $('.dataBankOptions').remove()
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Payment/DtBankCompany'); ?>",
            data: {
                COMPANY: COMPANYBANK
            },
            success:function(response)
            {
                let data = JSON.parse(response)
                let options = ''
                let selected = '';

                data.result.data.forEach( function(value, key) {
                    selected = (value.ISDEFAULT == 1) ? value.FCCODE : selected;
                    let Default = (value.ISDEFAULT == 1) ? '(Default)' : '';
                    options += '<option class="dataBankOptions" value="'+ value.FCCODE +'" '+selected+'>'+ value.BANKACCOUNT +' - '+ value.FCNAME +' - '+ value.CURRENCY +' '+ Default +'</option>'
                })

                $(options).insertAfter("#BANKCODE option:first");
                
            },
            error: function (e) {
                alert('Please Check Your Connection !!!');
            }
        });
    }

    function LoadDataTable() {
        if (!$.fn.DataTable.isDataTable('#DtInvoice')) {
            $('#DtInvoice').DataTable({
                "processing": true,
                "ajax": {
                    "url": "<?php echo site_url('ReportBankBalance/ShowData') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function(d) {
                        d.YEAR = YEAR;
                        d.MONTH = MONTH;
                        d.COMPANYGROUP = COMPANYGROUP;
                        d.COMPANYSUBGROUP = COMPANYSUBGROUP;
                        d.COMPANY = COMPANY;
                        d.BANKCODE = BANK;
                        d.USERNAME = USERNAME;
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
                    // {
                    //     "data": "BANKCODE"
                    // },
                    {
                        "data": "FCNAME"
                    },
                    {
                        "data": "BANKACCOUNT"
                    },
                    {
                        "data": "CURRENCY"
                    },
                    {
                        "data": "OPENING_BALANCE",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "CASHIN",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "CASHOUT",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "ENDING_BALANCE",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "REMARKS"
                    },
                ],
                "bFilter": true,
                "bPaginate": true,
                "bLengthChange": false,
                "bInfo": true,
                // "bFilter": false,
            });
            $('#DtInvoice_filter').remove()

            $('#searchInvoice').on('input', function() {
                table2.search(this.value).draw();
            });

            $('#DtInvoice thead th').addClass('text-center');
            table2 = $('#DtInvoice').DataTable();
        } else {
            table2.ajax.reload();
        }
    }
</script>