<script src="//maps.google.com/maps/api/js?libraries=placeses,visualization,drawing,geometry,places&key=AIzaSyCTNEZO6ODA9x9z0MDb9fPGSgtYI0mqvUo"></script>
<link rel="stylesheet" href="<?= base_url();?>assets/materialTime/bootstrap-material-datetimepicker.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<script src="//momentjs.com/downloads/moment-with-locales.min.js"></script>
<script src="<?= base_url();?>assets/materialTime/bootstrap-material-datetimepicker.js"></script>
<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="wrapper" ng-controller="routehistoryRoadCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Route Road
          <small>History</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Route Road History</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">

        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary" style="padding-left:10px; padding-right:10px;">
              <div class="box-header with-border">


                <div class="form-group col-md-2">
                  <label for="fromDate">Select Date</label>
                  <input type="text" id="selectDate" class="form-control floating-label" placeholder="Select Date">
                </div>

                <div class="form-group col-md-3">
                  <label for="toDate">Select User</label>
                  <select class="form-control" ng-model="selectedUser" ng-options="user.usrUserName+' ['+user.usrParentName+']'  for user in appUsers"></select>
                </div>

                <div class="form-group col-md-2" style="margin-top:24px;">
                  <button type="button" ng-click="submitRoute()" class="btn btn-block btn-primary form-control" title="Refresh Table">
                    <i class="fa fa-search"> </i>
                    Get Route
                  </button>
                </div>

                <div class="form-group col-md-3" style="margin-top:24px;">
                  <button type="button" ng-click="reloadPage()" class="btn btn-block btn-primary form-control" title="Refresh Table">
                    <i class="fa fa-refresh"></i>
                    Refresh Map to view new user data
                  </button>
                </div>
                    
              </div>
              <!-- /.box-header -->

            </div>
            <!-- /.box -->


            <div class="box box-primary">
              <div class="box-body" style="padding: 0px;">

                <div id="map" style="height: 68vh; width: 100%;"></div>
                
              </div>
              <!-- /.box-body -->
            </div>

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