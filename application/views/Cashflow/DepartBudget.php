<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Department Budget</li>
</ol>
<h1 class="page-header">Department Budget</h1>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Department Budget</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="form-row">
                    <div class="col">
                        <label for="PERIOD">Period</label>
                        <input type="text" class="form-control" name="PERIOD" id="PERIOD" placeholder="MMM YYYY">
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-0 table-responsive">
            <table id="DtForecast" class="table table-bordered table-striped table-hover dataTable no-footer dtr-inline" role="grid" width="100%" style="width: 100%;">
                <thead>
                    <tr role="row">
                        <th class="text-center align-middle" aria-sort="ascending" style="width: 30px;">No</th>
                        <th class="text-center align-middle">Departement Code</th>
                        <th class="text-center align-middle">Departement Name</th>
                        <th class="text-center align-middle">Budget</th>
                        <th class="text-center align-middle"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var YEAR = 0, MONTH = 0, table, table2, DEPTID = "", BUDGET = 0;
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    
    $('#PERIOD').datepicker({
        "autoclose": true,
        "todayHighlight": true,
        "viewMode": "months",
        "minViewMode": "months",
        "format": "M yyyy"
    });

    jQuery(document).ready(function($) {
        if (!$.fn.DataTable.isDataTable('#DtForecast')) {
            $('#DtForecast').DataTable({
                "processing": true,
                "ajax": {
                    "url": "<?php echo site_url('Budget/ShowData') ?>",
                    "type": "POST",
                    "data": function (d) {
                        d.YEAR = YEAR;
                        d.MONTH = MONTH;
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
                    {"data": "FCCODE"},
                    {"data": "FCNAME"},
                    {
                        "data": null,
                        render: function (data, type, row, meta) {
                            return '<input class="form-control form-control-sm text-right form'+meta.row+'" type="text" name="BUDGET" data-type="currency" value="'+formatRupiah(data.BUDGET)+'" readonly>';
                        }
                    },
                    {
                        "data": null,
                        render: function (data, type, row, meta) {
                            var html = '';
                            if (EDITS == 1) {
                                html += '<button class="btn btn-success btn-icon btn-circle btn-sm edit edit'+meta.row+'" title="Edit" style="margin-right: 5px;" id="'+meta.row+'">\n\
                                        <i class="fa fa-edit" aria-hidden="true"></i>\n\
                                        </button>';
                                html += '<button class="btn btn-primary btn-icon btn-circle btn-sm save save'+meta.row+'" title="Edit" style="margin-right: 5px;" id="'+meta.row+'" data-month="'+data.MONTH+'" data-week="'+data.WEEK+'" hidden>\n\
                                        <i class="fa fa-save" aria-hidden="true"></i>\n\
                                        </button>';
                                html += '<button class="btn btn-danger btn-icon btn-circle btn-sm cancel cancel'+meta.row+'" title="Edit" style="margin-right: 5px;" id="'+meta.row+'" data-month="'+data.MONTH+'" data-week="'+data.WEEK+'" hidden>\n\
                                        <i class="fa fa-trash" aria-hidden="true"></i>\n\
                                        </button>';
                            }
                            return html;
                        }
                    },
                ],
                "bFilter": true,
                "bPaginate": true,
                "bLengthChange": false,
                "bInfo": true,
            });
            
            table = $('#DtForecast').DataTable();
            table.on('click', '.edit', function () {
                $('.form'+$(this).attr('id')).removeAttr("readonly")
                $('.save'+$(this).attr('id')).removeAttr("hidden")
                $('.cancel'+$(this).attr('id')).removeAttr("hidden")
                $('.edit'+$(this).attr('id')).attr("hidden", true)
            });
            table.on('click', '.save', function () {
                $tr = $(this).closest('tr');
                var data = table.row($tr).data();
                DEPTID = data.FCCODE
                BUDGET = formatDesimal($('.form'+$(this).attr('id')).val())
                
                $('.form'+$(this).attr('id')).attr("readonly", true)
                $('.save'+$(this).attr('id')).attr("hidden", true)
                $('.cancel'+$(this).attr('id')).attr("hidden", true)
                $('.edit'+$(this).attr('id')).removeAttr("hidden")
                Save()
            });
            table.on('click', '.cancel', function () {
                $('.form'+$(this).attr('id')).attr("readonly", true)
                $('.save'+$(this).attr('id')).attr("hidden", true)
                $('.cancel'+$(this).attr('id')).attr("hidden", true)
                $('.edit'+$(this).attr('id')).removeAttr("hidden")
            });

        }
    })

    $('#PERIOD').on({
        'change': function () {
            MONTH = ListBulan.indexOf(this.value.substr(0, 3)) + 1;
            YEAR = this.value.substr(4, 4);
            table.ajax.reload();
        }
    });

    var Save = function () {
        $.ajax({
            dataType: "JSON",
            type: "POST",
            url: "<?php echo site_url('Budget/Save'); ?>",
            data: {
                MONTH: MONTH,
                YEAR: YEAR,
                ID: DEPTID,
                BUDGET: BUDGET,
                USERNAME: USERNAME
            },
            success: function (response) {
                if (response.status == 200) {
                    alert(response.result.data);
                } else if (response.status == 504) {
                    alert(response.result.data);
                    location.reload();
                } else {
                    alert(response.result.data);
                }
            },
            error: function (e) {
                $("#loader").hide();
                alert('Data Save Failed !!');
                $('#btnSave').removeAttr('disabled');
            }
        });
    }

    $(document).on("input", "input[data-type='currency']", function() {
        formatCurrency($(this), 'blur');
    })

    function formatNumber(n) {
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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

    function formatDesimal(n) {
        return n.replace(/[^0-9.-]+/g, "");
    }

    function formatRupiah(angka){
        var	reverse = angka.toString().split('').reverse().join(''),
        ribuan = reverse.match(/\d{1,3}/g);
        return ribuan = ribuan.join(',').split('').reverse().join('') + '.00';
    }
</script>