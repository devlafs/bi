<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Pasta;

$modelPasta = new Pasta();
$pastas = $modelPasta->getOrderedFolders("PAINEL");

$js = <<<JS
        
    $('#div-form-painel-duplicate').on('beforeSubmit', 'form#form-painel-duplicate', function () 
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

<div id="div-form-painel-duplicate">
    
    <?php $form = ActiveForm::begin([
        'id' => 'form-painel-duplicate',
        'enableClientValidation' => TRUE,
        'action' => '/ajax/duplicate-painel?id=' . $model->id
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

        <?= $form->field($model, 'descricao')->textArea(['rows' => 2]); ?>

        <?= $form->field($model, 'privado')->checkbox(); ?>

        <div class="text-right">

            <?= Html::button('Cancelar', ['class' => 'btn btn-sm text-uppercase', 'data-izimodal-close' => '']) ?>

            <?= Html::submitButton('Duplicar', ['class' => 'btn btn-sm text-uppercase']) ?>

        </div>

    <?php ActiveForm::end(); ?>

</div>