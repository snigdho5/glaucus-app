<link rel="stylesheet" href="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<script type="text/javascript" src="http://momentjs.com/downloads/moment-with-locales.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

<div class="wrapper" ng-controller="tripreportCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Trip
          <small>Report</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Trip Report</li>
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
                <h3 class="box-title">Trip Report 
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

                <div class="form-group col-md-2">
                  <label for="fromDate">From Date</label>
                  <input type="text" id="fromDate" class="form-control floating-label" placeholder="From Date">
                </div>

                <div class="form-group col-md-2">
                  <label for="toDate">To Date</label>
                  <input type="text" id="toDate" class="form-control floating-label" placeholder="To Date">
                </div>

                <div class="form-group col-md-2">
                  <label for="toDate">Select User</label>
                  <select class="form-control" ng-model="selectedUser" ng-options="user.usrUserName+' ['+user.usrParentName+']'  for user in appUsers"></select>
                </div>

                <div class="form-group col-md-2" style="margin-top:24px;">
                  <button type="button" ng-click="getReports()" class="btn btn-block btn-primary form-control" title="Refresh Table"><i class="fa fa-refresh"> </i> Submit</button>
                </div>
                  
                </div>


              </div>
            </div>







<!--
            <div class="box box-primary" style="padding-left:10px; padding-right:10px;">
              <div class="box-header with-border">


                <div class="form-group col-md-2">
                  <label for="fromDate">From Date</label>
                  <input type="text" id="fromDate" class="form-control floating-label" placeholder="From Date">
                </div>

                <div class="form-group col-md-2">
                  <label for="toDate">To Date</label>
                  <input type="text" id="toDate" class="form-control floating-label" placeholder="To Date">
                </div>

                <div class="form-group col-md-2">
                  <label for="toDate">Select User</label>
                  <select class="form-control" ng-model="selectedUser" ng-options="user.usrUserName+' ['+user.usrParentName+']'  for user in appUsers"></select>
                </div>

                <div class="form-group col-md-2" style="margin-top:24px;">
                  <button type="button" ng-click="getReports()" class="btn btn-block btn-primary form-control" title="Refresh Table"><i class="fa fa-refresh"> </i> Submit</button>
                </div>

                <div class="row">

                  <div class="form-group col-md-4" style="margin-top:24px;" align="right">
                    <a ng-click="exportToPDF('printBody')" class="btn btn-danger"  title="Update" style="margin-right: 5px;"><i class="fa fa-file-pdf-o"></i> PDF</a>
                    <a ng-click="exportToExcel('#tableToExport')" class="btn btn-danger"  title="Update" style="margin-right: 5px;"><i class="fa  fa-file-excel-o"></i> Excel</a>
                    <a ng-click="printToReport('printBody')" class="btn btn-danger"  title="Update"><i class="fa fa-print"></i> Print</a>
                  </div>
                </div>
                    
              </div>

            </div>-->
            <!-- /.box -->


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
                <div class="col-md-6"><h4><strong>Sales App Trip Report</strong></h4></div><div class="col-md-6" align="right"><h4><strong>{{reportDateInfo}}</strong></h4></div>
                <table class="table table-hover" id="tableToExport">
                  <thead>
                    <tr>
                      <th ng-click="sort('trpSNo')">S No
                        <span class="glyphicon sort-icon" ng-show="sortKey=='trpSNo'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('trpUserName')">Username
                        <span class="glyphicon sort-icon" ng-show="sortKey=='trpUserName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('trpStartDate')">Start Date
                        <span class="glyphicon sort-icon" ng-show="sortKey=='trpStartDate'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('trpStartTime')">Start Time
                        <span class="glyphicon sort-icon" ng-show="sortKey=='trpStartTime'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('trpEndDate')">Stop Date
                        <span class="glyphicon sort-icon" ng-show="sortKey=='trpEndDate'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('trpEndTime')">Stop Time
                        <span class="glyphicon sort-icon" ng-show="sortKey=='trpEndTime'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('trpTotalTime')">Total Time
                        <span class="glyphicon sort-icon" ng-show="sortKey=='trpTotalTime'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('trpDistance')">Distance
                        <span class="glyphicon sort-icon" ng-show="sortKey=='trpDistance'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr dir-paginate="trip in trips|orderBy:sortKey:reverse|filter:search|itemsPerPage:10000000">
                      <td>{{trip.trpSNo}}</td>
                      <td>{{trip.trpUserName}}</td>
                      <td>{{trip.trpStartDate}}</td>
                      <td>{{trip.trpStartTime}}</td>
                      <td>{{trip.trpEndDate}}</td>
                      <td>{{trip.trpEndTime}}</td>
                      <td>{{trip.trpTotalTime}}</td>
                      <td>{{trip.trpDistance}}</td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td><h4><strong>Total Distance</strong></h4></td>
                      <td><h4><strong>{{grandTotalDistance}} KM</strong></h4></td>
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