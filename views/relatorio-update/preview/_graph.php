<?php 

    use app\models\Pallete;
    use app\magic\GraficoMagic;
    
    $palletes = Pallete::find()->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE])->all();
    
    if($data):

        $elementoAtual = $data['elementoAtual'];
        $ultimo = $data['ultimo'];
        $nomes = $data['nomes'];
        $tipoGrafico = $data['tipoGrafico'];

        $filtro = str_replace("\u0022","\\\\\"",json_encode($data['filtro'], JSON_HEX_QUOT));

        $variaveis = 
        [
            'data' => $data,
            'tipo_grafico' => $tipoGrafico,
            'model' => $model,
            'index' => $index,
            'url' => '/consulta/preview?id=' . $model->id . '&type=null&sqlMode=' . $sqlMode,
            'view' => 'preview'
        ];

        $data_grafico = GraficoMagic::$data_grafico;
        
        $js = <<<JS

            $(document).ready(function()
            {
                $(document).off('click', '.choose-graph-type').on("click", ".choose-graph-type", function(e)
                {
                    e.preventDefault();

                    var _index = parseInt('{$index}');
                    var _id = parseInt('{$model->id}');
                    var _filtro = {$filtro};
                    var _type = $(this).data('type');

                    applyCa(e, _index, _id, _filtro, _type);
                });
            });
JS;

        $this->registerJs($js);

    ?>


    <h4 class="d-flex w-100">

        <?= Yii::t('app', 'view.consulta.preview_grafico') ?>
        
        <i class="ml-auto bp-reload update-graph"></i>
    
    </h4>

    <div class="d-flex block__chart--preview justify-content-between align-item-center w-100 mb-4">

        <div class="block--navigation-charts">

            <ul class="align-self-center navigation-charts_list">

                <?php foreach($palletes as $pallete) : ?>
                
                    <li>

                        <button title="<?= $pallete->nome ?>" data-id="<?= $pallete->id ?>" class="btn-icon choose-pallete">

                            <i class="bp-pallete mx-auto text-center d-block" style="<?= ($model->id_pallete == $pallete->id) ? '
    border: 2px solid #007EC3; box-shadow: 0px 0px 20px #007EC3;' : '' ?>height: 15px; width: 15px;background: linear-gradient(135deg, #fff 0%, <?= $pallete->color1 ?> 50%, <?= $pallete->color2 ?> 51%, #fff 100%);"></i>

                        </button>

                    </li>

                <?php endforeach; ?>

            </ul>

        </div>
        
        <div id="rendergraph" class="chart-box">

            <?= $this->render("/consulta-update/preview/graficos/" . $data_grafico[$tipoGrafico], $variaveis); ?>

        </div>
        
        <div class="block--navigation-charts">

            <ul class="align-self-center navigation-charts_list">

                <li>

                    <button data-type="area" class="choose-graph-type btn-icon <?= $tipoGrafico == 'area' ? 'active' : '' ?>">

                        <i class="bp-chart--area mx-auto text-center d-block "></i>

                    </button>

                </li>

                <li>

                    <button data-type="line" class="choose-graph-type btn-icon <?= $tipoGrafico == 'line' ? 'active' : '' ?>">

                        <i class="bp-chart--line mx-auto text-center d-block "></i>

                    </button>

                </li>

                <li>

                    <button data-type="bar" class="choose-graph-type btn-icon <?= $tipoGrafico == 'bar' ? 'active' : '' ?>">

                        <i class="bp-chart--bar mx-auto text-center d-block "></i>

                    </button>

                </li>

                <li>

                    <button data-type="column" class="choose-graph-type btn-icon <?= $tipoGrafico == 'column' ? 'active' : '' ?>">

                        <i class="bp-chart--colum mx-auto text-center d-block "></i>

                    </button>

                </li>

                <li>

                    <button data-type="pie" class="choose-graph-type btn-icon <?= $tipoGrafico == 'pie' ? 'active' : '' ?>">

                        <i class="bp-chart--pie mx-auto text-center d-block "></i>

                    </button>

                </li>
                
                <li>

                    <button data-type="donut" class="choose-graph-type btn-icon <?= $tipoGrafico == 'donut' ? 'active' : '' ?>">

                        <i class="bp-chart--donut mx-auto text-center d-block "></i>

                    </button>

                </li>

                <li>

                    <button data-type="funnel" class="choose-graph-type btn-icon <?= $tipoGrafico == 'funnel' ? 'active' : '' ?>">

                        <i class="bp-chart--funel mx-auto text-center d-block "></i>

                    </button>

                </li>

                <li>

                    <button data-type="kpi" class="choose-graph-type btn-icon <?= $tipoGrafico == 'kpi' ? 'active' : '' ?>">

                        <i class="bp-chart--kpi mx-auto text-center d-block "></i>

                    </button>

                </li>

                <li>

                    <button data-type="table" class="choose-graph-type btn-icon <?= $tipoGrafico == 'table' ? 'active' : '' ?>">

                        <i class="bp-chart--grid mx-auto text-center d-block "></i>

                    </button>

                </li>

                <li>

                    <button data-type="heatmap" class="choose-graph-type btn-icon <?= $tipoGrafico == 'heatmap' ? 'active' : '' ?>">

                        <i class="bp-kanban mx-auto text-center d-block "></i>

                    </button>

                </li>

            </ul>

        </div>

    </div>

<?php else : ?>
    
    <?= $this->render("/consulta-update/preview/_empty-graph"); ?>
    
<?php endif; ?>