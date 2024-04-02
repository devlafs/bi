<?php 

use yii\helpers\Html;
use app\magic\CacheMagic;
use Da\QrCode\QrCode;
use app\magic\MobileMagic;

$url = CacheMagic::getSystemData('url') ."/painel/visualizar/{$model->id}";

$qrCode = (new QrCode($url))
    ->setSize(200)
    ->setMargin(0)
    ->useForegroundColor(39, 65, 86)
    ->useBackgroundColor(255, 255, 255)
    ->useEncoding('UTF-8');

$js = <<<JS
        
    $("#modal-qrcode").iziModal({
        transitionIn: '',
        transitionOut: '',
        transitionInOverlay: '',
        transitionOutOverlay: ''
    });
        
    $(document).on('click', '.open-qrcode', function(event) 
    {
        event.preventDefault();
        event.stopPropagation();

        $('#modal-qrcode').iziModal('open');
    });     
        
JS;

$this->registerJs($js);

?>

<div id="modal-qrcode" style="display: none;">
    
    <div class="iziModal__header d-flex justify-content-start pl-3 pr-3 ">
        
        <h5 class="modal-title mr-auto align-self-center text-uppercase">QRCODE - <?= $model->nome ?></h5>
        
        <button type="button" class="btn btn-sm btn-link--inverse align-self-center text-uppercase cursor-pointer" data-izimodal-close="">X</button>
    
    </div>

    <div class="iziModal__body justify-content-center align-items-center p-3 text-center">
        
        <?= '<img src="' . $qrCode->writeDataUri() . '">' ?>
        
    </div>

</div>

<nav class="nav pageContent--nav align-item-center justify-content-start">
    
    <?php if(!MobileMagic::isMobile()) : ?>
    
        <span id="title-graph" class="navbar-text text-uppercase align-self-center"><?= $model->getPathName() ?></span>
        
    <?php endif; ?>
        
    <ul class="nav ml-auto">

        <?php if($can_generate_url || $can_send_email) : ?>

            <li class="nav-item dropdown">
                
                <a class="nav-link" title="Compartilhamento" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">

                    <i class="bp-share"></i>

                </a>

                <div class="dropdown-menu dropdown-menu-right">

                    <?php if($can_generate_url) : ?>
                    
                        <?= Html::a(($model->privado) ? 'Gerar URL privada' : 'Gerar URL pública',  'javascript:;', ['class' => 'dropdown-item', 'id' => 'generate-url', 'data-id' => $model->id, 'style' => 'cursor: pointer;']); ?>
                    
                    <?php endif; ?>

                </div>

            </li>
            
        <?php endif; ?>
            
        <?php if(Yii::$app->permissaoGeral->can('painel', 'alterar')) : ?>

            <li class="nav-item">

                <?= Html::a('<i class="bp-edit"></i>', ['alterar', 'id' => $model->id], ['title' => 'Alterar Painel', 'class' => 'nav-link']); ?>

            </li>
            
        <?php endif; ?>

        <li class="nav-item">

            <?= Html::a('<i class="bp-reload"></i>', 'javascript:', ['title' => 'Recarregar Painel', 'class' => 'nav-link', 'onclick' => 'location.reload();']); ?>

        </li>
        
    </ul>
    
</nav>
