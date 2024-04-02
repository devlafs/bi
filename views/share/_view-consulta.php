<?php

$this->title = 'Consulta::: ' . $model->nome;

if($model->privado && !$p):

    $current_url = Yii::$app->request->url;

    $js = <<<JS
    
    swal({
        title: 'Informe a chave de acesso:',
        input: 'text',
        inputAttributes:
        {
          autocapitalize: 'off'
        },
        showCancelButton: false,
        confirmButtonText: 'Visualizar',
        confirmButtonColor: '#007EC3',
        showLoaderOnConfirm: true,
        closeOnClickOutside: false,
        allowOutsideClick: false,
        preConfirm: (pass) => 
        {
            window.location =  '{$current_url}&p=' + pass;
        },
    });

JS;

    $this->registerJs($js);

endif;

?>

<div id="page-content-wrapper " style="padding-left:0px;">

    <div class="page-content inset h-100 mh-100">

        <?= ($header) ? $this->render('_layouts/_top', compact('model')) : null; ?>

        <div class="container-fluid h-100 mh-100 justify-content-between" id="content--container">

            <div class="col-md-12">
    
                <div data-mh="painel-group-001" class="card card-consulta card--chart card--consuta__full" style="width: 100%;">

                    <?php if(!$model->privado || $p) : ?>

                        <?= $this->render('/_graficos/_general/share', compact('index', 'data', 'model')) ?>

                    <?php endif; ?>

                </div>
            </div>


        </div>

    </div>

</div>