
<script src="<?php echo base_url() ?>/assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>/assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>/assets/plugins/DataTables/extensions/Buttons/js/buttons.flash.min.js"></script>	
<script src="<?php echo base_url() ?>/assets/plugins/parsley/dist/parsley.js"></script>
<script src="<?php echo base_url() ?>/assets/plugins/highlight/highlight.common.js"></script>
<script>
    var editor;
    var save_method; //for save method string

    $(document).ready(function () {
        $('.selectpicker').selectpicker('render');
        $("#datecreated").datepicker("setDate", new Date());

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

        if (getUrlParameter('type') == "add") {
            save_method = 'add';

        } else if (getUrlParameter('type') == "edit") {
            save_method = 'edit';
            $.ajax({
                url: "<?php echo site_url('ExtSystem/ajax_edit/') ?>/" + getUrlParameter('fccode'),
                type: "GET",
                dataType: "JSON",
                success: function (data)
                {
                    //alert(data.OVERTIME_TYPE);
                    $('[name="fccode"]').val(data.FCCODE);
                    $('[name="fcname"]').val(data.FCNAME);
                    $('[name="description"]').val(data.DESCRIPTION);

                    if (data.ISACTIVE == 'TRUE') {
                        $("#isactive").prop('checked', true);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
            $("#fccode").prop('readonly', true);
        } else
        {
            table = $('#table').DataTable({
                "dom": "Bfrtip",
                buttons: [{
                        text: '+ Add External System',
                        className: "btn btn-white btn-sm",
                        action: function (e, dt, node, config) {
                            location.href = '<?php echo site_url('ExtSystem?type=add&fcba=' . $this->session->userdata('fcba')) ?>';
                        }
                    }],
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.

                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('ExtSystem/ajax_list') ?>",
                    "type": "POST"
                },

                //Set column definition initialisation properties.
                "columnDefs": [{
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                    }, ],
            });
        }
    });

    $("#form_edit").on("submit", function (e) {

        if ($(this).parsley().isValid()) {
            e.preventDefault(),
                    swal({
                        title: "Do you really want to save ?",
                        text: "",
                        icon: "info",
                        buttons: {
                            cancel: {
                                text: "Cancel",
                                value: null,
                                visible: !0,
                                className: "btn btn-default btn-sm",
                                closeModal: !0
                            },
                            confirm: {
                                text: "Save",
                                value: !0,
                                visible: !0,
                                className: "btn btn-info btn-sm",
                                closeModal: !0
                            }
                        }
                    }).then(function (isConfirm) {
                if (isConfirm) {
                    var url;
                    if (save_method == 'add')
                    {
                        url = "<?php echo site_url('ExtSystem/ajax_add') ?>";
                    } else
                    {
                        url = "<?php echo site_url('ExtSystem/ajax_update') ?>";
                    }

                    // ajax adding data to database
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: $('#form_edit').serialize(),
                        dataType: "JSON",
                        success: function (data) {
                            //if success close modal and reload ajax table
                            window.location.href = "<?php echo site_url() . '/ExtSystem' ?>";
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert('Error adding / update data');
                        }
                    });
                }
            });
        }

    });

    $("#btnBack").click(function () {
        $("#btnPrint").hide();
        $("#btnDelete").hide();
        $("#btnBack").hide();
        window.location = "<?php echo site_url() . '/ExtSystem' ?>";
    });

    function delete_index(fccode) {
        if (confirm('Are you sure delete this data?'))
        {
            // ajax delete data from database
            $.ajax({
                url: "<?php echo site_url('ExtSystem/ajax_delete_index') ?>/" + fccode,
                type: "POST",
                dataType: "JSON",
                success: function (data)
                {
                    $('#table').DataTable().ajax.reload();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });

        }
    }
</script>