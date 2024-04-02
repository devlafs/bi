<?php

use app\magic\MenuMagic;
use app\magic\MobileMagic;

$menu_consulta = json_encode(MenuMagic::getMenuConsulta());
$menu_painel = json_encode(MenuMagic::getMenuPainel());
$menu_relatorio = json_encode(MenuMagic::getMenuRelatorio());

$permissao = Yii::$app->permissaoGeral;

$permissao_cadastrar_consulta = $permissao->can('ajax', 'consulta');
$permissao_alterar_consulta = $permissao->can('consulta', 'alterar');
$permissao_duplicar_consulta = $permissao->can('ajax', 'duplicate-consulta');
$permissao_excluir_consulta = $permissao->can('ajax', 'delete-consulta');

$permissao_cadastrar_painel = $permissao->can('ajax', 'painel');
$permissao_alterar_painel = $permissao->can('painel', 'alterar');
$permissao_duplicar_painel = $permissao->can('ajax', 'duplicate-painel');
$permissao_excluir_painel = $permissao->can('ajax', 'delete-painel');

$permissao_cadastrar_relatorio = $permissao->can('ajax', 'relatorio');
$permissao_alterar_relatorio = $permissao->can('relatorio', 'alterar');
$permissao_duplicar_relatorio = $permissao->can('ajax', 'duplicate-relatorio');
$permissao_excluir_relatorio = $permissao->can('ajax', 'delete-relatorio');

$permissao_cadastrar_pasta = $permissao->can('ajax', 'pasta');
$permissao_mover_pasta = $permissao->can('ajax', 'move-menu');
$permissao_excluir_pasta = $permissao->can('ajax', 'delete-folder');

$permissao_visualizar_painel = $permissao->can('painel', 'visualizar');
$permissao_visualizar_consulta = $permissao->can('consulta', 'visualizar');
$permissao_visualizar_relatorio = $permissao->can('relatorio-data', 'visualizar');

$controller_id = Yii::$app->controller->id;
$parameter_id = isset(Yii::$app->request->queryParams['id']) ? Yii::$app->request->queryParams['id'] : 0;

?>

<?php if($permissao_visualizar_consulta && ($permissao_cadastrar_consulta || $permissao_cadastrar_pasta || $permissao_excluir_pasta)) : ?>

    <div id="dropdown-menu-folder-consulta" data-id="" data-title="" class="dropdown-menu">
        
        <?php if($permissao_cadastrar_consulta) : ?>
        
            <h6 class="dropdown-header"><i class="bp-consulta"></i> Consulta</h6>
            
            <a class="dropdown-item open-consulta">Inserir</a>

        <?php endif; ?>
            
        <?php if($permissao_cadastrar_consulta && ($permissao_cadastrar_pasta || $permissao_excluir_pasta)) : ?>

            <div class="dropdown-divider"></div>
        
        <?php endif; ?>

        <?php if($permissao_cadastrar_pasta || $permissao_excluir_pasta) : ?>
        
            <h6 class="dropdown-header"><i class="indicator bp-Folder"></i> Pasta</h6>
            
            <?php if($permissao_cadastrar_pasta) : ?>
            
                <a class="dropdown-item open-folder" data-tipo="CONSULTA">Inserir</a>
                
                <a class="dropdown-item update-folder" data-tipo="CONSULTA">Alterar / Renomear</a>
            
            <?php endif; ?>

            <?php if($permissao_excluir_pasta) : ?>

                <a class="dropdown-item delete-folder">Excluir</a>
            
            <?php endif; ?>

            
        <?php endif; ?>
            
    </div>

<?php endif; ?>

<?php if($permissao_visualizar_consulta && ($permissao_duplicar_consulta || $permissao_alterar_consulta || $permissao_excluir_consulta)) : ?>

    <div id="dropdown-menu-consulta" data-id="" data-title="" class="dropdown-menu">
        
        <h6 class="dropdown-header"><i class="bp-consulta"></i> Consulta</h6>
        
        <?php if($permissao_duplicar_consulta) : ?>
        
            <a class="dropdown-item duplicate-consulta">Duplicar</a>
        
        <?php endif; ?>
        
        <?php if($permissao_alterar_consulta) : ?>
        
            <a class="dropdown-item rename-consulta">Renomear</a>
            
            <a class="dropdown-item update-consulta">Alterar</a>
        
        <?php endif; ?>

        <?php if($permissao_excluir_consulta) : ?>
        
            <a class="dropdown-item delete-consulta">Excluir</a>
        
        <?php endif; ?>
        
    </div>

<?php endif; ?>

