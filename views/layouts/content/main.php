<?php

use yii\helpers\Html;
use app\assets\ConsultaViewAsset;

ConsultaViewAsset::register($this);

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
        
        <div id="wrapper" class="pl-0 h-100 mh-100">

            <?= $this->render('//layouts/_partials/_loading'); ?>

            <div id="content-view">
                
                <?= $content ?>
                
            </div>
            
        </div>
                
        <?php $this->endBody() ?>

    </body>

</html>

<?php $this->endPage() ?>
