<div class="wrapper" ng-controller="editAdminCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Admin
          <small>Management</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="<?php echo base_url();?>adminmanagement">Admin Management</a></li>
          <li class="active">Edit Admin</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">

   <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Edit Admin</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form name="addForm" novalidate>
            <div class="box-body">

            <div class="row col-md-12">

                <div class="form-group col-md-12">
                  <label for="usrUnitId">Select Single/Multiple Unit*</label>
                  <div
                      isteven-multi-select
                      input-model="inputEditUnits"
                      output-model="outputEditUnits"
                      button-label="icon name"
                      item-label="icon name maker"
                      tick-property="ticked"
                  >
                  </div>
                </div>
              
            </div>

            <div class="row col-md-12">

              <div class="form-group col-md-6">
                <label for="userFirstName">First Name*</label>
                <input type="text" class="form-control" ng-model="userFirstName" name="userFirstName" placeholder="First Name" required>
                <span style="color:red;" ng-show="addForm.userFirstName.$dirty && addForm.userFirstName.$invalid">
                  <span ng-show="addForm.userFirstName.$error.required">First Name is required</span>
                </span>
              </div>

              <div class="form-group col-md-6">
                <label for="userLastName">Last Name*</label>
                <input type="text" class="form-control" ng-model="userLastName" name="userLastName" placeholder="Last Name*" required>
                <span style="color:red;" ng-show="addForm.userLastName.$dirty && addForm.userLastName.$invalid">
                  <span ng-show="addForm.userLastName.$error.required">Last Name is required</span>
                </span>
              </div>
              
            </div>

            <div class="row col-md-12">

              <div class="form-group col-md-6">
                <label for="userEmail">Email*</label>
                <input type="email" class="form-control" ng-model="userEmail" name="userEmail" placeholder="Email Id*" required>
                <span style="color:red;" ng-show="addForm.userEmail.$dirty && addForm.userEmail.$invalid">
                  <span ng-show="addForm.userEmail.$error.required">Email is required</span>
                  <span ng-show="addForm.userEmail.$error.email">Invalid email address</span>
                </span>
              </div>

              <div class="form-group col-md-6">
                <label for="userName">User Name*</label>
                <input type="text" class="form-control" ng-model="userName" name="userName" placeholder="Username*" required>
                <span style="color:red;" ng-show="addForm.userName.$dirty && addForm.userName.$invalid">
                  <span ng-show="addForm.userName.$error.required">User Name is required</span>
                </span>
              </div>

            </div>


            <div class="row col-md-12">

              <div class="form-group col-md-6">
                <label for="userPassword">Password*</label>
                <input type="password" class="form-control" ng-model="userPassword" name="userPassword" placeholder="Password*" required>
                <span style="color:red;" ng-show="addForm.userPassword.$dirty && addForm.userPassword.$invalid">
                  <span ng-show="addForm.userPassword.$error.required">Password is required</span>
                </span>
              </div>

              <div class="form-group col-md-6">
                <label for="userPasswordC">Password Confirmation*</label>
                <input type="password" class="form-control" ng-model="userPasswordC" name="userPasswordC" placeholder="Retype password*" required>
                <span style="color:red;" ng-show="addForm.userPasswordC.$dirty && addForm.userPasswordC.$invalid">
                  <span ng-show="addForm.userPasswordC.$error.required">Password confirmation is required</span>
                </span>
              </div>
              
            </div>

            <div class="row col-md-12">
              <div class="form-group col-md-6">
                <div class="checkbox" class="form-control">
                  <label>
                    <input type="checkbox" ng-model="userVenueAddPermission"> <strong>Can Add Venue Management Contents</strong>
                  </label>
                </div>
              </div>

              <div class="form-group col-md-6">
                <div class="checkbox" class="form-control">
                  <label>
                    <input type="checkbox" ng-model="userVenueEditPermission"> <strong>Can Edit Venue Management Contents</strong>
                  </label>
                </div>
              </div>
              
            </div>



            </div>
            <!-- /.box-body -->

            <div class="box-footer">
            <button type="submit" class="btn btn-default pull-left" ng-click="cancelEditAdmin()">Cancel</button>
              <button type="submit" class="btn btn-primary pull-right" ng-disabled="addForm.$invalid" ng-click="updateAdmin()">Update</button>
            </div>
          </form>
        </div>
        <!-- /.box -->
      </div>
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