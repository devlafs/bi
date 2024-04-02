<?php

$this->title = 'Inserir Metadado';
$this->params['breadcrumbs'][] = ['label' => 'Metadados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="metadado-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
