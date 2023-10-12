<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="icon" href="<?=base_url().$site->icon?>" sizes="40x40">

    <title><?=$title." | ".$site->nama?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?=base_url().'assets/'?>bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=base_url().'assets/'?>dist/css/AdminLTE.min.css">
    <!-- Material Design -->
    <link rel="stylesheet" href="<?=base_url().'assets/'?>dist/css/bootstrap-material-design.min.css">
    <link rel="stylesheet" href="<?=base_url().'assets/'?>dist/css/ripples.min.css">
    <link rel="stylesheet" href="<?=base_url().'assets/'?>dist/css/MaterialAdminLTE.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <?php
    if($this->session->userdata($this->site . 'isLogin')==true) {
        redirect('site/dashboard');
    } else {
        $cookie = json_decode(get_cookie('idk_store'), true);
        if ($cookie['status'] == true) {
            $cek = $this->m_website->login($cookie['username']);
            $this->session->set_userdata($this->site . 'isLogin', TRUE);
            $this->session->set_userdata($this->site . 'user', $cek->user_id);
            $this->session->set_userdata($this->site . 'name', $cek->name);
            $this->session->set_userdata($this->site . 'start', time());
            $this->session->set_userdata($this->site . 'expired', $this->session->userdata($this->site . 'start') + (30 * 60) );
        }
    }
    ?>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <?=$site->nama?>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <?=form_open('site/log_in')?>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="username" placeholder="Username" autofocus autocomplete="off">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-7">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember" value="1"> Remember Me
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-5">
                    <button type="submit" name="login" class="btn btn-primary btn-raised btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        <?=form_close()?>
        <!-- /.social-auth-links -->
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="<?=base_url().'assets/'?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?=base_url().'assets/'?>bootstrap/js/bootstrap.min.js"></script>
<!-- Material Design -->
<script src="<?=base_url().'assets/'?>dist/js/material.min.js"></script>
<script src="<?=base_url().'assets/'?>dist/js/ripples.min.js"></script>
<script>
    $.material.init();
</script>
</body>
</html>