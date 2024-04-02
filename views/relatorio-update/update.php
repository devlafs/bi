<?php

use yii\helpers\Html;
use app\magic\RelatorioMagic;
use app\magic\PreviewRelatorioMagic;
use yii\helpers\Url;

$this->title = \Yii::t('app', 'view.alterar') . ' ' . strtolower(\Yii::t('app', 'geral.relatorio')) . ': ' . $model->nome;

$index = 0;
$post = [];
$valores = $model->find()->getValueFields($model->id);
$argumentos = $model->find()->getArgFields($model->id);
$inultilizados = $model->find()->getUnusedFields($model->id_relatorio, $model->id);

$post['index'] = $index;

foreach($valores as $i => $valor)
{
    $post['valor'][$i] = $valor->campo->id;
}

foreach($argumentos as $i => $argumento)
{
    $post['argumento'][$i]['id'] = $argumento->campo->id;
}

$preview_data = PreviewRelatorioMagic::getData($model, $post, $sqlMode);
        
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
.choose-type .badge
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
        
.btn-visualizar-relatorio, .btn-salvar-relatorio
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
$t_erro_relatorio = \Yii::t('app', 'view.relatorio.erro_salvar_relatorio');
$t_mensagem_relatorio_vazia = \Yii::t('app', 'view.relatorio.msg_erro_salvar_relatorio_vazia');
$t_mensagem_relatorio_parametro = \Yii::t('app', 'view.relatorio.msg_erro_salvar_relatorio_parametro');
$t_relatorio_salva = \Yii::t('app', 'view.relatorio.msg_relatorio_salva_sucesso');
$t_ativo = \Yii::t('app', 'view.geral.ativo');

