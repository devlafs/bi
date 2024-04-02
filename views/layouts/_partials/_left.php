<?php

use app\magic\MenuMagic;
use app\magic\CacheMagic;
use app\magic\MobileMagic;

$controller_id = Yii::$app->controller->id;

$count_consulta = MenuMagic::getQuantidadeConsulta();
$count_painel = MenuMagic::getQuantidadePainel();
$count_relatorio = MenuMagic::getQuantidadeRelatorio();

$version = CacheMagic::getSystemData('version');

$permissao = Yii::$app->permissaoGeral;

$permissao_cadastrar_consulta = $permissao->can('ajax', 'consulta');
$permissao_cadastrar_pasta = $permissao->can('ajax', 'pasta');
$permissao_cadastrar_painel = $permissao->can('ajax', 'painel');
$permissao_cadastrar_relatorio = $permissao->can('ajax', 'relatorio');

$permissao_visualizar_painel = $permissao->can('painel', 'visualizar');
$permissao_visualizar_consulta = $permissao->can('consulta', 'visualizar');
$permissao_visualizar_relatorio = $permissao->can('relatorio-data', 'visualizar');

$js = <<<JS

    $('#menu-toggle').click(function() {
        event.preventDefault();
        $.ajax({
            url: '/ajax/update-menu-session',
            type: 'GET'
        })
    });

    $(document).delegate('.choose-bpbi-menu', 'click', function(e){
      e.preventDefault();
      var _type = $(this).data('type');

      if(_type == 'consulta')
      {
        $('#block__consulta').removeClass('display-none');
        $('#block__painel').addClass('display-none');
        $('#block__relatorio').addClass('display-none');
        $('#title__consulta').removeClass('display-none');
        $('#title__painel').addClass('display-none');
        $('#title__relatorio').addClass('display-none');
      }
      else if(_type == 'relatorio')
      {
        $('#block__relatorio').removeClass('display-none');
        $('#block__consulta').addClass('display-none');
        $('#block__painel').addClass('display-none');
        $('#title__relatorio').removeClass('display-none');
        $('#title__consulta').addClass('display-none');
        $('#title__painel').addClass('display-none');
      }
      else
      {
        $('#block__consulta').addClass('display-none');
        $('#block__relatorio').addClass('display-none');
        $('#block__painel').removeClass('display-none');
        $('#title__consulta').addClass('display-none');
        $('#title__relatorio').addClass('display-none');
        $('#title__painel').removeClass('display-none');
      }
    });

  

JS;

$this->registerJs($js);

$menu_contracted = isset($_SESSION['menu_' . Yii::$app->user->id]) ? $_SESSION['menu_' . Yii::$app->user->id] : false;

?>

<style>
    .title-menu {
        font-weight: 300;
        line-height: 26px;
        color: #fff;
        font-size: 1.714rem;
        margin-bottom: 0px;
        padding: 10px;
        margin-left: 0.429em;
    }

    .title-menu i.choose-bpbi-menu
    {
        color: #636c72 !important;
        cursor: pointer;
    }

    .title-menu i.choose-bpbi-menu.active
    {
        color: #fff !important;
    }

    .display-none
    {
        display: none !important;
    }

</style>

<div id="sidebar-wrapper" class="h-100 mh-100">
    <div class="sidebar--content h-100 mh-100">
        <div class="sidebar-nav pl-3 pr-3" id="menu-toggle">
            <div class="d-flex justify-content-start">
                <button id="main_icon" class="ml-auto align-self-center"><i class="<?= (!$menu_contracted) ? 'bp-arrow_left--circle' : 'bp-arrow_right--circle' ?>"></i></button>
            </div>
        </div>
        
        <div class="search__block mt-4 mb-4">
            <form id="form-sidebar" name="form-sidebar">
                <div class="typeahead__container">
                    <div class="typeahead__field">
                        <span class="typeahead__query">
                            <input class="js-typeahead-sidebar" id="find-data-menu" style="text-transform: uppercase;" name="sidebar[query]" type="search" placeholder="Localize Pastas, Painéis e Consultas" autocomplete="off">
                        </span>
                        <span class="typeahead__button"><i class="bp-search"></i></span>
                    </div>
                </div>
            </form>
        </div>

        <div id="sidebar--overflowBar">
            <ul id="sidebar" class="sidebar-nav">
                <?php if ($permissao_visualizar_painel) : ?>

                    <li id="title__painel" class="title__block d-flex align-items-center justify-content-start <?= ($controller_id == 'consulta' || $controller_id == 'relatorio-data') ? 'display-none' : '' ?>">
                        <div class="title-menu w-100">
                            Painéis
