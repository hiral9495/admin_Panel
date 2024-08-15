<?php
require_once "header.php"; 
?>
    <div class="row justify-content-center">
    <div id="errorMessages" class="alert alert-danger" style="display: none;"></div>
	<div class="col-md-4">
		<div class="card">
		<div class="card-header header-text">REGISTRATION</div>
		<div class="card-body">
			<form  id="registerForm" action="" method="POST">

				<div class="form-group mb-3">
					<input type="text" name="name" class="form-control" placeholder="Name" value="">
					<span class="text-danger error-div" id="nameError"></span>
				</div>
				<div class="form-group mb-3">
					<input type="text" name="email" class="form-control" placeholder="Email Address" value="" >
					<span class="text-danger error-div" id="emailError"></span>
				</div>
				<div class="form-group mb-3">
					<input type="password" name="password" class="form-control" placeholder="Password" value="">
					<span class="text-danger error-div" id="passwordError"></span>
				</div>
				<div class="form-group mb-3">
					<input type="password" name="confirmPassword" class="form-control" placeholder="confirm Password" value="">
					<span class="text-danger error-div" id="confirmPasswordError"></span>
				</div>
				<div class="form-group mb-3">
					<select class="form-select" aria-label="Default select example" name="userRole">	
					</select>
				</div>
				<div class="d-grid mx-auto">
					<button type="button" class="btn btn-primary btn-block mb-4" id="registerButton">Register</button>
				</div>
			</form>
		</div>
	</div>
</div>
</body>
</html>