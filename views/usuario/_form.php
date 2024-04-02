<?php

use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\AdminPerfil;
use kartik\switchinput\SwitchInput;

$beeIntegration = Yii::$app->params['beeIntegration'];

if($beeIntegration)
{
    $model->perfil_id = ($model->perfil_id) ? $model->perfil->nome : '';
    $departamento = ($model->usuarioDepartamento && $model->usuarioDepartamento[0]->departamento) ? $model->usuarioDepartamento[0]->departamento->nome : '';
    $model->departamento = $departamento;
}

?>

<div class="usuario-form">

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
                        'columnOptions' => ['colspan' => 6],
                    ],
                    'nomeResumo' =>
                    [
                        'type' => Form::INPUT_TEXT,
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'perfil_id' =>
                    [
                        'type' => ($beeIntegration && $model->id) ? Form::INPUT_STATIC : Form::INPUT_DROPDOWN_LIST,
                        'items' => ArrayHelper::map(AdminPerfil::find()->andWhere(['is_ativo' => TRUE,
                            'is_excluido' => FALSE])->orderBy('nome ASC')->all(), 'id', 'nome'),
                        'options' => 
                        [
                            'prompt' => ''
                        ],
                        'columnOptions' => ['colspan' => 3],
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
                        'type' => ($model->id) ? Form::INPUT_STATIC : Form::INPUT_TEXT,
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'email' =>
                    [
                        'type' => Form::INPUT_TEXT,
                        'options' => ['type' => 'email'],
                        'columnOptions' => ['colspan' => 3],
                    ],
                    ($model->id) ? 'nova_senha' : 'senha' =>
                    [
                        'type' => Form::INPUT_PASSWORD,
                        'columnOptions' => ['colspan' => 3],
                    ],
                    ($model->id) ? 'repetir_nova_senha' : 'repetir_senha' =>
                    [
                        'type' => Form::INPUT_PASSWORD,
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
                        'columnOptions' => ['colspan' => 4],
                    ],
                    'departamento' =>
                    [
                        'type' => ($beeIntegration && $model->id) ? Form::INPUT_STATIC : Form::INPUT_TEXT,
                        'columnOptions' => ['colspan' => 4],
                    ],
                    'cargo' =>
                    [
                        'type' => ($beeIntegration && $model->id) ? Form::INPUT_STATIC : Form::INPUT_TEXT,
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
                    'acesso_bi' =>
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
                        ]
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