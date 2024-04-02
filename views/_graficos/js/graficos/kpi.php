<?php

use app\magic\DataProviderMagic;
use app\magic\ResultMagic;

$json_data = str_replace("\u0022","\\\\\"",json_encode($data, JSON_HEX_QUOT));
$dataProvider = DataProviderMagic::getData($data['dataProvider'], 'kpi', FALSE, $data['campos']);
$filter = (isset($dataProvider['filter'])) ? str_replace("\u0022","\\\\\"",json_encode($dataProvider['filter'], JSON_HEX_QUOT)) : '{}';

$this->render('/_graficos/js/breadcrumb', ['url' => $url, 'token' => $data['token'], 'model_id' => $model->id, 'index' => $index]);

$js = <<<"JS"
    
    function filtrar() 
    {
        var _element = $(this);
        var _token = '{$data['token']}';
        var _index = parseInt('{$index}');
        var _id = parseInt('{$model->id}');
        var _last = ("{$data['ultimo']}" === "1");

        if(!_last)
        {
            var _dataFiltro = {$filter};
            
            var _idx = $(this).data('idx');
            var _category = _dataFiltro[_idx];

            if(!_category)
            {
                _category = 'null';
            }

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

    $('.card--kpi').on("click", filtrar);

JS;

$this->registerJs($js);

$idx = 0;

?>

<div class="row">

    <?php if(isset($dataProvider['data'])) : ?> 
    
        <?php foreach($dataProvider['data'] as $nome_x => $valor) : ?> 

            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">

                <div data-mh="painel-group-00<?= $index ?>" data-idx="<?= $idx; ?>" data-value="<?= ($nome_x) ? $nome_x : 'null' ?>" class="card card--kpi m-2  <?= ($data['ultimo'] != 1) ? 'cursor-pointer' : 'cursor-nodrop'; ?>" style="height: 125px;">

                    <div class="card-header d-flex align-item-center justify-content-end">

                        <h4 class="mr-auto align-self-center text-uppercase"><?= ($nome_x) ? $nome_x : 'null' ?></h4>

                    </div>

                    <div class="card-block pt-0">

                        <label class="card-label mb-0" style="font-size: 0.5rem;"><?= $data['nomes']['y']; ?></label>

                        <?php 
                    
                            $porcentagem_title = ($data['totalizador'] != 0) ? number_format(($valor / $data['totalizador']) * 100, 2, ',', '.') . '%' : '0%'; 
                            $value_title = ResultMagic::format($valor, $data['campos']['y'], 1,  TRUE);
                        ?>
                        
                        <p class="card-value" data-val="<?= $valor ?>" title="<?= "{$value_title} ({$porcentagem_title})" ?>" style="font-size: 3rem;"><?= ResultMagic::format($valor, $data['campos']['y'], $data['campos']['tipo_numero'], TRUE); ?></p>

                    </div>

                </div>

            </div>

            <?php $idx++; ?>

        <?php endforeach; ?>
        
    <?php endif; ?>

</div>

