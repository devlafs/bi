<?php

$this->title = 'Configurações do Sistema';
$this->params['breadcrumbs'][] = $this->title

?>

<div class="geral-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
