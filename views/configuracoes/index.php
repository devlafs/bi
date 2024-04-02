<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\lists\ConfiguracaoGraficoList;

$this->title = \Yii::t('app', 'view.configuracoes_graficos');
$this->params['breadcrumbs'][] = $this->title;

$attributes = [];

$attributes[] =
[
    'attribute' => 'view',
    'filter' => ConfiguracaoGraficoList::getDataView(),
    'value' => function ($model) 
    {
        return ConfiguracaoGraficoList::getView($model->view);
    }
];

$attributes[] =
[
    'attribute' => 'tipo',
    'filter' => ConfiguracaoGraficoList::getDataTypes(),
    'value' => function ($model) 
    {
        return ConfiguracaoGraficoList::getType($model->tipo);
    }
];

$attributes[] = 'is_serie:boolean';

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
              
?>

<div class="grafico-configuracao-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=> "{items}<div class='row'><div class='col-lg-6'>{pager}</div><div class='col-lg-6 text-right'>{summary}</div></div>",
        'columns' => $attributes
    ]); ?>
    
</div>