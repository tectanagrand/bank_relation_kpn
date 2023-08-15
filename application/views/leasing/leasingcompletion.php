<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Leasing</a></li>
    <li class="breadcrumb-item active">Leasing Completion</li>
</ol>
<h1 class="page-header">Leasing Completion</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Leasing Completion</h4>
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
                    <div class="col-2">
                        <label for="COMPANY">Tipe</label>
                        <select class="form-control mkreadonly" name="TIPE" id="TIPE">
                            <option value="1">Completion with forecast</option>
                            <option value="2">Completion but no forecast</option>
                        </select>
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
            <table id="DtLeasingCompletion" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                <thead>
                    <tr role="row">
                        <th class="text-center sorting align-middle" >Company</th>
                        <th class="text-center sorting align-middle" >Doc Number</th>
                        <th class="text-center sorting align-middle" >Item Name</th>
                        <th class="text-center sorting align-middle" >Vendor</th>
                        <th class="text-center sorting align-middle" >Line No</th>
                        <th class="text-center sorting align-middle" >Transaction Method</th>
                        <th class="text-center sorting align-middle" >Basic Amount</th>
                        <th class="text-center sorting align-middle" >Amount Yearly Leasing</th>
                        <th class="text-center sorting align-middle" >Outstanding Amount</th>
                        <th class="text-center sorting align-middle" >Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- modal -->
        <div class="modal fade" id="ReceiveModal" tabindex="-1" role="dialog" aria-labelledby="ReceiveModal" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Doc No - <span id="DOCNUMB"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="id">
                    <input type="hidden" id="uuid">
                    <input type="hidden" id="mdocnumber">
                    <input type="hidden" id="mmonth">
                    <input type="hidden" id="myear">
                    <input type="hidden" id="mlineno">
                    <!-- <input type="hidden" id="noreceiptdoc"> -->
                    <!-- <span style="color:red;">* Maksimal 100 Karakter</span> -->
                    <div class="col-md-6 mb-2">
                        <label for="COMPANY">Date Completion</label>
                        <input type="text" class="form-control" name="MPERIOD" id="MPERIOD" autocomplete="off">
                    </div>
                    <div class="col-md-6">
                        <label for="COMPANY">Amount By System</label>
                        <input type="text" data-type='currency' placeholder="amount from system" class="form-control" name="from_system" id="from_system" autocomplete="off" disabled>
                    </div>
                    <div class="col-md-6">
                        <label for="COMPANY">Amount By User</label>
                        <input type="text" data-type='currency' placeholder="amount" class="form-control" name="amount_modal" id="amount_modal" autocomplete="off">
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="saveModal">Save changes</button>
              </div>
            </div>
          </div>
        </div>
        <!-- end modal -->
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

    $('#MPERIOD').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "format": "mm/dd/yyyy",
        "startDate": '-1m',
    });

    function LoadDataTable() {
        if (!$.fn.DataTable.isDataTable('#DtLeasingCompletion')) {
            $('#DtLeasingCompletion').DataTable({
                "processing": true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Report Payment'
                    },  
                ],
                "ajax": {
                    "url": "<?php echo site_url('Leasing/ShowDataCompletion') ?>",
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
                    {"data": "VENDORNAME"},
                    {"data": "LINENO"},
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
                        "data":"BASIC_AMOUNT",
                        "className": "text-right",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                        var html = fCurrency(data);
                            return html;
                        }
                        
                    },
                    {
                        "data":"AMOUNT_YEARLY_LEASING",
                        "className": "text-right",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                        var html = fCurrency(data);
                            return html;
                        }
                        
                    },
                    {
                        "data":"REMAIN_BASIC_AMOUNT_LEASING",
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
                            // html += '<button class="btn btn-info btn-sm COMPLETION" data-id="'+data.ID+'" data-year="'+data.PERIOD_YEAR+'" data-month="'+data.PERIOD_MONTH+'"  data-docnumber="'+data.DOCNUMBER+'" data-lineno="'+data.LINENO+'"  id="COMPLETION" title="Pay">Execute</button>';
                            html += '<button class="btn btn-indigo btn-sm mt-2 ReceiveModal" data-id="'+data.ID+'" data-uuid="'+data.UUID+'" data-year="'+data.PERIOD_YEAR+'" data-month="'+data.PERIOD_MONTH+'"  data-docnumber="'+data.DOCNUMBER+'" data-lineno="'+data.LINENO+'"  id="ReceiveModals" title="Receive" data-toggle="modal" data-target="#ReceiveModal">Complete</button>';
                            return html;
                        }
                    }
                ],
            });

            $('#DtLeasingCompletion_filter').remove()

            $('#search').on( 'input', function () {
                table2.search( this.value ).draw();
            });

            $('#DtLeasingCompletion thead th').addClass('text-center');
            table2 = $('#DtLeasingCompletion').DataTable();
            // $("#DtLeasingCompletion_filter").remove();
            // $("#DOCNUMBER").on({
            //     'keyup': function () {
            //         table2.search(this.value, true, false, true).draw();
            //     }
            // });

        } else {
            table2.ajax.reload();
        }
    }

    $('body').on('click','#ReceiveModals',function(){
            
            var IDS = $(this).attr('data-id');
            var UUID = $(this).attr('data-uuid');
            var DOCNUMBER = $(this).attr('data-DOCNUMBER');
            var COMPANY   = $('#COMPANY').val();
            var LINENO    = $(this).attr('data-lineno');
            var YEAR    = $(this).attr('data-year');
            var MONTH    = $(this).attr('data-month');
            $('#id').text(IDS);
            $('#uuid').text(UUID);
            $('#DOCNUMB').text(DOCNUMBER);
            $('#mdocnumber').text(DOCNUMBER);
            $('#mlineno').text(LINENO);
            $('#myear').text(YEAR);
            $('#mmonth').text(MONTH);
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Leasing/viewDetailCompletion'); ?>",
                data: {
                    // COMPANY: COMPANY,
                    ID: UUID,
                    DOCNUMBER:DOCNUMBER
                },
                success: function (response) {
                    $('#loader').removeClass('show');
                     var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };
                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                    const total = ( (intVal(response[0].PENALTY_PERCENTAGE / 100) * intVal(response[0].REMAIN_BASIC_AMOUNT_LEASING)));
                    // console.log(total);
                    $('#from_system').val(numFormat(total));
                },
                error: function (e) {
                    $('#loader').removeClass('show');
                    // console.info(e);
                    alert('Data Save Failed !!');
                    LoadDataTable();
                    $('#btnSave').removeAttr('disabled');
                }
            });
            // $('#noreceiptdoc').val(IDS);
            // alert($('#ModNO_PO').text());
    });

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

    $('body').on('click','#saveModal',function(){
        var ID        = $("#id").text();
        var UUID      = $("#uuid").text();
        var COMPANY   = $('#COMPANY').val(); //$(this).attr('data-company');
        var DOCNUMBER = $("#mdocnumber").text();
        var LINENO    = $("#mlineno").text();
        var YEAR    = $("#myear").text();
        var MONTH    = $("#mmonth").text();
        var amount   = $('#amount_modal').val();
        if(amount == '' || amount == null){
            toastr.error("Amount Cant Empty");
        }else{
            $('#loader').addClass('show');
            // table2.ajax.reload();
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Leasing/saveLeasingCompletion'); ?>",
                data: {
                    ID: ID,
                    UUID:UUID,
                    COMPANY: COMPANY,
                    DOCNUMBER: DOCNUMBER,
                    // DOCDATE: moment($('#DOCDATE').val()).format('MM-DD-YYYY'),
                    LINENO: LINENO,
                    MONTH:MONTH,
                    TIPE: $('#TIPE').val(),
                    COMDATE: $('#MPERIOD').val(),
                    AMOUNT_WITH_PENALTY:amount,
                    YEAR:YEAR,
                    CBTN:2,
                    USERNAME: USERNAME
                },
                success: function (response) {
                    $('#loader').removeClass('show');
                    if (response.status == 200) {
                        alert(response.result.data);
                        // $('.PAY').attr('disabled',true);
                        $('#amount_modal').val('');
                        LoadDataTable();
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        $('#amount_modal').val('');
                        LoadDataTable();
                    } else {
                        alert(response.result.data);
                        $('#amount_modal').val('');
                    }
                },
                error: function (e) {
                    $('#loader').removeClass('show');
                    // console.info(e);
                    alert('Data Save Failed !!');
                    LoadDataTable();
                    $('#btnSave').removeAttr('disabled');
                }
            });
        }
        
    });

    $('body').on('click','#COMPLETION',function(){
        var ID        = $(this).attr('data-id');
        var COMPANY   = $('#COMPANY').val(); //$(this).attr('data-company');
        var DOCNUMBER = $(this).attr('data-docnumber');
        var LINENO    = $(this).attr('data-lineno');
        var YEAR      = $(this).attr('data-year');
        var MONTH     = $(this).attr('data-month');
        
        $('#loader').addClass('show');
            // table2.ajax.reload();
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Leasing/saveLeasingCompletion'); ?>",
                data: {
                    UUID: ID,
                    COMPANY: COMPANY,
                    DOCNUMBER: DOCNUMBER,
                    // DOCDATE: moment($('#DOCDATE').val()).format('MM-DD-YYYY'),
                    LINENO: LINENO,
                    MONTH:MONTH,
                    TIPE: $('#TIPE').val(),
                    YEAR:YEAR,
                    CBTN:1,
                    USERNAME: USERNAME
                },
                success: function (response) {
                    $('#loader').removeClass('show');
                    if (response.status == 200) {
                        alert(response.result.data);
                        // $('.PAY').attr('disabled',true);
                        LoadDataTable();
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        LoadDataTable();
                    } else {
                        alert(response.result.data);
                    }
                },
                error: function (e) {
                    $('#loader').removeClass('show');
                    // console.info(e);
                    alert('Data Save Failed !!');
                    LoadDataTable();
                    $('#btnSave').removeAttr('disabled');
                }
            });
    });

</script>

<script type="text/javascript">
    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            formatCurrency($(this), "blur");
        }
    });
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
            //            right_side = formatNumber(right_side);
            right_side = formatDesimal(right_side);
            //            if (blur === "blur") {
            //                right_side += "00";
            //            }
            //            right_side = right_side.substring(0, 2);
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
</script>