<?php

use app\magic\SqlMagic;
use app\magic\GraficoMagic;
use app\magic\MobileMagic;
use app\magic\ResultMagic;

$uns_data = SqlMagic::unserializeToken($data['token']);
$breadcrumbs = $uns_data['breadcrumb'];
$tipo_grafico = (isset($data['tipoGrafico'])) ? $data['tipoGrafico'] : 'column';
$ajaxUrl = "'/shipped/data?id=' + _id + '&color={$color}'";
$view = (MobileMagic::isMobile()) ? 'mobile' : 'content';
$sum = ResultMagic::format($data['totalizador'], $data['campos']['y'], $data['campos']['tipo_numero'], TRUE);

$variaveis = 
[
    'data' => $data,
    'tipo_grafico' => $tipo_grafico,
    'model' => $model,
    'index' => $index,
    'url' => $ajaxUrl,
    'view' => $view
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
        
CSS;

$this->registerCss($css);

if($color):

$css = <<<CSS

        .table__preview thead th, .dataTables_wrapper thead th, .dataTables_wrapper tbody tr.sum-total td, .table__preview tbody tr.sum-total td {
            background-color: #{$color} !important;
        }
        
        .card.card-consulta .card-footer span,
         .card.card-consulta .card-header h4 span,
          .card.card-consulta .card-header h4 {
            color: #{$color};
        }
        
        div.dataTables_wrapper div.dataTables_filter input, div.dataTables_wrapper div.dataTables_length select {
            border: 1px solid #{$color};
        }
        
        .card.card-consulta:hover, .card.card-consulta.active {
            border-color: #{$color};
        }
        
        a {
            color: #{$color} !important;
        }
        
        .card.card-consulta .card-value {
            color: #{$color} !important;
        }
        
        .card {
            border: none !important;
        }
        
        .card--kpi {
            border: 1px solid #{$color} !important;
        }
        
        .update-graph, .update-graph:hover {
            border: none !important;
            color: #{$color} !important;
            background-color: #FFF;
        }

CSS;

$this->registerCss($css);

endif;

if($model->javascript):

    $this->registerJs($model->javascript);

endif;

?>

<div class="card-header d-flex align-item-center justify-content-end get-current-data" data-token="<?= $data['token'] ?>" data-index="<?= $index ?>">
    
    <h4 id="breadcrumb-graph" class="mr-auto align-self-center text-uppercase">

        <?php 

        $string_breadcrumb = $model->nome;
                
        if($breadcrumbs)
        {
            foreach($breadcrumbs as $i => $breadcrumb)
            {
                $string_breadcrumb .= ' <i class="bp-arrow-right"></i> ';
                $nome_filho = ($breadcrumb['valor'] && !empty(trim($breadcrumb['valor']))) ? $breadcrumb['valor'] : ($i + 1) . 'º Nível';
                $string_breadcrumb .= ' <span class="btn-link breadcrumb-cp" title="Clique para alterar o ' . $nome_filho . '" data-index="' . $i . '">' . mb_substr($nome_filho, 0, 20) . '</span>';
            }
        }

        $string_breadcrumb .= ' <i class="bp-arrow-right"></i> ';

        $nome_campo = (isset($data['elementoAtual'])) ? $data['elementoAtual']->campo->nome : 'Total';
        
        $string_breadcrumb .= ' <span style="color:#181f1c;">' . mb_substr($nome_campo, 0, 80) . ' ( ' . $sum . ' )</span>';

        echo $string_breadcrumb; 
        
        ?>
               
    </h4>

    <i class="ml-auto mr-1 align-self-center bp-reload update-graph breadcrumb-cp" data-index="0"></i>

</div>

<div class="card-block ">

    <div id="rendergraph" class="chart-box h-100" data-index="<?= $index ?>" data-token="<?= $data['token'] ?>" style="overflow-y: auto; overflow-x: hidden;text-align: left;">

        <?= $this->render('/_graficos/js/graficos/' . $data_grafico[$tipo_grafico], $variaveis); ?>

    </div>

</div>