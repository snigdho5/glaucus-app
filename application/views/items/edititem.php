<div class="wrapper" ng-controller="editItemCtrl">

<?php $this->load->view('include/header_menu'); ?>
        <!-- Full Width Column -->
        <div class="content-wrapper">
            <div class="container">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Item<small>Management</small></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?php echo base_url();?>itemmanagement">Item Management</a></li>
                        <li class="active">Edit Item</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">
                            <!-- general form elements -->
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Edit Item</h3>
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <form name="addForm" novalidate>
                                    <div class="box-body">

                                        <div class="row col-md-12">
                                            <div class="form-group col-md-6">
                                                <label for="itemPackageMode">Select Package *</label>
                                                <select class="form-control" ng-model="itemPackageMode" required >
                                                    <option ng-repeat="packageMode in packageModes" value="{{packageMode.pckg_mode_id}}">{{packageMode.package_name}}</option>
                                                </select>                                               
                                                
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="itemPackageSize">Select Package Size *</label>
                                                <select class="form-control" ng-model="itemPackageSize" required >
                                                    <option ng-repeat="packageSize in packageSizes" value="{{packageSize.pckg_size_id}}">{{packageSize.package_size_name}}</option>
                                                </select>
                                            </div>

                                        </div>

                                        <div class="row col-md-12">

                                            <div class="form-group col-md-6">
                                                <label for="itemName">Item Name : *</label>
                                                <input type="text" class="form-control" placeholder="Item Name" ng-model="itemName" name="itemName" required>
                                                <span style="color:red;" ng-show="addForm.itemName.$dirty && addForm.itemName.$invalid">
                                                <span ng-show="addForm.itemName.$error.required">Item Name is required</span>
                                                </span>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="itemPrice">Distributor Item Price : *</label>
                                                <input type="text" class="form-control" placeholder="Distributor Item Price" name="itemPrice" ng-model="itemPrice" required>
                                                <span style="color:red;" ng-show="addForm.itemPrice.$dirty && addForm.itemPrice.$invalid">
                                                <span ng-show="addForm.itemPrice.$error.required">Distributor Item Price is required</span>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="row col-md-12">
                                            <div class="form-group col-md-6">
                                                <label for="itemRetailerPrice">Retail Item Price : *</label>
                                                <input type="text" class="form-control" placeholder="Retail Item Price" name="itemRetailerPrice" ng-model="itemRetailerPrice" required>
                                                <span style="color:red;" ng-show="addForm.itemRetailerPrice.$dirty && addForm.retailitemPrice.$invalid">
                                                <span ng-show="addForm.itemRetailerPrice.$error.required">Retail Item Price is required</span>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.box-body -->

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-default pull-left" ng-click="cancelEditItem()">Cancel</button>
                                        <button type="submit" class="btn btn-primary pull-right" ng-disabled="addForm.$invalid" ng-click="updateItem()">Update</button>
                                    </div>
                                </form>
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