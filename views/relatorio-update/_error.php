<?php

$js = <<<JS
   
    $(function() 
    {
        $('.card-consulta').matchHeight();
    });
        
JS;

$this->registerJs($js);

?>

<?= $this->render('//layouts/_partials/_left'); ?>

<div id="page-content-wrapper " class="h-100 mh-100 ">

    <div class="page-content inset h-100 mh-100">

        <?= $this->render('_layouts/_top', compact('model')); ?>

        <div class="container-fluid h-100 mh-100 justify-content-between" id="content--container">

            <div class="col-md-12">
    
                <div class="alert alert-danger">

                    <p><?= Yii::t('app', 'view.relatorio.problema_montagem_relatorio') ?></p>
                    
                    <p><?= Yii::t('app', 'view.relatorio.ajuda_montagem_relatorio') ?></p>

                </div>

            </div>

        </div>

    </div>

</div>