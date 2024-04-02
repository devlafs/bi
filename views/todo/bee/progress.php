<?php

use app\magic\CacheMagic;

$version = CacheMagic::getSystemData('version');

$this->title = 'Solicitação::: ' . $id;

$nome_atividade = $start = $end = $nome_processo = $status = $abertura = $marco = $conclusao = [];

$ultimo = 0;
$ultima_dep = [];

foreach($data as $index => $value)
{
    if(!in_array($value['nomeProcesso'], $nome_processo))
    {
        $nome_processo[] = $value['nomeProcesso'];
        $nome_atividade[] = $value['nomeProcesso'];
        $start[] = 0;
        $end[] = 0;
        $status[] = $value['statusSolicitacao'];
        $abertura[] = $value['aberturaSolicitacao'];
        $conclusao[] = $value['conclusaoSolicitacao'];
        $marco[] = '';
    }
    
    $cod_dep = substr($value['codigoSolicitacao'], 0, strrpos($value['codigoSolicitacao'], '/'));
    $nome_atividade[] = $value['nomeAtividade'];
    
    if($value['executado'] == '1')
    {
        $val = ($value['executado']) ? $value['marcoAtividade']/100 : 0;
        
        if($ultimo > $val && $val > 0)
        {
            $ultimo = isset($ultima_dep[$cod_dep]) ? $ultima_dep[$cod_dep] : 0;
        }
        
        $start[] = $ultimo;
        $end[] = $val - $ultimo;
        $ultimo = $val;
        $ultima_dep[$value['codigoSolicitacao']] = $val;
    }
    else
    {
        $start[] = 0;
        $end[] = 0;
    }
    
    $marco[] = $value['marcoAtividade'] . '%';
    $status[] = $value['statusAtividade'];
    $abertura[] = $value['aberturaAtividade'];
    $conclusao[] = $value['conclusaoAtividade'];
}

$json_atividades = json_encode(array_reverse($nome_atividade));
$json_start = json_encode(array_reverse($start));
$json_end = json_encode(array_reverse($end));
$json_status = json_encode(array_reverse($status));
$json_abertura = json_encode(array_reverse($abertura));
$json_conclusao = json_encode(array_reverse($conclusao));
$json_marco = json_encode(array_reverse($marco));

$js = <<<JS
        
function createTimeLine(_data)
{
    var myChart = echarts.init(document.getElementById('rendergraph'));

    var startdata = {$json_start};
    var enddata = {$json_end};
    var city = {$json_atividades};
    option = {
        tooltip :
        {
            textStyle: {fontSize: 12},
            trigger: 'axis', 
            axisPointer: { type: 'shadow' },
            formatter: function (params) 
            {
                var _status = {$json_status};
                var _abertura = {$json_abertura};
                var _conclusao = {$json_conclusao};
                var _index = params[0].dataIndex;
                return params[0].name + ' <br> Status: ' + _status[_index] + ' <br> Data de Abertura: ' + _abertura[_index] + ' <br> Data de Conclusão: ' + _conclusao[_index]; 
            }
        }, 
        color: ['#007EC3'],
        grid: {
            left: '50px',
            right: '100px',
            bottom: '20px',
            top: '20px',
            containLabel: true
        },
        xAxis: [{
                show: false,
            },
            {
                show: false,
            }
        ],
        yAxis: {
            type: 'category',
            axisLabel: {
                show: true,
                align: 'right',
                interval: 0,
                fontSize: 10,
                padding: [5, 5, 5, 5]
            },
            itemStyle: {

            },
            axisTick: {
                show: false,
            },
            axisLine: {
                show: false,
            },
            data: city,
        },
        series: [
            {
                show: true,
                type: 'bar',
                stack: 'one',
                barGap: '-100%',
                barWidth: '15px',
                max: 1,
                labelLine: {
                    show: false,
                },
                itemStyle: {
                    normal: {
                        barBorderColor: 'rgba(0,0,0,0)',
                        color: 'rgba(0,0,0,0)',
                        barBorderRadius: 5,
                    },
                    emphasis: {
                        barBorderColor: 'rgba(0,0,0,0)',
                        color: 'rgba(0,0,0,0)'
                    }
                },
                z: 2,
                data: startdata,
            },
            {
                show: true,
                type: 'bar',
                stack: 'one',
                barGap: '-100%',
                barWidth: '15px',
                max: 1,
                labelLine: {
                    show: true,
                },
                label: {
                    normal: {
                        show: true,
                        position: 'inside',
                        formatter: function (params) 
                        {
                            var _marco = {$json_marco};
                            var _index = params.dataIndex;
                            return (params.value) ? _marco[_index] : ''; 
                        },
                    }
                },
                itemStyle: {
                    normal: {
                        barBorderRadius: 5,
                    },
                },
                z: 2,
                data: enddata,
            },
        ]
    };

    myChart.setOption(option, true), $(function() 
    {
        function resize() 
        {
            setTimeout(function() 
            {
                myChart.resize()
            }, 1000)
        }

        $(window).on("resize", resize), $("#menu-toggle").on("click", resize)
    });
}

createTimeLine();
        
JS;

$this->registerJs($js);

?>


<div id="page-content-wrapper " style="padding-left:0px;">

    <div class="page-content inset h-100 mh-100">

        <nav class="nav pageContent--nav align-item-center justify-content-start">
    
            <span id="title-graph" title="<?= "Solicitação: {$id}"?>" class="navbar-text text-uppercase align-self-center" style="cursor: help;"><?= "Solicitação: {$id}"?></span>

        </nav>

        <div class="container-fluid h-100 mh-100 justify-content-between" id="content--container">

            <div class="col-md-12">
    
                <div data-mh="painel-group-001" class="card card-consulta card--chart card--consuta__full" style="width: 100%;">

                    <div id="rendergraph" class="chart-box h-100"></div>

                </div>
            </div>


        </div>

    </div>

</div>
