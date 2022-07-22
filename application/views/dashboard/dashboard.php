<!--<script src="https://maps.google.com/maps/api/js?libraries=placeses,visualization,drawing,geometry,places&key=AIzaSyDLzEQ6FcQtf9oZNBsDLf_trm_RCto_IJg"></script> -->
<script src="https://maps.google.com/maps/api/js?libraries=placeses,visualization,drawing,geometry,places&key=AIzaSyCTNEZO6ODA9x9z0MDb9fPGSgtYI0mqvUo"></script>
<div class="wrapper" ng-controller="dashboardCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Main
          <small>Dashboard</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Dashboard</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="box box-primary">
          <div class="box-body" style="padding: 0px;">

            <div id="map" style="height: 68vh; width: 100%;"></div>
            
          </div>
            <span><strong>To view the current location of the sales person double click on the blue baloon.</strong></span>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
  <?php $this->load->view('include/footer'); ?>
</div>
<!-- ./wrapper