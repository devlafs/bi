<?php

use app\magic\SqlMagic;
use app\magic\GraficoMagic;
use app\magic\MobileMagic;
use app\magic\ResultMagic;

$uns_data = SqlMagic::unserializeToken($data['token']);
$breadcrumbs = $uns_data['breadcrumb'];
$tipo_grafico = (isset($data['tipoGrafico'])) ? $data['tipoGrafico'] : 'column';
$ajaxUrl = "'/grafico/view?id=' + _id";

$view = (MobileMagic::isMobile()) ? 'mobile' : 'view';
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

if($model->javascript):

    $this->registerJs($model->javascript);

endif;

?>

<div class="card-header d-flex align-item-center justify-content-end get-current-data" data-token="<?= $data['token'] ?>" data-index="<?= $index ?>">
    
    <h4 id="breadcrumb-graph" class="mr-auto align-self-center text-uppercase">

        <?php 

        $string_breadcrumb = (MobileMagic::isMobile()) ? $model->getPathName() . ' <i class="bp-arrow-right"></i>' : '';
        $has_breadcrumb = FALSE;

        if($breadcrumbs)
        {
            foreach($breadcrumbs as $i => $breadcrumb)
            {
                if($i > 0)
                {
                    $string_breadcrumb .= ' <i class="bp-arrow-right"></i> ';
                }

                $nome_filho = ($breadcrumb['valor'] && !empty(trim($breadcrumb['valor']))) ? $breadcrumb['valor'] : ($i + 1) . 'º Nível';
                $string_breadcrumb .= ' <span class="btn-link breadcrumb-cp" title="Clique para alterar o ' . $nome_filho . '" data-index="' . $i . '">' . mb_substr($nome_filho, 0, 20) . '</span>';
            }

            $has_breadcrumb = TRUE;
        }

        if($has_breadcrumb)
        {
            $string_breadcrumb .= ' <i class="bp-arrow-right"></i> ';
        }

        $nome_campo = (isset($data['elementoAtual'])) ? $data['elementoAtual']->campo->nome : 'Total';
        
        $string_breadcrumb .= ' <span style="color:#181f1c;">' . mb_substr($nome_campo, 0, 80) . '</span>';

        echo $string_breadcrumb; 
        
        ?>

    </h4>

</div>

<div class="card-block ">

    <div id="rendergraph" class="chart-box h-100" data-index="<?= $index ?>" data-token="<?= $data['token'] ?>" style="overflow-y: auto; overflow-x: hidden;text-align: left;">

        <?= $this->render('/_graficos/js/graficos/' . $data_grafico[$tipo_grafico], $variaveis); ?>

    </div>

</div>

<div class="card-footer d-flex align-items-center justify-content-start">

    <div class="w-50">

        <label><?= $data['nomes']['y'] ?></label>

        <span><?= $sum ?></span>

    </div>

</div>