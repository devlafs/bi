<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\models\RelatorioFiltro;
use kartik\datecontrol\DateControl;
use common\magic\RelatorioMagic;

?>

<div class="right-sidebar">

    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;">

        <div class="slimscrollright" style="overflow: hidden; width: auto; height: 100%;">

            <div class="rpanel-title"><i class="ti-filter text-white"></i> Filtros<span><i class="ti-close open-filter"></i></span></div>

            <div class="r-panel-body">

                <?php Pjax::begin(['id' => 'search-form']) ?>

                <?php $form = ActiveForm::begin([
                    'action' => ['index', 'id' => $relatorio->id, 'first' => false],
                    'method' => 'get',
                    'options' => ['data-pjax' => true]
                ]); ?>

                <?php foreach ($filtros as $filtro): ?>

                    <?php

                        switch ($filtro->tipo):

                            case RelatorioFiltro::TIPO_TEXTO:

                                $form->field($model, "dynamic_{$filtro->tag}");

                                break;

                            case RelatorioFiltro::TIPO_VALOR:

                                $form->field($model, "dynamic_{$filtro->tag}")->input(['type' => 'number']);

                                break;

                            case RelatorioFiltro::TIPO_DATA:

                                echo $form->field($model, "dynamic_{$filtro->tag}")->widget(DateControl::classname(), [
                                    'type' => DateControl::FORMAT_DATE,
                                    'widgetOptions' => [
                                        'pluginOptions' => [
                                            'autoclose' => true
                                        ]
                                    ]
                                ]);

                                break;

                            case RelatorioFiltro::TIPO_LISTASQL:

                                $items = RelatorioMagic::getData($filtro->id);

                                echo $form->field($model, "dynamic_{$filtro->tag}")->dropDownList($items, ['prompt' => '']);

                                break;

                            case RelatorioFiltro::TIPO_LISTADADOS:

                                $items = RelatorioMagic::getData($filtro->id);

                                echo $form->field($model, "dynamic_{$filtro->tag}")->dropDownList($items, ['prompt' => '']);

                        endswitch;

                    ?>

                <?php endforeach; ?>

                <div class="form-group pull-right">

                    <?= Html::a('Limpar', ['index', 'id' => $relatorio->id], ['class' => 'btn btn-default']) ?>

                    <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-success']) ?>

                </div>

                <?php ActiveForm::end(); ?>

                <?php Pjax::end() ?>

            </div>

        </div>

        <div class="slimScrollBar" style="background: rgb(220, 220, 220); width: 5px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 381.574px;"></div>

        <div class="slimScrollRail" style="width: 5px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>

    </div>

</div>