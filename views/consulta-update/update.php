<?php

use yii\helpers\Html;
use app\magic\ConsultaMagic;
use app\magic\PreviewMagic;
use yii\helpers\Url;

$this->title = \Yii::t('app', 'view.alterar') . ' ' . strtolower(\Yii::t('app', 'geral.consulta')) . ': ' . $model->nome;

$index = 0;
$post = [];
$valores = $model->find()->getValueFields($model->id);
$argumentos = $model->find()->getArgFields($model->id);
$series = $model->find()->getSerieFields($model->id);
$inultilizados = $model->find()->getUnusedFields($model->id_indicador, $model->id);

$post['index'] = $index;

foreach($valores as $i => $valor)
{
    $post['valor'][$i] = $valor->campo->id;
}

foreach($argumentos as $i => $argumento)
{
    $post['argumento'][$i]['id'] = $argumento->campo->id;
    $post['argumento'][$i]['type'] = $argumento->tipo_grafico;
    $post['argumento'][$i]['sort'] = $argumento->ordenacao;
    $post['argumento'][$i]['tipo_numero'] = $argumento->tipo_numero;
}

foreach($series as $i => $serie)
{
    $post['serie'][$i] = $serie->campo->id;
}

$preview_data = PreviewMagic::getData($model, $post, $index, null, $sqlMode);
        
$css = <<<CSS

.dropdown-submenu 
{
    position: relative;
}

.dropdown-submenu > .dropdown-menu 
{
    top: 0;
    left: 100%;
    margin-top: -6px;
    margin-left: -1px;
    -webkit-border-radius: 0 6px 6px 6px;
    -moz-border-radius: 0 6px 6px;
    border-radius: 0 6px 6px 6px;
}

.dropdown-submenu:hover > .dropdown-menu
{
    display: block;
}

.dropdown-submenu > a:after 
{
    display: block;
    content: " ";
    float: right;
    width: 0;
    height: 0;
    border-color: transparent;
    border-style: solid;
    border-width: 5px 0 5px 5px;
    border-left-color: #ccc;
    margin-top: 5px;
    margin-right: -10px;
}

.dropdown-submenu:hover > a:after
{
    border-left-color: #fff;
}

.dropdown-submenu .pull-left 
{
    float: none;
}

.dropdown-submenu .pull-left > .dropdown-menu
{
    left: -100%;
    margin-left: 10px;
    -webkit-border-radius: 6px 0 6px 6px;
    -moz-border-radius: 6px 0 6px 6px;
    border-radius: 6px 0 6px 6px;
}
        
.choose-sort .badge,
.choose-type .badge,
.choose-tipo_numero .badge
{
    background-color: #26808f; 
    color: #FFF !important;
}
        
.attr-list-item .attr-list__toolbar.dropdown .btn--noborder:hover
{
    background-color: #FFF;
}
        
.attr-list-item .attr-list__toolbar.dropdown .btn--noborder:hover i 
{
    color: #007EC3;
    cursor: pointer;
}
        
.consulta__fonte .consulta__headerconfig::before {
    top: 85px;
}
        
.consulta__paramentros,
.consulta__fonte,
.consulta__preview
{
    height: calc(100vh - 48px);
}

.block--parametro_val .attr-list,
.block--parametro_ser .attr-list
{
    min-height: 100px;
}
        
.btn-visualizar-consulta, .btn-salvar-consulta
{
    cursor: pointer;
}
        
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

$url_visualizar = Url::toRoute(['visualizar', 'id' => $model->id]);

$t_confirm = \Yii::t('app', 'view.geral.certeza_salvar_alteracoes');
$t_sim = \Yii::t('app', 'view.sim');
$t_cancel = \Yii::t('app', 'view.cancelar');
$t_erro_consulta = \Yii::t('app', 'view.consulta.erro_salvar_consulta');
$t_mensagem_consulta_vazia = \Yii::t('app', 'view.consulta.msg_erro_salvar_consulta_vazia');
$t_mensagem_consulta_parametro = \Yii::t('app', 'view.consulta.msg_erro_salvar_consulta_parametro');
$t_consulta_salva = \Yii::t('app', 'view.consulta.msg_consulta_salva_sucesso');
$t_ativo = \Yii::t('app', 'view.geral.ativo');
$t_campos_adicionais = \Yii::t('app', 'view.consulta.campos_adicionais');
$t_cores_personalizadas = \Yii::t('app', 'view.consulta.cores_personalizadas');
$t_conf_avancadas = \Yii::t('app', 'view.consulta.conf_avancadas');

