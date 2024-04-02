<?php

use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Indicador;
use app\models\IndicadorCampo;

$js = <<<JS
        
    $(function() 
    {   
        var _language =
        {
            errorLoading: function () {
              return 'Os resultados não puderam ser carregados.';
            },
            inputTooLong: function (args) {
              var overChars = args.input.length - args.maximum;

              var message = 'Apague ' + overChars + ' caracter';

              if (overChars != 1) {
                    message += 'es';
              }

              return message;
            },
            inputTooShort: function (args) {
              var remainingChars = args.minimum - args.input.length;

              var message = 'Digite ' + remainingChars + ' ou mais caracteres';

              return message;
            },
            loadingMore: function () {
              return 'Carregando mais resultados…';
            },
            maximumSelected: function (args) {
              var message = 'Você só pode selecionar ' + args.maximum + ' ite';

              if (args.maximum == 1) {
                message += 'm';
              } else {
                message += 'ns';
              }

              return message;
            },
            noResults: function () {
              return 'Nenhum resultado encontrado';
            },
            searching: function () {
              return 'Buscando…';
            }
        };
        
        $(".selectpicker-id_indicador").select2(
        {
            theme: "bp1",
            placeholder: "",
            language: _language
        });
        
        $(".selectpicker-id_campo").select2(
        {
            theme: "bp1",
            placeholder: "",
            language: _language
        });
        
        $(".selectpicker-id_indicador").change(function()
        {
            var _value = $(this).val();

            jQuery.ajax({
                url: '/mapa-campo/load-campos?id=' + _value,
                dataType: 'json',
                success: function (_success) 
                {
                    console.log(_success);
        
                    $(".selectpicker-id_campo").empty().select2(
                    {
                        theme: "bp1",
                        placeholder: "",
                        language: _language,
                        data: _success
                    });
        
                    $(".selectpicker-id_campo").trigger("change");
                },
                beforeSend: function ()
                {
                    $('.div-loading').addClass("loading");
                },
                complete: function () 
                {
                    setTimeout(function() { $('.div-loading').removeClass("loading");}, 300);
                }
            });
        });
    });
        
JS;

$this->registerJs($js);

?>

<div class="mapa-campo-form">

    <?php $form = ActiveForm::begin(); ?>
    
        <p class="text-right">

            <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), ['index', 'id' => $model->id_mapa],
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
            
            <div class="mb-3">

                <?= Form::widget(
                [
                    'model' => $model,
                    'form' => $form,
                    'columns' => 12,
                    'attributes' =>
                    [
                        'id_indicador' =>
                        [
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'items' => ArrayHelper::map(Indicador::find()->andWhere([
                                'is_ativo' => true,
                                'is_excluido' => false,
                            ])->orderBy('nome ASC')->all(), 'id', 'nome'),
                            'options' => 
                            [
                                'prompt' => '', 
                                'class' => 'selectpicker-id_indicador',
                            ], 
                            'columnOptions' => ['colspan' => 4],
                        ],
                        'id_campo' =>
                        [
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'items' => ($model->id_indicador) ? ArrayHelper::map(IndicadorCampo::find()->andWhere([
                                'is_ativo' => true,
                                'is_excluido' => false,
                            ])->orderBy('nome ASC')->all(), 'id', 'nome') : [],
                            'options' => 
                            [
                                'prompt' => '', 
                                'class' => 'selectpicker-id_campo',
                            ], 
                            'columnOptions' => ['colspan' => 4],
                        ],
                        'tag' => 
                        [
                            'type' => Form::INPUT_TEXT,
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

            </div>
            
        </div>
    
    <?php ActiveForm::end(); ?>

</div>