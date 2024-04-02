<?php

$this->title = 'Inserir Email';
$this->params['breadcrumbs'][] = ['label' => 'Emails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$usuario = null;

if(!$model->tipo_destinatario)
{
    $model->tipo_destinatario = $model::TIPO_EMAIL;
}

?>

<div class="email-create">

    <?= $this->render('_form', compact('model', 't', 'data', 'perfis', 'departamentos', 'usuario')) ?>

</div>