<!--                            <i data-type="relatorio" class="choose-bpbi-menu bp-chart--grid float-right ml-2 mt-1" title="Relatórios"></i>-->
                            <i data-type="consulta" class="choose-bpbi-menu bp-consulta float-right ml-2 mt-1" title="Consultas"></i>
                            <i data-type="painel" class="choose-bpbi-menu bp-painel active float-right ml-2 mt-1" title="Painéis"></i>
                        </div>

                        <?php if ($permissao_cadastrar_painel || $permissao_cadastrar_pasta) : ?>

                            <div class="dropdown ml-auto">
                                <button class="btn btn-outline-info btn--noborder" type="button" id="dropdownMenuPainels" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bp-menu-dots"></i></button>
                                <div id="dropdown-general-painel" class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuPainels">

                                    <?php if ($permissao_cadastrar_painel) : ?>

                                        <h6 class="dropdown-header"><i class="bp-painel"></i> Painel</h6>
                                        <a class="dropdown-item open-painel" href="#">Inserir</a>

                                    <?php endif; ?>

                                    <?php if ($permissao_cadastrar_painel && $permissao_cadastrar_pasta) : ?>

                                        <div class="dropdown-divider"></div>

                                    <?php endif; ?>

                                    <?php if ($permissao_cadastrar_pasta) : ?>

                                        <h6 class="dropdown-header"><i class="indicator bp-Folder"></i> Pasta</h6>
                                        <a class="dropdown-item open-folder" data-tipo="PAINEL" href="#">Inserir</a>

                                    <?php endif; ?>

                                </div>
                            </div>

                        <?php endif; ?>

                    </li>
                    <li id="block__painel" <?= ($controller_id == 'consulta' || $controller_id == 'relatorio-data') ? 'class="display-none"' : '' ?>>
                        <div id="tree2"></div>
                    </li>

                <?php endif; ?>

                <?php if ($permissao_visualizar_consulta) : ?>

                    <li id="title__consulta" class="title__block d-flex align-items-center justify-content-startn2vrpb00d
                     <?= ($controller_id == 'consulta') ? '' : 'display-none' ?>">
                        <div class="title-menu w-100">
                            Consultas
