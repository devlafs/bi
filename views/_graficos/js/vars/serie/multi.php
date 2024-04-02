<?php

if($tipo_serializacao == 1) :

$js = <<<"JS"
        
    var _x = [];
    var _z = [];
    var _series = [];
    var _title = '';

    for(var i = 0; i < _data.dataProvider.x.length; i++)
    {
        _x.push(_data.dataProvider.x[i] || "null");         
    }
        
    if(_x.length === 0 && _data.elementoAtual == null)
    {
        _x = ['Total'];
    }

    for(var i = 0; i < _data.dataProvider.z.length; i++)
    {
        _z.push(_data.dataProvider.z[i] || "null");         
    }
        
    $.each(_data.dataProvider.data, function(serie_name, data_x)
    {
        var _dataserie = [];

        $.each(_x, function(index_x, name)
        {
            _value = data_x[name];

            if(typeof _value === 'undefined')
            {
                _value = 0;
            }

            if(_value == null)
            {
                _value = 'null';
            }

            _dataserie.push({'value': _value});
        });

        _series.push({$data});
    });
                    
JS;
        
else :
    
$js = <<<"JS"
        
    var _x = [];
    var _z = [];
    var _series = [];

    for(var i = 0; i < _data.dataProvider.x.length; i++)
    {
        _x.push(_data.dataProvider.x[i] || "null");         
    }
        
    if(_x.length === 0 && _data.elementoAtual == null)
    {
        _x = ['Total'];
    }

    for(var i = 0; i < _data.dataProvider.z.length; i++)
    {
        _z.push(_data.dataProvider.z[i] || "null");         
    }

    var _index_s = 0;
    var _out = [];
    var _title = '';
        
    $.each(_data.dataProvider.data, function(serie_name, data_x)
    {
        var _dataserie = [];
        var _dataout = [];

        $.each(_x, function(index_x, name)
        {
            _value = data_x[name];

            if(typeof _value === 'undefined')
            {
                _value = 0;
            }

            if(_value == null)
            {
                _value = 'null';
            }

            if(_index_s == 0)
            {
                _dataserie.push({'value': _value});
            }
            else
            {
                _dataout.push({'name': name,'value': _value});
            }
        });

        if(_index_s == 0)
        {
            _series.push({$data});
        }
        else
        {
            _out.push({series: [{'data': _dataout}]});
        }
                    
        _index_s++;
    });
        
JS;
    
endif;
        
echo $js;