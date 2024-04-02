<?php

use app\magic\SqlMagic;
use app\magic\GraficoMagic;
use app\magic\MobileMagic;
use app\magic\ResultMagic;

$uns_data = SqlMagic::unserializeToken($data['token']);
$breadcrumbs = $uns_data['breadcrumb'];
$tipo_grafico = (isset($data['tipoGrafico'])) ? $data['tipoGrafico'] : 'column';
$ajaxUrl = "'/content/data?id_painel={$painel->id}&id_consulta=' + _id + '&square={$square}'";
$view = (MobileMagic::isMobile()) ? 'mobile' : 'content';
$sum = ResultMagic::format($data['totalizador'], $data['campos']['y'], $data['campos']['tipo_numero'], TRUE);

$variaveis = 
[
    'data' => $data,
    'tipo_grafico' => $tipo_grafico,
    'model' => $model,
    'index' => $index,
    'url' => $ajaxUrl,
    'view' => $view,
    'square' => $square,
    'data_conected' => $data_conected
];

$data_grafico = GraficoMagic::$data_grafico;

$css = <<<CSS
        
    .bp-arrow-right
    {
        font-size: 8px;
    }
    
    .card.card-consulta .card-header h4 span 
    {
        font-size: 0.8rem;
    }
    
    .card {
        border: none !important;
    }
    
    .card--kpi {
        border: 1px solid rgba(0,0,0,.125) !important;
    }
    
    .btn-link {
        cursor: pointer;
    }
    
    .card-block-content
    {
        min-height: 200px;
    }
    
    .grid-stack .grid-stack-item[data-gs-height="12"] .card-block-content {
        height: 830px !important;
    }
    
    .grid-stack .grid-stack-item[data-gs-height="11"] .card-block-content {
        height: 760px !important;
    }

    .grid-stack .grid-stack-item[data-gs-height="10"] .card-block-content {
        height: 690px !important;
    }

    .grid-stack .grid-stack-item[data-gs-height="9"] .card-block-content {
        height: 620px !important;
    }

    .grid-stack .grid-stack-item[data-gs-height="8"] .card-block-content {
        height: 550px !important;
    }

    .grid-stack .grid-stack-item[data-gs-height="7"] .card-block-content {
        height: 480px !important;
    }

    .grid-stack .grid-stack-item[data-gs-height="6"] .card-block-content {
        height: 410px !important;
    }

    .grid-stack .grid-stack-item[data-gs-height="5"] .card-block-content {
        height: 340px !important;
    }

    .grid-stack .grid-stack-item[data-gs-height="4"] .card-block-content {
        height: 270px !important;
    }

    .grid-stack .grid-stack-item[data-gs-height="3"] .card-block-content {
        height: 200px !important;
    }

CSS;

$this->registerCss($css);

if($model->javascript):

    $this->registerJs($model->javascript);

endif;

?>

<div class="card-header d-flex align-item-center justify-content-end get-current-data" data-token="<?= $data['token'] ?>" data-index="<?= $index ?>">
    
    <h4 id="breadcrumb-graph" class="w-100 text-uppercase text-center">

        <?php

//        $string_breadcrumb = " <a class='btn-link' href='javascript:void(0)' style='font-size: 12px;' onclick='window.open(\"/consulta/visualizar/{$model->id}\", \"_blank\")' title='Clique para visualizar a consulta'>{$model->nome}</a> <br>";
        $string_breadcrumb = " {$model->nome} <br>";

        if($breadcrumbs)
        {
            foreach($breadcrumbs as $i => $breadcrumb)
            {
                $string_breadcrumb .= ' <i class="bp-arrow-right"></i> ';
                $nome_filho = ($breadcrumb['valor'] && !empty(trim($breadcrumb['valor']))) ? $breadcrumb['valor'] : ($i + 1) . 'º Nível';
                if($data_conected && isset($data_conected[$model->id_indicador]) && in_array($model->id, $data_conected[$model->id_indicador]))
                {
                    $string_breadcrumb .= ' <span style="color:#181f1c; font-size: 8px;" title="' . $breadcrumb['nome'] . '">' . mb_substr($nome_filho, 0, 20) . '</span>';
                }
                else
                {
                    $string_breadcrumb .= ' <span class="btn-link breadcrumb-cp' . $square . '" title="Clique para alterar o ' . $breadcrumb['nome'] . '" data-index="' . $i . '">' . mb_substr($nome_filho, 0, 20) . '</span>';
                }
            }
        }

        $string_breadcrumb .= ' <i class="bp-arrow-right"></i> ';

        $nome_campo = (isset($data['elementoAtual'])) ? $data['elementoAtual']->campo->nome : 'Total';

        $string_breadcrumb .= ' <span style="color:#181f1c; font-size: 8px;">' . mb_substr($nome_campo, 0, 80) . ' ( ' . $sum . ' )</span>';

        echo $string_breadcrumb;
        
        ?>
               
    </h4>

</div>

<div class="card-block card-block-content h-100">

    <div id="rendergraph<?= $square ?>" class="chart-box h-100" data-index="<?= $index ?>" data-token="<?= $data['token'] ?>" style="overflow-y: auto; overflow-x: hidden;text-align: left;">

        <?= $this->render('/_graficos/js/paineis/' . $data_grafico[$tipo_grafico], $variaveis); ?>

    </div>

</div>