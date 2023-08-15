<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link href="./assets/css/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet">

<style>
    .listdataAjaxVendor {
        max-height: 200px;
        width: 100%;
        background-color: white;
        border: 1px solid #d3d8de;
        white-space: nowrap;
        position: relative;
        overflow-y: scroll;
    }
    .listDataVENDOR {
        padding: 0;
        margin: 0;
    }
    li.AjaxAddedVendor {
        list-style-type: none;
        padding: 3px 10px;
    }
    li.AjaxAddedVendor:hover {
        background-color: #ecdcdc;
        cursor: pointer;
    }
</style>

<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Interco Loans</li>
</ol>
<h1 class="page-header">Interco Loans</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Interco Loans</h4>
    </div>
    <div class="panel-body">
        <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
            <div class="row">
                <div class="form-group col-md-9">
                    <button class="btn btn-primary" onclick="addPayment()">Add</button>
                </div>
                <!-- <div class="col-md-2 offset-md-1">
                    <input type="text" id="searchInvoice" class="form-control" placeholder="Cari.." >
                </div> -->
            </div>
            <div class="row m-0 table-responsive">
				<div style='overflow-x:hidden;width:1300px;'>
					<table id="DtInvoice" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
						<thead>
							<tr role="row">
								<th class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
								<th class="text-center sorting">Date</th>
								<th class="text-center sorting">Company Source</th>
								<th class="text-center sorting">Bank Source</th>
								<th class="text-center sorting">Company Target</th>
								<th class="text-center sorting">Bank Target</th>							
								<th class="text-center sorting">Voucher</th>
								<th class="text-center sorting">Giro</th>
								<th class="text-center sorting">Remark</th>
								<th class="text-center sorting">Source Amount</th>							
								<th class="text-center sorting">Rate</th>                            
								<th class="text-center sorting">Amount</th>
								<th class="text-center sorting" style="width: 30px;"></th>
							</tr>
						</thead>
						<tfoot>
							<tr role="row">
								<th class="text-right" colspan="11">Total :</th>
								<th class="text-right"></th>
								<th></th>
							</tr>
						</tfoot>
					</table>
				</div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="PaymentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Interco Loans</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            </div>
            <div class="modal-body">
                <form id="formAddPayment" data-toggle="validator" role="form">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="DATE_PAID">Date Paid *</label>
                            <div class="input-group date" id="DATE_PAID">
                                <input type="text" class="form-control" name="DATE_PAID" id="DATE_PAID2" required autocomplete="off">
                                <div class="input-group-addon input-group-append">
                                    <div class="input-group-text">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="form-group col-md-6">
						</div>
                        <div class="form-group col-md-5">
                            <label for="PAY_BANK">Company Source*</label>
                            <select class="form-control" name="COMPANY" id="COMPANY" required disabled>
								<option value="" selected>Choose Company</option>
								<?php
								foreach ($DtCompany as $values) {
									echo '<option value=' . $values->ID . '>' . $values->COMPANYCODE .' - '. $values->COMPANYNAME . '</option>';
								}
								?>
							</select>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="PAY_BANK">Bank Source*</label>
                            <select class="form-control mkreadonly" name="PAY_BANK" id="PAY_BANK" required disabled>
                                <option value="" selected>Choose Bank</option>
                            </select>
                        </div>
						<div class="form-group col-md-5">
                            <label for="PAY_BANK">Company Target*</label>
                            <select class="form-control" name="COMPANYTARGET" id="COMPANYTARGET" required disabled>
								<option value="" selected>Choose Company</option>
								<?php
								foreach ($DtCompany as $values) {
									echo '<option value=' . $values->ID . '>' . $values->COMPANYCODE .' - '. $values->COMPANYNAME . '</option>';
								}
								?>
							</select>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="PAY_BANK">Bank Target*</label>
                            <select class="form-control mkreadonly" name="PAY_BANKTARGET" id="PAY_BANKTARGET" required disabled>
                                <option value="" selected>Choose Bank</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="PAY_VOUCHER">Voucher *</label>
                            <input type="text" class="form-control" name="PAY_VOUCHER" id="PAY_VOUCHER" placeholder="Voucher" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="PAY_GIRO">Cek/Giro/Transfer *</label>
                            <input type="text" class="form-control" name="PAY_GIRO" id="PAY_GIRO" placeholder="Cek/Giro/Transfer" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="PAY_REMARK">Remark</label>
                            <input type="text" class="form-control" name="PAY_REMARK" id="PAY_REMARK" placeholder="Remark">
                        </div>
						<div class="form-group col-md-4">
                            <label for="PAY_PAID_AMOUNT">Exchange Rate *</label>
                            <input type="text" class="form-control text-right" name="PAY_RATE" id="PAY_RATE" data-type='currency' placeholder="Exchange Rate" required disabled>
                        </div>
						<div class="form-group col-md-4">
                            <label for="SOURCEAMOUNT">Bank Source Amount *</label>
                            <input type="text" class="form-control text-right" name="SOURCEAMOUNT" id="SOURCEAMOUNT" data-type='currency' placeholder="Bank Source Amount" required disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="PAY_PAID_AMOUNT">Transfered Amount *</label>
                            <input type="text" class="form-control text-right" name="PAY_PAID_AMOUNT" id="PAY_PAID_AMOUNT" data-type='currency' placeholder="Transfered Amount" required disabled>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="SavePayment()">Save</button>
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
    var AMOUNTSKRG = 0;
    var dataYear = [];
    var INTERCOID = '';
    var defaultBank;
	var defaultBank2;
    var ACTION;
    // var USED_BALANCE = 0;
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
    var YEAR = '', MONTH = '';

    $(document).ready(function () {
		
        $('#DATE_PAID2').datepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "autoclose":true,
             todayHighlight: true,
            "format": "mm/dd/yyyy",
        });

        $(document).on('change', '#PAY_BANK', function () {
            // getBankBalance($(this).val())
			if (INTERCOID === "") {
				$('#COMPANYTARGET').removeAttr("disabled");
				$('#COMPANYTARGET').val('');
			}
        })
		
		$(document).on('change', '#COMPANYTARGET', function () {
            // getBankBalance($(this).val())
			showBankTarget($(this).val());
			$('#PAY_BANKTARGET').removeAttr("disabled");
        })
		
		$(document).on('change', '#PAY_BANKTARGET', function () {
            // getBankBalance($(this).val())
			$('#SOURCEAMOUNT').removeAttr("disabled");
			
			$.ajax({
				url : "<?php echo site_url('IntercoLoans/validateBank/')?>",
				type: "POST",
				data: {
					CURRSOURCE:$('#PAY_BANK').val(),
					CURRTARGET: $('#PAY_BANKTARGET').val()
				},
				dataType: "JSON",
				success: function(data)
				{
					if (data.CURRSOURCE != data.CURRTARGET)
					{
						$('#PAY_RATE').removeAttr("disabled");
					}else
					{
						$('#PAY_RATE').prop("disabled", true);
						$('#PAY_RATE').val('1');
					}
					//$('[name="documentnumber"]').val(data.DOCNUMBER);
					//$('[name="documentdate"]').val(data.TDATE);
		 
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					alert('Error get data from ajax');
				}
			});
			
			//alert ($(this).find("option:selected").text());
			//alert ($('#PAY_BANK').val());
        })
		
        LoadDataTable()
		
        $(document).on('change', '#DATE_PAID2', function () { 
			
            $('#COMPANY').removeAttr("disabled");
            $('#COMPANY').val('')
			
            $('#PAY_PAID_AMOUNT').prop("disabled", true);
			$('#SOURCEAMOUNT').prop("disabled", true);
			$('#PAY_RATE').prop("disabled", true);
            $('#PAY_BANK').prop("disabled", true);
			$('#PAY_BANKTARGET').prop("disabled", true);
			
            $('.dataBankOptions').remove()
			$('.dataBankOptionsTarget').remove()
        })

        $(document).on('change', '#COMPANY', function () {
            showBank($(this).val())
        });

        $("input[data-type='currency']").on({
            keyup: function () {
                formatCurrency($(this));
            },
            blur: function () {
                formatCurrency($(this), "blur");
            }
        });

        /* SEARCH VENDOR */
        let timeOutonKeyupVendor = null
        $(document).on('input', '#VENDORNAME', function(){
            let dataKeywords = $(this).val()

            clearTimeout(timeOutonKeyupVendor);
            timeOutonKeyupVendor = setTimeout(function () {
                if (dataKeywords) {
                    $.ajax({
                        url: "<?php echo site_url('IVendor/GetDataAjax'); ?>",
                        method: "POST",
                        data: {keywords: dataKeywords},
                        success:function(response)
                        {
                            $('.listdataAjaxVendor').show()
                            $('.AjaxAddedVendor').remove()
                            let data = JSON.parse(response)
                            let options = ''
                            if (data.result.data.length > 0) {
                                data.result.data.forEach(function(value, key) {
                                    options += '<li class="AjaxAddedVendor" data-attr="'+value.ID+'" >' + value.FCNAME + '</li>'
                                })
                            } else {
                                options = '<li class="AjaxAddedVendor">Tidak ada data.</li>'
                            }
                            
                            $('ul#listVENDOR').append(options)
                        }
                    })
                } else {
                    alert('Input supplier tidak boleh kosong!')
                    $('.listdataAjaxVendor').hide()
                    return false
                }
            }, 1000);
        })

        /* SET VENDOR */
        $(document).on('click', '.AjaxAddedVendor', function() {
            SelectedCat = []
            $('.listdataAjaxVendor').hide()
            $('#VENDORNAME').val($(this).text())
            $('#VENDOR').val($(this).attr('data-attr'))
        })

        /* REMOVE MATERIAL CONTAINER */
        $(document).on('click', 'body', function() {
            $('.AjaxAddedVendor').remove()
            $('.listdataAjaxVendor').hide()
        })
		
		$('#SOURCEAMOUNT').on('keyup', function() {
			
			var pay_rate = $('#PAY_RATE').val().replace(/[^0-9.-]+/g,"");
			var source_amount = $(this).val().replace(/[^0-9.-]+/g,"");
			var fixamount = 0;
			
			$.ajax({
				url : "<?php echo site_url('IntercoLoans/validateBank/')?>",
				type: "POST",
				data: {
					CURRSOURCE:$('#PAY_BANK').val(),
					CURRTARGET: $('#PAY_BANKTARGET').val()
				},
				dataType: "JSON",
				success: function(data)
				{
					
					if (data.CURRSOURCE === 'USD' && data.CURRTARGET === 'IDR' || data.CURRSOURCE === 'US$' && data.CURRTARGET === "IDR")  {
						formatCurrency($('#PAY_PAID_AMOUNT').val(pay_rate * source_amount), 'blur')					
					}else if (data.CURRSOURCE === 'IDR' && data.CURRTARGET === 'USD' || data.CURRSOURCE === 'IDR' && data.CURRTARGET === 'US$')  {
						formatCurrency($('#PAY_PAID_AMOUNT').val(source_amount / pay_rate ), 'blur')					
					}else
					{
						formatCurrency($('#PAY_PAID_AMOUNT').val(pay_rate * source_amount), 'blur')
					}
					
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					alert('Error get data from ajax');
				}
			});
			
			
			//alert($('#PAY_RATE').val());
		});

    });

    function showBank(CompanyID) {
        $('.dataBankOptions').remove()
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Payment/DtBankCompany'); ?>",
            data: {
                COMPANY: CompanyID
            },
            success:function(response)
            {
                let data = JSON.parse(response)
                let options = ''
                data.result.data.forEach( function(value, key) {
                    let Default = (value.ISDEFAULT == 1) ? '(Default)' : '';
                    options += '<option class="dataBankOptions" value="'+ value.FCCODE +'">'+ value.BANKACCOUNT +' - '+ value.FCNAME +' - '+ value.CURRENCY +' '+ Default +'</option>'
                })
                $(options).insertAfter("#PAY_BANK option:first");
                $('#PAY_BANK').removeAttr("disabled");

                if (defaultBank) {
                    $('#PAY_BANK').val(defaultBank)
                    // getBankBalance(defaultBank)
                } else {
                    $('#PAY_BANK').val('')
                }
            },
            error: function (e) {
                alert('Please Check Your Connection !!!');
            }
        });
    }
	
	function showBankTarget(CompanyID) {
		//alert (defaultBank2);
        $('.dataBankOptionsTarget').remove()
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Payment/DtBankCompany'); ?>",
            data: {
                COMPANY: CompanyID
            },
            success:function(response)
            {
                let data = JSON.parse(response)
                let options = ''
                data.result.data.forEach( function(value, key) {
                    let Default = (value.ISDEFAULT == 1) ? '(Default)' : '';
                    options += '<option class="dataBankOptionsTarget" value="'+ value.FCCODE +'">'+ value.BANKACCOUNT +' - '+ value.FCNAME +' - '+ value.CURRENCY +' '+ Default +'</option>'
                })
                $(options).insertAfter("#PAY_BANKTARGET option:first");
                $('#PAY_BANKTARGET').removeAttr("disabled");

                if (defaultBank2) {
                    $('#PAY_BANKTARGET').val(defaultBank2)
                    // getBankBalance(defaultBank2)
                } else {
                    $('#PAY_BANKTARGET').val('')
                }
            },
            error: function (e) {
                alert('Please Check Your Connection !!!');
            }
        });
    }
	
    function addPayment() {
        ACTION = 'ADD';
        $('#formAddPayment').parsley().reset();
        $('.dataBankOptions').remove()
		$('.dataBankOptionsTarget').remove()
		 
        $('#PAY_PAID_AMOUNT').prop("disabled", true);
		$('#SOURCEAMOUNT').prop("disabled", true);
		$('#PAY_RATE').prop("disabled", true);
        $('#PAY_BANK').prop("disabled", true);
        $('#COMPANY').prop("disabled", true);
        $('#PAY_BANKTARGET').prop("disabled", true);
        $('#COMPANYTARGET').prop("disabled", true);
		
        $('#PAY_BANK').val('')
        $('#COMPANY').val('')
		$('#PAY_BANKTARGET').val('')
        $('#COMPANYTARGET').val('')
        $('#PAY_VOUCHER').val('')
        $('#PAY_GIRO').val('')
        $('#DATE_PAID2').val('')
        $('#PAY_REMARK').val('')
		formatCurrency($('#SOURCEAMOUNT').val(''), 'blur')
        formatCurrency($('#PAY_PAID_AMOUNT').val(''), 'blur')
		formatCurrency($('#PAY_RATE').val(''), 'blur')

        $('#PaymentModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    function LoadDataTable() {
        if (!$.fn.DataTable.isDataTable('#DtInvoice')) {
            $('#DtInvoice').DataTable({
                "processing": true,
                "responsive":true,
                "ajax": {
                    "url": "<?php echo site_url('IntercoLoans/SearchIntercoLoans') ?>",
                    "type": "POST",
                    "datatype": "JSON",
                    "data": function (d) {
                        d.USERNAME = USERNAME;
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
                "columns": [{
                        "data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
					{"data": "DATERELEASE"},					
					{"data": "COMPANYSOURCENAME"},
                    {"data": "BANKSOURCENAME"},
                    {"data": "COMPANYTARGETNAME"},
                    {"data": "BANKTARGETNAME"},
                    {"data": "VOUCHERNO"},
					{"data": "NOCEKGIRO"},
					{"data": "REMARKS"},
					{
                        "className": "text-right",
                        "data": "SOURCEAMOUNT",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
					{"data": "RATE"},
                    {
                        "className": "text-right",
                        "data": "AMOUNT",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        "orderable": false,
                        render: function (data, type, row, meta) {
                            var html = '';
                            if (EDITS == 1) {
                                html += '<button class="btn btn-success btn-icon btn-circle btn-sm edit" title="Edit" style="margin-right: 5px;">\n\
                                            <i class="fa fa-edit" aria-hidden="true"></i>\n\
                                            </button>';
                            }
                            if (DELETES == 1) {
                                html += '<button class="btn btn-danger btn-icon btn-circle btn-sm delete" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                            }
                            return html;
                        }
                    }
                ],
		        "deferRender": true,
                scrollY: 350,
                scrollX: true,
                scrollCollapse: true,
                scroller: true,
                "bFilter": true,
                "bPaginate": true,
                "bLengthChange": true,
                "bInfo": false,
                "columnDefs": [
                    { "searchable": false, "targets": 8 }
                  ],
                // "bFilter": false,
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api(), data;
                    
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                    };

                    // Total over all pages
                    totalAmountSource = api.column(11).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;

                    $(api.column(11).footer()).html(numFormat(totalAmountSource));
                }
            });

            // $('#DtInvoice_filter').remove()

            // $('#searchInvoice').on('input', function () {
            //     table2.search( this.value ).draw();
            // });

            $('#DtInvoice thead th').addClass('text-center');
            table2 = $('#DtInvoice').DataTable();

            table2.on('click', '.edit', function() {
                $tr = $(this).closest('tr');
                var data = table2.row($tr).data();

                $('#formAddPayment').parsley().reset();
				
				$('#DATE_PAID2').prop("disabled", true);
				$('#COMPANY').prop("disabled", true);
				$('#COMPANYTARGET').prop("disabled", true);
				
				
                //$('#COMPANY').removeAttr("disabled");
				//$('#COMPANYTARGET').removeAttr("disabled");

                defaultBank = data.BANKSOURCE
				defaultBank2 = data.BANKTARGET
                INTERCOID = data.INTERCOID
                showBank(data.COMPANYSOURCE)
				showBankTarget(data.COMPANYTARGET)
                //if (navigator.userAgent.indexOf("Chrome") !== -1){
					//dataYear = moment(data.DATERELEASE).format('M-YYYY').split("-", 2)
                //} else {
                    /* FOR MOZILA USERS */
                //    dataYear = moment('01 '+data.DATERELEASE.replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3")).format('M-YYYY').split("-", 2)
                //}
				//alert(data.BANKTARGET);
				
                $('#COMPANY').val(data.COMPANYSOURCE)
                $('#COMPANYTARGET').val(data.COMPANYTARGET)
                $('#PAY_BANK').val(data.BANKSOURCE)
				$('#PAY_BANKTARGET').val(data.BANKTARGET)
                $('#PAY_VOUCHER').val(data.VOUCHERNO)
                $('#PAY_GIRO').val(data.NOCEKGIRO)
                $('#DATE_PAID2').val(data.DATERELEASE)
                $('#PAY_REMARK').val(data.REMARKS)
				formatCurrency($('#SOURCEAMOUNT').val(data.SOURCEAMOUNT), 'blur')
				formatCurrency($('#PAY_RATE').val(data.RATE), 'blur')
                formatCurrency($('#PAY_PAID_AMOUNT').val(data.AMOUNT), 'blur')
				ACTION = 'EDIT';
				$('#PAY_BANK').prop("disabled", true);
				$('#PAY_BANKTARGET').prop("disabled", true);
				
                $('#PaymentModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            })

            table2.on('click', '.delete', function() {
                $tr = $(this).closest('tr');
                var data = table2.row($tr).data();
                if (confirm('Are you sure delete this data?')) {
                    $.ajax({
                        dataType: "JSON",
                        type: "POST",
                        url: "<?php echo site_url('IntercoLoans/DeleteIntercoLoans'); ?>",
                        data: {
                            INTERCOID: data.INTERCOID
                        },
                        success: function (response) {
                            if (response.status == 200) {
                                alert(response.result.data);
                                table2.ajax.reload();
                            } else if (response.status == 504) {
                                alert(response.result.data);
                                location.reload();
                            } else {
                                alert(response.result.data);
                            }
                        },
                        error: function (e) {
                            alert('Error deleting data !!');
                        }
                    });
                }
            })

        } else {
            table2.ajax.reload();
        }
    }

    var SavePayment = function () {

        // if(USERNAME != 'ERPKPN'){
        //     alert('Maintenance, Comeback later');
        // }else{
            if ($('#formAddPayment').parsley().validate()) {
                //alert($('#PAY_BANKTARGET').val());
                var pb  = $('#PAY_BANK').val();
                var pbt = $('#PAY_BANKTARGET').val();
                let dataAmount = formatDesimal($('#PAY_PAID_AMOUNT').val())
                let dataSourceAmount = formatDesimal($('#SOURCEAMOUNT').val())
                let dataRate = formatDesimal($('#PAY_RATE').val())
                if (dataAmount < 0) {
                    alert("Paid Amount must bigger than 0!")
                    return false
                } else if (dataRate < 0) {
                    alert("Rate must bigger than 0!")
                    return false
                }else if (dataSourceAmount < 0) {
                    alert("Source Amount must bigger than 0!")
                    return false
                }else if(pb == '' || pb == 'Choose Bank'){
                    alert("Choose Bank");
                    return false
                }else if(pbt == '' || pbt == 'Choose Bank'){
                    alert("Choose Bank Target");
                    return false
                }else{
                    $.ajax({
                        dataType: "JSON",
                        type: "POST",
                        url: "<?php echo site_url('IntercoLoans/SaveIntercoLoans'); ?>",
                        data: {
                            INTERCOID: INTERCOID,
                            BANKSOURCE: $('#PAY_BANK').val(),
                            BANKTARGET: $('#PAY_BANKTARGET').val(),
                            VOUCHERNO: $('#PAY_VOUCHER').val(),
                            NOCEKGIRO: $('#PAY_GIRO').val(),
                            DATERELEASE: $('#DATE_PAID2').val(),
                            REMARK: $('#PAY_REMARK').val(),
                            COMPANYSOURCE: $('#COMPANY').val(),
                            COMPANYTARGET: $('#COMPANYTARGET').val(),
                            SOURCEAMOUNT:dataSourceAmount,
                            RATE: dataRate,
                            AMOUNT: dataAmount,
                            USERNAME: USERNAME,
                            ACTION:ACTION
                        },
                        success: function (response) {
                            // alert("Maintenance, Comeback later.");
                            $("#loader").hide();
                            $('#btnSave').removeAttr('disabled');
                            INTERCOID = ''
                            if (response.status == 200) {

                                alert(response.result.data);
                                $('#PaymentModal').modal("hide");
                                table2.ajax.reload();

                            } else if (response.status == 504) {
                                alert(response.result.data);
                                location.reload();
                            } else {
                                alert(response.result.data);
                            }
                        },
                        error: function (e) {
                            console.info(e);
                            alert('Data Save Failed !!');
                        }
                    });
                }
            }    
        // }
        
		$('#DATE_PAID2').removeAttr("disabled");
    };

    function formatNumber(n) {
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    
    function formatDesimal(n) {
        return n.replace(/[^0-9.-]+/g, "");
    }

    function formatCurrency(input, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.
        // get input value
        var input_val = input.val();

        // don't validate empty input
        if (input_val === "") {
            return;
        }
        // original length
        var original_len = input_val.length;
        // initial caret position 
        var caret_pos = input.prop("selectionStart");
        // check for decimal
        if (input_val.indexOf(".") >= 0) {
            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");
            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);
            // add commas to left side of number
            left_side = formatNumber(left_side);
            // validate right side
            right_side = formatNumber(right_side);
            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }
            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);
            // join number by .
            input_val = left_side + "." + right_side;
        } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);
            input_val = input_val;
            // final formatting
            if (blur === "blur") {
                input_val += ".00";
            }
        }
        // send updated string to input
        input.val(input_val);
        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }

</script>