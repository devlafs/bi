<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->relatedObjectType . ': ' . $model->relatedObjectId;
$this->params['breadcrumbs'][] = ['label' => 'Logs de acessos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$data_type =
[
    'insert' => 'Cadastro',
    'update' => 'Alteração',
    'deleted' => 'Exclusão',
    'restore' => 'Restauração',
];

$tipo = '';

if (strpos($model->data, '"is_excluido":[0,true]') !== false) 
{
    $tipo = 'Exclusão';
}
else if (strpos($model->data, '"is_excluido":[1,0]') !== false)
{
    $tipo = 'Restauração';
}
else
{
    $tipo = isset($data_type[$model->type]) ? $data_type[$model->type] : $model->type;
}

?>

<div class="log-view">

    <p class="text-right">

        <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), (!empty(Yii::$app->request->referrer)) ? Yii::$app->request->referrer : ['index'], ['class' => 'btn btn-default']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
        [
            'relatedObjectType',
            'relatedObjectId',
            [
                'attribute' => 'type',
                'value' =>  $tipo
            ],
            [
                'attribute' => 'userId',
                'value' => ($model->user) ? ucwords(strtolower($model->user->nomeResumo)) : ''
            ],
            'hostname',
            [
                'attribute' => 'createdAt',
                'value' =>  Yii::$app->formatter->asDateTime($model->createdAt, 'php:d/m/Y H:i:s')
            ],
            [
                'attribute' => 'data',
                'format' => 'raw',
                'value' =>  $model->getStringData()
            ],
        ],
    ]) ?>

</div>