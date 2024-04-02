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

<select class="selectpicker-noSearch select-choose-field select-choose-field-<?= $indexAnd ?>-<?= $indexOr ?>" name="Form[<?= $indexAnd ?>][<?= $indexOr ?>][field]" data-indexand="<?= $indexAnd ?>" data-indexor="<?= $indexOr ?>" style="width:100%;" tabindex="-1" aria-hidden="true">

    <option></option>

    <?php foreach($argumentos as $argumento) : ?>

        <option value="<?= $argumento->id ?>"><?= mb_strtoupper($argumento->nome) ?></option>

    <?php endforeach; ?>

</select>