<?php if($permissao_visualizar_painel && ($permissao_cadastrar_painel || $permissao_cadastrar_pasta || $permissao_excluir_pasta)) : ?>

    <div id="dropdown-menu-folder-painel" data-id="" data-title="" class="dropdown-menu">
        
        <?php if($permissao_cadastrar_painel) : ?>
        
            <h6 class="dropdown-header"><i class="bp-painel"></i> Painel</h6>
            
            <a class="dropdown-item open-painel">Inserir</a>

        <?php endif; ?>
            
        <?php if($permissao_cadastrar_painel && ($permissao_cadastrar_pasta || $permissao_excluir_pasta)) : ?>

            <div class="dropdown-divider"></div>
        
        <?php endif; ?>

        <?php if($permissao_cadastrar_pasta || $permissao_excluir_pasta) : ?>
        
            <h6 class="dropdown-header"><i class="indicator bp-Folder"></i> Pasta</h6>
            
            <?php if($permissao_cadastrar_pasta) : ?>
            
                <a class="dropdown-item open-folder" data-tipo="PAINEL">Inserir</a>
                
                <a class="dropdown-item update-folder" data-tipo="PAINEL">Alterar / Renomear</a>
            
            <?php endif; ?>

            <?php if($permissao_excluir_pasta) : ?>

                <a class="dropdown-item delete-folder">Excluir</a>
            
            <?php endif; ?>

            
        <?php endif; ?>
            
    </div>

<?php endif; ?>

<?php if($permissao_visualizar_painel && ($permissao_duplicar_painel || $permissao_alterar_painel || $permissao_excluir_painel)) : ?>

    <div id="dropdown-menu-painel" data-id="" data-title="" class="dropdown-menu">
        
        <h6 class="dropdown-header"><i class="bp-painel"></i> Painel</h6>
        
        <?php if($permissao_duplicar_painel) : ?>
        
            <a class="dropdown-item duplicate-painel">Duplicar</a>
        
        <?php endif; ?>
        
        <?php if($permissao_alterar_painel) : ?>
        
            <a class="dropdown-item rename-painel">Renomear</a>
            
            <a class="dropdown-item update-painel">Alterar</a>
        
        <?php endif; ?>

        <?php if($permissao_excluir_painel) : ?>
        
            <a class="dropdown-item delete-painel">Excluir</a>
        
        <?php endif; ?>
        
    </div>

<?php endif; ?>

<?php if($permissao_visualizar_relatorio && ($permissao_cadastrar_relatorio || $permissao_cadastrar_pasta || $permissao_excluir_pasta)) : ?>

    <div id="dropdown-menu-folder-relatorio" data-id="" data-title="" class="dropdown-menu">

        <?php if($permissao_cadastrar_relatorio) : ?>

            <h6 class="dropdown-header"><i class="bp-chart--grid"></i> Relatório</h6>

            <a class="dropdown-item open-relatorio">Inserir</a>

        <?php endif; ?>

        <?php if($permissao_cadastrar_relatorio && ($permissao_cadastrar_pasta || $permissao_excluir_pasta)) : ?>

            <div class="dropdown-divider"></div>

        <?php endif; ?>

        <?php if($permissao_cadastrar_pasta || $permissao_excluir_pasta) : ?>

            <h6 class="dropdown-header"><i class="indicator bp-Folder"></i> Pasta</h6>

            <?php if($permissao_cadastrar_pasta) : ?>

                <a class="dropdown-item open-folder" data-tipo="RELATORIO">Inserir</a>

                <a class="dropdown-item update-folder" data-tipo="RELATORIO">Alterar / Renomear</a>

            <?php endif; ?>

            <?php if($permissao_excluir_pasta) : ?>

                <a class="dropdown-item delete-folder">Excluir</a>

            <?php endif; ?>


        <?php endif; ?>

    </div>

<?php endif; ?>

<?php if($permissao_visualizar_relatorio && ($permissao_duplicar_relatorio || $permissao_alterar_relatorio || $permissao_excluir_relatorio)) : ?>

    <div id="dropdown-menu-relatorio" data-id="" data-title="" class="dropdown-menu">

        <h6 class="dropdown-header"><i class="bp-chart--grid"></i> Relatório</h6>

        <?php if($permissao_duplicar_relatorio) : ?>

            <a class="dropdown-item duplicate-relatorio">Duplicar</a>

        <?php endif; ?>

        <?php if($permissao_alterar_relatorio) : ?>

            <a class="dropdown-item rename-relatorio">Renomear</a>

            <a class="dropdown-item update-relatorio">Alterar</a>

        <?php endif; ?>

        <?php if($permissao_excluir_relatorio) : ?>

            <a class="dropdown-item delete-relatorio">Excluir</a>

        <?php endif; ?>

    </div>

<?php endif; ?>

