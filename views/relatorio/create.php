<?php

$this->title = 'Inserir Relatório';
$this->params['breadcrumbs'][] = ['label' => 'Relatórios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="ind-relatorio-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
