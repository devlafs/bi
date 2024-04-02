<?php

use app\magic\DataProviderMagic;
use app\magic\GraficoMagic;
use app\magic\ResultMagic;

$json_data = str_replace("\u0022","\\\\\"",json_encode($data, JSON_HEX_QUOT));

$colspan = (isset($data['nomes']['w'])) ? sizeof($data['nomes']['w']) + 2 : 2;

$campo_id = (isset($data['campos']['x']['id'])) ? $data['campos']['x']['id'] : null;
$configuracao = GraficoMagic::getData($model->id, $view, 'table', $campo_id, !!$data['series']);
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
    
    $(function()
    {
        $('table#datagrid{$index}{$square} .select-el').on("click", function(e)
        {
            var _last = ("{$data['ultimo']}" === "1");
            var param = $(this).data('val');
            if(!_last)
            {
                 filtrar{$model->id}(param, true, "{$nome_campo}");
                {$filtrar_string}   
            }
        });
            
        $('table#datagrid{$index}{$square} .select-el a').on('click', function(e){
            e.stopPropagation(); 
        });
            
        var groupColumn = 0;
        var _colspan = {$colspan};

        var table = $('#datagrid{$index}{$square}').DataTable({$configuracao->data});
        
        $('#datagrid{$index}{$square} tbody').on('click', 'tr.group', function() 
        {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc')
            {
                table.order([groupColumn, 'desc']).draw();
            }
            else 
            {
                table.order([groupColumn, 'asc']).draw();
            }
        });
    });

    window.filtrar{$model->id} = function(param, next, _field) 
    {
        var _token = '{$data['token']}';
        var _index = parseInt('{$index}');
        var _id = parseInt('{$model->id}');
        var _category = param;
        var _last = ("{$data['ultimo']}" === "1");
        var _i = (next) ? _index + 1 : _index;
        var _url = (next) ? {$url} + '&own=1' : {$url};
        var _data = getDataForm();
        
        if(!_category)
        {
            _category = 'null';
        }
        
        if(!_last || !next)
        {
            var _csrfToken = $('meta[name="csrf-token"]').attr("content");

            jQuery.ajax({
                url: _url,
                type: 'POST',
                data: 
                {
                    index: _i,
                    filtro: _category,
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
    };
    
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

$dataProvider = DataProviderMagic::getData($data['dataProvider'], 'table', $data['series']);

?>

<table id="datagrid<?= $index; ?><?= $square ?>" class="table table-hover table-bordered" cellspacing="0" width="100%">

    <thead class="thead-default">

    <tr>

        <?php if($data['series']) : ?>

            <th><?= ($data['nomes']['z']) ? $data['nomes']['z'] : 'null'; ?></th>

        <?php endif; ?>

        <th><?= ($data['nomes']['x']) ? $data['nomes']['x'] : 'null'; ?></th>

        <?php if(isset($data['nomes']['w'])) :

            foreach($data['nomes']['w'] as $nome_w) : ?>

                <th><?= $nome_w ?></th>

            <?php endforeach;

        endif; ?>

        <th class="text-right"><?= $data['nomes']['y']; ?></th>

    </tr>

    </thead>

    <tbody>

    <?php foreach($dataProvider['data'] as $nome_serie => $valores) : ?>

        <?php foreach($valores['data'] as $valor) : ?>

            <tr class="select-el <?= ($data['ultimo'] != 1) ? 'cursor-pointer' : 'cursor-nodrop'; ?>" data-val="<?= (isset($valor['x'])) ? $valor['x'] : 'null' ?>">

                <?php if($data['series']) : ?>

                    <td><?= ($nome_serie) ? mb_strtoupper(ResultMagic::format($nome_serie, $data['campos']['z'], 1, TRUE)) : 'null' ?></td>

                <?php endif; ?>

                <td data-order="<?= (isset($valor['x'])) ? $valor['x'] : '' ?>">

                    <?php

                    if(!$data['elementoAtual'])
                    {
                        echo Yii::t('app', 'view.geral.total');
                    }
                    elseif(isset($valor['x']))
                    {
                        $x_value = ResultMagic::format($valor['x'], $data['campos']['x'], 1, TRUE);

                        if($data['links'] && isset($data['links']['x']))
                        {
                            $link = str_replace('{valor}', $valor['x'], $data['links']['x']);
                            echo "<a href='{$link}' target='_blank'>{$x_value}</a>";
                        }
                        else
                        {
                            echo $x_value;
                        }
                    }
                    else
                    {
                        echo 'null';
                    }

                    ?>

                </td>

                <?php if(isset($data['nomes']['w'])) :

                foreach($data['nomes']['w'] as $index_w => $nome_w) :

                $w_value = (isset($valor['w' . $index_w])) ? ResultMagic::format($valor['w' . $index_w], $data['campos']['w' . $index_w], 1, TRUE) : '-';

                ?>

                <td data-order="<?= (isset($valor['w' . $index_w])) ? $valor['w' . $index_w] : '' ?>">

                    <?php

                    if($data['links'] && isset($data['links'][$nome_w]) && isset($valor['w' . $index_w]))
                    {
                        $link = str_replace('{valor}', $valor['w' . $index_w], $data['links'][$nome_w]);
                        echo "<a href='{$link}' target='_blank'>{$w_value}</a>";
                    }
                    else
                    {
                        echo "{$w_value}";
                    }

                    echo "</td>";

                    ?>

                    <?php endforeach;

                    endif; ?>

                    <?php

                    $porcentagem_title = ($data['totalizador'] != 0) ? number_format(($valor['y'] / $data['totalizador']) * 100, 2, ',', '.') . '%' : '0%';
                    $value_title = ResultMagic::format($valor['y'], $data['campos']['y'], 1,  TRUE);
                    ?>

                <td data-order="<?= $valor['y'] ?>" data-val="<?= $valor['y'] ?>" title="<?= "{$value_title} ({$porcentagem_title})" ?>" class="text-right"><?= ResultMagic::format($valor['y'], $data['campos']['y'], $data['campos']['tipo_numero'],  TRUE) . "  <span style='font-size: 9px;'>({$porcentagem_title})</span>"; ?></td>

            </tr>

        <?php endforeach; ?>

    <?php endforeach; ?>

    </tbody>

</table>