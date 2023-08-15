<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Leasing</a></li>
    <li class="breadcrumb-item active">Delete Leasing Transaction</li>
</ol>
<h1 class="page-header">Delete Leasing Transaction</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Delete Leasing Transaction</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="form-row">
                    <div class="col-4">
                        <label for="COMPANY">Company</label>
                        <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                            <option value="0">Select Company</option>
                            <?php
                            foreach ($DtCompany as $values) {
                                echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <!-- <div class="col-2">
                        <label for="COMPANY">Tipe</label>
                        <select class="form-control mkreadonly" name="TIPE" id="TIPE">
                            <option value="1">Delete with forecast</option>
                            <option value="2">Delete without forecast</option>
                        </select>
                    </div> -->
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
                        <th class="text-center sorting align-middle" >Company</th>
                        <th class="text-center sorting align-middle" >Doc Number</th>
                        <th class="text-center sorting align-middle" >Item Name</th>
                        <th class="text-center sorting align-middle" >Period Year</th>
                        <th class="text-center sorting align-middle" >Period Month</th>
                        <th class="text-center sorting align-middle" >Transaction No</th>
                        <th class="text-center sorting align-middle" >Transaction Method</th>
                        <th class="text-center sorting align-middle" >Basic Amount Monthly</th>
                        <th class="text-center sorting align-middle" >Interest Amount Monthly</th>
                        <th class="text-center sorting align-middle" >Amount Monthly Leasing</th>
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
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Report Payment'
                    },  
                ],
                "ajax": {
                    "url": "<?php echo site_url('Leasing/showDeleteTransaction') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function (d) {
                        // d.DOCNUMBER = $('#DOCNUMBER').val();
                        // d.DOCDATE =  moment($('#DOCDATE').val()).format('MM-DD-YYYY');
                        d.COMPANY   = $('#COMPANY').val();
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
                    {"data": "COMPANYNAME"},
                    {"data": "DOCNUMBER"},
                    {"data": "FCNAME"},
                    {"data": "PERIOD_YEAR"},
                    {"data": "PERIOD_MONTH"},
                    {"data": "TRANSAKSI_KE"},
                    {"data": "TRANSACTIONMETHOD_BY"},
                    // {
                    //     "data": "AMOUNT_AFTER_CONV",
                    //     "className": "text-right",
                    //     "orderable": false,
                    //     render: function (data, type, row, meta) {
                    //     var html = fCurrency(data);
                    //         return html;
                    //     }
                    // },
                    {
                        "data":"BASIC_AMOUNT_MONTHLY",
                        "className": "text-right",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                        var html = fCurrency(data);
                            return html;
                        }
                        
                    },
                    {
                        "data":"INTEREST_AMOUNT_MONTHLY",
                        "className": "text-right",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                        var html = fCurrency(data);
                            return html;
                        }
                    },
                    {
                        "data":"AMOUNT_MONTHLY_LEASING",
                        "className": "text-right",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                        var html = fCurrency(data);
                            return html;
                        }
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            var html = '';
                            html += '<button class="btn btn-danger btn-sm delTrans" data-id="'+data.GID+'" data-year="'+data.PERIOD_YEAR+'" data-month="'+data.PERIOD_MONTH+'"  data-docnumber="'+data.DOCNUMBER+'" data-lineno="'+data.LINENO+'"  id="delTrans" title="Pay">Delete</button>';
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

    $('body').on('click','#delTrans',function(){
        var GID        = $(this).attr('data-id');
        var COMPANY   = $('#COMPANY').val(); //$(this).attr('data-company');
        var DOCNUMBER = $(this).attr('data-docnumber');
        var LINENO    = $(this).attr('data-lineno');
        var YEAR    = $(this).attr('data-year');
        var MONTH    = $(this).attr('data-month');
        if (confirm('Are you sure?')) {
            $("#loader").show();
            // table2.ajax.reload();
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Leasing/DeleteLeasingTransaction'); ?>",
                data: {
                    GID: GID,
                    COMPANY: COMPANY,
                    DOCNUMBER: DOCNUMBER,
                    // DOCDATE: moment($('#DOCDATE').val()).format('MM-DD-YYYY'),
                    LINENO: LINENO,
                    MONTH:MONTH,
                    // TIPE: $('#TIPE').val(),
                    YEAR:YEAR,
                    USERNAME: USERNAME
                },
                success: function (response) {
                    $("#loader").hide();
                    if (response.status == 200) {
                        toastr.success(response.result.data.MESSAGE);
                        // $('.PAY').attr('disabled',true);
                        LoadDataTable();
                    } else if (response.status == 504) {
                        toastr.error(response.result.data.MESSAGE);
                        LoadDataTable();
                    } else {
                        toastr.error(response.result.data.MESSAGE);
                    }
                    $("#loader").hide();
                },
                error: function (e) {
                    $("#loader").hide();
                    // console.info(e);
                    toastr.error('Delete Failed');
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