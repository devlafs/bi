<?php

$this->title = 'Alterar Email: ' . $model->assunto;
$this->params['breadcrumbs'][] = ['label' => 'Emails', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->assunto, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Alterar';

?>

<div class="perfil-email">

    <?= $this->render('_form', compact('model', 't', 'data', 'perfis', 'departamentos', 'usuario')) ?>

</div>
