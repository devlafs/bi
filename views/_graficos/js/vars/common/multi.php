<?php

$js = <<<"JS"
        
    var _x = [];
    var _y = [];
        
    for(var i = 0; i < _data.dataProvider.x.length; i++)
    {
        _x.push(_data.dataProvider.x[i]);         
    }
        
    if(_x.length === 0 && _data.elementoAtual == null)
    {
        _x = ['Total'];
    }
                
    $.each(_x, function(index_x, name)
    {
        _value = _data.dataProvider.data[name];
        _color = _data.dataColor[name];

        if(typeof _value === 'undefined')
        {
            _value = 0;
        }

        if(_value == null)
        {
            _value = 'null';
        }

        _y.push({value: _value, itemStyle: {color: _color}});
    });
        
JS;

echo $js;