<link href="./assets/css/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<style type="text/css">
    .dt-button{
        transition-duration: 0.4s;
        background-color: #76b6d6;
    }
    .dt-button:hover {
        background-color: #138496;
        color: #138496;
    }
</style>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Report</a></li>
    <li class="breadcrumb-item active">Tracing Document Detail</li>
</ol>
<h1 class="page-header">Tracing Document Detail</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Tracing Document Detail</h4>
    </div>
    <div class="invoice">
        <div class="invoice-company text-inverse f-w-600">
            <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="row">
                    <!-- <div class="form-group col-md-3">
                        <select class="form-control mkreadonly" name="DOCTYPE" id="DOCTYPE">
                            <option value="">Select Document Type</option>
                            <?php
                            foreach ($DtDocType as $values) {
                                echo '<option value=' . $values->FCCODE . '>' . $values->FCCODE . ' - ' . $values->FCNAME . '</option>';
                            }
                            ?>
                        </select>
                    </div> -->
                    <!-- <div class="form-group col-md-3">
                        <select class="form-control mkreadonly" name="COMPANY" id="COMPANY" required="true">
                            <option disabled selected hidden>Select Company</option>
                            <option value="">All</option>
                            <?php
                            foreach ($DtCompany as $values) {
                                echo '<option value="' . $values->COMPANYCODE . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <select class="form-control mkreadonly" name="COMPANYGROUP" id="COMPANYGROUP">
                            <option value="" disabled selected hidden>Select Company Group</option>
                            <option value="CMT">CEMENT</option><option value="MOTIVE">MOTIVE</option><option value="PLT">PLANTATION</option><option value="PROPERTY">PROPERTY</option><option value="WOOD">WOOD</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        
                        <select class="form-control" name="COMPANYSUBGROUP" id="COMPANYSUBGROUP">
                            <option value="" disabled selected hidden>Select Company SubGroup</option>
                            <option value="UPSTREAM">UPSTREAM</option>
                            <option value="DOWNSTREAM">DOWNSTREAM</option></select>
                    </div> -->
                    <div class="form-group col-md-3">
                       <div class="form-group">
                        <input type="text" class="form-control roleaccess" name="DOCNUMBER" id="DOCNUMBER" placeholder="Search Document Number" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group col-1">
                        <button type="button" class="btn btn-info" style="padding: 3px 10px;" onclick="searchData()">Show</button>
                    </div>
                    <div class="form-group col-md-12">
                    <hr style="background:#e2e7eb!important;margin:.3rem 0!important;">
                    </div>
                </div>            
            </form>
        </div>
        <!-- <div class="invoice-header">        
            <div class="row">
                <div class="col-md-4">
                    <table>
                        <tr>
                            <td style="font-size: 12px; color: black; font-weight: bold; width: 165px">Company Code</td>
                            <td style="font-size: 12px; color: black; font-weight: bold; width: 10px">:</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;" id="COMPANYCODEHEAD"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; color: black; font-weight: bold;">Document Number Source</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;">:</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;" id="DOCNUMBER_SOURCEHEAD"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; color: black; font-weight: bold;">Document Date Source</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;">:</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;" id="DOCDATE_SOURCEHEAD"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; color: black; font-weight: bold;">Amount Source</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;">:</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;" id="AMOUNT_SOURCEHEAD"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; color: black; font-weight: bold;">Total Invoice</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;">:</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;" id="TOTAL_INVHEAD"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; color: black; font-weight: bold;">Total PPH</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;">:</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;" id="TOTAL_PPHHEAD"></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <table>
                        <tr>
                            <td style="font-size: 12px; color: black; font-weight: bold; width: 140px">Total Amount Request</td>
                            <td style="font-size: 12px; color: black; font-weight: bold; width: 10px">:</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;" id="TOTAL_AMOUNTREQUESTHEAD"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; color: black; font-weight: bold;">Total Amount Adjust</td>
                            <td style="font-size: 12px; color: black; font-weight: bold; width: 30px text-center">:</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;" id="TOTAL_AMOUNTADJSHEAD"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; color: black; font-weight: bold;">Total Debet</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;">:</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;" id="TOTAL_DEBETHEAD"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; color: black; font-weight: bold;">Total Credit</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;">:</td>
                            <td style="font-size: 12px; color: black; font-weight: bold;" id="TOTAL_CREDITHEAD"></td>
                        </tr>
                    </table>
                </div>
            </div>             
        </div> -->
        <div class="invoice-content">
            <div class="row ml-0 mr-0 mb-0 mt-2 table-responsive">
                <div class="table-responsive">
                    <table id="DtTracing" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                        <thead>
                            <tr role="row">
                                <!-- <th class="text-center" style="width: 7px">No</th> -->
                                <th class="text-nowrap text-center">Company</th>
                                <th class="text-nowrap text-center">Dept</th>
                                <th class="text-nowrap text-center">DocNumber</th>
                                <!-- <th class="text-nowrap text-center">DocDate</th> -->
                                <th class="text-nowrap text-center">Amount</th>
                                <th class="text-nowrap text-center">DocNumber Inv</th>
                                <th class="text-nowrap text-center">Inv Vendor no</th>
                                <!-- <th class="text-nowrap text-center">DocDate Inv</th> -->
                                <th class="text-nowrap text-center">Vendor</th>
                                <th class="text-nowrap text-center">Currency</th>
                                <th class="text-nowrap text-center">Amount Inv</th>
                                <th class="text-nowrap text-center">Due Date</th>
                                <!-- <th class="text-nowrap text-center">Total Inv</th> -->
                                <!-- <th class="text-nowrap text-center">Amount PPH </th> -->
                                <!-- <th class="text-nowrap text-center">Total PPH</th> -->
                                <th class="text-nowrap text-center">Forecast Year</th>
                                <th class="text-nowrap text-center">Forecast Month</th>
                                <th class="text-nowrap text-center">Forecast Week</th>
                                <th class="text-nowrap text-center">Amount Request</th>
                                <!-- <th class="text-center">Total Amount Request</th> -->
                                <th class="text-nowrap text-center">Amount Adjust</th>
                                <!-- <th class="text-center">Total Amount Adjust</th> -->
                                <th class="text-nowrap text-center">Voucher No </th>
                                <!-- <th class="text-nowrap text-center">Bank Name</th>
                                <th class="text-nowrap text-center">Bank Account</th> -->
                                <th class="text-nowrap text-center">Date Release</th>
                                <!-- <th class="text-nowrap text-center">NoCekGiro</th> -->
                                <!-- <th class="text-nowrap text-center">Debet</th> -->
                                <!-- <th class="text-nowrap text-center">Total Debt</th> -->
                                <th class="text-nowrap text-center">Amount Payment</th>
                                <!-- <th class="text-nowrap text-center">Total Credit</th> -->
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<script src="./assets/js/datetime/bootstrap-datetimepicker.min.js"></script>

<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    
    var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;

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

    var COMPANY = '', DOCNUMBER, COMPANYGROUP = '', COMPANYSUBGROUP = '';
    $(document).ready(function() {

        // $(document).on('change', '#COMPANY', function() {            
        //     COMPANY = $(this).val();
        // });

        $(document).on('change', '#DOCNUMBER', function() {            
            DOCNUMBER = $(this).val();
        });
    });

    function LoadDataTable() {
        if (!$.fn.DataTable.isDataTable('#DtTracing')) {
            $('#DtTracing').DataTable({
                "processing": true,
                "serverSide":true,
                "lengthMenu": [ [10, 25, 50, 100], [10, 25, 50, 100] ],
                "ajax": {
                    "url": "<?php echo site_url('TracingDocument/ShowDataDet') ?>",
                    // "contentType": "application/json",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function(d) {
                        // d.COMPANY       = COMPANY;
                        // d.COMPANYGROUP  = $('#COMPANYGROUP').val();
                        // d.COMPANYSUBGROUP  = $('#COMPANYSUBGROUP').val();
                        d.DOCNUMBER = DOCNUMBER;
                    },
                    "dataSrc": function(ext) {
                        if (ext.status == 200) {
                                sendAll = ext.result.data.data;
                                ext.draw = ext.result.data.draw;
                                ext.recordsTotal = ext.result.data.recordsTotal;
                                ext.recordsFiltered = ext.result.data.recordsFiltered;
                                return ext.result.data.data;
                            } else if (ext.status == 504) {
                                alert(ext.result.data);
                                location.reload();
                                return [];
                            } else {
                                console.info(ext.result.data);
                                return [];
                            }
                        $("#loader").removeClass('show');
                    },
                    "beforeSend": function() {
                    $("#loader").addClass('show');
                    },
                    "complete": function() {
                        $("#loader").removeClass('show');
                    }
                },
                "columns": [
                // {
                //     "data": null,
                //     "className": "text-center",
                //     render: function (data, type, row, meta) {
                //         return meta.row + 1;
                //     }
                // },
                {"data": "COMPANYCODE","className": "text-center"},
                {"data": "DEPARTMENT","className": "text-center"},
                {"data": "DOCNUMBER_SOURCE","className": "text-center"},
                // {"data": "DOCDATE_SOURCE","className": "text-center"},
                {
                    "data": "AMOUNT_SOURCE",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {"data": "DOCNUMBER_INV","className": "text-center"},
                {"data": "INV_VENDORNO","className": "text-center"},
                // {"data": "DOCDATE_INV","className": "text-center"},
                {"data": "VENDORNAME","className": "text-center"},
                {"data": "INV_CURRENCY","className": "text-center"},
                {
                    "data": "AMOUNT_INV",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {"data": "INV_DUEDATE","className": "text-center"},
                // {
                //     "data": "TOTAL_INV",
                //     "className": "text-right",
                //     render: $.fn.dataTable.render.number(',', '.', 2)
                // },
                // {
                //     "data": "AMOUNT_PPH",
                //     "className": "text-right",
                //     render: $.fn.dataTable.render.number(',', '.', 2)
                // },
                // {
                //     "data": "TOTAL_PPH",
                //     "className": "text-right",
                //     render: $.fn.dataTable.render.number(',', '.', 2)
                // },
                {"data": "YEAR","className": "text-center"},
                {"data": "MONTH","className": "text-center"},
                {"data": "WEEK","className": "text-center"},
                {
                    "data": "AMOUNTREQUEST",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                // {
                //     "data": "TOTAL_AMOUNTREQUEST",
                //     "className": "text-right",
                //     render: $.fn.dataTable.render.number(',', '.', 2)
                // },
                {
                    "data": "AMOUNTADJS",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                // {
                //     "data": "TOTAL_AMOUNTADJS",
                //     "className": "text-right",
                //     render: $.fn.dataTable.render.number(',', '.', 2)
                // },
                {"data": "VOUCHERNO","className": "text-center"},
                // {"data": "BANKNAME","className": "text-center"},
                // {"data": "BANKACCOUNT","className": "text-center"},
                {"data": "DATERELEASE","className": "text-center"},
                // {"data": "NOCEKGIRO","className": "text-center"},
                {
                    "data": "CREDIT",
                    // "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                // {
                //     "data": "TOTAL_DEBET",
                //     "className": "text-right",
                //     render: $.fn.dataTable.render.number(',', '.', 2)
                // },
                // {
                //     "data": "CREDIT",
                //     // "className": "text-right",
                //     render: $.fn.dataTable.render.number(',', '.', 2)
                // },
                // {
                //     "data": "TOTAL_CREDIT",
                //     "className": "text-right",
                //     render: $.fn.dataTable.render.number(',', '.', 2)
                // },
            ],
                "bFilter": true,
                "bPaginate": true,
                "bLengthChange": true,
                "bInfo": true,
            });
            table2 = $('#DtTracing').DataTable();
            // $('#DtTracing_filter').remove()

            // $('#DOCNUMBER').on( 'input', function () {
            //     table2.search( this.value ).draw();
            // });

            $('input.input-sm').on({
                    'keyup': function () {
                        table2.search(this.value, true, false, true).draw();
                    }
                });

            $('#DtTracing thead th').addClass('text-center');
            
        }else {
            table2.ajax.reload();
        }
    }

    var searchData = function () {
        // if ($('#DOCNUMBER').val() == '') {
        //     alert("Document Number can not be empty!")
        //     return false
        // } 
        // else{
            LoadDataTable()   
        // } 
    }      
        
        // if (LoadDataTable() == null || LoadDataTable() ==''){
        //     alert("Data Not Found")
        //     return false
        // }

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