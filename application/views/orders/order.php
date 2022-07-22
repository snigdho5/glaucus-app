<div class="wrapper" ng-controller="orderCtrl">

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
          <li class="active">Order Management</li>
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
                  <th ng-click="sort('ordName')">Name
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ordName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('ordVenue')">Venue
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ordVenue'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('ordAmount')">Amount (INR)
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ordAmount'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('ordCustomerName')">Customer Name
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ordCustomerName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('ordForDate')">For Date
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ordForDate'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('ordParentName')">Created By
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ordParentName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('ordStatus')">Status
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ordStatus'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th style="width: 90px;">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr dir-paginate="order in orders|orderBy:sortKey:reverse|filter:search|itemsPerPage:5">
                <!--
                  <td>{{user.ausrId}}</td>-->
                  <td>{{order.ordName}}</td>
                  <td>{{order.ordVenue}}</td>
                  <td>{{order.ordAmount}}</td>
                  <td>{{order.ordCustomerName}}</td>
                  <td>{{order.ordForDate}}</td>
                  <td>{{order.ordParentName}}</td>
                  <td><span ng-class='whatClassIsIt(order.ordStatus)'>{{order.ordStatus}}</span></td>
                  <td>
                    <a class="btn btn-social-icon btn-danger" data-toggle="modal" data-target=".modal-order-details" ng-click="viewOrderDetails(order)" title="View Order Details"><i class="fa fa-eye"></i></a>
                    <a class="btn btn-social-icon btn-danger" ng-click="goToEdit(order)" title="Edit"><i class="fa fa-edit"></i></a>
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
        <h4 class="modal-title">Query Details</h4>
      </div>
      <div class="modal-body">

        <div class="box-body">

          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4"><strong>Name : </strong></div>
                <div class="col-md-8">{{activeOrder.ordName}}</div>
              </div>
              <div class="row">
                <div class="col-md-4"><strong>Unit : </strong></div>
                <div class="col-md-8">{{activeOrder.ordUnit}}</div>
              </div>
              <div class="row">
                <div class="col-md-4"><strong>Venue : </strong></div>
                <div class="col-md-8">{{activeOrder.ordVenue}}</div>
              </div>
              <div class="row">
                <div class="col-md-4"><strong>Quantity : </strong></div>
                <div class="col-md-8">{{activeOrder.ordQuantity}}</div>
              </div>
              <div class="row">
                <div class="col-md-4"><strong>Amount (INR) : </strong></div>
                <div class="col-md-8">{{activeOrder.ordAmount}}</div>
              </div>
              <div class="row">
                <div class="col-md-4"><strong>Order For Date : </strong></div>
                <div class="col-md-8">{{activeOrder.ordForDate}}</div>
              </div>
              <div class="row">
                <div class="col-md-4"><strong>Created Date : </strong></div>
                <div class="col-md-8">{{activeOrder.ordDate}}</div>
              </div>
              <div class="row">
                <div class="col-md-4"><strong>Created Time : </strong></div>
                <div class="col-md-8">{{activeOrder.ordTime}}</div>
              </div>
              <div class="row">
                <div class="col-md-4"><strong>Details : </strong></div>
                <div class="col-md-8">{{activeOrder.ordDescription}}<br></div>
              </div>
              <div class="row">
                <div class="col-md-4"><strong>Meeting Name : </strong></div>
                <div class="col-md-8">{{activeOrder.ordMeetingName}}</div>
              </div>
              <div class="row">
                <div class="col-md-4"><strong>Customer Name : </strong></div>
                <div class="col-md-8">{{activeOrder.ordCustomerName}}</div>
              </div>
              <div class="row">
                <div class="col-md-4"><strong>Company Name : </strong></div>
                <div class="col-md-8">{{activeOrder.ordCustomerCompanyName}}</div>
              </div>
              <div class="row">
                <div class="col-md-4"><strong>Customer Address : </strong></div>
                <div class="col-md-8">{{activeOrder.ordCustomerAddress}}<br></div>
              </div>
              <div class="row">
                <div class="col-md-4"><strong>Created By : </strong></div>
                <div class="col-md-8">{{activeOrder.ordParentName}}</div>
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