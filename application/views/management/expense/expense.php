  <link href="http://www.professorcloud.com/styles/cloud-zoom.css" rel="stylesheet" type="text/css">
  <script type="text/JavaScript" src="http://www.professorcloud.com/js/cloud-zoom.1.0.2.js"></script>
<div class="wrapper" ng-controller="expenseCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Expense
          <small>Management</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Expense Management</li>
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
              <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th ng-click="sort('expId')">Id
                      <span class="glyphicon sort-icon" ng-show="sortKey=='expId'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('expDate')">Date
                      <span class="glyphicon sort-icon" ng-show="sortKey=='expDate'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('expTitle')">Title
                      <span class="glyphicon sort-icon" ng-show="sortKey=='expTitle'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('expAmount')">Amount (INR)
                      <span class="glyphicon sort-icon" ng-show="sortKey=='expAmount'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('expImageAvailable')">Image Available
                      <span class="glyphicon sort-icon" ng-show="sortKey=='expImageAvailable'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('expIsMeetingAssociated')">Meeting Associated
                      <span class="glyphicon sort-icon" ng-show="sortKey=='expIsMeetingAssociated'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('expPaymentStatus')">Payment Status
                      <span class="glyphicon sort-icon" ng-show="sortKey=='expPaymentStatus'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('expCompleted')">Completed
                      <span class="glyphicon sort-icon" ng-show="sortKey=='expCompleted'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('expParentName')">Created By
                      <span class="glyphicon sort-icon" ng-show="sortKey=='expParentName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th style="width: 135px;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr dir-paginate="expense in expenses|orderBy:sortKey:reverse|filter:search|itemsPerPage:5">
                    <td>{{expense.expId}}</td>
                    <td>{{expense.expDate}}</td>
                    <td>{{expense.expTitle}}</td>
                    <td>{{expense.expAmount}}</td>
                    <td><span ng-class='whatClassIsIt(expense.expImageAvailable)'>{{expense.expImageAvailable}}</span></td>
                    <td><span ng-class='whatClassIsIt(expense.expIsMeetingAssociated)'>{{expense.expIsMeetingAssociated}}</span></td>
                    <td><span ng-class='whatClassIsIt(expense.expPaymentStatus)'>{{expense.expPaymentStatus}}</span></td>
                    <td><span ng-class='whatClassIsIt(expense.expCompleted)'>{{expense.expCompleted}}</span></td>
                    <td>{{expense.expParentName}}</td>
                    <td>
                      <a class="btn btn-social-icon btn-danger" data-toggle="modal" data-target=".modal-expense-details" ng-click="viewExpense(expense)" title="View Details"><i class="fa fa-eye"></i></a>
                      <a class="btn btn-social-icon btn-danger" ng-click="submitPaid(expense)" title="Paid"><i class="fa fa-check-circle"></i></a>
                      <a class="btn btn-social-icon btn-danger" ng-click="submitUnpaid(expense)" title="Unpaid"><i class="fa fa-close"></i></a>
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


<div class="modal modal-expense-details">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Expense Details</h4>
      </div>
      <div class="modal-body">

        <div class="box-body">

          <div class="row col-md-12" style="margin-bottom: 5px;">
             <fieldset class="col-md-12" style="border: 1px solid black; margin-left: 10px; padding-bottom: 10px;">
              <legend style="width: 150px; border-bottom: 0px; margin-bottom: 5px; text-align: center;"> Expense Details </legend>
                <div class="row">
                  <div class="col-md-4"><strong>Expense Title : </strong></div>
                  <div class="col-md-8">{{activeExpense.expTitle}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Amount (INR) : </strong></div>
                  <div class="col-md-8">{{activeExpense.expAmount}}</div>
                </div>
                <div class="row" ng-hide="meetingNameDisplay">
                  <div class="col-md-4"><strong>Meeting Name : </strong></div>
                  <div class="col-md-8">{{activeExpense.expMeetingName}}</div>
                </div>
                <div class="row" ng-hide="customerNameDisplay">
                  <div class="col-md-4"><strong>Customer Name : </strong></div>
                  <div class="col-md-8">{{activeExpense.expMeetingCustomerName}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Expense Description : </strong></div>
                  <div class="col-md-8">{{activeExpense.expDescription}}</div>
                </div>
                <div class="row">
                  <div class="col-md-4"><strong>Image Available : </strong></div>
                  <div class="col-md-8">{{activeExpense.expImageAvailable}}</div>
                </div>
                <div class="row" ng-hide="imageDisplay">
                  <div class="col-md-12"><strong>Expense Image : </strong></div>
                  <div class="col-md-12" align="center"><img src="{{activeExpense.expImage}}" style="margin-top: 10px; width: 450px; height: 450px;" id="expense-image-view">
                  <img src="{{activeExpense.expImage}}" style="margin-top: 10px; width: 450px; height: 450px; background-color: white;" id="expense-image-container"></div>
                </div>
             </fieldset>
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