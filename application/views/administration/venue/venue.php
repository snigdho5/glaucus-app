<div class="wrapper" ng-controller="venueCtrl">

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
          <li class="active">Service Management</li>
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

              <div class="box-title">
                <div class="input-group input-group-sm" style="width: 250px;">
                  <input type="text" ng-model="search" class="form-control" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
              <?php if($this->session->userdata['logged_web_user']['userVenueAddPermission']=="true"){ ?>
              <div class="box-title pull-right">
                <a href="<?php echo base_url();?>venuemanagement/add"><button type="button" class="btn btn-block btn-primary"><i class="fa fa-plus"> </i>  Add Service</button></a>
              </div>
              <?php } ?>
              <div class="box-title pull-right" style="margin-right: 5px;">
                <a ng-click="refreshTable()"><button type="button" class="btn btn-block btn-primary" title="Refresh Table"><i class="fa fa-refresh"> </i></button></a>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th ng-click="sort('venCode')">Service Code
                    <span class="glyphicon sort-icon" ng-show="sortKey=='venCode'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('venShortName')">Short Name
                    <span class="glyphicon sort-icon" ng-show="sortKey=='venShortName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('venFullName')">Full Name
                    <span class="glyphicon sort-icon" ng-show="sortKey=='venFullName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('venUnitName')">Unit Name
                    <span class="glyphicon sort-icon" ng-show="sortKey=='venUnitName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('venDescription')">Description
                    <span class="glyphicon sort-icon" ng-show="sortKey=='venDescription'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('venStatus')">Status
                    <span class="glyphicon sort-icon" ng-show="sortKey=='venStatus'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr dir-paginate="venue in venues|orderBy:sortKey:reverse|filter:search|itemsPerPage:5">
                  <td>{{venue.venCode}}</td>
                  <td>{{venue.venShortName}}</td>
                  <td>{{venue.venFullName}}</td>
                  <td>{{venue.venUnitName}}</td>
                  <td>{{venue.venDescription}}</td>
                  <td><span ng-class='whatClassIsIt(venue.venStatus)'>{{venue.venStatus}}</span></td>
                  <td>
                    <a class="btn btn-social-icon btn-danger" data-toggle="modal" data-target=".modal-venue-details" ng-click="viewVenueDetails(venue)" title="View"><i class="fa fa-eye"></i></a>
                    <?php if($this->session->userdata['logged_web_user']['userVenueEditPermission']=="true"){ ?>
                    <a class="btn btn-social-icon btn-danger" ng-click="myStatus(venue)" title="Change Status"><i class="fa fa-check-circle"></i></a>
                    <a class="btn btn-social-icon btn-danger" ng-click="goToEdit(venue)" title="Edit"><i class="fa fa-edit"></i></a>
                    <?php } ?>
                  </td>
                </tr>
              </tbody>
              </table>
              </div>

              <div ng-if="dataloading" class="row" align="center">
                <div class="row">
                  <img src="<?php echo base_url(); ?>assets/dist/img/boxc.gif"> 
                </div>
              </div>
              <dir-pagination-controls
                max-size="5"
                direction-links="true"
                boundary-links="true" >
              </dir-pagination-controls>
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

  <div class="modal modal-venue-details">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Venue Details</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-4"><strong>Service Code : </strong></div>
                  <div class="col-md-8">{{activeVenue.venCode}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Service Short Name : </strong></div>
                  <div class="col-md-8">{{activeVenue.venShortName}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Service Full Name : </strong></div>
                  <div class="col-md-8">{{activeVenue.venFullName}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Unit Name : </strong></div>
                  <div class="col-md-8">{{activeVenue.venUnitName}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Service Description : </strong></div>
                  <div class="col-md-8">{{activeVenue.venDescription}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

</div>
<!-- ./wrapper -->