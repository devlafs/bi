<?php

use app\magic\DataProviderMagic;
use app\magic\GraficoMagic;
use app\magic\ResultMagic;

$json_data = str_replace("\u0022","\\\\\"",json_encode($data, JSON_HEX_QUOT));

$colspan = (isset($data['nomes']['w'])) ? sizeof($data['nomes']['w']) + 2 : 2;

$campo_id = (isset($data['campos']['x']['id'])) ? $data['campos']['x']['id'] : null;
$configuracao = GraficoMagic::getData($model->id, $view, 'table', $campo_id, !!$data['series']);

$this->render('/_graficos/js/breadcrumb', ['url' => $url, 'token' => $data['token'], 'model_id' => $model->id, 'index' => $index]);

$js = <<<"JS"
    
    $(function()
    {
        $('table#datagrid{$index} .select-el').on("click", function(e)
        {
            var _val = $(this).data('val');
            var _token = '{$data['token']}';
            var _index = parseInt('{$index}');
            var _id = parseInt('{$model->id}');
            var _last = ("{$data['ultimo']}" === "1");
        
            if(!_last)
            {
                selectEl(_val, _token, _index, _id);
            }
        });
            
        $('table#datagrid{$index} .select-el a').on('click', function(e){
            e.stopPropagation(); 
        });
            
        var groupColumn = 0;
        var _colspan = {$colspan};

        var table = $('#datagrid{$index}').DataTable({$configuracao->data});
        // new $.fn.dataTable.FixedHeader(table);
        
        $('#datagrid{$index} tbody').on('click', 'tr.group', function() 
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

    function selectEl(_val, _token, _index, _id)
    {
        var _category = _val;
        
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
    };

JS;

$this->registerJs($js);

$dataProvider = DataProviderMagic::getData($data['dataProvider'], 'table', $data['series']);

?>

<table id="datagrid<?= $index; ?>" class="table table-hover table-bordered" cellspacing="0" width="100%">

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