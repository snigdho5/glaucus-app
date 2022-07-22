<div class="wrapper" ng-controller="settingCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Setting
          <small>Change Password</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Setting</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">


      <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="/#changepassword" data-toggle="tab">Change Password</a></li>
            </ul>
            <div class="tab-content">
              <!-- /.tab-pane -->
              <div class="active tab-pane" id="changepassword">



               <div class="row">
                  <!-- left column -->
                  <div class="col-md-6">
                    <!-- general form elements -->
                      <!-- form start -->
                      <form name="addForm" novalidate>
                        <div class="box-body">
                          <div class="form-group">
                            <label for="userCurrentPassword">Current Password</label>
                            <input type="password" class="form-control" ng-model="userCurrentPassword" name="userCurrentPassword" id="userCurrentPassword" placeholder="Current Password*" required>
                            <span style="color:red;" ng-show="addForm.userCurrentPassword.$dirty && addForm.userCurrentPassword.$invalid">
                              <span ng-show="addForm.userCurrentPassword.$error.required">Current Password is required</span>
                            </span>
                          </div>
                          <div class="form-group">
                            <label for="userNewPassword">New Password</label>
                            <input type="password" class="form-control" ng-model="userNewPassword" name="userNewPassword" id="userNewPassword" placeholder="New Password*" required>
                            <span style="color:red;" ng-show="addForm.userNewPassword.$dirty && addForm.userNewPassword.$invalid">
                              <span ng-show="addForm.userNewPassword.$error.required">New Password is required</span>
                            </span>
                          </div>
                          <div class="form-group">
                            <label for="userNewPasswordC">Confirm New Password</label>
                            <input type="password" class="form-control" ng-model="userNewPasswordC" name="userNewPasswordC" id="userNewPasswordC" placeholder="Confirm New Password*" required>
                            <span style="color:red;" ng-show="addForm.userNewPasswordC.$dirty && addForm.userNewPasswordC.$invalid">
                              <span ng-show="addForm.userNewPasswordC.$error.required">Confirm New Password is required</span>
                            </span>
                          </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                          <button type="submit" class="btn btn-primary pull-right" ng-disabled="addForm.$invalid" ng-click="submitChangePassword()">Change Password</button>
                        </div>
                      </form>
                    </div>
                    <!-- /.box -->
                  </div>
                </div>


              </div>

              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>



      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
  <?php $this->load->view('include/footer'); ?>
</div>
<!-- ./wrapper -->