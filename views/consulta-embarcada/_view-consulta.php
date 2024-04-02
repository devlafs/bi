<?php

$this->title = \Yii::t('app', 'geral.consulta') . '::: ' . $model->nome;

if($color):

    $css = <<<CSS

        .table__preview thead th, .dataTables_wrapper thead th, .dataTables_wrapper tbody tr.sum-total td, .table__preview tbody tr.sum-total td {
            background-color: #{$color} !important;
        }
        
        .card.card-consulta .card-footer span,
         .card.card-consulta .card-header h4 span,
          .card.card-consulta .card-header h4 {
            color: #{$color};
        }
        
        div.dataTables_wrapper div.dataTables_filter input, div.dataTables_wrapper div.dataTables_length select {
            border: 1px solid #{$color};
        }
        
        .card.card-consulta:hover, .card.card-consulta.active {
            border-color: #{$color};
        }
        
        a {
            color: #{$color} !important;
        }
        
        .card.card-consulta .card-value {
            color: #{$color} !important;
        }

CSS;

$this->registerCss($css);

endif;

?>

<div id="page-content-wrapper " style="padding-left:0px;">

    <div class="page-content inset h-100 mh-100">

        <div class="container-fluid h-100 mh-100 justify-content-between" id="content--container">

            <div class="col-md-12">
    
                <div data-mh="painel-group-001" class="card card-consulta card--chart card--consuta__full" style="width: 100%;">

                    <?= $this->render('/_graficos/_general/cshipped', compact('index', 'data', 'model')) ?>

                </div>

            </div>

        </div>

    </div>

</div>