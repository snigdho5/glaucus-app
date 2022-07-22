<div class="wrapper" ng-controller="allOrderslist">

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
                  <th ng-click="sort('id')">Serial No.
                    <span class="glyphicon sort-icon" ng-show="sortKey=='id'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('id')">Order Id
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ordName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('firstName')">Sales Person
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ordVenue'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('distributor')">Distributor
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ordAmount'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('noOfItemsOder')">No Of Items Oders
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ordCustomerName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('status')">Status
                      <span class="glyphicon sort-icon" ng-show="sortKey=='status'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th ng-click="sort('totalPrice')">Amount (INR)
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ordForDate'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr dir-paginate="order in approchakorders|orderBy:sortKey:reverse|filter:search|itemsPerPage:5">
                <!--
                  <td>{{user.ausrId}}</td>-->
                  <td>{{$index + 1}}</td>
                  <td>{{order.id}}</td>
                  <td>{{order.firstName}} {{order.lastName}}</td>
                  <td>{{order.distributor}}</td>
                  <td>{{order.noOfItemsOder}}</td>
                  <td>
                      <span ng-if="order.status === '1'" ng-class='badgeClass(order.status)'>Delivered</span>
                      <span ng-if="order.status === '0'" ng-class='badgeClass(order.status)'>Not Delivered</span>
                  </td>
                  <td>&#8377;{{order.totalPrice}}</td>
                  <td>
                    <a class="btn btn-social-icon btn-danger" ng-click="changeStatus(order)" title="Change Status">
                      <i ng-if="order.status === '0'" class="fa fa-check"></i>
                      <i ng-if="order.status === '1'" class="fa fa-times"></i>
                    </a>
                    <a class="btn btn-social-icon btn-primary" data-toggle="modal" data-target=".modal-order-details" ng-click="getOrderDetails(order.id)" title="View Order Details"><i class="fa fa-eye"></i></a>
                    <a class="btn btn-social-icon btn-primary" data-toggle="modal" ng-click="getInvoiceDetails(order.id)" title="View Invoice Details"><i class="fa fa-stack-exchange"></i></a>
                    <a class="btn btn-social-icon btn-primary" ng-click="addInvoice(order)" title="Edit"><i class="fa fa-plus"></i></a>
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Order Details</h4>
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
                      <th>Item Name</th>
                      <th>Package</th>
                      <th>Size</th>
                      <th>Item Price</th>
                      <th>Quantity</th>
                      <th>Item Total Price</th>
                      <th>Order Place Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr ng-repeat="orderlist in rochakorderslist">
                      <td> {{$index + 1}}</td>
                      <td>{{orderlist.itemName}}</td>
                      <td>{{orderlist.packageMode}}</td>
                      <td>{{orderlist.packageSize}}</td>
                      <td>{{orderlist.itemPrice}}</td>
                      <td>{{orderlist.cartQuantity}}</td>
                      <td>&#8377;{{orderlist.totalItemPrice}}</td>
                      <td>{{orderlist.orderDate}}</td>
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


<div class="modal modal-invoice-details">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Invoice Details</h4>
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
                      <th>Invoice ID</th>
                      <th>Order ID</th>
                      <th>Invoice Date</th>
                      <th>Invoice Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr ng-repeat="invoicedata in glaucusinvoicedata">
                      <td>{{$index + 1}}</td>
                      <td>{{invoicedata.invoice_id}}</td>
                      <td>{{invoicedata.order_id}}</td>
                      <td>{{invoicedata.invoice_date}}</td>
                      <td>&#8377;{{invoicedata.invoice_amount}}</td>
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