<div ng-controller="signupCtrl" class="register-box">
  <div class="register-logo" style="text-shadow: 0px 2px 5px rgba(0,0,0,0.3);">
    <a href="../../index2.html"><b>Lnsel</b>Sales</a>
  </div>

  <div class="register-box-body" style="box-shadow: 0 6px 6px -1px rgba(0,0,0,0.5);">
    <p class="login-box-msg">Register a new membership</p>

    <form name="signupForm" novalidate>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" ng-model="userName" name="userName" placeholder="Username" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
        <span style="color:red;" ng-show="signupForm.userName.$dirty && signupForm.userName.$invalid">
          <span ng-show="signupForm.userName.$error.required">Username is required</span>
        </span>
      </div>
      <div class="form-group has-feedback">
        <input type="email" class="form-control" ng-model="userEmail" name="userEmail" placeholder="Email" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        <span style="color:red;" ng-show="signupForm.userEmail.$dirty && signupForm.userEmail.$invalid">
          <span ng-show="signupForm.userEmail.$error.required">Email is required</span>
          <span ng-show="signupForm.userEmail.$error.email">Invalid email address</span>
        </span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" ng-model="userPassword" name="userPassword" placeholder="Password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <span style="color:red;" ng-show="signupForm.userPassword.$dirty && signupForm.userPassword.$invalid">
          <span ng-show="signupForm.userPassword.$error.required">Password is required</span>
        </span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" ng-model="userPasswordC" name="userPasswordC" placeholder="Retype password" required>
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
        <span style="color:red;" ng-show="signupForm.userPasswordC.$dirty && signupForm.userPasswordC.$invalid">
          <span ng-show="signupForm.userPasswordC.$error.required">Password Confirmation is required</span>
        </span>
      </div>
      <div class="row">
        <div class="col-xs-8">
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" ng-click="submitSignup()" ng-disabled="signupForm.$invalid" class="btn btn-primary btn-block btn-flat">Register</button>
        </div>
        <!-- /.col -->
      </div>
    </form>


    <a href="login" class="text-center">I already have a membership</a>
  </div>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->