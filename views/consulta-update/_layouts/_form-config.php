<?php 

use app\magic\ConfigMagic;
use yii\helpers\Html;

$js = <<<JS

    Sortable.create(document.getElementById('campos-disponiveis'), {
        group: { name: 'blocks', pull: true, put: true },
        animation: 150,
        onStart: function (evt) 
        {
            document.documentElement.classList.add("draggable-cursor");
        },
        onEnd: function (evt) 
        {
            document.documentElement.classList.remove("draggable-cursor");
        }
    });

    Sortable.create(document.getElementById('campos-utilizados'), {
        group: { name: 'blocks', pull: true, put: true },
        animation: 150,
        onStart: function (evt) 
        {
            document.documentElement.classList.add("draggable-cursor");
        },
        onEnd: function (evt) 
        {
            document.documentElement.classList.remove("draggable-cursor");
        }
    });
        
    $('#div-button-conf').delegate('#btn-salvar-configuracoes', 'click', function()
    {
        var _campos = [];
        
        var _argumentos = [];

        $('ul#campos-utilizados').children().each(function() 
        {
            _campos.push($(this).data('id'));
        });
        
        $('ul#argumento').children().each(function() 
        {
            _argumentos.push($(this).data('id'));
        });
        
        var _saveAll = $('#saveAll:checkbox:checked').length > 0;

        var _data = {'campos': _campos, 'argumentos': _argumentos, 'saveAll': _saveAll};

        $.post({
            url: '/consulta/salvar-configuracoes?consulta_id={$model->id}&item_id={$item->id}',
            type: 'POST',
            data: _data,
            success: function(msg) 
            {
                $('#modal-config').iziModal('close');
            
                iziToast.success({
                    title: 'Configurações salvas com sucesso!',
                    position: 'topCenter',
                    close: true,
                    transitionIn: 'flipInX',
                    transitionOut: 'flipOutX',
                });
            }
        });
    });
   
JS;

$this->registerJs($js);

$css = <<<CSS
        
    .block--parametro_list.block--disponiveis,
    .block--parametro_list.block--utilizados
    {
        min-height: 300px;
        max-height: 300px;
        overflow-x: hidden;
    }    
        
    .cbx-container .cbx-icon i.bp-check
    {
        color: #007482;
        font-size: 20px;
        font-weight: bolder;
    }
        
CSS;

$this->registerCss($css);

?>

<div class="row">

    <div class="col-lg-6">
        
        <div class="d-flex align-item-center justify-content-end py-1">

            <h4 class="mr-auto align-self-center text-uppercase" style="font-size: 12px;">Campos Disponíveis</h4>
        
        </div>
        
        <div class="d-flex block--parametro_list block--disponiveis justify-content-center align-item-center">
        
            <ul id="campos-disponiveis" class="attr-list">

                <?= ConfigMagic::getFields($camposDisponiveis); ?>

            </ul>
            
        </div>

    </div>

    <div class="col-lg-6" style="border-left: 1px solid #cecece;">

        <div class="d-flex align-item-center justify-content-end py-1">

            <h4 class="mr-auto align-self-center text-uppercase" style="font-size: 12px;">Campos Utilizados</h4>
        
        </div>
        
        <div class="d-flex block--parametro_list block--utilizados justify-content-center align-item-center">
        
            <ul id="campos-utilizados" class="attr-list">

                <?= ConfigMagic::getFields($camposUtilizados, TRUE); ?>

            </ul>
            
        </div>
        
    </div>
    
    <div class="col-lg-12 mt-1">
        
        <input id="saveAll" type="checkbox" name="saveAll" value="0">Aplicar para todos os argumentos<br>
        
    </div>
    
</div>

<div id="div-button-conf" class="row p-3 float-right">
    
    <?= Html::button('Cancelar', ['class' => 'btn btn-sm text-uppercase', 'data-izimodal-close' => '']) ?>

    <?= Html::submitButton( \Yii::t('app', 'view.salvar'), ['id' => 'btn-salvar-configuracoes', 'class' => 'btn btn-sm ml-2 text-uppercase']) ?>

</div>