<!--                            <i data-type="relatorio" class="choose-bpbi-menu bp-chart--grid float-right ml-2 mt-1" title="Relatórios"></i>-->
                            <i data-type="consulta" class="choose-bpbi-menu bp-consulta active float-right ml-2 mt-1" title="Consultas"></i>
                            <i data-type="painel" class="choose-bpbi-menu bp-painel float-right ml-2 mt-1" title="Painéis"></i>
                        </div>

                        <?php if ($permissao_cadastrar_consulta || $permissao_cadastrar_pasta) : ?>

                            <div class="dropdown ml-auto">
                                <button class="btn btn-outline-info btn--noborder" type="button" id="dropdownMenuConsultas" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bp-menu-dots"></i></button>
                                <div id="dropdown-general-consulta" class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuConsultas">

                                    <?php if ($permissao_cadastrar_consulta) : ?>

                                        <h6 class="dropdown-header"><i class="bp-consulta"></i> Consulta</h6>
                                        <a class="dropdown-item open-consulta" href="#">Inserir</a>

                                    <?php endif; ?>

                                    <?php if ($permissao_cadastrar_consulta && $permissao_cadastrar_pasta) : ?>

                                        <div class="dropdown-divider"></div>

                                    <?php endif; ?>

                                    <?php if ($permissao_cadastrar_pasta) : ?>

                                        <h6 class="dropdown-header"><i class="indicator bp-Folder"></i> Pasta</h6>
                                        <a class="dropdown-item open-folder" data-tipo="CONSULTA" href="#">Inserir</a>

                                    <?php endif; ?>

                                </div>
                            </div>

                        <?php endif; ?>

                    </li>
                    <li id="block__consulta" <?= ($controller_id == 'consulta') ? '' : 'class="display-none"' ?>>
                        <div id="tree1"></div>
                    </li>

                <?php endif; ?>

                <?php if (false) : ?>

                    <li id="title__relatorio" class="title__block d-flex align-items-center justify-content-startn2vrpb00d
                     <?= ($controller_id == 'relatorio-data') ? '' : 'display-none' ?>">
                        <div class="title-menu w-100">
                            Relatórios
<!--                            <i data-type="relatorio" class="choose-bpbi-menu bp-chart--grid active float-right ml-2 mt-1" title="Relatórios"></i>-->
                            <i data-type="consulta" class="choose-bpbi-menu bp-consulta float-right ml-2 mt-1" title="Consultas"></i>
                            <i data-type="painel" class="choose-bpbi-menu bp-painel float-right ml-2 mt-1" title="Painéis"></i>
                        </div>

                        <?php if ($permissao_cadastrar_relatorio || $permissao_cadastrar_pasta) : ?>

                            <div class="dropdown ml-auto">
                                <button class="btn btn-outline-info btn--noborder" type="button" id="dropdownMenuConsultas" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bp-menu-dots"></i></button>
                                <div id="dropdown-general-relatorio" class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuConsultas">

                                    <?php if ($permissao_cadastrar_relatorio) : ?>

                                        <h6 class="dropdown-header"><i class="bp-chart--grid"></i> Relatórios</h6>
                                        <a class="dropdown-item open-relatorio" href="#">Inserir</a>

                                    <?php endif; ?>

                                    <?php if ($permissao_cadastrar_relatorio && $permissao_cadastrar_pasta) : ?>

                                        <div class="dropdown-divider"></div>

                                    <?php endif; ?>

                                    <?php if ($permissao_cadastrar_pasta) : ?>

                                        <h6 class="dropdown-header"><i class="indicator bp-Folder"></i> Pasta</h6>
                                        <a class="dropdown-item open-folder" data-tipo="RELATORIO" href="#">Inserir</a>

                                    <?php endif; ?>

                                </div>
                            </div>

                        <?php endif; ?>

                    </li>
                    <li id="block__relatorio" <?= ($controller_id == 'relatorio-data') ? '' : 'class="display-none"' ?>>
                        <div id="tree3"></div>
                    </li>

                <?php endif; ?>

            </ul>
        </div>

        <ul class="sidebar--menu__contracted <?= (isset($contracted) && $contracted || MobileMagic::isMobile() || $menu_contracted) ? '' : 'block__hide' ?>" id="sidebar--menu__collapsed">

            <?php if ($permissao_visualizar_painel) : ?>

                <li>
                    <a class="d-flex justify-content-center align-items-center">
                        <span class="badge badge-pill badge-success"><?= ($count_painel > 100) ? '+99' : $count_painel ?></span>
                        <i class="align-middle bp-painel"></i>
                    </a>
                </li>

            <?php endif; ?>

            <?php if ($permissao_visualizar_consulta) : ?>

                <li>
                    <a class="d-flex justify-content-center align-items-center">
                        <span class="badge badge-pill badge-success"><?= ($count_consulta > 100) ? '+99' : $count_consulta ?></span>
                        <i class="align-middle bp-consulta"></i>
                    </a>
                </li>

            <?php endif; ?>

            <?php if ($permissao_visualizar_relatorio) : ?>
