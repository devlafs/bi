<?php

use app\magic\DataProviderMagic;
use app\magic\GraficoMagic;

$dataProvider = DataProviderMagic::getData($data['dataProvider'], $tipo_grafico, $data['series'], $data['campos']);
$dataColor = (isset($dataProvider["x"])) ? DataProviderMagic::getDataColor($model->id, $data['campos']["x"]["id"], $dataProvider["x"]) : [];
$data['dataProvider'] = $dataProvider;
$data['dataColor'] = $dataColor;
$json_data = str_replace("\u0022", "\\\\\"", json_encode($data, JSON_HEX_QUOT));

$campo_id = (isset($data['campos']['x']['id'])) ? $data['campos']['x']['id'] : null;
$configuracao = GraficoMagic::getData($model->id, $view, $tipo_grafico, $campo_id, !!$data['series']);

$prepend = (isset($data['campos']['y'])) ? $data['campos']['y']['prefixo'] : '';
$pospend = (isset($data['campos']['y'])) ? $data['campos']['y']['sufixo'] : '';

$date = time();

$tipo = '1';

if(isset($data['campos']['y']))
{
    $tipo = $data['campos']['tipo_numero'];
    $this->render('/_graficos/js/formatter', ['campo' => $data['campos']['y'], 'tipo_numero' => $tipo]);
}


$this->render('/_graficos/js/breadcrumb', ['url' => $url, 'token' => $data['token'], 'model_id' => $model->id, 'index' => $index]);

$variaveis = $this->render("/_graficos/js/vars/serie/heatmap");

$option = "option = {$configuracao->data};";

$theme = $this->render("/themes/{$model->pallete->file}.json");

$js = <<<"JS"
    
    function create{$tipo_grafico}(_data)
    {
        var _prepend = '{$prepend}';
        var _pospend = '{$pospend}';
        var _filename = '{$model->nome}';
        var _tipo = '{$tipo}';
        var _last = ("{$data['ultimo']}" === "1");

        {$variaveis}
    
        var themeJSON = {$theme};
        echarts.registerTheme('{$model->pallete->file}', themeJSON);
        
        var myChart{$date} = echarts.init(document.getElementById('rendergraph'), '{$model->pallete->file}');

        {$option}

        myChart{$date}.setOption(option, true), $(function() 
        {
            function resize() 
            {
                setTimeout(function() 
                {
                    myChart{$date}.resize()
                }, 1000)
            }
        
            $(window).on("resize", resize), $("#menu-toggle").on("click", resize)
        });
            
        function filtrar(param) 
        {
            var _token = '{$data['token']}';
            var _index = parseInt('{$index}');
            var _id = parseInt('{$model->id}');
            
            if(!_last)
            {
                var _dataFiltro = _data.dataProvider.filter;
                var _category = _dataFiltro[param.value[1]];
            
                var _i = _index + 1;
                var _csrfToken = $('meta[name="csrf-token"]').attr("content");
            
                jQuery.ajax({
                    url: {$url},
                    type: 'POST',
                    data: 
                    {
                        index: _i,
                        filtro: _category,
                        token: _token,
                        _csrf: _csrfToken
                    },
                    success: function (data) 
                    {
                        $('.card-consulta').html(data);
                    },
                    beforeSend: function ()
                    {
                        $('.div-loading').addClass("loading");
                    },
                    complete: function () 
                    {
                        setTimeout(function() { $('.div-loading').removeClass("loading");}, 300);
                    }
                });
            }
        }

        myChart{$date}.on("click", filtrar);
        
        myChart{$date}.on('mousemove', function (params) 
        {
            if(_last)
            {
                myChart{$date}.getZr().setCursorStyle('no-drop');
            }
        });
    }
        
    create{$tipo_grafico}({$json_data});

JS;

$this->registerJs($js);
