<?php

$this->title = $model->nome;

$can_generate_url = Yii::$app->permissaoPainel->can($model->id, 'ajax', 'generate-url-publica');

$can_send_email = Yii::$app->permissaoPainel->can($model->id, 'ajax', 'send-url-publica');

$default_data = '[{"x":0,"y":0,"width":3,"height":3,"consulta":""}]';
        
$data = ($model->data) ? json_encode($model->data) : $default_data;

$filter = "";

if($model->data)
{
    foreach($model->data as $val)
    {
        if(isset($val['type']) && $val['type'] != 'img')
        {
            $filter .= "window.filtrarP" . $val['consulta'] . "();";
        }
    }
}

$js = <<<JS
        
    $(function () 
    {
        var options = 
        {
            animate: true,
            verticalMargin: '10px',
            float: true,
            acceptWidgets: '.grid-stack-item',
            staticGrid: true,
            height: 'calc(100vh - 90px)'
        };
        
        $('.grid-stack').gridstack(options);
        
        new function () 
        {
            this.serializedData = {$data};

            this.grid = $('.grid-stack').data('gridstack');

            this.loadGrid = function ()
            {
                this.grid.removeAll();
                
                var items = GridStackUI.Utils.sort(this.serializedData);
                var _node = 1;

                _.each(items, function (node) {
                    
                    if(node.type == 'img')
                    {
                        this.grid.addWidget($('<div data-gs-min-width="3" data-gs-min-height="3" data-consulta="' + node.consulta + '">' +
                         '<div class="grid-stack-item-content" />' +
                            '<div class="grid-stack-item-content" style="left: 0;">' +
                                '<div class="card-block d-flex justify-content-center align-items-center" style="height: 100%;">' +
                                   '<img class="w-100 h-100" src="/' + node.consulta + '"></div>' +
                                '</div>' +
                            '</div><div/>'),
                            node.x, node.y, node.width, node.height);
                    }
                    else
                    {
                        var _loading = '<div id="div-loading-' + _node + '" class="div-loading-p">' +
                            '<div class="bb-r-spinner" style="z-index: 100; margin:auto; left:0; right:0; top:0; bottom:0; position:absolute;">' +
                                '<div class="bb-r-spinner-circle-transparent"></div>' +
                                '<div class="bb-r-spinner-circle"></div>' +
                            '</div>' +
                        '</div>';
                                        
                        this.grid.addWidget($('<div data-gs-min-width="3" data-gs-min-height="3" data-consulta="' + node.consulta + '">' +
                         '<div class="grid-stack-item-content" />' +
                            '<div class="grid-stack-item-content" style="left: 0;">' +
                                '<div class="card-block d-flex justify-content-center align-items-center" style="height: 100%;">' +
                                    _loading +
                                    '<div class="card card-consulta card-consulta' + _node + ' card--chart card--chart-' + _node + ' card--consuta__full mt-0" data-node="' + _node + '" data-consulta="' + node.consulta + '" style="height: 100%;width: 100%;">' +
                                '</div>' +
                            '</div><div/>'),
                            node.x, node.y, node.width, node.height);
                    }
                    
                    _node++;
                }, this);
        
                return false;
          
            }.bind(this);
        
            this.loadGrid();
            
            $( document ).ready(function() {
                var _canstart = 1;
                $(".card.card-consulta.card--chart").each(function(index) {
                    while (_canstart == 1)
                    {
                        _canstart = 0;

                        if(_canstart == 0)
                        {
                            var _this = $(this);
                            var _idconsulta = _this.attr('data-consulta');
                            var _node = _this.attr('data-node');
                            var _csrfToken = $('meta[name="csrf-token"]').attr("content");
                            
                             $.ajax({
                                url: '/content/view?id_painel={$model->id}&id_consulta=' + _idconsulta + '&square=' + _node,
                                type: 'POST',
                                data: 
                                {
                                    index: 0,
                                    token: null,
                                    _csrf: _csrfToken
                                },
                                success: function(data) 
                                {
                                    _this.html(data);
                                },
                                beforeSend: function ()
                                {
                                    $('#div-loading-' + _node).addClass("loading");
                                },
                                complete: function () 
                                {
                                    setTimeout(function() { $('#div-loading-' + _node).removeClass("loading");}, 300);
                                }
                            });   
                            
                             _canstart = 1;
                            break;
                        }
                    }
                });
            });
        };
    });

    $(document).delegate('#filter-painel', 'click', function(e){
        e.stopPropagation();
        {$filter}
    });
     
JS;

$this->registerJs($js);

if($can_generate_url) :

    if($model->privado)
    {
        $swal_success = <<<JS

        swal({
            type: 'success',
            confirmButtonColor: '#007EC3',
            title: 'Url gerada com sucesso!',
            html:
            '<hr><br><p style="font-weight: bold; font-size: 16px;">Url privada:</p><p><b>' + _data.url + '</b></p><br><p style="font-weight: bold; font-size: 16px;">Chave de acesso:</p><p><b>' + _data.pass + '</b></p>',
        });

JS;
    }
    else
    {
        $swal_success = <<<JS

        swal({
            type: 'success',
            confirmButtonColor: '#007EC3',
            title: 'Url gerada com sucesso!',
            html:
            '<hr><br><p style="font-weight: bold; font-size: 16px;">Url pública:</p><p><b>' + _data.url + '</b></p>',
        });

JS;
    }

