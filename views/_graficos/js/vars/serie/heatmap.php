<?php

$js = <<<"JS"
    
    var _zh = _data.dataProvider.z;
    var _xd = _data.dataProvider.x;
    var _vdata = _data.dataProvider.data;
    var _max = 0;
    
    var _values = [];
    
    for(var i = 0; i < _zh.length; i++)
    {
        var _zpr = _vdata[_zh[i]];

        for(var j = 0; j < _xd.length; j++)
        {
            _values.push([j, i, _zpr[_xd[j]] || 0]);
            if(_zpr[_xd[j]] > _max)
            {
                _max = _zpr[_xd[j]];
            }
        } 
    }    
    
    var _values = _values.map(function (item) {
        return [item[1], item[0], item[2] || '-'];
    });
    
    var _x = [];
    var _z = [];

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
                    
JS;
        
echo $js;