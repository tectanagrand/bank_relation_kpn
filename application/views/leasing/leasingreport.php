<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<!-- <script src="https://cdn.datatables.net/plug-ins/1.10.10/api/sum().js"></script> -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">New Leasing</a></li>
    <li class="breadcrumb-item active">New Leasing</li>
</ol>
<h1 class="page-header">New Report Leasing</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">New Report Leasing</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="form-row">
                    <div class="col">
                        <label for="COMPANY">Company</label>
                        <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                            <option selected="">Select Company</option>
                            <option value="0">All Company</option>
                            <?php
                            foreach ($DtCompany as $values) {
                                echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <!-- <div class="col">
                        <label for="PERIOD">Period</label>
                        <input type="text" class="form-control" name="PERIOD" id="PERIOD" autocomplete="off">
                    </div> -->
                    <!-- <div class="col">
                        <label for="PERIOD">Interest Percentage</label>
                        <input type="text" class="form-control" name="INTEREST_PERCENTAGE" id="INTEREST_PERCENTAGE" autocomplete="off" required>
                    </div> -->
                    <div class="col">
                        <label for="PERIOD"></label>
                        <button class="btn btn-info mt-4 btn-sm" onclick="Export()">Export</button>
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
                        <th class="text-center sorting_disabled align-middle" >Company</th>
                        <th class="text-center sorting_disabled align-middle" >Bank</th>
                        <th class="text-center sorting_disabled align-middle" >BU</th>
                        <th class="text-center sorting_disabled align-middle" >Docnumber</th>
                        <th class="text-center sorting_disabled align-middle" >Currency</th>
                        <th class="text-center sorting_disabled align-middle" >Basic Amount</th>
                        <th class="text-center sorting_disabled align-middle" >Interest</th>
                        <th class="text-center sorting_disabled align-middle" >Total Month</th>
                        <th class="text-center sorting_disabled align-middle" >Due Date</th>
                        <th class="text-center sorting_disabled align-middle" >First Payment</th>
                        <th class="text-center sorting_disabled align-middle" >Remain</th>
                        <!-- <th class="text-center sorting_disabled align-middle" >#</th> -->
                    </tr>
                </thead>
                <!-- <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                    </tr>
                </tfoot> -->
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
    var COMPANY;
    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }

    var table2;

        //     $('#PERIOD').datepicker({
        //     "autoclose": true,
        //     "todayHighlight": true,
        //     "viewMode": "months",
        //     "minViewMode": "months",
        //     "format": "M yyyy"
        // });

    //Load Data Table Awal
    function LoadDataTable() {
        
        if (!$.fn.DataTable.isDataTable('#DtLeasingTransaction')) {
            $('#DtLeasingTransaction').DataTable({
                "processing": true,
                "columnDefs": [
                    { "visible": false, "targets": 0 }
                ],
                // "drawCallback": function ( settings ) {
                //     var api = this.api();
                //     var rows = api.rows( {page:'current'} ).nodes();
                //     var last=null;
         
                //     api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                //         if ( last !== group ) {
                //             $(rows).eq( i ).before(
                //                 '<tr class="group"><td colspan="5">'+group+'</td></tr>'
                //             );
         
                //             last = group;
                //         }
                //     } );
                // },
                "ajax": {
                    "url": "<?php echo site_url('Leasing/showReportLeasing') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function (d) {
                        d.COMPANY = $('#COMPANY').val();
                    },
                    "dataSrc": function (ext) {
                        if(ext.result.size == 0){
                            toastr.error("Data Kosong");
                            return [];
                        }
                        else if (ext.status == 200) {
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
                    {"data": "COMPANYCODE"},
                    {"data": "SUPPNAME"},
                    {"data": "BUNAME"},
                    {"data": "DOCNUMBER"},
                    {"data": "CURRENCY"},
                    {
                        "data": "BASIC_AMOUNT",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {"data": "INTEREST_PERCENTAGE"},
                    {"data": "TOTAL_MONTH"},
                    {"data": "DUEDATE_PER_MONTH"},
                    {"data": "VALID_FROM"},
                    {
                        "data": "REMAIN_BASIC_AMOUNT_LEASING",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    }
                    // ,
                    // {
                    //     "data": null,
                    //     "className": "text-center",
                    //     "orderable": false,
                    //     render: function(data, type, row, meta) {
                            
                    //         var html = '';
                            
                    //             html += '<button class="btn btn-info btn-sm Export" id="Export" data-company="'+data.COMPANYID+'" title="Export">Print</button>';    
                            
                    //         return html;
                    //     }
                    // }
                    // {
                    //     "data": "AMOUNT_MONTHLY_LEASING",
                    //     "className": "text-right",
                    //     render: $.fn.dataTable.render.number(',', '.', 2)
                    // },
                    
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
            
        }
        else{
            table2.ajax.reload();
        }
    }

    var Export = function () {
                    // var DOCNUM = $(this).attr('data-docnumber');
                    var COMP = $('#COMPANY').val();
                    var url = "<?php echo site_url('Leasing/exportNewReport'); ?>?COMPANY=PARAM1";
                    url = url.replace("PARAM1", COMP);
                    // url = url.replace("PARAM2", DOCNUM);
                    window.open(url, '_blank');
                };

    // function footerCallback(row, data, start, end, display) {
    //             var api = this.api(), data;
         
    //                 // Remove the formatting to get integer data for summation
    //                 var intVal = function ( i ) {
    //                     return typeof i === 'string' ?
    //                         i.replace(/[\$,]/g, '')*1 :
    //                         typeof i === 'number' ?
    //                             i : 0;
    //                 };

    //                 basic = api
    //                         .column( 8 )
    //                         .data()
    //                         .reduce( function (a, b) {
    //                             return intVal(a) + intVal(b);
    //                         }, 0 );
                            
    //                 interest = api
    //                         .column( 9 )
    //                         .data()
    //                         .reduce( function (a, b) {
    //                             return intVal(a) + intVal(b);
    //                         }, 0 );
                            
    //                 amount = api
    //                         .column( 10 )
    //                         .data()
    //                         .reduce( function (a, b) {
    //                             return intVal(a) + intVal(b);
    //                         }, 0 );

    //                 basic_conv = api
    //                         .column( 11 )
    //                         .data()
    //                         .reduce( function (a, b) {
    //                             return intVal(a) + intVal(b);
    //                         }, 0 );
                            
    //                 interest_conv = api
    //                         .column( 12 )
    //                         .data()
    //                         .reduce( function (a, b) {
    //                             return intVal(a) + intVal(b);
    //                         }, 0 );
                            
    //                 amount_conv = api
    //                         .column( 13 )
    //                         .data()
    //                         .reduce( function (a, b) {
    //                             return intVal(a) + intVal(b);
    //                         }, 0 );
                
    //                 var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
    //                 // $( api.column( 8 ).footer() ).html(numFormat(basic));
    //                 // $( api.column( 9 ).footer() ).html(numFormat(interest));
    //                 // $( api.column( 10 ).footer() ).html(numFormat(amount));
    //                 $( api.column( 11 ).footer() ).html(numFormat(basic_conv));
    //                 $( api.column( 12 ).footer() ).html(numFormat(interest_conv));
    //                 $( api.column( 13 ).footer() ).html(numFormat(amount_conv));
    // }

    $('#COMPANY').on({
            'change': function() {
                COMPANY = $('#COMPANY').val();
                LoadDataTable();
            }
        });

    // $(document.body).on('change','#COMPANY',function(){
    //      COMPANY = $('#COMPANY').val();
    //      LoadDataTable();
    // });
    // table2 = $('#DtLeasingTransaction').DataTable();
    //Change Period
    // $('#COMPANY').on({
    //     'change': function () {
    //         // MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
    //         // YEAR = this.value.substr(4, 4);
    //         COMPANY = $(this).val();
    //         LoadDataTable();
    //     }
    // });

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