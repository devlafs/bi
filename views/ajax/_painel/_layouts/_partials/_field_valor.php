<?php 

use app\magic\FiltroMagic;
use kartik\number\NumberControl;

switch($data['type']):

    case FiltroMagic::COND_IGUAL_A:
    case FiltroMagic::COND_MAIOR:
    case FiltroMagic::COND_MAIOR_IGUAL:
    case FiltroMagic::COND_MENOR:
    case FiltroMagic::COND_MENOR_IGUAL:

        echo NumberControl::widget([
            'id' => 'form_value',
            'name' => 'Form[value]',
            'options' =>
            [
                'class' => 'form-control',
                'data-cubo' => $campo->id_indicador,
                'data-field' => $campo->id,
                'data-type' => $data['type']
            ],
            'maskedInputOptions' =>
            [
                'prefix' => '',
                'groupSeparator' => '.',
                'radixPoint' => ',',
                'digits' => $campo->casas_decimais,
                'allowMinus' => TRUE,
            ]
        ]);
        
        break;
    
    default:
        
?> 
        
<input class="form-control disabled" disabled="disabled" type="text">
        
<?php
        
endswitch;

?>