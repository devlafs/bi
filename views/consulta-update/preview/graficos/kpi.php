<?php

use app\magic\DataProviderMagic;
use app\magic\ResultMagic;

$json_data = str_replace("\u0022","\\\\\"",json_encode($data, JSON_HEX_APOS|JSON_HEX_QUOT));
$dataProvider = DataProviderMagic::getData($data['dataProvider'], 'kpi', FALSE, $data['campos']);
$filter = (isset($dataProvider['filter'])) ? str_replace("\u0022","\\\\\"",json_encode($dataProvider['filter'], JSON_HEX_APOS|JSON_HEX_QUOT)) : '{}';

$js = <<<"JS"
    
    function filtrar() 
    {
        var _data = {$json_data};
        var _last = ("{$data['ultimo']}" === "1");

        if(!_last)
        {
            var _index = parseInt('{$index}');
            var _id = parseInt('{$model->id}');
            var _filtro = _data.filtro;
            var _i = parseInt(_index) + 1;
            var _dataFiltro = {$filter};
            
            var _idx = $(this).data('idx');
            var _value = _dataFiltro[_idx];

            if(!_value)
            {
                _value = 'null';
            }

            _filtro.push({'nome': _data.coluna, 'valor': _value});

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

    $('.card--kpi').on("click", filtrar);

JS;

$this->registerJs($js);

$idx = 0;

?>

<div class="row">
    
    <?php if(isset($dataProvider['data'])) : ?> 
    
        <?php foreach($dataProvider['data'] as $nome_x => $valor) : ?> 

            <?php if($idx < 4) : ?>

                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div data-mh="painel-group-00<?= $idx ?>" data-idx="<?= $idx; ?>" data-value="<?= ($nome_x) ? $nome_x : 'null' ?>" class="card card-consulta card--kpi  <?= ($data['ultimo'] != 1) ? 'cursor-pointer' : 'cursor-nodrop'; ?>" style="height: 125px;">

                        <div class="card-header d-flex align-item-center justify-content-end">

                            <h4 class="mr-auto align-self-center text-uppercase">

                                <?= ($nome_x) ? $nome_x : 'null' ?>

                            </h4>

                        </div>

                        <div class="card-block pt-0">

                            <label class="card-label mb-0" style="font-size: 0.5rem;"><?= $data['nomes']['y']; ?></label>

                            <p class="card-value" style="font-size: 3rem;"><?= ResultMagic::format($valor, $data['campos']['y'], $data['campos']['tipo_numero'], TRUE); ?></p>

                        </div>

                    </div>

                </div>

                <?php $idx ++; ?>

            <?php endif; ?>

        <?php endforeach; ?>
    
    <?php endif; ?>
    
</div>

