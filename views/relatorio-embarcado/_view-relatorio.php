<?php

use yii\widgets\Pjax;
use app\magic\RelatorioMagic;

$this->title = \Yii::t('app', 'geral.relatorio') . '::: ' . $model->nome;

if($color):

    $css = <<<CSS

        .table .thead-default tr,
         button.btn.btn-sm.btn-primary.text-uppercase {
            background-color: #{$color} !important;
            color: #fff !important;
        }

        .table__preview thead th, #datagrid thead th, .dataTables_wrapper tbody tr.sum-total td, .table__preview tbody tr.sum-total td {
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

$this->registerJs(

    '$("document").ready(function(){ 

        $("#search-form").on("pjax:end", function() {

            $.pjax.reload({container:"#gridData"});

        });

    });'

);

if($model->javascript):

    $this->registerJs($model->javascript);

endif;

?>

<div id="page-content-wrapper " style="padding-left:0px;">

    <div class="page-content inset h-100 mh-100">

        <div class="relatorio-index">

            <div class="row">

                <div class="col-lg-12 col-md-12">

                    <div class="card card-outline-plan">

                        <div class="card-body p-5" style="max-height: calc(100vh - 50px); overflow-y: scroll;">

                            <?php if(!$dataProvider): ?>

                                <div class="alert alert-danger">
                                    O Relatório não foi configurado corretamente. Por favor, entre em contato com o administrador do sistema e solicite a correção.
                                </div>

                            <?php else: ?>

                                <?php if($model->condicao) :

                                    $limpar = ['v', 'c'=> $c, 'u' => $usuario_id, 'p' => $p, 'b' => $color];
                                    ?>

                                    <?= $this->render('/ajax/_relatorio/_layouts/_filter', compact('model', 'searchModel', 'limpar')) ?>

                                <?php endif; ?>

                                <?php Pjax::begin(['id' => 'gridData']) ?>

                                <table id="datagrid" class="table table-hover table-bordered">

                                    <thead class="thead-default">

                                    <tr>

                                        <?php foreach ($campos['x'] as $campo) : ?>

                                            <td><?= $campo['campo']['nome'] ?></td>

                                        <?php endforeach; ?>

                                        <td><?= $campos['y']['campo']['nome'] ?></td>

                                    </tr>

                                    </thead>

                                    <tbody>

                                    <?php foreach($dataProvider->getModels() as $valor) : ?>

                                        <tr>

                                            <?php foreach ($campos['x'] as $campo) : ?>

                                                <td><?= RelatorioMagic::format($campo['campo'], $valor) ?></td>

                                            <?php endforeach; ?>

                                            <td><?= RelatorioMagic::format($campos['y']['campo'], $valor) ?></td>

                                        </tr>

                                    <?php endforeach; ?>

                                    </tbody>

                                </table>

                                <?php Pjax::end() ?>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>