<link rel="stylesheet" href="<?= base_url();?>assets/materialTime/bootstrap-material-datetimepicker.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<script type="text/javascript" src="//momentjs.com/downloads/moment-with-locales.min.js"></script>
<script type="text/javascript" src="<?= base_url();?>assets/materialTime/bootstrap-material-datetimepicker.js"></script>
<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

<div class="wrapper" ng-controller="addInvoiceCtrl">

<?php $this->load->view('include/header_menu'); ?>
        <!-- Full Width Column -->
        <div class="content-wrapper">
            <div class="container">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Add<small>Invoice</small></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?php echo base_url();?>rochakorders">Order Management</a></li>
                        <li class="active">Add Invoice</li>
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
                                    <h3 class="box-title">Add Invoice</h3>
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <form id="ivoadd" name="addForm" novalidate>
                                    <div class="box-body">

                                        <div class="row col-md-12">

                                            <div class="form-group col-md-4">
                                                <label for="invoiceDate">Invoice Date: *</label>
                                                <input id="ivdate" type="text" class="form-control" placeholder="Invoice Date" ng-model="invoiceDate" name="invoiceDate" >
                                                <span style="color:red;" ng-show="addForm.invoiceDate.$dirty && addForm.invoiceDate.$invalid">
                                                    <span ng-show="addForm.invoiceDate.$error.required">Invoice Date Required</span>
                                                </span>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="invoiceID">Invoice ID : *</label>
                                                <input type="text" class="form-control" placeholder="Invoice ID" name="invoiceID" ng-model="invoiceID" required/>
                                                <span style="color:red;" ng-show="addForm.invoiceID.$dirty && addForm.invoiceID.$invalid">
                                                    <span ng-show="addForm.invoiceID.$error.required">Invoice ID is required</span>
                                                </span>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="invoiceAmount">Invoice Amount : *</label>
                                                <input type="text" class="form-control" placeholder="Invoice Amount" name="invoiceAmount" ng-model="invoiceAmount" required/>
                                                <span style="color:red;" ng-show="addForm.invoiceAmount.$dirty && addForm.invoiceAmount.$invalid">
                                                    <span ng-show="addForm.invoiceAmount.$error.required">Invoice Amount is required</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-default pull-left" ng-click="cancelInvoiceAdd()">Cancel</button>
                                        <button type="submit" class="btn btn-primary pull-right" ng-disabled="addForm.$invalid" ng-click="addInvoice()">Add Invoice</button>
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