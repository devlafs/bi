<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\magic\PeriodicidadeMagic;

$this->title = 'Cubo: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Cubos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="ind-indicador-view">

    <p class="text-right">

        <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), (!empty(Yii::$app->request->referrer)) ? Yii::$app->request->referrer : ['index'], ['class' => 'btn btn-default']) ?>

        <?php if(Yii::$app->permissaoGeral->can('indicador', 'update')) : ?>

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
                'attribute' => 'periodicidade',
                'value' => PeriodicidadeMagic::getPeriodo($model->periodicidade)
            ],
            'hora_inicial',
            [
                'attribute' => 'executed_at',
                'format' => 'raw',
                'value' => Yii::$app->formatter->asDate($model->executed_at, 'php:d/m/Y H:i')
            ],
            [
                'attribute' => 'qtd_dados',
                'value' => $model->getQuantidadeDados()
            ],
            [
                'attribute' => 'is_ativo',
                'value' => ($model->is_ativo) ? 'Ativo' : 'Inativo'
            ],
            [
                'label' => 'Consultas',
                'format' => 'raw',
                'value' => $model->getStringConsultas()
            ],
            [
                'label' => 'Histórico',
                'format' => 'raw',
                'value' => $model->getLogsCarga()
            ],
            'sql'
        ],
    ]) ?>

</div>
