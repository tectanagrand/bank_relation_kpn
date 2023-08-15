<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="./assets/js/datetime/moment-with-locales.min.js"></script>
<link href="./assets/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css" rel="stylesheet" />
<script src="./assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="./assets/plugins/DataTables/extensions/Buttons/js/jszip.min.js"></script>
<script src="./assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
<script src="./assets/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js"></script>
<style>
    input[type="text"]::placeholder {
                text-align: right;
            }
</style>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">E-Log</li>
</ol>
<h1 class="page-header">E-Log Send Document</h1>
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
                <div class="col-md-12">
                    <div class="form-row">
                        <div class="col-md-2">
                            <label for="COMPANY">Company</label>
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
                            <label for="COMPANY">Company Subgroup</label>
                            <select class="form-control mkreadonly" name="SUBGROUP" id="SUBGROUP">
                                <option value="0">Select</option>
                                <option value="UPSTREAM">UPSTREAM</option>
                                <option value="DOWNSTREAM">DOWNSTREAM</option>
                                <option value="PROPERTY">PROPERTY</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="PERIOD">Period</label>
                            <!-- <input type="text" class="form-control" name="PERIOD" id="PERIOD" autocomplete="off"> -->
                            <div class="input-group input-daterange">
                                <input type="text" class="form-control" id="PERIODFROM" autocomplete="off">
                                <div class="input-group-addon" style="padding:4px 0 0 0 !important;">></div>
                                <input type="text" class="form-control" id="PERIODTO"  autocomplete="off">
                            </div>
                        </div>  
                        <div class="col-md-2">
                            <label for="COMPANY">Search</label>
                            <div class="input-group">
                                <input type="text" id="search" name="search" class="form-control" placeholder="Cari.." >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="COMPANY">Search Vendor</label>
                            <div class="input-group">
                                <select class="form-control sVendor" id="sVendor" name="sVendor" >
                                    <!-- <option disabled selected>Choose</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group mt-4">
                                <button type="submit" class="btn btn-primary btn-sm sendAll" style="">Send All</button>
                                <button class="btn btn-dark btn-sm" id="clearSelect2">Clear Vendor</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-0 table-responsive">
                <table id="DtElog" data-order="[]" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DtElog_info" style="width: 100%;">
                    <thead>
                        <tr role="row">
                        <th class="text-center sorting_disabled" data-orderable="false">Company</th>
                        <!-- <th class="text-center sorting_asc">Doc No</th> -->
                        <th class="text-center sorting_disabled" data-orderable="false">No Invoice</th>
                        <th class="text-center sorting_disabled" data-orderable="false">No PO/SPO</th>
                        <th class="text-center sorting_disabled" data-orderable="false">Vendor</th>
                        <th class="text-center sorting_disabled" data-orderable="false">Currency</th>
                        <th class="text-center sorting_disabled" data-orderable="false">Amount</th>
                        <th class="text-center sorting_disabled" data-orderable="false">Voucherno</th>
                        <th class="text-center sorting_disabled" data-orderable="false">Dpp</th>
                        <th class="text-center sorting_disabled" data-orderable="false">Pph</th>
                        <th class="text-center sorting_disabled" data-orderable="false">Ppn</th>
                        <th class="text-center sorting_disabled" data-orderable="false">Net</th>
                        <th class="text-center sorting_disabled" data-orderable="false">First Dept</th>
                        <th class="text-center sorting_disabled" data-orderable="false">Sender</th>
                        <th class="text-center sorting_disabled" data-orderable="false">Last Remarks</th>
                        <th class="text-center sorting">Date</th>
                        <th class="text-center sorting_disabled" data-orderable="false">Send To</th>
                        <th class="text-center sorting_disabled" data-orderable="false">Remarks</th>
                        <th class="text-center sorting_disabled" data-orderable="false"aria-label="Action">#</th>
                        <th class="text-center sorting_disabled"><input type="checkbox" id="pil"></th>
                        </tr>
                    </thead>
                </table>
            </div>
    </div>

    <div class="modal fade" id="otherModal" tabindex="-1" role="dialog" aria-labelledby="otherModal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Other Data - <span id="ModNO_PO"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="col-md-12">
                <input type="hidden" id="noreceiptdoc">
                <input type="hidden" id="mRemarks">
                <input type="hidden" id="mPos">
                <input type="hidden" id="mDept">
                <div class="row">
                    <input class="form-control mb-2" type="text" name="VOUCHERNO" id="VOUCHERNO" autocomplete="off" placeholder="VOUCHER">
                    <input class="form-control mb-2" data-type='currency' type="text" name="DPP" id="DPP" autocomplete="off" placeholder="DPP">    
                    <input class="form-control mb-2" data-type='currency' type="text" name="PPN" id="PPN" autocomplete="off" placeholder="PPN">
                    <input class="form-control mb-2" data-type='currency' type="text" name="PPH" id="PPH" autocomplete="off" placeholder="PPH">
                    <input class="form-control mb-2" data-type='currency' type="text" name="NET" id="NET" autocomplete="off" placeholder="NET">
                </div>
                
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="saveModal">Send Changes</button>
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
    var sendAll = [];
    var DtElog = [];
    var sDEPT;
    var sVendor;
    var selectDept = <?php echo json_encode($DtDepartment) ?>;
    var cekDept    = <?php echo json_encode($cekDEPT); ?>;

    $('.input-daterange input').each(function() {
        $(this).datepicker('clearDates');
    });

    $(document).ready(function () {
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
            $('#pil').removeAttr('checked');
            
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
                                "url": "<?php echo site_url('Elog/ShowSendData') ?>",
                                "datatype": "JSON",
                                "type": "POST",
                                "data": function (d) {
                                    d.COMPANY  = COMPANY;
                                    d.SUBGROUP = SUBGROUP;
                                    d.sDATE = sDate;
                                    d.fDATE = fDate;
                                    d.VENDOR   = sVendor;
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
                                    $("#overlay").show();
                                },
                                "complete": function() {
                                    $("#overlay").hide();
                                }
                            },
                            "columns": [
                            {"data": "COMPANYNAME",
                                "orderable": false},
                            // {"data": "NO_RECEIPT_DOC"},
                            {"data": "INVOICE_CODE",
                                "orderable": false},
                            {"data": "NO_PO",
                                "orderable": false},
                            {"data": "VENDORNAME",
                                "orderable": false},
                            {"data": "CURRENCY",
                                "orderable": false},
                            {
                                "data": "AMOUNT",
                                "className": "text-right",
                                "orderable": false,
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
                            {"data": "FIRST_DEPT",
                                "orderable": false},
                            {"data": "SENDER",
                                "orderable": false},
                            {"data": "LAST_REMARK",
                                "orderable": false},
                            {"data": "DATE_RECEIPT"},
                            {
                                "data": null,
                                "className": "selectDept",
                                "orderable": false,
                                render: function(data, type, row, meta) {
                                var html = '';
                                var mSel = '<select name="DEPARTMENT" id="DEPARTMENT" class="form-control"><option>Choose</option>';
                                for (i in selectDept) {
                                  
                                    mSel+= '<option value="'+ selectDept[i].DEPARTMENT +'">'+ selectDept[i].DEPARTEMENTNAME +'</option>';
                                }

                                return mSel;
                                }
                            },
                            {
                                "data": null,
                                "className": "text-center REMARKS",
                                "orderable": false,
                                render: function(data, type, row, meta) {
                                    var html = '';
                                   
                                        html += '<input type="text" class="form-control" id="valREMARKS" />';    
                                    return html;
                                }
                            },
                            {
                                "data": null,
                                "className": "text-center",
                                "orderable": false,
                                render: function(data, type, row, meta) {
                                    var html = '';
                                        if(data.STATUS == 0){
                                            html += '<button class="btn btn-info btn-sm Send" data-id="'+data.NO_RECEIPT_DOC+'" data-invcode="'+data.INVOICE_CODE+'" data-nopo="'+data.NO_PO+'" data-vendor="'+data.VENDOR+'" data-amount="'+data.AMOUNT+'" data-pos="'+data.POS+'"  id="Send" title="Send">Send</button>';
                                        }else{
                                            html += '<button class="btn btn-info btn-sm Send" data-id="'+data.NO_RECEIPT_DOC+'" data-invcode="'+data.INVOICE_CODE+'" data-nopo="'+data.NO_PO+'" data-vendor="'+data.VENDOR+'" data-amount="'+data.AMOUNT+'"  id="Send" title="Send" disabled>Send</button>';    
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
                            "bFilter": true,
                            "bPaginate": true,
                            "bLengthChange": false,
                            "bInfo": true
                        });
                        $('#DtElog thead th').addClass('text-center');
                        table = $('#DtElog').DataTable();
                        table.on('click', '.edit', function() {
                            $tr = $(this).closest('tr');
                            var data = table.row($tr).data();
                            window.location.href = window.location.href + '?type=edit&UUID=' + data.UUID;
                        });
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
                                if($(this).closest("tr").find("#DEPARTMENT").val() == 'Choose'){
                                    alert('Please Choose DEPARTMENT for '+ data.INVOICE_CODE);
                                }else{
                                    data.FLAG  = "1";
                                    data.DEPARTMENT = $(this).closest("tr").find("#DEPARTMENT").val();
                                    data.REMARKS = $(this).closest("tr").find("#valREMARKS").val();    
                                }
                            } else {
                                data.FLAG = "0";
                            }
                        });
                        $("#DtElog_filter").remove();
                        $("#search").on({
                            'keyup': function () {
                                table.search(this.value, true, false, true).draw();
                            }
                        });

                        $('#sVendor').on('select2:select', function (e) {
                            sVendor = e.params.data.id;
                            table.ajax.reload();   
                        });
                }

        $(".sVendor").select2({
            allowClear: true,
            placeholder: "Choose Vendor",
            debug:true,
            ajax: {
                url: "<?php echo site_url('Elog/getVendorSend') ?>",
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
            
            // else{
            //     if (!$.fn.DataTable.isDataTable('#DtElog')) {
            //             $('#DtElog').DataTable({
            //                  dom: 'Bfrtip',
            //                 "deferRender":true,
            //                 "buttons": [{
            //                         extend: "excel",
            //                         title: 'Data Outstanding Elog',
            //                         className: "btn-xs btn-green mb-2",
            //                         text: 'Export To Excel'
            //                     }],
            //                 "processing": true,
            //                 "serverSide":true,
            //                 "ajax": {
            //                     "url": "<?php echo site_url('Elog/ShowSendData') ?>",
            //                     "datatype": "JSON",
            //                     "type": "POST",
            //                     "data": function (d) {
            //                         d.COMPANY  = COMPANY;
            //                         d.SUBGROUP = SUBGROUP;
            //                         // d.sDATE = moment(sDate).format('MM-DD-YYYY');
            //                     },
            //                     "dataSrc": function (ext) {
            //                         if (ext.status == 200) {
            //                             sendAll = ext.result.data.data;
            //                             ext.draw = ext.result.data.draw;
            //                             ext.recordsTotal = ext.result.data.recordsTotal;
            //                             ext.recordsFiltered = ext.result.data.recordsFiltered;
            //                             return ext.result.data.data;
            //                         } else if (ext.status == 504) {
            //                             alert(ext.result.data);
            //                             location.reload();
            //                             return [];
            //                         } else {
            //                             console.info(ext.result.data);
            //                             return [];
            //                         }
            //                     },
            //                     "beforeSend": function() {
            //                         $('#loader').addClass('show');
            //                     },
            //                     "complete": function() {
            //                         $('#loader').removeClass('show');
            //                     }
            //                 },
            //                 "columns": [
            //                 {"data": "COMPANYNAME"},
            //                 // {"data": "NO_RECEIPT_DOC"},
            //                 {"data": "INVOICE_CODE"},
            //                 {"data": "NO_PO"},
            //                 {"data": "VENDORNAME"},
            //                 {"data": "CURRENCY"},
            //                 {
            //                     "data": "AMOUNT",
            //                     "className": "text-right",
            //                     render: $.fn.dataTable.render.number(',', '.', 2)
            //                 },
            //                 {"data": "VOUCHERNO"},
            //                 {
            //                     "data": "DPP",
            //                     "className": "text-right",
            //                     render: $.fn.dataTable.render.number(',', '.', 2)
            //                 },
            //                 {
            //                     "data": "AMOUNT_PPN",
            //                     "className": "text-right",
            //                     render: $.fn.dataTable.render.number(',', '.', 2)
            //                 },
            //                 {
            //                     "data": "AMOUNT_PPH",
            //                     "className": "text-right",
            //                     render: $.fn.dataTable.render.number(',', '.', 2)
            //                 },
            //                 {
            //                     "data": "AMOUNT_NET",
            //                     "className": "text-right",
            //                     render: $.fn.dataTable.render.number(',', '.', 2)
            //                 },
            //                 {"data": "FIRST_DEPT"},
            //                 {"data": "SENDER"},
            //                 {"data": "LAST_REMARK"},
            //                 {"data": "DATE_RECEIPT"},
            //                 {
            //                     "data": null,
            //                     "className": "selectDept",
            //                     "orderable": false,
            //                     render: function(data, type, row, meta) {
            //                     var html = '';
            //                     var mSel = '<select name="DEPARTMENT" id="DEPARTMENT" class="form-control"><option>Choose</option>';
            //                     for (i in selectDept) {
                                  
            //                         mSel+= '<option value="'+ selectDept[i].DEPARTMENT +'">'+ selectDept[i].DEPARTEMENTNAME +'</option>';
            //                     }

            //                     return mSel;
            //                     }
            //                 },
            //                 {
            //                     "data": null,
            //                     "className": "text-center REMARKS",
            //                     "orderable": false,
            //                     render: function(data, type, row, meta) {
            //                         var html = '';
                                   
            //                             html += '<input type="text" class="form-control" id="valREMARKS" />';    
            //                         return html;
            //                     }
            //                 },
            //                 {
            //                     "data": null,
            //                     "className": "text-center",
            //                     "orderable": false,
            //                     render: function(data, type, row, meta) {
            //                         var html = '';
            //                             if(data.STATUS == 0){
            //                                 html += '<button class="btn btn-info btn-sm Send" data-id="'+data.NO_RECEIPT_DOC+'" data-invcode="'+data.INVOICE_CODE+'" data-nopo="'+data.NO_PO+'" data-vendor="'+data.VENDOR+'" data-amount="'+data.AMOUNT+'" data-pos="'+data.POS+'"  id="Send" title="Send">Send</button>';
            //                                 html += '<button class="btn btn-indigo btn-sm mt-2 otherModal" data-id="'+data.NO_RECEIPT_DOC+'" data-invcode="'+data.INVOICE_CODE+'" data-nopo="'+data.NO_PO+'" data-vendor="'+data.VENDOR+'" data-amount="'+data.AMOUNT+'"  id="otherModals" data-pos="'+data.POS+'" data-toggle="modal" data-target="#otherModal">Other Send</button>'; 
            //                             }else{
            //                                 html += '<button class="btn btn-info btn-sm Send" data-id="'+data.NO_RECEIPT_DOC+'" data-invcode="'+data.INVOICE_CODE+'" data-nopo="'+data.NO_PO+'" data-vendor="'+data.VENDOR+'" data-amount="'+data.AMOUNT+'"  id="Send" title="Send" disabled>Send</button>';    
            //                             }
                                        
            //                         return html;
            //                     }
            //                 },
            //                 {
            //                         "data": null,
            //                         "className": "text-center align-middle",
            //                         "orderable": false,
            //                         render: function (data, type, row, meta) {
            //                             var html = '<input type="checkbox" name="pils" class="pils" data-id="'+data.NO_RECEIPT_DOC+'" data-invcode="'+data.INVOICE_CODE+'" data-nopo="'+data.NO_PO+'" data-vendor="'+data.VENDOR+'" data-amount="'+data.AMOUNT+'" data-pos="'+data.POS+'">';
            //                             return html;
            //                         }
            //                     }
            //                 ],
            //                 // responsive: {
            //                 //     details: {
            //                 //         renderer: function (api, rowIdx, columns) {
            //                 //             var data = $.map(columns, function (col, i) {
            //                 //                 return col.hidden ?
            //                 //                 '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
            //                 //                 '<td>' + col.title + '</td> ' +
            //                 //                 '<td>:</td> ' +
            //                 //                 '<td>' + col.data + '</td>' +
            //                 //                 '</tr>' :
            //                 //                 '';
            //                 //             }).join('');
            //                 //             return data ? $('<table/>').append(data) : false;
            //                 //         }
            //                 //     }
            //                 // },
            //                 "bFilter": true,
            //                 "bPaginate": true,
            //                 "bLengthChange": false,
            //                 "bInfo": true
            //             });
            //             $('#DtElog thead th').addClass('text-center');
            //             table = $('#DtElog').DataTable();
            //             table.on('click', '.edit', function() {
            //                 $tr = $(this).closest('tr');
            //                 var data = table.row($tr).data();
            //                 window.location.href = window.location.href + '?type=edit&UUID=' + data.UUID;
            //             });
            //             table.on('click', '.view', function () {
            //                 var UUID = $(this).attr('data-id');
            //                 $('#loader').addClass('show');
            //                 $.ajax({
            //                         dataType: "JSON",
            //                         type: "POST",
            //                         url: "<?php echo site_url('Elog/viewDetail'); ?>",
            //                         data: {
            //                             ID: UUID},
            //                         success: function (response) {
            //                             $('#loader').removeClass('show');
            //                             $('#VCOMPANYNAME').val(response[0].COMPANYNAME);
            //                             $('#VDEPARTMENT').val(response[0].DEPARTMENT);
            //                             $('#VDOCNUMBER').val(response[0].DOCNUMBER);
            //                             $('#VDOCDATE').val(response[0].DOCDATE);
            //                             $('#VVENDOR').val(response[0].VENDORNAME);
            //                             $("#MView").modal({
            //                                 backdrop: 'static',
            //                                 keyboard: false
            //                             });
            //                         },
            //                         error: function (e) {
            //                             $('#loader').removeClass('show');
            //                             alert('Error Get data !!');
            //                         }
            //                     });
            //             });
            //             // table.on('click', '.delete', function () {
            //             //     $tr = $(this).closest('tr');
            //             //     var data = table.row($tr).data();
            //             //     if (confirm('Are you sure delete this data ?')) {
            //             //         $.ajax({
            //             //             dataType: "JSON",
            //             //             type: "POST",
            //             //             url: "<?php echo site_url('Elog/DeleteMaster'); ?>",
            //             //             data: {
            //             //                 DOCNUMBER: data.DOCNUMBER,
            //             //                 USERNAME: USERNAME
            //             //             },
            //             //             success: function (response) {
            //             //                 if (response.status == 200) {
            //             //                     alert(response.result.data.MESSAGE);
            //             //                     table.ajax.reload();
            //             //                 } else if (response.status == 504) {
            //             //                     alert(response.result.data.MESSAGE);
            //             //                     location.reload();
            //             //                 } else {
            //             //                     alert(response.result.data.MESSAGE);
            //             //                 }
            //             //             },
            //             //             error: function (e) {
            //             //                 alert('Error deleting data !!');
            //             //             }
            //             //         });
            //             //     }
            //             // });
            //             // table.on('click', '.export', function () {
            //             //     var DOCNUM = $(this).attr('data-docnumber');
            //             //     var COMP = $(this).attr('data-company');
            //             //     var url = "<?php echo site_url('Elog/exportTransaction'); ?>?COMPANY=PARAM1&DOCNUMBER=PARAM2";
            //             //     url = url.replace("PARAM1", COMP);
            //             //     url = url.replace("PARAM2", DOCNUM);
            //             //     window.open(url, '_blank');
            //             // });
            //             table.on('change', '.pils', function () {
            //                 $tr = $(this).closest('tr');
            //                 var data = table.row($tr).data();
            //                 if (this.checked) {
            //                     if($(this).closest("tr").find("#DEPARTMENT").val() == 'Choose'){
            //                         alert('Please Choose DEPARTMENT for '+ data.INVOICE_CODE);
            //                     }else{
            //                         data.FLAG  = "1";
            //                         data.DEPARTMENT = $(this).closest("tr").find("#DEPARTMENT").val();
            //                         data.REMARKS = $(this).closest("tr").find("#valREMARKS").val();    
            //                     }
            //                 } else {
            //                     data.FLAG = "0";
            //                 }
            //             });
            //             $("#DtElog_filter").remove();
            //             // $("#search").on({
            //             //     'keyup': function () {
            //             //         table.search(this.value, true, false, true).draw();
            //             //     }
            //             // });
            //             $('#search').keyup(delay(function (e) {
            //               table.search(this.value, true, false, true).draw();
            //             }, 500));

            //             function delay(fn, ms) {
            //               let timer = 0
            //               return function(...args) {
            //                 clearTimeout(timer)
            //                 timer = setTimeout(fn.bind(this, ...args), ms || 0)
            //               }
            //             }
            //         }
            // }
        }

        $('#pil').on('change', function () {
            if (this.checked) {
                $('#DtElog .pils').prop("checked", true);
            } else {
                $('#DtElog .pils').prop("checked", false);
            }
            $('#DtElog .pils').change();
        });

        $('#PERIODTO').datepicker({
                    "autoclose": true,
                    "todayHighlight": true
            });
        $('#PERIODFROM').datepicker({
                    "autoclose": true,
                    "todayHighlight": true
            });

        var sDate,fDate;
        $('#PERIODTO').on({
            'change': function () {
                // MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
                // YEAR = this.value.substr(4, 4);
                fDate = $('#PERIODFROM').val();
                sDate = $(this).val();
                table.ajax.reload();
            }
        });

        // $("#PERIOD").datepicker().datepicker("setDate", new Date());
    });

    var Add = function () {
        window.location.href = window.location.href + '?type=add';
    };
    function Cancel() {
        window.location.href = window.location.href.split("?")[0];
    }

