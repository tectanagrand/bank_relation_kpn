<link href="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/9.0.0/jsoneditor.css" rel="stylesheet" type="text/css">
<style>
    .close-button {
        float: left;
        width: 26px;
        height: 26px;
        padding-left: 8px;
        font-size: 18px !important;
        font-weight: bold;
        top: 13px;
        position: absolute;
        text-decoration: none !important;
    }
    .space-close {
        width: 10px;
        float: left;
        padding: 13px;
    }
</style>
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">API_CF_TRANSACTIONS</li>
</ol>
<h1 class="page-header">API_CF_TRANSACTIONS</h1>
<div class="panel panel-success">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">API_CF_TRANSACTIONS</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="form-row">
                    <div class="col-md-12 col-lg-8 pb-2">
                    <input type="text" class="form-control" id="url" placeholder="Full path api">
                    </div>
                    <div class="col-1">
                        <input type="button" class="btn btn-info btn-sm w-100" id="fetch" value="Fetch" onclick="fetch()">
                    </div>
                    <div class="col-1">
                        <input type="button" class="btn btn-info btn-sm w-100" id="fetchraw" value="Fetch RAW" onclick="fetchRaw()">
                    </div>
                    <div class="col-1">
                        <input type="button" class="btn btn-info btn-sm w-100" id="save" value="Save" disabled onclick="save()">
                    </div>
                    <div class="col-1">
                        <input type="button" class="btn btn-info btn-sm w-100" id="clear" value="Clear" disabled onclick="clearDt()">
                    </div>
                </div>
            </div>
            <div class="col-12">
                <hr />
            </div>
        </div>
        <div class="row m-0 table-responsive">
            <table id="DtUpload" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUpload_info">
                <thead>
                    <tr role="row">
                        <th class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th>
                        <th class="text-center sorting">Status</th>
                        <th class="text-center sorting">Company</th>
                        <th class="text-center sorting">Business Unit</th>
                        <th class="text-center sorting">Department</th>
                        <th class="text-center sorting">Doc Number</th>
                        <th class="text-center sorting">Doc Type</th>
                        <th class="text-center sorting">Total Include VAT</th>
                        <th class="text-center sorting">Total PPH</th>
                        <th class="sorting_disabled"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div id="loader2" class="position-absolute w-100 h-100" style="top:50%;left:50%;transform:translate(-50%, -50%);z-index:99999;background:rgba(255, 255, 255, 0.33);display:none;">
    <div class="position-absolute" style="top:50%;left:50%;transform:translate(-50%, -50%);">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#298a89" stroke="none" transform="rotate(87.4793 50 51)">
                <animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform>
            </path>
        </svg>
    </div>
</div>
<!-- Modal Upload Detial -->
<div class="modal fade" id="MUploadDetail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail PO : </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="DtUploadDetail" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="DtUploadDetail_info">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Doc Date</th>
                                <th class="text-center">Vendor</th>
                                <th class="text-center">Remark</th>
                                <th class="text-center">Material</th>
                                <th class="text-center">Amount Include VAT</th>
                                <th class="text-center">Amount PPH</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="6" class="text-right">Total:</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="RAWJson">
    <div class="modal-dialog modal-lg" style="height:90%">
        <div class="modal-content h-100">
            <div id="jsoneditor" class="h-100"></div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/9.0.0/jsoneditor.js"></script>
