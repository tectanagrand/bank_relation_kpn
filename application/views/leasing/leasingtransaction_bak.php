<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Leasing</a></li>
    <li class="breadcrumb-item active">Leasing Transaction</li>
</ol>
<h1 class="page-header">Leasing Transaction</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Leasing Transaction</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="form-row">
                    <div class="col my-auto">
                        <!-- <button type="button" class="btn btn-info" id="exportData">Export</button>  -->
                    </div>
                    <div class="col">
                        <label for="COMPANY">Company</label>
                        <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                            <option value="">All Company</option>
                            <?php
                            foreach ($DtCompany as $values) {
                                echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="PERIOD">Period</label>
                        <input type="text" class="form-control" name="PERIOD" id="PERIOD" autocomplete="off" disabled>
                    </div>
                    <div class="col">
                        <label for="PERIOD">Interest Percentage</label>
                        <input type="text" class="form-control" name="INTEREST_PERCENTAGE" id="INTEREST_PERCENTAGE" autocomplete="off" required>
                    </div>
                    <div class="col">
                        <label for="PERIOD">Due Date</label>
                        <input type="date" class="form-control" name="DUEDATE" id="DUEDATE" required>
                    </div>
                    <div class="col">
                        <label for="searchInvoice">Search</label>
                        <input type="text" id="searchInvoice" name="searchInvoice" class="form-control" placeholder="Cari.." >
                    </div>
                </div>
            </div>
        </div>
        <div class="row ml-0 mr-0 mb-0 mt-2 table-responsive">
            <table id="DtLeasingTransaction" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                <thead>
                    <tr role="row">
                        <th class="text-center sorting_asc align-middle" aria-sort="ascending" style="width: 30px;">No</th>
                        <th class="text-center sorting align-middle" >Company</th>
                        <th class="text-center sorting align-middle" >Doc Number</th>
                        <th class="text-center sorting align-middle" >Item Name</th>
                        <th class="text-center sorting align-middle" >Amount Monthly</th>
                        <th class="text-center sorting align-middle" >Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    // var table2;
    var YEAR = '', MONTH = '', DEPARTMENT = '', FORECASTID;
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }

    var table2;

    //Load Data Table Awal
    function LoadDataTable() {
        if (!$.fn.DataTable.isDataTable('#DtLeasingTransaction')) {
            $('#DtLeasingTransaction').DataTable({
                "processing": true,
                "processData":false,
                "ajax": {
                    "url": "<?php echo site_url('Leasing/ShowDataTransaction') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function (d) {
                        d.COMPANY  = COMPANY;
                    },
                    "dataSrc": function (ext) {
                        if(ext.result.size == 0){
                            alert('data kosong');
                            return [];
                        }
                        else if (ext.status == 200) {
                            var m = ext.result.data[0].CURRENTACCOUNTINGPERIOD;
                            // var y = ext.result.data.CURRENTACCCOUNTINGYEAR;
                            $('#PERIOD').val(m);
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
                "columns": [
                    {
                        "data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {"data": "COMPANYNAME"},
                    {"data": "DOCNUMBER"},
                    {"data": "FCNAME"},
                    {
                        "data": "AMOUNT_PER_MONTH",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            // console.log(data);
                            // console.log(row);
                            // console.log(meta);
                            var html = '';
                            html += '<button class="btn btn-info btn-sm PAY" data-id="'+data.UUID+'" data-company="'+data.COMPANY+'" data-pyear="'+data.PERIOD_YEAR+'" data-pmonth="'+data.PERIOD_MONTH+'" data-docnumber="'+data.DOCNUMBER+'"  id="PAY" title="Pay">Pay</button><br><button id="test">test</button>';
                            return html;
                        }
                    }
                ],
                deferRender: true,
                scrollY: 400,
                scrollX: true,
                scrollCollapse: true,
                scroller: true,
                "bFilter": true,
                "bPaginate": false,
                "bLengthChange": false,
                "bInfo": true
            });
            table2 = $('#DtLeasingTransaction').DataTable();
            $('#DtLeasingTransaction thead th').addClass('text-center');
            //Hapus Searching Default Datatable
            $('#DtLeasingTransaction_filter').remove();
            // $('#searchInvoice').on('input', function () {
            //     table2.search(this.value).draw();
            // });
            table2.on('click', '#test', function() {
              var row = $(this).closest('tr');
              var data = table2.row(row).data();

              console.log(data);

            });
        }else{
            table2.ajax.reload();
        }
    }

    $('body').on('click','#PAY',function(){
        var ID = $(this).attr('data-id');
        var COMPANY = $(this).attr('data-company');
        var DOCNUMBER = $(this).attr('data-docnumber');
        var pYEAR = $(this).attr('data-pyear');
        var pMONTH = $(this).attr('data-pmonth');
        
        $("#loader").show();
            // table2.ajax.reload();
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Leasing/saveLeasingTransaction'); ?>",
                data: {
                    UUID: ID,
                    COMPANY: COMPANY,
                    DOCNUMBER: DOCNUMBER,
                    DUEDATE: $('#DUEDATE').val(),
                    INTEREST_PERCENTAGE: $('#INTEREST_PERCENTAGE').val(),
                    YEAR: pYEAR,
                    MONTH: pMONTH,
                    USERNAME: USERNAME
                },
                success: function (response) {
                    $("#loader").hide();
                    if (response.status == 200) {
                        alert(response.result.data);
                        // $('.PAY').attr('disabled',true);
                        window.location.reload();
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        location.reload();
                    } else {
                        alert(response.result.data);
                    }
                },
                error: function (e) {
                    $("#loader").hide();
                    // console.info(e);
                    alert('Data Save Failed !!');
                    $('#btnSave').removeAttr('disabled');
                }
            });
    });

    //Load Period
    // $('#PERIOD').datepicker({
    //     "autoclose": true,
    //     "todayHighlight": true,
    //     "viewMode": "months",
    //     "minViewMode": "months",
    //     "format": "M yyyy",
    //     "startDate": '-0m',
    // });
    //Change Period
    $('#COMPANY').on({
        'change': function () {
            // MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
            // YEAR = this.value.substr(4, 4);
            COMPANY = $(this).val();
            LoadDataTable();
        }
    });

    //Change Department
    // $('#DEPARTMENT').on({
    //     'change': function () {
    //         DEPARTMENT = this.value;
    //         console.info(DEPARTMENT);
    //         table2.ajax.reload();
    //     }
    // });

    //Format Currency Manual
    function formatNumber(n) {
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
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

</script>