<link rel="stylesheet" href="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.css" />
<script type="text/javascript" src="//momentjs.com/downloads/moment-with-locales.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<div class="wrapper" ng-controller="editDistributor">
<?php $this->load->view('include/header_menu'); ?>
        <!-- Full Width Column -->
        <div class="content-wrapper">
            <div class="container">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Distributors<small>Management</small></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?= base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?= base_url('distributors');?>"><i class="fa fa-share-alt"></i>Distributors Management</a></li>
                        <li class="active">Edit Distributor</li>
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
                                    <h3 class="box-title">Edit Distributor</h3>
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <form name="addForm" novalidate enctype="multipart/form-data">
                                    <div class="box-body">

                                        <div class="row col-md-12">
                                            <div class="form-group col-md-6">
                                                <label for="distributorName">Distributor Name : *</label>
                                                <input type="text" class="form-control" placeholder="Distributor Name" name="distributorName" ng-model="distributorName" required>
                                                <span style="color:red;" ng-show="addForm.distributorName.$dirty && addForm.distributorName.$invalid">
                                                <span ng-show="addForm.distributorName.$error.required">Distributor Name required</span>
                                                </span>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="district">Select District *</label>         
                                                <select class="form-control" ng-model="distributordistrict" required>
                                                    <option ng-repeat="district in appdistricts" value="{{district.districtId}}">{{district.districtName}}</option>
                                                </select>
                                                <span style="color:red;" ng-show="addForm.distributors.$dirty && addForm.distributors.$invalid">
                                                <span ng-show="addForm.distributors.$error.required">District required</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row col-md-12">
                                            <div class="form-group col-md-6">
                                                <label for="distributorType">Distributor Type : </label>
                                                <select class="form-control" ng-model="distributorType" required name="distributorType">
                                                    <option value="">Select Distributor Type</option>
                                                    <option value="1">Distributor</option>
                                                    <option value="2">Retailer</option>
                                                </select>
                                                <!-- <input type="text" class="form-control" placeholder="Distributor Name" name="distributorType" ng-model="distributorType"> -->
                                                <span style="color:red;" ng-show="addForm.distributorType.$dirty && addForm.distributorType.$invalid">
                                                <span ng-show="addForm.distributorType.$error.required">Distributor Type required</span>
                                                </span>
                                            </div>                                
                                            <div class="form-group col-md-6">
                                                <label for="distributorPhone">Distributor Phone : *</label>
                                                <input type="text" class="form-control" placeholder="Distributor Phone" name="distributorPhone" ng-model="distributorPhone" required>
                                                <span style="color:red;" ng-show="addForm.distributorPhone.$dirty && addForm.distributorPhone.$invalid">
                                                <span ng-show="addForm.distributorPhone.$error.required">Distributor Phone required</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row col-md-12">
                                            <div class="form-group col-md-6">
                                                <label for="distributorEmail">Distributor Email : </label>
                                                <input type="email" class="form-control" ng-model="distributorEmail" name="distributorEmail" placeholder="Email Id*">
                                                <span style="color:red;" ng-show="addForm.distributorEmail.$dirty && addForm.distributorEmail.$invalid">
                                                <span ng-show="addForm.distributorEmail.$error.required">Email is required</span>
                                                <span ng-show="addForm.distributorEmail.$error.email">Invalid email address</span>
                                                </span>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="distributorAddress">Distributor Address : *</label>
                                                <input type="text" class="form-control" placeholder="Distributor Address" name="distributorAddress" ng-model="distributorAddress" required>
                                                <span style="color:red;" ng-show="addForm.distributorAddress.$dirty && addForm.distributorAddress.$invalid">
                                                <span ng-show="addForm.distributorAddress.$error.required">Distributor Address required</span>
                                                </span>
                                            </div>                                            
                                        </div>
                                        <div class="row col-md-12">
                                            <div class="form-group col-md-6">
                                                <label for="distributorGstNo">Distributor GST No. : </label>
                                                <input type="text" class="form-control" placeholder="Distributor GST No." name="distributorGstNo" ng-model="distributorGstNo">
                                                <span style="color:red;" ng-show="addForm.distributorGstNo.$dirty && addForm.distributorGstNo.$invalid">
                                                <span ng-show="addForm.distributorGstNo.$error.required">Distributor GST required</span>
                                                </span>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="distributorDob">Distributor DOB : </label>
                                                <input type="text" id="dobDate" name="distributorGstNo" ng-model="distributorDob" class="form-control floating-label" placeholder="Distributor DOB">
                                                <span style="color:red;" ng-show="addForm.distributorDob.$dirty && addForm.distributorDob.$invalid">
                                                <span ng-show="addForm.distributorDob.$error.required">Distributor DOB</span>
                                                </span>
                                            </div>                                            
                                        </div>
                                        <div class="row col-md-12">
                                            <div class="form-group col-md-6">
                                                <label for="distributordiscount">Discount : </label>
                                                <input type="text" name="distributorcredit" ng-model="distributordiscount" class="form-control floating-label" placeholder="Discount"/>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="distributorcredit">Credit Limit : </label>
                                                <input type="text" name="distributorcredit" ng-model="distributorcredit" class="form-control floating-label" placeholder="Credit Limit" required>
                                                <span style="color:red;" ng-show="addForm.distributorcredit.$dirty && addForm.distributorcredit.$invalid">
                                                <span ng-show="addForm.distributorcredit.$error.required">Add Credit Limit</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row col-md-12">
                                            <div class="form-group col-md-6">
                                                    <div class="col-md-3" ng-if="distributorKyc">
                                                        <a href="<?= base_url('uploads/kyc/distributor/') ?>{{distributorKyc}}" target="_blank">Download KYC</a>
                                                    </div>
                                                    <div class="col-md-9">
                                                    <label for="userKyc">Upload KYC Document (File format PDF) *</label>
                                                    <input type="file" ngf-select ng-model="picFile" name="file"                                 accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf," ngf-max-size="2MB" 
                                                   ngf-model-invalid="errorFile"/>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-default pull-left" ng-click="cancelEditDistributors()">Cancel</button>
                                        <button type="submit" class="btn btn-primary pull-right" ng-disabled="addForm.$invalid" ng-click="updateDistributor(picFile)">Update</button>
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