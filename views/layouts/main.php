<?php

use yii\helpers\Html;
use app\assets\AppAsset;
use app\magic\MobileMagic;

AppAsset::register($this);
$menu_contracted = isset($_SESSION['menu_' . Yii::$app->user->id]) ? $_SESSION['menu_' . Yii::$app->user->id] : false;

?>

<?php $this->beginPage() ?>

<!doctype html>

<html lang="<?= Yii::$app->language ?>">

<head>

    <meta charset="<?= Yii::$app->charset ?>">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?= Html::csrfMetaTags() ?>

    <title><?= Html::encode($this->title) ?></title>

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

    <script src="/js/jquery.min.js"></script>
    
    <script src="/js/tether.min.js"></script>
    
    <script src="/js/bootstrap.min.js"></script>
    
    <?php $this->head() ?>

</head>

    <body>

        <?php $this->beginBody() ?>

        <div id="wrapper" class="<?= !MobileMagic::isMobile() && !$menu_contracted ? 'expanded' : '' ?> h-100 mh-100">

            <?= $this->render('_partials/_left'); ?>

            <div id="page-content-wrapper " class="h-100 mh-100 ">

                <div class="page-content inset h-100 mh-100">

                    <?= $this->render('_partials/_top'); ?>

                    <?= $this->render('//layouts/_partials/_modal-consulta'); ?>

                    <?= $this->render('//layouts/_partials/_modal-painel'); ?>

                    <?= $this->render('//layouts/_partials/_modal-relatorio'); ?>

                    <?= $this->render('//layouts/_partials/_modal-pasta'); ?>

                    <div class="container-fluid pt-3 justify-content-between" id="content--container">

                        <?php

                        if (Yii::$app->session->hasFlash('toast-success')):

                            $message_success =  Yii::$app->session->getFlash('toast-success');

                            $js_success = "iziToast.success({
                                title: '{$message_success}',
                                position: 'topCenter',
                                close: true,
                                transitionIn: 'flipInX',
                                transitionOut: 'flipOutX',
                            });";

                            $this->registerJs($js_success);

                        endif;

                        if (Yii::$app->session->hasFlash('toast-error')):

                            $message_error =  Yii::$app->session->getFlash('toast-error');

                            $js_error = "iziToast.error({
                                title: '{$message_error}',
                                position: 'topCenter',
                                close: true,
                                transitionIn: 'flipInX',
                                transitionOut: 'flipOutX',
                            });";

                            $this->registerJs($js_error);

                        endif;

                        ?>

                        <?= $content ?>

                    </div>

                </div>

            </div>

        </div>

    <?php $this->endBody() ?>

        <?php
        
        $consulta = null;
        $painel = null;
        $relatorio = null;

        echo $this->render('//layouts/_partials/_script-folder', compact('consulta', 'painel', 'relatorio'));

        ?>

        <script>
            $.ajaxSetup({
                data: <?= \yii\helpers\Json::encode([
                    \yii::$app->request->csrfParam => \yii::$app->request->csrfToken,
                ]) ?>,
                error: function (x, status, error) 
                {
                    swal({
                        type: 'error',
                        text: 'Ocorreu um erro ao executar a ação desejada. Atualize a tela e tente novamente, e caso problema persista entre em contato com o administrador do sistema.',
                        title: x.status + " - " + error
                    });
                }
            });

        </script>

    </body>

</html>

<?php $this->endPage() ?>

