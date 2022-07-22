<div class="wrapper" ng-controller="userCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          User
          <small>Management</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">User Management</li>
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
                <a href="<?php echo base_url();?>usermanagement/add"><button type="button" class="btn btn-block btn-primary"><i class="fa fa-plus"> </i>  Add User</button></a>
              </div>
              <div class="box-title pull-right" style="margin-right: 5px;">
                <a ng-click="refreshTable()"><button type="button" class="btn btn-block btn-primary" title="Refresh Table"><i class="fa fa-refresh"> </i></button></a>
              </div>
            </div>
            <!-- /.box-header -->
            <div >
            <div class="box-body table-responsive no-padding">
            <table class="table table-hover" id="tableToExport">
              <thead>
                <tr>
                <!--
                  <th ng-click="sort('ausrId')">Id
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ausrId'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>-->
                  <th ng-click="sort('ausrFirstName')">First Name
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ausrFirstName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('ausrLastName')">Last Name
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ausrLastName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('ausrUserEmail')">Email
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ausrUserEmail'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('ausrContactNo')">Contact No
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ausrContactNo'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('ausrUserName')">Username
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ausrUserName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('ausrParentName')">Created By
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ausrParentName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('ausrStatusName')">Status
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ausrStatusName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th style="width: 240px;">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr dir-paginate="user in appUsers|orderBy:sortKey:reverse|filter:search|itemsPerPage:5">
                <!--
                  <td>{{user.ausrId}}</td>-->
                  <td>{{user.ausrFirstName}}</td>
                  <td>{{user.ausrLastName}}</td>
                  <td>{{user.ausrUserEmail}}</td>
                  <td>{{user.ausrContactNo}}</td>
                  <td>{{user.ausrUserName}}</td>
                  <td>{{user.ausrParentName}}</td>
                  <td><span ng-class='whatClassIsIt(user.ausrStatusName)'>{{user.ausrStatusName}}</span></td>
                  <td>
                    <a class="btn btn-social-icon btn-danger" ng-click="userLogout(user)" title="Logout from App"><i class="fa fa-sign-out"></i></a>
                    <a class="btn btn-social-icon btn-danger" ng-click="myStatus(user)" title="Change Status"><i class="fa fa-check-circle"></i></a>
                    <a class="btn btn-social-icon btn-danger" data-toggle="modal" data-target=".modal-user-details" ng-click="viewUserDetails(user)" title="View User Details"><i class="fa fa-eye"></i></a>
                    <a class="btn btn-social-icon btn-danger" ng-click="goToEdit(user)" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-social-icon btn-danger" ng-click="viewUserDetails(user)" data-toggle="modal" data-target=".modal-change-created" title="Change Created By"><i class="fa fa-cogs"></i></a>
                  </td>
                </tr>
              </tbody>
              </table>
              </div>
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


<div class="modal modal-user-details">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">User Details</h4>
      </div>
      <div class="modal-body">

        <div class="box-body">

          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-3"><strong>First Name : </strong></div>
                <div class="col-md-9">{{activeUser.ausrFirstName}}</div>
              </div>
              <div class="row">
                <div class="col-md-3"><strong>Last Name : </strong></div>
                <div class="col-md-9">{{activeUser.ausrLastName}}</div>
              </div>
              <div class="row">
                <div class="col-md-3"><strong>Username : </strong></div>
                <div class="col-md-9">{{activeUser.ausrUserName}}</div>
              </div>
              <div class="row">
                <div class="col-md-3"><strong>Designation : </strong></div>
                <div class="col-md-9">{{activeUser.ausrDesignation}}</div>
              </div>
              <div class="row">
                <div class="col-md-3"><strong>Email : </strong></div>
                <div class="col-md-9">{{activeUser.ausrUserEmail}}</div>
              </div>
              <div class="row">
                <div class="col-md-3"><strong>Contact No : </strong></div>
                <div class="col-md-9">{{activeUser.ausrContactNo}}</div>
              </div>
              <div class="row">
                <div class="col-md-3"><strong>Password : </strong></div>
                <div class="col-md-9">{{activeUser.ausrPassword}}</div>
              </div>
              <div class="row" ng-if="activeUser.ausrKycFile">
                <div class="col-md-3"><strong>KYC Document : </strong></div>
                <div class="col-md-9"><iframe ng-src="{{activeUser.ausrKycFile}}" style="width:400px; height:350px;" frameborder="0"></iframe></div>
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



<div class="modal modal-change-created">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Change Created By To</h4>
      </div>
      <div class="modal-body">
      <form name="updateForm" novalidate>
        <div class="box-body">

            <div class="row col-md-12">

              <div class="form-group col-md-12">
                <div class="row">
                  <div class="col-md-3"><strong>First Name : </strong></div>
                  <div class="col-md-9">{{activeUser.ausrFirstName}}</div>
                </div>
                <div class="row">
                  <div class="col-md-3"><strong>Last Name : </strong></div>
                  <div class="col-md-9">{{activeUser.ausrLastName}}</div>
                </div>
                <div class="row">
                  <div class="col-md-3"><strong>Username : </strong></div>
                  <div class="col-md-9">{{activeUser.ausrUserName}}</div>
                </div>
                <div class="row">
                  <div class="col-md-3"><strong>Designation : </strong></div>
                  <div class="col-md-9">{{activeUser.ausrDesignation}}</div>
                </div>
              </div>

              <div class="form-group col-md-12">
              
                <label for="mtnUserId">Select Created By To*</label>
                <select class="form-control" ng-model="adminParent" ng-options="admin.adminUserName+' ['+admin.adminParentName+']' for admin in admins" required></select>
              </div>
              
            </div>

        </div>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" ng-click="updateCreatedBy()" ng-disabled="updateForm.$invalid">Update</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->




</div>
<!-- ./wrapper -->