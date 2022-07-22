<link rel="stylesheet" href="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<script type="text/javascript" src="http://momentjs.com/downloads/moment-with-locales.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/materialTime/bootstrap-material-datetimepicker.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<link rel="stylesheet" media="screen" href="http://handsontable.github.io/ngHandsontable/node_modules/handsontable/dist/handsontable.full.min.css">
<script src="http://handsontable.github.io/ngHandsontable/node_modules/handsontable/dist/handsontable.full.min.js"></script>
<script src="http://handsontable.github.io/ngHandsontable/dist/ngHandsontable.js"></script>
<script src="http://handsontable.github.io/ngHandsontable/demo/js/services/dataFactory.js"></script>



<div class="wrapper" ng-controller="addMultipleCustomerCtrl as ctrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Customer
          <small>Management</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="<?php echo base_url();?>customermanagement">Customer Management</a></li>
          <li class="active">Add Multiple Customer</li>
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
            <h3 class="box-title">Add Multiple Customer</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
            <div class="box-body">
<div class="row col-md-12">
<!--
<hot-table settings="{colHeaders: colHeaders, contextMenu: ['row_above', 'row_below', 'remove_row'], afterChange: afterChange}"
           row-headers="false"
           min-spare-rows="minSpareRows"
           datarows="db.items"
           height="300"
           width="700">
    <hot-column data="id" title="'ID'"></hot-column>
    <hot-column data="name.first" title="'First Name'" type="grayedOut" read-only></hot-column>
    <hot-column data="name.last" title="'Last Name'" type="grayedOut" read-only></hot-column>
    <hot-column data="address" title="'Address'" width="150"></hot-column>
    <hot-column data="area" title="'Favorite food'" type="'autocomplete'">
        <hot-autocomplete datarows="area in areas"></hot-autocomplete>
    </hot-column>
    <hot-column data="price" title="'Price'" type="'numeric'" width="80" format="'$ 0,0.00'"></hot-column>
    <hot-column data="isActive" title="'Is active'" type="'checkbox'" width="65" checked-template="'Yes'" unchecked-template="'No'"></hot-column>
</hot-table>-->

<!--
<hot-table settings="{colHeaders: colHeaders, contextMenu: ['row_above', 'row_below', 'remove_row'], afterChange: afterChange}"
           row-headers="false"
           min-spare-rows="minSpareRows"
           datarows="db.items"
           height="300"
           width="700">
    <hot-column data="name.first" title="'First Name'" type="grayedOut" read-only></hot-column>
    <hot-column data="name.last" title="'Last Name'" type="grayedOut" read-only></hot-column>
    <hot-column data="gender.name" title="'Gender'" type="'autocomplete'">
        <hot-autocomplete datarows="gender.gndName for gender in genders"></hot-autocomplete>
    </hot-column>
    <hot-column data="address" title="'Address'" width="150"></hot-column>
    <hot-column data="product.description" title="'Favorite food'" type="'autocomplete'">
        <hot-autocomplete datarows="description in product.options"></hot-autocomplete>
    </hot-column>
    <hot-column data="price" title="'Price'" type="'numeric'" width="80" format="'$ 0,0.00'"></hot-column>
    <hot-column data="isActive" title="'Is active'" type="'checkbox'" width="65" checked-template="'Yes'" unchecked-template="'No'"></hot-column>
</hot-table>-->
<div style="padding: 15px;">
   <div id="example1" class="hot handsontable htColumnHeaders"></div> 
</div>



</div>



            </div>
            <!-- /.box-body -->

            <div class="box-footer">
            <button type="submit" class="btn btn-default pull-left" ng-click="cancelAddCustomer()">Cancel</button>
              <button type="submit" class="btn btn-primary pull-right" ng-click="submitAddMultipleCustomer()">Submit</button>
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