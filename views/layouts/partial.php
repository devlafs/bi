<?php

use yii\helpers\Html;
use app\assets\PartialAsset;

//PartialAsset::register($this);

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
        

        <?php $this->head() ?>
        
    </head>

    <body>
        
        <?php $this->beginBody() ?>
        
            <?= $content ?>
        
        <?php $this->endBody() ?>

    </body>

</html>

<?php $this->endPage() ?>
