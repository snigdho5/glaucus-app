<link rel="stylesheet" href="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<script type="text/javascript" src="http://momentjs.com/downloads/moment-with-locales.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

<div class="wrapper" ng-controller="usermeetingreportCtrl">

<?php $this->load->view('include/user_header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Dashboard
          <small>Meeting Report</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>user/"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Meeting Report</li>
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
                <div class="col-md-3">
                  <h3 class="box-title">Meeting Report 
                    <small>Filter</small>
                  </h3>
                </div>

                <!-- tools box -->
                <div class="col-md-9">

                  <div class="pull-right box-tools">
                    <a ng-click="exportToPDF('printBody')" class="btn btn-danger btn-sm"  title="Update" style="margin-right: 5px;"><i class="fa fa-file-pdf-o"></i> PDF</a>
                    <a ng-click="exportAction()" class="btn btn-danger btn-sm"  title="Update" style="margin-right: 5px;"><i class="fa fa-file-pdf-o"></i> Mobile PDF</a>
                    <a ng-click="exportToExcel('#tableToExport')" class="btn btn-danger btn-sm"  title="Update" style="margin-right: 5px;"><i class="fa  fa-file-excel-o"></i> Excel</a>
                    <a ng-click="printToReport('printBody')" class="btn btn-danger btn-sm"  title="Update" style="margin-right: 5px;"><i class="fa fa-print"></i> Print</a>
                    <button type="button" class="btn btn-primary btn-sm" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                      <i class="fa fa-minus"></i></button>
                  </div>
                  
                </div>

                <!-- /. tools -->
              </div>
              <!-- /.box-header -->
              <div class="box-body pad" style="">

                <div class="row col-md-12">

                  <div class="form-group col-md-3">
                    <label for="toDate">Select Date Type</label>
                    <select class="form-control" ng-change="getDateType()" ng-model="selectedDateType" ng-options="datetype.dttName for datetype in datetypes"></select>
                  </div>

                  <div class="form-group col-md-3">
                    <label for="fromDate">From Date</label>
                    <input type="text" ng-disabled="fromDate" id="fromDate" class="form-control floating-label" placeholder="From Date">
                  </div>

                  <div class="form-group col-md-3">
                    <label for="toDate">To Date</label>
                    <input type="text" ng-disabled="toDate" id="toDate" class="form-control floating-label" placeholder="To Date">
                  </div>

                  <div class="form-group col-md-3">
                    <label for="toDate">Select Customer Type</label>
                    <select class="form-control" ng-model="selectedCustomerType" ng-options="customertype.custName for customertype in customertypes"></select>
                  </div>

                </div>
                <div class="row col-md-12">


                  <div class="form-group col-md-6">
                    <label for="toDate">Select Industry Type</label>
                    <select class="form-control" ng-model="selectedIndustryType" ng-options="industrytype.indtName for industrytype in industrytypes"></select>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="toDate">Select Company</label>
                    <select class="form-control" ng-model="selectedCompany" ng-options="company.cmpName for company in companies"></select>
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
                <div class="col-md-6"><h4><strong>Sales App Meeting Report</strong></h4></div><div class="col-md-6" align="right"><h4><strong>{{reportDateInfo}}</strong></h4></div>
                <table class="table table-hover export-table" id="tableToExport">
                  <thead>
                    <tr>
                      <th ng-click="sort('mtnCode')">Code
                        <span class="glyphicon sort-icon" ng-show="sortKey=='mtnCode'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('mtnName')">Meeting Name
                        <span class="glyphicon sort-icon" ng-show="sortKey=='mtnName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('mtnDate')">Meeting Date
                        <span class="glyphicon sort-icon" ng-show="sortKey=='mtnDate'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('mtnTime')">Meeting Time
                        <span class="glyphicon sort-icon" ng-show="sortKey=='mtnTime'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('lgnrLogoutDate')">Type
                        <span class="glyphicon sort-icon" ng-show="sortKey=='lgnrLogoutDate'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('lgnrLogoutTime')">Customer Name
                        <span class="glyphicon sort-icon" ng-show="sortKey=='lgnrLogoutTime'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('mtnVisited')">Visited
                        <span class="glyphicon sort-icon" ng-show="sortKey=='mtnVisited'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('mtnCompleted')">Completed
                        <span class="glyphicon sort-icon" ng-show="sortKey=='mtnCompleted'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('mtnUserName')">Assigned User
                        <span class="glyphicon sort-icon" ng-show="sortKey=='mtnUserName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr dir-paginate="meeting in meetings|orderBy:sortKey:reverse|filter:search|itemsPerPage:10000000">
                      <td>{{meeting.mtnCode}}</td>
                      <td>{{meeting.mtnName}}</td>
                      <td>{{meeting.mtnDate}}</td>
                      <td>{{meeting.mtnTime}}</td>
                      <td>{{meeting.mtnMeetingType}}</td>
                      <td>{{meeting.mtnCustomerName}}</td>
                      <td>{{meeting.mtnVisited}}</td>
                      <td>{{meeting.mtnCompleted}}</td>
                      <td>{{meeting.mtnUserName}}</td>
                    </tr>
                  </tbody>
                  </table>


                </div>
            </div>
            <!-- /.box -->

          </div>
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
  <?php $this->load->view('include/footer'); ?>
</div>
<!-- ./wrapper -->