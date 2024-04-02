<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Perfil: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Perfis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="perfil-view">

    <p class="text-right">

        <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), (!empty(Yii::$app->request->referrer)) ? Yii::$app->request->referrer : ['index'], ['class' => 'btn btn-default']) ?>

        <?php if(Yii::$app->permissaoGeral->can('perfil', 'update')) : ?>

            <?= Html::a('<i class="bp-edit"></i> ' . \Yii::t('app', 'view.alterar'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?php endif; ?>        

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
        [
            'nome',
            'descricao',
            'acesso_bi:boolean',
            'is_admin:boolean',
            [
                'label' => 'Usuários',
                'format' => 'raw',
                'value' => $model->getStringUsuarios()
            ]
        ],
    ]) ?>

</div>
