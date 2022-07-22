<div class="wrapper" ng-controller="addUserCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          User
          <small>Management</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="<?php echo base_url();?>usermanagement">User Management</a></li>
          <li class="active">Add User</li>
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
            <h3 class="box-title">Add User</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form name="addForm" novalidate>
            <div class="box-body">

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
                <label for="userContactNo">Contact Number*</label>
                <input type="number" class="form-control" ng-model="userContactNo" ng-minlength="8" ng-maxlength="12"  name="userContactNo" placeholder="Contact Number" required>
                <span style="color:red;" ng-show="addForm.userContactNo.$dirty && addForm.userContactNo.$invalid">
                  <span ng-show="addForm.userContactNo.$error.required">User Contact No is required</span>
                  <span ng-show="addForm.userContactNo.$error.minlength">Contact number should be more than 7 digits</span>
                  <span ng-show="addForm.userContactNo.$error.maxlength">Contact number should be less than 13 digits</span>
                </span>
              </div>

              <div class="form-group col-md-6">
                <label for="userEmail">Email*</label>
                <input type="email" class="form-control" ng-model="userEmail" name="userEmail" placeholder="Email Id*" required>
                <span style="color:red;" ng-show="addForm.userEmail.$dirty && addForm.userEmail.$invalid">
                  <span ng-show="addForm.userEmail.$error.required">Email is required</span>
                  <span ng-show="addForm.userEmail.$error.email">Invalid email address</span>
                </span>
              </div>

            </div>

            <div class="row col-md-12">

              <div class="form-group col-md-6">
                <label for="userName">User Name*</label>
                <input type="text" class="form-control" ng-model="userName" name="userName" placeholder="Username*" required>
                <span style="color:red;" ng-show="addForm.userName.$dirty && addForm.userName.$invalid">
                  <span ng-show="addForm.userName.$error.required">User Name is required</span>
                </span>
              </div>

              <div class="form-group col-md-6">
                <label for="userDesignation">Designation*</label>
                <input type="text" class="form-control" ng-model="userDesignation" name="userDesignation" placeholder="Designation*" required>
                <span style="color:red;" ng-show="addForm.userDesignation.$dirty && addForm.userDesignation.$invalid">
                  <span ng-show="addForm.userDesignation.$error.required">Designation is required</span>
                </span>
              </div>
              
            </div>


            <div class="row col-md-12">

              <div class="form-group col-md-6">
                <label for="userAddress">Address*</label>
                <input type="text" class="form-control" ng-model="userAddress" name="userAddress" placeholder="Address" required>
                <span style="color:red;" ng-show="addForm.userAddress.$dirty && addForm.userAddress.$invalid">
                  <span ng-show="addForm.userAddress.$error.required">Address is required</span>
                </span>
              </div>

              <div class="form-group col-md-6">
                <label for="userCountry">Select Country*</label>
                <select class="form-control" ng-model="userCountry" ng-options="country.countryName for country in countries" ng-change="getStates()" required> </select>
              </div>
              
            </div>


            <div class="row col-md-12">

              <div class="form-group col-md-6">
                <label for="userState">Select State*</label>
                <select class="form-control" ng-model="userState" ng-options="state.stateName for state in states" ng-change="getCities()" required></select>
              </div>

              <div class="form-group col-md-6">
                <label for="userCity">Select City*</label>
                <select class="form-control" ng-model="userCity" ng-options="city.cityName for city in cities" required></select>
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
                <label for="userKyc">Upload KYC Document (File format PDF) *</label>
                <input type="file" ngf-select ng-model="picFile" name="file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf," ngf-max-size="2MB" required ngf-model-invalid="errorFile"/>
                </div>
            </div>
              
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
            <button type="submit" class="btn btn-default pull-left" ng-click="cancelAddUser()">Cancel</button>
              <button type="submit" class="btn btn-primary pull-right" ng-disabled="addForm.$invalid" ng-click="submitAddUser(picFile)">Submit</button>
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