$js = <<<JS

    $(document).ready(function() 
    {
        datamodified = 0;
    
        $(document).delegate('.btn-visualizar-relatorio', 'click', function()
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
    });
        
    $(document).delegate('.btn-salvar-relatorio', 'click', function()
    {
        var _valor = [];
        
        $('ul#valor').children().each(function() 
        {
            _valor.push($(this).data('id'));
        });

        var _argumento = [];

        $('ul#argumento').children().each(function() 
        {
            _argumento.push({'id': $(this).data('id'), 'type': $(this).data('type')});
        });
        
        var _error = false;
        
        if($('#painelName').val().trim() == '')
        {
            _error = true;
        
            iziToast.error({
                title: '{$t_erro_relatorio}',
                message: '{$t_mensagem_relatorio_vazia}',
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
                title: '{$t_erro_relatorio}',
                message: '{$t_mensagem_relatorio_vazia}',
                message: '{$t_mensagem_relatorio_parametro}',
                position: 'topCenter',
                close: true,
                transitionIn: 'flipInX',
                transitionOut: 'flipOutX',
            });
        }
        
        if(!_error)
        {
            var _data = {'nome': $('#painelName').val().trim(), 'valor': _valor, 'argumento': _argumento};

            $.post({
                url: '/relatorio-data/alterar?id={$model->id}',
                type: 'POST',
                data: _data,
                dataType: 'json',
                success: function(msg) 
                {
                    iziToast.success({
                        title: '{$t_relatorio_salva}',
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
            
    $(document).ready(function()
    {
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
                _argumento.push({'id': $(this).data('id'), 'type': $(this).data('type')});
            });

            var _data = {'index': 0, 'valor': _valor, 'argumento': _argumento};

            return _data;
        }
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
    
    $(document).ready(function() 
{
    $(".config-update-relatorio.open-config").click(function(e) 
    {
        e.preventDefault();
        
        if($(".sidebar--painel").hasClass("block__slide"))
        {
            $(".sidebar--painel .tab-content #filtro").html('');
            var _id = $(this).data('id');

            jQuery.ajax({
                url: '/relatorio-data/open-filter-update?id=' + _id,
                success: function (_data) 
                {
                    $(".sidebar--painel .tab-content #filtro").html(_data);

                    $(".sidebar--painel").toggleClass("block__slide");
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
        else
        {
            $(".sidebar--painel").toggleClass("block__slide");
        }
    });
});
            
JS;

$this->registerJs($js);

$index_id = 0;

?>

<?= $this->render('_layouts/_filter', compact('model')); ?>

<?= $this->render('//layouts/_partials/_left', ['contracted' => TRUE]); ?>

<div class="page-content inset h-100 mh-100">
    
    <nav class="nav pageContent--nav align-item-center justify-content-start">
        <form class="form-inline painel__title--edit d-flex align-items-center justify-content-end col-12">
            <label class="sr-only" for="painelName"><?= Yii::t('app', 'view.relatorio.nome_relatorio') ?></label>
            <input type="text" value="<?= $model->nome ?>" class="form-control mr-auto w-75" id="painelName" placeholder="<?= Yii::t('app', 'view.relatorio.nome_relatorio') ?>">
            <button type="button" class="btn btn-sm btn-link--inverse btn-visualizar-relatorio text-uppercase trigger-warning"><?= Yii::t('app', 'view.visualizar') ?></button>
            <button type="button" class="btn btn-outline-light btn-salvar-relatorio text-uppercase"><?= Yii::t('app', 'view.salvar') ?></button>

            <a class="nav-link open-config" title="<?= Yii::t('app', 'view.relatorio.conf_relatorio') ?>" href="#">

                <i class="ml-auto config-update-relatorio bp-config-gear open-config" data-id="<?= $model->id ?>"></i>

            </a>
        </form>
    </nav>

    <div class="container-fluid justify-content-between" id="content--container">

        <div class="row">

            <div class="col-xl-3 consulta consulta__fonte px-0">

                <div class="consulta__headerconfig">

                    <div class="d-flex align-item-center justify-content-end">

                        <h3 class="mr-auto align-self-center"><?= Yii::t('app', 'view.relatorio.fonte_dados') ?> </h3>

                    </div>

                    <p class="text-uppercase" style="font-size: 14px;"><?= Yii::t('app', 'geral.relatorio') ?>:::
                        <?= Html::a(mb_strtoupper($model->relatorio->nome), ['/relatorio/index']); ?>
                    </p>
                </div>

                <div class="consulta__cubocontent">

                    <div class="typeahead__container" id="typeahead__container-filtro-argumento">

                        <div class="typeahead__field">

                            <span class="typeahead__query">

                                <input class="js-typeahead-sidebar" id="filtro-argumento" style="text-transform: uppercase;" placeholder="<?= Yii::t('app', 'view.relatorio.pesquise_campo') ?>">

                            </span>

                            <span class="typeahead__button"><i class="bp-search"></i></span>

                        </div>

                    </div>

                    <ul id="fonte-dados" class="attr-list">

                        <?= RelatorioMagic::getFields($inultilizados, TRUE); ?>

                    </ul>
                </div>

            </div>

            <div class="col-xl-3 consulta consulta__paramentros">

                <div class="consulta__headerconfig px-0 pb-0">
                    <div class="d-flex align-item-center justify-content-end">
                        <h3 class="mr-auto align-self-center"><?= Yii::t('app', 'view.relatorio.parametros') ?></h3>
                    </div>
                    <p><?= Yii::t('app', 'view.relatorio.parametro_texto') ?></p>
                </div>
                <div class="consulta__paramentroscontent">
                    <div class="block--parametro">
                        <div class="d-flex align-item-center justify-content-end py-1">
                            <h4 class="mr-auto align-self-center text-uppercase"><?= Yii::t('app', 'view.relatorio.valor') ?></h4>
                            <i class="bp-number align-self-center"></i>
                        </div>
                        <div class="d-flex block--parametro_list block--parametro_val block--parametro_list_empty justify-content-center align-item-center">
                            <ul id="valor" class="attr-list w-100 px-2 pt-3">

                                <?= RelatorioMagic::getFields($valores, FALSE, ($index_id + count($inultilizados))); ?>
                                
                            </ul>
                        </div>
                    </div>
                    <div class="block--parametro mt-2">
                        <div class="d-flex align-item-center justify-content-start py-1">
                            <h4 class="mr-auto align-self-center text-uppercase"><?= Yii::t('app', 'view.relatorio.argumentos') ?></h4>
                            <i class="bp-time align-self-center pr-2"></i>
                            <i class="bp-string align-self-center pr-2"></i>
                            <i class="bp-number align-self-center"></i>
                        </div>
                        <div class="d-flex block--parametro_list block--parametro_arg block--parametro_list_empty justify-content-center align-item-center">
                            <ul id="argumento" class="attr-list w-100 px-2 pt-3">

                                <?= RelatorioMagic::getFields($argumentos, FALSE, ($index_id + count($inultilizados) + count($valores))); ?>
                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 consulta consulta__preview">

                <?= $this->render("/relatorio-update/preview/_data", ['index' => $index, 'data' => $preview_data, 'model' => $model, 'sqlMode' => $sqlMode]); ?>

            </div>

        </div>

    </div>

</div>