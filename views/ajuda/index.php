<?php

$this->title = \Yii::t('app', 'view.ajuda');

?>

<style>

    .box-ajuda
    {
        margin-left: 60px;
        margin-top: 10px;
        margin-bottom: 10px;
        margin-right: 10px;
    }

</style>

<div>

    <div class="accordion" id="accordionCategoria">

        <?php foreach ($categorias as $index => $categoria) : ?>

            <div class="card">

                <div class="card-header" id="headingCategoria<?= $index ?>">

                    <h5 class="mb-0">

                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseCategoria<?= $index ?>" aria-expanded="true" aria-controls="collapseCategoria<?= $index ?>">

                            <?= $categoria->ordem . ' - ' . $categoria->nome ?>

                        </button>

                    </h5>

                </div>

                <div id="collapseCategoria<?= $index ?>" class="collapse" aria-labelledby="headingCategoria<?= $index ?>" data-parent="#accordionCategoria">

                    <div class="card-body">

                        <div class="accordion" id="accordionjuda">

                            <?php foreach ($categoria->ajudas as $ajuda) : ?>

                                <div class="card">

                                    <div class="card-header p-0" style="background-color: #FFF; border-bottom: none;" id="headingAjuda<?= $ajuda->ordem ?>">

                                        <h5 class="mb-0 ml-5">

                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseAjuda<?= $ajuda->ordem ?>" aria-expanded="true" aria-controls="collapseAjuda<?= $ajuda->ordem ?>">

                                                <?= $ajuda->ordem . ' - ' . $ajuda->titulo ?>

                                            </button>

                                        </h5>

                                    </div>

                                    <div id="collapseAjuda<?= $ajuda->ordem ?>" class="collapse" aria-labelledby="headingAjuda<?= $ajuda->ordem ?>" data-parent="#accordionAjuda">

                                        <div class="card-body">

                                            <div class="box-ajuda">

                                                <?= $ajuda->texto ?>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</div>