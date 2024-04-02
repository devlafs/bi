<?php

use app\models\Consulta;
use app\models\Painel;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\helpers\Html;
use kartik\switchinput\SwitchInput;
use app\models\Pasta;
use yii\helpers\ArrayHelper;
use app\models\RelatorioData;

$is_admin = Yii::$app->user->identity->perfil->is_admin;

$consultas = Consulta::find()->joinWith(['indicador'])->andWhere([
    'bpbi_consulta.is_ativo' => TRUE,
    'bpbi_consulta.is_excluido' => FALSE,
    'bpbi_indicador.is_ativo' => TRUE,
    'bpbi_indicador.is_excluido' => FALSE,
])->orderBy('bpbi_consulta.nome ASC')->all();

$paineis = Painel::find()->andWhere([
    'is_ativo' => TRUE,
    'is_excluido' => FALSE
])->orderBy('nome ASC')->all();

$relatorios = RelatorioData::find()->andWhere([
    'is_ativo' => TRUE,
    'is_excluido' => FALSE
])->orderBy('nome ASC')->all();

$css = <<<CSS
        
    .box-permission
    {
        border-top: 1px solid #cecece;
        padding: 10px;
    }

CSS;

$this->registerCss($css);

$js_accesso = <<<JS
        
    function()
    { 
        var _isadmin = '{$is_admin}';
        
        var _value_access = $(this).bootstrapSwitch('state'); 
        
        var _inputs = $('#div-permissoes input[type="checkbox"]');

        _inputs.bootstrapSwitch('disabled', false);
        
        _inputs.bootstrapSwitch('state', false);
        
        var _inputsview = $('#div-permissoes input[type="checkbox"].permission-view');
        
        $('#adminperfil-bpbi_menu_painel').val('');
        
        $('#adminperfil-bpbi_menu_consulta').val(''); 
        
        $('#adminperfil-bpbi_menu_relatorio').val(''); 
        
        if(_value_access)
        {
            var _value_admin = $('#adminperfil-is_admin').bootstrapSwitch('state'); 
                        
            if(_value_admin)
            {
                _inputs.bootstrapSwitch('state', true);
        
                _inputs.bootstrapSwitch('disabled', true);
            }
            else
            {
                _inputs.bootstrapSwitch('state', false);
        
                _inputs.bootstrapSwitch('disabled', true);
        
                _inputsview.bootstrapSwitch('disabled', false);
        
                $('#adminperfil-bpbi_menu_painel').attr('disabled', false);
        
                $('#adminperfil-bpbi_menu_consulta').attr('disabled', false);        

                $('#adminperfil-bpbi_menu_relatorio').attr('disabled', false);        
            }
        
            if(_isadmin == 1)
            {
                $('.field-adminperfil-is_admin input[type="checkbox"]').bootstrapSwitch('disabled', false);
            }
        }
        else
        {
            $('.field-adminperfil-is_admin input[type="checkbox"]').bootstrapSwitch('state', false);

            $('.field-adminperfil-is_admin input[type="checkbox"]').bootstrapSwitch('disabled', true);
            
            $('#adminperfil-bpbi_menu_painel').attr('disabled', true);
            
            $('#adminperfil-bpbi_menu_consulta').attr('disabled', true);
            
            $('#adminperfil-bpbi_menu_relatorio').attr('disabled', true);
            
            _inputs.bootstrapSwitch('disabled', true);
        }
    }
        
JS;

$js_admin = <<<JS
        
    function()
    { 
        var _value = $(this).bootstrapSwitch('state'); 
        
        var _inputs = $('#div-permissoes input[type="checkbox"]');
        
        var _inputsview = $('#div-permissoes input[type="checkbox"].permission-view');
        
        $('#adminperfil-bpbi_menu_painel').val('');
        
        $('#adminperfil-bpbi_menu_consulta').val(''); 
        
        $('#adminperfil-bpbi_menu_relatorio').val(''); 
        
        _inputs.bootstrapSwitch('disabled', false);
        
        _inputs.bootstrapSwitch('state', false);
        
        if(_value)
        {
            _inputs.bootstrapSwitch('state', true);
        
            _inputs.bootstrapSwitch('disabled', true);
        
            $('#adminperfil-bpbi_menu_painel').attr('disabled', true);
            
            $('#adminperfil-bpbi_menu_consulta').attr('disabled', true);
            
            $('#adminperfil-bpbi_menu_relatorio').attr('disabled', true);
        }
        else
        {
            var _value_access = $('#adminperfil-acesso_bi').bootstrapSwitch('state');
        
            _inputs.bootstrapSwitch('state', false);
            
            if(_value_access)
            {
                _inputsview.bootstrapSwitch('disabled', false);
        
                $('#adminperfil-bpbi_menu_painel').attr('disabled', false);
        
                $('#adminperfil-bpbi_menu_consulta').attr('disabled', false);
                
                $('#adminperfil-bpbi_menu_relatorio').attr('disabled', false);
            }
            else
            {
                _inputsview.bootstrapSwitch('disabled', true);
            }
        }
    }
        
