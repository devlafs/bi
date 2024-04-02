<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = $relatorio->nome . ':: Campos';
$this->params['breadcrumbs'][] = ['label' => 'Relatórios', 'url' => ['/relatorio/index']];
$this->params['breadcrumbs'][] = ['label' => $relatorio->nome, 'url' => ['/relatorio/view', 'id' => $relatorio->id]];
$this->params['breadcrumbs'][] = $this->title;

$filter = ['texto' => 'Texto', 'data' => 'Data', 'valor' => 'Valor', 'formulavalor' => 'Fórmula (Valor)', 'formulatexto' => 'Fórmula (Texto)'];
        
$permissao = Yii::$app->permissaoGeral;

$attributes = [];

$attributes[] =
[
    'attribute' => 'ordem',
    'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
    'contentOptions' => ['style' => 'text-align: center;'],
    'filter' => FALSE
];

$attributes[] = 'nome';

$attributes[] =
[
    'attribute' => 'tipo',
    'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
    'contentOptions' => ['style' => 'text-align: center;'],
    'filter' => \app\models\RelatorioCampo::$tipos,
    'format' => 'raw',
    'value' => function($model)
    {
        return $model->getTipoString();
    }
];

if($permissao->can('relatorio-campo', 'update')) :
    
$attributes[] =
[
    'class' => '\kartik\grid\ActionColumn',
    'header' => \Yii::t('app', 'view.editar'),
    'template' => '{update}',
    'buttons' => 
    [
        'update' => function ($url, $model) {
            return Html::a('<i class="bp-edit"></i>', ['update', 'id_relatorio' => $model->id_relatorio, 'id' => $model->id], [
                'title' => \Yii::t('app', 'view.alterar'),
            ]);
        },
    ],
];
        
endif;

if($permissao->can('relatorio-campo', 'delete')) :
        
$attributes[] =
[
    'class' => '\kartik\grid\ActionColumn',
    'header' => \Yii::t('app', 'view.excluir'),
    'template' => '{delete}',
    'buttons' => 
    [
        'delete' => function ($url, $model) {
            return Html::a('<i class="fa fa-trash"></i>', 'javascript:', [
                'title' => \Yii::t('app', 'view.excluir'),
                'class' => 'delete-campo',
                'data-relatorio' => $model->id_relatorio,
                'data-id' => $model->id,
                'data-title' => $model->nome
            ]);
        },
    ],
];

$js = <<<JS
   
    $(document).delegate('.delete-campo', 'click', function (e) 
    {
        e.preventDefault();
        var _id = $(this).data('id');
        var _relatorio = $(this).data('relatorio');
        var _title = $(this).data('title');
        
        swal({
            title: 'Exclusão de campo',
            text: "Deseja realmente excluir o campo '" + _title + "'?",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#007EC3',
            confirmButtonText: 'Excluir'
        }).then((result) => 
        {
            if (result.value) 
            {
                $.ajax({
                    url: '/relatorio-campo/delete?id_relatorio=' + _relatorio + '&id=' + _id,
                    type: 'GET',
                    success: function (_data) 
                    {
                        location.reload();
                    }
                })
            }
        })
    });
        
JS;

$this->registerJs($js);

endif;

?>

<div class="relatorio-campo-index">

    <p class="text-right">

        <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), (!empty(Yii::$app->request->referrer)) ? Yii::$app->request->referrer : ['/relatorio'], ['class' => 'btn btn-default']) ?>

        <?php if($permissao->can('relatorio-campo', 'generate')) : ?>
    
            <?= Html::a('<i class="fa fa-plus"></i> Gerar campos', ['generate', 'id_relatorio' => $relatorio->id], ['class' => 'btn btn-primary']) ?>

        <?php endif; ?>
    
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=> "{items}<div class='row'><div class='col-lg-6'>{pager}</div><div class='col-lg-6 text-right'>{summary}</div></div>",
        'columns' => $attributes
    ]); ?>
</div>
