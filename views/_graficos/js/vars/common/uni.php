<?php

$js = <<<"JS"
        
    var _x = [];
    var _y = [];

    for(var i = 0; i < _data.dataProvider.x.length; i++)
    {
        _x.push(_data.dataProvider.x[i] || "null");         
    }
        
    if(_x.length === 0 && _data.elementoAtual == null)
    {
        _x = ['Total'];
    }
        
    $.each(_x, function(idx, field_name)
    {
        _value = _data.dataProvider.data[field_name];
        _color = _data.dataColor[field_name];

        if(field_name === undefined)
        {
            _y.push({'name': 'Total', 'value': _value});
        }
        else if(field_name == null)
        {
            _y.push({'name': 'null', 'value': _value, itemStyle: {color: _color}});
        }
        else
        {
            _y.push({'name': field_name, 'value': _value, itemStyle: {color: _color}});
        }
    });
                
JS;

echo $js;