<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\lists\FrequenciaList;

$this->title = 'Email: ' . $model->assunto;
$this->params['breadcrumbs'][] = ['label' => 'Emails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$t = ($model->id_consulta) ? 'consulta' : 'painel';

?>

<div class="email-view">

    <p class="text-right">

        <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), (!empty(Yii::$app->request->referrer)) ? Yii::$app->request->referrer : ['index', 't' => $t], ['class' => 'btn btn-default']) ?>

        <?php if(Yii::$app->permissaoGeral->can('email', 'update')) : ?>

            <?= Html::a('<i class="bp-edit"></i> ' . \Yii::t('app', 'view.alterar'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?php endif; ?>        

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'assunto',
            [
                'label' => ($model->view == $model::VIEW_CONSULTA) ? 'Consulta' : "Painel",
                'value' => ($model->view == $model::VIEW_CONSULTA) ? $model->consulta->nome : $model->painel->nome,
            ],
            [
                'label' => 'Destinatário',
                'format' => 'raw',
                'value' => $model->getDestinatario()
            ],
            [
                'attribute' => 'frequencia',
                'value' => isset($model::$frequencias[$model->frequencia]) ? $model::$frequencias[$model->frequencia] : ''
            ],
            [
                'label' => 'Dia',
                'value' => $model->getDiaEnvio()
            ],
            [
                'attribute' => 'hora',
                'value' => FrequenciaList::getNomeHora($model->hora)
            ],
            'send_pdf:boolean',
            [
                'attribute' => 'sent_at',
                'value' => ($model->sent_at) ? Yii::$app->formatter->asDate($model->sent_at, 'php:d/m/Y H:i') : ''
            ],
            [
                'attribute' => 'log',
                'format' => 'raw',
                'value' => $model->getLogsEnvio()
            ]
        ],
    ]) ?>

</div>
