<?php

use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\helpers\Html;

$model->perfil_nome = ($model->perfil_id) ? $model->perfil->nome : '';

$beeIntegration = Yii::$app->params['beeIntegration'];

if($beeIntegration)
{
    $departamento = ($model->usuarioDepartamento && $model->usuarioDepartamento[0]->departamento) ? $model->usuarioDepartamento[0]->departamento->nome : '';
    $model->departamento = $departamento;
}

?>

<div class="usuario-form">

    <?php $form = ActiveForm::begin(); ?>
    
        <p class="text-right">

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
                    'nome' =>
                    [
                        'type' => Form::INPUT_TEXT,
                        'columnOptions' => ['colspan' => 8],
                    ],
                    'nomeResumo' =>
                    [
                        'type' => Form::INPUT_TEXT,
                        'columnOptions' => ['colspan' => 4],
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
                    'perfil_nome' =>
                    [
                        'type' => Form::INPUT_STATIC,
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'departamento' =>
                    [
                        'type' => Form::INPUT_STATIC,
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'cargo' =>
                    [
                        'type' => Form::INPUT_STATIC,
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'login' =>
                    [
                        'type' => Form::INPUT_STATIC,
                        'columnOptions' => ['colspan' => 3],
                    ],
                ],
            ]); ?>
            
            <hr>
            
            <?= Form::widget(
            [
                'model' => $model,
                'form' => $form,
                'columns' => 12,
                'attributes' =>
                [
                    'celular' =>
                    [
                        'type' => Form::INPUT_WIDGET, 
                        'widgetClass' => yii\widgets\MaskedInput::classname(),
                        'options' => ['mask' => '(99) 99999-9999'],
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'email' =>
                    [
                        'type' => Form::INPUT_TEXT,
                        'options' => ['type' => 'email'],
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'nova_senha' =>
                    [
                        'type' => Form::INPUT_PASSWORD,
                        'columnOptions' => ['colspan' => 3],
                    ],     
                    'repetir_nova_senha' =>
                    [
                        'type' => Form::INPUT_PASSWORD,
                        'columnOptions' => ['colspan' => 3],
                    ],
                    
                ],
            ]); ?>
            
            <?= Form::widget(
            [
                'model' => $model,
                'form' => $form,
                'columns' => 1,
                'attributes' =>
                [
                    'obs' =>
                    [
                        'type' => Form::INPUT_TEXTAREA,
                        'options' => ['rows' => 3],
                    ]
                ],
            ]); ?>

        </div>
    
    <?php ActiveForm::end(); ?>

</div>