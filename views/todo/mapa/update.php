<?php

$this->title = 'Alterar Mapa: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Mapas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Alterar';

?>

<div class="mapa-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
