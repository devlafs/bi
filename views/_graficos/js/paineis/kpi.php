<?php

use app\magic\DataProviderMagic;
use app\magic\ResultMagic;

$json_data = str_replace("\u0022","\\\\\"",json_encode($data, JSON_HEX_QUOT));
$dataProvider = DataProviderMagic::getData($data['dataProvider'], 'kpi', FALSE, $data['campos']);
$filter = (isset($dataProvider['filter'])) ? str_replace("\u0022","\\\\\"",json_encode($dataProvider['filter'], JSON_HEX_QUOT)) : '{}';
$nome_campo = $data['field'];

$this->render('/_graficos/js/paineis/breadcrumb', ['url' => $url, 'token' => $data['token'], 'model_id' => $model->id, 'index' => $index, 'square' => $square]);

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
    
    window.filtrar{$model->id} = function(param, next, _field) 
    {
        var _element = $(this);
        var _id = parseInt('{$model->id}');
        
        var _token = '{$data['token']}';
        var _index = parseInt('{$index}');
        var _last = ("{$data['ultimo']}" === "1");
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

    $('.card--kpi{$square}').on("click", function() {
        var _dataFiltro = {$filter};
            
        var _idx = $(this).data('idx');
        var param = _dataFiltro[_idx];
        var _last = ("{$data['ultimo']}" === "1");

        if(!param)
        {
            param = 'null';
        }
        
        if(!_last)
        {
            filtrar{$model->id}(param, true, "{$nome_campo}");
            {$filtrar_string}   
        }
    });

    function resizeElement(_el, _width)
    {
        _el.removeClass('col-lg-3');
        _el.removeClass('col-md-6');
        _el.removeClass('col-sm-12');
        _el.removeClass('col-xs-12');

        if (_width < 576) {
            _el.addClass('col-sm-12');
        } else if (_width < 768) {
            _el.addClass('col-md-6');
        } else {
            _el.addClass('col-lg-3');
        }
    }

    $(document).ready(function(){
        var _el = $('.content-fluid-{$square}');
        resizeElement(_el.children(), _el.width());
        _el.on('resize',function(){
            resizeElement(_el.children(), _el.width());
        });
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

JS;

$this->registerJs($js);

$idx = 0;

?>

<div class="row content-fluid-<?= $square ?>">

    <?php if(isset($dataProvider['data'])) : ?>

        <?php foreach($dataProvider['data'] as $nome_x => $valor) : ?>

            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">

                <div data-mh="painel-group-00<?= $index ?>" data-idx="<?= $idx; ?>" data-value="<?= ($nome_x) ? $nome_x : 'null' ?>" class="card card--kpi card--kpi<?= $square ?> m-2 <?= ($data['ultimo'] != 1) ? 'cursor-pointer' : 'cursor-nodrop'; ?>" style="height: 100px;">

                    <div class="card-header d-flex align-item-center justify-content-end">

                        <h4 class="mr-auto align-self-center text-uppercase"><?= ($nome_x) ? $nome_x : 'null' ?></h4>

                    </div>

                    <div class="card-block pt-0">

                        <label class="card-label mb-0" style="font-size: 0.5rem;"><?= $data['nomes']['y']; ?></label>

                        <?php

                        $porcentagem_title = ($data['totalizador'] != 0) ? number_format(($valor / $data['totalizador']) * 100, 2, ',', '.') . '%' : '0%';
                        $value_title = ResultMagic::format($valor, $data['campos']['y'], 1,  TRUE);
                        ?>

                        <p class="card-value" data-val="<?= $valor ?>" title="<?= "{$value_title} ({$porcentagem_title})" ?>" style="font-size: 2.9rem;"><?= ResultMagic::format($valor, $data['campos']['y'], $data['campos']['tipo_numero'], TRUE); ?></p>

                    </div>

                </div>

            </div>

            <?php $idx++; ?>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

