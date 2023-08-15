<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">E-Log</li>
</ol>
<h1 class="page-header">E-Log Receive Document <?= $this->session->userdata('DEPARTMENT');?></h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">E-Log </h4>
    </div>
    <div class="panel-body">
            <div class="row mb-2">
                <div class="col-md-2">
                    <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                                <option value="0" selected>All Company</option>
                                <?php
                                foreach ($DtCompany as $values) {
                                    echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                }
                                ?>
                            </select>
                </div>
                <div class="col-md-2">
                            <select class="form-control mkreadonly" name="SUBGROUP" id="SUBGROUP">
                                <option value="0">Select Subgroup</option>
                                <option value="UPSTREAM">UPSTREAM</option>
                                <option value="DOWNSTREAM">DOWNSTREAM</option>
                                <option value="PROPERTY">PROPERTY</option>
                            </select>
                        </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="text" id="search" name="search" class="form-control" placeholder="Cari.." >
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <select class="form-control sVendor" id="sVendor" name="sVendor" >
                            <!-- <option disabled selected>Choose</option> -->
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="text" id="PERIOD" name="PERIOD" class="form-control" autocomplete="off" placeholder="mm/dd/yyyy">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                                <button type="submit" class="btn btn-primary receiveAll" style="">Receive All</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <button id="btnExport" type="button" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i><span> Export</span></button>
                    <button class="btn btn-dark btn-sm" id="clearSelect2">Clear Vendor</button>
                </div>
            </div>
            <div class="row m-0 table-responsive">
                <table id="DtElog" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DtElog_info" style="width: 100%;">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc">Company</th>
                            <!-- <th class="text-center sorting_asc">Doc No</th> -->
                            <th class="text-center">No Invoice</th>
                            <th class="text-center">No PO/SPO</th>
                            <th class="text-center">Vendor</th>
                            <th class="text-center">Currency</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Voucherno</th>
                            <th class="text-center">Dpp</th>
                            <th class="text-center">Pph</th>
                            <th class="text-center">Ppn</th>
                            <th class="text-center">Net</th>
                            <th class="text-center" style="width: 20px;">Receive From</th>
                            <th class="text-center">Remarks</th>
                            <th class="text-center sorting">Date</th>
                            <th class="text-center sorting_disabled" aria-label="Action">#</th>
                            <th class="text-center sorting_disabled"><input type="checkbox" id="pil"></th>
                        </tr>
                    </thead>
                </table>
            </div>
    </div>
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
                            <!-- <div class="col-md-12">
                                <?php
                                    $CDepartment = '';
                                    foreach ($DtDepartment as $values) {
                                        $CDepartment .= '<option value="' . $values->DEPARTMENT . '">' . $values->DEPARTEMENTNAME . '</option>';
                                    }
                                ?>
                                <label>Dept</label>
                                <select class="form-control w-100" name="DEPT" id="DEPT">
                                    <option value="0" selected>All Department</option>
                                    <?php echo $CDepartment; ?>
                                </select>
                            </div> -->
                            <!-- <div class="col-md-12 mt-4">
                                <?php
                                    $CDepartment = '';
                                    foreach ($DtDepartment as $values) {
                                        $CDepartment .= '<option value="' . $values->DEPARTMENT . '">' . $values->DEPARTEMENTNAME . '</option>';
                                    }
                                ?>
                                <label>Send To</label>
                                <select class="form-control w-100" name="SEND_TO" id="SEND_TO">
                                    <option value="0" selected>All Department</option>
                                    <?php echo $CDepartment; ?>
                                </select>
                            </div> -->
                            <div class="col-md-12 mt-2">
                                <label>Vendor</label>
                                <select class="form-control w-100 EVENDOR" id="EVENDOR" name="EVENDOR" style="width: 100%">
                                    <option disabled selected>Select</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="EPERIOD">Period</label>
                                    <input type="text" class="form-control" name="EPERIOD" id="EPERIOD" placeholder="MM YYYY" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="checkDate">
                                    <label class="form-check-label" for="checkDate">
                                    By Date
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="EPERIOD">From Date</label>
                                    <input type="text" class="form-control" name="FROMDATE" id="FROMDATE" placeholder="MM/DD/YYYY" autocomplete="off" disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="EPERIOD">To Date</label>
                                    <input type="text" class="form-control" name="TODATE" id="TODATE" placeholder="MM/DD/YYYY" autocomplete="off" disabled>
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
    <div class="modal fade" id="ReceiveModal" tabindex="-1" role="dialog" aria-labelledby="ReceiveModal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Remarks - <span id="ModNO_PO"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="col-md-12">
                <input type="hidden" id="noreceiptdoc">
                <span style="color:red;">* Maksimal 100 Karakter</span>
                <input type="text" maxlength="100" class="form-control" name="withRemarks" id="withRemarks" autocomplete="off">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="saveModal">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    <!-- modal view  -->
    <!-- <?php if (!empty($_GET)) { ?>
        <div class="panel-footer text-left">
            <button type="button" id="btnSave" onclick="SaveMaster()" class="btn btn-primary btn-sm m-l-5">Save</button>
            <button type="button" class="btn btn-warning btn-sm m-l-5" onclick="Cancel()">Cancel</button>
        </div>
    <?php } ?> -->
