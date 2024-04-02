<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\lists\ConfiguracaoGraficoList;

$this->title = \Yii::t('app', 'view.configuracao_grafico') . ': ' . ConfiguracaoGraficoList::getView($model->view) .
        ' | ' . ConfiguracaoGraficoList::getType($model->tipo);
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'view.configuracoes_graficos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="configuracao-grafico-view">

    <p class="text-right">

        <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), (!empty(Yii::$app->request->referrer)) ? Yii::$app->request->referrer : ['index'], ['class' => 'btn btn-default']) ?>

        <?= Html::a('<i class="bp-edit"></i> ' . \Yii::t('app', 'view.alterar'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
        [
            [
                'attribute' => 'view',
                'value' => ConfiguracaoGraficoList::getView($model->view)
            ],
            [
                'attribute' => 'tipo',
                'value' => ConfiguracaoGraficoList::getType($model->tipo)
            ],
            'data:ntext',
            'is_serie:boolean',
            'data_serie:ntext',
        ],
    ]) ?>

</div>
