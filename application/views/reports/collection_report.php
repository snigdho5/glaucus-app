<link rel="stylesheet" href="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<script type="text/javascript" src="//momentjs.com/downloads/moment-with-locales.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.js"></script>
<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

<div class="wrapper" ng-controller="rochakcollectionreport">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Collection
          <small>Report</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Collection Report</li>
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
                <h3 class="box-title">Collection Report 
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
                  <div class="form-group col-md-6">
                    <label for="fromDate">Filter By Date</label>
                    <input type="radio" name="filterDate" ng-click="dateChecker()">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="fromDate">Filter By Time Period</label>
                    <input type="radio" name="filterDate" ng-click="timeChecker()">
                  </div>  
                  <div class="form-group col-md-4">
                    <label for="fromDate">From Date</label>
                    <input type="text" id="fromDate" class="form-control floating-label" placeholder="From Date" ng-model="fromDate" ng-disabled="datePeriod">
                  </div>

                  <div class="form-group col-md-4">
                    <label for="toDate">To Date</label>
                    <input type="text" id="toDate" class="form-control floating-label" placeholder="To Date" ng-model="toDate" ng-disabled="datePeriod">
                  </div>

                  <div class="form-group col-md-4">
                    <label for="toDate">Select Distributor</label>
                      <select class="form-control" ng-model="distributor">
                            <option value="" selected>Select Distributor</option>
                            <option ng-repeat="distributor in appdistributors" value="{{distributor.distributorId}}">{{distributor.distributorName}}</option>      
                      </select>
                  </div>
                </div>
                <div class="row col-md-12">
                  <div class="form-group col-md-4">
                    <label for="toDate">Select Sales User</label>
                    <select class="form-control" ng-model="user">
                            <option value="" selected>Select Sales Person</option>
                            <option ng-repeat="user in appusers" value="{{user.id}}">{{user.firstName}} {{user.lastName}}</option>      
                    </select>
                  </div>                    
                  <div class="form-group col-md-4">
                    <label for="toDate">Select Time Period</label>
                    <select class="form-control" ng-model="time" ng-disabled="timePeriod">
                            <option value="" selected>Select Time Period</option>
                            <option value="5" selected>Report By Current Week</option>
                            <option value="6" selected>Report By Current Month</option>
                            <option value="7" selected>Report By Half Yearly</option>
                            <option value="1" selected>Report By Quater 1</option>
                            <option value="2" selected>Report By Quater 2</option>
                            <option value="3" selected>Report By Quater 3</option>
                            <option value="4" selected>Report By Quater 4</option>
                            <option value="8" selected>Report By Anually</option>                                 
                    </select>
                  </div>
                  
                </div>

                <div class="row col-md-12">
                  <div class="form-group col-md-2 pull-right" style="margin-top:24px;">
                    <button type="button" ng-click="generateReports()" class="btn btn-block btn-primary form-control" title="Refresh Table"><i class="fa fa-refresh"> </i> Submit</button>
                  </div>
                </div>

              </div>
            </div>
                    
              </div>
              <!-- /.box-header -->

            </div>
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
                <div class="col-md-6"><h4><strong>Sales App Collection Report</strong></h4></div><div class="col-md-6" align="right"><h4><strong>{{reportDateInfo}}</strong></h4></div>
                <table class="table table-hover" id="tableToExport">
                  <thead>
                    <tr>
                      <th ng-click="sort('')">S No
                        <span class="glyphicon sort-icon" ng-show="sortKey=='ordSNo'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('collectionDate')">Collection Date
                        <span class="glyphicon sort-icon" ng-show="sortKey=='ordName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('firstName')">Sales Person
                        <span class="glyphicon sort-icon" ng-show="sortKey=='ordUserName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>                      
                      <th ng-click="sort('distributor')">Distributor
                        <span class="glyphicon sort-icon" ng-show="sortKey=='ordName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                      <th ng-click="sort('collectionAmount')">Collection Amount
                        <span class="glyphicon sort-icon" ng-show="sortKey=='ordStatus'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr dir-paginate="collection in collections|orderBy:sortKey:reverse|filter:search|itemsPerPage:10000000">
                      <td>{{$index + 1}}</td>
                      <td>{{collection.collectionDate}}</td>
                      <td>{{collection.firstName}} {{collection.lastName}}</td>
                      <td>{{collection.distributor}}</td>
                      <td>&#8377; {{collection.collectionAmount}}</td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td><h4><strong>Total Amount</strong></h4></td>
                      <td><h4><strong>&#8377; {{collectionTotalAmount}}</strong></h4></td>
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