<?php

$js = <<<JS

function getDataForm()
{
    var _data = [];
    var _indicador = {$model->id_indicador};

    $('#div-filter input, #div-filter select').each(function(){
        var _value = $(this).val();
        var _cubo = $(this).data('cubo');
        var _field = $(this).data('field');
        var _type = $(this).data('type');
        
        if(_field != undefined && _cubo != undefined && _cubo == _indicador && _value.trim() != '')
        {
            _data.push({'field': _field, 'type': _type, 'value': _value});
        }
    });
    
    return _data;
}

JS;

$this->registerJs($js);

?>

<div class="col-md-12 col-xs-12 pl-0 pr-0">
              
    <meta http-equiv="refresh" content="1800">
    
    <div class="card card-consulta card--chart card--consuta__full mt-0" style="height: 100%;">

        <?= $this->render('/_graficos/_general/content', compact('index', 'data', 'model', 'square', 'painel', 'data_conected')) ?>

    </div>

</div>