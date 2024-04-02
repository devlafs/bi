<?php

use app\magic\DataProviderMagic;
use app\magic\SqlMagic;
use app\magic\ResultMagic;

$json_data = str_replace("\u0022","\\\\\"",json_encode($data, JSON_HEX_QUOT));

$precision = ($data['tipo_valor'] == 'valor' || $data['tipo_valor'] == 'formulavalor') ? 2 : 0;
$prepend = (isset($data['campos']['y'])) ? $data['campos']['y']['prefixo'] : '';
$pospend = (isset($data['campos']['y'])) ? $data['campos']['y']['sufixo'] : '';

$colspan = (isset($data['nomes']['w'])) ? sizeof($data['nomes']['w']) + 2 : 2;

$dataProvider = DataProviderMagic::getData($data['dataProvider'], 'table', ($data['series'] && !in_array($data['tipoGrafico'], ['pie', 'donut', 'funnel', 'kpi'])));

$uns_data = SqlMagic::unserializeToken($data['token']);
$breadcrumbs = $uns_data['breadcrumb'];
$sum = ResultMagic::format($data['totalizador'], $data['campos']['y'], $data['campos']['tipo_numero'], TRUE);

if($breadcrumbs)
{
    echo '<table class="table table-bordered" style="margin-top: 50px;" cellspacing="0" width="100%"><thead>';

    foreach($breadcrumbs as $breadcrumb)
    {
        echo "<tr><th style='background-color: #007EC3; color: #FFF; font-size: 12px;'>{$breadcrumb['nome']}</th>";
        echo "<th style='font-size: 12px;'>{$breadcrumb['valor']}</th></tr>";
    }
    echo '</thead></table>';
}

?>

<div>

    <table class="table table-bordered mt-3" style="margin-top: 50px;" cellspacing="0" width="100%">

        <thead>

            <tr>

                <th style="background-color: #007EC3; color: #FFF; font-size: 12px;"><?= isset($data['nomes']['x']) ? $data['nomes']['x'] : '-'; ?></th>

                <?php if(isset($data['nomes']['w'])) :

                        foreach($data['nomes']['w'] as $nome_w) :  ?>

                            <th style="background-color: #007EC3; color: #FFF; font-size: 12px;"><?= $nome_w ?></th>

                    <?php endforeach;

                endif; ?>

                <th class="text-right" style="background-color: #007EC3; color: #FFF; font-size: 12px;"><?= $data['nomes']['y']; ?></th>

            </tr>

        </thead>

        <tbody>

            <?php foreach($dataProvider['data'] as $nome_serie => $valores) : ?> 

                <?php if($data['series'] && !in_array($data['tipoGrafico'], ['pie', 'donut', 'funnel', 'kpi'])) : ?>

                    <tr>
                        
                        <td class="text-center" colspan="<?= $colspan ?>" style="background-color: #e7edff; font-size: 12px;"><?= ($nome_serie) ? mb_strtoupper(ResultMagic::format($nome_serie, $data['campos']['z'], 1, TRUE)) : 'null' ?></td>

                    </tr>

                <?php endif; ?>

                <?php foreach($valores['data'] as $valor) :?>

                    <tr>

                        <td style="font-size: 12px;">

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

                            foreach($data['nomes']['w'] as $index_w => $nome_w) : 

                                $w_value = (isset($valor['w' . $index_w])) ? htmlspecialchars(ResultMagic::format($valor['w' . $index_w], $data['campos']['w' . $index_w], 1, TRUE)) : '-';

                                echo "<td style='font-size: 12px;'>{$w_value}</td>";

                            ?>

                            <?php endforeach;

                        endif; ?>
                        
                        <td class="text-right" style="font-size: 12px;"><?= htmlspecialchars(ResultMagic::format($valor['y'], $data['campos']['y'], $data['campos']['tipo_numero'], TRUE)); ?></td>

                    </tr>

                <?php endforeach; ?>

            <?php endforeach; ?>

            <tr>

                <th colspan="<?= $colspan - 1 ?>"  style="background-color: #007EC3; color: #FFF; font-size: 12px;">Total</th>

                <th class="text-right" style="background-color: #007EC3; color: #FFF; font-size: 12px;"><?= $sum; ?></th>

            </tr>
                    
        </tbody>

    </table>

</div>