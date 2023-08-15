    <div class="card p-3 bg-white rounded shadow-sm">
    <h6 class="border-gray mb-0"><i class="ti-key pr-2"></i>Change Password</h6>
</div>

<div class="row p-2 mb-2 d-flex justify-content-center">
    <div class="col-md-6 p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form id="FAddEditForm" data-parsley-validate="true" data-parsley-errors-messages-disabled onsubmit="return false">
                    <div class="form-group">
                        <label for="PASSWORD">Old Password</label>
                        <input type="password" class="form-control" id="PASSWORD" placeholder="Old Password" autofocus required>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="NPASSWORD">New Password</label>
                            <input type="password" class="form-control" id="NPASSWORD" placeholder="New Password" required>
                        </div>
                        <div class="form-group col-md-6 pb-2">
                            <label for="CNPASSWORD">Confirmation Password</label>
                            <input type="password" class="form-control" id="CNPASSWORD" placeholder="Confirmation Password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-md btn-block" onclick="Save()">Change Password<i class="fa fa-arrow-right pl-2"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";

    var Save = function () {
        if ($('#FAddEditForm').parsley().validate()) {
            if ($('#NPASSWORD').val().trim() == $('#CNPASSWORD').val().trim()) {
                $("#loader").show();
                $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('IUsers/ChangePassword'); ?>",
                    data: {
                        USERNAME: USERNAME,
                        PASSWORD: $('#PASSWORD').val().trim(),
                        NPASSWORD: $('#NPASSWORD').val().trim(),
                        CNPASSWORD: $('#CNPASSWORD').val().trim()
                    },
                    success: function (response) {
                        $("#loader").hide();
                        if (response.status == 200) {
                            alert(response.result.data);
                            $('#PASSWORD').val('');
                            $('#NPASSWORD').val('');
                            $('#CNPASSWORD').val('');
                        } else if (response.status == 504) {
                            alert(response.result.data);
                            location.reload();
                        } else {
                            alert(response.result.data);
                        }
                    },
                    error: function (e) {
                        $("#loader").hide();
                        console.info(e);
                        alert('Data Save Failed !!');
                    }
                });
            } else {
                $("#loader").hide();
                alert('Data Save Failed !!');
                swal({
                    title: 'Information',
                    text: 'New Password and Confirmation Password Not Match !!',
                    icon: 'warning',
                    button: {
                        text: 'Close',
                        value: true,
                        visible: true
                    }
                });
            }

        }
    };
</script>
