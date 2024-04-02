<?php

use app\magic\SqlMagic;
use app\magic\ActiveGraphMagic;

$argumentos = SqlMagic::getArgumentos($model->id);

$css = <<<CSS
        
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
        
    .or-text-separator
    {
        margin-top: -40px;
        color: #FFF;
        background-color: #177487;
        padding: 5px;
        z-index: 2;
        border-radius: 5px;
        margin-left: auto;
        margin-right: auto;
    }
        
    .and-button-text
    {
        font-size: 24px;
    }
        
CSS;

$this->registerCss($css);

$t_ou = Yii::t('app', 'view.geral.ou');

if($can_filter_graph):

$js_filter = <<<JS
        
    $(document).delegate(".select-choose-field", 'change', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();
        
        var _indexAnd = $(this).data('indexand');
        var _indexOr = $(this).data('indexor');
        var _id = $(this).val();
        
        jQuery.ajax({
            url: '/ajax/get-type-view?id=' + _id + '&and=' + _indexAnd + '&or=' + _indexOr,
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
            url: '/ajax/get-field-view?id=' + _id + '&value=' + _val + '&and=' + _indexAnd + '&or=' + _indexOr,
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
            url: '/ajax/get-field-view?id=' + _id + '&value=' + _val + '&and=' + _indexAnd + '&or=' + _indexOr + '&tag=' + _tag,
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
            url: '/ajax/and-filter-view?id={$model->id}&index=' + _next,
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
            url: '/ajax/or-filter-view?id={$model->id}&indexAnd=' + _indexAnd + '&indexOr=' + _next,
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
            
        var _sizeAnd = $('.filter-list__atribute').length,
            _sizeOr = _andEl.find('li.list-group-item').length,
            _isLastOr = _orEl.find('.bnt-add__and').length;
            
        if(_sizeOr == 1)
        {
            $(".and-text-separator-" + _indexAnd).remove();
            _andEl.toggleClass("block__hide");
            _andEl.remove();
        }
        else
        {
            $(".or-text-separator-" + _indexOr).remove();
            _orEl.toggleClass("block__hide");
            _orEl.remove();
            
            if(_isLastOr == 1)
            {
                _andEl.find('.list-group-item').last().append('<button class="bnt-add__and" data-index="'+ _indexAnd +'"><i class="bp-plus"></i> {$t_ou}</button>');
            }
        }
            
        $(".and-text-separator-" + $(".filter-list__atribute:first").data('index')).remove();
        _andEl.find('.list-group-item:first .or-text-separator').remove();
    });
            
    $(document).delegate(".save-filter", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();

        var _form = $('#form-filter');
            
        jQuery.ajax({
            url: '/ajax/save-filter-view?id={$model->id}',
            data: _form.serialize(),
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
            
    $(document).delegate(".restaure-user-graph", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();

        jQuery.ajax({
            url: '/ajax/restaure-graph?id={$model->id}',
            type: 'POST',
            success: function (_data) 
            {
                iziToast.success({
                    title: 'Gráficos restaurados com sucesso!',
                    position: 'topCenter',
                    close: true,
                    transitionIn: 'flipInX',
                    transitionOut: 'flipOutX',
                });
            
                $('.bp--accordion-graph .choose-graph').removeClass('active');
                $('.bp--accordion-graph .chdefault').addClass('active');
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
   
$this->registerJs($js_filter);

endif;

if($can_change_graph) :
            
$js_graph = <<<JS
                    
    $(document).delegate(".choose-graph", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();

        var _id = $(this).data('id');
        var _graph = $(this).data('graph');
                    
        $('.list-group-item.list-group-item-' + _id).removeClass('active');
        $(this).addClass('active');
                    
        jQuery.ajax({
            url: '/ajax/change-user-graph?id={$model->id}&field=' + _id + '&graph=' + _graph,
            type: 'GET',
            success: function (_data) 
            {
                iziToast.success({
                    title: 'Gráfico salvo com sucesso!',
                    position: 'topCenter',
                    close: true,
                    transitionIn: 'flipInX',
                    transitionOut: 'flipOutX',
                });
            },
        });
    });
            
JS;
   
$this->registerJs($js_graph);

endif;
            
$js = <<<JS
            
    $(document).delegate(".update-view", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();

        var _csrfToken = $('meta[name="csrf-token"]').attr("content");

        jQuery.ajax({
            url: '/grafico/view?id={$model->id}',
            type: 'POST',
            data: 
            {
                index: 0,
                token: null,
                _csrf: _csrfToken
            },
            success: function (data) 
            {
                $('.card-consulta').html(data);
            },
            beforeSend: function ()
            {
                $('.div-loading').addClass("loading");
            },
            complete: function () 
            {
                setTimeout(function() { $('.div-loading').removeClass("loading");}, 300);
            }
        });
    });
        
JS;

$this->registerJs($js);

?>

<div class="sidebar--painel block__slide h-100 mh-100">

    <div class="sidebar--painelHeader d-flex justify-content-start pl-3 pr-3">

        <h5 class="modal-title mr-auto align-self-center text-uppercase">Config. da Consulta</h5>

        <button type="button" class="btn btn-sm btn-link align-self-center text-uppercase close-painel">Fechar</button>

        <button type="button" class="btn btn-sm btn-outline-primary align-self-center text-uppercase update-view">Atualizar</button>

    </div>

    <?php if($can_filter_graph && $can_change_graph): ?>
    
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

                <a class="nav-link flex-column d-flex justify-content-center align-items-center" href="#visualizacao" data-toggle="tab" role="tab">

                    <div class="item-self-center">

                        <i class="mx-auto bp-chart-type text-center d-block"></i>

                        <span class="mx-auto text-uppercase text-center d-block">Gráficos</span>

                    </div>

                </a>

            </li>
            
        </ul>
    
    <?php endif; ?>

    <div id="sidebar--tab-pane" class="d-flex w-100">

        <div class="tab-content">

            <?php if($can_filter_graph): ?>

                <div class="tab-pane active" id="filtro" role="tabpanel">

                </div>
            
            <?php endif; ?>

            <?php if($can_change_graph): ?>

                <div class="tab-pane <?= (!$can_filter_graph) ? 'active' : '' ?>" id="visualizacao" role="tabpanel ">

                    <div class="d-flex align-item-center align-items-stretch ">

                        <div class="col-12 tab-pane--title ">
                            
                            <h3>
                
                                Gráficos Disponíveis 

                                <button type="button" class="mt-1 btn btn-sm btn-outline-primary align-self-center text-uppercase restaure-user-graph float-right">Restaurar Padrão</button>

                            </h3>

                            <p class="text-uppercase ">Para alterar o tipo de visualização na consulta basta selecionar um dos gráficos disponíveis abaixo.</p>

                        </div>

                    </div>

                    <div class="bp--accordion bp--accordion-graph" id="accordion" role="tablist" aria-multiselectable="true">

                        <?php foreach($argumentos as $index_argumento => $argumento) : ?>

                            <div class="card">

                                <div class="card-header" role="tab" id="heading<?= $index_argumento ?>">

                                    <h5 class="mb-0 d-flex justify-content-start">

                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $index_argumento ?>" aria-expanded="true" aria-controls="collapse<?= $index_argumento ?>"><?= $argumento->campo->nome ?></a>

                                        <?php

                                            $tipo_grafico = ActiveGraphMagic::getActiveGraph($argumento);
                                            $data_icon = ActiveGraphMagic::getIconData();
                                            $icon = (isset($data_icon[$tipo_grafico])) ? $data_icon[$tipo_grafico] : 'colum';

                                        ?>

                                    </h5>

                                </div>

                                <div id="collapse<?= $index_argumento ?>" class="collapse" role="tabpanel" aria-labelledby="heading<?= $index_argumento ?>">

                                    <div class="card-block">

                                        <div class="list-group">

                                            <?php foreach($data_icon as $field => $icon) : ?>

                                                <button type="button" data-id="<?= $argumento->id ?>" data-index="<?= $index_argumento ?>" data-graph="<?= $field ?>"
                                                    class="<?= ($argumento->tipo_grafico == $field) ? ' chdefault ' : '' ?>list-group-item list-group-item-<?= $argumento->id ?> choose-graph list-group-item-action py-3 <?= ($tipo_grafico == $field || (!$tipo_grafico && $argumento->tipo_grafico == $field)) ? 'active' : '' ?>">

                                                    <i class="bp-<?= $icon ?> mr-2"></i>

                                                    <span class="text-uppercase "><?= ActiveGraphMagic::getIconName($field) ?> <?= ($argumento->tipo_grafico == $field) ? '(Padrão)' : '' ?></span>

                                                </button>

                                            <?php endforeach; ?>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>
            
            <?php endif; ?>

        </div>

    </div>

</div>