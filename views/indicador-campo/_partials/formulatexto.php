<?php

use kartik\builder\Form;

?>

<?= Form::widget(
[
    'model' => $model,
    'form' => $form,
    'columns' => 12,
    'attributes' =>
    [

        'prefixo' => 
        [
            'type' => Form::INPUT_TEXT,
            'label' => "Prefixo <i class='fa fa-question-circle' style='font-size: 10px;' title='Informação visualizada antes do valor'></i>",
            'columnOptions' => ['colspan' => 3],
        ],
        'sufixo' => 
        [
            'type' => Form::INPUT_TEXT,
            'label' => "Sufixo <i class='fa fa-question-circle' style='font-size: 10px;' title='Informação visualizada depois do valor'></i>",
            'columnOptions' => ['colspan' => 3],
        ]
    ],
]); ?>