<link href="./assets/css/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet">
<style>
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
                <form id="FAddEditForm" class="form-inline" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                    <div class="row w-100">
                        <div class="form-group col-md-2">
                            <select class="form-control w-100" name="COMPANY" id="COMPANY" required>
                                <option value="" selected disabled>Choose Company</option>
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
                        <div class="form-group col-md-2">
                            <!-- <label for="FROMDATE">From Date *</label> -->
                            <div class="input-group date" id="FROMDATE">
                                <input type="text" class="form-control mkreadonly roleaccess" id="FROMDATE2" placeholder="From Date"/>
                                <div class="input-group-addon input-group-append">
                                    <div class="input-group-text">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-2">
                            <!-- <label for="TODATE">To Date *</label> -->
                            <div class="input-group date" id="TODATE">
                                <input type="text" class="form-control mkreadonly roleaccess" name="TODATE" id="TODATE2" placeholder="To Date"/>
                                <div class="input-group-addon input-group-append">
                                    <div class="input-group-text">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-1">
                            <button type="button" class="btn btn-info" style="padding: 3px 10px;" onclick="searchCashflow()">Search</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12"><hr style="background:#e2e7eb!important;"></div>
            <div class="col-md-2 offset-md-10">
                <!-- <label for="" class="text-light">Search</label> -->
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
                        <th class="text-center no-sort align-middle">Doc Type</th>
                        <th class="text-center no-sort align-middle">Doc Number</th>
                        <th class="text-center no-sort align-middle">Debit</th>
                        <th class="text-center no-sort align-middle">Credit</th>
                        <th class="text-center no-sort align-middle">Balance</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr role="row">
                        <th class="text-right" colspan="8">Total :</th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                    </tr>
                </tfoot>
            </table>
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
    var COMPANYBANK = '';
    
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
            'change': function () {

                let dataYear = ''
                if (navigator.userAgent.indexOf("Chrome") !== -1){
                    dataYear = moment($(this).val()).format('M-YYYY').split("-", 2)
                } else {
                    /* FOR MOZILA USERS */
                    dataYear = moment('01 '+$(this).val().replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3")).format('M-YYYY').split("-", 2)
                }

                YEAR = dataYear[1]
                MONTH = dataYear[0]

                LoadDataTable()
            }
        });

        $(document).on('change', '#COMPANY', function() {
            showBank($(this).val())
        });

    });

    function showBank(COMPANYBANK) {
        $('.dataBankOptions').remove()
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Payment/DtBankCompany'); ?>",
            data: {
                COMPANY: COMPANYBANK
            },
            success:function(response)
            {
                let data = JSON.parse(response)
                let options = ''
                let selected = '';

                data.result.data.forEach( function(value, key) {
                    selected = (value.ISDEFAULT == 1) ? value.FCCODE : selected;
                    let Default = (value.ISDEFAULT == 1) ? '(Default)' : '';
                    options += '<option class="dataBankOptions" value="'+ value.FCCODE +'" '+selected+'>'+ value.BANKACCOUNT +' - '+ value.FCNAME +' - '+ value.CURRENCY +' '+ Default +'</option>'
                })

                $(options).insertAfter("#BANKCODE option:first");
                
            },
            error: function (e) {
                alert('Please Check Your Connection !!!');
            }
        });
    }

    function LoadDataTable() {
		if (!$.fn.DataTable.isDataTable('#DtInvoice')) {
            $('#DtInvoice').DataTable({
                "processing": true,
                "ajax": {
                    "url": "<?php echo site_url('Cashflow/ShowData') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function (d) {
                        d.BANKCODE = $('#BANKCODE').val();
                        d.DATEFROM = moment($('#FROMDATE2').val()).format('MM/DD/YYYY');
                        d.DATETO = moment($('#TODATE2').val()).format('MM/DD/YYYY');
                    },
                    "dataSrc": function (ext) {
                        if (ext.status == 200) {
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
                    {
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
					{
                        "data": "PAYMENTTYPE",
                        "orderable": false
                    },
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
                // "bFilter": false,
                "order": [[ 0, 'asc' ]],
                "columnDefs": [
                    { "visible": false, "targets": 0 }
                ],
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page: 'current'} ).nodes();             
                    var myData = api.rows({ page: 'current' }).data();
                    var last = null;
                    api.column(0, {page: 'current'} ).data().each( function ( group, i, $currTable ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                '<tr>\n\
                                <th  colspan="9" style="border-right:0; background-color:#bee5eb">Opening Balance</th>\n\
                                <th class="text-right" style="background-color:#bee5eb">'+fCurrency($currTable.rows(rows[i]._DT_RowIndex).data()[0].OPENING)+'</th>\n\
                                </tr>'
                            );
        
                            last = group;
                        }
                    } );

                    api.rows({ page: 'current' }).invalidate();
                    // console.log(myData)
                },
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api(), data;
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                    };
                    // Total over all pages
                    totalDebit = api.column(8).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    totalCredit = api.column(9).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;

                    $(api.column(8).footer()).html(numFormat(totalDebit));
                    $(api.column(9).footer()).html(numFormat(totalCredit));
                }
            });

           $('#DtInvoice_filter').remove()

            $('#searchInvoice').on( 'input', function () {
                table2.search( this.value ).draw();
            });

            $('#DtInvoice thead th').addClass('text-center');
            table2 = $('#DtInvoice').DataTable();

        } else {
            table2.ajax.reload();
        }
    }

    var searchCashflow = function () {
        let monthOnlyFromDate = moment($('#FROMDATE2').val()).format('YYYY');
        let monthOnlyToDate = moment($('#TODATE2').val()).format('YYYY');

        if ($('#FROMDATE2').val() == '' || $('#TODATE2').val() == '' || $('#FROMDATE2').val() == null || $('#TODATE2').val() == null) {
            alert("'From Date' & 'To Date' can not be empty!");
            return;
        } else if(monthOnlyFromDate !== monthOnlyToDate) {
            alert('Range date must be on the same year!');
            return;
        } else {
            LoadDataTable()
        }
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
	
    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

</script>