JS;

$js_index = <<<JS
        
    function()
    { 
        var _value = $(this).bootstrapSwitch('state'); 
        var _index = $(this).data('line');
        var _inputs = $('.permission-line-' + _index);
        
        if(_value)
        {
            _inputs.bootstrapSwitch('disabled', false);
        }
        else
        {
            _inputs.bootstrapSwitch('state', false);
            _inputs.bootstrapSwitch('disabled', true);
        }
    }
        
JS;

$js_select2 = <<<JS

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
        
        $(".selectpicker-field-id_consulta").select2(
        {
            theme: "bp1",
            placeholder: "",
            language: _language
        });      
        
        $(".selectpicker-field-id_painel").select2(
        {
            theme: "bp1",
            placeholder: "",
            language: _language
        });
        
        $(".selectpicker-field-id_relatorio_data").select2(
        {
            theme: "bp1",
            placeholder: "",
            language: _language
        });
        
        $('.field-perfilcomplemento-id_consulta').parent().hide();
        $('.field-perfilcomplemento-id_painel').parent().hide();
        $('.field-perfilcomplemento-id_relatorio_data').parent().hide();
        
        if($('#perfilcomplemento-pagina_inicial').val() == 2)
        {
            $('.field-perfilcomplemento-id_painel').parent().show();
        }
        
        if($('#perfilcomplemento-pagina_inicial').val() == 3)
        {
            $('.field-perfilcomplemento-id_consulta').parent().show();
        }
               
        if($('#perfilcomplemento-pagina_inicial').val() == 5)
        {
            $('.field-perfilcomplemento-id_relatorio_data').parent().show();
        }
        
        $(document).delegate('#perfilcomplemento-pagina_inicial', 'change', function(){
            var _val = $(this).val();
            
            $('.field-perfilcomplemento-id_consulta').parent().hide();
            $('.field-perfilcomplemento-id_painel').parent().hide();
            $('.field-perfilcomplemento-id_relatorio_data').parent().hide();
                
            if(_val == 2)
            {
                $('.field-perfilcomplemento-id_painel').parent().show();
            }
            else if(_val == 3)
            {
                $('.field-perfilcomplemento-id_consulta').parent().show();
            }
            else if(_val == 5)
            {
                $('.field-perfilcomplemento-id_relatorio_data').parent().show();
            }
        });
    });

    $("#treeviewc").hummingbird();
    $("#treeviewp").hummingbird();

JS;

$this->registerJs($js_select2);

?>

<style>
    .perfil-form ul
    {
        list-style: none;
    }

    #treeviewp,
    #treeviewc
    {
        padding-left: 0px;
    }
