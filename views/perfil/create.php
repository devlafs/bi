<?php

$this->title = 'Inserir Perfil';
$this->params['breadcrumbs'][] = ['label' => 'Perfis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="perfil-create">

    <?= $this->render('_form', [
        'model' => $model,
        'complemento' => $complemento,
        'menuConsulta' => $menuConsulta,
        'menuPainel' => $menuPainel,
        'menuRelatorio' => $menuRelatorio
    ]) ?>

</div>
