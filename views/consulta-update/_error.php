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

                    <p><?= Yii::t('app', 'view.consulta.problema_montagem_consulta') ?></p>
                    
                    <p><?= Yii::t('app', 'view.consulta.ajuda_montagem_consulta') ?></p>

                </div>

            </div>

        </div>

    </div>

</div>