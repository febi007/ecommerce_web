<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="icon" href="<?=base_url().$site->icon?>" sizes="32x32">

    <title><?=$title." | ".$site->nama?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!--	<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">-->
<!--	<link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@600&family=Fredoka+One&display=swap" rel="stylesheet">-->

    <link href="https://fonts.googleapis.com/css?family=Stylish" rel="stylesheet">

    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?=base_url().'assets/'?>bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?=base_url().'assets/'?>plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=base_url().'assets/'?>dist/css/AdminLTE.min.css">
    <!-- Material Design -->
    <link rel="stylesheet" href="<?=base_url().'assets/'?>dist/css/bootstrap-material-design.min.css">
    <link rel="stylesheet" href="<?=base_url().'assets/'?>dist/css/ripples.min.css">
    <link rel="stylesheet" href="<?=base_url().'assets/'?>dist/css/MaterialAdminLTE.min.css">
    <link href="<?=base_url().'assets/'?>dist/css/icon.css" rel="stylesheet">
    <!-- MaterialAdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?=base_url().'assets/'?>dist/css/skins/all-md-skins.min.css">
    <!-- Pace style -->
    <link rel="stylesheet" href="<?=base_url().'assets/'?>plugins/pace/pace.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery 2.2.3 -->
    <script src="<?=base_url().'assets/'?>plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- jQuery-autocomplete-->
    <script src="<?=base_url().'assets/'?>plugins/jQuery-autocomplete/jquery.autocomplete.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="<?=base_url().'assets/'?>bootstrap/js/bootstrap.min.js"></script>
    <!-- Form Validation -->
    <script type="text/javascript" src="<?=base_url().'assets/'?>plugins/jquery-validation/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?=base_url().'assets/'?>plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- Numeric -->
    <script type="text/javascript" src="<?=base_url().'assets/'?>plugins/numeric/jquery.numeric.js"></script>
    <!-- Currency -->
    <script type="text/javascript" src="<?=base_url().'assets/'?>plugins/autonumeric/autoNumeric-1.9.41.js"></script>
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?=base_url().'assets/'?>plugins/daterangepicker/daterangepicker.css">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?=base_url().'assets/'?>plugins/colorpicker/bootstrap-colorpicker.min.css">
    <!-- CK Editor -->
    <!--<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>-->
    <script src="//cdn.ckeditor.com/4.9.2/full/ckeditor.js"></script>
    <!--<script src="https://nightly.ckeditor.com/18-06-28-06-04/full/ckeditor.js"></script>-->
    <!-- Clock Picker -->
    <link rel="stylesheet" href="<?=base_url().'assets/'?>plugins/clockpicker/clockpicker.css">
    <!-- ChartJS 1.0.1 -->
    <script src="<?=base_url().'assets/'?>plugins/chartjs/Chart.min.js"></script>
