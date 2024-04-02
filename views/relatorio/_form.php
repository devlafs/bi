<?php

use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Conexao;
use app\magic\PeriodicidadeMagic;

if($model->id) 
{
    $model->id_conexao = $model->conexao->nome;
}


?>

<div class="ind-relatorio-form">

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
                        'columnOptions' => ['colspan' => 4],
                    ],
                    'id_conexao' =>
                    [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => ArrayHelper::map(Conexao::find()->andWhere([
                            'is_ativo' => TRUE,
                            'is_excluido' => FALSE
                        ])->orderBy('nome ASC')->all(), 'id', 'nome'),
                        'options' => 
                        [
                            'prompt' => ''
                        ],
                        'columnOptions' => ['colspan' => 4],
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
                    'sql' =>
                    [
                        'type' => Form::INPUT_TEXTAREA,
                        'options' => ['rows' => 20],
                    ]
                ],
            ]); ?>

        </div>
    
    <?php ActiveForm::end(); ?>

</div>
