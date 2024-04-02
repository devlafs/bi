<?php

$this->title = \Yii::t('app', 'view.inserir') . ' ' . strtolower(\Yii::t('app', 'geral.conexao'));
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'view.conexoes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="conexao-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
