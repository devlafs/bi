<?php

use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
        <title><?= Html::encode($this->title) ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta name="viewport" content="width=device-width">
                <!--[if !mso]><!--><meta http-equiv="X-UA-Compatible" content="IE=edge"><!--<![endif]-->
                    <title></title>
                    <!--[if !mso]><!-- -->
                    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
                        <!--<![endif]-->

                        <style type="text/css" id="media-query">
                            body {
                                margin: 0;
                                padding: 0; }

                            table, tr, td {
                                vertical-align: top;
                                border-collapse: collapse; }

                            .ie-browser table, .mso-container table {
                                table-layout: fixed; }

                            * {
                                line-height: inherit; }

                            a[x-apple-data-detectors=true] {
                                color: inherit !important;
                                text-decoration: none !important; }

                            [owa] .img-container div, [owa] .img-container button {
                                display: block !important; }

                            [owa] .fullwidth button {
                                width: 100% !important; }

                            [owa] .block-grid .col {
                                display: table-cell;
                                float: none !important;
                                vertical-align: top; }

                            .ie-browser .num12, .ie-browser .block-grid, [owa] .num12, [owa] .block-grid {
                                width: 600px !important; }

                            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
                                line-height: 100%; }

                            .ie-browser .mixed-two-up .num4, [owa] .mixed-two-up .num4 {
                                width: 200px !important; }

                            .ie-browser .mixed-two-up .num8, [owa] .mixed-two-up .num8 {
                                width: 400px !important; }

                            .ie-browser .block-grid.two-up .col, [owa] .block-grid.two-up .col {
                                width: 300px !important; }

                            .ie-browser .block-grid.three-up .col, [owa] .block-grid.three-up .col {
                                width: 200px !important; }

                            .ie-browser .block-grid.four-up .col, [owa] .block-grid.four-up .col {
                                width: 150px !important; }

                            .ie-browser .block-grid.five-up .col, [owa] .block-grid.five-up .col {
                                width: 120px !important; }

                            .ie-browser .block-grid.six-up .col, [owa] .block-grid.six-up .col {
                                width: 100px !important; }

                            .ie-browser .block-grid.seven-up .col, [owa] .block-grid.seven-up .col {
                                width: 85px !important; }

                            .ie-browser .block-grid.eight-up .col, [owa] .block-grid.eight-up .col {
                                width: 75px !important; }

                            .ie-browser .block-grid.nine-up .col, [owa] .block-grid.nine-up .col {
                                width: 66px !important; }

                            .ie-browser .block-grid.ten-up .col, [owa] .block-grid.ten-up .col {
                                width: 60px !important; }

                            .ie-browser .block-grid.eleven-up .col, [owa] .block-grid.eleven-up .col {
                                width: 54px !important; }

                            .ie-browser .block-grid.twelve-up .col, [owa] .block-grid.twelve-up .col {
                                width: 50px !important; }

                            @media only screen and (min-width: 620px) {
                                .block-grid {
                                    width: 600px !important; }
                                .block-grid .col {
                                    vertical-align: top; }
                                .block-grid .col.num12 {
                                    width: 600px !important; }
                                .block-grid.mixed-two-up .col.num4 {
                                    width: 200px !important; }
                                .block-grid.mixed-two-up .col.num8 {
                                    width: 400px !important; }
                                .block-grid.two-up .col {
                                    width: 300px !important; }
                                .block-grid.three-up .col {
                                    width: 200px !important; }
                                .block-grid.four-up .col {
                                    width: 150px !important; }
                                .block-grid.five-up .col {
                                    width: 120px !important; }
                                .block-grid.six-up .col {
                                    width: 100px !important; }
                                .block-grid.seven-up .col {
                                    width: 85px !important; }
                                .block-grid.eight-up .col {
                                    width: 75px !important; }
                                .block-grid.nine-up .col {
                                    width: 66px !important; }
                                .block-grid.ten-up .col {
                                    width: 60px !important; }
                                .block-grid.eleven-up .col {
                                    width: 54px !important; }
                                .block-grid.twelve-up .col {
                                    width: 50px !important; } }

                            @media (max-width: 620px) {
                                .block-grid, .col {
                                    min-width: 320px !important;
                                    max-width: 100% !important;
                                    display: block !important; }
                                .block-grid {
                                    width: calc(100% - 40px) !important; }
                                .col {
                                    width: 100% !important; }
                                .col > div {
                                    margin: 0 auto; }
                                img.fullwidth, img.fullwidthOnMobile {
                                    max-width: 100% !important; }
                                .no-stack .col {
                                    min-width: 0 !important;
                                    display: table-cell !important; }
                                .no-stack.two-up .col {
                                    width: 50% !important; }
                                .no-stack.mixed-two-up .col.num4 {
                                    width: 33% !important; }
                                .no-stack.mixed-two-up .col.num8 {
                                    width: 66% !important; }
                                .no-stack.three-up .col.num4 {
                                    width: 33% !important; }
                                .no-stack.four-up .col.num3 {
                                    width: 25% !important; }
                                .mobile_hide {
                                    min-height: 0px;
                                    max-height: 0px;
                                    max-width: 0px;
                                    display: none;
                                    overflow: hidden;
                                    font-size: 0px; } }

                        </style>
                        <?php $this->head() ?>
                        </head>
                        <body class="clean-body" style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #007EC3">
                            <?php $this->beginBody() ?>
                            <?= $content ?>
                            <?php $this->endBody() ?>
                        </body>
                        </html>
                        <?php $this->endPage() ?>
