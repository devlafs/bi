<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Redefinir senha';

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
    
<div class="d-flex flex-row justify-content-sm-between justify-content-lg-end align-items-start">
    <h1 class="hidden-lg-up ml-4 mt-4 text-hide logo">BI Logo</h1>
</div>

<div class="row h-100 justify-content-center">

    <div class="col-md-10 col-lg-10 col-xl-8 align-self-center login--form">

        <div class="col-lg-10 push-lg-1 col-xl-8 push-xl-2">
            <h1 class="text-center "><?= Html::encode($this->title) ?></h1>
            
            <?php if(!$token || !$usuario): ?>
            
                <div class="alert alert-danger">

                    <p>Ooops.</p>

                    <p>Token inválido ou já utilizado.</p>

                </div>
            
            <?php endif; ?>

            <?php $form = ActiveForm::begin(['id' => 'form-password', 'action' => "/user/password?t={$token->token}", 'options' => ['class' => 'col-form'], 'enableClientValidation' => false]); ?>

                <?php if($token  && $usuario): ?>
            
                    <h6 class="text-center "><?= $usuario->nome ?></h6>

                    <div class="form-group">

                        <?= $form->field($model, 'senha', [
                            'template' => '<div class="input-group input-group-lg"><span class="input-group-addon col-2" id="sizing-addon1 ">{label}</span>{input}</div>'
                        ])->passwordInput(['placeholder' => "******", 'aria-describedby' => 'sizing-addon1', 'autoFocus' => TRUE]); ?>

                    </div>

                    <div class="form-group">

                        <?= $form->field($model, 'repetir_senha', [
                            'template' => '<div class="input-group input-group-lg"><span class="input-group-addon col-2" id="sizing-addon1 ">{label}</span>{input}</div>'
                        ])->passwordInput(['placeholder' => "******", 'aria-describedby' => 'sizing-addon1']); ?>

                    </div>

                    <div>
                        <?= Html::submitButton('Redefinir', ['class' => 'btn btn-block btn-lg btn-primary text-uppercase p-4 btn-login', 'name' => 'login-button']) ?>
                    </div>
                
                <?php endif; ?>

                <div class="hidden-sm-down text-right">
                    <?= Html::a('Ir para o Login', '/user/login', ['class' => 'btn btn-sm btn-link']) ?>
                    <button class="btn btn-sm btn-link"></button>
                </div>

                <div class="hidden-md-up text-center">
                    <?= Html::a('Ir para o Login', '/user/login', ['class' => 'btn btn-block btn-link']) ?>
                </div>

            <?php ActiveForm::end(); ?>
                
        </div>

    </div>
    
</div>