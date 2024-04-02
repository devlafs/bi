<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = 'Logs de acesso';
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

$columns = [];

$columns[] =
[
    'attribute' => 'admusua_id',
    'label' => 'Usuário',
    'format' => 'raw',
    'enableSorting' => false,
    'value' => function($model)
    {
        return ($model->usuario) ? ucwords(strtolower($model->usuario->nomeResumo)) : '';
    }
];

$columns[] = 'desc_ip';

$columns[] = 
[
    'attribute' => 'dthr_login',
    'filter' => FALSE,
    'format' => 'raw',
    'headerOptions' => ['style' => 'width: 20%; text-align: center;'],
    'contentOptions' => ['style' => 'width: 20%; text-align: center;'],
    'value' => function($model)
    {
        return Yii::$app->formatter->asDateTime($model->dthr_login, 'php:d/m/Y H:i:s');
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

?>

<div class="log-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=> "{items}<div class='row'><div class='col-lg-6'>{pager}</div><div class='col-lg-6 text-right'>{summary}</div></div>",
        'columns' => $columns
    ]); ?>
        
</div>