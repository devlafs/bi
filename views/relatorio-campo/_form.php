<?php

use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\helpers\Html;
use kartik\switchinput\SwitchInput;
use app\models\RelatorioCampo;

?>

<div class="ind-indicador-form">

    <?php $form = ActiveForm::begin(['id' => 'form-fields', 'enableClientValidation' => false]); ?>

    <p class="text-right">

        <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), (!empty(Yii::$app->request->referrer)) ? Yii::$app->request->referrer : ['index', 'id' => $model->id_indicador],
            [
                'class' => 'btn btn-default pull-right',
                'style' => 'margin-left: 10px;'
            ]); ?>

        <?= Html::submitButton('Salvar e Pré-visualizar',
            [
                'class' => 'btn btn-primary',
            ]); ?>

    </p>

    <div class="card p-3">

        <div class="mb-3">

            <?= Form::widget(
                [
                    'model' => $model,
                    'form' => $form,
                    'columns' => 12,
                    'attributes' =>
                        [
                            'ordem' =>
                                [
                                    'type' => Form::INPUT_TEXT,
                                    'columnOptions' => ['colspan' => 2],
                                ],
                            'nome' =>
                                [
                                    'type' => Form::INPUT_TEXT,
                                    'columnOptions' => ['colspan' => 6],
                                ],
                            'tipo' =>
                                [
                                    'type' => Form::INPUT_DROPDOWN_LIST,
                                    'items' => RelatorioCampo::$tipos,
                                    'options' => ['prompt' => ''],
                                    'columnOptions' => ['colspan' => 4],
                                ],
                        ],
                ]); ?>

            <?= Form::widget(
                [
                    'model' => $model,
                    'form' => $form,
                    'columns' => 12,
                    'attributes' =>
                        [
                            'options' =>
                                [
                                    'type' => Form::INPUT_TEXTAREA,
                                    'options' => ['rows' => 5],
                                    'columnOptions' => ['colspan' => 12],
                                ],
        //                    'function' =>
        //                        [
        //                            'type' => Form::INPUT_TEXTAREA,
        //                            'options' => ['rows' => 5],
        //                            'columnOptions' => ['colspan' => 6],
        //                        ]
                        ],
                ]); ?>

            <?= Form::widget(
                [
                    'model' => $model,
                    'form' => $form,
                    'columns' => 1,
                    'attributes' =>
                        [
                            'descricao' =>
                                [
                                    'type' => Form::INPUT_TEXTAREA,
                                    'options' => ['rows' => 3],
                                ]
                        ],
                ]); ?>

            <?= Form::widget(
                [
                    'model' => $model,
                    'form' => $form,
                    'attributes' =>
                        [
                            'is_ativo' =>
                                [
                                    'type' => Form::INPUT_WIDGET,
                                    'widgetClass' => SwitchInput::classname(),
                                    'columnOptions' => ['colspan' => 3],
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
                                        ]
                                ],
                        ],
                ]); ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
