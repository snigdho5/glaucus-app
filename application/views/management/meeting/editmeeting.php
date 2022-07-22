<link rel="stylesheet" href="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<script type="text/javascript" src="http://momentjs.com/downloads/moment-with-locales.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<div class="wrapper" ng-controller="editMeetingCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Meeting
          <small>Management</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="<?php echo base_url();?>meetingmanagement">Meeting Management</a></li>
          <li class="active">Edit Meeting</li>
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
            <h3 class="box-title">Edit Meeting</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form name="addForm" novalidate>
            <div class="box-body">

            <div class="row col-md-12">

              <div class="form-group col-md-6">
                <label for="cusFirstName">Customer First Name</label>
                <input type="text" class="form-control" ng-model="cusFirstName" name="cusFirstName" placeholder="First Name" required disabled="true">
                <span style="color:red;" ng-show="addForm.cusFirstName.$dirty && addForm.cusFirstName.$invalid">
                  <span ng-show="addForm.cusFirstName.$error.required">First Name is required</span>
                </span>
              </div>

              <div class="form-group col-md-6">
                <label for="cusLastName">Customer Last Name</label>
                <input type="text" class="form-control" ng-model="cusLastName" name="cusLastName" placeholder="Last Name" required disabled="true">
                <span style="color:red;" ng-show="addForm.cusLastName.$dirty && addForm.cusLastName.$invalid">
                  <span ng-show="addForm.cusLastName.$error.required">Last Name is required</span>
                </span>
              </div>
              
            </div>

            <div class="row col-md-12">

              <div class="form-group col-md-4">
                <label for="mtnName">Meeting Name*</label>
                <input type="text" class="form-control" ng-model="mtnName" name="mtnName" placeholder="Meeting Name" required>
                <span style="color:red;" ng-show="addForm.mtnName.$dirty && addForm.mtnName.$invalid">
                  <span ng-show="addForm.mtnName.$error.required">Meeting Name is required</span>
                </span>
              </div>

              <div class="form-group col-md-4">
                <label for="mtnUserId">Select Assign To*</label>
                <select class="form-control" ng-model="mtnUserId" ng-options="appUser.ausrUserName+' ['+appUser.ausrParentName+']' for appUser in appUsers" required></select>
              </div>

              <div class="form-group col-md-4">
                <label for="mtnTelecallerId">Select TeleCaller*</label>
                <select class="form-control" ng-model="mtnTelecallerId" ng-options="adminUser.usrName+' ['+adminUser.usrTypeName+']' for adminUser in adminUsers" required></select>
              </div>
              
            </div>

            <div class="row col-md-12">

              <div class="form-group col-md-4">
                <label for="mtnDate">Meeting Date*</label>
				        <input type="text" id="mtnDate" class="form-control floating-label" placeholder="Date">
              </div>

              <div class="form-group col-md-4">
                <label for="mtnTime">Meeting Time*</label>
				        <input type="text" id="mtnTime" class="form-control floating-label" placeholder="Time">
              </div>

              <div class="form-group col-md-4">
                <label for="mtnUserId">Select Meeting Type*</label>
                <select class="form-control" ng-model="mtnMeetingType" ng-options="meeting_type.mtntName for meeting_type in meeting_types" required></select>
              </div>

            </div>

            <div class="row col-md-12">

              <div class="form-group col-md-12">
                <label for="mtnComments">Meeting Comments</label>
                <textarea type="text" class="form-control" ng-model="mtnComments" name="mtnComments" placeholder="Meeting Comments"></textarea>
              </div>
              
            </div>

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
            <button type="submit" class="btn btn-default pull-left" ng-click="cancelEditMeeting()">Cancel</button>
              <button type="submit" class="btn btn-primary pull-right" ng-disabled="addForm.$invalid" ng-click="submitUpdateMeeting()">Update</button>
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