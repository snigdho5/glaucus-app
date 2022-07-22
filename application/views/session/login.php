<div ng-controller="loginCtrl" class="login-box">
  <div class="login-logo" style="text-shadow: 0px 2px 5px rgba(0,0,0,0.3);">
    <a href="/"><b>Glaucus</b> SalesForce App</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body" style="box-shadow: 0 6px 6px -1px rgba(0,0,0,0.5);">
    <p class="login-box-msg">Sign in to start your session</p>

    <form name="loginForm" novalidate>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" ng-model="userName" name="userName" placeholder="Username" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
        <span style="color:red;" ng-show="loginForm.userName.$dirty && loginForm.userName.$invalid">
          <span ng-show="loginForm.userName.$error.required">Username is required</span>
        </span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" ng-model="userPassword" name="userPassword" placeholder="Password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <span style="color:red;" ng-show="loginForm.userPassword.$dirty && loginForm.userPassword.$invalid">
          <span ng-show="loginForm.userPassword.$error.required">Password is required</span>
        </span>
      </div>
      <div class="row">
        <div class="col-xs-8">
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" ng-click="submitLogin()" ng-disabled="loginForm.$invalid" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <!-- /.social-auth-links -->

    <!--<a href="#">I forgot my password</a><br>-->
    <!--
    <a href="signup" class="text-center">Register a new membership</a>-->

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->