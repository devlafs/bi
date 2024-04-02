<?php

use app\magic\DataProviderMagic;
use app\magic\GraficoMagic;
use app\magic\ResultMagic;

$json_data = str_replace("\u0022","\\\\\"",json_encode($data, JSON_HEX_APOS|JSON_HEX_QUOT));

$colspan = (isset($data['nomes']['w'])) ? sizeof($data['nomes']['w']) + 2 : 2;
$configuracao = GraficoMagic::getData($model->id, $view, 'table', null, !!$data['series']);

$js = <<<"JS"
    
    $(function()
    {
        $('table#datagrid{$index} .select-el').on("click", function(e)
        {
            var _val = $(this).data('val');
            var _data = JSON.parse('{$json_data}');
            var _index = parseInt('{$index}');
            var _id = parseInt('{$model->id}');
            var _last = ("{$data['ultimo']}" === "1");
        
            if(!_last)
            {
                selectEl(_val, _data, _index, _id);
            }
        });
            
        $('table#datagrid{$index} .select-el a').on('click', function(e){
            e.stopPropagation(); 
        });
            
        var groupColumn = 0;
        var _colspan = {$colspan};

        var table = $('#datagrid{$index}').DataTable({$configuracao->data});

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

    function selectEl(_val, _data, _index, _id)
    {
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

        var _filtro = _data.filtro;
        
        var _i = parseInt(_index) + 1;
            
        _categoria = _val;
        
        if(!_categoria)
        {
            _categoria = 'null';
        }
        
        _coluna = _data.coluna;
        _filtro.push({'nome': _coluna, 'valor': _categoria});

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
    };

JS;

$this->registerJs($js);

if($data) : 
        
    $dataProvider = DataProviderMagic::getData($data['dataProvider'], 'table', $data['series']);
        
?>

<div class="d-flex justify-content-center align-item-center w-100">

    <table id="datagrid<?= $index; ?>" class="table table-hover table-bordered w-100" cellspacing="0" width="100%">

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

                    <tr class="select-el  <?= ($data['ultimo'] != 1) ? 'cursor-pointer' : 'cursor-nodrop'; ?>" data-val="<?= (isset($valor['x'])) ? $valor['x'] : 'null' ?>">

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
                                echo ResultMagic::format($valor['x'], $data['campos']['x'], 1, TRUE);
                            }
                            else
                            {
                                echo 'null';
                            }
                            
                            ?>
                        
                        </td>
                        
                        <?php if(isset($data['nomes']['w'])) :

                            foreach($data['nomes']['w'] as $index_w => $nome_w) : ?>

                                <td data-order="<?= (isset($valor['w' . $index_w])) ? $valor['w' . $index_w] : '' ?>"><?= (isset($valor['w' . $index_w])) ? ResultMagic::format($valor['w' . $index_w], $data['campos']['w' . $index_w], 1, TRUE) : '-' ?></td>

                            <?php endforeach;

                        endif; ?>

                        <td data-order="<?= $valor['y'] ?>"  class="text-right"><?= ResultMagic::format($valor['y'], $data['campos']['y'], $data['campos']['tipo_numero'], TRUE); ?></td>

                    </tr>
                    
                <?php endforeach; ?>
            
            <?php endforeach; ?>

        </tbody>

    </table>

</div>

<?php else : ?>
    
    <?= $this->render("/consulta-update/preview/_empty-table"); ?>
    
<?php endif; ?>