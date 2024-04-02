<?php

use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\helpers\Html;
use app\lists\TemplateEmailList;

$css = <<<CSS
        
    .badge-default.tags
    {
        color: #555555;
        background: #f5f5f5;
        border: 1px solid #ccc;
        border-radius: 4px;
        cursor: default;
        margin: 5px 0 0 6px;
        padding: 2px 5px;
    }
        
CSS;

$this->registerCss($css);

if($model->id && in_array($model->tipo, TemplateEmailList::getDataTiposDisabled()))
{
    $tipos = [$model->tipo => $model->getTipos()];
}
else
{
    $tipos = TemplateEmailList::getDataTiposEnabled();
}

?>

<div class="perfil-form mb-4">

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
                        'columnOptions' => ['colspan' => 8],
                    ],
                    'tipo' =>
                    [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => $tipos,
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
                    'tags' =>
                    [
                        'type'=>Form::INPUT_WIDGET, 
                        'widgetClass' => '\kartik\select2\Select2',
                        'options' => 
                        [
                            'data' => TemplateEmailList::$tags,
//                            'value' => 'Logo - BP1', 
                            'options' => ['multiple' => true],
                            'pluginOptions' => 
                            [
                                'tokenSeparators' => [',', ' '],
                                'tags' => true
                            ],
                        ],
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
                    'html' =>
                    [
                        'type'=>Form::INPUT_WIDGET, 
                        'widgetClass' => '\dosamigos\ckeditor\CKEditor',
                        'options' => 
                        [
                            'options' => ['rows' => 6],
                            'preset' => 'advanced'
                        ],
                    ],
                ],
            ]); ?>
            
        </div>
    
    <?php ActiveForm::end(); ?>

</div>