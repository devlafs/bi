<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$js = <<<JS
        
    $('#div-form-painel-rename').on('beforeSubmit', 'form#form-painel-rename', function () 
    {
        var form = $(this);

        if (form.find('.has-error').length) 
        {
            return false;
        }

        $.ajax({
            url    : form.attr('action'),
            type   : 'post',
            data   : form.serialize(),
            success: function (response) 
            {
                if(response.success)
                {
                    window.location.href = response.url;
                }
                else
                {
                    $('#modal-painel .iziModal__body').html(response.form);
                }
            }
        });

        return false;
    });
        
JS;

$this->registerJs($js);

?>

<div id="div-form-painel-rename">
    
    <?php $form = ActiveForm::begin([
        'id' => 'form-painel-rename',
        'enableClientValidation' => TRUE,
        'action' => '/ajax/rename-painel?id=' . $model->id
    ]); ?>

        <?= $form->field($model, 'nome'); ?>

        <div class="text-right">

            <?= Html::button('Cancelar', ['class' => 'btn btn-sm text-uppercase', 'data-izimodal-close' => '']) ?>

            <?= Html::submitButton( \Yii::t('app', 'view.salvar'), ['class' => 'btn btn-sm text-uppercase']) ?>

        </div>

    <?php ActiveForm::end(); ?>

</div>