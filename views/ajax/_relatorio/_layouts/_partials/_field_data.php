<?php

use app\magic\FiltroMagic;
use app\lists\DateFormatList;
use kartik\date\DatePicker;
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
    });
        
JS;

$this->registerJs($js);

switch ($data['type']):

    case FiltroMagic::COND_IGUAL_A:
    case FiltroMagic::COND_DIFERENTE:
    case FiltroMagic::COND_MAIOR:
    case FiltroMagic::COND_MENOR:

        if($campo->formato == DateFormatList::DD__MM__YYYY || $campo->formato == DateFormatList::DD_MM_YYYY) :

        ?>

            <?= DatePicker::widget([
                'name' => 'Form[value]',
                'pickerIcon' => '<i class="fas fa-calendar-alt"></i>',
                'removeIcon' => '<i class="fas fa-times"></i>',
                'options' =>
                [
                    'class' => 'form-control',
                    'data-cubo' => $campo->id_indicador,
                    'data-field' => $campo->id,
                    'data-type' => $data['type']
                ],
                'pluginOptions' =>
                [
                    'autoclose' => true,
                    'format' => 'dd/mm/yyyy'
                ]
            ]); ?>

        <?php elseif($campo->formato == DateFormatList::DD): ?>

            <select data-cubo="<?= $campo->id_indicador ?>" data-field="<?= $campo->id ?>" data-type="<?= $data['type'] ?>" name="Form[value]" class="form-control">

                <option></option>

                <?php for($i = 1; $i < 32; $i++): $s = ($i < 10) ? "0{$i}" : $i; ?>

                    <option value="<?= $s ?>"><?= $s ?></option>

                <?php endfor; ?>

            </select>

        <?php elseif($campo->formato == DateFormatList::MM): ?>

            <select data-cubo="<?= $campo->id_indicador ?>" data-field="<?= $campo->id ?>" data-type="<?= $data['type'] ?>" name="Form[value]" class="form-control">

                <option></option>

                <?php for($i = 1; $i < 13; $i++): $s = ($i < 10) ? "0{$i}" : $i; ?>

                    <option value="<?= $s ?>"><?= $s ?></option>

                <?php endfor; ?>

            </select>

        <?php elseif($campo->formato == DateFormatList::YYYY): ?>

            <select data-cubo="<?= $campo->id_indicador ?>" data-field="<?= $campo->id ?>" data-type="<?= $data['type'] ?>" name="Form[value]" class="form-control">

                <option></option>

                <?php for($i = 1980; $i < 2040; $i++): ?>

                    <option value="<?= $i ?>"><?= $i ?></option>

                <?php endfor; ?>

            </select>

        <?php else:

                echo MaskedInput::widget([
                    'name' => 'Form[value]',
                    'mask' => DateFormatList::getMask($campo->formato),
                    'options' => [
                        'class' => 'form-control',
                        'data-cubo' => $campo->id_indicador,
                        'data-field' => $campo->id,
                        'data-type' => $data['type']
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