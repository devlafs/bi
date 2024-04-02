<?php

$this->title = 'Inserir Mapa';
$this->params['breadcrumbs'][] = ['label' => 'Mapas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="mapa-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
