<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Usuário: ' . $model->nomeResumo;
$this->params['breadcrumbs'][] = ['label' => 'Usuários', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$beeIntegration = Yii::$app->params['beeIntegration'];

if($beeIntegration)
{
    $departamento = ($model->usuarioDepartamento && $model->usuarioDepartamento[0]->departamento) ? $model->usuarioDepartamento[0]->departamento->nome : '';
    $model->departamento = $departamento;
}

?>

<div class="usuario-view">

    <p class="text-right">

        <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), (!empty(Yii::$app->request->referrer)) ? Yii::$app->request->referrer : ['index'], ['class' => 'btn btn-default']) ?>

        <?php if(Yii::$app->permissaoGeral->can('usuario', 'update')) : ?>

            <?= Html::a('<i class="bp-edit"></i> ' . \Yii::t('app', 'view.alterar'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?php endif; ?>        

    </p>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
        [
            'nome',
            'nomeResumo',
            'login',
            'email:email',
            'celular',
            [
                'label' => 'Perfil',
                'value' => ($model->perfil) ? $model->perfil->nome : ''
            ],
            'departamento',
            'cargo',
            'acesso_bi:boolean',
            'obs:ntext',
        ],
    ]) ?>

</div>