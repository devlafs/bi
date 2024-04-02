<?php

use app\magic\FiltroMagic;
use app\models\IndicadorCampo;

$cubos = FiltroMagic::getCubos();

$js = <<<JS
        
    $(function() 
    {
        $(".selectpicker").select2(
        {
            theme: "bp1"
        });
        
        $(".selectpicker-noSearch").select2(
        {
            theme: "bp1",
            minimumResultsForSearch: Infinity
        });
    });
        
JS;

$this->registerJs($js);

?>

<li class="list-group-item item_<?= $indexAnd ?>_<?= $indexOr ?>" data-index="<?= $indexOr ?>">
    
    <button class="remove-atribute" data-indexand="<?= $indexAnd ?>" data-indexor="<?= $indexOr ?>">

        <i class="bp-close"></i>

    </button>

    <div class="d-flex w-100 flex-column justify-content-between">

        <div class="d-flex align-item-end mt-2">

            <div class="d-inline-flex w-100">

                <select name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][cubo]" class="selectpicker select2-hidden-accessible select-choose-cubo select-choose-cubo-<?= $indexAnd ?>-<?= $indexOr ?> w-100" data-indexand="<?= $indexAnd ?>" data-indexor="<?= $indexOr ?>" style="width:100%;" tabindex="-1" aria-hidden="true">

                    <option></option>

                    <?php foreach($cubos as $cubo) : ?>

                        <?php $selected = ($data && $data['cubo'] == $cubo->id) ? 'selected="selected"' : '' ?>

                        <option value="<?= $cubo->id ?>" <?= $selected ?> ><?= mb_strtoupper($cubo->nome) ?></option>

                    <?php endforeach; ?>

                </select>

            </div>

        </div>

        <div class="d-flex align-item-end mt-2">

            <div class="d-inline-flex w-100 render_select_field_<?= $indexAnd ?>_<?= $indexOr ?>">

                <?php if($data): ?>

                    <?php

                    $argumentos = IndicadorCampo::find()->andWhere(['id_indicador' => $data['cubo'], 'is_ativo' => TRUE, 'is_excluido' => FALSE])->orderBy('nome ASC')->all();

                    ?>

                    <select class="selectpicker-noSearch select-choose-field select-choose-field-<?= $indexAnd ?>-<?= $indexOr ?>" name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][field]" data-indexand="<?= $indexAnd ?>" data-indexor="<?= $indexOr ?>" style="width:100%;" tabindex="-1" aria-hidden="true">

                        <option></option>

                        <?php foreach($argumentos as $argumento) : ?>

                            <?php $selected = ($data && $data['field'] == $argumento->id) ? 'selected="selected"' : '' ?>

                            <option value="<?= $argumento->id ?>" <?= $selected ?> ><?= mb_strtoupper($argumento->nome) ?></option>

                        <?php endforeach; ?>

                    </select>

                <?php else: ?>

                    <select class="selectpicker-noSearch disabled w-100" name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][field]" data-indexand="<?= $indexAnd ?>" data-indexor="<?= $indexOr ?>" style="width:100%;" disabled="disabled" tabindex="-1" aria-hidden="true">

                        <option></option>

                    </select>

                <?php endif; ?>

            </div>

        </div>
        
        <div class="d-flex align-item-end mt-2">

            <div class="d-inline-flex w-100 render_select_type_<?= $indexAnd ?>_<?= $indexOr ?>">

                <?php if($data): ?>
                
                    <?php 
                        
                        $campo = IndicadorCampo::findOne($data['field']);
                        $data_list = FiltroMagic::getPainelListByType($campo->tipo);

                    ?>
                
                    <select class="selectpicker-noSearch select-choose-type select-choose-type-<?= $indexAnd ?>-<?= $indexOr ?>" name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][type]" data-indexand="<?= $indexAnd ?>" data-indexor="<?= $indexOr ?>" style="width:100%;" tabindex="-1" aria-hidden="true">

                        <option></option>

                        <?php foreach($data_list as $index => $element) : ?>

                            <?php $selected = ($data && $data['type'] == $index) ? 'selected="selected"' : '' ?>
                        
                            <option value="<?= $index ?>" <?= $selected ?> ><?= mb_strtoupper($element) ?></option>

                        <?php endforeach; ?>

                    </select>
                
                <?php else: ?>
                
                    <select class="selectpicker-noSearch disabled w-100" name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][type]" data-indexand="<?= $indexAnd ?>" data-indexor="<?= $indexOr ?>" style="width:100%;" disabled="disabled" tabindex="-1" aria-hidden="true">

                        <option></option>

                    </select>
                    
                <?php endif; ?>
                
            </div>

        </div>

    </div>

</li>