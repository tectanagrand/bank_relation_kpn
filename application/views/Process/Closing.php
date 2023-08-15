<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Closing</li>
</ol>
<h1 class="page-header">Closing</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title">Closing Period Control</h4>
    </div>
    <div class="panel-body">
            <div class="row mb-2">
                <div class="col-md-12 mb-2">
                <div class="form-row">
                    <div class="col-4">
                        <label>Company Group</label>
                        <select class="form-control COMPANYGROUP" id="COMPANYGROUP" name='COMPANYGROUP'>
                        <option value="" selected>-Choose-</option>
                        <option value="CMT">CEMENT</option><option value="MOTIVE">MOTIVE</option><option value="PLT">PLANTATION</option><option value="PROPERTY">PROPERTY</option><option value="WOOD">WOOD</option>
                    </select>
                    </div>
                    <div class="col-4">
                        <label for="COMPANY">Company Subgroup</label>
                        <select class="form-control COMPANYSUBGROUP" id="COMPANYSUBGROUP" name='COMPANYSUBGROUP'>
                            <option value="" selected>-Choose-</option>
                            <option value="UPSTREAM">UPSTREAM</option>
                            <option value="DOWNSTREAM">DOWNSTREAM</option></select>
                        </select>
                    </div>
                    <!-- <div class="col-4">
                        <label for="COMPANY">Company</label>
                        <select class="form-control mkreadonly" name="COMPANY" id="COMPANY">
                            <option value="">Choose Company</option>
                            <?php
                            foreach ($DtCompany as $values) {
                                echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                            }
                            ?>
                        </select>
                    </div> -->
                    <!-- <div class="col-4">
                        <label for="PERIOD">Current Period</label>
                        <input type="text" class="form-control" name="PERIOD" id="PERIOD" autocomplete="off" disabled="">
                    </div> -->
                    <!-- <div class="col-4">
                        <label for="PERIOD">Period</label>
                        <input type="text" class="form-control" name="PERIOD" id="PERIOD" placeholder="MMM YYYY" autocomplete="off">
                    </div> -->
                    <div class="col-4 mt-4">
                        <button type="button" id="ProcessPeriod" class="btn btn-info" style="padding: 3px 10px;">Process</button>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <!-- <div class="alert alert-muted fade show ml-2" style="width:33%;color: #21282c;background-color: #d8dde0;border-color: #627884;">
            
            </div> -->
            <div class="row m-0 table-responsive">
                <table id="DtLeasing" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" role="grid" width="100%" aria-describedby="DtLeasing_info" style="width: 100%;">
                    <thead>
                        <tr role="row">
                            <th class="text-center sorting_asc" style="width: 30px;">#</th>
                            <th class="text-center sorting">Company Name</th>
                            <th class="text-center sorting">Current Date</th>
                            <th class="text-center sorting">Current Accounting Year</th>
                            <th class="text-center sorting">Current Accounting Period</th>
                            <th class="text-center sorting">Close Accounting Year</th>
                            <th class="text-center sorting">Close Accounting Period</th>
                            <!-- <th class="text-center sorting_disabled" aria-label="Action"></th> -->
                        </tr>
                    </thead>
                </table>
            </div>
    </div>
</div>

<script>
    // var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    // var DELETES = <?php echo $ACCESS['DELETES']; ?>;
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
    var YEAR, MONTH, COMPANY;
    var DtElog = [];
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    
        
