<?php

$js = <<<JS
   
    $(function() 
    {
        $('.card-consulta').matchHeight();
    });
        
JS;

$this->registerJs($js);

$this->title = 'Problema na montagem dos dados dessa consulta.';

$can_generate_url = FALSE;

$can_send_email = FALSE;

$can_filter_graph = FALSE;

$can_change_graph = FALSE;

$can_export = FALSE;

?>

<?= $this->render('//layouts/_partials/_left'); ?>

<div id="page-content-wrapper " class="h-100 mh-100 ">

    <div class="page-content inset h-100 mh-100">

        <?= $this->render('_layouts/_top', compact('model', 'can_export', 'can_generate_url', 'can_send_email', 'can_filter_graph', 'can_change_graph')); ?>

        <div class="container-fluid h-100 mh-100 justify-content-between" id="content--container">

            <div class="col-md-12">
    
                <div class="alert alert-danger">

                    <p><?= $this->title ?></p>
                    
                    <p>Clique em editar, faça as configurações necessárias, salve e depois visualize os dados corretamente.</p>

                </div>

            </div>

        </div>

    </div>

</div>