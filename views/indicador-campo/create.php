<?php

$this->title = 'Inserir Fórmula';
$this->params['breadcrumbs'][] = ['label' => 'Cubos', 'url' => ['/indicador/index']];
$this->params['breadcrumbs'][] = ['label' => $model->indicador->nome, 'url' => ['/indicador/view', 'id' => $model->id_indicador]];
$this->params['breadcrumbs'][] = ['label' => 'Campos', 'url' => ['index', 'id' => $model->id_indicador]];
$this->params['breadcrumbs'][] = 'Alterar';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="campo-create">

    <?= $this->render('_formula', [
        'model' => $model,
        'preview' => []
    ]) ?>

</div>
