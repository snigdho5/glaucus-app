<div class="wrapper" ng-controller="paymentProjectionlist">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Payment
          <small>Projection Logs</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Payment Projection Logs</li>
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
                  <th ng-click="sort('id')">Serial No.
                    <span class="glyphicon sort-icon" ng-show="sortKey=='id'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('firstName')">Sales Person
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ordVenue'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>                  
                  <th ng-click="sort('projectioncount')">No Of Projection
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ordCustomerName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('projectionDate')">Projection Date
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ordAmount'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('projectionamount')">Total Amount (INR)
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ordForDate'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th style="width: 90px;">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr dir-paginate="projection in appProjectionHistory|orderBy:sortKey:reverse|filter:search|itemsPerPage:5">
                <!--
                  <td>{{user.ausrId}}</td>-->
                  <td>{{$index + 1}}</td>
                  <td>{{projection.firstName}} {{projection.lastName}}</td>
                  <td>{{projection.projectioncount}}</td>
                  <td>{{projection.projectionDate}}</td>
                  <td>&#8377; {{projection.projectionamount}}</td>
                  <td>
                    <a class="btn btn-social-icon btn-danger" data-toggle="modal" data-target=".modal-order-details" ng-click="getProjectionDetails(projection.id)" title="View Projection Details"><i class="fa fa-eye"></i></a>
<!--                    <a class="btn btn-social-icon btn-danger" ng-click="goToEdit(order)" title="Edit"><i class="fa fa-edit"></i></a>-->
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


<div class="modal modal-order-details">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Projection Logs</h4>
      </div>
      <div class="modal-body">

        <div class="box-body">

          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <table class="table table-hover" id="tableToExport">
                <thead>
                <tr>
                <th>Serial No.</th>                
                <th>Distributor</th>
                <th>Collection Date</th>
                <th>Collection Amount</th>
                <th>Projection Date</th>
                </tr>
                </thead>
                    <tbody>
                        <tr ng-repeat="projectionlogs in projectionlist">
                            <td> {{$index + 1}}</td>                            
                            <td>{{projectionlogs.distributor}}</td>
                            <td>{{projectionlogs.collectionDate}}</td>
                            <td>&#8377; {{projectionlogs.collectionAmount}}</td>
                            <td>{{projectionlogs.projectionDate}}</td>                            
                        </tr>
                    </tbody>
                </table>
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