<link rel="stylesheet" href="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<script type="text/javascript" src="http://momentjs.com/downloads/moment-with-locales.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="wrapper" ng-controller="editorderCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Order
          <small>Management</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="<?php echo base_url();?>ordermanagement">Order Management</a></li>
          <li class="active">Edit Order</li>
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
                <h3 class="box-title">Edit Order</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
              <form name="addForm" novalidate>
                <div class="box-body">

                <div class="row col-md-12">

                  <div class="form-group col-md-6">
                    <label for="ordName">Order Name*</label>
                    <input type="text" class="form-control" ng-model="ordName" name="ordName" placeholder="Query Name" required>
                    <span style="color:red;" ng-show="addForm.ordName.$dirty && addForm.ordName.$invalid">
                      <span ng-show="addForm.ordName.$error.required">Order Name is required</span>
                    </span>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="ordUnit">Select Unit*</label>
                    <select class="form-control" ng-model="ordUnitSelect" ng-options="unit.untName for unit in units" ng-change="getVenues()" required></select>
                  </div>
                  
                </div>

                <div class="row col-md-12">

                  <div class="form-group col-md-6">
                    <label for="ordVenue">Select Service*</label>
                    <select class="form-control" ng-model="ordVenueSelect" ng-options="venue.venShortName for venue in venues" required></select>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="ordQuantity">Quantity*</label>
                    <input type="number" class="form-control" ng-model="ordQuantity" name="ordQuantity" placeholder="Quantity" required>
                    <span style="color:red;" ng-show="addForm.ordQuantity.$dirty && addForm.ordQuantity.$invalid">
                      <span ng-show="addForm.ordQuantity.$error.required">Quantity is required</span>
                    </span>
                  </div>
                  
                </div>

                <div class="row col-md-12">

                  <div class="form-group col-md-3">
                    <label for="ordAmount">Amount*</label>
                    <input type="number" class="form-control" ng-model="ordAmount" name="ordAmount" placeholder="Amount" required>
                    <span style="color:red;" ng-show="addForm.ordAmount.$dirty && addForm.ordAmount.$invalid">
                      <span ng-show="addForm.ordAmount.$error.required">Amount is required</span>
                    </span>
                  </div>

                  <div class="form-group col-md-3">
                    <label for="ordForDate">Order Date*</label>
                    <input type="text" id="ordForDate" class="form-control floating-label" placeholder="Date">
                  </div>

                  <div class="form-group col-md-6">
                    <label for="ordStatus">Select Status*</label>
                    <select class="form-control" ng-model="ordStatusSelect" ng-options="statustype.ostName for statustype in statustypes" required></select>
                  </div>
                  
                </div>

                <div class="row col-md-12">
                  <div class="form-group col-md-12">
                    <label for="ordDescription">Description</label>
                    <textarea class="form-control" ng-model="ordDescription" name="ordDescription" placeholder="Description" required></textarea>
                  </div>
                </div>



                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                <button type="submit" class="btn btn-default pull-left" ng-click="cancelEditOrder()">Cancel</button>
                  <button type="submit" class="btn btn-primary pull-right" ng-disabled="addForm.$invalid" ng-click="updateOrder()">Update</button>
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