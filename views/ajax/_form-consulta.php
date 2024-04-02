<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Indicador;
use app\models\Pasta;
use yii\helpers\ArrayHelper;

$cubos = ArrayHelper::map(Indicador::find()->select('UPPER(nome) as nome, id as id')->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE])->orderBy('nome ASC')->all(), 'id', 'nome');
$modelPasta = new Pasta();
$pastas = $modelPasta->getOrderedFolders("CONSULTA");

$js = <<<JS
        
    $('#div-form-consulta').on('beforeSubmit', 'form#form-consulta', function () 
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
                    $('#modal-consulta .iziModal__body').html(response.form);
                }
            }
        });

        return false;
    });
        
JS;

$this->registerJs($js);

?>

<div id="div-form-consulta">
    
    <?php $form = ActiveForm::begin([
        'id' => 'form-consulta',
        'enableClientValidation' => TRUE,
        'action' => '/ajax/consulta'
    ]); ?>

        <?= $form->field($model, 'nome'); ?>

        <?= $form->field($model, 'id_indicador')->dropDownList(
            $cubos,
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

        <?= $form->field($model, 'privado')->checkbox(); ?>

        <div class="text-right">

            <?= Html::button('Cancelar', ['class' => 'btn btn-sm text-uppercase', 'data-izimodal-close' => '']) ?>

            <?= Html::submitButton('Inserir', ['class' => 'btn btn-sm text-uppercase']) ?>

        </div>

    <?php ActiveForm::end(); ?>

</div>