$js_generate_url = <<<JS
        
    $(document).delegate("#generate-url", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();
        
        var _id = $(this).data('id');
        
        jQuery.ajax({
            url: '/ajax/generate-url-publica?id_consulta=null&id_painel=' + _id + '&view=2',
            type: 'json',
            success: function (_data) 
            {
                if(_data.success)
                {
                    {$swal_success}
                }
                else
                {
                    iziToast.error({
                        title: 'Erro ao gerar a URL!',
                        message: 'Favor entrar em contato com o administrador do sistema!',
                        position: 'topCenter',
                        close: true,
                        transitionIn: 'flipInX',
                        transitionOut: 'flipOutX',
                    });
                }
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

    $(document).delegate("#generate-wpp", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();
        
        var _id = $(this).data('id');
        
        jQuery.ajax({
            url: '/ajax/generate-url-publica?id_consulta=null&id_painel=' + _id + '&view=2',
            type: 'json',
            success: function (_data) 
            {
                if(_data.success)
                {
                    swal({
                        type: 'question',
                        confirmButtonColor: '#007EC3',
                        title: 'Compartilhar o painel via whatsapp:',
                        showConfirmButton: false,
                        html:
                        '<a class="btn btn-success" style="color: #FFF !important" target="_blank" href="https://api.whatsapp.com/send?text=' + encodeURIComponent(_data.url) + '">Compartilhar</a>',
                    });
                }
                else
                {
                    iziToast.error({
                        title: 'Erro ao gerar ao compartilhar painel!',
                        message: 'Favor entrar em contato com o administrador do sistema!',
                        position: 'topCenter',
                        close: true,
                        transitionIn: 'flipInX',
                        transitionOut: 'flipOutX',
                    });
                }
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

$this->registerJs($js_generate_url);

endif;

if($can_send_email) :
    
$js_send_url = <<<'JS'

    $(document).delegate("#send-url", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();
        
        var _id = $(this).data('id');
        
        swal({
            title: 'Informe o email para o compartilhamento',
            input: 'text',
            inputAttributes:
            {
              autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Enviar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#007EC3',
            showLoaderOnConfirm: true,
            preConfirm: (email) => 
            {
                $.ajax({
                    url: '/ajax/send-url-publica?id_consulta=null&id_painel=' + _id + '&view=2',
                    type: 'POST',
                    data: {'email': email},
                    success: function(data)
                    {
                        if(data.success)
                        {
                            swal({
                                type: 'success',
                                confirmButtonColor: '#007EC3',
                                title: 'Sucesso',
                                text: 'Painel compartilhado com sucesso.'
                            });
                        }
                    },
                    error: function(data)
                    {
                        swal("Erro ao enviar", data.responseJSON.message, "error");
                    }
                });
            },
            allowOutsideClick: () => !swal.isLoading()
        });
    });
        
JS;

$this->registerJs($js_send_url);

endif;

$css = <<<CSS
        
    .grid-stack-item-content {
        height: auto;
        margin: 1em;
        background-color: #fff;
    }
            
    *:fullscreen
    *:-ms-fullscreen,
    *:-webkit-full-screen,
    *:-moz-full-screen 
    {
       overflow: auto !important;
    }
    
    .div-loading-p.loading 
    .div-loading-p.loading 
    {
        overflow: hidden;  
        display: block; 
    }
    .div-loading-p:not(.loading)
    {
        display: none;
    }

    ::-webkit-scrollbar {
      width: 5px;
      height: 5px;
    }
    
    /*!* Track *!*/
    ::-webkit-scrollbar-track {
      border-radius: 10px;
    }
     
    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: #bec3cf; 
      border-radius: 10px;
    }
CSS;

$this->registerCss($css);

if($model->javascript):
    
    $this->registerJs($model->javascript);
    
endif;

echo $this->render('//layouts/_partials/_left'); 

?>

<div id="page-content-wrapper " class="h-100 mh-100 ">

    <div class="page-content inset h-100 mh-100">

        <?= $this->render('_layouts/_top', compact('model', 'can_generate_url', 'can_send_email')); ?>

        <div class="container-fluid h-100 mh-100 justify-content-between" id="content--container" style="background-color: #cecece33;">

            <div style="height: calc(100vh - 90px);">

                <?php if($model->condicao) : ?>

                    <div id="div-filter" class="col-md-12 pl-0 pr-0">

                        <div class="card card--chart card--consuta__full mt-0 mb-2" style="height: 100%; margin-right: 10px;">

                            <div class="card-block row pt-1 pb-1">

                                <?= $this->render('/ajax/_painel/_layouts/_filter', compact('model')) ?>

                            </div>

                        </div>

                    </div>

                <?php endif; ?>
                
                <div id="divfullscreen" class="grid-stack" style="background-color: rgb(242, 243, 245);"></div>
                
            </div>

        </div>

    </div>

</div>