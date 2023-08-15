<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="">
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<style>
    #overlay {
        position: fixed;
        /* Sit on top of the page content */
        display: none;
        /* Hidden by default */
        width: 100%;
        /* Full width (cover the whole page) */
        height: 100%;
        /* Full height (cover the whole page) */
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        /* Black background with opacity */
        z-index: 2;
        /* Specify a stack order in case you're using a different order for other elements */
        cursor: pointer;
        /* Add a pointer on hover */
    }

    #dtf {
        height: 582px;
        overflow: auto;
    }

    #dtf th {
        border: 2px solid #d3d3d3;
        background-color: #fff;
    }

    #DtForecast_filter {
        display: none;
    }

    #DtForecast_wrapper {
        position: relative;
        clear: both;
        width: auto;
        max-height: 582px;
        margin-left: 0px;
        /*        border-bottom: 1px solid black; .dataTables_wrapper
                border-top: 1px solid black;
                border-left: 1px solid black;
                border-right: 1px solid black;
                background-color: #9D9C9D; */
        /*zoom: 1;*/
    }
</style>
<?php
$CDepartment = '';
foreach ($DtDepartment as $values) {
    $CDepartment .= '<option value="' . $values->DEPARTMENT . '">' . $values->DEPARTEMENTNAME . '</option>';
}
?>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Negatif Amount</li>
</ol>
<h1 class="page-header">Forecast</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Negatif Amount</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="form-row">
                    <div class="col">
                        <label for="DEPARTMENT">Departement</label>
                        <select class="form-control" name="DEPARTMENT" id="DEPARTMENT">
                            <option value="" selected>All Department</option>
                            <?php echo $CDepartment; ?>
                        </select>
                    </div>
                            <div class="col">
                                <label for="COMPANYGROUP">Group</label>
                                <select class="form-control mkreadonly" name="COMPANYGROUP" id="COMPANYGROUP">
                                    <option value="">All</option>
                                    <option value="0">Null</option>
                                    <option value="CMT">CEMENT</option><option value="MOTIVE">MOTIVE</option><option value="PLT">PLANTATION</option><option value="PROPERTY">PROPERTY</option><option value="WOOD">WOOD</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="SUBGROUP">Subgroup</label>
                                <select class="form-control" name="COMPANYSUBGROUP" id="COMPANYSUBGROUP">
                                    <option value="" selected="">All</option>
                                    <option value="0">Null</option>
                                    <option value="UPSTREAM">UPSTREAM</option>
                                    <option value="DOWNSTREAM">DOWNSTREAM</option></select>
                            </div>
                            <div class="col">
                                <label for="PERIOD">Period</label>
                                <input type="text" class="form-control" name="PERIOD" id="PERIOD" placeholder="MMM YYYY" autocomplete="off">
                            </div>
                            <div class="col">
                                <label for="search">Search</label>
                                <input type="text" class="form-control col-md-10" name="searchBox" id="searchBox">
                            </div>
                        
                    

                </div>
            </div>
        </div>
        <div class="row m-0 table-responsive" id="dtf">
            <table id="DtForecast" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr role="row">
                        <th class="text-center align-middle" aria-sort="ascending" style="width: 30px;" >No</th>
                        <th class="text-center align-middle" >Department</th>
                        <th class="text-center align-middle" >Company</th>
                        <th class="text-center align-middle" >Docref</th>
                        <th class="text-center align-middle" >Year</th>
                        <th class="text-center align-middle" >Month</th>
                        <th class="text-center align-middle" >Amount Outstanding</th>
                        <th class="text-center align-middle" >Amount Request</th>
                        <th class="text-center align-middle" >Amount Adjs</th>
                        <th class="text-center align-middle" >Over</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    // let statusCard = document.querySelector('#dtf');
    // // add scroll event listener for change head's position 
    // statusCard.addEventListener('scroll', e => {
    //     let tableHead = document.querySelector('thead');
    //     let scrollTop = statusCard.scrollTop;
    //     tableHead.style.transform = 'translateY(' + scrollTop + 'px)';
    // })

    $(document).on("keyup", "#searchBox", function() {
        var tables = $("#DtForecast").DataTable({
            retrieve: true,
            paging: false,
            dom: "t"
        });
        tables.search($(this).val()).draw();
        $('#dtf').animate({
            scrollTop: 0
        }, 1000);
        // var toPos = $("#dtf").position().top;
        //   $("#dtf").scrollTop(toPos);
    });
