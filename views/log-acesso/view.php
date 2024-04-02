<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->usuario->nomeResumo;
$this->params['breadcrumbs'][] = ['label' => 'Logs de acesso', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="log-view">

    <p class="text-right">

        <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), (!empty(Yii::$app->request->referrer)) ? Yii::$app->request->referrer : ['index'], ['class' => 'btn btn-default']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
        [
            [
                'attribute' => 'admusua_id',
                'value' => ($model->usuario) ? ucwords(strtolower($model->usuario->nomeResumo)) : ''
            ],
            'desc_ip',
            'desc_useragent',
            [
                'attribute' => 'dthr_login',
                'value' =>  Yii::$app->formatter->asDateTime($model->dthr_login, 'php:d/m/Y H:i:s')
            ],
        ],
    ]) ?>

</div>