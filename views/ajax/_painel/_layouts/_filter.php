<?php

use app\models\IndicadorCampo;
use app\magic\FiltroMagic;

$css = <<<CSS

#div-filter .form-group
{
    margin-bottom: 0;
}

CSS;

$this->registerCss($css);

foreach ($model->condicao as $condicao):

    if(isset($condicao[1])) :

        $data = $condicao[1];

    ?>

        <div class="col-lg-3 col-md-4 col-sm-6">

            <?php $campo = IndicadorCampo::findOne($data['field']);  ?>

            <div class="form-group highlight-addon field-adminusuario-login required">

                <label title="Cubo: <?= $campo->indicador->nome ?>" class="control-label has-star" style="font-size: 0.8571rem"><?= $campo->nome ?></label>
                <small>(<?= FiltroMagic::getLabelNameFields($data['type']) ?>)</small>

                <?= $this->render('//ajax/_painel/_layouts/_partials/_field_' . $campo->tipo, [
                    'data' => $data,
                    'campo' => $campo
                ]); ?>

            </div>

        </div>

    <?php endif; ?>

<?php endforeach; ?>

<div class="col-lg-12 mt-1">

    <button type="button" id="filter-painel" class="btn btn-sm btn-primary text-uppercase" style="float: right">Filtrar</button>
    <button type="button" class="btn btn-sm text-uppercase mr-1" onclick="location.reload();" style="float: right">Limpar</button>

</div>
