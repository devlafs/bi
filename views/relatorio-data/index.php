<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\RelatorioCampo;

$this->title = $model->nome;

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="relatorio-index">

    <div class="row">

        <div class="col-lg-12 col-md-12">

            <div class="card card-outline-plan">

                <div class="card-body p-5">

                    <?php if(!$dataProvider): ?>

                        <div class="alert alert-danger">
                            O Relatório não foi configurado corretamente. Por favor, entre em contato com o administrador do sistema e solicite a correção.
                        </div>

                    <?php else: ?>

                        <?php if($model->condicao) : ?>

                            <div id="div-filter" class="col-md-12 pl-0 pr-0">

                                <div class="card card--chart card--consuta__full mt-0 mb-2" style="height: 100%; margin-right: 10px;">

                                    <div class="card-block row pt-1 pb-1">

                                        <?= $this->render('/ajax/_relatorio/_layouts/_filter', compact('model')) ?>

                                    </div>

                                </div>

                            </div>

                        <?php endif; ?>

                        <table id="datagrid" class="table table-hover table-bordered" cellspacing="0" width="100%">

                            <thead class="thead-default">

                            <tr>

                                <?php foreach ($campos as $campo) : ?>

                                    <td><?= $campo->nome ?></td>

                                <?php endforeach; ?>

                            </tr>

                            </thead>

                            <tbody>

                            </tbody>

                        </table>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>