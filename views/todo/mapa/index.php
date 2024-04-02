<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = 'Mapas';
$this->params['breadcrumbs'][] = $this->title;

$permissao = Yii::$app->permissaoGeral;

$attributes = [];

$attributes[] = 'nome';
         
$attributes[] = 
[
    'label' => 'Qtd. de Campos',
    'attribute' => 'qtd_tags',
    'filter' => FALSE,
    'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
    'contentOptions' => ['style' => 'text-align: center;'],
    'value' => function ($model)
    {
        return count($model->campos);
    }
];

$attributes[] = 
[
    'class' => '\kartik\grid\ActionColumn',
    'header' => \Yii::t('app', 'view.visualizar'),
    'template' => '{view}',
    'buttons' =>
    [
        'view' => function ($url, $model)
        {
            return Html::a('<span class="fa fa-eye"></span>', $url, [
                'title' => \Yii::t('app', 'view.visualizar'),
            ]);
        }
    ],
];
    
if($permissao->can('mapa-campo', 'index')) :
    
$attributes[] = 
[
    'class' => '\kartik\grid\ActionColumn',
    'header' => 'Conf. Campos',
    'template' => '{fields}',
    'buttons' => 
    [
        'fields' => function ($url, $model) 
        {
            return Html::a('<i class="bp-data-grid"></i>', ['/mapa-campo/index', 'id' => $model->id], [
                'title' => \Yii::t('app', 'view.visualizar'),
            ]);
        }
    ],
];
    
endif;
            
if($permissao->can('mapa', 'update')) :
    
$attributes[] = 
[
    'class' => '\kartik\grid\ActionColumn',
    'header' => \Yii::t('app', 'view.editar'),
    'template' => '{update}',
    'buttons' => 
    [
        'update' => function ($url, $model) {
            return Html::a('<i class="bp-edit"></i>', $url, [
                'title' => \Yii::t('app', 'view.alterar'),
            ]);
        },
    ],
];
    
endif;
        
if($permissao->can('mapa', 'delete')) :
      
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
                'class' => 'delete-mapa',
                'data-id' => $model->id,
                'data-title' => $model->nome
            ]);
        },
    ],
];

$js = <<<JS
   
    $(document).delegate('.delete-mapa', 'click', function (e) 
    {
        e.preventDefault();
        var _id = $(this).data('id');
        var _title = $(this).data('title');
        
        swal({
            title: 'Exclusão de mapa',
            text: "Deseja realmente excluir o mapa '" + _title + "'?",
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
                    url: '/mapa/delete?id=' + _id,
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

<div class="mapa-index">

    <?php if($permissao->can('mapa', 'create')) : ?>
    
        <p class="text-right">
            
            <?= Html::a('<i class="fa fa-plus"></i> ' . \Yii::t('app', 'view.novo'), ['create'], ['class' => 'btn btn-primary']) ?>
            
        </p>
    
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=> "{items}<div class='row'><div class='col-lg-6'>{pager}</div><div class='col-lg-6 text-right'>{summary}</div></div>",
        'columns' => $attributes
    ]); ?>
        
</div>
