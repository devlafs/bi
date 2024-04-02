<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Metadado: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Metadados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="metadado-view">

    <p class="text-right">

        <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), (!empty(Yii::$app->request->referrer)) ? Yii::$app->request->referrer : ['index'], ['class' => 'btn btn-default']) ?>

        <?php if(Yii::$app->permissaoGeral->can('metadado', 'update')) : ?>

            <?= Html::a('<i class="bp-edit"></i> ' . \Yii::t('app', 'view.alterar'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?php endif; ?>        

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
        [
            'nome',
            'descricao',
            [
                'label' => "Arquivo",
                'format' => 'raw',
                'value' => Html::a("Clique para baixar", "/uploads/" . $model->caminho, ['target' => '_blank'])
            ],
            [
                'label' => 'Qtd. de Dados',
                'value' => $model->getQuantidadeDados()
            ],
            'is_incremental:boolean'
        ],
    ]) ?>

</div>