</script>
<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var USERACCESS = "<?php echo $DtUser2->USERACCESS; ?>";
    var YEAR = "",
        MONTH = "",
        COMPANYGROUP = "",
        COMPANYSUBGROUP = "",
        DEPARTMENT = "",
        timeOutonKeyup = null;
    var table, table2, table3;
    var DtForecast = [],
        DtRevApp = [];
    <?php if ($DtUser2->USERACCESS == '100005') { ?>
        var Disable = "hidden";
    <?php } else { ?>
        var Disable = "disabled";
    <?php } ?>
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    
    if (!$.fn.DataTable.isDataTable('#DtForecast')) {
            $('#DtForecast').DataTable({
                processing: true,
                filter:true,
                dom: 'Bfrtip',
                buttons: {
                    dom: {
                        button: {
                            className: 'btn btn-info' //Primary class for all buttons
                        }
                    },
                    buttons: [                  
                        {
                            //EXCEL
                            extend: 'excelHtml5',
                            title: 'Report Negatif Amount' //extend the buttons that u wanna use
                        }
                    ]
                },
               "aaData": DtForecast,
               "language": {
                                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading..n.</span> '
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
                        "data": "DEPARTMENT"
                    },
                    {
                        "data": "COMPANY"
                    },
                    {
                        "data": "DOCREF"
                    },
                    {
                        "data": "YEAR"
                    },
                    {
                        "data": "MONTH"
                    },
                    {
                        "data": "AMOUNTOUTSTANDING",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNTREQUEST",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNTADJS",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "OVER",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    }
                    
                ],
                "searching": true,
                "bProcessing": true,
                "responsive": true,
                "bFilter": true,
                "bPaginate": true,
                "bLengthChange": false,
                "bInfo": false
            });

            table = $('#DtForecast').DataTable();
    }
    

    //    Load Date Picker Period
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
            MONTH           = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
            YEAR            = this.value.substr(4, 4);
            $('#loader').addClass('show');
            $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Forecast/showNegatifAmount'); ?>",
                    data: {
                        YEAR: YEAR,
                        MONTH: MONTH,
                        DEPARTMENT: $("#DEPARTMENT").val(),
                        COMPANYSUBGROUP: $('#COMPANYSUBGROUP').val(),
                        COMPANYGROUP: $('#COMPANYGROUP').val()
                    },
                    success: function(response, textStatus, jqXHR) {
                        $('#loader').removeClass('show');
                        if (response.status == 200) {
                            DtForecast = response.result.data;
                            table.clear();
                            table.rows.add(DtForecast);
                            table.draw();
                        } else if (response.status == 504) {
                            alert(response.result.data);
                            location.reload();
                        } else {
                            alert(response.result.data);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Please Check Your Connection !!!');
                        $('#loader').removeClass('show');
                    }
                });
        }
    });
    $('#DEPARTMENT').on({
        'change': function() {
            $('#PERIOD').change();
        }
    });
    $('#COMPANYSUBGROUP').on({
        'change': function() {
            $('#PERIOD').change();
        }
    });
    $('#CASHFLOWTYPE').on({
        'change': function() {
            $('#PERIOD').change();
        }
    });

    //    Function - Function Formater Numberic
    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            formatCurrency($(this), "blur");
        }
    });

    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function formatCurrency(input, blur) {
        var input_val = input.val();
        if (input_val === "") {
            return;
        }
        var original_len = input_val.length;
        var caret_pos = input.prop("selectionStart");
        if (input_val.indexOf(".") >= 0) {
            var decimal_pos = input_val.indexOf(".");
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring((decimal_pos + 1));
            left_side = formatNumber(left_side);
            right_side = formatDesimal(right_side);
            input_val = left_side + "." + right_side;
        } else {
            input_val = formatNumber(input_val);
            input_val = input_val;
            if (blur === "blur") {
                input_val += ".00";
            }
        }
        input.val(input_val);
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }

    function formatDesimal(n) {
        return n.replace(/[^0-9.-]+/g, "");
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
    //    End Formater Numberic
</script>