<div class="wrapper" ng-controller="adminCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Admin
          <small>Management</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Admin Management</li>
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
              <div class="box-title pull-right">
                <a href="<?php echo base_url();?>adminmanagement/add"><button type="button" class="btn btn-block btn-primary"><i class="fa fa-plus"> </i>  Add Admin</button></a>
              </div>
              <div class="box-title pull-right" style="margin-right: 5px;">
                <a ng-click="refreshTable()"><button type="button" class="btn btn-block btn-primary" title="Refresh Table"><i class="fa fa-refresh"> </i></button></a>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <thead>
                <tr>
                <!--
                  <th ng-click="sort('wusrId')">Id
                    <span class="glyphicon sort-icon" ng-show="sortKey=='wusrId'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>-->
                  <th ng-click="sort('wusrFirstName')">First Name
                    <span class="glyphicon sort-icon" ng-show="sortKey=='wusrFirstName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('wusrLastName')">Last Name
                    <span class="glyphicon sort-icon" ng-show="sortKey=='wusrLastName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('wusrUnitNames')">Unit Names
                    <span class="glyphicon sort-icon" ng-show="sortKey=='wusrUnitNames'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('wusrUserName')">Username
                    <span class="glyphicon sort-icon" ng-show="sortKey=='wusrUserName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('wusrParentId')">Created By
                    <span class="glyphicon sort-icon" ng-show="sortKey=='wusrParentId'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('wusrStatusName')">Status
                    <span class="glyphicon sort-icon" ng-show="sortKey=='wusrStatusName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr dir-paginate="user in webUsers|orderBy:sortKey:reverse|filter:search|itemsPerPage:5">
                <!--
                  <td>{{user.wusrId}}</td>-->
                  <td>{{user.wusrFirstName}}</td>
                  <td>{{user.wusrLastName}}</td>
                  <td>{{user.wusrUnitNames}}</td>
                  <td>{{user.wusrUserName}}</td>
                  <td>{{user.wusrParentName}}</td>
                  <td><span ng-class='whatClassIsIt(user.wusrStatusName)'>{{user.wusrStatusName}}</span></td>
                  <td>
                    <a class="btn btn-social-icon btn-danger" ng-click="myStatus(user)" title="Change Status"><i class="fa fa-check-circle"></i></a>
                    <a class="btn btn-social-icon btn-danger" data-toggle="modal" data-target=".modal-admin-details" ng-click="viewAdminDetails(user)" title="View"><i class="fa fa-eye"></i></a>
                    <a class="btn btn-social-icon btn-danger" ng-click="goToEdit(user)" title="Edit"><i class="fa fa-edit"></i></a>
                  </td>
                </tr>
              </tbody>
              </table>
              </div>

              <div ng-if="dataloading" class="row" align="center">
                <div class="row">
                  <img src="<?php echo base_url();?>assets/dist/img/boxc.gif"> 
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


<div class="modal modal-admin-details">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Admin Details</h4>
      </div>
      <div class="modal-body">

        <div class="box-body">

          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-3"><strong>First Name : </strong></div>
                <div class="col-md-9">{{activeAdmin.wusrFirstName}}</div>
              </div>
              <div class="row">
                <div class="col-md-3"><strong>Last Name : </strong></div>
                <div class="col-md-9">{{activeAdmin.wusrLastName}}</div>
              </div>
              <div class="row">
                <div class="col-md-3"><strong>Username : </strong></div>
                <div class="col-md-9">{{activeAdmin.wusrUserName}}</div>
              </div>
              <div class="row">
                <div class="col-md-3"><strong>Unit Names : </strong></div>
                <div class="col-md-9">{{activeAdmin.wusrUnitNames}}</div>
              </div>
              <div class="row">
                <div class="col-md-3"><strong>Password : </strong></div>
                <div class="col-md-9">{{activeAdmin.wusrPassword}}</div>
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