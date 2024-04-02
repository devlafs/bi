<?php

use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this);

$css = <<<CSS
        
#chartbp1 
{
    width: 100%;
    height: 500px;
    font-size: 11px;
}
        
.div-loading
{
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
}
        
.div-loading.loading 
{
    overflow: hidden;   
}
        
.div-loading.loading
{
    display: block;
}
        
.breadcrumb-cp,
.cursor-pointer
{
    cursor: pointer;
}
        
.cursor-nodrop
{
    cursor: no-drop;
}
        
.card.card-consulta .card-block .dataTables_wrapper 
{
    height: 100%;
}
        
.btn-xs 
{
    padding: 1px 5px;
    font-size: 12px;
    line-height: 1.5;
    border-radius: 3px;
}
    
a 
{
    color: #237486;
    text-decoration: none;
}
        
.page-content #content--container 
{
    padding: 0px;
}
   
CSS;

$this->registerCss($css);

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
        
        <div id="wrapper" style="padding-left: 0px;">

            <div class="div-loading">
                <div class="bb-r-spinner" style='position: fixed; top: 50%; left: 50%;'>
                    <div class="bb-r-spinner-circle-transparent">
                    </div>
                    <div class="bb-r-spinner-circle">
                    </div>
                </div>
            </div>
            
            <div id="content-view">
                <?= $content ?>
            </div>
            
        </div>
        
        <script src="/js/jquery.min.js"></script>

    <?php $this->endBody() ?>
        
    </body>

</html>

<?php $this->endPage() ?>