</div>

<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var DEPT = "<?php echo $SESSION->DEPARTMENT; ?>";
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
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
    var COMPANY = 0;
    var SUBGROUP = 0;
    var tanggal;
    var sVendor;
    var DtElog = [];
    var selectDept = <?php echo json_encode($DtDepartment) ?>;
    $(document).ready(function () {
        $('#PERIOD').datepicker({
            "autoclose": true,
            "todayHighlight": true,
            // "viewMode": "months",
            // "minViewMode": "months",
            "format": "mm/dd/yyyy",
            // "setDate": new Date()
        });

        $('#page-container').addClass('page-sidebar-minified');
        if (getUrlParameter('type') == "edit" || getUrlParameter('type') == "add") {
            UrlParam = getUrlParameter('type');
            if (getUrlParameter('type') == "add") {
                if (ADDS != 1) {
                    $('#btnSave').remove();
                }
                SetDataKosong();
            } else {
                if (EDITS != 1) {
                    $('#btnSave').remove();
                }
                var data = [];//<?php echo json_encode($DtElog); ?>;
                SetData(data);
            }
        } else {
            if (!$.fn.DataTable.isDataTable('#DtElog')) {

                $('#DtElog').DataTable({
                    "processing": true,
                    "serverSide":true,
                    "deferRender":true,
                    "ajax": {
                        "url": "<?php echo site_url('Elog/ShowReceiveData') ?>",
                        "datatype": "JSON",
                        "type": "POST",
                        "data": function (d) {
                            d.COMPANY = COMPANY;
                            d.SUBGROUP = SUBGROUP;
                            d.FROMDATE = tanggal;
                            d.VENDOR   = sVendor;
                        },
                        "dataSrc": function (ext) {
                            if (ext.status == 200) {
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
                            $("#loader").addClass('show');
                        },
                        "complete": function() {
                            $("#loader").removeClass('show');
                        }
                    },
                    "columns": [
                    {"data": "COMPANYCODE"},
                    // {"data": "NO_RECEIPT_DOC"},
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
                    {"data": "UPDATED_BY"},
                    {"data": "REMARK"},
                    {"data": "DATE_RECEIPT"},
                    {
                        "data": null,
                        "className": "text-center",
                        render: function(data, type, row, meta) {
                            var html = '';
                           
                                if(data.STATUS == 2){
                                    html += '<button class="btn btn-info btn-sm Receive" data-id="'+data.NO_RECEIPT_DOC+'" data-invcode="'+data.INVOICE_CODE+'" data-nopo="'+data.NO_PO+'" data-vendor="'+data.VENDOR+'" data-amount="'+data.AMOUNT+'"  id="Receive" title="Receive" disabled>Receive</button>';
                                }else{
                                    if(DEPT == 'FINANCE' || 'IT' || 'AP'){
                                        html += '<button class="btn btn-info btn-sm Receive mr-2" data-id="'+data.NO_RECEIPT_DOC+'" data-invcode="'+data.INVOICE_CODE+'" data-nopo="'+data.NO_PO+'" data-vendor="'+data.VENDOR+'" data-amount="'+data.AMOUNT+'"  id="Receive" title="Receive">Receive</button>';
                                        html += '<button class="btn btn-indigo btn-sm mt-2 ReceiveModal" data-id="'+data.NO_RECEIPT_DOC+'" data-invcode="'+data.INVOICE_CODE+'" data-nopo="'+data.NO_PO+'" data-vendor="'+data.VENDOR+'" data-amount="'+data.AMOUNT+'"  id="ReceiveModals" title="Receive" data-toggle="modal" data-target="#ReceiveModal">Receive With Remarks</button>';
                                        if(data.FILENAME != null){
                                            html += '<button class="btn mt-2 btn-green btn-sm btnFiles mr-2" data-id="'+data.NO_RECEIPT_DOC+'" data-filename="'+data.FILENAME+'" id="btnFiles">See Attachment</button>';
                                        }
                                    }else{
                                        html += '<button class="btn btn-info btn-sm Receive mr-2 mb-2" data-id="'+data.NO_RECEIPT_DOC+'" data-invcode="'+data.INVOICE_CODE+'" data-nopo="'+data.NO_PO+'" data-vendor="'+data.VENDOR+'" data-amount="'+data.AMOUNT+'"  id="Receive" title="Receive">Receive</button>';
                                    }
                                    // if(DEPT == 'FINANCE'){
                                    //     html += '<button class="btn btn-inverse btn-sm finish" data-id="'+data.NO_RECEIPT_DOC+'" data-invcode="'+data.INVOICE_CODE+'" data-nopo="'+data.NO_PO+'" data-vendor="'+data.VENDOR+'" data-amount="'+data.AMOUNT+'"  id="finish" title="finish">Received Closing</button>'
                                    // }    
                                }    
                            return html;
                        }
                    },
                    {
                            "data": null,
                            "className": "text-center align-middle",
                            "orderable": false,
                            render: function (data, type, row, meta) {
                                var html = '<input type="checkbox" name="pils" class="pils" data-id="'+data.NO_RECEIPT_DOC+'" data-invcode="'+data.INVOICE_CODE+'" data-nopo="'+data.NO_PO+'" data-vendor="'+data.VENDOR+'" data-amount="'+data.AMOUNT+'" data-pos="'+data.POS+'">';
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
                $('#DtElog thead th').addClass('text-center');
                table = $('#DtElog').DataTable();

                $('#PERIOD').on({
                    'change': function() {
                            tanggal = this.value;
                            table.ajax.reload();            
                        }
                });

                $('#sVendor').on('select2:select', function (e) {
                    sVendor = e.params.data.id;
                    table.ajax.reload();   
                });

                table.on('change', '.pils', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    if (this.checked) {
                        data.FLAG  = "1";
                        data.DEPARTMENT = $(this).closest("tr").find("#DEPARTMENT").val();
                        data.REMARKS = $(this).closest("tr").find("#valREMARKS").val();
                    } else {
                        data.FLAG = "0";
                    }
                });
                table.on('click', '.btnFiles', function() {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    window.open("<?php echo base_url('assets/elogfiles/')?>" + data.FILENAME,'_blank');
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

        $(".sVendor").select2({
            allowClear: true,
            placeholder: "Choose Vendor",
            debug:true,
            ajax: {
                url: "<?php echo site_url('Elog/getVendorNew') ?>",
                dataType: 'json',
                delay: 250,
                type: 'GET',
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function(data, page) {
                    // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data
                    return {
                        results: $.map(data, function (item) {
                          return {
                            id:item.VENDORID,
                            text:item.VENDORNAME
                        }
                    })
                    };
                },
                cache: true
            },
            escapeMarkup: function(markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 1,
            //templateResult: formatRepo,
            //templateSelection: formatRepoSelection
        });

        function clearSelectedOptions() {
          $('#sVendor').val(null).trigger('change');
        }
        
        $("#clearSelect2").on("click",function(){
            clearSelectedOptions();
            sVendor = '';
            table.ajax.reload();
        });


    });

    $('#btnExport').on({
        'click': function() {
                // if(USERNAME != 'ADMIN'){
                //     alert('Maintenance, Comeback Later.');    
                // }else{
                    $('#FExport').parsley().reset();
                    $("#MExport").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                // }
            }
    });

    $('[data-dismiss=modal]').on('click', function (e) {
            $("#EVENDOR").val([]).trigger("change");
    });

    $('#checkDate').change(function() {
        if ($('#checkDate').is(':checked') == true){
              $('#EPERIOD').prop('disabled', true);
              $('#FROMDATE').prop('disabled', false);
              $('#TODATE').prop('disabled', false);

                $('#FROMDATE').datepicker({
                    "autoclose": true,
                    "todayHighlight": true,
                    "setDate": new Date()
                });

                $('#TODATE').datepicker({
                    "autoclose": true,
                    "todayHighlight": true,
                    "setDate": new Date()
                });
              // console.log('checked');
           } else {
            $('#EPERIOD').prop('disabled', false);
            $('#FROMDATE').prop('disabled', true);
            $('#TODATE').prop('disabled', true);
           }
    });

    $('#EPERIOD').datepicker({
        "autoclose": true,
        // "todayHighlight": true,
        "viewMode": "months",
        "minViewMode": "months",
        "format": "M yyyy"
    });

    $("#EVENDOR").select2({
            // theme: 'bootstrap4',
            width: 'resolve',
            dropdownParent: $('#MExport .modal-content'),
            ajax: {
                url: "<?php echo site_url('Leasing/getVendor') ?>",
                dataType: 'json',
                delay: 250,
                type: 'GET',
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function(data, page) {
                    // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data
                    return {
                        results: $.map(data, function (item) {
                          return {
                            id:item.ID,
                            text:item.TEXT
                        }
                    })
                    };
                },
                cache: true
            },
            escapeMarkup: function(markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 1,
            //templateResult: formatRepo,
            //templateSelection: formatRepoSelection
        });

    var Export = function(type) {
        if ($('#FExport').parsley().validate()) {
            var EVENDOR  = $('#EVENDOR').val();

            if($('#checkDate').is(':checked') == true){
                var url = "<?php echo site_url('Elog/ReceiveExport'); ?>?COMPANY=PARAM2&FROMDATE=PARAM3&TODATE=PARAM4&VENDOR=PARAM9";
                url = url.replace("PARAM1", type);
                if ($("#ECOMPANY").val() == "" || $("#ECOMPANY").val() == null || $("#ECOMPANY").val() == undefined) {
                    alert('Cant Null');
                } else {
                    url = url.replace("PARAM2", $("#ECOMPANY").val());
                }

                if(EVENDOR == null || EVENDOR == 'null'){
                    EVENDOR = 0;
                }
                var FROMDATE = $('#FROMDATE').val();
                var TODATE   = $('#TODATE').val();
                url = url.replace("PARAM3", FROMDATE);
                url = url.replace("PARAM4", TODATE);
                url = url.replace("PARAM9", EVENDOR);
            }else{
                var url = "<?php echo site_url('Elog/ReceiveExport'); ?>?COMPANY=PARAM2&MONTH=PARAM4&YEAR=PARAM5&VENDOR=PARAM9";
                url = url.replace("PARAM1", type);
                if ($("#ECOMPANY").val() == "" || $("#ECOMPANY").val() == null || $("#ECOMPANY").val() == undefined) {
                    alert('Cant Null');
                } else {
                    url = url.replace("PARAM2", $("#ECOMPANY").val());
                }
                if(EVENDOR == null || EVENDOR == 'null'){
                    EVENDOR = 0;
                }
                MONTH = ListBulan.indexOf($('#EPERIOD').val().substr(0, 3)) + 1;
                YEAR = $('#EPERIOD').val().substr(4, 4);
                url = url.replace("PARAM4", MONTH);
                url = url.replace("PARAM5", YEAR);
                url = url.replace("PARAM9", EVENDOR);
            }
            
            window.open(url, '_blank');
        }
    }

    var Add = function () {
        window.location.href = window.location.href + '?type=add';
    };
    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }

</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('body').on('click','.receiveAll',function(){

            $(this).prop('disabled', true); 
            $('#loader').addClass('show');
            $.each(table.data(), function (index, value) {
                
                if (value.NO_RECEIPT_DOC == undefined || value.NO_RECEIPT_DOC == null || value.NO_RECEIPT_DOC == '') {
                } else {

                    if (value.FLAG == "1" || value.FLAG == 1) {
                        DtElog.push(value);
                    }
                }
            });
            $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Elog/receiveReceiptAll'); ?>",
                    data: {
                       DtElog: DtElog,
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
            DtElog = [];
            table.ajax.reload();
            $(this).prop('disabled', false);
            $('#loader').removeClass('show');
        });

        $('body').on('click','#Receive',function(){
            var NO_RECEIPT_DOC = $(this).attr('data-id');
            var INVOICE_CODE = $(this).attr('data-invcode');
            var NO_PO = $(this).attr('data-nopo');
            var VENDOR = $(this).attr('data-vendor');
            var AMOUNT = $(this).attr('data-amount');
            // var REMARKS = $(this).closest("tr").find("#valREMARKS").val();
            // var DEPARTMENT = $(this).closest("tr").find("#DEPARTMENT").val();
            
            $('#loader').addClass('show');
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Elog/receiveReceipt'); ?>",
                    data: {
                        NO_RECEIPT_DOC: NO_RECEIPT_DOC,
                        INVOICE_CODE: INVOICE_CODE,
                        NO_PO: NO_PO,
                        // DEPARTMENT: DEPARTMENT,
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

        $('body').on('click','#ReceiveModals',function(){
            $('#withRemarks').val('');
            var NO_RECEIPT_DOC = $(this).attr('data-id');
            var INVOICE_CODE = $(this).attr('data-invcode');
            var NO_PO = $(this).attr('data-nopo');
            $('#ModNO_PO').text(NO_PO);
            $('#noreceiptdoc').val(NO_RECEIPT_DOC);
            // alert($('#ModNO_PO').text());
        });



        $('body').on('click','#saveModal',function(){
            
            $('#loader').addClass('show');
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Elog/receiveReceipt'); ?>",
                    data: {
                        NO_RECEIPT_DOC: $('#noreceiptdoc').val(),
                        // DEPARTMENT: DEPARTMENT,
                        NO_PO:  $('#ModNO_PO').text(),
                        REMARK: $('#withRemarks').val(),
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
                        $('#ReceiveModal').modal('hide');
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
        
        $('body').on('click','#finish',function(){
            var NO_RECEIPT_DOC = $(this).attr('data-id');
            var INVOICE_CODE = $(this).attr('data-invcode');
            var NO_PO = $(this).attr('data-nopo');
            var VENDOR = $(this).attr('data-vendor');
            var AMOUNT = $(this).attr('data-amount');
            // var REMARKS = $(this).closest("tr").find("#valREMARKS").val();
            // var DEPARTMENT = $(this).closest("tr").find("#DEPARTMENT").val();
            
            $('#loader').addClass('show');
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Elog/receiveReceiptClosing'); ?>",
                    data: {
                        NO_RECEIPT_DOC: NO_RECEIPT_DOC,
                        // DEPARTMENT: DEPARTMENT,
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
        $('#COMPANY').on({
            'change': function () {
                // MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
                // YEAR = this.value.substr(4, 4);
                COMPANY = $(this).val();
                table.ajax.reload();
            }
        });

        $('#SUBGROUP').on({
            'change': function () {
                // MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
                // YEAR = this.value.substr(4, 4);
                SUBGROUP = $(this).val();
                table.ajax.reload();
            }
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