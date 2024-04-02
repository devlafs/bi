<?php

$this->title = 'Inserir Cubo';
$this->params['breadcrumbs'][] = ['label' => 'Cubos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="ind-indicador-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
