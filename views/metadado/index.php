<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = 'Metadados';
$this->params['breadcrumbs'][] = $this->title;
  
$css = <<<CSS
        
    .badge
    {
        border-radius: .5rem; 
        padding: .25em .6em;
    }
        
CSS;

$this->registerCss($css);

$permissao = Yii::$app->permissaoGeral;

$attributes = [];

$attributes[] = 'nome';

$attributes[] =
    [
        'attribute' => 'executed_at',
        'filter' => FALSE,
        'headerOptions' => ['style' => 'width: 20%; text-align: center;'],
        'contentOptions' => ['style' => 'text-align: center;'],
        'format' => 'raw',
        'value' => function ($model)
        {
            return ($model->executed_at) ? Yii::$app->formatter->asDate($model->executed_at, 'php:d/m/Y H:i') : '';
        },
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
            return Html::a('<i class="fa fa-eye"></i>', $url, [
                'title' => \Yii::t('app', 'view.visualizar'),
            ]);
        },
    ],
];
        
if($permissao->can('metadado', 'update')) :
        
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
        
if($permissao->can('metadado', 'delete')) :
      
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
                'class' => 'delete-metadado',
                'data-id' => $model->id,
                'data-title' => $model->nome
            ]);
        },
    ],
];

$js = <<<JS
   
    $(document).delegate('.delete-metadado', 'click', function (e) 
    {
        e.preventDefault();
        var _id = $(this).data('id');
        var _title = $(this).data('title');
        
        swal({
            title: 'Exclusão de metadado',
            text: "Deseja realmente excluir a metadado '" + _title + "'?",
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
                    url: '/metadado/delete?id=' + _id,
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

<div class="metadado-index">

    <?php if($permissao->can('metadado', 'create')) : ?>
    
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