<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Forecast vs Actual</li>
</ol>
<h1 class="page-header">Forecast vs Actual</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Forecast vs Actual</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="COMPANYGROUP">Company Group *</label>
                        <select class="form-control mkreadonly" name="COMPANYGROUP" id="COMPANYGROUP">
                            <option value="">All Company Group</option>
                            <?php
                            foreach ($Dtcompanygroup as $values) {
                                echo '<option value=' . $values->FCCODE . '>' . $values->FCNAME . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="COMPANYSUBGROUP">Company Sub Group *</label>
                        <select class="form-control mkreadonly" name="COMPANYSUBGROUP" id="COMPANYSUBGROUP">
                            <option value="">All Company Sub Group</option>
                            <!-- <?php
                                    foreach ($Dtcompanysubgroup as $values) {
                                        echo '<option value=' . $values->FCCODE . '>' . $values->FCNAME . '</option>';
                                    }
                                    ?> -->
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="COMPANY">Company *</label>
                        <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                            <option value="0">All Company</option>
                            <!-- <?php
                                    foreach ($DtCompany as $values) {
                                        echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                    }
                                    ?> -->
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col my-auto">
                        <button onclick="VExport()" class="btn btn-success" style="padding: 3px 10px;"><i class="fa fa-file-excel"></i> Export</button>
                    </div>
                    <div class="col">
                        <label for="DEPARTMENT">Departement</label>
                        <select class="form-control" name="DEPARTMENT" id="DEPARTMENT">
                            <option value="" selected>All Department</option>
                            <?php
                            foreach ($departement as $values) {
                                echo '<option value=' . $values->DEPARTMENT . '>' . $values->DEPARTEMENTNAME . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="PERIOD">Period</label>
                        <input type="text" class="form-control" name="PERIOD" id="PERIOD" placeholder="MMM YYYY" autocomplete="off">
                    </div>
                    <div class="col">
                        <label for="searchInvoice">Search</label>
                        <input type="text" id="searchInvoice" name="searchInvoice" class="form-control" placeholder="Cari..">
                    </div>
                </div>
            </div>
        </div>
        <div class="row ml-0 mr-0 mb-0 mt-2 table-responsive">
            <table id="DtInvoice" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                <thead>
                    <tr role="row">
                        <th class="text-center sorting_asc align-middle" aria-sort="ascending" style="" rowspan="2">No</th>
                        <th class="text-center sorting align-middle" rowspan="2">Company</th>
                        <th class="text-center sorting align-middle" rowspan="2">Business Unit</th>
                        <th class="text-center sorting align-middle" rowspan="2">Department</th>
                        <th class="text-center sorting align-middle" rowspan="2">Doc Type</th>
                        <th class="text-center sorting align-middle" rowspan="2">Doc Number</th>
                        <th class="text-center sorting align-middle" rowspan="2">Doc Date</th>
                        <th class="text-center sorting align-middle" rowspan="2">Doc Reference</th>
                        <th class="text-center sorting align-middle" rowspan="2">Vendor</th>
                        <th class="text-center sorting align-middle" rowspan="2">Due Date</th>
                        <th class="text-center sorting align-middle" rowspan="2">Amount Doc</th>

                        <th class="text-center sorting" colspan="5">Requested</th>
                        <th class="text-center sorting" colspan="5">Adjusted</th>
                        <th class="text-center sorting" colspan="5">Paid</th>
                    </tr>
                    <tr>
                        <th>W1</th>
                        <th>W2</th>
                        <th>W3</th>
                        <th>W4</th>
                        <th>W5</th>
                        <th>W1</th>
                        <th>W2</th>
                        <th>W3</th>
                        <th>W4</th>
                        <th>W5</th>
                        <th>W1</th>
                        <th>W2</th>
                        <th>W3</th>
                        <th>W4</th>
                        <th>W5</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr role="row">
                        <th class="text-right" colspan="10">Total :</th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<!-- Modal MExport -->
<div class="modal fade" id="MExport" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Export Data</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            </div>
            <form id="FExport" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="COMPANYGROUP">Company Group *</label>
                                    <select class="form-control mkreadonly" name="ECOMPANYGROUP" id="ECOMPANYGROUP">
                                        <option value="">All Company Group</option>
                                        <?php
                                        foreach ($Dtcompanygroup as $values) {
                                            echo '<option value=' . $values->FCCODE . '>' . $values->FCNAME . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="COMPANYSUBGROUP">Company Sub Group *</label>
                                        <select class="form-control mkreadonly" name="ECOMPANYSUBGROUP" id="ECOMPANYSUBGROUP">
                                            <option value="">All Company Sub Group</option>
                                             <?php
                                                    foreach ($Dtcompanysubgroup as $values) {
                                                        echo '<option value=' . $values->FCCODE . '>' . $values->FCNAME . '</option>';
                                                    }
                                                    ?> 
                                        </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="ECOMPANY">Company</label>
                                    <select class="form-control mkreadonly" name="ECOMPANY" id="ECOMPANY">
                                        <option value="0" selected>All Company</option>
                                        <?php
                                        foreach ($DtCompany as $values) {
                                            echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="EDEPARTMENT">Department</label>
                                    <select class="form-control" name="EDEPARTMENT" id="EDEPARTMENT">
                                        <option value="" selected>All Department</option>
                                        <?php
                                        foreach ($departement as $values) {
                                            echo '<option value=' . $values->DEPARTMENT . '>' . $values->DEPARTEMENTNAME . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="EPERIOD">Period</label>
                                    <input type="text" class="form-control" name="EPERIOD" id="EPERIOD" placeholder="MMM YYYY" required autocomplete="off">
                                </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="Export('EXCEL')"><i class="fa fa-file-excel"></i> Excel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var table2;
    var YEAR = '',
        MONTH = '',
        DEPARTMENT = '',
        COMPANY = '',
        COMPANYGROUPNAME = '',
        COMPANYSUBGROUP = '',
        FORECASTID;
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    //Load Data Table Awal
    if (!$.fn.DataTable.isDataTable('#DtInvoice')) {
        $('#DtInvoice').DataTable({
            "processing": true,
            "ajax": {
                "url": "<?php echo site_url('ReportFtP/ShowData') ?>",
                "type": "POST",
                "datatype": "JSON",
                "data": function(d) {
                    d.YEAR = YEAR;
                    d.MONTH = MONTH;
                    d.DEPARTMENT = DEPARTMENT;
                    d.COMPANY = COMPANY;
                    d.COMPANYGROUPNAME = COMPANYGROUPNAME;
                    d.COMPANYSUBGROUP = COMPANYSUBGROUP;
                    d.USERNAME = USERNAME;
                },
                "dataSrc": function(ext) {
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
            "columns": [{
                    "data": null,
                    "className": "text-center",
                    render: function(data, type, row, meta) {
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
                    "data": "DOCNUMBER"
                },
                {
                    "data": "DOCDATE"
                },
                {
                    "data": "DOCREF"
                },
                {
                    "data": "VENDORNAME"
                },
                {
                    "data": "DUEDATE"
                },
                {
                    "data": "AMOUNTINV",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "REQUESTW1",
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        var html = fCurrency(data);
                        if ((parseFloat(row.ADJSW1) <= 0 && parseFloat(row.ADJSW2) <= 0 && parseFloat(row.ADJSW3) <= 0 && parseFloat(row.ADJSW4) <= 0 &&
                                parseFloat(row.ADJSW5) <= 0) && parseFloat(data) != 0 && row.ISACTIVE == 2) {
                            html = '<div class="text-danger">' + html + '</div>';
                        }
                        return html;
                    }
                },
                {
                    "data": "REQUESTW2",
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        var html = fCurrency(data);
                        if ((parseFloat(row.ADJSW1) <= 0 && parseFloat(row.ADJSW2) <= 0 && parseFloat(row.ADJSW3) <= 0 && parseFloat(row.ADJSW4) <= 0 &&
                                parseFloat(row.ADJSW5) <= 0) && parseFloat(data) != 0 && row.ISACTIVE == 2) {
                            html = '<div class="text-danger">' + html + '</div>';
                        }
                        return html;
                    }
                },
                {
                    "data": "REQUESTW3",
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        var html = fCurrency(data);
                        if ((parseFloat(row.ADJSW1) <= 0 && parseFloat(row.ADJSW2) <= 0 && parseFloat(row.ADJSW3) <= 0 && parseFloat(row.ADJSW4) <= 0 &&
                                parseFloat(row.ADJSW5) <= 0) && parseFloat(data) != 0 && row.ISACTIVE == 2) {
                            html = '<div class="text-danger">' + html + '</div>';
                        }
                        return html;
                    }
                },
                {
                    "data": "REQUESTW4",
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        var html = fCurrency(data);
                        if ((parseFloat(row.ADJSW1) <= 0 && parseFloat(row.ADJSW2) <= 0 && parseFloat(row.ADJSW3) <= 0 && parseFloat(row.ADJSW4) <= 0 &&
                                parseFloat(row.ADJSW5) <= 0) && parseFloat(data) != 0 && row.ISACTIVE == 2) {
                            html = '<div class="text-danger">' + html + '</div>';
                        }
                        return html;
                    }
                },
                {
                    "data": "REQUESTW5",
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        var html = fCurrency(data);
                        if ((parseFloat(row.ADJSW1) <= 0 && parseFloat(row.ADJSW2) <= 0 && parseFloat(row.ADJSW3) <= 0 && parseFloat(row.ADJSW4) <= 0 &&
                                parseFloat(row.ADJSW5) <= 0) && parseFloat(data) != 0 && row.ISACTIVE == 2) {
                            html = '<div class="text-danger">' + html + '</div>';
                        }
                        return html;
                    }
                },
                {
                    "data": "ADJSW1",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "ADJSW2",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "ADJSW3",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "ADJSW4",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "ADJSW5",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "PAIDW1",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "PAIDW2",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "PAIDW3",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "PAIDW4",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
                {
                    "data": "PAIDW5",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2)
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
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),data;
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };
                // Total over all pages
                totalAmountDoc = api.column(10, { search: 'applied'} ).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                totalReqW1 = api.column(11, { search: 'applied'} ).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                totalReqW2 = api.column(12, { search: 'applied'} ).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                totalReqW3 = api.column(13, { search: 'applied'} ).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                totalReqW4 = api.column(14, { search: 'applied'} ).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                totalReqW5 = api.column(15, { search: 'applied'} ).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                totalAdjW1 = api.column(16, { search: 'applied'} ).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                totalAdjW2 = api.column(17, { search: 'applied'} ).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                totalAdjW3 = api.column(18, { search: 'applied'} ).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                totalAdjW4 = api.column(19, { search: 'applied'} ).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                totalAdjW5 = api.column(20, { search: 'applied'} ).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                totalPaidW1 = api.column(21, { search: 'applied'} ).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                totalPaidW2 = api.column(22, { search: 'applied'} ).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                totalPaidW3 = api.column(23, { search: 'applied'} ).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                totalPaidW4 = api.column(24, { search: 'applied'} ).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                totalPaidW5 = api.column(25, { search: 'applied'} ).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                $(api.column(10).footer()).html(numFormat(totalAmountDoc));
                $(api.column(11).footer()).html(numFormat(totalReqW1));
                $(api.column(12).footer()).html(numFormat(totalReqW2));
                $(api.column(13).footer()).html(numFormat(totalReqW3));
                $(api.column(14).footer()).html(numFormat(totalReqW4));
                $(api.column(15).footer()).html(numFormat(totalReqW5));
                $(api.column(16).footer()).html(numFormat(totalAdjW1));
                $(api.column(17).footer()).html(numFormat(totalAdjW2));
                $(api.column(18).footer()).html(numFormat(totalAdjW3));
                $(api.column(19).footer()).html(numFormat(totalAdjW4));
                $(api.column(20).footer()).html(numFormat(totalAdjW5));
                $(api.column(21).footer()).html(numFormat(totalPaidW1));
                $(api.column(22).footer()).html(numFormat(totalPaidW2));
                $(api.column(23).footer()).html(numFormat(totalPaidW3));
                $(api.column(24).footer()).html(numFormat(totalPaidW4));
                $(api.column(25).footer()).html(numFormat(totalPaidW5));
            }
        });

        table2 = $('#DtInvoice').DataTable();
        $('#DtInvoice thead th').addClass('text-center');
        //Hapus Searching Default Datatable
        $('#DtInvoice_filter').remove();
        $('#searchInvoice').on('input', function() {
            table2.search(this.value).draw();
        });
    }

    //Load Company Sub Group
    // $("#COMPANYGROUP").on('change', function() {
    //     COMPANYSUBGROUP = "";
    //     COMPANYGROUPNAME = $(this).val();;
    //     getDataCompanySubGroup($(this).val());
    //     table2.ajax.reload();
    // })

    $("#COMPANYGROUP").on({
        'change': function() {
            $('#loader').addClass('show');
            var gFCCODE = $(this).val();
            var gFCNAME = $('#COMPANYGROUP option:selected').text();
            if (gFCNAME = 'PLANTATION') {
                COMPANYSUBGROUP = "UPSTREAM";
            } else {
                COMPANYSUBGROUP = gFCNAME;
            }
            $.ajax({
                url: "<?php echo site_url('ReportFtP/getSubGroup'); ?>",
                method: "POST",
                data: {
                    FCCODE: gFCCODE
                },
                async: true,
                dataType: 'json',
                success: function(data) {
                    // console.log(data.result);
                    var listSubGroup = '';
                    var i;
                    listSubGroup += '<option value="0">All Subgroup</option>';
                    for (i = 0; i < data.result.data.length; i++) {
                        listSubGroup += '<option value=' + data.result.data[i].FCCODE + '>' + data.result.data[i].FCNAME + '</option>';
                        $.ajax({
                            url: "<?php echo site_url('ReportFtP/getCompany'); ?>",
                            method: "POST",
                            data: {
                                FCCODE: data.result.data[i].FCCODE
                            },
                            async: true,
                            dataType: 'json',
                            success: function(data2) {
                                // console.log(data.result);
                                var listCompany = '';
                                var i;
                                listCompany += '<option value="0">All Company</option>';
                                for (i = 0; i < data2.result.data.length; i++) {
                                    listCompany += '<option value=' + data2.result.data[i].ID + '>' + data2.result.data[i].COMPANYCODE + ' - ' + data2.result.data[i].COMPANYNAME + '</option>';
                                }
                                $('#COMPANY').html(listCompany);
                                $('#loader').removeClass('show');
                            }
                        });
                    }
                    $('#COMPANYSUBGROUP').html(listSubGroup);
                    $('#loader').removeClass('show');
                },
                error: function (e) {
                            $("#loader").hide();
                            // console.info(e);
                            alert('Load Data Failed !!');
                        }
            });
            return false;
            table2.ajax.reload();
        }
    });

    $("#COMPANYSUBGROUP").on({
        'change': function() {
            $('#loader').addClass('show');
            var gsFCCODE = $(this).val();

            $.ajax({
                url: "<?php echo site_url('ReportFtP/getCompany'); ?>",
                method: "POST",
                data: {
                    FCCODE: gsFCCODE
                },
                async: true,
                dataType: 'json',
                success: function(data) {
                    console.log(data.result);
                    var listCompany = '';
                    var i;
                    for (i = 0; i < data.result.data.length; i++) {
                        listCompany += '<option value=' + data.result.data[i].ID + '>' + data.result.data[i].COMPANYCODE + ' - ' + data.result.data[i].COMPANYNAME + '</option>';
                    }
                    $('#COMPANY').html(listCompany);
                    $('#loader').removeClass('show');

                },
                error: function (e) {
                            $("#loader").hide();
                            // console.info(e);
                            alert('Load Data Failed !!');
                            
                        }
            });
            return false;
            table2.ajax.reload();
        }
    });

    //Load Period
    $('#PERIOD').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "viewMode": "months",
        "minViewMode": "months",
        "format": "M yyyy",
        "orientation": "bottom"
    });
    //Change Period
    $('#PERIOD').on({
        'change': function() {
            MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
            YEAR = this.value.substr(4, 4);
            COMPANYID = $('#COMPANY').val();
            table2.ajax.reload();
        }
    });

    //Change Department
    $('#DEPARTMENT').on({
        'change': function() {
            DEPARTMENT = this.value;
            COMPANYID = $('#COMPANY').val();
            console.info(DEPARTMENT);
            table2.ajax.reload();
        }
    });

    $('#COMPANYGROUP').on({
        'change': function() {
            COMPANYGROUPNAME = this.value;
            table2.ajax.reload();
        }
    });

    $('#COMPANYSUBGROUP').on({
        'change': function() {
            COMPANYSUBGROUP = this.value;
            table2.ajax.reload();
        }
    });

    $('#COMPANY').on({
        'change': function() {
            COMPANY = this.value;
            table2.ajax.reload();
        }
    });

    var VExport = function() {
        $('#FExport').parsley().reset();
        $("#MExport").modal({
            backdrop: 'static',
            keyboard: false
        });
    };

    //    Export Data
    $('#EPERIOD').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "viewMode": "months",
        "minViewMode": "months",
        "format": "M yyyy"
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
            var url = "<?php echo site_url('Process/PaymentExport'); ?>?type=PARAM1&COMPANY=PARAM6&MONTH=PARAM2&YEAR=PARAM3&DEPARTMENT=PARAM4&USERNAME=PARAM5&GROUP=PARAM7&SUBGROUP=PARAM8";
            MONTH = ListBulan.indexOf($('#EPERIOD').val().substr(0, 3)) + 1;
            YEAR = $('#EPERIOD').val().substr(4, 4);
            url = url.replace("PARAM1", type);
            url = url.replace("PARAM2", MONTH);
            url = url.replace("PARAM3", YEAR);
            if ($("#EDEPARTMENT").val() == "" || $("#EDEPARTMENT").val() == null || $("#EDEPARTMENT").val() == undefined) {
                url = url.replace("PARAM4", 'ALL');
            } else {
                url = url.replace("PARAM4", $("#EDEPARTMENT").val());
            }
            url = url.replace("PARAM6", $("#ECOMPANY").val());
            url = url.replace("PARAM7", $("#ECOMPANYGROUP").val());
            url = url.replace("PARAM8", $("#ECOMPANYSUBGROUP").val());
            url = url.replace("PARAM5", USERNAME);
            window.open(url, '_blank');
        }
    }

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

    // var getDataCompanySubGroup = function(COMPANYGROUPNAME, selected = false) {
    //     $('.dataCompanySubGroup').remove()
    //     $.ajax({
    //         url: "<?php echo site_url('ReportFtP/getDataCompanySubGroup'); ?>",
    //         method: "post",
    //         data: {
    //             COMPANYGROUPNAME = COMPANYGROUPNAME
    //         },
    //         success: function(response) {
    //             let data = JSON.parse(response)
    //             let options = ''
    //             data.result.data.forEach(function(value, key) {
    //                 options += '<option class="dataCompanySubGroup" value="' + value.FCCODE + '">' + value.FCNAME + '</option>'
    //             })
    //             $('#COMPANYSUBGROUP').append(options)
    //             if (selected) {
    //                 $('#COMPANYSUBGROUP').val(selected);
    //             }
    //         }
    //     })
    // }
</script>