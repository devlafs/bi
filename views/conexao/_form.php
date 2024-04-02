<?php

use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\helpers\Html;

?>

<div class="ind-indicador-form">

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
                    'nome' => 
                    [
                        'type' => Form::INPUT_TEXT,
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'tipo' =>
                    [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => 
                        [
                            'mysql' => 'MYSQL',
                            'oracle' => 'ORACLE',
                            'pgsql' => 'POSTGRESQL',
                            'sqlserver' => 'SQLSERVER',
                            'firebird' => 'FIREBIRD'
                        ],
                        'options' =>
                        [
                            'prompt' => ''
                        ],
                        'columnOptions' => ['colspan' => 2],
                    ],
                    'host' =>
                    [
                        'type' => Form::INPUT_TEXT,
                        'columnOptions' => ['colspan' => 4],
                    ],
                    'porta' =>
                    [
                        'type' => Form::INPUT_TEXT,
                        'columnOptions' => ['colspan' => 1],
                    ],
                    'database' =>
                    [
                        'type' => Form::INPUT_TEXT,
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


                    'login' => 
                    [
                        'type' => Form::INPUT_TEXT,
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'senha' => 
                    [
                        'type' => Form::INPUT_PASSWORD,
                        'columnOptions' => ['colspan' => 3],
                    ],
                ],
            ]); ?>
            
        </div>

    <?php ActiveForm::end(); ?>
    
</div>
