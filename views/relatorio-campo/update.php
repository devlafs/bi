<?php

use yii\helpers\Html;

$this->title = 'Alterar Campo: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Relatórios', 'url' => ['/relatorio/index']];
$this->params['breadcrumbs'][] = ['label' => $model->relatorio->nome, 'url' => ['/relatorio/view', 'id' => $model->id_relatorio]];
$this->params['breadcrumbs'][] = ['label' => 'Campos', 'url' => ['index', 'id' => $model->id_relatorio]];
$this->params['breadcrumbs'][] = 'Alterar';

?>

<div class="ind-relatorio-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
