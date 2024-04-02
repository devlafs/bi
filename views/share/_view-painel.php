<?php

$this->title = 'Painel::: ' . $model->nome;


if($model->privado && !$p):

    $current_url = Yii::$app->request->url;

    $js = <<<JS
    
    swal({
        title: 'Informe a chave de acesso:',
        input: 'text',
        inputAttributes:
        {
          autocapitalize: 'off'
        },
        showCancelButton: false,
        confirmButtonText: 'Visualizar',
        confirmButtonColor: '#007EC3',
        showLoaderOnConfirm: true,
        closeOnClickOutside: false,
        allowOutsideClick: false,
        preConfirm: (pass) => 
        {
            window.location =  '{$current_url}&p=' + pass;
        },
    });

JS;

    $this->registerJs($js);

else:

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
                                    setTimeout(function() { console.log(_node); $('#div-loading-' + _node).removeClass("loading");}, 300);
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

endif;

?>

<div id="page-content-wrapper " style="padding-left:0px;">

    <div class="page-content inset h-100 mh-100">

        <?= ($header) ? $this->render('_layouts/_top', compact('model')) : null; ?>

        <div class="container-fluid h-100 mh-100 justify-content-between" id="content--container">

            <div class="col-md-12 mt-3" style="height: calc(100vh - 90px);">

                <?php if(!$model->privado || $p) : ?>

                    <?php if($model->condicao) : ?>

                        <div id="div-filter" class="col-md-12 pl-0 pr-0">

                            <div class="card card--chart card--consuta__full mt-0 mb-2" style="height: 100%; margin-right: 10px;">

                                <div class="card-block row pt-1 pb-1">

                                    <?= $this->render('/ajax/_painel/_layouts/_filter', compact('model')) ?>

                                </div>

                            </div>

                        </div>

                    <?php endif; ?>

                    <div class="grid-stack" style="background-color: #FFF;"></div>

                <?php endif; ?>
                
            </div>

        </div>

    </div>

</div>