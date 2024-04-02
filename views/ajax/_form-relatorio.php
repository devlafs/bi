<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Relatorio;
use app\models\Pasta;
use yii\helpers\ArrayHelper;

$relatorios = ArrayHelper::map(Relatorio::find()->select('UPPER(nome) as nome, id as id')->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE])->orderBy('nome ASC')->all(), 'id', 'nome');
$modelPasta = new Pasta();
$pastas = $modelPasta->getOrderedFolders("RELATORIO");

$js = <<<JS
        
    $('#div-form-relatorio').on('beforeSubmit', 'form#form-relatorio', function () 
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
                    $('#modal-relatorio .iziModal__body').html(response.form);
                }
            }
        });

        return false;
    });
        
JS;

$this->registerJs($js);

?>

<div id="div-form-relatorio">
    
    <?php $form = ActiveForm::begin([
        'id' => 'form-relatorio',
        'enableClientValidation' => TRUE,
        'action' => '/ajax/relatorio'
    ]); ?>

        <?= $form->field($model, 'nome'); ?>

        <?= $form->field($model, 'id_relatorio')->dropDownList(
            $relatorios,
            [
                'prompt' => '',
                'class' => 'form-control align-self-center',
                'style' => 'width: 100%;'
            ]
        ); ?>

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

        <div class="text-right">

            <?= Html::button('Cancelar', ['class' => 'btn btn-sm text-uppercase', 'data-izimodal-close' => '']) ?>

            <?= Html::submitButton('Inserir', ['class' => 'btn btn-sm text-uppercase']) ?>

        </div>

    <?php ActiveForm::end(); ?>

</div>