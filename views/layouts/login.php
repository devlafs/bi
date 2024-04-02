<?php

use yii\helpers\Html;
use app\magic\CacheMagic;

$version = CacheMagic::getSystemData('version');

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
        <link rel="stylesheet" href="/css/bootstrap.css">
        <link rel="stylesheet" href="/css/iziToast.min.css">
        <link rel="stylesheet" href="/css/main.css">
        
        <?php $this->head() ?>
    </head>

    <body>
        
        <?php $this->beginBody() ?>

        <div class="container-fluid bp1-application">
            <div class="row h-100">
                <div class="col-lg-12 col-xl-12 justify-content-center login--content">
                    
                    <?php

                        if (Yii::$app->session->hasFlash('toast-success')):

                            $message =  Yii::$app->session->getFlash('toast-success');

                            $js = "iziToast.success({
                                title: '{$message}',
                                position: 'topCenter',
                                close: true,
                                transitionIn: 'flipInX',
                                transitionOut: 'flipOutX',
                            });";

                            $this->registerJs($js);

                        endif;

                    ?>
                    
                    <?= $content ?>
                </div>
            </div>
        </div>

        <script src="/js/jquery.min.js"></script>
        <script src="/js/tether.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/iziToast.js"></script>
        
        <?php $this->endBody() ?>
        
    </body>

</html>

<?php $this->endPage() ?>
