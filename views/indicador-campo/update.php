<?php

use yii\helpers\Html;

$this->title = 'Alterar Campo: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Cubos', 'url' => ['/indicador/index']];
$this->params['breadcrumbs'][] = ['label' => $model->indicador->nome, 'url' => ['/indicador/view', 'id' => $model->id_indicador]];
$this->params['breadcrumbs'][] = ['label' => 'Campos', 'url' => ['index', 'id' => $model->id_indicador]];
$this->params['breadcrumbs'][] = 'Alterar';

?>

<div class="ind-indicador-update">

    <?= $this->render(($model->tipo == 'formulavalor' || $model->tipo == 'formulatexto') ? '_formula' : '_form', [
        'model' => $model,
        'preview' => $preview
    ]) ?>

</div>
