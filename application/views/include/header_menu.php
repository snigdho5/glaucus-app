<header class="main-header" style="box-shadow: 0 3px 3px -1px rgba(0,0,0,0.1);">
  <nav class="navbar navbar-static-top" style="background-color:#6fcaff; color:#000">
    <div class="container">
      <div class="navbar-header">
        <a href="<?php echo base_url();?>" class="navbar-brand"><b>Glaucus </b>SalesForce App</a>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
          <i class="fa fa-bars"></i>
        </button>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <li <?php if(in_array($this->uri->segment(1), array('', 'dashboard'))) { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
          <li class="dropdown <?php if(in_array($this->uri->segment(1), array('customersreport','meetingreport','orderreport', 'stockreport','loginreport', 'tripreport', 'expensereport', 'routehistory', 'performance'))) echo 'active'; ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <!-- <li <?php if($this->uri->segment(1) == 'customersreport') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>customersreport"><i class="fa fa-file-text"></i> Customer Report</a></li>
              <li <?php if($this->uri->segment(1) == 'meetingreport') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>meetingreport"><i class="fa fa-file-text"></i> Meeting Report</a></li> -->
              <li <?php if($this->uri->segment(1) == 'orderreport') { ?> class="active" <?php } ?>><a href="<?= base_url(); ?>orderreport"><i class="fa fa-file-text"></i> Order Report</a></li>
              <li <?php if($this->uri->segment(1) == 'stockreport') { ?> class="active" <?php } ?>><a href="<?= base_url(); ?>stockreport"><i class="fa fa-file-text"></i> Stock Report</a></li>
              <li <?php if($this->uri->segment(1) == 'projectionreport') { ?> class="active" <?php } ?>><a href="<?= base_url(); ?>projectionreport"><i class="fa fa-file-text"></i> Projection Report</a></li>
              <li <?php if($this->uri->segment(1) == 'collectionreport') { ?> class="active" <?php } ?>><a href="<?= base_url(); ?>collectionreport"><i class="fa fa-file-text"></i> Collection Report</a></li>
              <li <?php if($this->uri->segment(1) == 'creditreport') { ?> class="active" <?php } ?>><a href="<?= base_url(); ?>creditreport"><i class="fa fa-file-text"></i> Credit Report</a></li>
              <li <?php if($this->uri->segment(1) == 'loginreport') { ?> class="active" <?php } ?>><a href="<?= base_url(); ?>loginreport"><i class="fa fa-file-text"></i> Login Report</a></li>
              <!-- <li <?php if($this->uri->segment(1) == 'tripreport') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>tripreport"><i class="fa fa-file-text"></i> Trip Report</a></li> -->
              <li <?php if($this->uri->segment(1) == 'expensereport') { ?> class="active" <?php } ?>><a href="<?= base_url(); ?>expensereport"><i class="fa fa-file-text"></i> Expense Report</a></li>
              <li <?php if($this->uri->segment(1) == 'routehistory' && $this->uri->segment(2) != 'direction') { ?> class="active" <?php } ?>><a href="<?= base_url(); ?>routehistory"><i class="fa fa-road"></i> Route History</a></li>
              <li <?php if($this->uri->segment(2) == 'direction') { ?> class="active" <?php } ?>><a href="<?= base_url('routehistory/direction'); ?>"><i class="fa fa-road"></i> Route Footmap History</a></li>
              <li <?php if($this->uri->segment(1) == 'performance') { ?> class="active" <?php } ?>><a href="<?= base_url(); ?>performance"><i class="fa fa-line-chart"></i> Performance Analysis</a></li>
            </ul>
          </li>
          <!--<li <?php if($this->uri->segment(1) == 'customerinfo') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>customerinfo">All Customer Info</a></li>-->
          <li class="dropdown <?php if(in_array($this->uri->segment(1), array('usermanagement', 'customermanagement', 'meetingmanagement', 'meetingreassignment', 'expensemanagement', 'leavemanagement', 'ordermanagement', 'itemmanagement'))) echo 'active'; ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Management <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li <?php if($this->uri->segment(1) == 'usermanagement') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>usermanagement"><i class="fa fa-user"></i> User Managment</a></li>
              <!--<li <?php if($this->uri->segment(1) == 'customermanagement') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>customermanagement"><i class="fa fa-users"></i> Customer Management</a></li>
              <li <?php if($this->uri->segment(1) == 'meetingmanagement') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>meetingmanagement"><i class="fa fa-calendar-check-o"></i> Meeting Management</a></li>
              <li <?php if($this->uri->segment(1) == 'meetingreassignment') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>meetingreassignment"><i class="fa fa-calendar-plus-o"></i> Meeting Re-Assignment</a></li>-->
              <li <?php if($this->uri->segment(1) == 'expensemanagement') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>expensemanagement"><i class="fa fa-money"></i> Expense Management</a></li>
              <li <?php if($this->uri->segment(1) == 'leavemanagement') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>leavemanagement"><i class="fa fa-calendar-times-o"></i> Leave Request Management</a></li>
              <!-- <li <?php if($this->uri->segment(1) == 'ordermanagement') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>ordermanagement"><i class="fa fa-list-alt"></i> Order Management</a></li> -->
              <li <?php if($this->uri->segment(1) == 'rochakorders') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>rochakorders"><i class="fa fa-list-alt"></i> Order Management</a></li>
               <li <?php if($this->uri->segment(1) == 'itemmanagement') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>itemmanagement"><i class="fa fa-product-hunt"></i> Item Management</a></li>
	             <li <?php if($this->uri->segment(1) == 'distributors') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>distributors"><i class="fa fa-share-alt"></i> Distributor Management</a></li>
	             <li <?php if($this->uri->segment(1) == 'creditHistory') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>creditHistory"><i class="fa fa-credit-card"></i> Credit History</a></li>
	             <li <?php if($this->uri->segment(1) == 'projectionHistory') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>projectionHistory"><i class="fa fa-money"></i> Payment Projection</a></li>
            </ul>
          </li>
          <li class="dropdown <?php if(in_array($this->uri->segment(1), array('adminmanagement', 'unitmanagement', 'venuemanagement', 'setting'))) echo 'active'; ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Administration <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li <?php if($this->uri->segment(1) == 'adminmanagement') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>adminmanagement"><i class="fa fa-user-secret"></i> Admin Management</a></li>
              <!--<li <?php if($this->uri->segment(1) == 'unitmanagement') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>unitmanagement"><i class="fa fa-industry"></i> Unit Management</a></li>
              <li <?php if($this->uri->segment(1) == 'venuemanagement') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>venuemanagement"><i class="fa fa-building"></i> Service Management</a></li>-->
              <li <?php if($this->uri->segment(1) == 'setting') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>setting"><i class="fa fa-gears"></i> Setting</a></li>
            </ul>
          </li>
        </ul>
      </div>
      <!-- /.navbar-collapse -->
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#">
              <!-- The user image in the navbar-->
              <img src="<?php echo base_url();?>assets/dist/img/usericon.png" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?php echo $this->session->userdata['logged_web_user']['userName']?></span>
            </a>
          </li>

          <li class="dropdown messages-menu" title="Logout">
          <!-- Menu toggle button -->
          <a href="<?php echo base_url();?>dashboard/logout">
            <i class="glyphicon glyphicon-log-out"></i>
          </a>
        </li>
        </ul>
      </div>
      <!-- /.navbar-custom-menu -->
    </div>
    <!-- /.container-fluid -->
  </nav>
</header>
