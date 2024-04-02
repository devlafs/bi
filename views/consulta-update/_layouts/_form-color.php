<?php 

use yii\helpers\Html;
use kartik\form\ActiveForm;

$js = <<<JS

$('#btn-adicionar-cor').on("click", function(e)
{
    var _ordem = $('#table-value-color tbody tr:last').data('ordem');
    
    if(_ordem === undefined)
    {
        _ordem = 1;
    }
    else 
    {
        _ordem = _ordem + 1;
    }
    
    jQuery.ajax({
        url: '/consulta/config-color-row?item_id={$campo->id}&ordem=' + _ordem,
        type: 'POST',
        success: function (data) 
        {
            $('#table-value-color tbody').append('<tr data-ordem="' + _ordem + '">' + data + '</tr>');
        },
    });
});

$('#div-button-color').delegate('#btn-salvar-cores', 'click', function()
{
    $.post({
        url: '/consulta/salvar-cores?consulta_id={$model->id}&item_id={$campo->id}',
        type: 'POST',
        data: $('#form-color').serialize(),
        success: function(msg) 
        {
            $('#modal-color').iziModal('close');
        
            iziToast.success({
                title: 'Cores personalizadas salvas com sucesso!',
                position: 'topCenter',
                close: true,
                transitionIn: 'flipInX',
                transitionOut: 'flipOutX',
            });
        }
    });
});


JS;

$this->registerJs($js);

?>

<div class="col-lg-12">

    <?php $form = ActiveForm::begin(['id' => 'form-color']); ?>

        <table id="table-value-color" class="table table-hover">
            <thead>
                <tr>
                    <th>
                        Valor
                    </th>
                    <th>
                        Cor
                    </th>
                    <th>
                        Ação
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if($campoCores) : ?>
                    <?php foreach($campoCores as $index => $campoCor) : ?>
                        <tr data-ordem="<?= $index + 1 ?>">
                            <?= $this->render('_form-color-field', ['model' => $campoCor, 'campo_id' => $campo->id, 'ordem' => $index + 1]); ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr data-ordem="1">
                        <?= $this->render('_form-color-field', ['model' => null, 'campo_id' => $campo->id, 'ordem' => 1]); ?>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    <?php ActiveForm::end(); ?>


</div>

<div id="div-button-color" class="row p-3 float-right">
    
    <?= Html::button('Cancelar', ['class' => 'btn btn-sm text-uppercase', 'data-izimodal-close' => '']) ?>

    <?= Html::submitButton('Adiconar Opção', ['id' => 'btn-adicionar-cor', 'class' => 'btn btn-sm ml-2 text-uppercase']) ?>

    <?= Html::submitButton( \Yii::t('app', 'view.salvar'), ['id' => 'btn-salvar-cores', 'class' => 'btn btn-sm ml-2 text-uppercase']) ?>

</div>