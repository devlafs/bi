<?php

$this->title = 'Inserir Template';
$this->params['breadcrumbs'][] = ['label' => 'Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$usuario = null;

?>

<div class="template-create">

    <?= $this->render('_form', compact('model')) ?>

</div>
