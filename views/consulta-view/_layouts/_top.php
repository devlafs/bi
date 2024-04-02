<?php 

use yii\helpers\Html;
use app\magic\CacheMagic;
use Da\QrCode\QrCode;
use app\magic\MobileMagic;
use kartik\mpdf\Pdf;

$url = CacheMagic::getSystemData('url') ."/consulta/visualizar/{$model->id}";

$qrCode = (new QrCode($url))
    ->setSize(200)
    ->setMargin(0)
    ->useForegroundColor(39, 65, 86)
    ->useBackgroundColor(255, 255, 255)
    ->useEncoding('UTF-8');

$css = <<<CSS
        
    .notification-counter 
    {   
        margin-left: -10px;
        background-color: rgba(212, 19, 13, 1);
        color: #fff;
        border-radius: 3px;
        padding: 1px 3px;
        font: 8px Verdana;
    }   
        
CSS;

$this->registerCss($css);

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
        
        <li class="nav-item">


        </li>

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
            
        <?php if(false) : ?>
            
            <li class="nav-item dropdown">

                <a class="nav-link" title="Exportar dados" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">

                    <i class="bp-chart--grid"></i>

                </a>

                <div class="dropdown-menu dropdown-menu-right">

                    <?= Html::a('CSV', 'javascript:;', ['class' => 'dropdown-item', 'id' => 'export-data-csv', 'target' => '_blank']); ?>

                    <div class="dropdown-divider"></div>

                    <?= Html::a('XLS', 'javascript:;', ['class' => 'dropdown-item', 'id' => 'export-data-excel', 'target' => '_blank']); ?>

                    <div class="dropdown-divider"></div>

                    <h6 class="dropdown-header">PDF</h6>

                    <?= Html::a('Retrato', 'javascript:;', ['class' => 'dropdown-item export-data-pdf', 'data-orientation' => Pdf::ORIENT_PORTRAIT, 'target' => '_blank']); ?>

                    <?= Html::a('Paisagem', 'javascript:;', ['class' => 'dropdown-item export-data-pdf',  'data-orientation' => Pdf::ORIENT_LANDSCAPE, 'target' => '_blank']); ?>

                </div>

            </li>
            
        <?php endif; ?>
        
        <?php if(Yii::$app->permissaoGeral->can('consulta', 'alterar')) : ?>

            <li class="nav-item">

                <?= Html::a('<i class="bp-edit"></i>', ['alterar', 'id' => $model->id], ['title' => 'Alterar Consulta', 'class' => 'nav-link']); ?>

            </li>

        <?php endif; ?>
            
        <?php if($can_filter_graph || $can_change_graph) : ?>

            <li class="nav-item">

                <a class="nav-link open-config" title="Configurações da Consulta" data-id="<?= $model->id ?>" href="#">

                    <i class="bp-config-gear"></i>
                    
                    <?php if($modifications) :?>
                    
                    <span class="notification-counter" title="Possui gŕaficos e/ou filtros personalizados">
                        
                        <?php 
                        
                            foreach($modifications as $modification) : 

                                echo $modification['quantidade'];

                            endforeach; 
                        
                        ?>
                        
                    </span>
                        
                    <?php endif; ?>

                </a>

            </li>
            
        <?php endif; ?>

    </ul>
    
</nav>
