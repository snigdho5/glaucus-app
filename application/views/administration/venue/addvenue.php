<div class="wrapper" ng-controller="addVenueCtrl">

  <?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Service
          <small>Management</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="<?php echo base_url();?>venuemanagement">Service Management</a></li>
          <li class="active">Add</li>
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
            <h3 class="box-title">Add Service</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form name="addForm" novalidate>
            <div class="box-body">
              <div class="row col-md-12">
                <div class="form-group col-md-4">
                  <label for="venCode">Service Code (Auto Generated)</label>
                  <input type="text" class="form-control" ng-model="venCode" name="venCode" placeholder="Venue Code" disabled>
                </div>
                <div class="form-group col-md-8">
                  <label for="venUnitId">Select Unit*</label>
                  <select class="form-control" ng-model="venUnitId" ng-options="unit.untName for unit in units" required></select>
                  </span>
                </div>
              </div>
              <div class="row col-md-12">
                <div class="form-group col-md-4">
                  <label for="venShortName">Service Short Name*</label>
                  <input type="text" class="form-control" ng-model="venShortName" name="venShortName" placeholder="Venue Short Name*" required>
                  <span style="color:red;" ng-show="addForm.venShortName.$dirty && addForm.venShortName.$invalid">
                    <span ng-show="addForm.venShortName.$error.required">Service Short Name is required</span>
                  </span>
                </div>
                <div class="form-group col-md-8">
                  <label for="venFullName">Service Full Name*</label>
                  <input type="text" class="form-control" ng-model="venFullName" name="venFullName" placeholder="Venue Full Name*" required>
                  <span style="color:red;" ng-show="addForm.venFullName.$dirty && addForm.venFullName.$invalid">
                    <span ng-show="addForm.venFullName.$error.required">Service Full Name is required</span>
                  </span>
                </div>
              </div>
              <div class="row col-md-12">
                <div class="form-group col-md-12">
                  <label for="venDescription">Service Description</label>
                  <textarea class="form-control" ng-model="venDescription" name="venDescription" placeholder="Venue Description"></textarea>
                </div>
              </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
              <button type="submit" class="btn btn-default pull-left" ng-click="cancelAddVenue()">Cancel</button>
              <button type="submit" class="btn btn-primary pull-right" ng-disabled="addForm.$invalid" ng-click="submitAddVenue()">Submit</button>
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