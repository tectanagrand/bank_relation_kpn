<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<link href="./assets/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script src="./assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="./assets/plugins/DataTables/extensions/Buttons/js/jszip.min.js"></script>
<script src="./assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
<script src="./assets/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js"></script>
<style>
    .filter-option-inner-inner{
        color: black;
    }
</style>
<!-- <ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">B2B REVERSAL</a></li>
    <li class="breadcrumb-item active">B2B REVERSAL</li>
</ol> -->
<!-- <h1 class="page-header">Znego</h1> -->
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">B2B REVERSAL</h4>
    </div>
    <div class="panel-body">
        <?php if (empty($_GET)) { ?>
            <div class="row mb-2">
                <div>
                    <button id="clearSelect" type="button" class="btn btn-success btn-sm"><span> Clear Select</span></button>
                </div>
                <div class="col-md-4">
                    <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                                            <option value="">All Company</option>
                                            <?php
                                            foreach ($DtCompany as $values) {
                                                echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                            }
                                            ?>
                                        </select>
                </div>
                <div>
                    <button type="button" class="btn btn-info" style="padding: 3px 10px;" onclick="searchCashflow()">Show</button>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" id="search" name="search" class="form-control" placeholder="Cari.." >
                    </div>
                </div>
                <div class="col pull-right">
                    <div class="input-group">
                        <button type="submit" class="btn btn-primary btn-sm sendAll" style="">Send All</button>
                    </div>
                </div>
            </div>
            <div class="row m-0 table-responsive">
                <table id="DtElog" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" width="100%" aria-describedby="DtElog_info" style="width: 100%;">
                    <thead>
                        <tr role="row">
                            <th class="text-center" data-orderable="false">Company</th>
                            <th class="text-center" data-orderable="false">Bu</th>
                            <th class="text-center" data-orderable="false">Doctype</th>
                            <th class="text-center" data-orderable="false">Docdate</th>
                            <th class="text-center" data-orderable="false">Dept</th>
                            <th class="text-center" data-orderable="false">Vendor</th>
                            <th class="text-center" data-orderable="false">Docnumber</th>
                            <th class="text-center" data-orderable="false">Znego No</th>
                            <th class="text-center" data-orderable="false">Amount</th>
                            <th class="text-center sorting_disabled" data-orderable="false" aria-label="Action">#</th>
                            <th class="text-center sorting_disabled"><input type="checkbox" id="pil"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        <?php } ?>
    </div>
    <!-- modal view  -->
    <div class="modal fade" id="MView">
        <div class="modal-dialog modal-lg" style="max-width: 95%  !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">View Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row m-0 table-responsive">
                        <table id="DtElogDetails" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" width="100%" aria-describedby="DtElog_info" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <!-- <th class="text-center sorting_asc" style="width: 30px;">No</th> -->
                                    <th class="text-center sorting_disabled">No</th>
                                    <!-- <th class="text-center sorting">Receipt Doc</th> -->
                                    <th class="text-center sorting_disabled">Invoice Code</th>
                                    <th class="text-center sorting_disabled">No PO</th>
                                    <th class="text-center sorting_disabled">Vendor</th>
                                    <th class="text-center sorting_disabled">Currency</th>
                                    <th class="text-center sorting_disabled">Amount</th>
                                    <th class="text-center sorting_disabled" data-orderable="false">Voucherno</th>
                                    <th class="text-center sorting_disabled" data-orderable="false">Dpp</th>
                                    <th class="text-center sorting_disabled" data-orderable="false">Pph</th>
                                    <th class="text-center sorting_disabled" data-orderable="false">Ppn</th>
                                    <th class="text-center sorting_disabled" data-orderable="false">Net</th>
                                    <th class="text-center sorting_disabled">Dept</th>
                                    <th class="text-center sorting_disabled">Updated By</th>
                                    <th class="text-center sorting_disabled">Send To</th>
                                    <th class="text-center sorting_disabled">Date</th>
                                    <th class="text-center sorting_disabled">Remarks</th>
                                    <th class="text-center sorting_disabled">Status</th>
                                    <!-- <th class="text-center sorting_disabled" aria-label="Action">#</th> -->
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary xModal" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php if (!empty($_GET)) { ?>
        <div class="panel-footer text-left">
            <button type="button" id="btnSave" onclick="SaveMaster()" class="btn btn-primary btn-sm m-l-5">Save</button>
            <button type="button" class="btn btn-warning btn-sm m-l-5" onclick="Cancel()">Cancel</button>
        </div>
    <?php } ?>
