<?php

$this->title = 'Alterar Template: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Alterar';

?>

<div class="template-email">

    <?= $this->render('_form', compact('model')) ?>

</div>