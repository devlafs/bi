<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = \Yii::t('app', 'view.conexoes');
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

$attributes[] =
[
    'format' => 'raw',
    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
    'contentOptions' => ['style' => 'text-align: center;'],
    'value' => function($model)
    {
        $data = $model->testConnection();
        $class = (!$data['error']) ? 'danger' : 'success';
        $text = (!$data['error']) ? \Yii::t('app', 'view.conexao.erro') : \Yii::t('app', 'view.conexao.online');
        $message = $data['message'];
        return "<span title='{$message}' class='badge badge-{$class}'>{$text}</span>";
    }
];

$attributes[] = 'nome';

$attributes[] = 'host';

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
        
if($permissao->can('conexao', 'update')) :
        
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
        
if($permissao->can('conexao', 'delete')) :
      
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
                'class' => 'delete-conexao',
                'data-id' => $model->id,
                'data-title' => $model->nome
            ]);
        },
    ],
];

$t_confirm_t = \Yii::t('app', 'view.exclusao');
$t_confirm_d = \Yii::t('app', 'view.confirm_exclusao');
$t_cancel = \Yii::t('app', 'view.cancelar');
$t_exclusao = \Yii::t('app', 'view.excluir');

$js = <<<JS
   
    $(document).delegate('.delete-conexao', 'click', function (e) 
    {
        e.preventDefault();
        var _id = $(this).data('id');
        var _title = $(this).data('title');
        
        swal({
            title: '{$t_confirm_t}',
            text: "{$t_confirm_d} '" + _title + "'?",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: '{$t_cancel}',
            confirmButtonColor: '#007EC3',
            confirmButtonText: '{$t_exclusao}'
        }).then((result) => 
        {
            if (result.value) 
            {
                $.ajax({
                    url: '/conexao/delete?id=' + _id,
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

<div class="conexao-index">

    <?php if($permissao->can('conexao', 'create')) : ?>
    
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