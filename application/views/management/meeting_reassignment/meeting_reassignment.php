<div class="wrapper" ng-controller="meetingreassignmentCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Meeting
          <small>Re-Assignment</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Meeting Re-Assignment</li>
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
                  <a ng-click="checkSelectedMeeting()"><button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target=".modal-meeting-updates" ><i class="fa fa-send"> </i>  Re-Assign To</button></a>
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
                  
                    <th>Select
                    </th>
                    <th ng-click="sort('mtnName')">Meeting Name
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnCustomerName')">Customer Name
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnCustomerName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnUserName')">Assign To
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnUserName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnDate')">Date
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnDate'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnTime')">Time
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnTime'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnVisited')">Visited
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnVisited'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnSignature')">Signature
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnSignature'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnPicture')">Picture
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnPicture'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnRemarks')">Remarks
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnRemarks'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('mtnParentName')">Created By
                      <span class="glyphicon sort-icon" ng-show="sortKey=='mtnParentName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <!--
                    <th>Action</th>-->
                  </tr>
                </thead>
                <tbody>
                  <tr dir-paginate="meeting in meetings|orderBy:sortKey:reverse|filter:search|itemsPerPage:5">
                    <td><div class="checkbox" style="margin-top: 0px;">
                    <label>
                      <input type="checkbox" ng-model="meeting.isChecked">
                    </label>
                  </div></td>
                    <td>{{meeting.mtnName}}</td>
                    <td>{{meeting.mtnCustomerName}}</td>
                    <td>{{meeting.mtnUserName}}</td>
                    <td>{{meeting.mtnDate}}</td>
                    <td>{{meeting.mtnTime}}</td>
                    <td><span ng-class='whatClassIsIt(meeting.mtnVisited)'>{{meeting.mtnVisited}}</span></td>
                    <td><span ng-class='whatClassIsIt(meeting.mtnSignature)'>{{meeting.mtnSignature}}</span></td>
                    <td><span ng-class='whatClassIsIt(meeting.mtnPicture)'>{{meeting.mtnPicture}}</span></td>
                    <td><span ng-class='whatClassIsIt(meeting.mtnRemarks)'>{{meeting.mtnRemarks}}</span></td>
                    <td>{{meeting.mtnParentName}}</td>
                    <!--
                    <td>
                      <a class="btn btn-social-icon btn-danger" data-toggle="modal" data-target=".modal-meeting-updates" ng-click="viewMeetingUpdates(meeting)" title="View Meeting Updates"><i class="fa fa-eye"></i></a>
                      <a class="btn btn-social-icon btn-danger" ng-click="goToEdit(meeting)" title="Edit"><i class="fa fa-edit"></i></a>
                    </td>-->
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


<div class="modal modal-meeting-updates">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Selected Meeting Re-Assign To</h4>
      </div>
      <div class="modal-body">
      <form name="updateForm" novalidate>
        <div class="box-body">

            <div class="row col-md-12">

              <div class="form-group col-md-12">
                <label for="mtnUserId">Select Re-Assign To*</label>
                <select class="form-control" ng-model="mtnUserId" ng-options="appUser.ausrUserName+' ['+appUser.ausrParentName+']' for appUser in appUsers" required></select>
              </div>
              
            </div>

        </div>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" ng-click="submitSelectedMeeting()" ng-disabled="updateForm.$invalid">Submit</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->




</div>
<!-- ./wrapper -->