<div class="wrapper" ng-controller="creditCtrl">
<!--<div class="wrapper">-->
    <?php $this->load->view('include/header_menu'); ?>
    <!-- Full Width Column -->
    <div class="content-wrapper">
        <div class="container">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Credit
                    <small>History Logs</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Credit Logs</li>
                </ol>
            </section>
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
                            <div>
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover" id="tableToExport">
                                        <thead>
                                            <tr>
                                                <th ng-click="sort()">Id
                                                    <span class="glyphicon sort-icon" ng-show="sortKey==''" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('distributor')">Distributor Name
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='distributor'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('firstName')">Sales Person
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='firstName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('settled')">Settled Amount
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='settled'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('outstanding')">Outstanding Amount
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='outstanding'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('creditDate')">Date Of Credit
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='creditDate'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('outstanding')">Status
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='outstanding'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
<!--                                                <th></th>-->
                                                <th style="width: 180px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr dir-paginate="credit in appCredits|orderBy:sortKey:reverse|filter:search|itemsPerPage:10">
                                                <td>{{$index + 1}}</td>
                                                <td>{{credit.distributor}}</td>
                                                <td>{{credit.firstName}} {{credit.lastName}}</td>
                                                <td>{{credit.settled}}</td>
                                                <td>{{credit.outstanding}}</td>
                                                <td>{{credit.creditDate}}</td>
                                                <td>
                                                    <span ng-if="credit.outstanding == '0'" class="label label-success">Credit Settled</span>
                                                    <span class="label label-danger" ng-if="credit.outstanding != '0'">Credit OutStanding</span>
                                                </td>
                                                <td>
                                                    <a class="btn btn-social-icon btn-danger" data-toggle="modal" data-target=".modal-user-details" ng-click="viewCreditDetails(credit)" title="View Credit Details"><i class="fa fa-eye"></i></a>
<!--                                                    <a class="btn btn-social-icon btn-danger" ng-click="goToEdit(credit)" title="Edit"><i class="fa fa-edit"></i></a>-->
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

                            <dir-pagination-controls max-size="5" direction-links="true" boundary-links="true">
                            </dir-pagination-controls>
                        </div>
                        <!-- /.box -->
                    </div>
                </div>
            </section>
        </div>
        <!-- /.container -->
    </div>
    <!-- /.content-wrapper -->
    <?php $this->load->view('include/footer'); ?>

    <!-- ./wrapper -->
    <div class="modal modal-user-details">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Credit Details</h4>
                </div>
                <div class="modal-body">

                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3"><strong> Credit ID  : </strong></div>
                                    <div class="col-md-9">{{activeCredit.creditId}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> Order ID : </strong></div>
                                    <div class="col-md-9">{{activeCredit.orderId}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> Distributor : </strong></div>
                                    <div class="col-md-9">{{activeCredit.distributor}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> Sales Person : </strong></div>
                                    <div class="col-md-9">{{activeCredit.salesperson}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> Credit Amount : </strong></div>
                                    <div class="col-md-9">&#8377;{{activeCredit.credit}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> Credit Settled : </strong></div>
                                    <div class="col-md-9">&#8377;{{activeCredit.settled}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> OutStanding : </strong></div>
                                    <div class="col-md-9">&#8377;{{activeCredit.outstanding}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> Credit Date : </strong></div>
                                    <div class="col-md-9">{{activeCredit.creditDate}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> OutStanding : </strong></div>
                                    <div class="col-md-9">{{activeCredit.lastPayment}}</div>
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