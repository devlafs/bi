<?php

use kartik\builder\Form;
use app\lists\DateFormatList;

?>

<?= Form::widget(
[
    'model' => $model,
    'form' => $form,
    'columns' => 2,
    'attributes' =>
    [
        'formato' => 
        [
            'type' => Form::INPUT_DROPDOWN_LIST,
            'items' => DateFormatList::$formats,
            'label' => "Formato (Data) <i class='fa fa-question-circle' style='font-size: 10px;' title='Ex: DD/MM/YYYY ou MM-YYYY'></i>",
            'columnOptions' => ['colspan' => 3],
        ],
    ],
]); ?>