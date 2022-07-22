<link rel="stylesheet" href="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<script type="text/javascript" src="http://momentjs.com/downloads/moment-with-locales.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

<div class="wrapper" ng-controller="customersreportCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Customer
          <small>Report</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Customer Report</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">

        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->

            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title">Customer Report 
                  <small>Filter</small>
                </h3>
                <!-- tools box -->
                <div class="pull-right box-tools">
                  <a ng-click="exportToPDF('printBody')" class="btn btn-danger btn-sm"  title="Update" style="margin-right: 5px;"><i class="fa fa-file-pdf-o"></i> PDF</a>
                  <a ng-click="exportToExcel('#tableToExport')" class="btn btn-danger btn-sm"  title="Update" style="margin-right: 5px;"><i class="fa  fa-file-excel-o"></i> Excel</a>
                  <a ng-click="printToReport('printBody')" class="btn btn-danger btn-sm"  title="Update" style="margin-right: 5px;"><i class="fa fa-print"></i> Print</a>
                  <button type="button" class="btn btn-primary btn-sm" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                    <i class="fa fa-minus"></i></button>
                </div>
                <!-- /. tools -->
              </div>
              <!-- /.box-header -->
              <div class="box-body pad" style="">

                <div class="row col-md-12">

                  <div class="form-group col-md-3">
                    <label for="toDate">Select Admin</label>
                    <select class="form-control" ng-change="getAdminUsers()" ng-model="selectedAdmin" ng-options="admin.adminName+' ['+admin.adminUnitNames+']' for admin in admins"></select>
                  </div>

                  <div class="form-group col-md-3">
                    <label for="toDate">Select User</label>
                    <select class="form-control" ng-model="selectedUser" ng-options="user.usrName+' ['+user.usrParentName+']' for user in users"></select>
                  </div>

                  <div class="form-group col-md-3">
                    <label for="toDate">Select Customer Type</label>
                    <select class="form-control" ng-model="selectedCustomerType" ng-options="customertype.custName for customertype in customertypes"></select>
                  </div>

                  <div class="form-group col-md-3">
                    <label for="toDate">Select Industry Type</label>
                    <select class="form-control" ng-model="selectedIndustryType" ng-options="industrytype.indtName for industrytype in industrytypes"></select>
                  </div>
                  
                </div>

                <div class="row col-md-12">

                  <div class="form-group col-md-3">
                    <label for="toDate">Select Company</label>
                    <select class="form-control" ng-model="selectedCompany" ng-options="company.cmpName for company in companies"></select>
                  </div>

                  <div class="form-group col-md-3">
                    <label for="toDate">Select Area</label>
                    <select class="form-control" ng-model="selectedArea" ng-options="area.areaName for area in areas"></select>
                  </div>

                  <div class="form-group col-md-3">
                    <label for="fromDate">Customer Date of Birth</label>
                    <input type="text" id="dob" class="form-control floating-label" placeholder="Date of Birth">
                  </div>

                  <div class="form-group col-md-3">
                    <label for="fromDate">Customer Date of Anniversary</label>
                    <input type="text" id="doa" class="form-control floating-label" placeholder="Date of Anniversary">
                  </div>
                  
                </div>

                <div class="row col-md-12">
                  <div class="form-group col-md-2 pull-right" style="margin-top:24px;">
                    <button type="button" ng-click="getReports()" class="btn btn-block btn-primary form-control" title="Refresh Table"><i class="fa fa-refresh"> </i> Submit</button>
                  </div>
                </div>

              </div>
            </div>


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
              </div>
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding" id="printBody">
                <div class="col-md-6"><h4><strong>Sales App Customer Report</strong></h4></div><div class="col-md-6" align="right"><h4><strong>{{reportDateInfo}}</strong></h4></div>
                <table class="table table-hover" id="tableToExport">
                  <thead>
                    <tr>
                      <th ng-click="sort('cusCode')">Code
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusCode'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('cusFirstName')">First Name
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusFirstName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('cusLastName')">Last Name
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusLastName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('cusGender')">Gender
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusGender'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('cusCustomerType')">Customer Type
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusCustomerType'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('cusLastMeetingName')">Last Meeting Name
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusLastMeetingName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('cusLastMeetingDateTime')">Last Meeting DateTime
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusLastMeetingDateTime'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th style="display: none;" ng-hide="true" ng-click="sort('cusDOB')">DOB
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusDOB'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th style="display: none;" ng-click="sort('cusDOA')">DOA
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusDOA'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th style="display: none;" ng-click="sort('cusIndustryType')">Industry Type
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusIndustryType'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th style="display: none;" ng-click="sort('cusCompanyName')">Company Name
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusCompanyName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th style="display: none;" ng-click="sort('cusDepartment')">Department
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusDepartment'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th style="display: none;" ng-click="sort('cusDesignation')">Designation
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusDesignation'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th style="display: none;" ng-click="sort('cusEmail')">Email
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusEmail'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th style="display: none;" ng-click="sort('cusMobileNo')">Mobile No
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusMobileNo'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th style="display: none;" ng-click="sort('cusAlternateNo')">Alternate No
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusAlternateNo'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th style="display: none;" ng-click="sort('cusCountry')">Country
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusCountry'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th style="display: none;" ng-click="sort('cusState')">State
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusState'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th style="display: none;" ng-click="sort('cusCity')">City
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusCity'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th style="display: none;" ng-click="sort('cusAddress')">Address
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusAddress'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th style="display: none;" ng-click="sort('cusLandmark')">Landmark
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusLandmark'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th style="display: none;" ng-click="sort('cusArea')">Area
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusArea'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th style="display: none;" ng-click="sort('cusPinCode')">Pincode
                        <span class="glyphicon sort-icon" ng-show="sortKey=='cusPinCode'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr dir-paginate="customer in customers|orderBy:sortKey:reverse|filter:search|itemsPerPage:10000000">
                      <td>{{customer.cusCode}}</td>
                      <td>{{customer.cusFirstName}}</td>
                      <td>{{customer.cusLastName}}</td>
                      <td>{{customer.cusGender}}</td>
                      <td>{{customer.cusCustomerType}}</td>
                      <td>{{customer.cusLastMeetingName}}</td>
                      <td>{{customer.cusLastMeetingDateTime}}</td>
                      <td style="display: none;" >{{customer.cusDOB}}</td>
                      <td style="display: none;" >{{customer.cusDOA}}</td>
                      <td style="display: none;" >{{customer.cusIndustryType}}</td>
                      <td style="display: none;" >{{customer.cusCompanyName}}</td>
                      <td style="display: none;" >{{customer.cusDepartment}}</td>
                      <td style="display: none;" >{{customer.cusDesignation}}</td>
                      <td style="display: none;" >{{customer.cusEmail}}</td>
                      <td style="display: none;" >{{customer.cusMobileNo}}</td>
                      <td style="display: none;" >{{customer.cusAlternateNo}}</td>
                      <td style="display: none;" >{{customer.cusCountry}}</td>
                      <td style="display: none;" >{{customer.cusState}}</td>
                      <td style="display: none;" >{{customer.cusCity}}</td>
                      <td style="display: none;" >{{customer.cusAddress}}</td>
                      <td style="display: none;" >{{customer.cusLandmark}}</td>
                      <td style="display: none;" >{{customer.cusArea}}</td>
                      <td style="display: none;" ng-hide="true" >{{customer.cusPinCode}}</td>
                    </tr>
                  </tbody>
                  </table>


                </div>
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