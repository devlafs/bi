<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = 'Logs de ações';
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
    
    .table td
    {
        padding: 0.5rem;
    }

CSS;

$this->registerCss($css);

$this->registerJs(

   '$("document").ready(function(){ 

        $("#search-form").on("pjax:end", function() {

            $.pjax.reload({container:"#gridData"});

        });

    });'

);

$data_type =
[
    'insert' => 'Cadastro',
    'update' => 'Alteração',
    'deleted' => 'Exclusão',
    'restore' => 'Restauração',
];

$columns = [];

$columns[] = 'relatedObjectType';

$columns[] = 
[
    'attribute' => 'relatedObjectId',
    'headerOptions' => ['style' => 'width: 15%; text-align: center;'],
    'contentOptions' => ['style' => 'width: 15%; text-align: center;'],
];

$columns[] = 
[
    'attribute' => 'type',
    'headerOptions' => ['style' => 'width: 20%; text-align: center;'],
    'contentOptions' => ['style' => 'width: 20%; text-align: center;'],
    'filter' => $data_type,
    'value' => function($model) use ($data_type)
    {
        if (strpos($model->data, '"is_excluido":[0,true]') !== false) 
        {
            return 'Exclusão';
        }
        else if (strpos($model->data, '"is_excluido":[1,0]') !== false)
        {
            return 'Restauração';
        }
        else
        {
            return isset($data_type[$model->type]) ? $data_type[$model->type] : $model->type;
        }
    }
];

$columns[] = 
[
    'attribute' => 'userId',
    'label' => '',
    'filter' => FALSE,
    'format' => 'raw',
    'enableSorting' => false,
    'value' => function($model)
    {
        return ($model->user) ? ucwords(strtolower($model->user->nomeResumo)) : '';
    }
];

$columns[] = 
[
    'attribute' => 'createdAt',
    'filter' => FALSE,
    'format' => 'raw',
    'headerOptions' => ['style' => 'width: 20%; text-align: center;'],
    'contentOptions' => ['style' => 'width: 20%; text-align: center;'],
    'value' => function($model)
    {
        return Yii::$app->formatter->asDateTime($model->createdAt, 'php:d/m/Y H:i:s');
    }
];

$columns[] = 
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

$columns[] =
[
    'class' => '\kartik\grid\ActionColumn',
    'header' => 'Restaurar',
    'template' => '{restore}',
    'buttons' =>
    [
        'restore' => function ($url, $model)
        {
            if (strpos($model->data, '"is_excluido":[0,true]') !== false)
            {
                return Html::a('<span class="fa fa-undo"></span>', $url, [
                    'title' => 'Restaurar',
                ]);
            }
        }
    ],
];

?>

<div class="log-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=> "{items}<div class='row'><div class='col-lg-6'>{pager}</div><div class='col-lg-6 text-right'>{summary}</div></div>",
        'columns' => $columns
    ]); ?>
        
</div>