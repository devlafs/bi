<?php

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

?>

<select class="selectpicker-noSearch select-choose-type select-choose-type-<?= $indexAnd ?>-<?= $indexOr ?>" name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][type]" data-indexand="<?= $indexAnd ?>" data-indexor="<?= $indexOr ?>" style="width:100%;" tabindex="-1" aria-hidden="true">

    <option></option>

    <?php foreach($list as $index => $element) : ?>

        <option value="<?= $index ?>"><?= mb_strtoupper($element) ?></option>

    <?php endforeach; ?>

</select>