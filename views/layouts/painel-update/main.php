<?php

use yii\helpers\Html;
use app\assets\PainelUpdateAsset;

PainelUpdateAsset::register($this);

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
        
        <div id="wrapper" class="h-100 mh-100">
            
            <div id="page-content-wrapper " class="h-100 mh-100 ">
                
                <?= $this->render('//layouts/_partials/_loading'); ?>
                
                <?= $this->render('//layouts/_partials/_modal-consulta'); ?>
                
                <?= $this->render('//layouts/_partials/_modal-painel'); ?>

                <?= $this->render('//layouts/_partials/_modal-relatorio'); ?>

                <?= $this->render('//layouts/_partials/_modal-pasta'); ?>
                
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
                        text: 'Ocorreu um erro ao executar a ação desejada. Atualize a tela e tente novamente, caso problema persista entre em contato com o administrador do sistema.',
                        title: x.status + " - " + error
                    });
                }
            });
            
        </script>
        
    </body>

</html>

<?php $this->endPage() ?>
