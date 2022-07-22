<link rel="stylesheet" href="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.css" />
<script type="text/javascript" src="//momentjs.com/downloads/moment-with-locales.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.js"></script>
<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/angularjs-dropdown-multiselect/1.11.8/angularjs-dropdown-multiselect.min.js"></script>
<div class="wrapper" ng-controller="assigndistributorsTosales">
<?php $this->load->view('include/header_menu'); ?>
        <!-- Full Width Column -->
        <div class="content-wrapper">
            <div class="container">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Distributors & Sales<small>Management</small></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?= base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?= base_url('distributors');?>"><i class="fa fa-share-alt"></i>Distributors Management</a></li>
                        <li class="active">Distributors & Sales Assigned</li>
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
                                    <h3 class="box-title">Distributors & Sales Assigned</h3>
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <form name="addForm" novalidate enctype="multipart/form-data">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                            </div>
                                            <div class="col-md-6">
                                                <div
                                                    isteven-multi-select
                                                    input-model     =   "appsalesperson"
                                                    output-model    =   "addsalesperson"
                                                    button-label    =   "icon name"
                                                    item-label      =   "icon name maker"
                                                    tick-property   =   "ticked"
                                                >
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-default pull-left" ng-click="cancelEditDistributors()">Cancel</button>
                                        <button type="submit" class="btn btn-primary pull-right" ng-disabled="addForm.$invalid" ng-click="assigns2d()">Update</button>
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