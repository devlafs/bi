<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = $mapa->nome . ':: Campos';
$this->params['breadcrumbs'][] = ['label' => 'Mapas', 'url' => ['/mapa/index']];
$this->params['breadcrumbs'][] = ['label' => $mapa->nome, 'url' => ['/mapa/view', 'id' => $mapa->id]];
$this->params['breadcrumbs'][] = $this->title;

$permissao = Yii::$app->permissaoGeral;

$attributes = [];

$attributes[] =
[
    'attribute' => 'nome_campo',
    'value' => function ($model)
    {
        return ($model->campo) ? $model->campo->nome : '';
    }
];

$attributes[] = 'tag';

if($permissao->can('mapa-campo', 'update')) :
    
$attributes[] =
[
    'class' => '\kartik\grid\ActionColumn',
    'header' => \Yii::t('app', 'view.editar'),
    'template' => '{update}',
    'buttons' => 
    [
        'update' => function ($url, $model) {
            return Html::a('<i class="bp-edit"></i>', ['update', 'id_mapa' => $model->id_mapa, 'id' => $model->id], [
                'title' => \Yii::t('app', 'view.alterar'),
            ]);
        },
    ],
];
        
endif;

if($permissao->can('mapa-campo', 'delete')) :
        
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
                'data-mapa' => $model->id_mapa,
                'data-id' => $model->id,
                'data-title' => $model->campo->nome
            ]);
        },
    ],
];

$js = <<<JS
   
    $(document).delegate('.delete-campo', 'click', function (e) 
    {
        e.preventDefault();
        var _id = $(this).data('id');
        var _mapa = $(this).data('mapa');
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
                    url: '/mapa-campo/delete?id_mapa=' + _mapa + '&id=' + _id,
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

<div class="mapa-campo-index">

    <p class="text-right">
        
        <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), ['/mapa'], ['class' => 'btn btn-default']) ?>
        
        <?php if($permissao->can('mapa-campo', 'create')) : ?>
    
            <?= Html::a('<i class="fa fa-plus"></i> ' . \Yii::t('app', 'view.novo'), ['create', 'id_mapa' => $mapa->id], ['class' => 'btn btn-primary']) ?>

        <?php endif; ?>
    
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=> "{items}<div class='row'><div class='col-lg-6'>{pager}</div><div class='col-lg-6 text-right'>{summary}</div></div>",
        'columns' => $attributes
    ]); ?>
</div>
