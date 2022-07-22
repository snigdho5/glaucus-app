<div class="wrapper" ng-controller="editUnitCtrl">

  <?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Unit
          <small>Management</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="<?php echo base_url();?>unitmanagement">Unit Management</a></li>
          <li class="active">Edit</li>
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
            <h3 class="box-title">Edit Unit</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form name="addForm" novalidate>
            <div class="box-body">
              <div class="row col-md-12">
                <div class="form-group col-md-4">
                  <label for="untCode">Unit Code (Auto Generated)</label>
                  <input type="text" class="form-control" ng-model="untCode" name="untCode" placeholder="Unit Code" disabled>
                </div>
                <div class="form-group col-md-8">
                  <label for="untName">Unit Name*</label>
                  <input type="text" class="form-control" ng-model="untName" name="untName" placeholder="Unit Name*" required>
                  <span style="color:red;" ng-show="addForm.untName.$dirty && addForm.untName.$invalid">
                    <span ng-show="addForm.untName.$error.required">Unit Name is required</span>
                  </span>
                </div>
              </div>
              <div class="row col-md-12">
                <div class="form-group col-md-12">
                  <label for="untDescription">Unit Description</label>
                  <textarea class="form-control" ng-model="untDescription" name="untDescription" placeholder="Unit Description"></textarea>
                </div>
              </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
              <button type="submit" class="btn btn-default pull-left" ng-click="cancelUpdateUnit()">Cancel</button>
              <button type="submit" class="btn btn-primary pull-right" ng-disabled="addForm.$invalid" ng-click="submitUpdateUnit()">Update</button>
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