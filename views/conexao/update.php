<?php

$this->title = \Yii::t('app', 'view.alterar') . ' ' . strtolower(\Yii::t('app', 'geral.conexao')) . ': ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'view.conexoes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = \Yii::t('app', 'view.alterar');

?>

<div class="conexao-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
