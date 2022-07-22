<style type="text/css">
  .highcharts-credits{
    display: none;
  }
</style>

<div class="wrapper" ng-controller="performanceCtrl">

<?php $this->load->view('include/header_menu'); ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Performance
          <small>Analysis</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Performance Analysis</li>
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
                <h3 class="box-title">Performance
                  <small>Filter</small>
                </h3>
                <!-- tools box -->
                <div class="pull-right box-tools">
                  <button type="button" class="btn btn-primary btn-sm" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                    <i class="fa fa-minus"></i></button>
                </div>
                <!-- /. tools -->
              </div>
              <!-- /.box-header -->
              <div class="box-body pad" style="">

                <div class="row col-md-12">

                  <div class="form-group col-md-3">
                    <label for="collectionAmount">Collection Amount Target</label>
                    <input type="number" class="form-control" ng-model="collectionAmount" name="collectionAmount" placeholder="Collection Amount">
                  </div>

                  <div class="form-group col-md-3">
                    <label for="orderNo">Order/Month Target</label>
                    <input type="number" class="form-control" ng-model="orderNo" name="orderNo" placeholder="Order Number">
                  </div>

                  <div class="form-group col-md-3">
                    <label for="visitNo">Order Amount/Month Target</label>
                    <input type="number" class="form-control" ng-model="orderAmount" name="orderAmount" placeholder="Order Amount">
                  </div>

                  <div class="form-group col-md-3 pull-right">
                    <label for="toDate">Select Year</label>
                    <select class="form-control" ng-model="selectedYear" ng-options="year.yearName for year in years"></select>
                  </div>

                </div>
                <div class="row col-md-12">
                  <div class="form-group col-md-3">
                    <label for="visitNo">Projection Amount Target</label>
                    <input type="number" class="form-control" ng-model="projectionAmount" name="projectionAmount" placeholder="Projection Amount">
                  </div>

                </div>

                <div class="row col-md-12">

                  <div class="form-group col-md-10">
                    <label for="venUnitId">Select Single/Multiple User*</label>
                    <div
                        isteven-multi-select
                        input-model="inputUsers"
                        output-model="outputUsers"
                        button-label="icon name"
                        item-label="icon name maker"
                        tick-property="ticked"
                    >
                    </div>
                  </div>

                  <div class="form-group col-md-2 pull-right" style="margin-top:24px;">
                    <button type="button" ng-click="getPerformanceData()" class="btn btn-block btn-primary form-control" title="Refresh Table"><i class="fa fa-refresh"> </i> Submit</button>
                  </div>
                  
                </div>


              </div>
            </div>            

            <div class="box box-primary">
              <div class="box-body" style="padding: 0px;">

                <div id="order_container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                
              </div>
            </div>

            <div class="box box-primary">
              <div class="box-body" style="padding: 0px;">

                <div id="order_amount_container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                
              </div>
            </div>

            <div class="box box-primary">
              <div class="box-body" style="padding: 0px;">

                <div id="projection_amount_container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                
              </div>
            </div>

            <div class="box box-primary">
              <div class="box-body" style="padding: 0px;">

                <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                
              </div>
            </div>

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