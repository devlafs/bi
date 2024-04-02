<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Entrar';

$css = <<<CSS
        
    .help-block-error
    {
        color: rgb(236, 101, 101);
        font-size: 12px;
        text-align: center;
        margin: 5px; 
    }
        
CSS;

$this->registerCss($css);

if($model->getErrors())
{
    foreach($model->getErrors() as $error)
    {
        $str_error = (is_array($error)) ? $error[0] : $error;
        $js = <<<JS
        
    iziToast.error({
        title: 'Error',
        message: '{$str_error}',
        position: 'topCenter',
        close: true,
        transitionIn: 'flipInX',
        transitionOut: 'flipOutX',
    });
        
    $('.form-group').addClass('has-danger');
JS;

    $this->registerJs($js);
    }
}

?>

<div class="row h-100 justify-content-center">

    <div class="col-md-8 col-lg-8 col-xl-8 align-self-center login--form">

        <div class="col-lg-6 push-lg-1 col-xl-6 push-xl-3">
            <h1 class="text-center "><?= Html::encode($this->title) ?></h1>

            <?php $form = ActiveForm::begin(['id' => 'form-login-bi', 'options' => ['class' => 'col-form'], 'enableClientValidation' => false]); ?>

                <div class="form-group ">

                    <?= $form->field($model, 'email', [
                        'template' => '<div class="input-group input-group-lg"><span class="input-group-addon col-3" id="sizing-addon1 ">{label}</span>{input}</div>'
                    ])->textInput(['aria-describedby' => 'sizing-addon1', 'autoFocus' => TRUE]); ?>

                </div>

                <div class="form-group">

                    <?= $form->field($model, 'password', [
                        'template' => '<div class="input-group input-group-lg"><span class="input-group-addon col-3" id="sizing-addon1 ">{label}</span>{input}</div>'
                    ])->passwordInput(['placeholder' => "******", 'aria-describedby' => 'sizing-addon1']); ?>

                </div>

                <div>
                    <?= Html::submitButton(Yii::t('app', 'login.botao_acessar'), ['class' => 'btn btn-block btn-lg btn-primary text-uppercase p-4 btn-login', 'title' => date("d/m/Y H:i"), 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>

    </div>
    
</div>