<div class="wrapper" ng-controller="unitCtrl">

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
          <li class="active">Unit Management</li>
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
              <?php if($this->session->userdata['logged_web_user']['userUnitAddPermission']=="true"){ ?>
              <div class="box-title pull-right">
                <a href="<?php echo base_url();?>unitmanagement/add"><button type="button" class="btn btn-block btn-primary"><i class="fa fa-plus"> </i>  Add Unit</button></a>
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
                  <th ng-click="sort('untCode')">Unit Code
                    <span class="glyphicon sort-icon" ng-show="sortKey=='untCode'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('untName')">Name
                    <span class="glyphicon sort-icon" ng-show="sortKey=='untName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('untDescription')">Description
                    <span class="glyphicon sort-icon" ng-show="sortKey=='untDescription'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('untStatus')">Status
                    <span class="glyphicon sort-icon" ng-show="sortKey=='untStatus'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr dir-paginate="unit in units|orderBy:sortKey:reverse|filter:search|itemsPerPage:5">
                  <td>{{unit.untCode}}</td>
                  <td>{{unit.untName}}</td>
                  <td>{{unit.untDescription}}</td>
                  <td><span ng-class='whatClassIsIt(unit.untStatus)'>{{unit.untStatus}}</span></td>
                  <td>
                    <a class="btn btn-social-icon btn-danger" data-toggle="modal" data-target=".modal-unit-details" ng-click="viewUnitDetails(unit)" title="View"><i class="fa fa-eye"></i></a>
                    <?php if($this->session->userdata['logged_web_user']['userUnitAddPermission']=="true"){ ?>
                    <a class="btn btn-social-icon btn-danger" ng-click="myStatus(unit)" title="Change Status"><i class="fa fa-check-circle"></i></a>
                    <a class="btn btn-social-icon btn-danger" ng-click="goToEdit(unit)" title="Edit"><i class="fa fa-edit"></i></a>
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

  <div class="modal modal-unit-details">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Unit Details</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-4"><strong>Unit Code : </strong></div>
                  <div class="col-md-8">{{activeUnit.untCode}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Name : </strong></div>
                  <div class="col-md-8">{{activeUnit.untName}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Description : </strong></div>
                  <div class="col-md-8">{{activeUnit.untDescription}}</div>
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