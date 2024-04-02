<?php

use yii\helpers\Html;
use kartik\grid\GridView;
        
$this->title = 'Perfis';
$this->params['breadcrumbs'][] = $this->title;

$permissao = Yii::$app->permissaoGeral;

$attributes = [];

$attributes[] = 'nome';

$attributes[] =
[
    'attribute' => 'acesso_bi',
    'format' => 'boolean',
    'headerOptions' => ['class' => 'text-center'],
    'contentOptions' => ['style' => 'width: 10%;', 'class' => 'text-center'],
];

$attributes[] =
[
    'attribute' => 'is_admin',
    'format' => 'boolean',
    'headerOptions' => ['class' => 'text-center'],
    'contentOptions' => ['style' => 'width: 10%;', 'class' => 'text-center'],
];

$attributes[] =
[
    'label' => 'Quantidade de Usuários',
    'attribute' => 'quantidade_usuarios',
    'headerOptions' => ['class' => 'text-center'],
    'contentOptions' => ['style' => 'width: 10%;', 'class' => 'text-center'],
    'filter' => FALSE,
    'value' => function ($model)
    {
        return sizeof($model->usuarios);
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

if($permissao->can('perfil', 'update')) :
    
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
        
if($permissao->can('perfil', 'delete')) :
    
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
                'class' => 'delete-perfil',
                'data-id' => $model->id,
                'data-title' => $model->nome
            ]);
        },
    ],
];
    
$js = <<<JS
   
    $(document).delegate('.delete-perfil', 'click', function (e) 
    {
        e.preventDefault();
        var _id = $(this).data('id');
        var _title = $(this).data('title');
        
        swal({
            title: 'Exclusão de perfil',
            text: "Deseja realmente excluir o perfil '" + _title + "'?",
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
                    url: '/perfil/delete?id=' + _id,
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

<div class="ind-perfil-index">

    <?php if($permissao->can('perfil', 'create')) : ?>
    
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