<!--    <script src="--><?php //echo base_url('assets/bootstrap/js/bootstrap.bundle.js');?><!--"></script>-->
    <script src="<?php echo base_url('assets/ckeditor/ckeditor.js');?>"></script>

    <?php
    if($this->session->userdata($this->site . 'isLogin')!=true) {
        $cookie = json_decode(get_cookie('idk_store'), true);
        if ($cookie['status'] == true) {
            $cek = $this->m_website->login($cookie['username']);
            $this->session->set_userdata($this->site . 'isLogin', TRUE);
            $this->session->set_userdata($this->site . 'user', $cek->user_id);
            $this->session->set_userdata($this->site . 'name', $cek->name);
            $this->session->set_userdata($this->site . 'start', time());
            $this->session->set_userdata($this->site . 'expired', $this->session->userdata($this->site . 'start') + (30 * 60) );
        } else {
            redirect(base_url().'site');
        }
    }
    ?>

    <style>
        /*body{zoom:85%}*/
        /**{font-family: 'Stylish', sans-serif!important;}*/
        html{font-family: 'Stylish', sans-serif!important;}
        body{font-family: 'Stylish', sans-serif!important;}
        @media only screen and (max-width: 500px) {
            .dropdown-position {
                position: relative
            }
        }

        .colorpicker-saturation {
            width: 200px;
            height: 200px;
        }

        .colorpicker-hue,
        .colorpicker-alpha {
            width: 25px;
            height: 200px;
        }

        .error {
            color: red;
            display: block;
        }

        .img_profile {
            max-height: 100px;
            max-width: 50px;
        }

        .img_preview {
            max-height: 150px;
            max-width: 100px;
        }

        .img_preview2 {
            max-height: 200px;
            max-width: 200px;
        }

        #img_preview {
            display: none;
        }

        .modal-full {
            width: 98%;
        }

        .modal { overflow: auto !important; }

        .topcorner{
            position:absolute;
            top:0;
            right:0;
            cursor: pointer;
        }

        .autocomplete-suggestions { border: 1px solid #999; background: #fff; cursor: default; overflow: auto; }
        .autocomplete-suggestion { padding: 10px 5px; font-size: 1.2em; white-space: nowrap; overflow: hidden; }
        .autocomplete-selected { background: #f0f0f0; }
        .autocomplete-suggestions strong { font-weight: normal; color: #3399ff; }

        @media print {
            #Header, #Footer { display: none !important; }
        }
        th,td {
            vertical-align: middle; text-align: center; white-space: nowrap;
        }
        ul.tab-buttons {
            margin-bottom: 30px;
            margin-top: 20px;
            padding: 0;
        }
        /* For mobile phones: */
        ul.tab-buttons li {
            background: #ff2b42 none repeat scroll 0 0;
            box-shadow: 0 11px 10px 0 rgba(0, 0, 0, 0.1);
            display: inline-block;
            height: 130px;
            transition: all 0.4s ease 0s;
            width: 100%;
        }
        @media only screen and (min-width: 600px) {
            /* For tablets: */
            ul.tab-buttons li {
                background: #ff2b42 none repeat scroll 0 0;
                box-shadow: 0 11px 10px 0 rgba(0, 0, 0, 0.1);
                display: inline-block;
                height: 130px;
                transition: all 0.4s ease 0s;
                width: 49.6%;
            }
        }
        @media only screen and (min-width: 768px) {
            /* For desktop: */
            ul.tab-buttons li {
                background: #ff2b42 none repeat scroll 0 0;
                box-shadow: 0 11px 10px 0 rgba(0, 0, 0, 0.1);
                display: inline-block;
                height: 130px;
                transition: all 0.4s ease 0s;
                width: 16.35%;
            }
        }

        .font-status {
            font-weight: bold;
            font-size: 18pt;
        }

        ul.tab-buttons li.selected {
            background: #ff2b42 none repeat scroll 0 0;
            transition: all 0.4s ease 0s;
        }
        ul.tab-buttons li.selected:hover { background: #FD4559 }
        /*END TAB MENU ONE*/
        ul.tab-buttons li.selected2 {
            background: #1cbac8;
            -webkit-transition: all 0.4s ease 0s;
            transition: all 0.4s ease 0s;
        }
        ul.tab-buttons li.selected2:hover { background: #2AC7D5 }
        /*END TAB MENU TWO*/
        ul.tab-buttons li.selected3 {
            background: #00cccc;
            -webkit-transition: all 0.4s ease 0s;
            transition: all 0.4s ease 0s;
        }
        ul.tab-buttons li.selected3:hover { background: #00E0E0 }
        /*END TAB MENU THREE*/
        ul.tab-buttons li.selected4 {
            background: #21bb9d;
            -webkit-transition: all 0.4s ease 0s;
            transition: all 0.4s ease 0s;
        }
        ul.tab-buttons li.selected4:hover { background: #30CCAE }
        /*END TAB MENU FOUR*/
        ul.tab-buttons li.selected5 {
            background: #09afdf;
            -webkit-transition: all 0.4s ease 0s;
            transition: all 0.4s ease 0s;
        }
        ul.tab-buttons li.selected5:hover { background: #20C6F6 }
        /*END TAB MENU FIVE*/
        ul.tab-buttons li.selected6 {
            background: #F7AD17 none repeat scroll 0 0;
            margin-right: 0;
            transition: all 0.4s ease 0s;
        }
        ul.tab-buttons li.selected6:hover { background: #FFBC34 }
        /*END TAB MENU SIX*/
        /*END TAB MENU*/
        /*START TAB CONTAINER*/
        .tab-container {
            background: #fff none repeat scroll 0 0;
            border: 1px solid #e8e8e9;
            box-shadow: 0 11px 10px 0 rgba(0, 0, 0, 0.1);
            height: 100%;
            margin-bottom: 30px;
            padding: 20px;
        }
        .tab-container > div { display: none }
        .tab-buttons li span {
            display: block;
            color: #fff;
            font-family: "Berkshire Swash",sans-serif;
        }
        .tab-buttons li a i {
            color: #fff;
            display: block;
            font-size: 36px;
            margin: 30px auto 5px;
        }
        .tab-buttons li a {
            display: block;
            font-size: 16px;
            text-decoration: none;
            text-align: center;
        }
        .tab-buttons li a:hover { color: #fff }

        /*Scrollbar*/
        .scrollbar
        {
            width: 100%;
            height: 100%;
            overflow-y: scroll;
            overflow-x: hidden;
        }
        .scrollbar::-webkit-scrollbar-track
        {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            border-radius: 0px;
            background-color: #F5F5F5;
        }

        .scrollbar::-webkit-scrollbar
        {
            width: 0px;
            background-color: #F5F5F5;
        }

        .scrollbar::-webkit-scrollbar-thumb
        {
            border-radius: 0px;
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
            background-color: rgba(0, 151, 167, 1);
        }

        /*Loading*/
        .first-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1050;
            background: rgba(168, 168, 168, .5)
        }
        .first-loader img {
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px
        }
    </style>
</head>