</script>
<script type="text/javascript">
    $(document).ready(function() {
        var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;

        $('body').on('click','.sendAll',function(){

            $(this).prop('disabled', true); 
            $('#loader').addClass('show');
            $.each(table.data(), function (index, value) {
                //  var ins = $('#DtElog').find("tbody select").map(function() {

                //     // return $(this).find(":selected").val() // get selected text
                //     return $(this).val() // get selected value

                // }).get()

                //  alert(ins);
                
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
                    url: "<?php echo site_url('Elog/sendReceiptAll'); ?>",
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

        //oder
        $('body').on('click','#otherModals',function(){
            $('#VOUCHERNO').val('');
            $('#DPP').val('');
            $('#PPN').val('');
            $('#PPH').val('');
            $('#NET').val('');
            var NO_RECEIPT_DOC = $(this).attr('data-id');
            var INVOICE_CODE = $(this).attr('data-invcode');
            var NO_PO = $(this).attr('data-nopo');
            $('#ModNO_PO').text(NO_PO);
            $('#noreceiptdoc').val(NO_RECEIPT_DOC);
            $('#mDept').val($(this).closest("tr").find("#DEPARTMENT").val());
            $('#mPos').val($(this).attr('data-pos'));
            $('#mRemarks').val($(this).closest("tr").find("#valREMARKS").val());
            // alert($('#ModNO_PO').text());
        });


        //save other modal
        $('body').on('click','#saveModal',function(){
            
            $('#loader').addClass('show');
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Elog/otherSend'); ?>",
                    data: {
                        NO_RECEIPT_DOC: $('#noreceiptdoc').val(),
                        POS: $('#mPos').val(),
                        REMARKS: $('#mRemarks').val(),
                        DEPARTMENT: $('#mDept').val(),
                        VOUCHERNO: $('#VOUCHERNO').val(),
                        DPP: $('#DPP').val(),
                        PPN: $('#PPN').val(),
                        PPH: $('#PPH').val(),
                        NET: $('#NET').val(),
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

        $("#DPP").on({
            'keyup': function() {
                var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };
                
                var dpp   = intVal($(this).val());
                var ppn   = intVal($('#PPN').val());
                var pph   = intVal($('#PPH').val());

                let net = intVal(dpp + ppn - pph);
                $('#NET').val(numFormat(net));

            }
        });

        $("#PPN").on({
            'keyup': function() {
                var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };
                
                var dpp   = intVal($('#DPP').val());
                var ppn   = intVal($(this).val());
                var pph   = intVal($('#PPH').val());

                let net = intVal(dpp + ppn - pph);
                $('#NET').val(numFormat(net));

            }
        });

        $("#PPH").on({
            'keyup': function() {
                var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };
                
                var dpp   = intVal($('#DPP').val());
                var ppn   = intVal($('#PPN').val());
                var pph   = intVal($(this).val());

                let net = intVal(dpp + ppn - pph);
                $('#NET').val(numFormat(net));

            }
        });



        //end oder

        $('body').on('click','#Send',function(){

            
            var NO_RECEIPT_DOC = $(this).attr('data-id');
            var INVOICE_CODES = $(this).attr('data-invcode');
            var NO_POS = $(this).attr('data-nopo');
            var VENDOR = $(this).attr('data-vendor');
            var AMOUNT = $(this).attr('data-amount');
            var POS    = $(this).attr('data-pos');
            var REMARKS = $(this).closest("tr").find("#valREMARKS").val();
            var DEPARTMENT = $(this).closest("tr").find("#DEPARTMENT").val();
            if(DEPARTMENT == 'Choose' || DEPARTMENT == 'Memilih'){
                alert('Please Choose Department');
            }else{
                $('#loader').addClass('show');
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Elog/sendReceipt'); ?>",
                    data: {
                        NO_RECEIPT_DOC: NO_RECEIPT_DOC,
                        REMARKS: REMARKS,
                        POS: POS,
                        INVOICE_CODES: INVOICE_CODES,
                        NO_POS: NO_POS,
                        DEPARTMENT: DEPARTMENT,
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
            }
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