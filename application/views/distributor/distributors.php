<div class="wrapper" ng-controller="distributorCtrl">
<!--<div class="wrapper">-->
    <?php $this->load->view('include/header_menu'); ?>
    <!-- Full Width Column -->
    <div class="content-wrapper">
        <div class="container">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Distributors
                    <small>Management</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Distributors Management</li>
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
                                <div class="box-title pull-right" style="margin-left:5px; ">
                                    <a href="<?= base_url('distributors/addcsv'); ?>"><button type="button" class="btn btn-block btn-warning"><i class="fa fa-upload"> </i> Upload Distributors CSV</button></a>
                                </div>
                                <div class="box-title pull-right">
                                    <a href="<?= base_url('distributors/add');?>"><button type="button" class="btn btn-block btn-primary"><i class="fa fa-plus"> </i> Add Distributor</button></a>
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
                                                <!--
                  <th ng-click="sort('ausrId')">Id
                    <span class="glyphicon sort-icon" ng-show="sortKey=='ausrId'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                  </th>-->
                                                <th ng-click="sort('distributorId')">Distributor Id
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='distributorId'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('distributorName')">Distributor Name
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='distributorName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('districtName')">District
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='districtName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('distributorType')">Distributor Type
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='distributorType'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('distributorPhone')">Distributor Phone
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='distributorPhone'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
<!--
                                                <th ng-click="sort('ausrParentName')">Created By
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='ausrParentName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>-->
                                                <th ng-click="sort('status')">Status
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='status'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
<!--                                                <th></th>-->
                                                <th style="width: 180px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr dir-paginate="distributor in appdistributors|orderBy:sortKey:reverse|filter:search|itemsPerPage:10">
                                                <!--
                  <td>{{user.ausrId}}</td>-->
                                                <td>{{distributor.distributorId}}</td>
                                                <td>{{distributor.distributorName}}</td>
                                                <td>{{distributor.districtName}}</td>
                                                <!-- <td>{{distributor.distributorType}}</td> -->
                                                <td>
                                                    <span ng-if="distributor.distributorType === '1'">Distributor</span>
                                                    <span ng-if="distributor.distributorType === '2'">Retailer</span>
                                                </td>
                                                <td>{{distributor.distributorPhone}}</td>                  
                                                <td>
                                                    <span ng-if="distributor.status === '1'" ng-class='badgeClass(distributor.status)'>Active</span>
                                                    <span ng-if="distributor.status === '0'" ng-class='badgeClass(distributor.status)'>Inactive</span>
                                                </td>
                                                <td>
                                                    <a class="btn btn-social-icon btn-danger" ng-click="changeStatus(distributor)" title="Change Status">
                                                        <i class="fa fa-check-circle"></i>
                                                    </a>
                                                    <a class="btn btn-social-icon btn-danger" data-toggle="modal" data-target=".modal-user-details" ng-click="viewDistributorDetails(distributor)" title="View Distributor Details">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a class="btn btn-social-icon btn-danger" ng-click="goToEdit(distributor)" title="Edit">    <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a class="btn btn-social-icon btn-danger" ng-click="addsales(distributor)" title="Assign Sales Person">
                                                        <i class="fa fa-user-plus"></i>
                                                    </a>
<!--                                                    <a class="btn btn-social-icon btn-danger" ng-click="viewItemDetails(item)" data-toggle="modal" data-target=".modal-change-created" title="Change Created By"><i class="fa fa-cogs"></i></a>-->
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
                    <h4 class="modal-title">Distributor Details</h4>
                </div>
                <div class="modal-body">

                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3"><strong> Distributor Name  : </strong></div>
                                    <div class="col-md-9">{{activeDistributor.distributorName}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> District : </strong></div>
                                    <div class="col-md-9">{{activeDistributor.district}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> Distributor Type : </strong></div>
                                    <div class="col-md-9">{{activeDistributor.distributorType}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> Phone : </strong></div>
                                    <div class="col-md-9">{{activeDistributor.distributorPhone}}</div>
                                </div>
                                <div class="row" ng-if="activeDistributor.distributorEmail != null">
                                    <div class="col-md-3"><strong> Email : </strong></div>
                                    <div class="col-md-9">{{activeDistributor.distributorEmail}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> Status : </strong></div>
                                    <div class="col-md-9">{{activeDistributor.distributorStatus}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> Address : </strong></div>
                                    <div class="col-md-9">{{activeDistributor.distributorAddress}}</div>
                                </div>
                                <div class="row" ng-if="activeDistributor.distributorGstNo != '' && activeDistributor.distributorGstNo != null">
                                    <div class="col-md-3"><strong> GSTIN : </strong></div>
                                    <div class="col-md-9">{{activeDistributor.distributorGstNo}}</div>
                                </div>                                
                                <div class="row" ng-if="activeDistributor.distributorDob != ''">
                                    <div class="col-md-3"><strong> Date Of Birth : </strong></div>
                                    <div class="col-md-9">{{activeDistributor.distributorDob}}</div>
                                </div>
                                <div class="row" ng-if="activeDistributor.creditLimit != 0">
                                    <div class="col-md-3"><strong> Credit Limit : </strong></div>
                                    <div class="col-md-9">&#8377; {{activeDistributor.creditLimit}}</div>
                                </div>
                                <div class="row" ng-if="activeDistributor.distributorattachment != ''">
                                    <div class="col-md-2"><strong> KYC : </strong></div>
<!--
                                    <div class="col-md-9">                                        
                                        <a href="{{activeDistributor.distributorattachment}}" target="_blank">Donwload KYC</a>
                                    </div>
-->
                                    <div class="col-md-10">
                                        <iframe ng-src="{{activeDistributor.distributorattachment}}" style="width:450px; height:300px;" frameborder="0"></iframe>
                                    </div>
                                </div>
<!--
                                <div class="row">
                                    <div class="col-md-3"><strong> Created Date : </strong></div>
                                    <div class="col-md-9">{{activeDistributor.distributorCreated_at | date: 'yyyy-MM-dd'}}</div>
                                </div>
-->
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