</div>

<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var DEPT = "<?php echo $SESSION->DEPARTMENT; ?>";

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
    var table, ACTION, ID;
    var COMPANY;
    var DtData = [];
    $(document).ready(function () {
        $('body').on('click','.sendAll',function(){

            $(this).prop('disabled', true); 
            $('#loader').addClass('show');
            $.each(table.data(), function (index, value) {
                //  var ins = $('#DtElog').find("tbody select").map(function() {

                //     // return $(this).find(":selected").val() // get selected text
                //     return $(this).val() // get selected value

                // }).get()

                //  alert(ins);
                
                if (value.ID == undefined || value.ID == null || value.ID == '') {
                } else {

                    if (value.FLAG == "1" || value.FLAG == 1) {

                        DtData.push(value);

                    }
                }
            });
            $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Znego/sendAll'); ?>",
                    data: {
                       DtData: DtData,
                       USERNAME:USERNAME
                    },
                    success: function (response) {
                        //$("#page-loader").addClass('d-none');
                        
                        if (response.status == 200) {
                            alert(response.result.data);
                            table.ajax.reload();
                        } else if (response.status == 504) {
                            alert(response.result.data);
                            table.ajax.reload();
                        } else {
                            alert(response.result.data);
                        }
                    },
                    error: function (e) {
                        //$("#page-loader").addClass('d-none');
                        // console.info(e);
                        alert('Data Save Failed !!');   
                        $('#loader').removeClass('show');
                    }
                });
            DtData = [];
            table.ajax.reload();
            $(this).prop('disabled', false);
            $('#loader').removeClass('show');
        });

    });

        // $(".selectpicker").selectpicker({
        //     noneSelectedText : 'Please Select Company' // by this default 'Nothing selected' -->will change to Please Select
        // });
    
        // $('#clearSelect').on({
        //     'click': function() {
        //     $(".selectpicker").selectpicker('deselectAll');
        //     }
        // });

        var searchCashflow = function () {
            // if ($('#COMPANY').val() == '' ||  $('#COMPANY').val() == null) {
                // alert("cannot be empty!")
                // return false
            // } else {
                COMPANY = $('#COMPANY').val();
                loadData();
            // }
        }

        function loadData(){
            if (!$.fn.DataTable.isDataTable('#DtElog')) {
                $('#DtElog').DataTable({
                     dom: 'Bfrtip',
                    "deferRender":true,
                    "buttons": [{
                            extend: "excel",
                            title: 'Data Outstanding Elog',
                            className: "btn-xs btn-green mb-2",
                            text: 'Export To Excel'
                        }],
                    "processing": true,
                    "serverSide":true,
                    "ajax": {
                        "url": "<?php echo site_url('Znego/showDataReversal') ?>",
                        "datatype": "JSON",
                        "type": "POST",
                        "data": function (d) {
                            d.COMPANY  = COMPANY;
                            // d.SUBGROUP = SUBGROUP;
                            // d.sDATE = moment(sDate).format('MM-DD-YYYY');
                        },
                        "dataSrc": function (ext) {
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
                        },
                        "beforeSend": function() {
                            $('#loader').addClass('show');
                        },
                        "complete": function() {
                            $('#loader').removeClass('show');
                        }
                    },
                    "columns": [
                        {"data": "COMPANYCODE"},
                        {"data": "BUSINESSUNITCODE"},
                        {"data": "DOCTYPE"},
                        {"data": "DOCDATE"},
                        {"data": "DEPARTMENT"},                            
                        {"data": "VENDORNAME"},
                        {"data": "DOCNUMBER"},
                        {"data": "NEGO_NO"},
                        {
                            "data": "AMOUNT_INCLUDE_VAT",
                            "className": "text-right",
                            render: $.fn.dataTable.render.number(',', '.', 2)
                        },
                        {
                            "data": null,
                            "className": "text-center",
                            "orderable": false,
                            render: function(data, type, row, meta) {
                                var html = '';
                                
                                html += '<button class="btn btn-info btn-sm Send" data-id="'+data.ID+'" data-docnumber="'+data.DOCNUMBER+'" id="Send" title="Forecast">Forecast</button>';
                                    
                                return html;
                            }
                        },
                        {
                            "data": null,
                            "className": "text-center align-middle",
                            "orderable": false,
                            render: function (data, type, row, meta) {
                                var html = '<input type="checkbox" name="pils" class="pils" data-id="'+data.ID+'">';
                                return html;
                            }
                        }
                    ],
                    // responsive: {
                    //     details: {
                    //         renderer: function (api, rowIdx, columns) {
                    //             var data = $.map(columns, function (col, i) {
                    //                 return col.hidden ?
                    //                 '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                    //                 '<td>' + col.title + '</td> ' +
                    //                 '<td>:</td> ' +
                    //                 '<td>' + col.data + '</td>' +
                    //                 '</tr>' :
                    //                 '';
                    //             }).join('');
                    //             return data ? $('<table/>').append(data) : false;
                    //         }
                    //     }
                    // },
                    "bFilter": true,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bInfo": true
                });
                $('#DtElog thead th').addClass('text-center');
                table = $('#DtElog').DataTable();
                table.on('click', '.view', function () {
                    var UUID = $(this).attr('data-id');
                    $('#loader').addClass('show');
                    $.ajax({
                            dataType: "JSON",
                            type: "POST",
                            url: "<?php echo site_url('Elog/viewDetail'); ?>",
                            data: {
                                ID: UUID},
                            success: function (response) {
                                $('#loader').removeClass('show');
                                $('#VCOMPANYNAME').val(response[0].COMPANYNAME);
                                $('#VDEPARTMENT').val(response[0].DEPARTMENT);
                                $('#VDOCNUMBER').val(response[0].DOCNUMBER);
                                $('#VDOCDATE').val(response[0].DOCDATE);
                                $('#VVENDOR').val(response[0].VENDORNAME);
                                $("#MView").modal({
                                    backdrop: 'static',
                                    keyboard: false
                                });
                            },
                            error: function (e) {
                                $('#loader').removeClass('show');
                                alert('Error Get data !!');
                            }
                        });
                });
                table.on('change', '.pils', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    if (this.checked) {
                        // if($(this).closest("tr").find("#DEPARTMENT").val() == 'Choose'){
                            // alert('Please Choose DEPARTMENT for '+ data.INVOICE_CODE);
                        // }else{
                            data.FLAG  = "1";
                            console.log(data)
                            // data.DEPARTMENT = $(this).closest("tr").find("#DEPARTMENT").val();
                            // data.REMARKS = $(this).closest("tr").find("#valREMARKS").val();    
                        // }
                    } else {
                        data.FLAG = "0";
                    }
                });
                $("#DtElog_filter").remove();
                // $("#search").on({
                //     'keyup': function () {
                //         table.search(this.value, true, false, true).draw();
                //     }
                // });
                $('#search').keyup(delay(function (e) {
                  table.search(this.value, true, false, true).draw();
                }, 500));

                function delay(fn, ms) {
                  let timer = 0
                  return function(...args) {
                    clearTimeout(timer)
                    timer = setTimeout(fn.bind(this, ...args), ms || 0)
                  }
                }
            }else{
                table.ajax.reload();
            }
        }

        $('#pil').on('change', function () {
            if (this.checked) {
                $('#DtElog .pils').prop("checked", true);
            } else {
                $('#DtElog .pils').prop("checked", false);
            }
            $('#DtElog .pils').change();
        });
        
        $('body').on('click','#Send',function(){

            
            var cfid = $(this).attr('data-id');
            var DOCNUMBER = $(this).attr('data-docnumber');
           
            $('#loader').addClass('show');
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Znego/sendReversal'); ?>",
                data: {
                    CFID: cfid,
                    DOCNUMBER:DOCNUMBER,
                    USERNAME: USERNAME
                },
                success: function (response) {
                    $('#loader').removeClass('show');
                    if (response.status == 200) {
                        alert(response.result.data);
                        // $('.PAY').attr('disabled',true);
                        table.ajax.reload();
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        location.reload();
                    } else {
                        alert(response.result.data);
                    }
                    
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

    var Add = function () {
        window.location.href = window.location.href + '?type=add';
    };
    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }

    $('body').on('click','.btnView',function(){
        $("#DtElogDetails").dataTable().fnDestroy();
        var COMP = $(this).attr('data-company');
        var INV  = $(this).attr('data-no_inv');
        var NO_RECEIPT_DOC   = $(this).attr('data-norec');
        $('#loader').addClass('show');
        
        $('#DtElogDetails').DataTable({
            dom: 'Bfrtip',
                    "buttons": [{
                            extend: "excel",
                            title: 'Data History Elog',
                            className: "btn-xs btn-green mb-2",
                            text: 'Export To Excel'
                        }],
                "processing": true,
                "bDestroy": true,
                "retrieve": true,
                "ajax": {
                    "url": "<?php echo site_url('Elog/getHistoryDoc'); ?>",
                    "datatype": "JSON",
                    "type": "POST",
                    "data": function (d) {
                        d.COMPANY = COMP;
                        d.INVOICE_CODE = INV;
                        d.NO_RECEIPT_DOC = NO_RECEIPT_DOC;
                    },
                    "dataSrc": function (ext) {
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
                "columns": [
                {
                    "data": null,
                    "className": "text-center",
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {"data": "INVOICE_CODE"},
                {"data": "NO_PO"},
                {"data": "VENDORNAME"},
                {"data": "CURRENCY"},
                {
                    "data": "AMOUNT",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {"data": "VOUCHERNO"},
                    {
                        "data": "DPP",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNT_PPN",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNT_PPH",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNT_NET",
                        "className": "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                {"data": "DEPT"},
                {"data": "UPDATED_BY"},
                {"data": "SEND_TO"},
                {"data": "DATE_RECEIPT"},
                {"data": "REMARK"},
                {
                    "data": null,
                    "className": "text-center",
                    "orderable": false,
                        render: function (data, type, row, meta) {
                            var html = '';
                            if(data.POS == 1){
                                html += '<span>Created</span>';
                            }
                            if(data.POS == 2){
                                html += '<span>Sent</span>';
                            }
                            if(data.POS == 3){
                                html += '<span>Received</span>';
                            }
                            if(data.POS == 4){
                                html += '<span>Received and Closed</span>';
                            }
                            return html;
                        }
                }
                ],
                responsive: {
                    details: {
                        renderer: function (api, rowIdx, columns) {
                            var data = $.map(columns, function (col, i) {
                                return col.hidden ?
                                '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                                '<td>' + col.title + '</td> ' +
                                '<td>:</td> ' +
                                '<td>' + col.data + '</td>' +
                                '</tr>' :
                                '';
                            }).join('');
                            return data ? $('<table/>').append(data) : false;
                        }
                    }
                },
                "bFilter": true,
                "bPaginate": true,
                "bLengthChange": false,
                "bInfo": true
        });
        $('#DtElogDetails thead th').addClass('text-center');
        $('#loader').removeClass('show');
        $('#MView').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

</script>
<!-- formatting -->
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