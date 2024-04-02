<?php

use kartik\form\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = "Edição de painel:: " . $model->nome;

$default_data = '[{"x":0,"y":0,"width":3,"height":3,"consulta":""}]';

$data = ($model->data) ? json_encode($model->data) : $default_data;
$consultas = ($data_consulta) ? json_encode($data_consulta) : json_encode([]);

$js = <<<JS
        
    var options = 
    {
        animate: true,
        verticalMargin: '10px',
        float: true,
        acceptWidgets: '.grid-stack-item'
    };

    var _grid = $('.grid-stack');
    var _data_consulta = {$consultas};
            
    $(function () 
    {
        _grid.gridstack(options);
        
        new function () 
        {
            this.serializedData = {$data};

            this.grid = $('.grid-stack').data('gridstack');
            
            this.loadGrid = function ()
            {
                this.grid.removeAll();
                
                var items = GridStackUI.Utils.sort(this.serializedData);
                
                _.each(items, function (node) 
                {
                    _color = '#00bcd4';
                        
                    if(node.conected === undefined || node.conected == 0)
                    {
                        _color = '#808080';
                        node.conected = 0;
                    }
                    
                    if(node.consulta) 
                    {
                        if(node.type == 'img')
                        {
                            var _iframe = '<div class="card-header d-flex align-item-center justify-content-end">' +
                                 '<div class="text-right m-1"><i class="fa fa-times btn-remove-item" style="cursor: pointer; color: #808080"></i>' +
                                 '</div></div><div class="card-block d-flex justify-content-center align-items-center" style="height: 100%;">' +
                                 '<img class="w-100 h-100" src="/' + node.consulta + '"></div>';
                                
                            this.grid.addWidget($('<div data-gs-min-width="3" data-gs-min-height="3" data-type="img" data-conected="0" data-consulta="' + node.consulta + '">' +
                             '<div class="grid-stack-item-content" /><div class="grid-stack-item-content card card-consulta" style="left: 0;">' +
                                _iframe +
                            '</div><div/>'),
                            node.x, node.y, node.width, node.height);
                        }
                        else
                        {
                            if(_data_consulta[node.consulta] !== undefined)
                            {
                                var _name = _data_consulta[node.consulta].fullname;
                                var _chart = _data_consulta[node.consulta].chart;
                                
                                var _iframe = '<div class="card-header d-flex align-item-center justify-content-end"><h4 class="mr-auto align-self-center text-uppercase">'
                                    +  _name + '</h4><div class="text-right m-1"><i class="fa fa-link btn-conect-item mr-2" style="cursor: pointer; color: ' + _color + '"></i>' +
                                     '<i class="fa fa-times btn-remove-item" style="cursor: pointer; color: #808080"></i>' +
                                     '</div></div><div class="card-block d-flex justify-content-center align-items-center" style="height: 100%;">' +
                                     '<i class="bp-chart--' + _chart + '" style="font-size: 150px; color: #17748738;"></i></div>';
                            }
                            else
                            {
                                var _iframe = '<div class="card-header d-flex align-item-center justify-content-end"><h4 class="mr-auto align-self-center text-uppercase">404</h4><div class="text-right m-1">' +
                                     '<i class="fa fa-times btn-remove-item" style="cursor: pointer; color: #808080"></i>' +
                                     '</div></div><div class="card-block d-flex justify-content-center align-items-center" style="height: 100%;"></div>';
                            }
                            
                            this.grid.addWidget($('<div data-gs-min-width="3" data-gs-min-height="3" data-type="consulta" data-conected="' + node.conected + '" data-consulta="' + node.consulta + '">' +
                             '<div class="grid-stack-item-content" /><div class="grid-stack-item-content card card-consulta" style="left: 0;">' +
                                _iframe +
                            '</div><div/>'),
                            node.x, node.y, node.width, node.height);
                        }
                    }
                    else
                    {
                        this.grid.addWidget($('<div data-gs-min-width="3" data-gs-min-height="3" data-type="consulta" data-conected="' + node.conected + '" data-consulta="' + node.consulta + '"><div class="grid-stack-item-content" /><div class="grid-stack-item-content card card-consulta" style="left: 0;">' +
                            '<div class="text-right m-1">' +
                                '<i class="fa fa-link btn-conect-item mr-2" style="cursor: pointer; color: ' + _color + '"></i>' +
                                '<i class="fa fa-times btn-remove-item" style="cursor: pointer; color: #808080"></i>' +
                            '</div>' +
                            '<div class="card-block d-flex justify-content-center align-items-center" style="height: 100%;">' +
                                '<div class="dropdown">' +
                                    '<a class="btn item-self-center" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                        '<img class="img-responsive" src="/img/icons/consulta__new.svg">' +
                                    '</a>' +
                                    '<div class="dropdown-menu" style="margin-top: -90px; margin-left: 100px;">' +
                                        '<a class="dropdown-item bnt-painel-new-consulta" href="#">Nova Consulta</a>' +
                                        '<a class="dropdown-item bnt-painel-new-imagem" href="#">Nova Imagem</a>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div><div/>'),
                        node.x, node.y, node.width, node.height);
                    }
                            
                }, this);
        
                return false;
          
            }.bind(this);
        
            this.saveGrid = function () 
            {
                this.serializedData = _.map($('.grid-stack > .grid-stack-item:visible'), function (el) 
                {
                    el = $(el);
                    var node = el.data('_gridstack_node');
                    var _consulta = node.el.attr('data-consulta');
                    var _conected = node.el.attr('data-conected');
                    var _type = node.el.attr('data-type');
                
                    return {x: node.x, y: node.y, width: node.width, height: node.height, consulta: _consulta, conected: _conected, type: _type};
        
                }, this);
        
                $('#painelData').val(JSON.stringify(this.serializedData));
        
                return false;
        
            }.bind(this);
            
            this.addWidget = function () 
            {
                this.grid.addWidget($('<div data-gs-min-width="3" data-gs-min-height="3" data-consulta="" data-type="" data-conected="0"><div class="grid-stack-item-content" /><div class="grid-stack-item-content card card-consulta" style="left: 0;">' +
                    '<div class="text-right m-1">' +
                        '<i class="fa fa-link btn-conect-item mr-2" style="cursor: pointer; color: #808080"></i>' +
                        '<i class="fa fa-times btn-remove-item" style="cursor: pointer; color: #808080;"></i>' +
                    '</div>' +
                    '<div class="card-block d-flex justify-content-center align-items-center" style="height: 100%;">' +
                        '<div class="dropdown">' +
                            '<a class="btn item-self-center" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                '<img class="img-responsive" src="/img/icons/consulta__new.svg">' +
                            '</a>' +
                            '<div class="dropdown-menu" style="margin-top: -90px; margin-left: 100px;">' +
                                '<a class="dropdown-item bnt-painel-new-consulta" href="#">Nova Consulta</a>' +
                                '<a class="dropdown-item bnt-painel-new-imagem" href="#">Nova Imagem</a>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div><div/>'),
                1, 1, 3, 3, true);

                this.saveGrid();
            
                return false;
        
            }.bind(this);
            
            this.clearGrid = function () 
            {
                swal({
                    title: 'Exclusão dos ítens do painel',
                    text: "Deseja realmente excluir todos os ítens do painel?",
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#007EC3',
                    confirmButtonText: 'Excluir'
                }).then((result) => 
                {
                    if (result.value) 
                    {
                        this.grid.removeAll();
                    }
                })
                        
                this.saveGrid();
            
                return false;
            
            }.bind(this);
            
            $('.grid-stack').change(this.saveGrid);
            $('#clear-grid').click(this.clearGrid);
            $('#add-widget').click(this.addWidget);
            this.loadGrid();
        };
    });
      
    $(document).delegate('.bnt-painel-new-consulta', 'click', function()
    {
        let _widget = $(this).closest(".grid-stack-item");
            
        var _items = {$select};
        var _consultas = [];
        var _select = {};
        var _selected = JSON.parse($('#painelData').val().trim());
        
        $.each(_selected, function(i, item) 
        {
            var found = $.inArray(_selected[i].consulta, _consultas);
        
            if (found >= 0) 
            {
            
            } 
            else 
            {
                _consultas.push(_selected[i].consulta);
            }
        });
        
        $.each(_items, function(i, item) 
        {
            var found = $.inArray(i, _consultas);
            
            if (found >= 0) 
            {
                _select[i] = _items[i] + ' ***';
            } 
            else 
            {
                _select[i] = _items[i];
            }
        });
        
        console.log(_select);
        
        swal({
            title: 'Selecione a Consulta',
            input: 'select',
            inputOptions: _select,
            inputClass: 'form-control align-self-center',
            showCancelButton: true,
            confirmButtonText: 'Salvar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#007EC3',
            showLoaderOnConfirm: true,
            preConfirm: (value) => 
            {
                var _name = _data_consulta[value].fullname;
                var _chart = _data_consulta[value].chart;
                
                var _iframe = '<div class="card-header d-flex align-item-center justify-content-end"><h4 class="mr-auto align-self-center text-uppercase">'
                +  _name + '</h4><div class="text-right m-1">' +
                 '<i class="fa fa-link mr-2" style="cursor: pointer; color: #808080"></i>' +
                 '<i class="fa fa-times btn-remove-item" style="cursor: pointer; color: #808080"></i>' +
                 '</div></div><div class="card-block d-flex justify-content-center align-items-center" style="height: 100%;">' +
                 '<i class="bp-chart--' + _chart + '" style="font-size: 150px; color: #17748738;"></i></div>';
                _widget.find('.card-block').parent().html(_iframe);
                _widget.attr('data-consulta', value);
                $('.grid-stack').change();
            },
            allowOutsideClick: () => !swal.isLoading()
        }).then((result) => 
        {
            if(result.dismiss == 'cancel' || result.dismiss == 'overlay')
            {
                
            }
            else
            {
                swal({
                    type: 'success',
                    confirmButtonColor: '#007EC3',
                    title: 'Sucesso',
                    text: 'Consulta adicionada com sucesso.'
                });
            }
        });
        
        var select = $('select.swal2-select');
        console.log(select);
        select.html(select.find('option').sort(function(x, y) {
            return $(x).text() > $(y).text() ? 1 : -1;
        }));
    });
    
    $("#modal-img").iziModal({
        transitionIn: '',
        transitionOut: '',
        transitionInOverlay: '',
        transitionOutOverlay: ''
    });
    
    var _currentWidget;

    $(document).delegate('.bnt-painel-new-imagem', 'click', function()
    {
        event.preventDefault();
        event.stopPropagation();
        
        _currentWidget = $(this).closest(".grid-stack-item");
        $('#modal-img').iziModal('open');
    });
    
    function updateImageData(src)
    {
        var _square = _currentWidget;
        _square.find('.card-block').parent().html("<img class='w-100 h-100' src='/" + src + "'>");
        _square.attr('data-type', 'img');
        _square.attr('data-consulta', src);
        $('.grid-stack').change();
        $('#modal-img').iziModal('close');
    }
        
    $(document).delegate('.btn-remove-item', 'click', function()
    {
        let _widget = $(this).closest(".grid-stack-item");

        swal({
            title: 'Exclusão de ítem do painel',
            text: "Deseja realmente excluir este ítem do painel?",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#007EC3',
            confirmButtonText: 'Excluir'
        }).then((result) => 
        {
            if (result.value) 
            {
                _grid.data('gridstack').removeWidget(_widget);
            
                $('.grid-stack').change();
            }
        })
    });
    
    $(document).delegate('.btn-conect-item', 'click', function()
    {
        let _widget = $(this).closest(".grid-stack-item");
        var _el = $(this).parent().parent().parent().parent();
        var _conected = _el.attr('data-conected');
        
        if(_conected == 0)
        {
            _widget.attr('data-conected', '1');
            $(this).css('color', '#00bcd4');
        }
        else
        {
            _widget.attr('data-conected', '0');
            $(this).css('color', '#808080');   
        }
        
        $('.grid-stack').change();
    });
        
    $(document).delegate('.btn-salvar-painel', 'click', function()
    {
        var _error = false;
        
        if($('#painelName').val().trim() == '')
        {
            _error = true;
        
            iziToast.error({
                title: 'Erro ao salvar o painel!',
                message: 'Não é possível salvar um painel com nome vazio. Verifique!',
                position: 'topCenter',
                close: true,
                transitionIn: 'flipInX',
                transitionOut: 'flipOutX',
            });
        }
        
        if(!_error)
        {
            var _data = {'nome': $('#painelName').val().trim(), 'data': $('#painelData').val().trim()};

            $.post({
                url: '/painel/alterar?id={$model->id}',
                type: 'POST',
                data: _data,
                dataType: 'json',
                success: function(msg) 
                {
                    iziToast.success({
                        title: 'Painel salvo com sucesso!',
                        position: 'topCenter',
                        close: true,
                        transitionIn: 'flipInX',
                        transitionOut: 'flipOutX',
                    });
                }
            });
        }
    });
                
    $(".open-config").click(function(e) 
    {
        e.preventDefault();

        $(".sidebar--painel .tab-content #filtro").html('');
        var _id = $(this).data('id');
    
        jQuery.ajax({
            url: '/ajax/open-filter-painel?id=' + _id,
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
    });
    
    $(".close-painel").click(function(e) 
    {
        e.preventDefault();
        $(".sidebar--painel").toggleClass("block__slide");
    });
JS;

$this->registerJs($js);

$css = <<<CSS
        
    .grid-stack-item-content {
        height: auto;
        margin: 1em;
        background-color: #fff;
    }
        
    .grid-stack-item-removing {
        opacity: 0.5;
    }
        
    .btn-data-painel {
        cursor: pointer;
        background-color: #FFF;
        color: #177487;
        padding: 8px;
        border: 1px solid #177487;
        margin-top: -8px;
    }
        
    .btn-data-painel:hover {
        background-color: #26808f;
        color: #FFF;
    }
        
CSS;

$this->registerCss($css);
?>

<?= $this->render('//layouts/_partials/_left', ['contracted' => TRUE]); ?>

<?= $this->render('_layouts/_filter', compact('model')); ?>

<div class="page-content inset h-100 mh-100">

    <div id="modal-img" style="display: none;">

        <div class="iziModal__header d-flex justify-content-start pl-3 pr-3 ">

            <h5 class="modal-title mr-auto align-self-center text-uppercase">Nova Imagem</h5>

            <button type="button" class="btn btn-sm btn-link--inverse align-self-center text-uppercase cursor-pointer" data-izimodal-close="">X</button>

        </div>

        <div class="iziModal__body justify-content-center align-items-center p-3 text-center">

            <?php

            $formLogo = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data']
            ]);

            echo FileInput::widget([
                'name' => 'logo',
                'id' => 'input-img',
                'language' => 'pt-BR',
                'pluginOptions' =>
                    [
                        'showCaption' => false,
                        'showRemove' => true,
                        'showUpload' => true,
                        'language' => 'pt-BR',
                        'browseIcon' => '<i class="fa fa-search"></i>',
                        'removeIcon' => '<i class="fa fa-trash"></i> ',
                        'uploadLabel' => 'Salvar',
                        'maxFileSize' => 1024,
                        'maxFileCount' => 1,
                        'fileActionSettings' =>
                            [
                                'showUpload' => TRUE,
                                'showDownload' => FALSE,
                                'showRemove' => FALSE,
                                'showZoom' => FALSE,
                                'showDrag' => FALSE
                            ],
                        'previewFileType' => 'image',
                        'uploadUrl' => Url::to(['/painel/upload-image'])
                    ],
                'pluginEvents' => [
                    'fileuploaded' => 'function(event, data, previewId, index){
                        $("#input-img").fileinput("clear");
                        updateImageData(data.response);    
                    }',
                ],
                'options' => ['accept' => 'image/*']
            ]);

            ActiveForm::end();

            ?>

        </div>

    </div>

    <nav class="nav pageContent--nav align-item-center justify-content-start">
        <form class="form-inline painel__title--edit d-flex align-items-center justify-content-end col-12">
            <label class="sr-only" for="painelName">Nome do Painel</label>
            <input type="text" value="<?= $model->nome ?>" class="form-control mr-auto w-75" id="painelName" placeholder="Nome do Painel">
            <input type="hidden" id="painelData" name="painelData">
            <?= Html::a('Visualizar', ['visualizar', 'id' => $model->id], ['class' => 'btn btn-sm btn-link--inverse text-uppercase trigger-warning']); ?>
            <button type="button" class="btn btn-outline-light btn-salvar-painel text-uppercase">Salvar</button>
            
            <a class="nav-link" title="Configurações do Painel" href="#">
                <i class="bp-config-gear open-config" data-id="<?= $model->id ?>"></i>
            </a>
        </form>
    </nav>

    <div class="container-fluid justify-content-between" id="content--container">

        <div class="row m-3">
            
            <div class="col-md-12">
                
                <?= Html::button('ADICIONAR', ['id' => 'add-widget', 'class' => 'btn btn-sm btn-data-painel text-uppercase trigger-warning']); ?>
                <?= Html::button('EXCLUIR TUDO', ['id' => 'clear-grid', 'class' => 'btn btn-sm btn-data-painel text-uppercase trigger-warning']); ?>

            </div>

        </div>
        
        <div class="row m-3">
            
            <div class="col-md-12">
                                
                <div class="grid-stack"></div>

            </div>

        </div>

    </div>

</div>