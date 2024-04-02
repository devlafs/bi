<?php

use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\helpers\Html;
use app\magic\ResultMagic;

$js = <<<JS
      
    function updateFields()
    {
        $('#indicadorcampo-reload_form').val(1);
        $('#form-fields').submit();
    };
        
    $(document).delegate('#indicadorcampo-tipo', 'change', updateFields);
        
JS;

$this->registerJs($js);

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

                <?= $form->field($model, 'reload_form')->hiddenInput()->label(false); ?>
                
                <?= Form::widget(
                [
                    'model' => $model,
                    'form' => $form,
                    'columns' => 12,
                    'attributes' =>
                    [
                        'ordem' =>
                        [
                            'type' => Form::INPUT_STATIC,
                            'columnOptions' => ['colspan' => 1],
                        ],     
                        'nome' => 
                        [
                            'type' => Form::INPUT_TEXT,
                            'columnOptions' => ['colspan' => 3],
                        ],
                        'tipo' => 
                        [
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'items' => ['texto' => 'Texto', 'data' => 'Data', 'valor' => 'Valor'],
                            'columnOptions' => ['colspan' => 2],
                        ],
                        'link' =>
                        [
                            'label' => "Link externo <i class='fa fa-question-circle' style='font-size: 10px;' title='Exemplo: http://url/acao?codigo={valor}'></i>",
                            'type' => Form::INPUT_TEXT,
                            'columnOptions' => ['colspan' => 6],
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
                        'descricao' =>
                        [
                            'type' => Form::INPUT_TEXTAREA,
                            'options' => ['rows' => 3],
                        ]
                    ],
                ]); ?>
                
                <?php 
                
//                echo Form::widget(
//                [
//                    'model' => $model,
//                    'form' => $form,
//                    'columns' => 1,
//                    'attributes' =>
//                    [
//                        'agrupar_valor' =>
//                        [
//                            'type' => Form::INPUT_WIDGET, 
//                            'widgetClass' => SwitchInput::classname(),
//                            'options' =>
//                            [
//                                'pluginOptions' => 
//                                [
//                                    'size' => 'mini',
//                                    'onText' => 'Sim',
//                                    'offText' => 'Não',
//                                    'onColor' => 'success',
//                                    'offColor' => 'danger',
//                                ]
//                            ],
//                        ]
//                    ],
//                ]);
                    
                ?>
                
            </div>
            
            <div class="row pt-3" style="border-top: 1px solid #d8d8d8;" id="div-format">
                    
                <div class="col-sm-12">

                    <div class="form-group highlight-addon">

                        <div class="mr-0 ml-0">

                            <?= ($model->tipo) ? $this->render("/indicador-campo/_partials/" . $model->tipo, compact('form', 'model')) : ''; ?>

                        </div>

                    </div>

                </div>

            </div>
            
            <?php if($preview) : ?>
        
                <div class="row pt-3" style="border-top: 1px solid #d8d8d8;">
                    
                    <div class="col-sm-12">

                        <div class="form-group highlight-addon">

                            <label class="control-label">Pré-visualização do campo (10 primeiros):</label>

                            <div class="row" style="margin-right: 0px; margin-left: 0px;">
                                
                                <?php 
                                
                                    foreach($preview as $type => $dapre) : 

                                        $dapre_field = isset($dapre['x']) ? $dapre['x'] : null;
                                        
                                        if($dapre_field && !empty($dapre_field)) :
                                            
                                            $style = ($type === 'error') ? 'border: 1px solid red; color: red;' : 'border: 1px solid #177487; color: #177487;';
                                            $title = ($type === 'error') ? "title='{$preview['error']['x']}'" : '';

                                            ?>

                                            <span class="m-1 p-1" <?= $title ?> style="<?= $style ?> border-radius: 5px;">
                                                <?= ResultMagic::format($dapre_field, $model) ?>
                                            </span>

                                            <?php 
                                
                                        endif;
                                    
                                    endforeach; 
                                    
                                ?>
                                
                            </div>

                        </div>

                    </div>
            
                </div>

            <?php endif; ?>
            
        </div>
    
    <?php ActiveForm::end(); ?>

</div>