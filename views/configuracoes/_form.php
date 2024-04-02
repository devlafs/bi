<?php

use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\helpers\Html;
use app\lists\ConfiguracaoGraficoList;

$model->label_view = ConfiguracaoGraficoList::getView($model->view);
$model->label_tipo = ConfiguracaoGraficoList::getType($model->tipo);
$is_serie = $model->is_serie;
$model->label_serie = ($model->is_serie) ? \Yii::t('app', 'view.sim') : \Yii::t('app', 'view.nao');

?>

<div class="grafico-configuracao-form">

    <?php $form = ActiveForm::begin(); ?>

        <p class="text-right">

            <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), (!empty(Yii::$app->request->referrer)) ? Yii::$app->request->referrer : ['index'],
            [
                'class' => 'btn btn-default pull-right',
                'style' => 'margin-left: 10px;'
            ]); ?>

            <?= Html::submitButton( \Yii::t('app', 'view.salvar'),
            [
                'class' => 'btn btn-primary',
            ]); ?>

        </p>
        
        <div class="card p-3">

            <?= Form::widget(
            [
                'model' => $model,
                'form' => $form,
                'columns' => 12,
                'attributes' =>
                [
                    'label_view' => 
                    [
                        'type' => Form::INPUT_HIDDEN_STATIC,
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'label_tipo' => 
                    [
                        'type' => Form::INPUT_HIDDEN_STATIC,
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'label_serie' => 
                    [
                        'type' => Form::INPUT_HIDDEN_STATIC,
                        'columnOptions' => ['colspan' => 3],
                    ]
                ],
            ]); ?>

            <?= Form::widget(
            [
                'model' => $model,
                'form' => $form,
                'columns' => 12,
                'attributes' =>
                [
                    'data' => 
                    [
                        'type' => Form::INPUT_TEXTAREA,
                        'options' => ['rows' => 5],
                    ]
                ],
            ]); ?>
            
            <?php if($is_serie) : ?>
            
                <?= Form::widget(
                [
                    'model' => $model,
                    'form' => $form,
                    'columns' => 12,
                    'attributes' =>
                    [
                        'data_serie' => 
                        [
                            'type' => Form::INPUT_TEXTAREA,
                            'options' => ['rows' => 5],
                        ]
                    ],
                ]); ?>
            
                <?= Form::widget(
                [
                    'model' => $model,
                    'form' => $form,
                    'columns' => 12,
                    'attributes' =>
                    [
                        'data_timeline' => 
                        [
                            'type' => Form::INPUT_TEXTAREA,
                            'options' => ['rows' => 3],
                        ]
                    ],
                ]); ?>
            
            <?php endif; ?>
            
        </div>

    <?php ActiveForm::end(); ?>
    
</div>