</script>
<script>
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
                mSel+= '<option value="" selected>All</option></select>';
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
            COMPANYSUBGROUP = $(this).val();
            LoadDataTable();
            // loadData();
        }
    });

    function LoadDataTable(){
        if (!$.fn.DataTable.isDataTable('#DtLeasing')) {
                $('#DtLeasing').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "pageLength": 25,
                    "ajax": {
                        "url": "<?php echo site_url('Payment/getCompanyClosing') ?>",
                        "type": "POST",
                        "datatype": "JSON",
                        "data": function (d) {
                            // d.USERNAME = USERNAME;
                            d.SUBGROUP = COMPANYSUBGROUP;
                            // d.COMPANY  = optCOMPANY;
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
                    "columns": [{
                            "data": null,
                           "className": "text-center align-middle",
                                    "orderable": false,
                                    render: function (data, type, row, meta) {
                                        var html = '<input type="checkbox" name="pils" class="pils" data-id="'+data.ID+'">';
                                        return html;
                                    }
                        },
                        {"data": "COMPANYNAME"},
                        {"data": "CURRENTDATE",
                            "render": function (data) {
                            var d = new Date(data);
                            return d.toLocaleDateString();
                            }
                        },
                        {"data": "CURRENTACCOUNTINGYEAR"},
                        {"data": "CURRENTACCOUNTINGPERIOD"},
                        {"data": "CLOSEACCOUNTINGYEAR"},
                        {"data": "CLOSEACCOUNTINGPERIOD"}
                    ],
                    "bFilter": true,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bInfo": true
                });

                $('#DtLeasing_filter').remove()

                $('#searchInvoice').on( 'input', function () {
                    table.search( this.value ).draw();
                });

                $('#DtLeasing thead th').addClass('text-center');
                table = $('#DtLeasing').DataTable();
                table.on('change', '.pils', function () {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();

                    if (this.checked) {
                        $(this).closest('tr').addClass('selected');
                        data.FLAG  = "1";
                        console.log(data.FLAG)
                    } else {
                        $(this).closest('tr').removeClass('selected');
                        data.FLAG = "0";
                        console.log(data.FLAG)
                    }
                });
            } else {
                table.ajax.reload();
            }
    }

    $('body').on('click','#ProcessPeriod',function(){

        $(this).prop('disabled', true); 
        $('#loader').addClass('show');
        $.each(table.data(), function (index, value) {
            
            if (value.ID == undefined || value.ID == null || value.ID == '') {
            } else {

                if (value.FLAG == "1" || value.FLAG == 1) {
                    DtElog.push(value);
                }
            }
        });
        $.ajax({
                "type": "POST",
                "datatype": "application/json",
                "url": "<?php echo site_url('Payment/saveClosingNew'); ?>",
                "data": {
                   DtClosing: DtElog
                },
                success: function (response) {
                    //$("#page-loader").addClass('d-none');
                    console.log(response.result.data)
                    if (response.status == 200) {
                        alert(response.result.data);
                        LoadDataTable();
                        // table.ajax.reload();
                    } else if (response.status == 504) {
                        alert(response.result.data);
                        // table.ajax.reload();ss
                    } else {
                        alert(response.result.data);
                    }
                    DtElog = [];
                },
                error: function (e) {
                    //$("#page-loader").addClass('d-none');
                    // console.info(e);
                    alert('Data Save Failed !!');   
                    $('#loader').removeClass('show');
                    DtElog = [];
                }
            });
        
        
        $(this).prop('disabled', false);
        $('#loader').removeClass('show');
        

        // if($('#COMPANY').val() == '' || $('#COMPANY').val() == null){
        //     alert('Cant empty');
        // }else{
        //     $("#loader").show();
        //     // table.ajax.reload();
        //     $.ajax({
        //         dataType: "JSON",
        //         type: "POST",
        //         url: "<?php echo site_url('Payment/saveClosing'); ?>",
        //         data: {
        //             COMPANY: COMPANY,
        //             USERNAME: USERNAME
        //         },
        //         success: function (response) {
        //             $("#loader").hide();
        //             if (response.status == 200) {
        //                 alert(response.result.data);
        //                 // $('.PAY').attr('disabled',true);
        //                 table.ajax.reload();
        //             } else if (response.status == 504) {
        //                 alert(response.result.data);
        //                 location.reload();
        //             } else {
        //                 alert(response.result.data);
        //             }
        //         },
        //         error: function (e) {
        //             $("#loader").hide();
        //             // console.info(e);
        //             alert('Data Save Failed !!');
        //             $('#btnSave').removeAttr('disabled');
        //         }
        //     });
        // }
        
        
    });

    $('#COMPANY').on({
        'change': function () {
            COMPANY = $(this).val();
            $('#loader').addClass('show');
            // table.ajax.reload();
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Payment/getPaymentPeriod'); ?>",
                data: {
                    COMPANY: COMPANY,
                    USERNAME: USERNAME
                },
                success: function (response) {
                    $('#loader').removeClass('show');
                    if (response.status == 200) {
                        // alert(response.result.data);
                        $('#PERIOD').val(response.result.data.CURRENTACCOUNTINGPERIOD + ' - ' + response.result.data.CURRENTACCOUNTINGYEAR);
                        // table.ajax.reload();
                    } else if (response.status != 200) {
                        alert(response.result.data);
                         $('#ProcessPeriod').attr('disabled',true);
                    } else {
                        alert(response.result.data);
                         $('#ProcessPeriod').attr('disabled',true);
                    }
                },
                error: function (e) {
                    $('#loader').removeClass('show');
                    // console.info(e);
                    alert('Data Failed !!');
                    $('#ProcessPeriod').attr('disabled',true);
                }
            });
        }
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