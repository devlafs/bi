<?php 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\lists\ConfiguracaoGraficoList;

$js = <<<JS
        
    $('#btn-salvar-advanced').click(function (e) 
    {
        e.preventDefault();
        var form = $('form#form-conf-advanced');

        if (form.find('.has-error').length) 
        {
            return false;
        }

        $.ajax({
            url    : form.attr('action'),
            type   : 'post',
            data   : form.serialize(),
            success: function (data) 
            {
                if(data.success)
                {
                    $('#modal-advanced').iziModal('close');
        
                    iziToast.success({
                        title: 'Configurações salvas com sucesso',
                        position: 'topCenter',
                        close: true,
                        transitionIn: 'flipInX',
                        transitionOut: 'flipOutX',
                    });
                }
                else
                {
                    $('#modal-advanced .iziModal__body').html(data.form);
                }
            }
        });

        return false;
    });
        
JS;

$this->registerJs($js);

$model->label_view = ConfiguracaoGraficoList::getView($model->view);

$model->label_tipo = ConfiguracaoGraficoList::getType($model->tipo);

?>

<div class="div-form-advanced">

    <?php if($type != 'kpi') : ?>
    
        <?php $form = ActiveForm::begin([
            'id' => 'form-conf-advanced',
            'enableClientValidation' => TRUE,
            'action' => "/consulta/advanced-config?id={$model->id_consulta}&campo_id={$model->id_campo}&view={$model->view}&type={$model->tipo}&is_serie={$is_serie}"
        ]); ?>

            <?= $form->field($model, 'label_view')->textInput(['disabled' => TRUE]); ?>
    
            <?= $form->field($model, 'label_tipo')->textInput(['disabled' => TRUE]); ?>
    
            <?= $form->field($model, 'data')->textArea(['rows' => 10]); ?>

            <?php if($is_serie) : ?>

                <?= $form->field($model, 'data_serie')->textArea(['rows' => 5]); ?>

                <?php if($model->consulta->tipo_serializacao == 2) : ?>
    
                    <?= $form->field($model, 'data_timeline')->textArea(['rows' => 3]); ?>
    
                <?php endif; ?>
    
            <?php endif; ?>

            <div class="text-right">

                <?= Html::button('Cancelar', ['class' => 'btn btn-sm text-uppercase', 'data-izimodal-close' => '']) ?>

                <?= Html::button('Salvar', ['id' => 'btn-salvar-advanced', 'class' => 'btn btn-sm ml-2 text-uppercase']) ?>

            </div>

        <?php ActiveForm::end(); ?>
    
    <?php else : ?>
    
        <div class="alert alert-danger">Nenhuma configuração avançada foi encontrada para esse tipo de gráfico.</div>
    
    <?php endif; ?>
    
</div>