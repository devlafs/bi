<?php

$this->title = 'Alterar Cubo: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Cubos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Alterar';

?>

<div class="indicador-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
