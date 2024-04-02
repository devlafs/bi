<?php

use app\magic\FiltroMagic;
use kartik\checkbox\CheckboxX;
use app\lists\TagsList;
use kartik\date\DatePicker;
use app\lists\DateFormatList;
use yii\widgets\MaskedInput;

$css = <<<CSS

.cbx-container .cbx-icon i.bp-check
{
    color: #007482;
    font-size: 20px;
    font-weight: bolder;
}

CSS;

$this->registerCss($css);

$js = <<<JS
        
    $(function() 
    {   
        $(".selectpicker-noSearch").select2(
        {
            theme: "bp1",
            minimumResultsForSearch: Infinity
        });
        
        $("#data-tag-magic-{$indexAnd}-{$indexOr}").change(function()
        {
            var _val = $(this).val();
            var _el = $("#data-number-magic-{$indexAnd}-{$indexOr}");
            
            if(_val == 0)
            {
                _el.val(0);
                _el.prop('disabled', true);
            }
            else
            {
               _el.prop('disabled', false);
            }
        });
    });
        
JS;

$this->registerJs($js);

switch ($type):

    case FiltroMagic::COND_IGUAL_A:
    case FiltroMagic::COND_DIFERENTE:
    case FiltroMagic::COND_MAIOR:
    case FiltroMagic::COND_MENOR:

        if($campo->formato == DateFormatList::DD__MM__YYYY || $campo->formato == DateFormatList::DD_MM_YYYY) :

            if (!$tag && ($data && isset($data['value']['tag']))) {
                $tag = true;
            }

            if ($tag) :

                if (isset($data['value']['tag']['1']) && $data['value']['tag']['1'] == TagsList::TAG_HOJE) : ?>

                    <input id="data-number-magic-<?= $indexAnd ?>-<?= $indexOr ?>" class="form-control" disabled="disabled"
                           name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][value][tag][0]" type="number" min="0" value="0"
                           style="width: 80px; margin-right: 5px;">

                <?php else:

                    $selected_0 = ($data && $data['value']['tag']['0']) ? 'value="' . $data['value']['tag']['0'] . '"' : '' ?>

                    <input id="data-number-magic-<?= $indexAnd ?>-<?= $indexOr ?>" class="form-control"
                           name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][value][tag][0]" type="number"
                           min="0" <?= $selected_0 ?> style="width: 80px; margin-right: 5px;">

                <?php endif; ?>

                <select id="data-tag-magic-<?= $indexAnd ?>-<?= $indexOr ?>" class="selectpicker-noSearch"
                        name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][value][tag][1]" style="width:100%;" tabindex="-1"
                        aria-hidden="true">

                    <option></option>

                    <?php foreach (TagsList::getDataList() as $codigo => $nome) : ?>

                        <?php $selected_1 = ($data && $data['value']['tag']['1'] == $codigo) ? 'selected="selected"' : '' ?>

                        <option value="<?= $codigo ?>" <?= $selected_1 ?> ><?= mb_strtoupper($nome) ?></option>

                    <?php endforeach; ?>

                </select>

                <div class="w-50 text-right">

                    <label class="cbx-label">Tag</label>

                    <?= CheckboxX::widget([
                        'name' => 'tag-' . $indexAnd . '-' . $indexOr,
                        'value' => 1,
                        'options' => ['id' => 'tag-' . $indexAnd . '-' . $indexOr, 'class' => 'change-tag', 'data-indexand' => $indexAnd, 'data-indexor' => $indexOr],
                        'pluginOptions' => ['threeState' => false, 'iconChecked' => '<i class="bp-check"></i>']
                    ]); ?>

                </div>

            <?php

            else :

                ?>

                <?php $selected = ($data) ? $data['value'] : '' ?>

                <?= DatePicker::widget([
                'name' => 'Form['. $indexAnd . '][' . $indexOr . '][value]',
                'value' => $selected,
                'pickerIcon' => '<i class="fas fa-calendar-alt"></i>',
                'removeIcon' => '<i class="fas fa-times"></i>',
                'options' =>
                    [
                        'class' => 'form-control'
                    ],
                'pluginOptions' =>
                    [
                        'autoclose' => true,
                        'format' => 'dd/mm/yyyy'
                    ]
            ]); ?>

                <div class="w-50 text-right">

                    <label class="cbx-label">Tag</label>

                    <?= CheckboxX::widget([
                        'name' => 'tag-' . $indexAnd . '-' . $indexOr,
                        'value' => 0,
                        'options' => ['id' => 'tag-' . $indexAnd . '-' . $indexOr, 'class' => 'change-tag', 'data-indexand' => $indexAnd, 'data-indexor' => $indexOr],
                        'pluginOptions' => ['threeState' => false, 'iconChecked' => '<i class="bp-check"></i>']
                    ]); ?>

                </div>

            <?php

            endif;

        elseif($campo->formato == DateFormatList::DD): ?>

            <select name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][value]" class="form-control">

                <option></option>

                <?php $selected = ($data) ? $data['value'] : '' ?>

                <?php for($i = 1; $i < 32; $i++): $s = ($i < 10) ? "0{$i}" : $i; ?>

                    <option value="<?= $s ?>" <?= ($selected == $s) ? 'selected="selected"' : '' ?>><?= $s ?></option>

                <?php endfor; ?>

            </select>

        <?php elseif($campo->formato == DateFormatList::MM): ?>

            <select name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][value]" class="form-control">

                <option></option>

                <?php $selected = ($data) ? $data['value'] : '' ?>

                <?php for($i = 1; $i < 13; $i++): $s = ($i < 10) ? "0{$i}" : $i; ?>

                    <option value="<?= $s ?>" <?= ($selected == $s) ? 'selected="selected"' : '' ?>><?= $s ?></option>

                <?php endfor; ?>

            </select>

        <?php elseif($campo->formato == DateFormatList::YYYY): ?>

            <select name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][value]" class="form-control">

                <option></option>

                <?php $selected = ($data) ? $data['value'] : '' ?>

                <?php for($i = 1980; $i < 2040; $i++): ?>

                    <option value="<?= $i ?>" <?= ($selected == $i) ? 'selected="selected"' : '' ?>><?= $i ?></option>

                <?php endfor; ?>

            </select>

        <?php else:

            $selected = ($data) ? $data['value'] : '';

            echo MaskedInput::widget([
                'name' => 'Form['. $indexAnd . '][' . $indexOr . '][value]',
                'value' => $selected,
                'mask' => DateFormatList::getMask($campo->formato),
                'options' => [
                    'class' => 'form-control'
                ]
            ]);

        endif;

        break;

    case FiltroMagic::COND_INTERVALO_DE:

        if($campo->formato == DateFormatList::DD__MM__YYYY || $campo->formato == DateFormatList::DD_MM_YYYY) :

            ?>

            <?php $selected_0 = ($data) ? $data['value']['0'] : '' ?>
            <?php $selected_1 = ($data) ? $data['value']['1'] : '' ?>

            <?= DatePicker::widget([
            'name' => 'Form['. $indexAnd . '][' . $indexOr . '][value][0]',
            'value' => $selected_0,
            'pickerIcon' => '<i class="fas fa-calendar-alt"></i>',
            'removeIcon' => '<i class="fas fa-times"></i>',
            'options' =>
                [
                    'class' => 'form-control w-50 mr-1'
                ],
            'pluginOptions' =>
                [
                    'autoclose' => true,
                    'format' => 'dd/mm/yyyy'
                ]
        ]); ?>

            <?= DatePicker::widget([
            'name' => 'Form['. $indexAnd . '][' . $indexOr . '][value][1]',
            'value' => $selected_1,
            'pickerIcon' => '<i class="fas fa-calendar-alt"></i>',
            'removeIcon' => '<i class="fas fa-times"></i>',
            'options' =>
                [
                    'class' => 'form-control w-50'
                ],
            'pluginOptions' =>
                [
                    'autoclose' => true,
                    'format' => 'dd/mm/yyyy'
                ]
        ]); ?>

        <?php

        elseif($campo->formato == DateFormatList::DD): ?>

            <select name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][value][0]" class="form-control">

                <option></option>

                <?php $selected_0 = ($data) ? $data['value']['0'] : '' ?>

                <?php for($i = 1; $i < 32; $i++): $s = ($i < 10) ? "0{$i}" : $i; ?>

                    <option value="<?= $s ?>" <?= ($selected_0 == $s) ? 'selected="selected"' : '' ?>><?= $s ?></option>

                <?php endfor; ?>

            </select>

            <select name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][value][1]" class="form-control">

                <option></option>

                <?php $selected_1 = ($data) ? $data['value']['1'] : '' ?>

                <?php for($i = 1; $i < 32; $i++): $s = ($i < 10) ? "0{$i}" : $i; ?>

                    <option value="<?= $s ?>" <?= ($selected_1 == $s) ? 'selected="selected"' : '' ?>><?= $s ?></option>

                <?php endfor; ?>

            </select>

        <?php elseif($campo->formato == DateFormatList::MM): ?>

            <select name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][value][0]" class="form-control">

                <option></option>

                <?php $selected_0 = ($data) ? $data['value']['0'] : '' ?>

                <?php for($i = 1; $i < 13; $i++): $s = ($i < 10) ? "0{$i}" : $i; ?>

                    <option value="<?= $s ?>" <?= ($selected_0 == $s) ? 'selected="selected"' : '' ?>><?= $s ?></option>

                <?php endfor; ?>

            </select>

            <select name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][value][1]" class="form-control">

                <option></option>

                <?php $selected_1 = ($data) ? $data['value']['1'] : '' ?>

                <?php for($i = 1; $i < 13; $i++): $s = ($i < 10) ? "0{$i}" : $i; ?>

                    <option value="<?= $s ?>" <?= ($selected_1 == $s) ? 'selected="selected"' : '' ?>><?= $s ?></option>

                <?php endfor; ?>

            </select>

        <?php elseif($campo->formato == DateFormatList::YYYY): ?>

            <select name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][value][0]" class="form-control">

                <option></option>

                <?php $selected_0 = ($data) ? $data['value']['0'] : '' ?>

                <?php for($i = 1980; $i < 2040; $i++): ?>

                    <option value="<?= $i ?>" <?= ($selected_0 == $i) ? 'selected="selected"' : '' ?>><?= $i ?></option>

                <?php endfor; ?>

            </select>

            <select name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][value][1]" class="form-control">

                <option></option>

                <?php $selected_1 = ($data) ? $data['value']['1'] : '' ?>

                <?php for($i = 1980; $i < 2040; $i++): ?>

                    <option value="<?= $i ?>" <?= ($selected_1 == $i) ? 'selected="selected"' : '' ?>><?= $i ?></option>

                <?php endfor; ?>

            </select>

        <?php else:

            $selected_0 = ($data) ? $data['value']['0'] : '';
            $selected_1 = ($data) ? $data['value']['1'] : '';

            echo MaskedInput::widget([
                'name' => 'Form['. $indexAnd . '][' . $indexOr . '][value][0]',
                'value' => $selected_0,
                'mask' => DateFormatList::getMask($campo->formato),
                'options' => [
                    'class' => 'form-control'
                ]
            ]);

            echo MaskedInput::widget([
                'name' => 'Form['. $indexAnd . '][' . $indexOr . '][value][1]',
                'value' => $selected_1,
                'mask' => DateFormatList::getMask($campo->formato),
                'options' => [
                    'class' => 'form-control'
                ]
            ]);

        endif;

        break;

    default:

        ?>

        <input class="form-control disabled" disabled="disabled" type="text">

    <?php

endswitch;

?>