<link href="./assets/css/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<style>
    .dt-button {
        transition-duration: 0.4s;
        background-color: #76b6d6;
    }

    .dt-button:hover {
        background-color: #138496;
        color: #138496;
    }

    .listdataAjaxBank {
        max-height: 200px;
        width: 100%;
        background-color: white;
        border: 1px solid #d3d8de;
        white-space: nowrap;
        position: relative;
        overflow-y: scroll;
        left: 5px;
        padding: 0;
    }

    .ullistdataAjaxBANK {
        padding: 0;
        margin: 0;
    }

    li.AjaxAddedBank {
        list-style-type: none;
        padding: 3px 10px;
    }

    li.AjaxAddedBank:hover {
        background-color: #ecdcdc;
        cursor: pointer;
    }

    .listDataBANK {
        padding-left: 0;
        max-width: 290px;
    }
</style>

<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Bank Mutations</li>
</ol>
<h1 class="page-header">Bank Mutations</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Bank Mutations</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <select class="form-control COMPANYGROUP" id="COMPANYGROUP" name='COMPANYGROUP'>
                                    <option value="" selected disabled>Company Group</option>
                                    <option value="CMT">CEMENT</option>
                                    <option value="MOTIVE">MOTIVE</option>
                                    <option value="PLT">PLANTATION</option>
                                    <option value="PROPERTY">PROPERTY</option>
                                    <option value="WOOD">WOOD</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <select class="form-control COMPANYSUBGROUP" id="COMPANYSUBGROUP" name='COMPANYSUBGROUP'>
                                    <option value="" selected disabled>Subgroup</option>
                                    <option value="UPSTREAM">UPSTREAM</option>
                                    <option value="DOWNSTREAM">DOWNSTREAM</option></select>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                                    <option value="">All Company</option>
                                    <?php
                                    foreach ($DtCompany as $values) {
                                        echo '<option value=' . $values->ID . '>' . $values->COMPANYNAME . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <select class="form-control w-100" name="BANKCODE" id="BANKCODE" required>
                                    <option value="" selected>Choose Bank</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2 w-100">
                            <div class=" col-md-2">
                                <!-- <label for="FROMDATE">From Date *</label> -->
                                <div class="input-group date" id="FROMDATE">
                                    <input type="text" class="form-control mkreadonly roleaccess" id="FROMDATE2" placeholder="From Date" value="05/01/2020" />
                                    <div class="input-group-addon input-group-append">
                                        <div class="input-group-text">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" col-md-2">
                                <!-- <label for="TODATE">To Date *</label> -->
                                <div class="input-group date" id="TODATE">
                                    <input type="text" class="form-control mkreadonly roleaccess" name="TODATE" id="TODATE2" placeholder="To Date" value="05/31/2020" />
                                    <div class="input-group-addon input-group-append">
                                        <div class="input-group-text">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col my-auto">
                                <button type="button" class="btn btn-info" style="padding: 3px 10px;" onclick="searchCashflow()">Search</button>
                                <!-- <button onclick="VExport()" class="btn btn-success" style="padding: 3px 10px;"><i class="fa fa-file-excel"></i> Export</button> -->
                            </div>
                        </div>
                </form>
            </div>
            <!-- <div class="col-12">
                <hr style="background:#e2e7eb!important;">
            </div> -->
            <div class="col-md-2 offset-md-10"> 
                <label for="" class="text-light">Search</label> 
                <input type="text" id="searchInvoice" class="form-control" placeholder="Cari..">
            </div> 
        </div>
        <!-- <div class="row">
            <div class="col-md-9">
                <div class="form-group col-5 listdataAjaxBank" style="display: none">
                    <ul id="listBANK" class="roleaccess listDataBANK"></ul>
                </div>
            </div>
        </div> -->
        <div class="row ml-0 mr-0 mb-0 mt-2 table-responsive">
            <table id="DtInvoice" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                <thead>
                    <tr role="row">
                        <th class="text-center no-sort align-middle">Bank</th>
                        <!-- <th class="text-center no-sort align-middle">Company</th> -->
                        <th class="text-center no-sort align-middle">Date</th>
                        <th class="text-center no-sort align-middle">Voucher</th>
                        <th class="text-center no-sort align-middle">Giro</th>
                        <th class="text-center no-sort align-middle">Vendor</th>
                        <th class="text-center no-sort align-middle">Remark</th>
                        <!-- <th class="text-center no-sort align-middle">Doc Type</th> -->
                        <th class="text-center no-sort align-middle">Doc Number</th>
                        <th class="text-center no-sort align-middle">Debit</th>
                        <th class="text-center no-sort align-middle">Credit</th>
                        <th class="text-center no-sort align-middle">Balance</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr role="row">
                        <th class="text-right" colspan="7">Total :</th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right toBal"></th>
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
                                <label for="ECOMPANY">Choose Company</label>
                                <select class="form-control" name="ECOMPANY" id="ECOMPANY">
                                    <option value="" selected>All Company</option>
                                    <?php
                                    foreach ($DtCompany as $values) {
                                        echo '<option value=' . $values->ID . '>' . $values->COMPANYNAME . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="FROMDATE">From Date *</label>
                                <div class="input-group date" id="FROMDATE3">
                                    <input type="text" class="form-control" name="FROMDATE" id="FROMDATE" placeholder="MM/DD/YYYY" required />
                                    <div class="input-group-addon input-group-append">
                                        <div class="input-group-text">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="TODATE">To Date*</label>
                                <div class="input-group date" id="TODATE3">
                                    <input type="text" class="form-control" name="TODATE" id="TODATE" placeholder="MM/DD/YYYY" required />
                                    <div class="input-group-addon input-group-append">
                                        <div class="input-group-text">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- <button type="submit" class="btn btn-primary" onclick="Export('EXCEL')"><i class="fa fa-file-excel"></i> Excel</button> -->
                </div>
            </form>
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
    var table, COMPANYBANK = '';
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    if (dd <= 10) {
        dd = '0' + dd;
    }
    if (mm <= 10) {
        mm = '0' + mm;
    }
    var tgl = mm + '/' + dd + '/' + today.getFullYear();

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

    $(document).ready(function() {
        LoadDataTable()

        $(document).on('click', 'body', function() {
            $('.AjaxAddedBank').remove()
            $('.listdataAjaxBank').hide()
        })


        // $(document).on('click', '.AjaxAddedBank', function() {
        //     SelectedCat = []
        //     $('.listdataAjaxBank').hide()
        //     $('#BANKNAME').val($(this).text())
        //     $('#BANKCODE').val($(this).attr('data-attr'))
        // })

        // let timeOutonKeyupVendor = null
        // $(document).on('input', '#BANKNAME', function(){
        //     if (COMPANYBANK == ''){
        //         alert('Choose Company First!');
        //         $('#BANKNAME').val('');
        //         return;
        //     }
        //     let dataKeywords = $(this).val()

        //     clearTimeout(timeOutonKeyupVendor);
        //     timeOutonKeyupVendor = setTimeout(function () {
        //         if (dataKeywords) {
        //             $.ajax({
        //                 url: "<?php echo site_url('IBank/GetDataBankWithCompany'); ?>",
        //                 method: "POST",
        //                 data: {dataKeywords: dataKeywords.toUpperCase(), COMPANYBANK: COMPANYBANK},
        //                 success:function(response)
        //                 {
        //                     $('.listdataAjaxBank').show()
        //                     $('.AjaxAddedBank').remove()
        //                     let data = JSON.parse(response)
        //                     let options = ''
        //                     if (data.result.data.length > 0) {
        //                         data.result.data.forEach(function(value, key) {
        //                             options += '<li class="AjaxAddedBank" data-attr="'+value.FCCODE+'" >' + value.BANKACCOUNT +' - '+ value.FCNAME + '</li>'
        //                         })
        //                     } else {
        //                         options = '<li class="AjaxAddedBank">Tidak ada data.</li>'
        //                     }

        //                     $('ul#listBANK').append(options)
        //                 }
        //             })
        //         } else {
        //             $('.listdataAjaxBank').hide()
        //             return false
        //         }
        //     }, 1000);
        // })

        $('#FROMDATE').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "MM/DD/YYYY",
        });

        $('#TODATE').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "MM/DD/YYYY",
        });

        $('#PERIOD').on({
            'change': function() {

                let dataYear = ''
                if (navigator.userAgent.indexOf("Chrome") !== -1) {
                    dataYear = moment($(this).val()).format('M-YYYY').split("-", 2)
                } else {
                    /* FOR MOZILA USERS */
                    dataYear = moment('01 ' + $(this).val().replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3")).format('M-YYYY').split("-", 2)
                }

                YEAR = dataYear[1]
                MONTH = dataYear[0]

                LoadDataTable()
            }
        });

        var COMPANYGROUP = '', COMPANYSUBGROUP= '', COMPANY = '';
        $(document).on('change', '#COMPANYGROUP', function () {            
            COMPANYGROUP = $(this).val();
            if(COMPANYGROUP == 'CMT'){
                $.ajax({
                    url : "<?php echo site_url('Cash/getSubgroup');?>",
                    method : "POST",
                    data : {GROUP: COMPANYGROUP},
                    async : true,
                    dataType : 'json',
                    success: function(data){
                                // console.log(data.result);
                                var listSub = '';
                                var i;
                                for(i=0; i<data.result.data.length; i++){
                                    listSub += '<option value='+data.result.data[i].FCCODE+'>'+data.result.data[i].FCCODE_GROUP+'</option>';
                                }
                                $('#COMPANYSUBGROUP').html(listSub);
                                $('#loader').removeClass('show');
                                
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                $('#loader').removeClass('show');
                                alert('Please Check Your Connection !!!');
                            }
                });
                COMPANYGROUP = 'CEMENT';
                $.ajax({
                    url : "<?php echo site_url('Cash/getCompany');?>",
                    method : "POST",
                    data : {SUBGROUP: COMPANYGROUP},
                    async : true,
                    dataType : 'json',
                    success: function(data){
                                // console.log(data.result);
                                var listCompany = '';
                                var i;
                                for(i=0; i<data.result.data.length; i++){
                                    listCompany += '<option value='+data.result.data[i].ID+'>'+data.result.data[i].COMPANYCODE+' - '+data.result.data[i].COMPANYNAME+'</option>';
                                }
                                $('#COMPANY').html(listCompany);
                                $('#loader').removeClass('show');
                                
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                $('#loader').removeClass('show');
                                alert('Please Check Your Connection !!!');
                            }
                });
            }
            else{
                var listSub = '<option value="">All</option> <option value="UPSTREAM">UPSTREAM</option><option value="DOWNSTREAM">DOWNSTREAM</option>';
                $('#COMPANYSUBGROUP').html(listSub);
                if($('#COMPANYSUBGROUP').val() == '' || $('#COMPANYSUBGROUP').val() == null){
                    var selectCompany = <?php echo json_encode($DtCompany) ?>;
                    var mSel = '';
                    for (i in selectCompany) {
                      
                        mSel+= '<option value="'+ selectCompany[i].ID +'">'+ selectCompany[i].COMPANYCODE + ' - ' + selectCompany[i].COMPANYNAME +'</option>';
                    }
                    mSel+= '<option >Select Company</option></select>';
                    $('#COMPANY').html(mSel);
                    $('#loader').removeClass('show');
                }
            }
            
        });

        $(document).on('change', '#COMPANYSUBGROUP', function () {            
            COMPANYSUBGROUP = $(this).val();
            // LoadDataTable()
        });

        $(document).on('change', '#COMPANY', function () {            
            COMPANY = $(this).val();
            // LoadDataTable()
        });

        $("#COMPANYSUBGROUP").on({
            'change': function() {
                $('#loader').addClass('show');
                var value = $(this).val();
                if(value == '' || value == null){
                    var selectCompany = <?php echo json_encode($DtCompany) ?>;
                    var mSel = '';
                    for (i in selectCompany) {
                      
                        mSel+= '<option value="'+ selectCompany[i].ID +'">'+ selectCompany[i].COMPANYCODE + ' - ' + selectCompany[i].COMPANYNAME +'</option>';
                    }
                    mSel+= '<option >Select Company</option></select>';
                    $('#COMPANY').html(mSel);
                    $('#loader').removeClass('show');
                }
                else {
                    $.ajax({
                        url : "<?php echo site_url('Cash/getCompany');?>",
                        method : "POST",
                        data : {SUBGROUP: value},
                        async : true,
                        dataType : 'json',
                        success: function(data){
                                    // console.log(data.result);
                                    var listCompany = '';
                                    var i;
                                    for(i=0; i<data.result.data.length; i++){
                                        listCompany += '<option value='+data.result.data[i].ID+'>'+data.result.data[i].COMPANYCODE+' - '+data.result.data[i].COMPANYNAME+'</option>';
                                    }
                                    $('#COMPANY').html(listCompany);
                                    $('#loader').removeClass('show');
                                    
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    $('#loader').removeClass('show');
                                    alert('Please Check Your Connection !!!');
                                }
                    });
                }
                return false;
                // loadData();
            }
        });

        $(document).on('change', '#COMPANY', function() {
            showBank($(this).val())
        });

    });

    function showBank(COMPANYBANK) {
        $('#loader').addClass('show');
        $('.dataBankOptions').remove()
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Payment/DtBankCompany'); ?>",
            data: {
                COMPANY: COMPANYBANK
            },
            success: function(response) {
                let data = JSON.parse(response)
                let options = ''
                let selected = '';

                data.result.data.forEach(function(value, key) {
                    selected = (value.ISDEFAULT == 1) ? value.FCCODE : selected;
                    let Default = (value.ISDEFAULT == 1) ? '(Default)' : '';
                    options += '<option class="dataBankOptions" value="' + value.FCCODE + '" ' + selected + '>' + value.BANKACCOUNT + ' - ' + value.FCNAME + ' - ' + value.CURRENCY + ' ' + Default + '</option>'
                })

                $(options).insertAfter("#BANKCODE option:first");
                $('#loader').removeClass('show');

            },
            error: function(e) {
                alert('Please Check Your Connection !!!');
            }
        });
    }

    $(document).ready(function(e) {
        $('button.dt-button').addClass('btn');
        $('button.dt-button').addClass('btn-primary');
    });

    function LoadDataTable() {
        var opening = '';
        if (!$.fn.DataTable.isDataTable('#DtInvoice')) {
            $('#DtInvoice').DataTable({
                "processing": true,
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Report Bank Mutations'
                }, ],
                "ajax": {
                    "url": "<?php echo site_url('Cashflow/ShowData') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function(d) {
                        d.BANKCODE = $('#BANKCODE').val();
                        // d.BANKCODE = 'BPLT0072';
                        d.DATEFROM = moment($('#FROMDATE2').val()).format('MM/DD/YYYY');
                        d.DATETO = moment($('#TODATE2').val()).format('MM/DD/YYYY');
                    },
                    "dataSrc": function(ext) {
                        if (ext.status == 200) {
                            return ext.result.data;
                            $('#page-container').addClass('page-sidebar-minified');
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
                "columns": [{
                        "data": "BANKNAME",
                        "orderable": false
                    },
                    // {
                    //     "data": "COMPANYNAME",
                    //     "orderable": false
                    // },
                    {
                        "data": "DATERELEASE",
                        "orderable": false
                    },
                    {
                        "data": "VOUCHERNO",
                        "orderable": false
                    },
                    {
                        "data": "NOCEKGIRO",
                        "orderable": false
                    },
                    {
                        "data": "VENDORNAME",
                        "orderable": false
                    },
                    {
                        "data": "REMARK",
                        "orderable": false
                    },
                    // {
                    //     "data": "PAYMENTTYPE",
                    //     "orderable": false
                    // },
                    {
                        "data": "DOCNUMBER",
                        "orderable": false
                    },
                    {
                        "data": "DEBET",
                        "className": "text-right",
                        "orderable": false,
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "CREDIT",
                        "className": "text-right",
                        "orderable": false,
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "ENDING",
                        "className": "text-right",
                        "orderable": false,
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
                "bInfo": false,
                // fixedColumns: true,
                // "bFilter": false,
                "order": [
                    [1, 'asc']
                ],
                "columnDefs": [
                    { "visible": false, "targets": 0 },
                    { "width": '50', "targets": 1 },
                    { "width": '100', "targets": 2 },
                    { "width": '10', "targets": 3 },
                    { "width": '150', "targets": 4 },
                    { "width": '100', "targets": 5 },
                    { "width": '100', "targets": 6 },
                    { "width": '100', "targets": 7 },
                    { "width": '100', "targets": 8 },
                    { "width": '100', "targets": 9 }
                    // { "width": '100', "targets": 10 }
                ],
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };
                    // Total over all pages
                    totalDebit = api.column(7).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    totalCredit = api.column(8).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);


                    setTimeout(function(){
                        var tOpt    = intVal(opening);
                        var tDebit  = totalDebit;
                        var tCredit = totalCredit;

                        totalBalance = (tOpt + tDebit) - tCredit;
                        // console.log(data)
                        var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;

                        $(api.column(7).footer()).html(numFormat(totalDebit));
                        $(api.column(8).footer()).html(numFormat(totalCredit));
                        $(api.column(9).footer()).html(numFormat(totalBalance));
                    },1500);
                    
                },
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var myData = api.rows({
                        page: 'current'
                    }).data();
                    var last = null;
                    api.column(0, {
                        page: 'current'
                    }).data().each(function(group, i, $currTable) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                '<tr>\n\
                                <td colspan="8" style="border-right:0; background-color:#bee5eb">Opening Balance</td>\n\
                                <td class="text-right opening" id="opening" style="background-color:#bee5eb">' + fCurrency($currTable.rows(rows[i]._DT_RowIndex).data()[0].OPENING) + '</td>\n\
                                </tr>'

                            );
                            opening = fCurrency($currTable.rows(rows[i]._DT_RowIndex).data()[0].OPENING)
                            last = group;
                        }
                    });


                    api.rows({
                        page: 'current'
                    }).invalidate();
                    
                },
            });

            $('#DtInvoice_filter').remove()

            $('#searchInvoice').on('input', function() {
                table2.search(this.value).draw();
            });

            $('#DtInvoice thead th').addClass('text-center');
            table2 = $('#DtInvoice').DataTable();

        } else {
            table2.ajax.reload();
        }
    }

    var searchCashflow = function() {
        let monthOnlyFromDate = moment($('#FROMDATE2').val()).format('YYYY');
        let monthOnlyToDate = moment($('#TODATE2').val()).format('YYYY');

        if ($('#FROMDATE2').val() == '' || $('#TODATE2').val() == '' || $('#FROMDATE2').val() == null || $('#TODATE2').val() == null) {
            alert("'From Date' & 'To Date' can not be empty!");
            return;
        } else if (monthOnlyFromDate !== monthOnlyToDate) {
            alert('Range date must be on the same year!');
            return;
        } else {
            $('#page-container').addClass('page-sidebar-minified');
            LoadDataTable()
        }
    }

    var VExport = function() {
        $('#FExport').parsley().reset();
        $("#MExport").modal({
            backdrop: 'static',
            keyboard: false
        });
    };

    $('#FROMDATE3').datetimepicker({
        "allowInputToggle": false,
        "showClose": true,
        "showClear": true,
        "showTodayButton": true,
        "format": "MM/DD/YYYY"
    });

    $('#TODATE3').datetimepicker({
        "allowInputToggle": false,
        "showClose": true,
        "showClear": true,
        "showTodayButton": true,
        "format": "MM/DD/YYYY"
    });

    $("#FROMDATE3").datepicker('setDate', tgl);
    $("#TODATE3").datepicker('setDate', tgl);

    var Export = function(type) {
        if ($('#FExport').parsley().validate()) {
            var url = "<?php echo site_url('Process/CashflowExport'); ?>?type=PARAM1&FROMDATE=PARAM2&TODATE=PARAM3&DEPARTMENT=PARAM4&USERNAME=PARAM5";
            url = url.replace("PARAM1", type);
            url = url.replace("PARAM2", ConvertYYYYMMDD($("#FROMDATE").val()));
            url = url.replace("PARAM3", ConvertYYYYMMDD($("#TODATE").val()));
            if ($("#ECOMPANY").val() == "" || $("#ECOMPANY").val() == null || $("#ECOMPANY").val() == undefined) {
                url = url.replace("PARAM4", 'ALL');
            } else {
                url = url.replace("PARAM4", $("#ECOMPANY").val());
            }
            url = url.replace("PARAM5", USERNAME);
            window.open(url, '_blank');
        }
    }

    var ConvertYYYYMMDD = function(data) {
        if (data == "" || data == null || data == undefined) {
            return "";
        } else {
            var dd = data.substr(3, 2);
            var mm = data.substr(0, 2);
            var yyyy = data.substr(6, 4);
            return yyyy + mm + dd;
        }
    };

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