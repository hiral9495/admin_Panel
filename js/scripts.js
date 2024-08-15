$(document).ready(function() {
    $.ajax({
        url: 'http://localhost/admin_panel/public/register?action=roles',
        type: 'GET',
        dataType: 'json',
        success: function(data) {

            if (Array.isArray(data)) {
                const $roleSelect = $('select[name="userRole"]');
                $roleSelect.empty(); // Clear existing options

                data.forEach(function(role) {
                    $roleSelect.append(
                        $('<option></option>').val(role.id).text(role.user_type)
                    );
                });
            } else {
                console.error('Unexpected response format:', data);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching roles:', textStatus, errorThrown);
        }
    });

    $('#registerButton').on('click', function(event) {
        event.preventDefault();
        $(".error-div").text('');
        let form = $("#registerForm")[0];
        let formData = new FormData(form);
        let object = {};
        formData.forEach((value, key) => {
            object[key] = value
        });
        let json = JSON.stringify(object);

        $.ajax({
            url: 'http://localhost/admin_panel/public/register',
            type: 'POST',
            contentType: 'application/json',
            data: json,
            dataType: 'json',
            success: function(response) {
                if (response.errors) {
                    showErrorMessages(response.errors);
                } else {
                    window.location.href = 'login';
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                let response;
                try {
                    response = JSON.parse(jqXHR.responseText);
                } catch (e) {
                    alert('1An unexpected error occurred.');
                    console.error('Error:', textStatus, errorThrown);
                    return;
                }
                if (response.errors) {
                    showErrorMessages(response.errors);
                } else {
                    alert('An unexpected error occurred.');
                }
            }
        });
    });

    function showErrorMessages(errors) {

        for (const [field, message] of Object.entries(errors)) {
            const errorElementId = `#${field}Error`;
            $(errorElementId).text(message);
        }
    }

    $('#loginButton').on('click', function(event) {
        $(".error-div").text('');
        event.preventDefault();
        let form = $("#loginForm")[0];
        let formData = new FormData(form);
        let object = {};
        formData.forEach((value, key) => {
            object[key] = value
        });
        let json = JSON.stringify(object);

        $.ajax({
            url: 'http://localhost/admin_panel/public/login',
            type: 'POST',
            contentType: 'application/json',
            data: json,
            dataType: 'json',
            success: function(data) {
                if (data.errors) {
                    showErrorMessages(data.errors);
                } else {
                    window.location.href = 'dashboard'; // Redirect to a secure page
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                let response;
                try {
                    response = JSON.parse(jqXHR.responseText);
                } catch (e) {
                    alert('An unexpected error occurred.');
                    console.error('Error:', textStatus, errorThrown);
                    return;
                }

                if (response.errors) {
                    showErrorMessages(response.errors);
                } else {
                    alert('An unexpected error occurred.');
                }
            }
        });
    });

    $('.loginAs').on('click', function(event) {
        event.preventDefault();
        let id = $(this).attr('id');
        $.ajax({
            url: 'http://localhost/admin_panel/public/userList',
            type: 'POST',

            contentType: 'application/json',
            data: JSON.stringify({
                'id': id
            }),
            dataType: 'json',
            success: function(response) {
                if (response.message == "Login successful") {
                    window.location.href = 'dashboard';
                }
                console.log(response.message);
            },
            error: function() {
                $('#response').html('<p style="color: red;">An error occurred. Please try again.</p>');
            }
        });
    });
});