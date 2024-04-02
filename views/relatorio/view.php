<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\magic\PeriodicidadeMagic;

$this->title = 'Relatório: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Relatórios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="ind-relatorio-view">

    <p class="text-right">

        <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), (!empty(Yii::$app->request->referrer)) ? Yii::$app->request->referrer : ['index'], ['class' => 'btn btn-default']) ?>

        <?php if(Yii::$app->permissaoGeral->can('relatorio', 'update')) : ?>

            <?= Html::a('<i class="bp-edit"></i> ' . \Yii::t('app', 'view.alterar'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?php endif; ?>        

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
        [
            'nome',
            [
                'attribute' => 'id_conexao',
                'value' => $model->conexao->nome
            ],
            'descricao',
            [
                'attribute' => 'is_ativo',
                'value' => ($model->is_ativo) ? 'Ativo' : 'Inativo'
            ],
            'sql'
        ],
    ]) ?>

</div>
