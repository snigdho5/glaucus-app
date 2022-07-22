<div class="wrapper" ng-controller="itemCtrl">
<!--<div class="wrapper">-->

    <?php $this->load->view('include/header_menu'); ?>
    <!-- Full Width Column -->
    <div class="content-wrapper">
        <div class="container">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Item
                    <small>Management</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Item Management</li>
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
                                    <a href="<?php echo base_url();?>itemmanagement/addcsv"><button type="button" class="btn btn-block btn-warning"><i class="fa fa-upload"> </i> Upload Items CSV</button></a>
                                </div>
                                <div class="box-title pull-right">
                                    <a href="<?php echo base_url();?>itemmanagement/add"><button type="button" class="btn btn-block btn-primary"><i class="fa fa-plus"> </i> Add Item</button></a>
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
                                                <th ng-click="sort('pckg_id')">Item Id
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='ausrFirstName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('item_name')">Item Name
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='ausrLastName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('package_name')">Item Package
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='ausrUserEmail'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('package_size_name')">Item Size
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='ausrContactNo'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('package_item_price')">Item Price
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='ausrUserName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
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
                                            <tr dir-paginate="item in appItems|orderBy:sortKey:reverse|filter:search|itemsPerPage:10">
                                                <!--
                  <td>{{user.ausrId}}</td>-->
                                                <td>{{item.pckg_id}}</td>
                                                <td>{{item.item_name}}</td>
                                                <td>{{item.package_name}}</td>
                                                <td>{{item.package_size_name}}</td>
                                                <td>&#8377; {{item.package_item_price}}</td>
                                                <td>
                                                    <span ng-if="item.status === '1'" ng-class='badgeClass(item.status)'>Available</span>
                                                    <span ng-if="item.status === '0'" ng-class='badgeClass(item.status)'>Out Of Stock</span>
                                                </td>
                                                <td>
                                                    <a class="btn btn-social-icon btn-danger" ng-click="changeStatus(item)" title="Change Status"><i class="fa fa-check-circle"></i></a>
                                                    <a class="btn btn-social-icon btn-danger" data-toggle="modal" data-target=".modal-user-details" ng-click="viewItemDetails(item)" title="View User Details"><i class="fa fa-eye"></i></a>
                                                    <a class="btn btn-social-icon btn-danger" ng-click="goToEdit(item)" title="Edit"><i class="fa fa-edit"></i></a>
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
                    <h4 class="modal-title">Item Details</h4>
                </div>
                <div class="modal-body">

                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3"><strong> Item Name  : </strong></div>
                                    <div class="col-md-9">{{activeItem.itemName}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> Package Type : </strong></div>
                                    <div class="col-md-9">{{activeItem.itemPackage}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> Package Size : </strong></div>
                                    <div class="col-md-9">{{activeItem.itemSize}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> Package Price : </strong></div>
                                    <div class="col-md-9">&#8377; {{activeItem.itemPrice}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> Status : </strong></div>
                                    <div class="col-md-9">{{activeItem.itemStatus}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong> Created Date : </strong></div>
                                    <div class="col-md-9">{{activeItem.itemCreatedDate | date: 'yyyy-MM-dd'}}</div>
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