<?php 

use app\magic\FiltroMagic;

$data_repl = 
[
    '{empty}' => '{VAZIO}',
    '{null}' => '{NULO}',
    '{usuario_logado.id}' => '{USUARIO_LOGADO.ID}',
    '{usuario_logado.identificador}' => '{USUARIO_LOGADO.IDENTIFICADOR}',
    '{usuario_logado.nome}' => '{USUARIO_LOGADO.NOME}',
    '{usuario_logado.login}' => '{USUARIO_LOGADO.LOGIN}',
    '{usuario_logado.departamento_id}' => '{USUARIO_LOGADO.DEPARTAMENTO_NOME}',
    '{usuario_logado.perfil_id}' => '{USUARIO_LOGADO.PERFIL_NOME}'
];

$language = <<<JS

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

JS;

$this->registerJs($language);

$js = <<<JS
        
    $("#selectpicker-search-{$indexAnd}-{$indexOr}").select2(
    {
        theme: "bp1",
        language: _language,
        placeholder: "",
        minimumInputLength: 2,
        ajax: 
        {
            url:"/ajax/field-list?field_id={$campo['id']}",
            dataType: 'json',
            delay: 500,
            data: function(data) { return {q: data.term}; },
            results: function(data, page) { return {results: data}},
            escapeMarkup : function(markup) { return markup; },
            templateResult : function(result) { return result.text; },
            templateSelection : function(result) { return result.text; },
            cache : true
        }
    });

JS;

$this->registerJs($js);

switch($type):
    
    case FiltroMagic::COND_IGUAL_A:
    case FiltroMagic::COND_DIFERENTE:

        if(!$campo->tipo_lista):
        
        $js = <<<JS
        
            $(function() 
            {
                $(".selectpicker-search").select2(
                {
                    theme: "bp1",
                    placeholder: ""
                });
            });
            
JS;

        $this->registerJs($js);

        ?> 
        
            <select id="selectpicker-search-<?= $indexAnd ?>-<?= $indexOr ?>" name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][value]" style="width:100%;" tabindex="-1" aria-hidden="true">
                
                <option></option>

                <?php if($data && $data['value']) : ?>
                
                    <option value="<?= $data['value'] ?>" selected="selected"><?= (isset($data_repl[$data['value']])) ? $data_repl[$data['value']] : mb_strtoupper($data['value']) ?></option>

                <?php endif; ?>
                            
            </select>

        <?php

            else:

                $valores = FiltroMagic::getValorCampo($campo);

                ?>

                <select class="form-control" name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][value]" style="width:100%;">

                    <option></option>

                    <?php if($valores):

                        foreach ($valores as $v): ?>

                            <option value="<?= $v['text'] ?>" <?= ($data && $data['value'] && $data['value'] == $v['text']) ? 'selected="selected' : '' ?>><?= $v['text'] ?></option>

                        <?php endforeach;

                    endif; ?>

                </select>

            <?php

            endif;

        break;
        
    case FiltroMagic::COND_DENTRO_LISTA:
    case FiltroMagic::COND_FORA_LISTA:
        
        $css = <<<CSS
            
        .select2-container--bp1
        {
            height: 100%;
        }
            
CSS;
        
        $this->registerCss($css);

        ?> 
        
            <select id="selectpicker-search-<?= $indexAnd ?>-<?= $indexOr ?>" name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][value][]" multiple="multiple" style="width:100%;" tabindex="-1" aria-hidden="true">

                <option></option>
                
                <?php if($data && $data['value']) : ?>
                
                    <?php foreach($data['value'] as $value) : ?>
                
                        <option value="<?= $value ?>" selected="selected"><?= (isset($data_repl[$value])) ? $data_repl[$value] : mb_strtoupper($value) ?></option>

                    <?php endforeach; ?>
                    
                <?php endif; ?>

            </select>

        <?php
        
        break;
        
    case FiltroMagic::COND_CONTEM:
    case FiltroMagic::COND_NAO_CONTEM:
    case FiltroMagic::COND_COMECA_COM:
    case FiltroMagic::COND_NAO_COMECA_COM:
    case FiltroMagic::COND_TERMINA_COM:
    case FiltroMagic::COND_NAO_TERMINA_COM:
        
?> 

        <?php $selected = ($data) ? 'value="' . $data['value'] . '"' : '' ?>
        
        <input class="form-control" <?= $selected ?> name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][value]" type="text">
        
<?php
        break;

    default:
        
?> 
        
        <input class="form-control disabled" disabled="disabled" type="text">
        
<?php
        
endswitch;

?>