<script>
            
    $(function() 
    {
        var _menufolderconsulta = $("#dropdown-menu-folder-consulta");
        var _menuconsulta = $("#dropdown-menu-consulta");
        var _menugeneralconsulta = $("#dropdown-general-consulta");
        var _menufolderpainel = $("#dropdown-menu-folder-painel");
        var _menupainel = $("#dropdown-menu-painel");
        var _menugeneralpainel = $("#dropdown-general-painel");
        var _menufolderrelatorio = $("#dropdown-menu-folder-relatorio");
        var _menurelatorio = $("#dropdown-menu-relatorio");
        var _menugeneralrelatorio = $("#dropdown-general-relatorio");
        
        $("#modal-consulta").iziModal({
            transitionIn: '',
            transitionOut: '',
            transitionInOverlay: '',
            transitionOutOverlay: ''
        });
        
        $("#modal-painel").iziModal({
            transitionIn: '',
            transitionOut: '',
            transitionInOverlay: '',
            transitionOutOverlay: ''
        });

        $("#modal-relatorio").iziModal({
            transitionIn: '',
            transitionOut: '',
            transitionInOverlay: '',
            transitionOutOverlay: ''
        });

        $("#modal-pasta").iziModal({
            transitionIn: '',
            transitionOut: '',
            transitionInOverlay: '',
            transitionOutOverlay: ''
        });
        
        var filter = $('#find-data-menu'),
        filtering = false,
        thread = null;
        
        filter.on('keyup keypress', function(e) 
        {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13)
            { 
                e.preventDefault();
                return false;
            }
        });
        
        <?php if($permissao_visualizar_consulta) : ?>
        
            var $tree1 = $('#tree1');
            
            $tree1.tree({
                data: <?= $menu_consulta ?>,
                autoOpen: false,
                autoEscape: false,
                dragAndDrop: <?= (!MobileMagic::isMobile() && $permissao_mover_pasta) ? 'true' : 'false' ?>,
                saveState: 'treemenuconsulta',
                onCanMoveTo: function(moved_node, target_node, position)
                {
                    return target_node.class == "pasta";
                },
                onCanMove: function(node)
                {
                    return !filtering;
                },
                onCreateLi: function(node, $li) 
                {
                    var _customClass = node.class,
                    title = $li.find('.jqtree-title'),
                    search = filter.val().toLowerCase(),
                    value = title.text().toLowerCase();
        
                    $li.find('.jqtree-title').attr('title', node.title);

                    if(_customClass == 'pasta')
                    {
                        $li.addClass('jqtree-folder');
                    }
                    else
                    {
                        $li.find('.jqtree-element').addClass(_customClass);
                    }
                    
                    if(search !== '') 
                    {
                        $li.hide();
                        if(value.indexOf(search) > -1) {
                            $li.show();
                            var parent = node.parent;
                            while(typeof(parent.element) !== 'undefined') {
                                $(parent.element)
                                    .show()
                                    .addClass('jqtree-filtered');
                                parent = parent.parent;
                            }
                        }
                        if(!filtering) {
                            filtering = true;
                        };
                        if(!$tree1.hasClass('jqtree-filtering')) {
                            $tree1.addClass('jqtree-filtering');
                        };
                    } else {
                        if(filtering) {
                            filtering = false;
                        };
                        if($tree1.hasClass('jqtree-filtering')) {
                            $tree1.removeClass('jqtree-filtering');
                        };
                    };
                },
                closedIcon: $('<i class="indicator bp-Folder"></i>'),
                openedIcon: $('<i class="indicator bp-Folder--open"></i>')
            });

            $tree1.on(
                'tree.click',
                function(event) 
                {
                    var node = event.node;

                    if(node.class == 'pasta')
                    {
                        var ns = $tree1.tree('getNodeById', node.id);

                        if(node.is_open)
                        {
                            $tree1.tree('closeNode', ns);
                        }
                        else
                        {
                            $tree1.tree('openNode', ns);
                        }
                    }
                }
            );
    
            <?php if($permissao_mover_pasta) : ?>
        
                $tree1.on(
                    'tree.move',
                    function(event) {

                        var _current_id = event.move_info.moved_node.state;
                        var _current_class = event.move_info.moved_node.class;
                        var _position = event.move_info.position;
                        var _friend_id = event.move_info.target_node.state;

                         $.ajax({
                            url: '/ajax/move-menu',
                            type: 'POST',
                            data: 
                            {
                                'current_id': _current_id,
                                'class': _current_class,
                                'position': _position,
                                'parent_id': _friend_id,
                            },
                            success: function (success) 
                            {
                                if(success)
                                {
                                    iziToast.success({
                                        title: 'Menu alterado com sucesso!',
                                        position: 'topCenter',
                                        close: true,
                                        transitionIn: 'flipInX',
                                        transitionOut: 'flipOutX',
                                    });
                                }
                            }
                        });
                    }
                );

            <?php endif; ?>
                
            $tree1.on(
                'tree.contextmenu',
                function(event) 
                {
                    var node = event.node;
                    var _id = node.state;
                    var _title = node.title;
                    _menugeneralconsulta.parent().removeClass('show');
                    _menupainel.hide();
                    _menufolderpainel.hide();
                    _menurelatorio.hide();
                    _menufolderrelatorio.hide();

                    if(node.class == 'pasta')
                    {
                        _menuconsulta.hide();
                        _menufolderconsulta.attr('data-id', _id);
                        _menufolderconsulta.attr('data-title', _title);

                        _menufolderconsulta.css({
                            display: "block",
                            left: event.click_event.pageX,
                            top: (event.click_event.pageY - 220) > 0 ? event.click_event.pageY - 220 : 0
                        });
                    }
                    else
                    {
                        _menufolderconsulta.hide();
                        _menuconsulta.attr('data-id', _id);
                        _menuconsulta.attr('data-title', _title);

                        _menuconsulta.css({
                            display: "block",
                            left: event.click_event.pageX,
                            top: (event.click_event.pageY - 142) > 0 ? event.click_event.pageY - 142 : 0
                        });
                    }
                }
            );

            _menufolderconsulta.on("click", "a", function() {
                _menufolderconsulta.hide();
            });

            _menuconsulta.on("click", "a", function(e) {
                _menuconsulta.hide();
            });
            
            <?php if($consulta) : ?>
                
                var nodeFolder = $tree1.tree('getNodeById', 'pasta_<?= $consulta->id_pasta ?>');
                $tree1.tree('openNode', nodeFolder);
                var nodeConsulta = $tree1.tree('getNodeById', 'consulta_<?= $consulta->id ?>');
                $tree1.tree('selectNode', nodeConsulta);

            <?php else: ?>

                $tree1.tree('selectNode', null);

            <?php endif; ?>
                
            <?php if($permissao_cadastrar_consulta) : ?>

                $(document).on('click', '.open-consulta', function(event) 
                {
                    event.preventDefault();
                    event.stopPropagation();

                    var _folder_id = $(this).parent().attr('data-id');

                    if(typeof _folder_id === "undefined")
                    {
                        _folder_id = "null";
                    }

                    jQuery.ajax({
                        url: '/ajax/consulta?id=' + _folder_id,
                        type: 'POST',
                        success: function (data) 
                        {
                            $('#modal-consulta .modal-title').html('Nova Consulta');
                            $('#modal-consulta .iziModal__body').html(data);
                            $('#modal-consulta').iziModal('open');
                        },
                    });

                    return false;
                });

            <?php endif; ?>

            <?php if($permissao_duplicar_consulta) : ?>

                $(document).on('click', '.duplicate-consulta', function(event) 
                {
                    var _id = $(this).parent().attr('data-id');
                    
                    jQuery.ajax({
                        url: '/ajax/duplicate-consulta?id=' + _id,
                        type: 'POST',
                        success: function (data) 
                        {
                            $('#modal-consulta .modal-title').html('Duplicar Consulta');
                            $('#modal-consulta .iziModal__body').html(data);
                            $('#modal-consulta').iziModal('open');
                        },
                    });
                });

            <?php endif; ?>

            <?php if($permissao_alterar_consulta) : ?>

                $(document).on('click', '.update-consulta', function(event) 
                {
                    event.preventDefault();
                    event.stopPropagation();

                    var _id = $(this).parent().attr('data-id');

                    window.location.replace("/consulta/alterar/" + _id);

                    return false;
                });
                
                $(document).on('click', '.rename-consulta', function(event) 
                {
                    event.preventDefault();
                    event.stopPropagation();

                    var _id = $(this).parent().attr('data-id');

                    jQuery.ajax({
                        url: '/ajax/rename-consulta?id=' + _id,
                        type: 'POST',
                        success: function (data) 
                        {
                            $('#modal-consulta .modal-title').html('Renomear Consulta');
                            $('#modal-consulta .iziModal__body').html(data);
                            $('#modal-consulta').iziModal('open');
                        },
                    });
                });

            <?php endif; ?>

            <?php if($permissao_excluir_consulta) : ?>

                $(document).on('click', '.delete-consulta', function(event)
                {
                    var _id = $(this).parent().attr('data-id');
                    var _name = $(this).parent().attr('data-title');
                    var _current = ('<?= $controller_id ?>' == 'consulta' && <?= $parameter_id ?> == _id);

                    swal({
                        title: "Exclusão de consulta",
                        text: "Tem certeza que deseja excluir a consulta '" + _name + "'?",
                        type: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#007EC3',
                        confirmButtonText: 'Excluir'
                    }).then((result) => 
                    {
                        if (result.value) 
                        {
                            $.ajax({
                                url: '/ajax/delete-consulta?id=' + _id,
                                success: function (data) 
                                {
                                    if(_current)
                                    {
                                         window.location.href = "/";
                                    }
                                    else
                                    {
                                        location.reload();
                                    }
                                },
                            });
                        }
                    });
                });

            <?php endif; ?>
    
        <?php endif; ?>
    
        <?php if($permissao_visualizar_painel) : ?>
        
            var $tree2 = $('#tree2');

            $tree2.tree({
                data: <?= $menu_painel ?>,
                autoOpen: false,
                autoEscape: false,
                dragAndDrop: <?= (!MobileMagic::isMobile() && $permissao_mover_pasta) ? 'true' : 'false' ?>,
                saveState: 'treemenupainel',
                onCanMoveTo: function(moved_node, target_node, position)
                {
                    return target_node.class == "pasta";
                },
                onCanMove: function(node)
                {
                    return !filtering;
                },
                onCreateLi: function(node, $li) 
                {
                    var _customClass = node.class,
                    title = $li.find('.jqtree-title'),
                    search = filter.val().toLowerCase(),
                    value = title.text().toLowerCase();

                    $li.find('.jqtree-title').attr('title', node.title);

                    if(_customClass == 'pasta')
                    {
                        $li.addClass('jqtree-folder');
                    }
                    else
                    {
                        $li.find('.jqtree-element').addClass(_customClass);
                    }
                    
                    if(search !== '') 
                    {
                        $li.hide();
                        if(value.indexOf(search) > -1) {
                            $li.show();
                            var parent = node.parent;
                            while(typeof(parent.element) !== 'undefined') {
                                $(parent.element)
                                    .show()
                                    .addClass('jqtree-filtered');
                                parent = parent.parent;
                            }
                        }
                        if(!filtering) {
                            filtering = true;
                        };
                        if(!$tree2.hasClass('jqtree-filtering')) {
                            $tree2.addClass('jqtree-filtering');
                        };
                    } else {
                        if(filtering) {
                            filtering = false;
                        };
                        if($tree2.hasClass('jqtree-filtering')) {
                            $tree2.removeClass('jqtree-filtering');
                        };
                    };
                },
                closedIcon: $('<i class="indicator bp-Folder"></i>'),
                openedIcon: $('<i class="indicator bp-Folder--open"></i>')
            });

            $tree2.on(
                'tree.click',
                function(event) 
                {
                    var node = event.node;

                    if(node.class == 'pasta')
                    {
                        var ns = $tree2.tree('getNodeById', node.id);

                        if(node.is_open)
                        {
                            $tree2.tree('closeNode', ns);
                        }
                        else
                        {
                            $tree2.tree('openNode', ns);
                        }
                    }
                }
            );
    
            <?php if($permissao_mover_pasta) : ?>
    
                $tree2.on(
                    'tree.move',
                    function(event) {

                        var _current_id = event.move_info.moved_node.state;
                        var _current_class = event.move_info.moved_node.class;
                        var _position = event.move_info.position;
                        var _friend_id = event.move_info.target_node.state;

                         $.ajax({
                            url: '/ajax/move-menu',
                            type: 'POST',
                            data: 
                            {
                                'current_id': _current_id,
                                'class': _current_class,
                                'position': _position,
                                'parent_id': _friend_id,
                            },
                            success: function (success) 
                            {
                                if(success)
                                {
                                    iziToast.success({
                                        title: 'Menu alterado com sucesso!',
                                        position: 'topCenter',
                                        close: true,
                                        transitionIn: 'flipInX',
                                        transitionOut: 'flipOutX',
                                    });
                                }
                            }
                        });
                    }
                );

            <?php endif; ?>
                
            $tree2.on(
                'tree.contextmenu',
                function(event) 
                {
                    var node = event.node;
                    var _id = node.state;
                    var _title = node.title;
                    _menugeneralpainel.parent().removeClass('show');
                    _menuconsulta.hide();
                    _menufolderconsulta.hide();
                    _menurelatorio.hide();
                    _menufolderrelatorio.hide();

                    if(node.class == 'pasta')
                    {
                        _menupainel.hide();
                        _menufolderpainel.attr('data-id', _id);
                        _menufolderpainel.attr('data-title', _title);

                        _menufolderpainel.css({
                            display: "block",
                            left: event.click_event.pageX,
                            top: (event.click_event.pageY - 220) > 0 ? event.click_event.pageY - 220 : 0
                        });
                    }
                    else
                    {
                        _menufolderpainel.hide();
                        _menupainel.attr('data-id', _id);
                        _menupainel.attr('data-title', _title);

                        _menupainel.css({
                            display: "block",
                            left: event.click_event.pageX,
                            top: (event.click_event.pageY - 142) > 0 ? event.click_event.pageY - 142 : 0
                        });
                    }
                }
            );

            _menufolderpainel.on("click", "a", function() {
                _menufolderpainel.hide();
            });

            _menupainel.on("click", "a", function(e) {
                _menupainel.hide();
            });
            
            <?php if($painel) : ?>

                var nodeFolder = $tree2.tree('getNodeById', 'pasta_<?= $painel->id_pasta ?>');
                $tree2.tree('openNode', nodeFolder);
                var nodePainel = $tree2.tree('getNodeById', 'painel_<?= $painel->id ?>');
                $tree2.tree('selectNode', nodePainel);

            <?php else: ?>

                $tree2.tree('selectNode', null);

            <?php endif; ?>
                
            <?php if($permissao_cadastrar_painel) : ?>
            
                $(document).on('click', '.open-painel', function(event) 
                {
                    event.preventDefault();
                    event.stopPropagation();

                    var _folder_id = $(this).parent().attr('data-id');

                    if(typeof _folder_id === "undefined")
                    {
                        _folder_id = "null";
                    }

                    jQuery.ajax({
                        url: '/ajax/painel?id=' + _folder_id,
                        type: 'POST',
                        success: function (data) 
                        {
                            $('#modal-painel .modal-title').html('Novo Painel');
                            $('#modal-painel .iziModal__body').html(data);
                            $('#modal-painel').iziModal('open');
                        },
                    });

                    return false;
                });

            <?php endif; ?>

            <?php if($permissao_duplicar_painel) : ?>

                $(document).on('click', '.duplicate-painel', function(event) 
                {
                    var _id = $(this).parent().attr('data-id');
                    
                    jQuery.ajax({
                        url: '/ajax/duplicate-painel?id=' + _id,
                        type: 'POST',
                        success: function (data) 
                        {
                            $('#modal-painel .modal-title').html('Duplicar Painel');
                            $('#modal-painel .iziModal__body').html(data);
                            $('#modal-painel').iziModal('open');
                        },
                    });
                });

            <?php endif; ?>

            <?php if($permissao_alterar_painel) : ?>

                $(document).on('click', '.update-painel', function(event) 
                {
                    event.preventDefault();
                    event.stopPropagation();

                    var _id = $(this).parent().attr('data-id');

                    window.location.replace("/painel/alterar/" + _id);

                    return false;
                });
                
                $(document).on('click', '.rename-painel', function(event) 
                {
                    event.preventDefault();
                    event.stopPropagation();

                    var _id = $(this).parent().attr('data-id');

                    jQuery.ajax({
                        url: '/ajax/rename-painel?id=' + _id,
                        type: 'POST',
                        success: function (data) 
                        {
                            $('#modal-painel .modal-title').html('Renomear Painel');
                            $('#modal-painel .iziModal__body').html(data);
                            $('#modal-painel').iziModal('open');
                        },
                    });
                });

            <?php endif; ?>

            <?php if($permissao_excluir_painel) : ?>

                $(document).on('click', '.delete-painel', function(event)
                {
                    var _id = $(this).parent().attr('data-id');
                    var _name = $(this).parent().attr('data-title');
                    var _current = ('<?= $controller_id ?>' == 'painel' && <?= $parameter_id ?> == _id);

                    swal({
                        title: "Exclusão de painel",
                        text: "Tem certeza que deseja excluir o painel '" + _name + "'?",
                        type: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#007EC3',
                        confirmButtonText: 'Excluir'
                    }).then((result) => 
                    {
                        if (result.value) 
                        {
                            $.ajax({
                                url: '/ajax/delete-painel?id=' + _id,
                                success: function (data) 
                                {
                                    if(_current)
                                    {
                                         window.location.href = "/";
                                    }
                                    else
                                    {
                                        location.reload();
                                    }
                                },
                            });
                        }
                    });
                });

            <?php endif; ?>

        <?php endif; ?>

        <?php if($permissao_visualizar_relatorio) : ?>

        var $tree3 = $('#tree3');

        $tree3.tree({
            data: <?= $menu_relatorio ?>,
            autoOpen: false,
            autoEscape: false,
            dragAndDrop: <?= (!MobileMagic::isMobile() && $permissao_mover_pasta) ? 'true' : 'false' ?>,
            saveState: 'treemenurelatorio',
            onCanMoveTo: function(moved_node, target_node, position)
            {
                return target_node.class == "pasta";
            },
            onCanMove: function(node)
            {
                return !filtering;
            },
            onCreateLi: function(node, $li)
            {
                var _customClass = node.class,
                    title = $li.find('.jqtree-title'),
                    search = filter.val().toLowerCase(),
                    value = title.text().toLowerCase();

                $li.find('.jqtree-title').attr('title', node.title);

                if(_customClass == 'pasta')
                {
                    $li.addClass('jqtree-folder');
                }
                else
                {
                    $li.find('.jqtree-element').addClass(_customClass);
                }

                if(search !== '')
                {
                    $li.hide();
                    if(value.indexOf(search) > -1) {
                        $li.show();
                        var parent = node.parent;
                        while(typeof(parent.element) !== 'undefined') {
                            $(parent.element)
                                .show()
                                .addClass('jqtree-filtered');
                            parent = parent.parent;
                        }
                    }
                    if(!filtering) {
                        filtering = true;
                    };
                    if(!$tree3.hasClass('jqtree-filtering')) {
                        $tree3.addClass('jqtree-filtering');
                    };
                } else {
                    if(filtering) {
                        filtering = false;
                    };
                    if($tree3.hasClass('jqtree-filtering')) {
                        $tree3.removeClass('jqtree-filtering');
                    };
                };
            },
            closedIcon: $('<i class="indicator bp-Folder"></i>'),
            openedIcon: $('<i class="indicator bp-Folder--open"></i>')
        });

        $tree3.on(
            'tree.click',
            function(event)
            {
                var node = event.node;

                if(node.class == 'pasta')
                {
                    var ns = $tree3.tree('getNodeById', node.id);

                    if(node.is_open)
                    {
                        $tree3.tree('closeNode', ns);
                    }
                    else
                    {
                        $tree3.tree('openNode', ns);
                    }
                }
            }
        );

        <?php if($permissao_mover_pasta) : ?>

        $tree3.on(
            'tree.move',
            function(event) {

                var _current_id = event.move_info.moved_node.state;
                var _current_class = event.move_info.moved_node.class;
                var _position = event.move_info.position;
                var _friend_id = event.move_info.target_node.state;

                $.ajax({
                    url: '/ajax/move-menu',
                    type: 'POST',
                    data:
                        {
                            'current_id': _current_id,
                            'class': _current_class,
                            'position': _position,
                            'parent_id': _friend_id,
                        },
                    success: function (success)
                    {
                        if(success)
                        {
                            iziToast.success({
                                title: 'Menu alterado com sucesso!',
                                position: 'topCenter',
                                close: true,
                                transitionIn: 'flipInX',
                                transitionOut: 'flipOutX',
                            });
                        }
                    }
                });
            }
        );

        <?php endif; ?>

        $tree3.on(
            'tree.contextmenu',
            function(event)
            {
                var node = event.node;
                var _id = node.state;
                var _title = node.title;
                _menugeneralrelatorio.parent().removeClass('show');
                _menupainel.hide();
                _menufolderpainel.hide();
                _menurelatorio.hide();
                _menufolderrelatorio.hide();

                if(node.class == 'pasta')
                {
                    _menurelatorio.hide();
                    _menufolderrelatorio.attr('data-id', _id);
                    _menufolderrelatorio.attr('data-title', _title);

                    _menufolderrelatorio.css({
                        display: "block",
                        left: event.click_event.pageX,
                        top: (event.click_event.pageY - 220) > 0 ? event.click_event.pageY - 220 : 0
                    });
                }
                else
                {
                    _menufolderrelatorio.hide();
                    _menurelatorio.attr('data-id', _id);
                    _menurelatorio.attr('data-title', _title);

                    _menurelatorio.css({
                        display: "block",
                        left: event.click_event.pageX,
                        top: (event.click_event.pageY - 142) > 0 ? event.click_event.pageY - 142 : 0
                    });
                }
            }
        );

        _menufolderrelatorio.on("click", "a", function() {
            _menufolderrelatorio.hide();
        });

        _menurelatorio.on("click", "a", function(e) {
            _menurelatorio.hide();
        });

        <?php if($relatorio) : ?>

        var nodeFolder = $tree3.tree('getNodeById', 'pasta_<?= $relatorio->id_pasta ?>');
        $tree3.tree('openNode', nodeFolder);
        var nodeRelatorio = $tree3.tree('getNodeById', 'relatorio_<?= $relatorio->id ?>');
        $tree3.tree('selectNode', nodeRelatorio);

        <?php else: ?>

        $tree3.tree('selectNode', null);

        <?php endif; ?>

        <?php if($permissao_cadastrar_relatorio) : ?>

        $(document).on('click', '.open-relatorio', function(event)
        {
            event.preventDefault();
            event.stopPropagation();

            var _folder_id = $(this).parent().attr('data-id');

            if(typeof _folder_id === "undefined")
            {
                _folder_id = "null";
            }

            jQuery.ajax({
                url: '/ajax/relatorio?id=' + _folder_id,
                type: 'POST',
                success: function (data)
                {
                    $('#modal-relatorio .modal-title').html('Novo Relatorio');
                    $('#modal-relatorio .iziModal__body').html(data);
                    $('#modal-relatorio').iziModal('open');
                },
            });

            return false;
        });

        <?php endif; ?>

        <?php if($permissao_duplicar_relatorio) : ?>

        $(document).on('click', '.duplicate-relatorio', function(event)
        {
            var _id = $(this).parent().attr('data-id');

            jQuery.ajax({
                url: '/ajax/duplicate-relatorio?id=' + _id,
                type: 'POST',
                success: function (data)
                {
                    $('#modal-relatorio .modal-title').html('Duplicar Relatorio');
                    $('#modal-relatorio .iziModal__body').html(data);
                    $('#modal-relatorio').iziModal('open');
                },
            });
        });

        <?php endif; ?>

        <?php if($permissao_alterar_relatorio) : ?>

        $(document).on('click', '.update-relatorio', function(event)
        {
            event.preventDefault();
            event.stopPropagation();

            var _id = $(this).parent().attr('data-id');

            window.location.replace("/relatorio-data/alterar?id=" + _id);

            return false;
        });

        $(document).on('click', '.rename-relatorio', function(event)
        {
            event.preventDefault();
            event.stopPropagation();

            var _id = $(this).parent().attr('data-id');

            jQuery.ajax({
                url: '/ajax/rename-relatorio?id=' + _id,
                type: 'POST',
                success: function (data)
                {
                    $('#modal-relatorio .modal-title').html('Renomear Relatório');
                    $('#modal-relatorio .iziModal__body').html(data);
                    $('#modal-relatorio').iziModal('open');
                },
            });
        });

        <?php endif; ?>

        <?php if($permissao_excluir_relatorio) : ?>

        $(document).on('click', '.delete-relatorio', function(event)
        {
            var _id = $(this).parent().attr('data-id');
            var _name = $(this).parent().attr('data-title');
            var _current = ('<?= $controller_id ?>' == 'relatorio-data' && <?= $parameter_id ?> == _id);

            swal({
                title: "Exclusão de relatório",
                text: "Tem certeza que deseja excluir o relatório '" + _name + "'?",
                type: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#007EC3',
                confirmButtonText: 'Excluir'
            }).then((result) =>
            {
                if (result.value)
                {
                    $.ajax({
                        url: '/ajax/delete-relatorio?id=' + _id,
                        success: function (data)
                        {
                            if(_current)
                            {
                                window.location.href = "/";
                            }
                            else
                            {
                                location.reload();
                            }
                        },
                    });
                }
            });
        });

        <?php endif; ?>

        <?php endif; ?>

        $('body').click(function(event){
            _menufolderconsulta.hide();
            _menuconsulta.hide();
            _menufolderpainel.hide();
            _menupainel.hide();
            _menufolderrelatorio.hide();
            _menurelatorio.hide();
        });
            
        <?php if($permissao_cadastrar_pasta && ($permissao_visualizar_consulta || $permissao_visualizar_painel || $permissao_visualizar_relatorio)) : ?>
            
            $(document).on('click', '.open-folder', function(event)
            {
                event.preventDefault();
                event.stopPropagation();

                var _folder_id = $(this).parent().attr('data-id');
                var _tipo = $(this).attr('data-tipo');

                if(typeof _folder_id === "undefined")
                {
                    _folder_id = "null";
                }

                jQuery.ajax({
                    url: '/ajax/pasta?tipo=' + _tipo + '&id=' + _folder_id,
                    type: 'POST',
                    success: function (data) 
                    {
                        $('#modal-pasta .modal-title').html('Nova Pasta');
                        $('#modal-pasta .iziModal__body').html(data);
                        $('#modal-pasta').iziModal('open');
                    },
                });

                return false;
            });

            $(document).on('click', '.update-folder', function(event)
            {
                var _id = $(this).parent().attr('data-id');
                var _tipo = $(this).attr('data-tipo');
                
                jQuery.ajax({
                    url: '/ajax/pasta?tipo=' + _tipo + '&id=' + _id + '&update=true',
                    success: function (data) 
                    {
                        $('#modal-pasta .modal-title').html('Alterar Pasta');
                        $('#modal-pasta .iziModal__body').html(data);
                        $('#modal-pasta').iziModal('open');
                    },
                });

                return false;
            });
            
        <?php endif; ?>

        <?php if($permissao_excluir_pasta && ($permissao_visualizar_consulta || $permissao_visualizar_painel || $permissao_visualizar_relatorio)) : ?>

            $(document).on('click', '.delete-folder', function(event)
            {
                var _id = $(this).parent().attr('data-id');
                var _name = $(this).parent().attr('data-title');

                swal({
                    title: "Exclusão de pasta",
                    text: "Tem certeza que deseja excluir a pasta '" + _name + "'?",
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#007EC3',
                    confirmButtonText: 'Excluir'
                }).then((result) => 
                {
                    if (result.value) 
                    {
                        $.ajax({
                            url: '/ajax/delete-folder?id=' + _id,
                            success: function (data) 
                            {
                                location.reload();
                            },
                        });
                    }
                });
            });
            
        <?php endif; ?>
            
        filter.keyup(function()
        {
            clearTimeout(thread);
            thread = setTimeout(function () 
            {
                    $tree1.tree('loadData', <?= $menu_consulta ?>);
                    $tree2.tree('loadData', <?= $menu_painel ?>);
                    $tree3.tree('loadData', <?= $menu_relatorio ?>);
            }, 50);
        });
    });

</script>