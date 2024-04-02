<?php

use app\lists\ConfiguracaoGraficoList;

$this->title = \Yii::t('app', 'view.alterar') . ' ' . strtolower(\Yii::t('app', 'view.configuracao_grafico')) . ': '
    . ConfiguracaoGraficoList::getView($model->view) . ' | ' . ConfiguracaoGraficoList::getType($model->tipo);
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'view.configuracoes_graficos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => ConfiguracaoGraficoList::getView($model->view) .
        ' | ' . ConfiguracaoGraficoList::getType($model->tipo), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = \Yii::t('app', 'view.alterar');

?>

<div class="configuracao-grafico-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
