<?php

$this->title = 'Inserir Campo';
$this->params['breadcrumbs'][] = ['label' => 'Mapas', 'url' => ['/mapa/index']];
$this->params['breadcrumbs'][] = ['label' => $model->mapa->nome, 'url' => ['/mapa/view', 'id' => $model->id_mapa]];
$this->params['breadcrumbs'][] = ['label' => 'Campos', 'url' => ['index', 'id' => $model->id_mapa]];
$this->params['breadcrumbs'][] = 'Alterar';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="mapa-campo-create">

    <?= $this->render('_form', [
        'model' => $model,
        'preview' => []
    ]) ?>

</div>
