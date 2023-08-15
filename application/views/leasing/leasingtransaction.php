<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<!-- <script src="https://cdn.datatables.net/plug-ins/1.10.10/api/sum().js"></script> -->
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
                            <option selected="">Select Company</option>
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
                    <!-- <div class="col">
                        <label for="PERIOD">Interest Percentage</label>
                        <input type="text" class="form-control" name="INTEREST_PERCENTAGE" id="INTEREST_PERCENTAGE" autocomplete="off" required>
                    </div> -->
                    <!-- <div class="col">
                        <label for="PERIOD">Due Date</label>
                        <input type="text" class="form-control" name="DUEDATE" id="DUEDATE">
                    </div> -->
                    <div class="col">
                        <label for="searchInvoice">Search</label>
                        <input type="text" id="searchInvoice" name="searchInvoice" class="form-control" placeholder="Cari.." >
                    </div>
                    
                    <button type="submit" class="btn btn-primary payAll pull-right mt-4" style="height: fit-content;">Forecast All</button>
                
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
                        <th class="text-center sorting align-middle" >Vendor</th>
                        <th class="text-center sorting align-middle" >Transaction Method</th>
                        <th class="text-center sorting align-middle" >Currency</th>
                        <th class="text-center sorting align-middle" >Basic Amount Monthly</th>
                        <th class="text-center sorting align-middle" >Interest Amount Monthly</th>
                        <th class="text-center sorting align-middle" >Amount Monthly Leasing</th>
                        <th class="text-center sorting align-middle" >Basic Amount Monthly Conv</th>
                        <th class="text-center sorting align-middle" >Interest Amount Monthly Conv</th>
                        <th class="text-center sorting align-middle" >Amount Monthly Leasing Conv</th>
                        <th class="text-center sorting align-middle" >Action</th>
                        <th class="text-center"><input type="checkbox" id="pil"></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="text-align:right">Total</th>
                        <th style="text-align:right" id="tbasic"></th>
                        <th style="text-align:right" id="tinterest"></th>
                        <th style="text-align:right" id="tamount"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th></th>
                    </tr>
                </tfoot>
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
    var PayAll = [];
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }

    var table2;

    //Load Data Table Awal
    function LoadDataTable() {
        $('#pil').removeAttr('checked');
        if (!$.fn.DataTable.isDataTable('#DtLeasingTransaction')) {
            $('#DtLeasingTransaction').DataTable({
                "footerCallback": footerCallback,
                "processing": true,
                // "serverSide": true,
                // "processData":true,
                "ajax": {
                    "url": "<?php echo site_url('Leasing/ShowDataTransaction') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function (d) {
                        d.COMPANY  = COMPANY;
                    },
                    "dataSrc": function (ext) {
                        if(ext.result.data == 'Period Tidak Sama'){
                            alert('Period Tidak Sama');
                            return [];
                        }
                        if(ext.result.size == 0){
                            alert('data kosong');
                            return [];
                        }
                        else if (ext.status == 200) {
                            // $("#DUEDATE").datepicker('remove');
                            MONTH = ext.result.data[0].CURRENTACCOUNTINGPERIOD;
                            YEAR = ext.result.data[0].CURRENTACCOUNTINGYEAR;
                            // var gDate = ext.result.data[0].DUEDATE_PER_MONTH;
                            $('#PERIOD').val(MONTH + ' - ' + YEAR);
                            payAll = ext.result.data;
                            // var M = parseInt(MONTH) - 1;
                            // var Y  = parseInt(YEAR);
                            // $('#DUEDATE').datepicker({
                            //     autoclose:true,
                            //     startDate: new Date(Y,M,1)
                            // });
                            // $('#DUEDATE').datepicker('update', new Date(Y, M, gDate));
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
                    {"data": "VENDORNAME"},
                    {"data": "TRANSACTIONMETHOD_BY"},
                    {"data": "CURRENCY"},
                    {
                        "data": "BASIC_AMOUNT_MONTHLY",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "INTEREST_AMOUNT_MONTHLY",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNT_MONTHLY_LEASING",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    // basic amt monthly
                    // {   "data":null,
                    //     "className": "text-right rbasic",
                    //     render: function (data, type, row, meta) {
                    //         // console.log(data);
                    //         var numFormat   = $.fn.dataTable.render.number('\,', '.', 2).display;
                    //         // if(data.MONTHTOBE == '0' || data.MONTHTOBE == 0){
                    //         //     if(data.TRANSACTIONMETHOD_BY == 'FLAT' || data.TRANSACTIONMETHOD_BY == 'EFEKTIF'){
                                    
                    //         //         var res = data.BASIC_AMOUNT / data.TOTAL_MONTH;
                    //         //         var f = numFormat(res);
                    //         //     }
                    //         //     if(data.TRANSACTIONMETHOD_BY == 'ANUITAS'){
                    //         //         var amt_month_leas = (data.BASIC_AMOUNT * ((data.INTEREST_PERCENTAGE / 100 ) / 12) / (1-Math.pow(1/(1+((data.INTEREST_PERCENTAGE / 100) / 12)),data.TOTAL_MONTH)));

                    //         //         if(data.MONTHTOBE == '0' || data.MONTHTOBE == 0){
                    //         //             var int_amt_monthly = (data.BASIC_AMOUNT*(data.INTEREST_PERCENTAGE/100)/12);    
                    //         //         }else{
                    //         //             var int_amt_monthly = data.REMAIN_BASIC_AMOUNT_LEASING*((data.INTEREST_PERCENTAGE/100) / 12);
                    //         //         }
                    //         //         var res = (amt_month_leas - int_amt_monthly);
                    //         //         var f = numFormat(res);
                    //         //     }
                    //         // }
                    //         // else{
                    //             var res = data.BASIC_AMOUNT_MONTHLY;
                    //             var f = numFormat(res);
                    //         // }
                            
                    //         return f;
                            
                    //     }
                    // },
                    // interest amt monthly
                    // {   "data":null,
                    //     "className": "text-right rinterest",
                    //     render: function (data, type, row, meta) {
                    //         // console.log(data);
                    //         var numFormat   = $.fn.dataTable.render.number('\,', '.', 2).display;
                            
                    //         // if(data.MONTHTOBE == '0' || data.MONTHTOBE == 0){
                    //         // 	if(data.TRANSACTIONMETHOD_BY == 'EFEKTIF'){
                    //         //     	var res = (data.BASIC_AMOUNT*(data.INTEREST_PERCENTAGE/100)/12);
                    //         //     	var f = numFormat(res);
                    //         // 	}
                    //         // 	if(data.TRANSACTIONMETHOD_BY == 'ANUITAS'){
                    //         //     	var res = (data.BASIC_AMOUNT*(data.INTEREST_PERCENTAGE/100)/12);
                    //         //     	var f = numFormat(res);
                    //         // 	}
                    //         // 	if(data.TRANSACTIONMETHOD_BY == 'FLAT'){
	                   //         //      var res = data.INTEREST_AMOUNT / data.TOTAL_MONTH;
	                   //         //      var f = numFormat(res);
	                   //         //  }
                    //         // }
                    //         // else{
                    //         //     if(data.TRANSACTIONMETHOD_BY == 'EFEKTIF'){
                    //         //     	// var basic_amt_monthly    = data.BASIC_AMOUNT / data.TOTAL_MONTH;
                    //         //     	// var res = data.REMAIN_BASIC_AMOUNT_LEASING * (data.INTEREST_PERCENTAGE / 100) / 12;
                    //         //         var res = data.INTEREST_AMOUNT_MONTHLY;
                    //         //     	var f = numFormat(res);
                    //         // 	}
                    //         // 	if(data.TRANSACTIONMETHOD_BY == 'ANUITAS'){
                    //         //     	// var res = data.REMAIN_BASIC_AMOUNT_LEASING * (data.INTEREST_PERCENTAGE / 100) / 12;
                    //         //         var res = data.INTEREST_AMOUNT_MONTHLY;
                    //         //     	var f = numFormat(res);
                    //         // 	}
                    //         //     if(data.TRANSACTIONMETHOD_BY == 'FLAT'){
                    //                 // var res = data.INTEREST_AMOUNT / data.TOTAL_MONTH;
                    //                 var res = data.INTEREST_AMOUNT_MONTHLY;
                    //                 var f = numFormat(res);
                    //         //     }
                    //         // }
                            
                    //         return f;
                            
                    //     }
                    // },
                    //total amt
                    // {
                    //     "data": null,
                    //     "className": "text-right ramount",
                    //     render: function (data, type, row, meta) {
                    //         // console.log(data);
                    //         var numFormat   = $.fn.dataTable.render.number('\,', '.', 2).display;
                    //         // if(data.MONTHTOBE == '0' || data.MONTHTOBE == 0){
	                   //         //  if(data.TRANSACTIONMETHOD_BY == 'FLAT' || data.TRANSACTIONMETHOD_BY == 'EFEKTIF'){
	                                
	                   //         //      var res = data.AMOUNT_BEFORE_CONV / data.TOTAL_MONTH; // = basic_amt_mnthly
	                   //         //      // var f = numFormat(res);
                    //         //         var f = numFormat(res);
	                   //         //  }
                    //         //     // else if(data.TRANSACTIONMETHOD_BY == 'EFEKTIF'){
                    //         //     //     var res = data.BASIC_AMOUNT / data.TOTAL_MONTH; // = basic_amt_mnthly
                    //         //     //     var f = numFormat(res);
                    //         //     // }
                    //         //     else{
	                   //         //  	var res = (data.BASIC_AMOUNT * ((data.INTEREST_PERCENTAGE / 100 ) / 12) / (1-Math.pow(1/(1+((data.INTEREST_PERCENTAGE / 100) / 12)),data.TOTAL_MONTH)));
                    //         //     	// var f = numFormat(res);
                    //         //         var f = numFormat(res);
	                   //         //  }
                    //         // }
                    //         // else{
                    //             var res = data.AMOUNT_MONTHLY_LEASING;
                    //             // var f = numFormat(res);
                    //             var f = numFormat(res);
                                
                    //         // }
                            
                    //         return f;
                            
                    //     }
                    // },
                    {
                        "data": "BASIC_AMOUNT_MONTHLY_CONV",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "INTEREST_AMOUNT_MONTHLY_CONV",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNT_MONTHLY_LEASING_CONV",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    // {
                    //     "data": "AMOUNT_MONTHLY_LEASING",
                    //     "className": "text-right",
                    //     render: $.fn.dataTable.render.number(',', '.', 2)
                    // },
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            // console.log(data);
                            // console.log(row);
                            // console.log(meta);
                            var html = '';
                            // html += '<button class="btn btn-info btn-sm PAY" data-id="'+data.UUID+'" data-company="'+data.COMPANY+'" data-pyear="'+data.PERIOD_YEAR+'" data-pmonth="'+data.PERIOD_MONTH+'" data-docnumber="'+data.DOCNUMBER+'"  id="PAY" title="Pay">Pay</button>'; 
                            if(data.STATUS == '1' || data.STATUS == 1){
                                html += '<button class="btn btn-info btn-sm PAY" data-id="'+data.UUID+'" data-company="'+data.COMPANY+'" data-pyear="'+data.PERIOD_YEAR+'" data-pmonth="'+data.PERIOD_MONTH+'" data-docnumber="'+data.DOCNUMBER+'"  id="PAY" title="Pay" disabled>Forecast</button>';    
                            }else{
                                html += '<button class="btn btn-info btn-sm PAY" data-id="'+data.UUID+'" data-company="'+data.COMPANY+'" data-pyear="'+data.PERIOD_YEAR+'" data-pmonth="'+data.PERIOD_MONTH+'" data-docnumber="'+data.DOCNUMBER+'"  id="PAY" title="Pay">Forecast</button>';    
                            }
                            
                            return html;
                        }
                    },
                    {
                            "data": null,
                            "className": "text-center align-middle",
                            "orderable": false,
                            render: function (data, type, row, meta) {
                                return '<input type="checkbox" class="pils">';
                            }
                        },
                ],
                deferRender: true,
                scrollY: 400,
                scrollX: true,
                scrollCollapse: true,
                scroller: true,
                "bFilter": true,
                "bPaginate": false,
                "bLengthChange": false,
                "bInfo": true,
            });
            table2 = $('#DtLeasingTransaction').DataTable();
            $('#DtLeasingTransaction thead th').addClass('text-center');
            //Hapus Searching Default Datatable
            $('#DtLeasingTransaction_filter').remove();
            // $('#searchInvoice').on('input', function () {
            //     table2.search(this.value).draw();
            // });

            $('#searchInvoice').keyup(delay(function (e) {
                  table2.search(this.value, true, false, true).draw();
            }, 500));

            function delay(fn, ms) {
              let timer = 0
              return function(...args) {
                clearTimeout(timer)
                timer = setTimeout(fn.bind(this, ...args), ms || 0)
              }
            }

            table2.on('change', '.pils', function () {
                $tr = $(this).closest('tr');
                var data = table2.row($tr).data();
                if (this.checked) {
                    data.FLAG = "1";
                } else {
                    data.FLAG = "0";
                }
            });
        }else{
            table2.ajax.reload();
        }
    }

    function footerCallback(row, data, start, end, display) {
                var api = this.api(), data;
         
                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    basic = api
                            .column( 7 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                            
                    interest = api
                            .column( 8 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                            
                    amount = api
                            .column( 9 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                    basic_conv = api
                            .column( 10 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                            
                    interest_conv = api
                            .column( 11 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                            
                    amount_conv = api
                            .column( 12 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                
                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                    // $( api.column( 7 ).footer() ).html(numFormat(basic));
                    // $( api.column( 8 ).footer() ).html(numFormat(interest));
                    // $( api.column( 9 ).footer() ).html(numFormat(amount));
                    $( api.column( 10 ).footer() ).html(numFormat(basic_conv));
                    $( api.column( 11 ).footer() ).html(numFormat(interest_conv));
                    $( api.column( 12 ).footer() ).html(numFormat(amount_conv));
    }

    // table2 = $('#DtLeasingTransaction').DataTable();

    $('#pil').on('change', function () {
        if (this.checked) {
            $('#DtLeasingTransaction .pils').prop("checked", true);
        } else {
            $('#DtLeasingTransaction .pils').prop("checked", false);
        }
        $('#DtLeasingTransaction .pils').change();
    });

    $('body').on('click','.payAll',function(){
        // $(this).prop('disabled', true);
        // PayAll.push(data);
        // console.log(table2.data());
        $('#loader').addClass('show');
        //$('#page-loader').removeClass('d-none');
        $.ajax({
                        dataType: "JSON",
                        type: "POST",
                        url: "<?php echo site_url('Leasing/payAllLeasingTransaction'); ?>",
                        data: {
                           DtLeasing: JSON.stringify(payAll),
                           DUEDATE: $('#DUEDATE').val(),
                           // INTEREST_PERCENTAGE: $('#INTEREST_PERCENTAGE').val(),
                           COMPANY: COMPANY,
                           YEAR: YEAR,
                           MONTH: MONTH,
                           USERNAME: USERNAME
                        },
                        success: function (response) {
                            //$("#page-loader").addClass('d-none');
                            
                            if (response.status == 200) {
                                alert(response.result.data);
                                // $('.PAY').attr('disabled',true);
                                LoadDataTable();
                            } else if (response.status == 504) {
                                alert(response.result.data);
                                location.reload();
                            } else {
                                alert(response.result.data);
                            }
                            $('#loader').removeClass('show');
                        },
                        error: function (e) {
                            //$("#page-loader").addClass('d-none');
                            // console.info(e);
                            alert('Data Save Failed !!');   
                            $('#loader').removeClass('show');
                            $('#btnSave').removeAttr('disabled');
                        }
                    });
        // $.each(table2.data(), function (index, value) {

            // console.log(index);
            // console.log(value);
            // if (value.UUID == undefined || value.UUID == null || value.UUID == '') {
            // } else {
            //     // console.log(value);
            //     if (value.FLAG == 1) {
            //         PayAll.push(value);
            //         console.log(payAll);
            //         $("#page-loader").show();
            //     }
            // }
        // });
        // ReloadTable1();
        // $('#MOutstanding').modal("hide");
    });

    $('body').on('click','#PAY',function(){
        var ID = $(this).attr('data-id');
        var COMPANY = $(this).attr('data-company');
        var DOCNUMBER = $(this).attr('data-docnumber');
        var pYEAR = $(this).attr('data-pyear');
        var pMONTH = $(this).attr('data-pmonth');
        $('#loader').addClass('show');
        //$('#page-loader').removeClass('d-none');
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
                    // INTEREST_PERCENTAGE: $('#INTEREST_PERCENTAGE').val(),
                    YEAR: YEAR,
                    MONTH: MONTH,
                    USERNAME: USERNAME
                },
                success: function (response) {
                    //$("#page-loader").addClass('d-none');
                    if (response.status == 200) {
                        alert(response.result.data);
                        // $('.PAY').attr('disabled',true);
                        LoadDataTable();
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        location.reload();
                    } else {
                        alert(response.result.data);
                    }
                    $('#loader').removeClass('show');
                },
                error: function (e) {
                    //$("#page-loader").addClass('d-none');
                    // console.info(e);
                    alert('Data Save Failed !!');
                    $('#loader').removeClass('show');
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