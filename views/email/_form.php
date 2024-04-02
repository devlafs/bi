<?php

use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\helpers\Html;
use app\lists\FrequenciaList;
use yii\helpers\ArrayHelper;
use kartik\switchinput\SwitchInput;
use app\models\TemplateEmail;

$user_id = ($usuario) ? $usuario->id : null;
$user_name = ($usuario) ? $usuario->nomeResumo : '';

$js = <<<JS
        
    function updateFields()
    {
        $('#email-reload_form').val(1);
        $('#form-fields').submit();
    };
        
    $(document).delegate('#email-tipo_destinatario', 'change', updateFields);
        
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
        
        $(".selectpicker-field").select2(
        {
            theme: "bp1",
            placeholder: "",
            language: _language
        });

        $(".selectpicker-id_perfil").select2(
        {
            theme: "bp1",
            placeholder: "",
            language: _language
        });

        $(".selectpicker-departamento").select2(
        {
            theme: "bp1",
            placeholder: "",
            language: _language
        });
        
        $(".selectpicker-id_usuario").select2(
        {
            theme: "bp1",
            initialValueText: "{$user_name}",
            placeholder: "",
            language: _language,
            minimumInputLength: 3,
            ajax: 
            {
                url: "/email/load-users",
                dataType: "JSON",
                data: function(term) { return { q: term.term }},
                results: function (data) 
                {
                    return 
                    {
                        results: $.map(data, function (user) 
                        {
                            return {
                                text: user.text,
                                id: user.id
                            }
                        })
                    };
                }
            },
            escapeMarkup: function (markup) 
            {
                return markup;
            }
        });
    });
        
    $(document).delegate('#email-frequencia', 'change', function() 
    {
        var _val = $(this).val();
        
        $("#email-dia_mes").val("");        
        $("#email-dia_semana").val("");        
        
        switch(_val)
        {
            case '1':
                $("#email-dia_mes").prop("disabled", true);
                $("#email-dia_semana").prop("disabled", true);
                break;
            case '2':
                 $("#email-dia_mes").prop("disabled", true);
                $("#email-dia_semana").prop("disabled", false);
               break;
            case '3':
                $("#email-dia_mes").prop("disabled", false);
                $("#email-dia_semana").prop("disabled", true);
                break;
            default:
                $("#email-dia_mes").prop("disabled", true);
                $("#email-dia_semana").prop("disabled", true);
        }
    });
        
JS;

$this->registerJs($js);

if(!$model->tipo_destinatario)
{
    if($model->id_perfil)
    {
        $model->tipo_destinatario = $model::TIPO_PERFIL;
    }
    elseif($model->id_usuario)
    {
        $model->tipo_destinatario = $model::TIPO_USUARIO;
    }
    elseif($model->departamento || $model->id_departamento)
    {
        $model->tipo_destinatario = $model::TIPO_DEPARTAMENTO;
    }
    elseif($model->email)
    {
        $model->tipo_destinatario = $model::TIPO_EMAIL;
    }
}

$field = ($t == 'consulta') ? 'id_consulta' : 'id_painel';

$dest = 'id_perfil';

$data_dest = 
[
    'type' => Form::INPUT_DROPDOWN_LIST,
    'items' => ArrayHelper::map($perfis, 'id', 'nome'),
    'options' => 
    [
        'prompt' => '', 
        'class' => 'selectpicker-id_perfil',
    ], 
    'columnOptions' => ['colspan' => 3],
];

