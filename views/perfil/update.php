<?php

$this->title = 'Alterar Perfil: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Perfis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Alterar';

?>

<div class="perfil-update">

    <?= $this->render('_form', [
        'model' => $model,
        'complemento' => $complemento,
        'menuConsulta' => $menuConsulta,
        'menuPainel' => $menuPainel,
        'menuRelatorio' => $menuRelatorio
    ]) ?>

</div>
