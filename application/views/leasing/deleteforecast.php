<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Leasing</a></li>
    <li class="breadcrumb-item active">Delete Forecast</li>
</ol>
<h1 class="page-header">Delete Forecast</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Delete Forecast</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="form-row">
                    <div class="col-4">
                        <label for="DEPARTMENT">Departement</label>
                        <?php
                            $CDepartment = '';
                            foreach ($DtDepartment as $values) {
                                $CDepartment .= '<option value="' . $values->DEPARTMENT . '">' . $values->DEPARTEMENTNAME . '</option>';
                            }
                            ?>
                        <select class="form-control" name="DEPARTMENT" id="DEPARTMENT">
                            <option value="" selected>All Department</option>
                            <?php echo $CDepartment; ?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="CASHFLOWTYPE">Forecast Type</label>
                        <select class="form-control" name="CASHFLOWTYPE" id="CASHFLOWTYPE">
                            <option value="" selected>All</option>
                            <option value="0">Cash In</option>
                            <option value="1">Cash Out</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="PERIOD">Period</label>
                        <input type="text" class="form-control" name="PERIOD" id="PERIOD" placeholder="MMM YYYY" autocomplete="off">
                    </div>
                    <div class="col-2">
                        <label for="DOCNUMBER">Search</label>
                        <input type="text" class="form-control" name="search" id="search" autocomplete="off" required>
                    </div>
                    <!-- <div class="col-4 mt-4">
                        <button type="button" class="btn btn-info" style="padding: 3px 10px;" onclick="searchLeasing()">Search</button>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="row ml-0 mr-0 mb-0 mt-2 table-responsive">
            <table id="DtLeasingDelete" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                <thead>
                    <tr role="row">
                        <th class="text-center align-middle" >No</th>
                        <th class="text-center align-middle" >Company</th>
                        <th class="text-center align-middle" >Business Unit</th>
                        <th class="text-center align-middle" >Department</th>
                        <th class="text-center align-middle" >Doc Type</th>
                        <th class="text-center align-middle" >Doc Number</th>
                        <th class="text-center align-middle" >Vendor</th>
                        <th class="text-center align-middle" >Doc Invoice</th>
                        <th class="text-center align-middle" >Invoice Vendor No</th>
                        <th class="text-center align-middle" >Due Date</th>
                        <th class="text-center align-middle" >Currency</th>
                        <th class="text-center align-middle" >Amount Source</th>
                        <th class="text-center align-middle" >Amount Outstanding</th>
                        <th class="text-center sorting align-middle" >Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<!-- <script src="./assets/js/datetime/bootstrap-datetimepicker.min.js"></script> -->
