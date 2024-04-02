<?php

use app\models\ConsultaItemCor;
use kartik\helpers\Html;
use kartik\color\ColorInput;

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
        
    $(".selectpicker-search-field").select2(
    {
        theme: "bp1",
        language: _language,
        placeholder: "",
        minimumInputLength: 2,
        ajax: 
        {
            url:"/ajax/field-list?field_id={$campo_id}",
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

    $(document).delegate('.fa-trash' ,"click", function(e)
    {
        $(this).parent().parent().parent().remove();
    });

JS;

$this->registerJs($js);

if(!$model)
{
    $model = new ConsultaItemCor();
}

?>

<td>
    <select class="selectpicker-search-field" name="Form[<?= $ordem ?>][valor]" style="width:300px;" tabindex="-1" aria-hidden="true">
        <option></option>
        <?php if($model->id) : ?>
            <option value="<?= $model->valor ?>" selected="selected"><?= $model->valor ?></option>
        <?php endif; ?>
    </select>
</td>
<td>
    <?= ColorInput::widget([
        'model' => $model,
        'attribute' => 'cor',
        'options' => ['name' => "Form[{$ordem}][cor]", 'id' => "form_{$ordem}_cor"]
    ]); ?>
</td>
<td class="text-center">
    <?= Html::a('<i class="fa fa-trash mt-3"></i>', 'javascript:', [
        'title' => \Yii::t('app', 'view.excluir'),
    ]); ?>
</td>