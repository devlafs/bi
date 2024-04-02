<?php

use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\helpers\Html;
use kartik\file\FileInput;
use kartik\switchinput\SwitchInput;

?>

<div class="ind-indicador-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

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
                    'nome' => 
                    [
                        'type' => Form::INPUT_TEXT,
                        'columnOptions' => ['colspan' => 12],
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


                    'descricao' =>
                    [
                        'type' => Form::INPUT_TEXTAREA,
                        'options' => ['rows' => 3],
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
                            'file' =>
                                [
                                    'label' => ($model->id) ? 'Atualizar arquivo' : 'Arquivo',
                                    'type' => Form::INPUT_WIDGET,
                                    'widgetClass' => FileInput::classname(),
                                    'options' =>
                                        [
                                            'pluginOptions' =>
                                                [
                                                    'showPreview' => false,
                                                    'browseClass' => 'btn btn-success',
                                                    'browseLabel' => 'Pesquisar',
                                                    'showUpload' => false,
                                                    'showCancel' => false,
                                                    'removeClass' => 'btn btn-success',
                                                    'removeLabel' => 'Excluir'
                                                ]
                                        ]
                                ]
                        ],
                ]); ?>

            <?php if(!$model->id) : ?>

                <?= Form::widget(
                    [
                        'model' => $model,
                        'form' => $form,
                        'columns' => 1,
                        'attributes' =>
                            [
                                'novo_indicador' =>
                                    [
                                        'type' => Form::INPUT_WIDGET,
                                        'widgetClass' => SwitchInput::classname(),
                                        'options' =>
                                            [
                                                'pluginOptions' =>
                                                    [
                                                        'size' => 'mini',
                                                        'onText' => 'Sim',
                                                        'offText' => 'Não',
                                                        'onColor' => 'success',
                                                        'offColor' => 'danger',
                                                    ]
                                            ],
                                    ],
                            ],
                    ]); ?>

            <?php endif; ?>

            <?= Form::widget(
                [
                    'model' => $model,
                    'form' => $form,
                    'columns' => 1,
                    'attributes' =>
                        [
                            'is_incremental' =>
                                [
                                    'hint' => 'Se incrimental, a estrutura do arquivo deverá ser a mesma do arquivo anterior',
                                    'type' => Form::INPUT_WIDGET,
                                    'widgetClass' => SwitchInput::classname(),
                                    'options' =>
                                        [
                                            'pluginOptions' =>
                                                [
                                                    'size' => 'mini',
                                                    'onText' => 'Sim',
                                                    'offText' => 'Não',
                                                    'onColor' => 'success',
                                                    'offColor' => 'danger',
                                                ]
                                        ],
                                ],
                        ],
                ]); ?>
            
        </div>

    <?php ActiveForm::end(); ?>
    
</div>