<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var USERACCESS = "<?php echo $DtUser2->USERACCESS; ?>";
    var YEAR = "",
        MONTH = "";
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
    var table2;

    $(document).ready(function () {
        LoadDataTable();
    });

    $(document).ready(function(e){
        $('button.dt-button').addClass('btn');
        $('button.dt-button').addClass('btn-primary');
    });

    function LoadDataTable() {
        if (!$.fn.DataTable.isDataTable('#DtLeasingDelete')) {
            $('#DtLeasingDelete').DataTable({
                "processing": true,
                "bProcessing": true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Report Payment'
                    },  
                ],
                "ajax": {
                    "url": "<?php echo site_url('Leasing/showDeleteForecast') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function (d) {
                        d.YEAR = YEAR;
                        d.MONTH = MONTH;
                        d.DEPARTMENT = $("#DEPARTMENT").val();
                        d.CASHFLOWTYPE = $("#CASHFLOWTYPE").val();
                        d.USERNAME = USERNAME;
                        d.USERACCESS = USERACCESS;
                        // d.DOCNUMBER = $('#DOCNUMBER').val();
                        // d.DOCDATE =  moment($('#DOCDATE').val()).format('MM-DD-YYYY');
                        // d.COMPANY   = $('#COMPANY').val();
                    },
                    "dataSrc": function (ext) {
                        
                        if (ext.status == 200) {
                            $("#loader").hide();
                            return ext.result.data;
                        } else if (ext.status == 504) {
                            alert(ext.result.data);
                            location.reload();
                            return [];
                        } else {
                            // console.info(ext.result.data);
                            return [];
                        }
                    }
                },
                "columns": [
                    {
                        "data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": "COMPANYCODE"
                    },
                    {
                        "data": "BUSINESSUNITCODE"
                    },
                    {
                        "data": "DEPARTMENT"
                    },
                    {
                        "data": "DOCTYPE"
                    },
                    {
                        "data": "DOCREF"
                    },
                    {
                        "data": "VENDORNAME"
                    },
                    {
                        "data": "DOCNUMBER"
                    },
                    {
                        "data": "INVOICEVENDORNO"
                    },
                    {
                        "data": "DUEDATE"
                    },
                    {
                        "data": "CURRENCY"
                    },
                    {
                        "data": "AMOUNT_INCLUDE_VAT",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNTOUTSTANDING",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            var html = '';
                            html += '<button class="btn btn-danger btn-sm delTrans" data-id="'+data.ID+'"   data-docnumber="'+data.DOCREF+'"  id="delTrans" title="Pay">Delete</button>';
                            return html;
                        }
                    }
                ],
            });

            $('#DtLeasingDelete_filter').remove()

            $('#search').on( 'input', function () {
                table2.search( this.value ).draw();
            });

            $('#DtLeasingDelete thead th').addClass('text-center');
            table2 = $('#DtLeasingDelete').DataTable();
            // $("#DtLeasingDelete_filter").remove();
            // $("#DOCNUMBER").on({
            //     'keyup': function () {
            //         table2.search(this.value, true, false, true).draw();
            //     }
            // });

        } else {
            table2.ajax.reload();
        }
    }

    var searchLeasing = function () {
        // if ($('#DOCDATE').val() == '' || $('#DOCNUMBER').val() == '' || $('#DOCDATE').val() == null || $('#DOCNUMBER').val() == null) {
        //     alert("'cannot be empty!")
        //     return false
        // } else {
            
        // }
        LoadDataTable();
    }

    $('#COMPANY').on({
        'change': function () {
            // MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
            // YEAR = this.value.substr(4, 4);
            COMPANY = $(this).val();
            LoadDataTable();
        }
    });

    $('#PERIOD').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "viewMode": "months",
        "minViewMode": "months",
        "format": "M yyyy"
    });

    //    Change Data
    $('#PERIOD').on({
        'change': function() {
            MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
            YEAR = this.value.substr(4, 4);
            LoadDataTable();
        }
    });

    $('#DEPARTMENT').on({
        'change': function() {
            $('#PERIOD').change();
        }
    });
    $('#CASHFLOWTYPE').on({
        'change': function() {
            $('#PERIOD').change();
        }
    });

    $('body').on('click','#delTrans',function(){
        var GID        = $(this).attr('data-id');
        var DOCNUMBER = $(this).attr('data-docnumber');

        if (confirm('Are you sure?')) {
            $("#loader").show();
            // table2.ajax.reload();
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Leasing/DeleteForecastTransaction'); ?>",
                data: {
                    GID: GID,
                    DOCNUMBER: DOCNUMBER,
                    // DOCDATE: moment($('#DOCDATE').val()).format('MM-DD-YYYY'),
                    USERNAME: USERNAME
                },
                success: function (response) {
                    $("#loader").hide();
                    if (response.status == 200) {
                        alert(response.result.data.MESSAGE);
                        // $('.PAY').attr('disabled',true);
                        LoadDataTable();
                    } else if (response.status == 504) {
                        alert(response.result.data.MESSAGE);
                        LoadDataTable();
                    } else {
                        alert(response.result.data.MESSAGE);
                    }
                },
                error: function (e) {
                    $("#loader").hide();
                    // console.info(e);
                    alert('Data Save Failed !!');
                    LoadDataTable();
                    $('#btnSave').removeAttr('disabled');
                }
            });
        }
    });

    function fCurrency(n) {
        if (n.indexOf(".") >= 0) {
            var decimal_pos = n.indexOf(".");
            var left_side = n.substring(0, decimal_pos);
            var right_side = n.substring(decimal_pos);
            left_side = formatNumber(left_side);
            right_side = formatNumber(right_side);
            right_side += "00";
            right_side = right_side.substring(0, 2);
            n = left_side + "." + right_side;
            return n;
        } else {
            n = formatNumber(n);
            n += ".00";
            return n;
        }
    }

    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

</script>