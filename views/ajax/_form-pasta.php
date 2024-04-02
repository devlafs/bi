<?php

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;

$pastas = $model->getOrderedFolders($tipo);

$js = <<<JS
        
    $(document).ready(function () 
    {
        $('#div-form-pasta').on('beforeSubmit', 'form#form-pasta', function () 
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
                        location.reload();
                    }
                    else
                    {
                        $('#modal-pasta .iziModal__body').html(response.form);
                    }
                }
            });
        
            return false;
        });
    });     
        
JS;

$this->registerJs($js);

?>

<div id="div-form-pasta">

    <?php $form = ActiveForm::begin([
        'id' => 'form-pasta',
        'enableClientValidation' => TRUE,
        'action' => ($model->id) ? '/ajax/pasta?tipo=' . $tipo . '&id=' . $model->id .'&update=' . $update : '/ajax/pasta?tipo=' . $tipo
    ]); ?>

        <?= $form->field($model, 'nome'); ?>

        <?= $form->field($model, 'id_pasta')
            ->dropDownList(
            $pastas,
            [
                'prompt' => 'DIRETÓRIO RAIZ',
                'class' => 'form-control align-self-center',
                'style' => 'width: 100%;'
            ]
        ); ?>

        <div class="text-right">

            <?= Html::button('Cancelar', ['class' => 'btn btn-sm text-uppercase', 'data-izimodal-close' => '']) ?>

            <?= Html::submitButton( \Yii::t('app', 'view.salvar'), ['class' => 'btn btn-sm text-uppercase']) ?>

        </div>

    <?php ActiveForm::end(); ?>

</div>
