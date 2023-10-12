<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$title?></title>
    <script src="<?=base_url().'assets/'?>plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?=base_url().'assets/'?>plugins/sweetalert2/sweetalert2.all.js"></script>
    <style>
        .first-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 100;
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
<body>
<script>
    $(document).ready(function () {
        $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
        if ('<?=$status?>' === 'success') {
            setTimeout(function () {
                $('.first-loader').remove();
                swal({
                    type: 'success',
                    title: '<?=$message?>',
                    closeOnClickOutside: false
                }).then(function (value) {
                    window.location = '<?=$redirect?>';
                })
            }, 3000);
        } else {
            window.close();
        }
    })
</script>
</body>
</html>