<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Label</title>
    <style>
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font: 12pt "Tahoma";
        }
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm;
            margin: 10mm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .subpage {
            height: 276mm;
        }

        @page {
            size: A4;
            margin: 0;
        }
        @media print {
            html, body {
                width: 210mm;
                height: 297mm;
            }
            #Header, #Footer {
                display: none !important;
            }
            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }

        td {
            height: 6.8cm;
            border: 1px red solid;
        }

        .row {
            height: 100%;
        }

        .row:before, .row:after {
            display: table;
            content: " ";
        }

        .row:after {
            clear: both;
        }

        .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
            float: left;
        }
        .col-12 {
            width: 100%;
        }
        .col-11 {
            width: 91.66666667%;
        }
        .col-10 {
            width: 83.33333333%;
        }
        .col-9 {
            width: 75%;
        }
        .col-8 {
            width: 66.66666667%;
        }
        .col-7 {
            width: 58.33333333%;
        }
        .col-6 {
            width: 50%;
        }
        .col-5 {
            width: 41.66666667%;
        }
        .col-4 {
            width: 33.33333333%;
        }
        .col-3 {
            width: 25%;
        }
        .col-2 {
            width: 16.66666667%;
        }
        .col-1 {
            width: 8.33333333%;
        }
    </style>
</head>
<body>
<div class="book">
    <div class="page">
        <div class="subpage">
            <table width="100%">
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-4">a</div>
                            <div class="col-4">a</div>
                            <div class="col-4">a</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="page">
        <div class="subpage">Page 2/2</div>
    </div>
</div>
</body>
</html>