<!---->
<!--                <li>-->
<!--                    <a class="d-flex justify-content-center align-items-center">-->
<!--                        <span class="badge badge-pill badge-success">--><?php //= ($count_relatorio > 100) ? '+99' : $count_relatorio ?><!--</span>-->
<!--                        <i class="align-middle bp-chart--grid"></i>-->
<!--                    </a>-->
<!--                </li>-->

            <?php endif; ?>

        </ul>

        <ul class="sidebarfooter__block">
            <li class="d-flex align-items-center justify-content-start">
                <span>Bem-vindo, <?= Yii::$app->user->identity->nomeResumo ?>!</span>
                <div class="dropup ml-auto">
                    <button type="button" class="btn btn-outline-info btn--noborder" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bp-user"></i>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu">

                        <?php $show_divider = FALSE; ?>

                        <?php if ($permissao->can('conexao', 'index')) : $show_divider = TRUE; ?>

                            <a class="dropdown-item" href="/conexao">Conexões</a>

                        <?php endif; ?>

                        <?php if ($permissao->can('indicador', 'index')) : $show_divider = TRUE; ?>

                            <a class="dropdown-item" href="/indicador">Cubos</a>

                        <?php endif; ?>

                        <?php if ($permissao->can('metadado', 'index')) : $show_divider = TRUE; ?>

<!--                            <a class="dropdown-item" href="/metadado">Metadados</a>-->

                        <?php endif; ?>

                        <?php if ($permissao->can('relatorio', 'index')) : $show_divider = TRUE; ?>

<!--                            <a class="dropdown-item" href="/relatorio">Relatórios</a>-->

                        <?php endif; ?>

                        <?php if ($permissao->can('email', 'index')) : $show_divider = TRUE; ?>

<!--                            <a class="dropdown-item" href="/email">Emails</a>-->

                        <?php endif; ?>

                        <?php if ($permissao->can('usuario', 'index')) : $show_divider = TRUE; ?>

<!--                            <a class="dropdown-item" href="/usuario">Usuários</a>-->

                        <?php endif; ?>

                        <?php if ($permissao->can('perfil', 'index')) : $show_divider = TRUE; ?>

<!--                            <a class="dropdown-item" href="/perfil">Perfis/Permissões</a>-->

                        <?php endif; ?>

                        <?php if ($permissao->can('template-email', 'index')) : $show_divider = TRUE; ?>

<!--                            <a class="dropdown-item" href="/template-email">Temp. Emails</a>-->

                        <?php endif; ?>
                            
                        <?php if (Yii::$app->user->identity->id == 1) : $show_divider = TRUE; ?>

<!--                            <a class="dropdown-item" href="/configuracoes">Conf. Gráficos</a>-->

                        <?php endif; ?>

                        <?php if (Yii::$app->user->identity->id == 1) : $show_divider = TRUE; ?>

<!--                            <a class="dropdown-item" href="/geral">Conf. do Sistema</a>-->

                        <?php endif; ?>

                        <?php if ($show_divider) : ?>

                            <div class="dropdown-divider"></div>

                        <?php endif; ?>

<!--                        <a class="dropdown-item disabled" href="javascript:void();">Ajuda (Em breve)</a>-->

<!--                        <a class="dropdown-item" href="/change-log">Atualizações</a>-->

                        <?php if ($permissao->can('log', 'index')) : ?>

<!--                            <a class="dropdown-item" href="/log-acesso">Logs de acessos</a>-->

<!--                            <a class="dropdown-item" href="/log">Logs de ações</a>-->

                        <?php endif; ?>

                        <div class="dropdown-divider"></div>

<!--                        <a class="dropdown-item" href="/meu-perfil">Meu Perfil</a>-->

                        <a class="dropdown-item" href="/site/logout">Sair</a>

                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>