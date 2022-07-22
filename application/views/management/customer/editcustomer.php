<link rel="stylesheet" href="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<script type="text/javascript" src="http://momentjs.com/downloads/moment-with-locales.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<div class="wrapper" ng-controller="editCustomerCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Customer
          <small>Management</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="<?php echo base_url();?>customermanagement">Customer Management</a></li>
          <li class="active">Add Customer</li>
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
            <h3 class="box-title">Edit Customer</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form name="addForm" novalidate>
            <div class="box-body">

            <h4 class="page-header">General Details</h4>

            <div class="row col-md-12">

              <div class="form-group col-md-4">
                <label for="cusCode">Customer Code (Auto Generated)</label>
                <input type="text" class="form-control" ng-model="cusCode" name="cusCode" placeholder="Customer Code" disabled>
              </div>

              <div class="form-group col-md-4">
                <label for="cusFirstName">First Name*</label>
                <input type="text" class="form-control" ng-model="cusFirstName" name="cusFirstName" placeholder="First Name" required>
                <span style="color:red;" ng-show="addForm.cusFirstName.$dirty && addForm.cusFirstName.$invalid">
                  <span ng-show="addForm.cusFirstName.$error.required">First Name is required</span>
                </span>
              </div>

              <div class="form-group col-md-4">
                <label for="cusLastName">Last Name*</label>
                <input type="text" class="form-control" ng-model="cusLastName" name="cusLastName" placeholder="Last Name" required>
                <span style="color:red;" ng-show="addForm.cusLastName.$dirty && addForm.cusLastName.$invalid">
                  <span ng-show="addForm.cusLastName.$error.required">Last Name is required</span>
                </span>
              </div>
              
            </div>

            <div class="row col-md-12" style="margin-bottom: 10px;">

              <div class="form-group col-md-4">
                <label for="cusGender">Select Gender*</label>
                <select class="form-control" ng-model="cusGender" ng-options="gender.gndName for gender in genders" required></select>
              </div>

              <div class="form-group col-md-4">
                <label for="cusDOB">Date of Birth</label>
                <input type="text" id="cusDOB" ng-model="cusDOB" class="form-control floating-label" placeholder="Date of Birth">
              </div>

              <div class="form-group col-md-4">
                <label for="cusDOA">Date of Anniversary</label>
                <input type="text" id="cusDOA" ng-model="cusDOA" class="form-control floating-label" placeholder="Date of Anniversary">
              </div>
              
            </div>

            <h4 class="page-header">Professional Details</h4>

            <div class="row col-md-12">

              <div class="form-group col-md-4">
                <label for="cusCustomerType">Select Customer Type*</label>
                <select class="form-control" ng-model="cusCustomerType" ng-options="customerType.custName for customerType in customerTypes" required></select>
              </div>

              <div class="form-group col-md-4">
                <label for="cusIndustryType">Industry Type*</label>
                <select class="form-control" ng-model="cusIndustryType" ng-options="industryType.indtName for industryType in industryTypes" required></select>
              </div>

              <div class="form-group col-md-4">
                <label for="cusCompanyName">Company Name*</label>
                <input type="text" class="form-control" id="cmptags" ng-model="cusCompanyName" name="cusCompanyName" placeholder="Company Name" required>
                <span style="color:red;" ng-show="addForm.cusCompanyName.$dirty && addForm.cusCompanyName.$invalid">
                  <span ng-show="addForm.cusCompanyName.$error.required">Company Name is required</span>
                </span>
              </div>
              
            </div>


            <div class="row col-md-12" style="margin-bottom: 10px;">

              <div class="form-group col-md-4">
                <label for="cusDepartment">Department*</label>
                <input type="text" class="form-control" id="depttags" ng-model="cusDepartment" name="cusDepartment" placeholder="Department Name" required>
                <span style="color:red;" ng-show="addForm.cusDepartment.$dirty && addForm.cusDepartment.$invalid">
                  <span ng-show="addForm.cusDepartment.$error.required">Department is required</span>
                </span>
              </div>

              <div class="form-group col-md-4">
                <label for="cusDesination">Designation*</label>
                <input type="text" class="form-control" id="desgtags" ng-model="cusDesignation" name="cusDesignation" placeholder="Designation Name" required>
                <span style="color:red;" ng-show="addForm.cusDesignation.$dirty && addForm.cusDesignation.$invalid">
                  <span ng-show="addForm.cusDesignation.$error.required">Designation is required</span>
                </span>
              </div>
              
            </div>

            <h4 class="page-header">Contact Details</h4>

            <div class="row col-md-12">

              <div class="form-group col-md-4">
                <label for="cusEmail">Email</label>
                <input type="email" class="form-control" ng-model="cusEmail" name="cusEmail" placeholder="Email Id">
                <span style="color:red;" ng-show="addForm.cusEmail.$dirty && addForm.cusEmail.$invalid">
                  <!--<span ng-show="addForm.cusEmail.$error.required">Email is required</span>-->
                  <span ng-show="addForm.cusEmail.$error.email">Invalid email address</span>
                </span>
              </div>

              <div class="form-group col-md-4">
                <label for="cusMobileNo">Mobile No*</label>
                <input type="number" class="form-control" ng-model="cusMobileNo" ng-minlength="10" ng-maxlength="10"  name="cusMobileNo" placeholder="Mobile Number" required>
                <span style="color:red;" ng-show="addForm.cusMobileNo.$dirty && addForm.cusMobileNo.$invalid">
                  <span ng-show="addForm.cusMobileNo.$error.required">Mobile Number is required</span>
                  <span ng-show="addForm.cusMobileNo.$error.minlength">Mobile Number should be 10 digits</span>
                  <span ng-show="addForm.cusMobileNo.$error.maxlength">Mobile Number should be 10 digits</span>
                </span>
              </div>

              <div class="form-group col-md-4">
                <label for="cusAlternateNo">Alternate No</label>
                <input type="number" class="form-control" ng-model="cusAlternateNo" ng-minlength="8" ng-maxlength="12"  name="cusAlternateNo" placeholder="Alternate Number">
                <span style="color:red;" ng-show="addForm.cusAlternateNo.$dirty && addForm.cusAlternateNo.$invalid">
                  <span ng-show="addForm.cusAlternateNo.$error.required">Alternate Number is required</span>
                  <span ng-show="addForm.cusAlternateNo.$error.minlength">Alternate Number should be more than 7 digits</span>
                  <span ng-show="addForm.cusAlternateNo.$error.maxlength">Alternate Number should be less than 13 digits</span>
                </span>
              </div>
              
            </div>

            <div class="row col-md-12">

              <div class="form-group col-md-4">
                <label for="cusCountry">Select Country*</label>
                <select class="form-control" ng-model="cusCountry" ng-options="country.countryName for country in countries" ng-change="getStates()" required> </select>
              </div>

              <div class="form-group col-md-4">
                <label for="cusState">Select State*</label>
                <select class="form-control" ng-model="cusState" ng-options="state.stateName for state in states" ng-change="getCities()" required></select>
              </div>

              <div class="form-group col-md-4">
                <label for="cusCity">Select City*</label>
                <select class="form-control" ng-model="cusCity" ng-options="city.cityName for city in cities" required></select>
              </div>
              
            </div>

            <div class="row col-md-12">

              <div class="form-group col-md-4">
                <label for="cusAddress">Address*</label>
                <input type="text" class="form-control" ng-model="cusAddress" name="cusAddress" placeholder="Address" required>
                <span style="color:red;" ng-show="addForm.cusAddress.$dirty && addForm.cusAddress.$invalid">
                  <span ng-show="addForm.cusAddress.$error.required">Address is required</span>
                </span>
              </div>

              <div class="form-group col-md-4">
                <label for="cusAddress">Address Line 2</label>
                <input type="text" class="form-control" ng-model="cusAddress2" name="cusAddress2" placeholder="Address Line 2">
              </div>

              <div class="form-group col-md-4">
                <label for="cusLandmark">Landmark</label>
                <input type="text" class="form-control" ng-model="cusLandmark" name="cusLandmark" placeholder="Landmark">
                <span style="color:red;" ng-show="addForm.cusLandmark.$dirty && addForm.cusLandmark.$invalid">
                  <!--<span ng-show="addForm.cusLandmark.$error.required">Landmark is required</span>-->
                </span>
              </div>

              
            </div>

            <div class="row col-md-12">

              <div class="form-group col-md-4">
                <label for="cusArea">Area*</label>
                <input type="text" class="form-control" id="areatags" ng-model="cusArea" name="cusArea" placeholder="Area Name" required>
                <span style="color:red;" ng-show="addForm.cusArea.$dirty && addForm.cusArea.$invalid">
                  <span ng-show="addForm.cusArea.$error.required">Area Name is required</span>
                </span>
              </div>

              <div class="form-group col-md-4">
                <label for="cusPinCode">Pin Code*</label>
                <input type="number" class="form-control" ng-model="cusPinCode" name="cusPinCode" placeholder="Pin Code" required>
                <span style="color:red;" ng-show="addForm.cusPinCode.$dirty && addForm.cusPinCode.$invalid">
                  <span ng-show="addForm.cusPinCode.$error.required">Pin Code is required</span>
                </span>
              </div>

              <div class="form-group col-md-4">
                <label for="cusCountry">Select Account Manager*</label>
                <select class="form-control" ng-model="cusManage" ng-options="adminuser.usrName+' ['+adminuser.usrTypeName+']' for adminuser in adminusers" required> </select>
              </div>
              
            </div>





            </div>
            <!-- /.box-body -->
            <div class="box-footer">
            <button type="submit" class="btn btn-default pull-left" ng-click="cancelEditCustomer()">Cancel</button>
              <button type="submit" class="btn btn-primary pull-right" ng-disabled="addForm.$invalid" ng-click="submitUpdateCustomer()">Update</button>
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