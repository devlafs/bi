<?php

use app\magic\DataProviderMagic;
use app\magic\GraficoMagic;

$dataProvider = DataProviderMagic::getData($data['dataProvider'], $tipo_grafico, $data['series'], $data['campos']);
$dataColor = (isset($dataProvider["x"])) ? DataProviderMagic::getDataColor($model->id, $data['campos']["x"]["id"], $dataProvider["x"]) : [];
$data['dataProvider'] = $dataProvider;
$data['dataColor'] = $dataColor;
$json_data = str_replace("\u0022", "\\\\\"", json_encode($data, JSON_HEX_APOS|JSON_HEX_QUOT));

$configuracao = GraficoMagic::getData($model->id, $view, $tipo_grafico, null, !!$data['series']);

$prepend = (isset($data['campos']['y'])) ? $data['campos']['y']['prefixo'] : '';
$pospend = (isset($data['campos']['y'])) ? $data['campos']['y']['sufixo'] : '';

$tipo = '1';

if(isset($data['campos']['y']))
{
    $tipo = $data['campos']['tipo_numero'];
    $this->render('/_graficos/js/formatter', ['campo' => $data['campos']['y'], 'tipo_numero' => $tipo]);
}

$date = time();

$variaveis = (!$data['series']) ? $this->render("/_graficos/js/vars/common/multi") : 
$this->render("/_graficos/js/vars/serie/multi", ['data' => $configuracao->data_serie, 'tipo_serializacao' => $model->tipo_serializacao]);

$timeline = ($model->tipo_serializacao == 2 && $data['series']) ? "delete option.options[0]['legend']; delete option.options[0]['dataZoom']; $.merge(option.options, _out);" : "";
$option = ($model->tipo_serializacao == 2 && $data['series']) ? "option = {{$configuracao->data_timeline}, options:[{$configuracao->data}]};" : "option = {$configuracao->data};";
$y_timeline = ($model->tipo_serializacao == 2 && $data['series']) ? "100px" : "50px";
$position_timeline = ($model->tipo_serializacao == 2 && $data['series']) ? 'true' : 'false';

$theme = $this->render("/themes/{$model->pallete->file}.json");

$js = <<<"JS"
    
    function create{$tipo_grafico}(_data)
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
        
        var myChart{$date} = echarts.init(document.getElementById('rendergraph'), '{$model->pallete->file}');

        {$option}
        
        {$timeline}
        
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
            var _data = {$json_data};
            
            if(!_last)
            {
                var _index = parseInt('{$index}');
                var _id = parseInt('{$model->id}');
                var _filtro = _data.filtro;
                var _i = parseInt(_index) + 1;
                var _dataFiltro = _data.dataProvider.filter;
                
                _filtro.push({'nome': _data.coluna, 'valor': _dataFiltro[param.dataIndex]});
                
                var _valor = [];

                $('ul#valor').children().each(function() 
                {
                    _valor.push($(this).data('id'));
                });

                var _argumento = [];

                $('ul#argumento').children().each(function() 
                {
                    _argumento.push({'id': $(this).data('id'), 'type': $(this).data('type'), 'sort': $(this).data('sort'), 'tipo_numero': $(this).data('tipo_numero')});
                });

                var _serie = [];

                $('ul#serie').children().each(function() 
                {
                    _serie.push($(this).data('id'));
                });
                
                _post = {'index': _i, 'valor': _valor, 'argumento': _argumento, 'serie': _serie, 'filtro': _filtro};

                jQuery.ajax({
                url: '{$url}',
                    type: 'POST',
                    data: _post,
                    success: function (response) 
                    {
                        $('.consulta__preview').html(response);
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
