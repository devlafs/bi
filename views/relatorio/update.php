<?php

$this->title = 'Alterar Relatório: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Relatórios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Alterar';

?>

<div class="relatorio-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