switch($model->tipo_destinatario)
{
    case $model::TIPO_PERFIL:
        
        $dest = 'id_perfil';
        
        $data_dest = 
        [
            'type' => Form::INPUT_DROPDOWN_LIST,
            'items' => ArrayHelper::map($perfis, 'id', 'nome'),
            'options' => 
            [
                'prompt' => '', 
                'class' => 'selectpicker-id_perfil',
            ], 
            'columnOptions' => ['colspan' => 3],
        ];
        
        break;

    case $model::TIPO_USUARIO:
        
        $dest = 'id_usuario';
        
        $data_dest = 
        [
            'type' => Form::INPUT_DROPDOWN_LIST,
            'items' => [$user_id => $user_name],
            'options' => 
            [
                'prompt' => '', 
                'class' => 'selectpicker-id_usuario',
            ],
            'columnOptions' => ['colspan' => 3],
        ];
        
        break;
    
    case $model::TIPO_DEPARTAMENTO:
        
        $beeIntegration = Yii::$app->params['beeIntegration'];
        
        $dest = ($beeIntegration) ? 'id_departamento' : 'departamento';
        
        $data_dest = 
        [
            'type' => Form::INPUT_DROPDOWN_LIST,
            'items' => ArrayHelper::map($departamentos, 'id', 'nome'),
            'options' => 
            [
                'prompt' => '', 
                'class' => 'selectpicker-departamento',
            ],
            'columnOptions' => ['colspan' => 3],
        ];
        
        break;
    
    case $model::TIPO_EMAIL:
        
        $dest = 'email';
        
        $data_dest = 
        [
            'type' => Form::INPUT_TEXT,
            'columnOptions' => ['colspan' => 3],
        ];
        
        break;
}

?>

<div class="email-form">

    <?php $form = ActiveForm::begin(['id' => 'form-fields', 'enableClientValidation' => false]); ?>
    
        <p class="text-right">

            <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), (!empty(Yii::$app->request->referrer)) ? Yii::$app->request->referrer : ['index', 't' => $t],
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
            
            <?= $form->field($model, 'reload_form')->hiddenInput()->label(false); ?>

            <?= Form::widget(
            [
                'model' => $model,
                'form' => $form,
                'columns' => 12,
                'attributes' =>
                [
                    'assunto' =>
                    [
                        'type' => Form::INPUT_TEXT,
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'id_template' =>
                    [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => ArrayHelper::map(TemplateEmail::find()->all(), 'id', 'nome'),
                        'options' => ['prompt' => 'Padrão'], 
                        'columnOptions' => ['colspan' => 2],
                    ],
                    $field =>
                    [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => ArrayHelper::map($data, 'id', 'nome'),
                        'options' => 
                        [
                            'prompt' => '', 
                            'class' =>  'selectpicker-field'
                        ], 
                        'columnOptions' => ['colspan' => 2],
                    ],
                    'tipo_destinatario' =>
                    [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => $model::$tipos,
                        'options' =>
                        [
                            'prompt' => '',
                        ],
                        'columnOptions' => ['colspan' => 2],
                    ],
                    $dest => $data_dest
                ],
            ]); ?>
            
            <?= Form::widget(
            [
                'model' => $model,
                'form' => $form,
                'columns' => 12,
                'attributes' =>
                [
                    'frequencia' =>
                    [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => $model::$frequencias,
                        'options' => ['prompt' => ''],
                        'columnOptions' => ['colspan' => 3],
                    ],
                    'dia_mes' =>
                    [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => FrequenciaList::getDiasMes(),
                        'options' =>
                        [
                            'prompt' => '',
                            'disabled' => (!$model->frequencia || $model->frequencia != $model::FREQUENCIA_MENSAL)
                        ],
                        'columnOptions' => ['colspan' => 3],                   
                    ],
                    'dia_semana' =>
                    [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => FrequenciaList::getDataSemanal(),
                        'options' =>
                        [
                            'prompt' => '',
                            'disabled' => (!$model->frequencia || $model->frequencia != $model::FREQUENCIA_SEMANAL)
                        ],
                        'columnOptions' => ['colspan' => 3],                   
                    ],
                    'hora' =>
                    [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => FrequenciaList::getHoras(),
                        'options' => ['prompt' => ''],
                        'columnOptions' => ['colspan' => 3],
                    ],
                ],
            ]); ?>
            
            <?php if($t == 'consulta') : ?>
            
                <?= Form::widget(
                [
                    'model' => $model,
                    'form' => $form,
                    'columns' => 1,
                    'attributes' =>
                    [
                        'send_pdf' =>
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
            
            <hr>
            
        </div>
    
    <?php ActiveForm::end(); ?>

</div>