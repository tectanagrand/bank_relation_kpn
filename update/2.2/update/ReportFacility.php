<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Report Facility</li>
</ol>
<h1 class="page-header">Report Facility</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Report Facility</h4>
    </div>
    <div class="panel-body">
        <div class="row-12">
            <div class="row-12">
                <div class="col-md-2 pull-left">
                    <label for="COMPANY">Company</label>
                    <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                        <option value="" disabled="" selected="">--Select Company--</option>
                        <?php
                            echo "<option value='0'>All Company</option>";
                            foreach ($DtCompany as $values) {
                                echo '<option value="' . $values->ID . '">' . $values->COMPANYNAME . ' - ' . $values->COMPANYCODE . '</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row-12">
                <div class="col-md-2 pull-left">
                    <label for="BANK">Bank</label>
                    <select class="form-control" name="BANK" id="BANK" required>
                        <option value="" disabled="" selected="">--Select Bank--</option>
                    </select>
                </div>
            </div>
            <div class="row-12">
                <div class="col-md-2 pull-left">
                    <label for="CREDITTYPE">Credit Type</label>
                    <select class="form-control mkreadonly" name="CREDIT_TYPE" id="CREDIT_TYPE">
                        <option value="" disabled="" selected="">--Select Credit Type--</option>
                        <option value="KMK">KMK</option>
                        <option value="KI">KI</option>
                    </select>
                </div>
            </div>
            <!-- <div class="row-12">
                <div class="col-md-2 pull-left">
                    <button id="btnExport" type="button" class="btn btn-success btn-sm mt-4"><i class="fa fa-file-excel"></i><span> Export</span></button>
                </div>
            </div> -->
        </div>
        <div class="row m-0 table-responsive">
            <table id="DtFacilityRpt" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                <thead>
                    <tr role="row">
                    <th class="text-center sorting_asc" style="width: 30px;">No</th>
                        <th class="text-center sorting">PK Number</th>
                        <th class="text-center sorting">Contract Number</th>
                        <th class="text-center sorting">Company</th>
                        <th class="text-center sorting">Credit Type</th>
                        <th class="text-center sorting">Sub Credit Type</th>
                        <th class="text-center sorting_disabled" aria-label="Action">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="modal fade" id="ExportReportKMK" aria-labelledby="ExportReportKMK" role="dialog" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ExportKMKTitle">Contract Num - <span id="ContractNumRep"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="ExportKMKMod">
                            <div class="row ">
                                <div class="col-12 form-group">
                                    <label for="BEGBAL">Report Beginning Balance</label>
                                    <input type="text" class="form-control" name="BEGBAL" id="BEGBAL" data-type="currency"/>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-6 form-group">
                                    <button type="button" class='btn btn-primary' id="ExportKMKRep">Export Report</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
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
    var COMPANY = 0;
    var SUBCREDITTYPE = 0;
    var FDATE = 0;
    var TDATE = 0;
    var DtFacilityRpt = [];
    var Data = [] ;

    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('#DtFacilityRpt')) {
        $('#DtFacilityRpt').DataTable({
            dom: 'bfrtip',
            "deferRender":true,
            "buttons": [{
                extend: "excel",
                title: 'Data Outstanding Elog',
                className: "btn-xs btn-green mb-2",
                text: 'Export To Excel'
            }],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('ReportFacility/ShowData') ?>",
                "type": "POST",
                "datatype": "JSON",
                "data": function (d) {
                    d.COMPANY  = COMPANY;
                    d.SUBCREDITTYPE = SUBCREDITTYPE;
                    // d.SUBCREDITTYPE = 'KI' ;
                    d.FDATE = FDATE;
                    d.TDATE = TDATE;
                },
                "dataFilter" : function(data) {
                    var json = jQuery.parseJSON( data );
                    json.recordsTotal = json.result.data.recordsTotal;
                    json.recordsFiltered = json.result.data.recordsFiltered;
                    json.data = json.result.data.data;
        
                    return JSON.stringify( json ); // return JSON string
                },
            },
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading..n.</span> '
            },
            "columns": [{
                "data": "ID"
            },
            {"data": "PK_NUMBER"},
            {"data": "CONTRACT_NUMBER"},
            {"data": "COMPANYNAME"},
            {"data": "CREDIT_TYPE"},
            {"data": "SUB_CREDIT_TYPE"},
            {
                "data": null,
                "className": "text-center",
                "orderable": false,
                render: function (data, type, row, meta) {
                    var html = '';
                    if (EDITS == 1) {
                        // html += '<div><button class="mb-2 btn btn-info btn-icon btn-circle btn-sm view" title="view data" data-id="'+data.UUID+'"><i class="fa fa-pencil-alt"></i></button>';
                        html += '<div><button class="mb-2 btn btn-info btn-icon btn-circle btn-sm export" title="export data" data-id="'+data.UUID+'"><i class="fa fa-file-excel"></i></button>';
                    }
                    return html;
                }
            }],
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
            "bLengthChange": true,
            "bInfo": true,
            "columnDefs": [{
                responsivePriority: 1,
                targets: 0
            },
            {
                responsivePriority: 2,
                targets: 1
            },
            {
                responsivePriority: 3,
                targets: -1
            }]
        });
        table = $('#DtFacilityRpt').DataTable();

        table.on('click', '.export', function () {
            var tr = $(this).closest('tr') ;
            var data = table.row(tr).data();
            console.log(data);
            console.log($('#PERIODFROM').val());
            // if($('#PERIODFROM').val() == null || $('#PERIODTO').val() == null || $('#PERIODFROM').val() == "" || $('#PERIODTO').val() == "")
            // {
            //     toastr.error('Date Period is Empty')
            // }
            // else 
            // {
                if(data['KI_TYPE'] == 'SINGLE') {
                    var url = "<?php echo site_url('Kmk/ExportReportKI'); ?>?UUID=PARAM1&PK_NUMBER=PARAM2&START_PERIOD=PARAM3&END_PERIOD=PARAM4";
                        url = url.replace("PARAM1", data['UUID']);
                        url = url.replace("PARAM2", data['PK_NUMBER']);
                        url = url.replace("PARAM3", FDATE);
                        url = url.replace("PARAM4", TDATE);
                        // url = url.replace("PARAM2", DOCNUM);
                        window.open(url, '_blank');
                }
                else if (data['KI_TYPE'] == 'SYNDICATION') {
                    var url = "<?php echo site_url('Kmk/ExportReportKI_SYD'); ?>?UUID=PARAM1&PK_NUMBER=PARAM2&START_PERIOD=PARAM3&END_PERIOD=PARAM4";
                        url = url.replace("PARAM1", data['UUID']);
                        url = url.replace("PARAM2", data['PK_NUMBER']);
                        url = url.replace("PARAM3", FDATE);
                        url = url.replace("PARAM4", TDATE);
                        // url = url.replace("PARAM2", DOCNUM);
                        window.open(url, '_blank');
                }
                else if(data['CREDIT_TYPE'] == 'KMK') {
                    $('#ExportReportKMK').modal('show');
                    Data['UUID'] = data['UUID'];
                    Data['CONTRACT_NUMBER'] = data['CONTRACT_NUMBER'];
                    Data['PK_NUMBER'] = data['PK_NUMBER'] ;
                    $('#ContractNumRep').html(data['CONTRACT_NUMBER']);
                    $('#BEGBAL').val('');
                }
            // }
        });
    }
    });
    
    $('body').on('click', '#ExportKMKRep', function() {
        Data['LAST_BAL'] = formatDesimal($('#BEGBAL').val());
        var url = "<?php echo site_url('Kmk/ExportReportKMK'); ?>?CONTRACT_NUMBER=PARAM1&PK_NUMBER=PARAM2&UUID=PARAM3&START_PERIOD=PARAM4&END_PERIOD=PARAM5&BEGBAL=PARAM6";
            url = url.replace("PARAM1", Data['CONTRACT_NUMBER']);
            url = url.replace("PARAM2", Data['PK_NUMBER']);
            url = url.replace("PARAM3", Data['UUID']);
            url = url.replace("PARAM4", FDATE);
            url = url.replace("PARAM5", TDATE);
            url = url.replace("PARAM6", Data['LAST_BAL']);
            // url = url.replace("PARAM2", DOCNUM);
            console.log(url);
            window.open(url, '_blank');
    }) ;

    $('#COMPANY').on({
        'change': function() {
            COMPANY = $('#COMPANY option:selected').val();
            table.ajax.reload();
            $('#loader').addClass('show');
            $('#BANK').find('option:not(:first)').remove().end().val('');
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Kmk/DtBankCompany'); ?>",
                data: {
                    COMPANY: COMPANY
                },
                "dataSrc": function (ext) {
                    if (ext.status == 200) {
                        // sendAll = ext.result.data.data;
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
                success: function(response, textStatus, jqXHR) {
                    $('#loader').removeClass('show');
                    if (response.status == 200) {
                        var html = '';
                        DValue = '';
                        $.each(response.result.data, function(index, value) {
                            var CValue = JSON.stringify({
                                "BANKCODE": value.FCCODE,
                                "BANKNAME": value.FCNAME
                            });
                            if (value.ISDEFAULT == "1") {
                                DValue = CValue;
                                html += "<option value='" + CValue + "'>" + value.BANKACCOUNT + ' - ' + value.FCNAME + ' - ' + value.CURRENCY + ' (Default) </option>';
                            } else {
                                html += "<option value='" + CValue + "'>" + value.BANKACCOUNT + ' - ' + value.FCNAME + ' - ' + value.CURRENCY + '</option>';
                            }
                        });
                        $(html).insertAfter("#BANK option:first");
                        $("#BANK").val(DValue);
                        $("#BANK").change();
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        location.reload();
                    } else {
                        alert(response.result.data);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#loader').removeClass('show');
                    alert('Please Check Your Connection !!!');
                }
            });
        }
    });

    $('#CREDIT_TYPE').on({
        'change': function() {
            SUBCREDITTYPE = $('#CREDIT_TYPE option:selected').text();
            table.ajax.reload();
        }
    });

    $('#PERIODFROM').datepicker({
        "autoclose": true,
        "todayHighlight": true
    });
    

    $('#PERIODTO').datepicker({
        "autoclose": true,
        "todayHighlight": true
    });

    $('#PERIODFROM').on({
        'change': function () {
            FDATE = $(this).val();
            table.ajax.reload();
        }
    });

    $('#PERIODTO').on({
        'change': function () {
            TDATE = $(this).val();
            table.ajax.reload();
        }
    });

    $('#btnExport').on({
        'click': function() {
            $('#FExport').parsley().reset();
            $("#MExport").modal({
                backdrop: 'static',
                keyboard: false
            });
        }
    });

    var Export = function(type) {
        if ($('#FExport').parsley().validate()) {
            
                var url = "<?php echo site_url('Elog/ExportFirst'); ?>?FROMDATE=PARAM3&TODATE=PARAM4&DEPT=PARAM7";
                var FROMDATE = $('#FROMDATE').val();
                var TODATE   = $('#TODATE').val();
                url = url.replace("PARAM7", $("#DEPT").val());
                url = url.replace("PARAM3", FROMDATE);
                url = url.replace("PARAM4", TODATE);

            
            window.open(url, '_blank');
        }
    }
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
        // console.log(input_val);
        // console.log(input_val.indexOf('.')); 
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

    function fCurrency(n, curr = '') {
        if (n.indexOf(".") >= 0) {
            var decimal_pos = n.indexOf(".");
            var left_side = n.substring(0, decimal_pos);
            var right_side = n.substring(decimal_pos);
            left_side = formatNumber(left_side);
            right_side = formatNumber(right_side);
            right_side += "00";
            right_side = right_side.substring(0, 2);
            n = left_side + "." + right_side;
        } else {
            n = formatNumber(n);
            n += ".00";
          
        }
        if(curr != '') {
            n = curr +" "+ n ;
        } 
        return n ;
    }

</script>