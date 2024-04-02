<?php

use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\helpers\Html;
use kartik\color\ColorInput;
use kartik\file\FileInput;

$zoom = [];

for($i = 1; $i <= 20; $i++)
{
    $zoom["{$i}"] = $i;
}

?>

<div class="mapa-form">

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
                        'columnOptions' => ['colspan' => 4],
                    ],
                    'latitude' =>
                    [
                        'type' => Form::INPUT_TEXT,
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'longitude' =>
                    [
                        'type' => Form::INPUT_TEXT,
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'zoom' =>
                    [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => $zoom,
                        'options' => ['prompt' => ''],
                        'columnOptions' => ['colspan' => 2],
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
                    'identificador' =>
                    [
                        'type' => Form::INPUT_TEXT,
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'corfundo_ativo' =>
                    [
                        'type' => Form::INPUT_WIDGET,
                        'widgetClass' => ColorInput::classname(),
                        'options' =>
                        [
                            'readonly' => true
                        ],
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'corfundo_inativo' =>
                    [
                        'type' => Form::INPUT_WIDGET,
                        'widgetClass' => ColorInput::classname(),
                        'options' =>
                        [
                            'readonly' => true
                        ],
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'corborda' =>
                    [
                        'type' => Form::INPUT_WIDGET,
                        'widgetClass' => ColorInput::classname(),
                        'options' =>
                        [
                            'readonly' => true
                        ],
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
                'columns' => 1,
                'attributes' =>
                [
                    'file' =>
                    [
                        'type' => Form::INPUT_WIDGET,
                        'widgetClass' => FileInput::classname(),
                        'options' => 
                        [
                            'pluginOptions' => 
                            [

                                'allowedFileExtensions' => ['json'],
                            ]
                        ]
                    ]
                ],
            ]); ?>

        </div>
    
    <?php ActiveForm::end(); ?>

</div>
