
	<script src="<?php echo base_url()?>/assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
	<script src="<?php echo base_url()?>/assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
	<script src="<?php echo base_url()?>/assets/plugins/DataTables/extensions/Buttons/js/buttons.flash.min.js"></script>	
	<script src="<?php echo base_url()?>/assets/plugins/parsley/dist/parsley.js"></script>
	<script src="<?php echo base_url()?>/assets/plugins/highlight/highlight.common.js"></script>
<script>
var editor;
var save_method; //for save method string

$(document).ready(function() {
    App.init();
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

    table = $('#table').DataTable({
        "dom": "Bfrtip",
        buttons: [{
            text: '+ Add Bank',
            className: "btn btn-white btn-sm",
            action: function(e, dt, node, config) {
                add();
            }
        }],
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('MasterBank/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [{
            "targets": [-1], //last column
            "orderable": false, //set not orderable
        }, ],
    });
});

$("#form_edit").on("submit", function(e) {

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
            }).then(function(isConfirm) {
                if (isConfirm) {
                    // ajax adding data to database
                    //alert("detail");
                    url = "<?php echo site_url('MasterBank/ajax_add')?>";
					
                    // ajax adding data to database
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: $('#form_edit').serialize(),
                        dataType: "JSON",
                        success: function(data) {
                            //if success close modal and reload ajax table
                             $("#modal-edit").modal('hide');
							 $('#table').DataTable().ajax.reload();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert('Error adding / update data');
                        }
                    });
                }
            });
    }

});

function add() {
    save_method = 'add';
    $("#modal-edit").modal('show');
    $.ajax({
        url: "<?php echo site_url().'/MasterBank/ajax_controljob';?>",
        type: "POST",
        dataType: "JSON",
        success: function(controljob) {
            $('#controljob').empty();
            $('#controljob').append("<option value=''>Select Control Job</option>");
            $.each(controljob, function(i, controljob) {
                $('#controljob').append("<option value='" + controljob.FCCODE + "'>" + controljob.FCCODE + " - " + controljob.FCNAME + "</option>");
            });
            $('#controljob').selectpicker("refresh");
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
        }
    });
}
</script>