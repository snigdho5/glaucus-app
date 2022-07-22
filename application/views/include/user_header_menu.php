<header class="main-header" style="box-shadow: 0 3px 3px -1px rgba(0,0,0,0.1);">
  <nav class="navbar navbar-static-top">
    <div class="container">
      <div class="navbar-header">
        <a href="<?php echo base_url();?>" class="navbar-brand"><b>Lnsel</b>Sales</a>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
          <i class="fa fa-bars"></i>
        </button>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <li <?php if(in_array($this->uri->segment(1), array('', 'user'))) { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>user">Dashboard</a></li>
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
              <span class="hidden-xs"><?php echo $this->session->userdata['logged_app_user']['userName']?></span>
            </a>
          </li>

          <li class="dropdown messages-menu" title="Logout">
          <!-- Menu toggle button -->
          <a href="<?php echo base_url();?>user/logout">
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