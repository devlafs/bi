<?php

use yii\helpers\Html;
use app\assets\PainelViewAsset;
use app\models\RelatorioData;
use app\magic\MobileMagic;

PainelViewAsset::register($this);
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
        
        <?php if(MobileMagic::isMobile()): ?>
        
            <style>

                .page-content #content--container 
                {
                    padding: 0px !important;
                }
                
            </style>
        
        <?php endif; ?>

        <?php $this->head() ?>
        
    </head>

    <body>
        
        <?php $this->beginBody() ?>

        <div id="wrapper" class="<?= !MobileMagic::isMobile() && !$menu_contracted ? 'expanded' : '' ?> h-100 mh-100">

            <?= $this->render('//layouts/_partials/_loading'); ?>
            
            <?= $this->render('//layouts/_partials/_modal-consulta'); ?>

            <?= $this->render('//layouts/_partials/_modal-painel'); ?>

            <?= $this->render('//layouts/_partials/_modal-relatorio'); ?>

            <?= $this->render('//layouts/_partials/_modal-pasta'); ?>
            
            <div id="content-view">
                
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
                
        <?php $this->endBody() ?>

        <?php
                   
            $relatorio_id = Yii::$app->getRequest()->getQueryParam('id');
            $relatorio = RelatorioData::findOne($relatorio_id);
            $consulta = null;
            $painel = null;

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
                        text: 'Ocorreu um erro ao executar a ação desejada. Atualize a tela e tente novamente, caso problema persista entre em contato com o administrador do sistema.',
                        title: x.status + " - " + error
                    });
                }
            });
        </script>

    </body>

</html>

<?php $this->endPage() ?>
