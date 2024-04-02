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
        
$this->title = 'Cubos';
$this->params['breadcrumbs'][] = $this->title;

$filter_status = [1 => 'Ativo', 0 => 'Inativo'];

$permissao = Yii::$app->permissaoGeral;

$attributes = [];

$attributes[] = 
[
    'label' => '',
    'filter' => FALSE,
    'format' => 'raw',
    'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
    'contentOptions' => ['style' => 'text-align: center;'],
    'value' => function ($model)
    {
        $last_execution  = strtotime($model->executed_at);
        $current_datetime = date('Y-m-d H:i:s');
        $now = strtotime($current_datetime);
        
        $diff = $now - $last_execution;
        
        $prox_execution = ($model->executed_at) ? date('d/m/Y H:i', $last_execution + $model->periodicidade) : '';
        
        $class = "badge badge-success";
        $text = "Atualizado";
        
        if(!$model->is_ativo)
        {
            $class = "badge badge-danger";
            $text = "Inativo";
        }
        elseif($diff > $model->periodicidade)
        {
            $class = "badge badge-warning";
            $text = "Desatualizado";
        }
        
        return '<span title="' . $prox_execution . '" class="' . $class . '">' . $text . '</span>';
    }
];

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

$attributes[] = 
[
    'attribute' => 'periodicidade',
    'filter' => PeriodicidadeMagic::getDataPeriodo(),
    'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
    'contentOptions' => ['style' => 'text-align: center;'],
    'value' => function ($model)
    {
        return PeriodicidadeMagic::getPeriodo($model->periodicidade);
    }
];

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
    
if($permissao->can('indicador', 'carga')) :

$pdi = Yii::$app->params['pdi'];

$attributes[] = 
[
    'class' => '\kartik\grid\ActionColumn',
    'header' => 'Carga',
    'template' => '{carga}',
    'buttons' =>
    [
        'carga' => function ($url, $model) use ($pdi)
        {
            return Html::a('<i class="fa fa-sync"></i>', $url, [
                'title' => ($pdi) ? 'Agendar próxima carga' : 'Carregar dados',
            ]);
        }
    ],
];
    
endif;
    
if($permissao->can('indicador', 'status')) :
    
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
            
if($permissao->can('indicador', 'update')) :
    
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

if($permissao->can('indicador-campo', 'index')) :
    
$attributes[] = 
[
    'class' => '\kartik\grid\ActionColumn',
    'header' => 'Conf. Campos',
    'template' => '{fields}',
    'buttons' => 
    [
        'fields' => function ($url, $model) 
        {
            return Html::a('<i class="bp-data-grid"></i>', ['/indicador-campo/index', 'id' => $model->id], [
                'title' => \Yii::t('app', 'view.visualizar'),
            ]);
        }
    ],
];
    
endif;
        
if($permissao->can('indicador', 'delete')) :
      
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
                'class' => 'delete-indicador',
                'data-id' => $model->id,
                'data-title' => $model->nome
            ]);
        },
    ],
];

$js = <<<JS
   
    $(document).delegate('.delete-indicador', 'click', function (e) 
    {
        e.preventDefault();
        var _id = $(this).data('id');
        var _title = $(this).data('title');
        
        swal({
            title: 'Exclusão de cubo',
            text: "Deseja realmente excluir o cubo '" + _title + "'?",
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
                    url: '/indicador/delete?id=' + _id,
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

<div class="ind-indicador-index">

    <?php if($permissao->can('indicador', 'create')) : ?>
    
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
