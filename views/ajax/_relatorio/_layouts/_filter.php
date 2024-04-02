<?php

use app\models\RelatorioCampo;
use app\magic\FiltroMagic;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;
use yii\bootstrap\Html;

$css = <<<CSS

#div-filter .form-group
{
    margin-bottom: 0;
}

CSS;

$this->registerCss($css);

?>

<?php Pjax::begin(['id' => 'search-form']) ?>

<?php $form = ActiveForm::begin([
//    'action' => ['index'],
    'method' => 'get',
    'options' => ['data-pjax' => true]
]); ?>

<div class="row">

    <div class="col-lg-10">

        <div class="row">

            <?php foreach ($model->condicao as $condicao):

                if(isset($condicao[1])) :

                    $data = $condicao[1];

                    ?>

                    <div class="col-lg-3 col-md-4 col-sm-6">

                        <?php $campo = RelatorioCampo::findOne($data['field']);  ?>

                        <div class="form-group highlight-addon field-rela-login required">

                            <label title="Relatório: <?= $campo->relatorio->nome ?>" class="control-label has-star" style="font-size: 0.8571rem"><?= $campo->nome ?></label>

                            <?= $form->field($searchModel, 'dynamic_' . $campo->id )->label(false) ?>

                        </div>

                    </div>

                <?php endif; ?>

            <?php endforeach; ?>

        </div>

    </div>

    <div class="col-lg-2">
        <div class="form-group text-right" style="margin-top: 25px;">

            <?= Html::a('Limpar', $limpar, ['class' => 'btn btn-sm text-uppercase mr-1', 'data-pjax' => '0']) ?>

            <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-sm btn-primary text-uppercase']) ?>

        </div>

    </div>

</div>



<?php ActiveForm::end(); ?>

<?php Pjax::end() ?>
