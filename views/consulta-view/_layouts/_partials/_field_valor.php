<?php 

use app\magic\FiltroMagic;
use kartik\number\NumberControl;

switch($type):

    case FiltroMagic::COND_IGUAL_A:
    case FiltroMagic::COND_MAIOR:
    case FiltroMagic::COND_MAIOR_IGUAL:
    case FiltroMagic::COND_MENOR:
    case FiltroMagic::COND_MENOR_IGUAL:
    
        $selected = ($data) ? $data['value'] : 0;
        
        echo NumberControl::widget([
            'id' => 'form_' . $indexAnd. '_'. $indexOr .'_value',
            'name' => 'Form[' . $indexAnd. ']['. $indexOr .'][value]',
            'value' => $selected,
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
    
    case FiltroMagic::COND_INTERVALO_DE:
        
        $selected_0 = ($data) ? $data['value']['0'] : 0;
        $selected_1 = ($data) ? $data['value']['1'] : 0;
        
        echo NumberControl::widget([
            'id' => 'form_' . $indexAnd. '_'. $indexOr .'_value_0',
            'name' => 'Form[' . $indexAnd. ']['. $indexOr .'][value][0]',
            'value' => $selected_0,
            'options' =>
            [
                'class' => 'w-50 mr-1'
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
        
        echo NumberControl::widget([
            'id' => 'form_' . $indexAnd. '_'. $indexOr .'_value_1',
            'name' => 'Form[' . $indexAnd. ']['. $indexOr .'][value][1]',
            'value' => $selected_1,
            'options' =>
            [
                'class' => 'w-50'            
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