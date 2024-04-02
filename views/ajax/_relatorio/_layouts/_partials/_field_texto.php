<?php 

use app\magic\FiltroMagic;

switch($data['type']):
    
    case FiltroMagic::COND_IGUAL_A:
    case FiltroMagic::COND_DIFERENTE:
    case FiltroMagic::COND_CONTEM:
    case FiltroMagic::COND_NAO_CONTEM:
    case FiltroMagic::COND_COMECA_COM:
    case FiltroMagic::COND_NAO_COMECA_COM:
    case FiltroMagic::COND_TERMINA_COM:
    case FiltroMagic::COND_NAO_TERMINA_COM:
        
?> 

        <input class="form-control" name="RelatorioDinamicoSearch[]" type="text">
        
<?php
        break;

    default:
        
?> 
        
        <input class="form-control disabled" disabled="disabled" type="text">
        
<?php
        
endswitch;

?>