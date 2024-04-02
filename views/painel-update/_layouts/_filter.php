<?php

use kartik\switchinput\SwitchInput;
use yii\bootstrap\ActiveForm;

$css = <<<CSS
        
    .bootstrap-switch
    {
        float: right;
    }
        
    .list-group .form-group 
    {
        margin-top: -25px;
    }
        
    .bnt-add__and 
    {
        margin-bottom: 10px;
    }
   
    .and-text-separator
    {
        margin-top: 15px;
        color: #FFF;
        background-color: #177487;
        padding: 5px;
        z-index: 2;
        border-radius: 5px;
    }
        
    .and-button-text
    {
        font-size: 28px;
    }
                
    .bootstrap-switch
    {
        float: right;
    }
        
    .list-group .form-group 
    {
        margin-top: -25px;
    }
        
CSS;

$this->registerCss($css);

$js = <<<JS

    $(document).delegate(".select-choose-cubo", 'change', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();
        
        var _indexAnd = $(this).data('indexand');
        var _indexOr = $(this).data('indexor');
        var _id = $(this).val();
        
        jQuery.ajax({
            url: '/painel/get-field-update?id=' + _id + '&and=' + _indexAnd + '&or=' + _indexOr,
            success: function (_data) 
            {
                $(".render_select_field_" + _indexAnd + "_" + _indexOr).html(_data);
                $(".render_field_" + _indexAnd + "_" + _indexOr).html('<input class="form-control disabled" disabled="disabled" type="text">');
            },
        });
    });
        
    $(document).delegate(".select-choose-field", 'change', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();
        
        var _indexAnd = $(this).data('indexand');
        var _indexOr = $(this).data('indexor');
        var _id = $(this).val();
        
        jQuery.ajax({
            url: '/painel/get-type-update?id=' + _id + '&and=' + _indexAnd + '&or=' + _indexOr,
            success: function (_data) 
            {
                $(".render_select_type_" + _indexAnd + "_" + _indexOr).html(_data);
                $(".render_field_" + _indexAnd + "_" + _indexOr).html('<input class="form-control disabled" disabled="disabled" type="text">');
            },
        });
    });
        
    $(document).delegate(".select-choose-type", 'change', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();
        
        var _indexAnd = $(this).data('indexand');
        var _indexOr = $(this).data('indexor');
        var _val = $(this).val();
        var _id = $(".select-choose-field-" + _indexAnd + "-" + _indexOr).val();
        
        jQuery.ajax({
            url: '/painel/get-field-update?id=' + _id + '&value=' + _val + '&and=' + _indexAnd + '&or=' + _indexOr,
            success: function (_data) 
            {
                $(".render_field_" + _indexAnd + "_" + _indexOr).html(_data);
            },
        });
    });
    
    $(document).delegate(".change-tag", 'change', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();
        
        var _indexAnd = $(this).data('indexand');
        var _indexOr = $(this).data('indexor');
        var _val = $(".select-choose-type-" + _indexAnd + "-" + _indexOr).val();
        var _id = $(".select-choose-field-" + _indexAnd + "-" + _indexOr).val();
        var _tag = $(this).val();
        
        jQuery.ajax({
            url: '/painel/get-field-update?id=' + _id + '&value=' + _val + '&and=' + _indexAnd + '&or=' + _indexOr + '&tag=' + _tag,
            success: function (_data) 
            {
                $(".render_field_" + _indexAnd + "_" + _indexOr).html(_data);
            },
        });
    });
        
    $(document).delegate("#add-atribute", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();
        var _index = $('#filter-list__container ul.filter-list__atribute').last().data('index');
        var _isUndefined = (typeof _index === 'undefined');
        var _next = _isUndefined ? 1 : (_index + 1);
        
        jQuery.ajax({
            url: '/painel/and-filter-update?id={$model->id}&index=' + _next,
            success: function (_data) 
            {
                $("#filter-list__container").append(_data);
                
                if(!_isUndefined)
                {
                    $("#filter-list__atribute_" + _next).slideToggle();
                }
            },
        });
    });
        
    $(document).delegate(".bnt-add__and", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();

        var _indexAnd = $(this).data('index');
        var _indexOr = $('#filter-list__atribute_' + _indexAnd + ' li.list-group-item').last().data('index');
        var _next = _indexOr + 1;
        
        jQuery.ajax({
            url: '/painel/or-filter-update?id={$model->id}&indexAnd=' + _indexAnd + '&indexOr=' + _next,
            success: function (_data) 
            {
                $("#filter-list__atribute_" + _indexAnd).append(_data);
                $("#filter-list__atribute_" + _indexAnd + " .item_" + _indexAnd + "_" + _indexOr + " .bnt-add__and").remove();
            },
        });
    });
            
    $(document).delegate(".remove-atribute", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();

        var _indexAnd = $(this).data('indexand');
        var _indexOr = $(this).data('indexor');
            
        var _andEl = $("#filter-list__atribute_" + _indexAnd),
        _firstAnd = $(".filter-list__atribute:first"),
        _orEl = _andEl.find(".item_" + _indexAnd + "_" + _indexOr);
            
        var _sizeAnd = $('.filter-list__atribute').length;
            
        $(".and-text-separator-" + _indexAnd).remove();
        _andEl.toggleClass("block__hide");
        _andEl.remove();
            
        $(".and-text-separator-" + $(".filter-list__atribute:first").data('index')).remove();
        _andEl.find('.list-group-item:first .or-text-separator').remove();
    });
            
    $(document).delegate(".save-filter", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();
            
        jQuery.ajax({
            url: '/painel/save-filter-update?id={$model->id}',
            data:  $('#form-filter').serialize(),
            type: 'POST',
            success: function (_data) 
            {
                iziToast.success({
                    title: 'Filtros salvos com sucesso!',
                    position: 'topCenter',
                    close: true,
                    transitionIn: 'flipInX',
                    transitionOut: 'flipOutX',
                });
            },
        });
    });
            
    $(document).delegate(".save-conf-pain", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();

        var _form = $('#form-conf-pain');
            
        jQuery.ajax({
            url: '/painel/save-config-painel?id={$model->id}',
            data: _form.serialize(),
            type: 'POST',
            success: function (_data) 
            {
                iziToast.success({
                    title: 'Configurações salvas com sucesso!',
                    position: 'topCenter',
                    close: true,
                    transitionIn: 'flipInX',
                    transitionOut: 'flipOutX',
                });
            },
        });
    });
            
    $(document).delegate(".clean-filter", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();

        $('#filter-list__container').empty();
    });
        
