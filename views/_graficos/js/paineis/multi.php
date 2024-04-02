<?php

use app\magic\DataProviderMagic;
use app\magic\GraficoMagic;

$dataProvider = DataProviderMagic::getData($data['dataProvider'], $tipo_grafico, $data['series'], $data['campos']);
$dataColor = (isset($dataProvider["x"])) ? DataProviderMagic::getDataColor($model->id, $data['campos']["x"]["id"], $dataProvider["x"]) : [];
$data['dataProvider'] = $dataProvider;
$data['dataColor'] = $dataColor;
$json_data = str_replace("\u0022", "\\\\\"", json_encode($data, JSON_HEX_QUOT));
$nome_campo = $data['field'];

$campo_id = (isset($data['campos']['x']['id'])) ? $data['campos']['x']['id'] : null;
$configuracao = GraficoMagic::getData($model->id, $view, $tipo_grafico, $campo_id, !!$data['series']);

$prepend = (isset($data['campos']['y'])) ? $data['campos']['y']['prefixo'] : '';
$pospend = (isset($data['campos']['y'])) ? $data['campos']['y']['sufixo'] : '';

$tipo = '1';

if(isset($data['campos']['y']))
{
    $tipo = $data['campos']['tipo_numero'];
    $this->render('/_graficos/js/formatter', ['campo' => $data['campos']['y'], 'tipo_numero' => $tipo]);
}


$this->render('/_graficos/js/paineis/breadcrumb', ['url' => $url, 'token' => $data['token'], 'model_id' => $model->id, 'index' => $index, 'square' => $square]);

$variaveis = (!$data['series']) ? $this->render("/_graficos/js/vars/common/multi") : 
$this->render("/_graficos/js/vars/serie/multi", ['data' => $configuracao->data_serie, 'tipo_serializacao' => $model->tipo_serializacao]);

$timeline = ($model->tipo_serializacao == 2 && $data['series']) ? "delete option.options[0]['legend']; delete option.options[0]['dataZoom']; $.merge(option.options, _out);" : "";
$option = ($model->tipo_serializacao == 2 && $data['series']) ? "option = {{$configuracao->data_timeline}, options:[{$configuracao->data}]};" : "option = {$configuracao->data};";
$y_timeline = ($model->tipo_serializacao == 2 && $data['series']) ? "100px" : "50px";
$position_timeline = ($model->tipo_serializacao == 2 && $data['series']) ? 'true' : 'false';

$theme = $this->render("/themes/{$model->pallete->file}.json");

$filtrar_string = '';
$is_conected = false;

foreach($data_conected as $id_indcon => $conected)
{
    if($id_indcon == $model->id_indicador)
    {
        foreach($conected as $id_concon)
        {
            if($id_concon != $model->id)
            {
                $filtrar_string .= ' filtrar' . $id_concon . '(param, false, "'. $nome_campo . '");';
            }
            else
            {
                $is_conected = true;
            }
        }
    }
}

if(!$is_conected)
{
    $filtrar_string = '';
}

$js = <<<"JS"
    
    function create{$tipo_grafico}{$square}(_data)
    {
        var _prepend = '{$prepend}';
        var _pospend = '{$pospend}';
        var _filename = '{$model->nome}';
        var _tipo = '{$tipo}';
        var _ytimeline = '{$y_timeline}';
        var _postimeline = {$position_timeline};
        var _last = ("{$data['ultimo']}" === "1");

        {$variaveis}
    
        var themeJSON = {$theme};
        echarts.registerTheme('{$model->pallete->file}', themeJSON);
        
        var myChart{$square} = echarts.init(document.getElementById('rendergraph{$square}'), '{$model->pallete->file}');

        {$option}
        
        {$timeline}

        myChart{$square}.setOption(option, true), $(function() 
        {
            function resize() 
            {
                setTimeout(function() 
                {
                    myChart{$square}.resize()
                }, 1000)
            }
        
            $(window).on("resize", resize), $("#menu-toggle").on("click", resize)
        });
            
        window.filtrar{$model->id} = function(param, next, _field) 
        {
            var _token = '{$data['token']}';
            var _index = parseInt('{$index}');
            var _id = parseInt('{$model->id}');
            var _i = (next) ? _index + 1 : _index;
            var _url = (next) ? {$url} + '&own=1' : {$url};
            var _data = getDataForm();
            
            if(!_last || !next)
            {
                var _csrfToken = $('meta[name="csrf-token"]').attr("content");
            
                jQuery.ajax({
                    url: _url,
                    type: 'POST',
                    data: 
                    {
                        index: _i,
                        filtro: param,
                        field: _field,
                        token: _token,
                        data: _data,
                        _csrf: _csrfToken
                    },
                    success: function (data) 
                    {
                        $('.card-consulta{$square}').html(data);
                    },
                    beforeSend: function ()
                    {
                        $('.div-loading-{$square}').addClass("loading");
                    },
                    complete: function () 
                    {
                        setTimeout(function() { $('.div-loading-{$square}').removeClass("loading");}, 300);
                    }
                });
            }
        }

        myChart{$square}.on("click", function(e){
            var param = e.name;
            var _dataFiltro = _data.dataProvider.filter;
            var _category = _dataFiltro[e.dataIndex];
        
            if(!_last)
            {
                filtrar{$model->id}(_category, true, "{$nome_campo}");
                {$filtrar_string} 
            }
        });
        
        myChart{$square}.on('mousemove', function (params) 
        {
            if(_last)
            {
                myChart{$square}.getZr().setCursorStyle('no-drop');
            }
        });
        
        window.filtrarP{$model->id} = function() 
        {
            var _csrfToken = $('meta[name="csrf-token"]').attr("content");
            var _token = '{$data['token']}';
            var _index = parseInt('{$index}');
            var _id = parseInt('{$model->id}');
            
            var _data = getDataForm();
            
            if(_data)
            {
                jQuery.ajax({
                    url: {$url},
                    type: 'POST',
                    data: 
                    {
                        index: _index,
                        data: _data,
                        token: _token,
                        _csrf: _csrfToken
                    },
                    success: function (data) 
                    {
                        $('.card-consulta{$square}').html(data);
                    },
                    beforeSend: function ()
                    {
                        $('.div-loading-{$square}').addClass("loading");
                    },
                    complete: function () 
                    {
                        setTimeout(function() { $('.div-loading-{$square}').removeClass("loading");}, 300);
                    }
                });
            }
        }
    }
        
    create{$tipo_grafico}{$square}({$json_data});

JS;

$this->registerJs($js);
