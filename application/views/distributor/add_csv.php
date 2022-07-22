<!--<div class="wrapper" ng-controller="itemCtrl">-->
<div class="wrapper">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Distributor
          <small>Management</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="<?= base_url('distributors') ?>"><i class="fa fa-product-hunt"></i> Distributors List</a></li>
          <li class="active">Import Distributor CSV</li>
        </ol>
      </section>
      <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Import Distributor CSV</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
<!--                    <form name="addForm" novalidate>-->
                    <?php if ($this->session->flashdata('error')) { ?>
                        <h6 style="text-align:center; color:red;">
                            <?php echo $this->session->flashdata('error'); ?>
                        </h6>
                    <?php } ?>
                    <?php if ($this->session->flashdata('success')) { ?>
                        <h6 style="text-align:center; color:green;">
                            <?php echo $this->session->flashdata('success'); ?>
                        </h6>
                    <?php } ?>
                    <form method="post" action="<?= base_url('distributors/add_csv') ?>" enctype="multipart/form-data">
                        <div class="box-body">
                            
                            <div class="row col-md-12">
                                
                                <div class="form-group col-md-6 col-md-offset-3">
                                    <label for="userPackageName">Upload Distributor CSV File : *</label>
                                    <input type="file" name="csvfile" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                                </div>
                                
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
<!--                            <button type="submit" class="btn btn-default pull-left">Cancel</button>-->
                            <button type="submit" class="btn btn-danger pull-right">Upload Distributor CSV</button>
                        </div>
                    </form>
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
</div>