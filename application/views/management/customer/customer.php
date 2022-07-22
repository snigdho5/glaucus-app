<div class="wrapper" ng-controller="customerCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Customer
          <small>Management</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Customer Management</li>
        </o]l>
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
<!--
                <div class="box-title pull-right">
                  <a href="<?php echo base_url();?>customermanagement/addm"><button type="button" class="btn btn-block btn-primary"><i class="fa fa-plus"> </i>  Add Multiple Customer</button></a>
                </div>-->
                <div class="box-title pull-right" style="margin-right: 5px;">
                  <a href="<?php echo base_url();?>customermanagement/add"><button type="button" class="btn btn-block btn-primary"><i class="fa fa-plus"> </i>  Add Customer</button></a>
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
                    <th></th>
                    <th ng-click="sort('cusCode')">Customer Code
                      <span class="glyphicon sort-icon" ng-show="sortKey=='cusCode'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('cusFirstName')">First Name
                      <span class="glyphicon sort-icon" ng-show="sortKey=='cusFirstName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('cusLastName')">Last Name
                      <span class="glyphicon sort-icon" ng-show="sortKey=='cusLastName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('cusEmail')">Email
                      <span class="glyphicon sort-icon" ng-show="sortKey=='cusEmail'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('cusMobileNo')">Mobile No
                      <span class="glyphicon sort-icon" ng-show="sortKey=='cusMobileNo'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('cusCompanyName')">Company Name
                      <span class="glyphicon sort-icon" ng-show="sortKey=='cusCompanyName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('cusManageName')">Manage By
                      <span class="glyphicon sort-icon" ng-show="sortKey=='cusManageName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th ng-click="sort('cusParentName')">Created By
                      <span class="glyphicon sort-icon" ng-show="sortKey=='cusParentName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                    </th>
                    <th style="width: 90px;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr dir-paginate-start="customer in customers|orderBy:sortKey:reverse|filter:search|itemsPerPage:5" ng-click="expanded = !expanded">
                    <td>
                      <span ng-bind="expanded ? '&#8650' : '&#8649'"></span>
                    </td>
                    <td>{{customer.cusCode}}</td>
                    <td>{{customer.cusFirstName}}</td>
                    <td>{{customer.cusLastName}}</td>
                    <td>{{customer.cusEmail}}</td>
                    <td>{{customer.cusMobileNo}}</td>
                    <td>{{customer.cusCompanyName}}</td>
                    <td>{{customer.cusManageName}}</td>
                    <td>{{customer.cusParentName}}</td>
                    <td>
                      <a class="btn btn-social-icon btn-danger" ng-click="addMeeting(customer)" title="Create Meeting"><i class="fa fa-calendar-plus-o"></i></a>
                      <a class="btn btn-social-icon btn-danger" ng-click="goToEdit(customer)" title="Edit"><i class="fa fa-edit"></i></a>
                    </td>
                  </tr>
                  <tr dir-paginate-end ng-show="expanded">
                      <td></td>
                      <td colspan="2">
                        <div align="center"><span style="color: #3c8dbc;"><strong>General Details</strong></span></div>
                        <div><span><strong>Customer Code: </strong>{{customer.cusCode}}</span></div>
                        <div><span><strong>First Name: </strong>{{customer.cusFirstName}}</span></div>
                        <div><span><strong>Last Name: </strong>{{customer.cusLastName}}</span></div>
                        <div><span><strong>Gender: </strong>{{customer.cusGender}}</span></div>
                        <div><span><strong>Date of Birth: </strong>{{customer.cusDOB}}</span></div>
                        <div><span><strong>Date of Anniversary: </strong>{{customer.cusDOA}}</span></div>
                      </td>
                      <td colspan="3">
                        <div align="center"><span style="color: #3c8dbc;"><strong>Professional Details</strong></span></div>
                        <div><span><strong>Customer Type: </strong>{{customer.cusCustomerType}}</span></div>
                        <div><span><strong>Industry Type: </strong>{{customer.cusIndustryType}}</span></div>
                        <div><span><strong>Company Name: </strong>{{customer.cusCompanyName}}</span></div>
                        <div><span><strong>Department: </strong>{{customer.cusDepartment}}</span></div>
                        <div><span><strong>Designation: </strong>{{customer.cusDesignation}}</span></div>
                      </td>
                      <td colspan="3">
                        <div align="center"><span style="color: #3c8dbc;"><strong>Contact Details</strong></span></div>
                        <div><span><strong>Email: </strong>{{customer.cusEmail}}</span></div>
                        <div><span><strong>Mobile No.: </strong>{{customer.cusMobileNo}}</span></div>
                        <div><span><strong>Alternate No.: </strong>{{customer.cusAlternateNo}}</span></div>
                        <div><span><strong>Country: </strong>{{customer.cusCountry}}</span></div>
                        <div><span><strong>State: </strong>{{customer.cusState}}</span></div>
                        <div><span><strong>City: </strong>{{customer.cusCity}}</span></div>
                        <div><span><strong>Area: </strong>{{customer.cusArea}}</span></div>
                        <div><span><strong>Address: </strong>{{customer.cusAddress}}</span></div>
                        <div><span><strong>Landmark: </strong>{{customer.cusLandmark}}</span></div>
                        <div><span><strong>Pincode: </strong>{{customer.cusPinCode}}</span></div>
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
</div>
<!-- ./wrapper -->