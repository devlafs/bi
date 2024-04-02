<?php

$this->title = 'Alterar Campo: ' . $model->campo->nome;
$this->params['breadcrumbs'][] = ['label' => 'Mapas', 'url' => ['/mapa/index']];
$this->params['breadcrumbs'][] = ['label' => $model->mapa->nome, 'url' => ['/mapa/view', 'id' => $model->id_mapa]];
$this->params['breadcrumbs'][] = ['label' => 'Campos', 'url' => ['index', 'id' => $model->id_mapa]];
$this->params['breadcrumbs'][] = 'Alterar';

?>

<div class="mapa-campo-update">

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