<script>
    let dtApiHeader = [];
    let dtApiDetail = [];
    let status = false;
    let tbHeader, tbDetail;

    $(document).ready(function () {
        dataHeader();

        $('#RAWJson').on('hidden.bs.modal', function (e) {
            $('#jsoneditor').empty();
        })
    });

    function fetch() {
        if (!$('#url').val() || $('#url').val() == '') { 
            alert('Fill the api');
            return;
        }

        $('#fetch').attr('disabled', true);
        $('#loader2').show();
        $.ajax({
            dataType: "JSON",
            type: "POST",
            url: "<?php echo site_url('Api/fetchDataCFTrans'); ?>",
            data: {
                URL: $('#url').val(),
            },
            success: function (response) {
                if (response.status == 200) {
                    if (!response.result.data.STATUS) {
                        alert(response.result.data.MESSAGE)

                    } else {
                        $('#save').removeAttr('disabled');
                        $('#clear').removeAttr('disabled');
                        status = true;
                        dtApiHeader = response.result.data.MESSAGE;
                        tbHeader.clear();
                        tbHeader.rows.add(dtApiHeader);
                        tbHeader.draw();
                    }

                } else {
                    alert(response.result.data);
                }
                
                $('#fetch').removeAttr('disabled');
                $('#loader2').hide();
            },
            error: function (e) {
                console.info(e);
                alert('Data Save Failed !!');
                $('#fetch').removeAttr('disabled');
                $('#loader2').hide();
            }
        });
    }

    function fetchRaw() {
        if (!$('#url').val() || $('#url').val() == '') { 
            alert('Fill the api');
            return;
        }

        $('#fetchraw').attr('disabled', true);
        $('#loader2').show();
        $.ajax({
            dataType: "JSON",
            type: "POST",
            url: "<?php echo site_url('Api/fetchRAW'); ?>",
            data: {
                URL: $('#url').val(),
            },
            success: function (response) {
                const container = document.getElementById('jsoneditor')

                const options = {
                    mode: 'code'
                }

                const editor = new JSONEditor(container, options, response)

                $('.jsoneditor-menu').prepend('<a href="javascript:;" type="button" class="close-button" data-dismiss="modal">X</a><div class="space-close"></div>')
                $('#RAWJson').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                
                $('#fetchraw').removeAttr('disabled');
                $('#loader2').hide();
            },
            error: function (e) {
                console.info(e);
                alert('Something Wrong!');
                $('#fetchraw').removeAttr('disabled');
                $('#loader2').hide();
            }
        });

    }

    function dataHeader() {
        if (!$.fn.DataTable.isDataTable('#DtUpload')) {
            tbHeader = $('#DtUpload').DataTable({
                "aaData": dtApiHeader,
                "columns": [
                    {
                        "data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            var html = '';
                            if (data.STATUSH == '0') {
                                html += '<span class="badge badge-pill badge-success">Done</span>';
                            } else {
                                html += '<span class="badge badge-pill badge-danger" title="' + data.MESSAGEH + '">' + data.MESSAGEH + '</span>';
                                status = false;
                            }
                            return html;
                        }
                    },
                    {"data": "COMPANYCODE"},
                    {"data": "BUSINESSUNITCODE"},
                    {"data": "DEPARTMENTCODE"},
                    {"data": "DOCNUMBER"},
                    {"data": "DOCTYPE"},
                    {
                        "data": "AMOUNT_INCLUDE_VAT",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "data": "AMOUNT_PPH",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    }, {
                        "data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            var html = '';
                            html += '<button class="btn btn-success btn-icon btn-circle btn-sm view" title="View Detail" style="margin-right: 5px;">\n\
                                            <i class="fa fa-eye" aria-hidden="true"></i>\n\
                                        </button>';
                            return html;
                        }
                    }
                ],
                "bFilter": true,
                "bPaginate": true,
                "bLengthChange": true,
                "bInfo": true,
                "responsive": false
            });

            tbHeader.on('click', '.view', function () {
                $tr = $(this).closest('tr');
                var data = tbHeader.row($tr).data();
                $(".modal-title").text("Detail PO : " + data.DOCNUMBER);
                dtApiDetail = data.datadetail;

                dataDetail();

                $('#MUploadDetail').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            });
        }
    }

    function dataDetail() {
        if (!$.fn.DataTable.isDataTable('#DtUploadDetail')) {
            tbDetail = $('#DtUploadDetail').DataTable({
                "aaData": dtApiDetail,
                "columns": [
                    {
                        "data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": null,
                        "className": "text-center",
                        render: function (data, type, row, meta) {
                            var html = '';
                            if (data.STATUSD == 0) {
                                html += '<span class="badge badge-pill badge-success">Done</span>';
                            } else {
                                html += '<span class="badge badge-pill badge-danger" title="' + data.MESSAGED + '">' + data.MESSAGED + '</span>';
                                status = false;
                            }
                            return html;
                        }
                    },
                    {"data": "DOCDATE"},
                    {"data": "VENDORCODE"},
                    {"data": "REMARKS"},
                    {"data": "MATERIALCODE"},
                    {
                        "className": "text-right",
                        "data": "AMOUNT_INCLUDE_VAT",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        "className": "text-right",
                        "data": "AMOUNT_PPH",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    }
                ],
                "bFilter": true,
                "bPaginate": true,
                "bLengthChange": true,
                "bInfo": true,
                "responsive": false,
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api(), data;
                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                    };
                    // Total over all pages
                    totalVAT = api
                            .column(6)
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                    totalPPH = api
                            .column(7)
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                    $(api.column(6).footer()).html(numFormat(totalVAT));
                    $(api.column(7).footer()).html(numFormat(totalPPH));
                }
            });

        } else {
            tbDetail.clear();
            tbDetail.rows.add(dtApiDetail);
            tbDetail.draw();
        }
    }

    function save() {
        if (status === false) {
            alert('Data masih ada yang error !!!');

        } else if (dtApiHeader.length <= 0) {
            alert('Data yang di upload tidak ada !!!');

        } else {
            $('#loader2').show();
            $('#save').attr('disabled', true);
            $('#clear').attr('disabled', true);
            $.ajax({
                dataType: "JSON",
                type: "POST",
                url: "<?php echo site_url('Api/saveDataCFTrans'); ?>",
                data: {
                    DATA: JSON.stringify(dtApiHeader),
                },
                success: function (response) {
                    if (response.status == 200) {
                        alert(response.result.data.MESSAGE);
                        $('#url').val('');
                        dtApiHeader = [];
                        tbHeader.clear();
                        tbHeader.rows.add(dtApiHeader);
                        tbHeader.draw();

                    } else if (response.status == 504) {
                        alert(response.result.data);
                        location.reload();

                    } else {
                        alert(response.result.data);
                        $('#save').removeAttr('disabled');
                        $('#clear').removeAttr('disabled');
                    }

                    status = false;
                    $('#loader2').hide();
                },
                error: function (e) {
                    alert('Data Save Failed !!');
                    $('#loader2').hide();
                    console.info(e);
                    $('#save').removeAttr('disabled');
                }
            });
        }
    }

    function clearDt() {
        status = false;
        $('#save').attr('disabled', true);
        $('#clear').attr('disabled', true);
        
        dtApiHeader = [];
        tbHeader.clear();
        tbHeader.rows.add(dtApiHeader);
        tbHeader.draw();
    }
</script>