<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Conexao;
use app\magic\PeriodicidadeMagic;

$css = <<<CSS
    
   .badge
    {
        border-radius: .5rem;
        padding: .25em .6em;
    }
        
CSS;

$this->registerCss($css);
        
$this->title = 'Relatórios';
$this->params['breadcrumbs'][] = $this->title;

$filter_status = [1 => 'Ativo', 0 => 'Inativo'];

$permissao = Yii::$app->permissaoGeral;

$attributes = [];

$attributes[] = 'nome';

$attributes[] = 
[
    'attribute' => 'id_conexao',
    'filter' => ArrayHelper::map(Conexao::find()->andWhere([
        'is_ativo' => TRUE,
        'is_excluido' => FALSE
    ])->orderBy('nome ASC')->all(), 'id', 'nome'),
    'value' => function ($model)
    {
        return ($model->conexao) ? $model->conexao->nome : 'Sem conexão';
    }
];
    
if($permissao->can('relatorio', 'status')) :
    
$attributes[] = 
[
    'class' => '\kartik\grid\ActionColumn',
    'header' => 'Status',
    'template' => '{status}',
    'buttons' =>
    [
        'status' => function ($url, $model)
        {
            if($model->is_ativo)
            {
                return Html::a('<i class="fa fa-check"></i>', $url, [
                    'title' => 'Desativar',
                ]);
            }
            else
            {
                return Html::a('<i class="bp-close--circle-o"></i>', $url, [
                    'title' => 'Ativar',
                ]);
            }
        }
    ],
];
    
endif;
         
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
            
if($permissao->can('relatorio', 'update')) :
    
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

if($permissao->can('relatorio-campo', 'index')) :
    
$attributes[] = 
[
    'class' => '\kartik\grid\ActionColumn',
    'header' => 'Conf. Campos',
    'template' => '{fields}',
    'buttons' => 
    [
        'fields' => function ($url, $model) 
        {
            return Html::a('<i class="bp-data-grid"></i>', ['/relatorio-campo/index', 'id' => $model->id], [
                'title' => \Yii::t('app', 'view.visualizar'),
            ]);
        }
    ],
];
    
endif;
        
if($permissao->can('relatorio', 'delete')) :
      
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
                'class' => 'delete-relatorio',
                'data-id' => $model->id,
                'data-title' => $model->nome
            ]);
        },
    ],
];

$js = <<<JS
   
    $(document).delegate('.delete-relatorio', 'click', function (e) 
    {
        e.preventDefault();
        var _id = $(this).data('id');
        var _title = $(this).data('title');
        
        swal({
            title: 'Exclusão de cubo',
            text: "Deseja realmente excluir o relatório '" + _title + "'?",
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
                    url: '/relatorio/delete?id=' + _id,
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

<div class="ind-relatorio-index">

    <?php if($permissao->can('relatorio', 'create')) : ?>
    
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
