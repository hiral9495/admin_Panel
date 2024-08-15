<?php
   require_once 'header.php';
?>

<div class="container mt-5">
   <div class="row justify-content-center">
      <div class="col-md-4">
         <div class="card">
            <div class="card-header header-text">LOGIN</div>
            <div id="genralError"  class="text-danger error-div"></div>
            <div class="card-body">
               <form action="" method="post" id="loginForm">
                  <div class="form-group mb-3">
                     <input type="text" name="username" class="form-control" placeholder="Email" /> 
                     <span class="text-danger error-div" id="emailError"></span>
                  </div>
                  <div class="form-group mb-3">
                     <input type="password" name="password" class="form-control" placeholder="Password" value="" />
                     <span class="text-danger error-div" id="passwordError"></span> 
                  </div>
                  <div class="row mb-4">
                     <div class="col d-flex justify-content-center">
                        <!-- Checkbox -->
                        <div class="form-group">
                           <div class="custom-control custom-checkbox">
                              <input type="checkbox" name="remember" id="remember" class="custom-control-input" >
                              <label class="custom-control-label" for="remember">Remember Me</label>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <!-- Simple link -->
                        <a href="">Forgot password?</a>
                     </div>
                  </div>
                  <div class="d-grid mx-auto">
                     <button type="button" class="btn btn-primary btn-block mb-4" id="loginButton">Login</button>
                  </div>
                  <div class="text-center">
                     <p>Not a member? <a href="register">Register</a></p>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
</body>
</html>