JS;

$this->registerJs($js);

$js_permissao = <<<JS
        
    function()
    { 
        var _value = $(this).bootstrapSwitch('state'); 
        var _permissao_id = $(this).data('permissao_id'); 
        var _perfil_id = $(this).data('perfil_id'); 
        var _gerenciador = $(this).data('gerenciador');
        
        if(_gerenciador == 'visualizar')
        {
            if(_value)
            {
                $('.div-permissao-' + _perfil_id + '-no input').bootstrapSwitch('toggleDisabled');
            }
            else
            {
                $('.div-permissao-' + _perfil_id + '-no input').bootstrapSwitch('state', false);
                $('.div-permissao-' + _perfil_id + '-no input').bootstrapSwitch('toggleDisabled');
            }
        }
        
        jQuery.ajax({
            url: '/painel/permission-painel?painel_id={$model->id}&perfil_id=' + _perfil_id + '&permissao_id=' + _permissao_id + '&state=' + _value,
            type: 'GET'
        });
    }
        
JS;

?>

<div class="sidebar--painel block__slide h-100 mh-100">

    <div class="sidebar--painelHeader d-flex justify-content-start pl-3 pr-3">

        <h5 class="modal-title mr-auto align-self-center text-uppercase">Configurações do Painel</h5>

        <button type="button" class="btn btn-sm btn-link align-self-center text-uppercase close-painel">Fechar</button>

    </div>

    <ul id="sidebar--nav-tabs" class="nav nav-tabs nav-justified nav-tabs-sections">

        <li class="nav-item">

            <a class="nav-link flex-column d-flex justify-content-center align-items-center active " href="#filtro" data-toggle="tab" role="tab">

                <div class="item-self-center">

                    <i class="mx-auto bp-filter text-center d-block"></i>

                    <span class="mx-auto text-uppercase text-center d-block">Filtros</span>

                </div>

            </a>

        </li>

        <li class="nav-item">

            <a class="nav-link flex-column d-flex justify-content-center align-items-center" href="#permissao" data-toggle="tab" role="tab">

                <div class="item-self-center">

                    <i class="mx-auto bp-user text-center d-block"></i>

                    <span class="mx-auto text-uppercase text-center d-block">Permissões</span>

                </div>

            </a>

        </li>

        <li class="nav-item">

            <a class="nav-link flex-column d-flex justify-content-center align-items-center" href="#configuracoes" data-toggle="tab" role="tab">

                <div class="item-self-center">

                    <i class="mx-auto bp-config-gear text-center d-block"></i>

                    <span class="mx-auto text-uppercase text-center d-block">Config. Avançadas</span>

                </div>

            </a>

        </li>

    </ul>

    <div id="sidebar--tab-pane" class="d-flex w-100">

        <div class="tab-content">

            <div class="tab-pane active" id="filtro" role="tabpanel">


            </div>

            <div class="tab-pane" id="permissao" role="tabpanel ">

                <div class="d-flex align-item-center align-items-stretch ">

                    <div class="col-12 tab-pane--title ">

                        <h3>Permissões do Painel</h3>

                    </div>

                </div>

                <div class="bp--accordion" id="accordion" role="tablist" aria-multiselectable="true">

                    <?php foreach($model->getPermissoes() as $perfil_id => $perfil) : ?>

                        <div class="card">

                            <div class="card-header" role="tab" id="heading<?= $perfil_id ?>">

                                <h5 class="mb-0 d-flex justify-content-start">

                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $perfil_id ?>" aria-expanded="true" aria-controls="collapse<?= $perfil_id ?>"><?= $perfil['nome'] ?></a>

                                </h5>

                            </div>

                            <div id="collapse<?= $perfil_id ?>" class="collapse" role="tabpanel" aria-labelledby="heading<?= $perfil_id ?>">

                                <div class="card-block">

                                    <div class="list-group p-3">

                                        <?php

                                        $show = FALSE;

                                        foreach($perfil['permissoes'] as $permissao_id => $permissao):

                                        if($permissao['attributes']['gerenciador'] == 'visualizar' && $permissao['value'])
                                        {
                                            $show = TRUE;
                                        }

                                        $disabled = ($permissao['attributes']['gerenciador'] == 'visualizar' || $show) ? false : true;

                                        ?>

                                            <div class="div-permissao-<?= $perfil_id ?>-<?= ($permissao['attributes']['gerenciador'] == 'visualizar') ? 'yes' : 'no' ?> mb-3">

                                                <label><?= $permissao['attributes']['nome'] ?></label>

                                                <?= SwitchInput::widget([
                                                    'name' => 'permissao_' . $perfil_id . '_' . $permissao_id,
                                                    'value' => $permissao['value'],
                                                    'disabled' => $disabled,
                                                    'options' =>
                                                    [
                                                        'data-perfil_id' => $perfil_id,
                                                        'data-permissao_id' => $permissao_id,
                                                        'data-gerenciador' => $permissao['attributes']['gerenciador']
                                                    ],
                                                    'pluginOptions' =>
                                                    [
                                                        'size' => 'mini',
                                                        'onText' => 'Sim',
                                                        'offText' => 'Não',
                                                        'onColor' => 'success',
                                                        'offColor' => 'danger',
                                                    ],
                                                    'pluginEvents' =>
                                                    [
                                                        "switchChange.bootstrapSwitch" => $js_permissao
                                                    ]
                                                ]); ?>

                                            </div>

                                        <?php endforeach; ?>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endforeach ?>

                </div>

            </div>

            <div class="tab-pane" id="configuracoes" role="tabpanel ">

                <div class="d-flex align-item-center align-items-stretch ">

                    <div class="col-12 tab-pane--title ">

                        <h3>Configurações Avançadas <button type="button" class="btn btn-sm btn-outline-primary align-self-center text-uppercase save-conf-pain float-right">Salvar</button></h3>

                    </div>

                </div>

                <div class="bp--accordion" id="accordion" role="tablist" aria-multiselectable="true">

                    <?php $form = ActiveForm::begin([
                        'id' => 'form-conf-pain',
                        'enableClientValidation' => TRUE,
                    ]); ?>

                    <div class="row">

                        <div class="col-lg-6">

                            <label class="mt-2">Painel Privado</label>

                        </div>

                        <div class="col-lg-6">

                            <?= $form->field($model, 'privado')->dropDownList([0 => 'Não', 1 => 'Sim'])->label(false); ?>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-12">

                            <label title="Função JS" class="mt-2">Javascript</label>

                        </div>

                        <div class="col-lg-12">

                            <?= $form->field($model, 'javascript')->textArea(['rows' => 20])->label(false) ?>

                        </div>

                    </div>

                    <?php ActiveForm::end(); ?>

                </div>

            </div>

        </div>

    </div>

</div>