$js = <<<JS

    $(document).ready(function() 
    {
        datamodified = 0;
    
        $(document).delegate('.btn-visualizar-consulta', 'click', function()
        {
            if (datamodified == 1)
            {
                swal({
                    title: "{$t_confirm}",
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonText: '{$t_cancel}',
                    confirmButtonColor: '#007EC3',
                    confirmButtonText: '{$t_sim}'
                }).then((result) => 
                {
                    if (result.value) 
                    {
                        window.location.replace('{$url_visualizar}');
                    }
                });
            }
            else
            {
                window.location.replace('{$url_visualizar}');
            }            
        });

        if($('ul#valor').children().length > 0)
        {
            $('ul#valor').parent().removeClass('block--parametro_list_empty');
        }
        else
        {
            $('ul#valor').parent().addClass('block--parametro_list_empty');
        }

        if($('ul#argumento').children().length > 0)
        {
            $('ul#argumento').parent().removeClass('block--parametro_list_empty');
        }
        else
        {
            $('ul#argumento').parent().addClass('block--parametro_list_empty');
        }

        if($('ul#serie').children().length > 0)
        {
            $('ul#serie').parent().removeClass('block--parametro_list_empty');
        }
        else
        {
            $('ul#serie').parent().addClass('block--parametro_list_empty');
        }
    });
        
    $(document).delegate('.btn-salvar-consulta', 'click', function()
    {
        var _valor = [];
        
        $('ul#valor').children().each(function() 
        {
            _valor.push($(this).data('id'));
        });

        var _argumento = [];

        $('ul#argumento').children().each(function() 
        {
            _argumento.push({'id': $(this).data('id'), 'type': $(this).data('type'), 'sort': $(this).data('sort'), 'tipo_numero': $(this).data('tipo_numero')});
        });
        
        var _serie = [];

        $('ul#serie').children().each(function() 
        {
            _serie.push($(this).data('id'));
        });

        var _error = false;
        
        if($('#painelName').val().trim() == '')
        {
            _error = true;
        
            iziToast.error({
                title: '{$t_erro_consulta}',
                message: '{$t_mensagem_consulta_vazia}',
                position: 'topCenter',
                close: true,
                transitionIn: 'flipInX',
                transitionOut: 'flipOutX',
            });
        }
        
        if($('ul#valor').children().length == 0)
        {
            _error = true;
        
            iziToast.error({
                title: '{$t_erro_consulta}',
                message: '{$t_mensagem_consulta_vazia}',
                message: '{$t_mensagem_consulta_parametro}',
                position: 'topCenter',
                close: true,
                transitionIn: 'flipInX',
                transitionOut: 'flipOutX',
            });
        }
        
        if(!_error)
        {
            var _data = {'nome': $('#painelName').val().trim(), 'valor': _valor, 'argumento': _argumento, 'serie': _serie};

            $.post({
                url: '/consulta/alterar?id={$model->id}',
                type: 'POST',
                data: _data,
                dataType: 'json',
                success: function(msg) 
                {
                    iziToast.success({
                        title: '{$t_consulta_salva}',
                        position: 'topCenter',
                        close: true,
                        transitionIn: 'flipInX',
                        transitionOut: 'flipOutX',
                    });
                
                    datamodified = 0;
                }
            });
        }
    });
            
    Sortable.create(document.getElementById('fonte-dados'), {
        group: { name: 'blocks', pull: true, put: true },
        animation: 150,
        filter: ".static",
        onStart: function (evt) 
        {
            document.documentElement.classList.add("draggable-cursor");
        },
        onEnd: function (evt) 
        {
            document.documentElement.classList.remove("draggable-cursor");
        },
    });

    Sortable.create(document.getElementById('valor'), {
        group: 
        {   name: 'blocks', 
            pull: true,
            put: function (to, from, dragEl) 
            {
                return dragEl.classList.contains('acceptval') && to.el.children.length == 0;
            } 
        },
        onStart: function (evt) 
        {
            document.documentElement.classList.add("draggable-cursor");
        },
        onEnd: function (evt) 
        {
            document.documentElement.classList.remove("draggable-cursor");
        },
        onAdd: function (evt) 
        {
            var _id = evt.to.id;
            var _el = $('#' + _id);
            if(_el.children().length > 0)
            {
                _el.parent().removeClass('block--parametro_list_empty');
            }
            else
            {
                _el.parent().addClass('block--parametro_list_empty');
            }

            document.documentElement.classList.remove("draggable-cursor");
            datamodified = 1;
        },
        onRemove: function (evt) 
        {
            var _id = evt.to.id;
            var _el = $('#' + _id);
            if(_el.children().length > 0)
            {
                _el.parent().removeClass('block--parametro_list_empty');
            }
            else
            {
                _el.parent().addClass('block--parametro_list_empty');
            }

            document.documentElement.classList.remove("draggable-cursor");
            datamodified = 1;
        },
        animation: 150,
    });

    Sortable.create(document.getElementById('argumento'), {
        group: 
        {   name: 'blocks', 
            pull: true,
            put: function (to, from, dragEl) 
            {
                return dragEl.classList.contains('acceptarg');
            } 
        },
        onStart: function (evt) 
        {
            document.documentElement.classList.add("draggable-cursor");
        },
        onEnd: function (evt) 
        {
            document.documentElement.classList.remove("draggable-cursor");
        },
        onMove: function (evt) 
        {
            datamodified = 1;
        },
        onAdd: function (evt) 
        {
            var _id = evt.to.id;
            var _el = $('#' + _id);
            if(_el.children().length > 0)
            {
                _el.parent().removeClass('block--parametro_list_empty');
            }
            else
            {
                _el.parent().addClass('block--parametro_list_empty');
            }

            document.documentElement.classList.remove("draggable-cursor");
            datamodified = 1;
        },
        onRemove: function (evt) 
        {
            var _id = evt.to.id;
            var _el = $('#' + _id);
            if(_el.children().length > 0)
            {
                _el.parent().removeClass('block--parametro_list_empty');
            }
            else
            {
                _el.parent().addClass('block--parametro_list_empty');
            }

            document.documentElement.classList.remove("draggable-cursor");
            datamodified = 1;
        },
        animation: 150,
    });

    Sortable.create(document.getElementById('serie'), {
        group: 
        {   name: 'blocks', 
            pull: true,
            put: function (to, from, dragEl) 
            {
                return dragEl.classList.contains('acceptser') && to.el.children.length == 0;;
            } 
        },
        onStart: function (evt) 
        {
            document.documentElement.classList.add("draggable-cursor");
        },
        onEnd: function (evt) 
        {
                document.documentElement.classList.remove("draggable-cursor");
        },
        onAdd: function (evt) 
        {
            var _id = evt.to.id;
            var _el = $('#' + _id);
            if(_el.children().length > 0)
            {
                $('.config-avancadas').attr('data-isserie', 1);
                _el.parent().removeClass('block--parametro_list_empty');
                $('.choose-graph-type[data-type=pie]').hide();
            }
            else
            {
                $('.config-avancadas').attr('data-isserie', 0);
                _el.parent().addClass('block--parametro_list_empty');
                $('.choose-graph-type[data-type=pie]').show();
            }

            document.documentElement.classList.remove("draggable-cursor");
            datamodified = 1;
        },
        onRemove: function (evt) 
        {
            var _id = evt.to.id;
            var _el = $('#' + _id);
            if(_el.children().length > 0)
            {
                $('.config-avancadas').attr('data-isserie', 1);
                _el.parent().removeClass('block--parametro_list_empty');
                $('.choose-graph-type[data-type=pie]').hide();
            }
            else
            {
                $('.config-avancadas').attr('data-isserie', 0);
                _el.parent().addClass('block--parametro_list_empty');
                $('.choose-graph-type[data-type=pie]').show();
            }

            document.documentElement.classList.remove("draggable-cursor");
            datamodified = 1;
        },
        animation: 150,
    });
            
    $(document).ready(function()
    {
        $('.dropdown .dropdown-menu .choose-type').on("click", function(e)
        {
            var _val = $(this).data('val');
            var _index = $(this).data('index');
            var _elli = $('li#el-' + _index);
            var _elitem = $('.data-type.el-item-' + _index);
            _elli.data('type', _val);
            _elli.attr('data-type', _val);
            _elitem.data('type', _val);
            _elitem.attr('data-type', _val);
                
            _elitem.parent().parent().find('.config-avancadas').attr('data-graph', _val);

            _elitem.find('i')
                    .removeClass('bp-chart--area')
                    .removeClass('bp-chart--line').
                    removeClass('bp-chart--bar').
                    removeClass('bp-chart--colum').
                    removeClass('bp-chart--pie').
                    removeClass('bp-chart--donut').
                    removeClass('bp-chart--funel').
                    removeClass('bp-chart--kpi').
                    removeClass('bp-kanban').
                    removeClass('bp-chart--grid');
            _elitem.parent().find('.dropdown-menu .dropdown-item span').remove();

            switch(_val) 
            {
                case 'area':
                    _elitem.find('i').addClass('bp-chart--area');
                    break;
                case 'line':
                    _elitem.find('i').addClass('bp-chart--line');
                    break;
                case 'bar':
                    _elitem.find('i').addClass('bp-chart--bar');
                    break;
                case 'column':
                    _elitem.find('i').addClass('bp-chart--colum');
                    break;
                case 'pie':
                    _elitem.find('i').addClass('bp-chart--pie');
                    break;
                case 'donut':
                    _elitem.find('i').addClass('bp-chart--donut');
                    break;
                case 'funnel':
                    _elitem.find('i').addClass('bp-chart--funel');
                    break;
                case 'kpi':
                    _elitem.find('i').addClass('bp-chart--kpi');
                    break;
                case 'table':
                    _elitem.find('i').addClass('bp-chart--grid');
                    break;
                case 'heatmap':
                    _elitem.find('i').addClass('bp-kanban');
                    break;
                
                default:
            }

            $(this).append('<span class="badge badge-default badge-pill ml-auto">{$t_ativo}</span>');
            datamodified = 1;
            e.stopPropagation();
            e.preventDefault();
        });

        $('.dropdown .dropdown-menu .choose-sort').on("click", function(e)
        {
            var _val = $(this).data('val');
            var _index = $(this).data('index');
            var _elli = $('li#el-' + _index);
            var _elitem = $('.data-sort.el-item-' + _index);

            _elli.data('sort', _val);
            _elli.attr('data-sort', _val);
            _elitem.data('sort', _val);
            _elitem.attr('data-sort', _val);

            _elitem.find('svg').removeClass('fa-sort-alpha-down').removeClass('fa-sort-alpha-up');
            _elitem.parent().find('.dropdown-menu .dropdown-item span').remove();

            if(_val == 0 || _val == 2)
            {
                _elitem.find('svg').addClass('fa-sort-alpha-down');
            }
            else
            {
                _elitem.find('svg').addClass('fa-sort-alpha-up');
            }

            $(this).append('<span class="badge badge-default badge-pill ml-auto">{$t_ativo}</span>');
            datamodified = 1;
            e.stopPropagation();
            e.preventDefault();
        });
                
        $('.dropdown .dropdown-menu .choose-tipo_numero').on("click", function(e)
        {
            var _val = $(this).data('val');
            var _index = $(this).data('index');
            var _elli = $('li#el-' + _index);
            var _elitem = $('.data-tipo_numero.el-item-' + _index);

            _elli.data('tipo_numero', _val);
            _elli.attr('data-tipo_numero', _val);
            _elitem.data('tipo_numero', _val);
            _elitem.attr('data-tipo_numero', _val);

            _elitem.parent().find('.dropdown-menu .dropdown-item span').remove();

            $(this).append(' <span class="badge badge-default badge-pill ml-auto">{$t_ativo}</span>');
            datamodified = 1;
            e.stopPropagation();
            e.preventDefault();
        });
                
        function getData()
        {
            var _valor = [];

            $('ul#valor').children().each(function() 
            {
                _valor.push($(this).data('id'));
            });

            var _argumento = [];

            $('ul#argumento').children().each(function() 
            {
                _argumento.push({'id': $(this).data('id'), 'type': $(this).data('type'), 'sort': $(this).data('sort'), 'tipo_numero': $(this).data('tipo_numero')});
            });

            var _serie = [];

            $('ul#serie').children().each(function() 
            {
                _serie.push($(this).data('id'));
            });

            var _data = {'index': 0, 'valor': _valor, 'argumento': _argumento, 'serie': _serie};

            return _data;
        }
                
        $(document).on("click", ".choose-pallete", function(e)
        {
            var _pallete_id = $(this).data('id');
            
            $.ajax({
                url: '/consulta/update-pallete?id={$model->id}&pallete_id=' + _pallete_id,
                type: 'GET',
                success: function(response) 
                {
                    $('.choose-graph-type.active').click();
                },
            });
        });

        $(document).on("click", ".update-graph", function(e)
        {
            e.preventDefault();

            updateGraph();
        });
                
        function updateGraph()
        {
            var _data = getData();

            $.ajax({
                url: '/consulta/preview?id={$model->id}&type=null&sqlMode={$sqlMode}',
                type: 'POST',
                data: _data,
                success: function(response) 
                {
                    $('.consulta__preview').html(response);
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
        }
                
        $('.dropdown .dropdown-menu .open-config').on("click", function(e)
        {
            var _id = $(this).data('id');
            var _title = $(this).data('title');
                
            $(this).parent().parent().removeClass('show');
                
            jQuery.ajax({
                url: '/consulta/config-field?id={$model->id}&item_id=' + _id,
                type: 'POST',
                success: function (data) 
                {
                    $('#modal-config .modal-title').html('{$t_campos_adicionais}:: ' + _title);
                    $('#modal-config .iziModal__body').html(data);
                    $('#modal-config').iziModal('open');
                },
            });
        });
        
        $('.dropdown .dropdown-menu .open-color').on("click", function(e)
        {
            var _id = $(this).data('id');
            var _title = $(this).data('title');
                
            $(this).parent().parent().removeClass('show');
                
            jQuery.ajax({
                url: '/consulta/config-color?id={$model->id}&item_id=' + _id,
                type: 'POST',
                success: function (data) 
                {
                    $('#modal-color .modal-title').html('{$t_cores_personalizadas}:: ' + _title);
                    $('#modal-color .iziModal__body').html(data);
                    $('#modal-color').iziModal('open');
                },
            });
        });
    })
    
    $(document).delegate('#filtro-argumento', 'keyup', function()
    {
        var _val = $(this).val().toLowerCase();
        
        if(_val == '')
        {
            $('ul#fonte-dados li.attr-list-item').removeClass('not-filtered')
        }
        else
        {
            $('ul#fonte-dados li.attr-list-item span.title-el').filter(function() {
                return $(this).text().toLowerCase().indexOf(_val) < 0;
            }).parent().addClass('not-filtered');
            
            $('ul#fonte-dados li.attr-list-item span.title-el').filter(function() {
                return $(this).text().toLowerCase().indexOf(_val) >= 0;
            }).parent().removeClass('not-filtered');    
        }
    });
            
JS;

$this->registerJs($js);

if(Yii::$app->user->identity->id == 1) :
    
$js_bpone = <<<JS

    $(document).ready(function() 
    {
        $('.dropdown .dropdown-menu .config-avancadas-item').on("click", function(e)
        {
            var _id = $(this).data('id');
            var _view = $(this).data('view');
            var _element  = $('.config-avancadas-' + _id);
            var _title = _element.data('title');
            var _graph = _element.attr('data-graph');
            var _isserie = _element.attr('data-isserie');
                
            $(this).parent().parent().parent().parent().removeClass('show');
                
            jQuery.ajax({
                url: '/consulta/advanced-config?id={$model->id}&campo_id=' + _id + '&view=' + _view + '&type=' + _graph + '&is_serie=' + _isserie,
                type: 'POST',
                success: function (data) 
                {
                    $('#modal-advanced .modal-title').html('{$t_conf_avancadas}:: ' + _title);
                    $('#modal-advanced .iziModal__body').html(data);
                    $('#modal-advanced').iziModal('open');
                },
            });
        });
    })
            
JS;

$this->registerJs($js_bpone);

endif;

$index_id = 0;

?>

<?= $this->render('_layouts/_filter', compact('model')); ?>

<?= $this->render('//layouts/_partials/_left', ['contracted' => TRUE]); ?>

<div id="modal-config" style="display: none;">
    
    <div class="iziModal__header d-flex justify-content-start pl-3 pr-3 ">
        
        <h5 class="modal-title mr-auto align-self-center text-uppercase"></h5>
        
        <button type="button" class="btn btn-sm btn-link--inverse align-self-center text-uppercase cursor-pointer" data-izimodal-close="">X</button>
    
    </div>

    <div class="iziModal__body justify-content-center align-items-center" style="padding: 20px 10px;"></div>

</div>

<div id="modal-color" style="display: none;">

    <div class="iziModal__header d-flex justify-content-start pl-3 pr-3 ">

        <h5 class="modal-title mr-auto align-self-center text-uppercase"></h5>

        <button type="button" class="btn btn-sm btn-link--inverse align-self-center text-uppercase cursor-pointer" data-izimodal-close="">X</button>

    </div>

    <div class="iziModal__body justify-content-center align-items-center" style="padding: 20px 10px;"></div>

</div>

<?php if(Yii::$app->user->identity->id == 1) : ?>

    <div id="modal-advanced" style="display: none;">

        <div class="iziModal__header d-flex justify-content-start pl-3 pr-3 ">

            <h5 class="modal-title mr-auto align-self-center text-uppercase"></h5>

            <button type="button" class="btn btn-sm btn-link--inverse align-self-center text-uppercase cursor-pointer" data-izimodal-close="">X</button>

        </div>

        <div class="iziModal__body justify-content-center align-items-center" style="padding: 20px 10px;"></div>

    </div>

<?php endif; ?>

<script>
            
    $(function() 
    {
        $("#modal-config").iziModal({
            transitionIn: '',
            transitionOut: '',
            transitionInOverlay: '',
            transitionOutOverlay: ''
        });

        $("#modal-color").iziModal({
            transitionIn: '',
            transitionOut: '',
            transitionInOverlay: '',
            transitionOutOverlay: ''
        });

    <?php if(Yii::$app->user->identity->id == 1) : ?>
        
            $("#modal-advanced").iziModal({
                transitionIn: '',
                transitionOut: '',
                transitionInOverlay: '',
                transitionOutOverlay: ''
            });
            
        <?php endif; ?>
    });

</script>

<div class="page-content inset h-100 mh-100">
    
    <nav class="nav pageContent--nav align-item-center justify-content-start">
        <form class="form-inline painel__title--edit d-flex align-items-center justify-content-end col-12">
            <label class="sr-only" for="painelName"><?= Yii::t('app', 'view.consulta.nome_consulta') ?></label>
            <input type="text" value="<?= $model->nome ?>" class="form-control mr-auto w-75" id="painelName" placeholder="<?= Yii::t('app', 'view.consulta.nome_consulta') ?>">
            <button type="button" class="btn btn-sm btn-link--inverse btn-visualizar-consulta text-uppercase trigger-warning"><?= Yii::t('app', 'view.visualizar') ?></button>
            <button type="button" class="btn btn-outline-light btn-salvar-consulta text-uppercase"><?= Yii::t('app', 'view.salvar') ?></button>

            <a class="nav-link open-config" title="<?= Yii::t('app', 'view.consulta.conf_consulta') ?>" href="#">

                <i class="ml-auto config-update-consulta bp-config-gear open-config" data-id="<?= $model->id ?>"></i>

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
        </form>
    </nav>

    <div class="container-fluid justify-content-between" id="content--container">

        <div class="row">

            <div class="col-xl-3 consulta consulta__fonte px-0">

                <div class="consulta__headerconfig">

                    <div class="d-flex align-item-center justify-content-end">

                        <h3 class="mr-auto align-self-center"><?= Yii::t('app', 'view.consulta.fonte_dados') ?> </h3>

                    </div>

                    <p class="text-uppercase" style="font-size: 14px;"><?= Yii::t('app', 'geral.indicador') ?>:::
                        <?= Html::a(mb_strtoupper($model->indicador->nome), ['/indicador/index']); ?>
                    </p>
                </div>

                <div class="consulta__cubocontent">

                    <div class="typeahead__container" id="typeahead__container-filtro-argumento">

                        <div class="typeahead__field">

                            <span class="typeahead__query">

                                <input class="js-typeahead-sidebar" id="filtro-argumento" style="text-transform: uppercase;" placeholder="<?= Yii::t('app', 'view.consulta.pesquise_campo') ?>">

                            </span>

                            <span class="typeahead__button"><i class="bp-search"></i></span>

                        </div>

                    </div>

                    <ul id="fonte-dados" class="attr-list">

                        <?= ConsultaMagic::getFields($inultilizados, TRUE, $index_id, (sizeof($series) > 0)); ?>

                    </ul>
                </div>

            </div>

            <div class="col-xl-3 consulta consulta__paramentros">

                <div class="consulta__headerconfig px-0 pb-0">
                    <div class="d-flex align-item-center justify-content-end">
                        <h3 class="mr-auto align-self-center"><?= Yii::t('app', 'view.consulta.parametros') ?></h3>
                    </div>
                    <p><?= Yii::t('app', 'view.consulta.parametro_texto') ?></p>
                </div>
                <div class="consulta__paramentroscontent">
                    <div class="block--parametro">
                        <div class="d-flex align-item-center justify-content-end py-1">
                            <h4 class="mr-auto align-self-center text-uppercase"><?= Yii::t('app', 'view.consulta.valor') ?></h4>
                            <i class="bp-number align-self-center"></i>
                            <i class="bp-formula align-self-center"></i>
                        </div>
                        <div class="d-flex block--parametro_list block--parametro_val block--parametro_list_empty justify-content-center align-item-center">
                            <ul id="valor" class="attr-list w-100 px-2 pt-3">

                                <?= ConsultaMagic::getFields($valores, FALSE, ($index_id + count($inultilizados)), (sizeof($series) > 0)); ?>
                                
                            </ul>
                        </div>
                    </div>
                    <div class="block--parametro mt-2">
                        <div class="d-flex align-item-center justify-content-start py-1">
                            <h4 class="mr-auto align-self-center text-uppercase"><?= Yii::t('app', 'view.consulta.argumentos') ?></h4>
                            <i class="bp-time align-self-center pr-2"></i>
                            <i class="bp-string align-self-center pr-2"></i>
                            <i class="bp-number align-self-center"></i>
                        </div>
                        <div class="d-flex block--parametro_list block--parametro_arg block--parametro_list_empty justify-content-center align-item-center">
                            <ul id="argumento" class="attr-list w-100 px-2 pt-3">
                                
                                <?= ConsultaMagic::getFields($argumentos, FALSE, ($index_id + count($inultilizados) + count($valores)), (sizeof($series) > 0)); ?>
                                
                            </ul>
                        </div>
                    </div>
                    <div class="block--parametro mt-2">
                        <div class="d-flex align-item-center justify-content-end py-1">
                            <h4 class="mr-auto align-self-center text-uppercase"><?= Yii::t('app', 'view.consulta.serie') ?></h4>
                            <i class="bp-time align-self-center pr-2"></i>
                            <i class="bp-string align-self-center pr-2"></i>
                            <i class="bp-number align-self-center"></i>
                        </div>
                        <div class="d-flex block--parametro_list block--parametro_ser block--parametro_list_empty justify-content-center align-item-center">
                            <ul id="serie" class="attr-list w-100 px-2 pt-3">
                                
                                <?= ConsultaMagic::getFields($series, FALSE, ($index_id + count($inultilizados) + count($valores) + count($argumentos)), (sizeof($series) > 0)); ?>
                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 consulta consulta__preview">
                
                <?= $this->render("/consulta-update/preview/_data", ['index' => $index, 'data' => $preview_data, 'model' => $model, 'sqlMode' => $sqlMode]); ?>

            </div>

        </div>

    </div>

</div>