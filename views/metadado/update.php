<?php

$this->title = 'Alterar Metadado: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Metadados', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Alterar';

?>

<div class="metadado-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
