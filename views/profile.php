<?php
include 'main.php';
?>
<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="home" aria-selected="true">Profile Detail</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab" aria-controls="profile" aria-selected="false">Change Password</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row justify-content-center mt-3">
                    <div class="col-md-6">
                        <form id="profileForm">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label"><b>Name:</b></label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="<?=  $_SESSION['username']?>">
                                <span class="text-danger" id="nameError"></span>
                            </div>
                            <div class="form-group mb-3">
                                <label for="name" class="form-label"><b>Email Adress:</b></label>
                                <input type="text" name="email" class="form-control" placeholder="Email Address" value="<?=  $_SESSION['user_email']?>">
                                <span class="text-danger" id="emailError"></span>
                            </div>
                            <div id="emailError" style="color:red;"></div>
                            <div class="d-grid mx-auto">
                                <button type="submit" class="btn btn-primary btn-block mb-4">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                <div class="row justify-content-center mt-3">
                    <div class="col-md-6">
                        <form id="passwordForm">
                            <div class="form-group mb-3">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" value="">
                                <span class="text-danger" id="passwordError"></span>
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" placeholder="confirm Password" value="">
                                <span class="text-danger" id="confirmError"></span>
                            </div>
                            <div class="d-grid mx-auto">
                                <button type="submit" class="btn btn-primary btn-block mb-4">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
            $('#profileForm').on('submit', function(e) {
                e.preventDefault();
                let form = $("#profileForm")[0];
                let formData = new FormData(form);
                let object = {};
                formData.forEach((value, key) => { object[key] = value });
                let json = JSON.stringify(object);
                $.ajax({
                    url: 'http://localhost/admin_panel/public/profile?action=profileUpdate',
                    type: 'POST',
                    contentType: 'application/json',
                    data: json,
                    dataType: 'json',
                    success: function(response) {
                        if (response.errors) {
                        showErrorMessages(response.errors);
                        } else {
                            alert(response.message);
                            window.location.reload(); 
                        }
                        $('#nameError').text('');
                        $('#emailError').text('');
                    },
                    error: function(response) {
                        let errors = response.responseJSON.errors;
                        $('#nameError').text(errors.name ? errors.name : '');
                        $('#emailError').text(errors.email ? errors.email : '');
                    }
                });
            });
       
            $('#passwordForm').on('submit', function(e) {
                e.preventDefault();
                let form = $("#passwordForm")[0];
                let formData = new FormData(form);
                let object = {};
                formData.forEach((value, key) => { object[key] = value });
                let json = JSON.stringify(object);
                $.ajax({
                    url: 'http://localhost/admin_panel/public/profile?action=passwordChange',
                    type: 'POST',
                    contentType: 'application/json',
                    data: json,
                    dataType: 'json',
                    success: function(response) {
                        $('#passwordError').text('');
                        $('#confirmError').text('');
                        $('#password, #confirmPassword').val('');
                    },
                    error: function(response) {
                        let errors = response.responseJSON.errors;
                        $('#passwordError').text(errors.password ? errors.password : '');
                        $('#confirmError').text(errors.confirmPassword ? errors.confirmPassword : '');
                    }
                });
            });
        });
        function showErrorMessages(errors) {
        for (const [field, message] of Object.entries(errors)) {
            const errorElementId = `#${field}Error`;
            $(errorElementId).text(message);
        }
    }
</script>