</style>

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
                            ],
                            'pluginEvents' => 
                            [
                                "switchChange.bootstrapSwitch" => $js_accesso
                            ]
                        ],
                        'columnOptions' => ['colspan' => 4],
                    ],
                    'is_admin' => ($is_admin) ?
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
                            ],
                            'pluginEvents' => 
                            [
                                "switchChange.bootstrapSwitch" => $js_admin,
                            ]
                        ],
                        'columnOptions' => ['colspan' => 4],
                    ] : [
                        'type' => Form::INPUT_WIDGET, 
                        'widgetClass' => SwitchInput::classname(),
                        'options' =>
                        [
                            'disabled' => TRUE,
                            'pluginOptions' => 
                            [
                                'size' => 'mini',
                                'onText' => 'Sim',
                                'offText' => 'Não',
                                'onColor' => 'success',
                                'offColor' => 'danger',
                            ],
                            'pluginEvents' => 
                            [
                                "switchChange.bootstrapSwitch" => $js_admin,
                            ]
                        ],
                        'columnOptions' => ['colspan' => 4],
                    ]
                ],
            ]); ?>

            <?= Form::widget(
                [
                    'model' => $complemento,
                    'form' => $form,
                    'columns' => 12,
                    'attributes' =>
                    [
                        'pagina_inicial' =>
                        [
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'items' => $complemento::$tipos,
                            'columnOptions' => ['colspan' => 3],
                        ],
                        'id_consulta' =>
                        [
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'items' => ArrayHelper::map($consultas, 'id', 'nome'),
                            'options' =>
                            [
                                'prompt' => '',
                                'class' =>  'selectpicker-field-id_consulta'
                            ],
                            'columnOptions' => ['colspan' => 3],
                        ],
                        'id_painel' =>
                        [
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'items' => ArrayHelper::map($paineis, 'id', 'nome'),
                            'options' =>
                                [
                                    'prompt' => '',
                                    'class' =>  'selectpicker-field-id_painel'
                                ],
                            'columnOptions' => ['colspan' => 3],
                        ],
                        'id_relatorio_data' =>
                            [
                                'type' => Form::INPUT_DROPDOWN_LIST,
                                'items' => ArrayHelper::map($relatorios, 'id', 'nome'),
                                'options' =>
                                    [
                                        'prompt' => '',
                                        'class' =>  'selectpicker-field-id_relatorio_data'
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

            <div id="div-permissoes">
            
                <h5>Permissões</h5>

                <?php
                
                $index = 1;

                foreach($model->getPermissoes() as $gerenciador => $permissao) : 
                
                ?>

                    <div class="box-permission">

                        <p><b><?= $gerenciador; ?></b></p>
                        
                        <div class="row">
                        
                            <?php 

                            $disabled = FALSE;
                            $existe_visualizar = FALSE;
                            
                            for($column = 1; $column <= 6; $column++) :
                                
                                echo "<div class='col-lg-2'>";
                                                 
                                    if(isset($permissao[$column])) : 
                                        
                                        $attributes = [];
                                        
                                        if(!$model->acesso_bi || $model->is_admin)
                                        {
                                            $disabled = TRUE;
                                        }
                                        
                                        $dado = $permissao[$column];
                                        
                                        $data = $dado['attributes'];
                                        
                                        if(strtolower($data['nome']) == 'visualizar')
                                        {
                                            $existe_visualizar = TRUE;
                                        }

                                        $switch_change = (strtolower($data['nome']) == 'visualizar') ? $js_index : '';
                                        $class = (strtolower($data['nome']) == 'visualizar' || !$existe_visualizar) ? 'permission-view' : 'permission-data permission-line-' . $index;

                                        if($model->acesso_bi && $model->is_admin)
                                        {
                                            $model->permissoes[$gerenciador][$data['column']][$data['id']] = TRUE;
                                        }
                                        
                                        $attributes["permissoes[{$gerenciador}][{$data['column']}][{$data['id']}]"] = 
                                        [
                                            'label' => $data['nome'] . " <i class='fa fa-question-circle' style='font-size: 10px;' title='{$data['descricao']}'></i>",
                                            'type' => Form::INPUT_WIDGET, 
                                            'widgetClass' => SwitchInput::classname(),
                                            'options' =>
                                            [
                                                'disabled' => $disabled,
                                                'options' =>
                                                [
                                                    'class' => $class,
                                                    'data-line' => $index
                                                ],
                                                'pluginOptions' => 
                                                [
                                                    'size' => 'mini',
                                                    'onText' => 'Sim',
                                                    'offText' => 'Não',
                                                    'onColor' => 'success',
                                                    'offColor' => 'danger',
                                                ],
                                                'pluginEvents' => 
                                                [
                                                    "switchChange.bootstrapSwitch" => $switch_change,
                                                ]
                                            ],
                                        ];
                                            
                                        echo Form::widget(
                                        [
                                            'model' => $model,
                                            'form' => $form,
                                            'columns' => 1,
                                            'attributes' => $attributes
                                        ]);

                                        if(strtolower($data['nome']) == 'visualizar' && !$dado[$data['id']])
                                        {
                                            $disabled = TRUE;
                                        }
                                        
                                    endif;
                            
                                echo "</div>";
                                
                            endfor;

                        echo '</div>';
                        
                    echo '</div>';

                    $index++;
                
                endforeach;
                
            ?>  

            </div>

            <hr>

            <div class="row pt-2" style="border-top: 1px solid #cecece;">

                <div class="col-lg-4">

                    <h5>Painéis</h5>

                    <?= $menuPainel ?>

                </div>

                <div class="col-lg-4">

                    <h5>Consultas</h5>

                    <?= $menuConsulta ?>

                </div>

                <div class="col-lg-4">

                    <h5>Relatórios</h5>

                    <?= $menuRelatorio ?>

                </div>

            </div>

        </div>

    <?php ActiveForm::end(); ?>

</div>
