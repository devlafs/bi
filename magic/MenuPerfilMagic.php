<?php

namespace app\magic;

use app\models\AdminPerfil;
use app\models\ConsultaPermissao;
use app\models\PainelPermissao;
use app\models\RelatorioData;
use app\models\RelatorioPermissao;
use Yii;
use app\models\Pasta;
use app\models\Consulta;
use app\models\Painel;
use yii\db\Query;

class MenuPerfilMagic {

    public static function getCuttedName($name) {
        $len = 35;
        return (strlen($name) > $len) ? mb_strtoupper(mb_substr($name, 0, $len)) . '...' : mb_strtoupper($name);
    }

//    
//    
//

    public static function getPastasConsulta() {
        return Pasta::find()
            ->andWhere('id_pasta is null')
            ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE, 'tipo' => "CONSULTA"])->orderBy('nome ASC')->all();
    }

    public static function getConsultasConsulta() {
        return Consulta::find()
            ->joinWith('indicador')
            ->andWhere('bpbi_consulta.id_pasta is null')
            ->andWhere([
                'bpbi_consulta.is_ativo' => TRUE,
                'bpbi_consulta.is_excluido' => FALSE,
                'bpbi_indicador.is_ativo' => TRUE,
                'bpbi_indicador.is_excluido' => FALSE
            ])->orderBy('nome ASC')->all();
    }

    public static function getContentConsulta($perfil, $pastas = "", $consultas = "") {
        $menus = "";
        $checked = ($perfil && $perfil->acesso_bi && $perfil->is_admin) ? 'checked' : '';

        if ($consultas) {
            foreach ($consultas as $consulta) {

                if($perfil && $perfil->acesso_bi && !$perfil->is_admin)
                {
                    $permissao = ConsultaPermissao::find()->andWhere([
                        'is_ativo' => TRUE,
                        'is_excluido' => FALSE,
                        'id_permissao' => 1,
                        'id_perfil' => $perfil->id,
                        'id_consulta' => $consulta->id
                    ])->exists();

                    $checked = ($permissao) ? 'checked' : '';
                }

                $id_pasta = ($consulta->id_pasta) ? $consulta->id_pasta : 'jg';
                $menus .= "<li><label><input id=\"c-{$consulta->id}\" {$checked} name=\"AdminPerfil[permissoesConteudo][consulta][c][{$id_pasta}][{$consulta->id}]\" class=\"hummingbirdNoParent\" data-class=\"consulta\" data-id=\"{$consulta->id}\" type=\"checkbox\" /> <i class=\"bp-consulta\"></i> " . mb_strtoupper(addslashes($consulta->nome)) . "</label></li>";
            }
        }

        if ($pastas) {
            foreach ($pastas as $pasta) {

                $subpastas = $pasta->pastas;
                $subconsultas = $pasta->consultas;

                if ($subpastas) {

                    if($perfil && $perfil->acesso_bi && !$perfil->is_admin)
                    {
                        $checked = (is_array($perfil->bpbi_menu_consulta) && in_array($pasta->id, $perfil->bpbi_menu_consulta)) ? 'checked' : '';
                    }

                    $menus .= "<li><label><input id=\"p-{$pasta->id}\" {$checked} name=\"AdminPerfil[permissoesConteudo][consulta][p][{$pasta->id}][1]\" data-class=\"pasta\" data-id=\"{$pasta->id}\" type=\"checkbox\" /> <i class=\"bp-Folder\"></i> " . mb_strtoupper(addslashes($pasta->nome))
                        . "</label><ul>" . self::getContentConsulta($perfil, $subpastas, $subconsultas) . "</ul></li>";
                } elseif ($subconsultas) {
                    $children = "";

                    foreach ($subconsultas as $consulta) {
                        if ($consulta->indicador->is_ativo || !$consulta->indicador->is_excluido) {

                            if($perfil && $perfil->acesso_bi && !$perfil->is_admin)
                            {
                                $permissao = ConsultaPermissao::find()->andWhere([
                                    'is_ativo' => TRUE,
                                    'is_excluido' => FALSE,
                                    'id_permissao' => 1,
                                    'id_perfil' => $perfil->id,
                                    'id_consulta' => $consulta->id
                                ])->exists();

                                $checked = ($permissao) ? 'checked' : '';
                            }

                            $id_pasta = ($consulta->id_pasta) ? $consulta->id_pasta : 'jg';
                            $children .= "<li><label><input id=\"c-{$consulta->id}\" {$checked} class=\"hummingbirdNoParent\" name=\"AdminPerfil[permissoesConteudo][consulta][c][{$id_pasta}][{$consulta->id}]\" data-class=\"consulta\" data-id=\"{$consulta->id}\" type=\"checkbox\" /> <i class=\"bp-consulta\"></i> " . mb_strtoupper(addslashes($consulta->nome)) . "</label></li>";
                        }
                    }

                    if($perfil && $perfil->acesso_bi && !$perfil->is_admin)
                    {
                        $checked = (is_array($perfil->bpbi_menu_consulta) && in_array($pasta->id, $perfil->bpbi_menu_consulta)) ? 'checked' : '';
                    }

                    $menus .= "<li><label><input id=\"p-{$pasta->id}\" {$checked} name=\"AdminPerfil[permissoesConteudo][consulta][p][{$pasta->id}][1]\" data-class=\"pasta\" data-id=\"{$pasta->id}\" type=\"checkbox\" /> <i class=\"bp-Folder\"></i> " . mb_strtoupper(addslashes($pasta->nome))
                        . "</label><ul>{$children}</ul></li>";
                } else {

                    if($perfil && $perfil->acesso_bi && !$perfil->is_admin)
                    {
                        $checked = (is_array($perfil->bpbi_menu_consulta) && in_array($pasta->id, $perfil->bpbi_menu_consulta)) ? 'checked' : '';
                    }

                    $menus .= "<li><label><input id=\"p-{$pasta->id}\" {$checked} name=\"AdminPerfil[permissoesConteudo][consulta][p][{$pasta->id}][1]\" class=\"hummingbirdNoParent\" data-class=\"pasta\" data-id=\"{$pasta->id}\" type=\"checkbox\" /> <i class=\"bp-Folder\"></i>" . mb_strtoupper(addslashes($pasta->nome))
                        . "</label></li>";
                }
            }
        }

        return $menus;
    }

    public static function getMenuConsulta($perfil = null) {
        $pastas = self::getPastasConsulta();
        $consultas = self::getConsultasConsulta();
        $data = self::getContentConsulta($perfil, $pastas, $consultas);

        return "<ul id=\"treeviewc\">{$data}</ul>";
    }

//
//
//

    public static function getPastasPainel() {
        return Pasta::find()
            ->andWhere('id_pasta is null')
            ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE, 'tipo' => "PAINEL"])->orderBy('nome ASC')->all();;
    }

    public static function getPaineisPainel() {
        return Painel::find()
            ->andWhere('id_pasta is null')
            ->andWhere([
                'is_ativo' => TRUE,
                'is_excluido' => FALSE,
            ])->orderBy('nome ASC')->all();;
    }

    public static function getContentPainel($perfil = null, $pastas = "", $paineis = "") {
        $menus = "";
        $checked = ($perfil && $perfil->acesso_bi && $perfil->is_admin) ? 'checked' : '';

        if ($paineis) {
            foreach ($paineis as $painel) {
                if($perfil && $perfil->acesso_bi && !$perfil->is_admin)
                {
                    $permissao = PainelPermissao::find()->andWhere([
                        'is_ativo' => TRUE,
                        'is_excluido' => FALSE,
                        'id_permissao' => 1,
                        'id_perfil' => $perfil->id,
                        'id_painel' => $painel->id
                    ])->exists();

                    $checked = ($permissao) ? 'checked' : '';
                }

                $id_pasta = ($painel->id_pasta) ? $painel->id_pasta : 'jg';
                $menus .= "<li><label><input id=\"p-{$painel->id}\" {$checked} class=\"hummingbirdNoParent\" name=\"AdminPerfil[permissoesConteudo][painel][c][{$id_pasta}][{$painel->id}]\" data-class=\"painel\" data-id=\"{$painel->id}\" type=\"checkbox\" /> <i class=\"bp-painel\"></i> " . mb_strtoupper(addslashes($painel->nome)) . "</label></li>";
            }
        }

        if ($pastas) {
            foreach ($pastas as $pasta) {
                $subpastas = $pasta->pastas;
                $subpaineis = $pasta->paineis;

                if ($subpastas) {

                    if($perfil && $perfil->acesso_bi && !$perfil->is_admin)
                    {
                        $checked = (is_array($perfil->bpbi_menu_painel) && in_array($pasta->id, $perfil->bpbi_menu_painel)) ? 'checked' : '';
                    }

                    $menus .= "<li><label><input id=\"p-{$pasta->id}\" {$checked} name=\"AdminPerfil[permissoesConteudo][painel][p][{$pasta->id}][1]\" data-class=\"pasta\" data-id=\"{$pasta->id}\" type=\"checkbox\" /> <i class=\"bp-Folder\"></i>" . mb_strtoupper(addslashes($pasta->nome))
                        . "</label><ul>" . self::getContentPainel($perfil, $subpastas, $subpaineis) . "</ul></li>";
                } elseif ($subpaineis) {
                    $children = "";

                    foreach ($subpaineis as $painel) {
                        if ($painel->is_ativo || !$painel->is_excluido) {

                            if($perfil && $perfil->acesso_bi && !$perfil->is_admin)
                            {
                                $permissao = PainelPermissao::find()->andWhere([
                                    'is_ativo' => TRUE,
                                    'is_excluido' => FALSE,
                                    'id_permissao' => 1,
                                    'id_perfil' => $perfil->id,
                                    'id_painel' => $painel->id
                                ])->exists();

                                $checked = ($permissao) ? 'checked' : '';
                            }

                            $id_pasta = ($painel->id_pasta) ? $painel->id_pasta : 'jg';
                            $children .= "<li><label><input id=\"p-{$painel->id}\" {$checked} name=\"AdminPerfil[permissoesConteudo][painel][c][{$id_pasta}][{$painel->id}]\" class=\"hummingbirdNoParent\" data-class=\"painel\" data-id=\"{$painel->id}\" type=\"checkbox\" /> <i class=\"bp-painel\"></i> " . mb_strtoupper(addslashes($painel->nome)) . "</label></li>";
                        }
                    }

                    if($perfil && $perfil->acesso_bi && !$perfil->is_admin)
                    {
                        $checked = (is_array($perfil->bpbi_menu_painel) && in_array($pasta->id, $perfil->bpbi_menu_painel)) ? 'checked' : '';
                    }

                    $menus .= "<li><label><input id=\"p-{$pasta->id}\" {$checked} name=\"AdminPerfil[permissoesConteudo][painel][p][{$pasta->id}][1]\" data-class=\"pasta\" data-id=\"{$pasta->id}\" type=\"checkbox\" /> <i class=\"bp-Folder\"></i>" . mb_strtoupper(addslashes($pasta->nome))
                        . "</label><ul>{$children}</ul></li>";
                } else {

                    if($perfil && $perfil->acesso_bi && !$perfil->is_admin)
                    {
                        $checked = (is_array($perfil->bpbi_menu_painel) && in_array($pasta->id, $perfil->bpbi_menu_painel)) ? 'checked' : '';
                    }

                    $menus .= "<li><label><input id=\"p-{$pasta->id}\" {$checked} class=\"hummingbirdNoParent\" name=\"AdminPerfil[permissoesConteudo][painel][p][{$pasta->id}][1]\" data-class=\"pasta\" data-id=\"{$pasta->id}\" type=\"checkbox\" /> <i class=\"bp-Folter\"></i> " . mb_strtoupper(addslashes($pasta->nome))
                        . "</label></li>";
                }
            }
        }

        return $menus;
    }

    public static function getMenuPainel($perfil = null) {
        $pastas = self::getPastasPainel();
        $paineis = self::getPaineisPainel();
        $data = self::getContentPainel($perfil, $pastas, $paineis);

        return "<ul id=\"treeviewp\">{$data}</ul>";
    }

    //
//
//
    public static function getPastasRelatorio() {
        return Pasta::find()
            ->andWhere('id_pasta is null')
            ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE, 'tipo' => "RELATORIO"])->orderBy('nome ASC')->all();;
    }

    public static function getRelatoriosRelatorio() {
        return RelatorioData::find()
            ->andWhere('id_pasta is null')
            ->andWhere([
                'is_ativo' => TRUE,
                'is_excluido' => FALSE,
            ])->orderBy('nome ASC')->all();;
    }

    public static function getContentRelatorio($perfil = null, $pastas = "", $relatorios = "") {
        $menus = "";
        $checked = ($perfil && $perfil->acesso_bi && $perfil->is_admin) ? 'checked' : '';
        if ($relatorios) {
            foreach ($relatorios as $relatorio) {
                if($perfil && $perfil->acesso_bi && !$perfil->is_admin)
                {
                    $permissao = RelatorioPermissao::find()->andWhere([
                        'is_ativo' => TRUE,
                        'is_excluido' => FALSE,
                        'id_permissao' => 1,
                        'id_perfil' => $perfil->id,
                        'id_relatorio_data' => $relatorio->id
                    ])->exists();
                    $checked = ($permissao) ? 'checked' : '';
                }
                $id_pasta = ($relatorio->id_pasta) ? $relatorio->id_pasta : 'jg';
                $menus .= "<li><label><input id=\"p-{$relatorio->id}\" {$checked} class=\"hummingbirdNoParent\" name=\"AdminPerfil[permissoesConteudo][relatorio][c][{$id_pasta}][{$relatorio->id}]\" data-class=\"relatorio\" data-id=\"{$relatorio->id}\" type=\"checkbox\" /> <i class=\"bp-relatorio\"></i> " . mb_strtoupper(addslashes($relatorio->nome)) . "</label></li>";
            }
        }
        if ($pastas) {
            foreach ($pastas as $pasta) {
                $subpastas = $pasta->pastas;
                $subrelatorios = $pasta->relatorios;
                if ($subpastas) {
                    if($perfil && $perfil->acesso_bi && !$perfil->is_admin)
                    {
                        $checked = (is_array($perfil->bpbi_menu_relatorio) && in_array($pasta->id, $perfil->bpbi_menu_relatorio)) ? 'checked' : '';
                    }
                    $menus .= "<li><label><input id=\"p-{$pasta->id}\" {$checked} name=\"AdminPerfil[permissoesConteudo][relatorio][p][{$pasta->id}][1]\" data-class=\"pasta\" data-id=\"{$pasta->id}\" type=\"checkbox\" /> <i class=\"bp-Folder\"></i>" . mb_strtoupper(addslashes($pasta->nome))
                        . "</label><ul>" . self::getContentRelatorio($perfil, $subpastas, $subrelatorios) . "</ul></li>";
                } elseif ($subrelatorios) {
                    $children = "";
                    foreach ($subrelatorios as $relatorio) {
                        if ($relatorio->is_ativo || !$relatorio->is_excluido) {
                            if($perfil && $perfil->acesso_bi && !$perfil->is_admin)
                            {
                                $permissao = RelatorioPermissao::find()->andWhere([
                                    'is_ativo' => TRUE,
                                    'is_excluido' => FALSE,
                                    'id_permissao' => 1,
                                    'id_perfil' => $perfil->id,
                                    'id_relatorio_data' => $relatorio->id
                                ])->exists();
                                $checked = ($permissao) ? 'checked' : '';
                            }
                            $id_pasta = ($relatorio->id_pasta) ? $relatorio->id_pasta : 'jg';
                            $children .= "<li><label><input id=\"p-{$relatorio->id}\" {$checked} name=\"AdminPerfil[permissoesConteudo][relatorio][c][{$id_pasta}][{$relatorio->id}]\" class=\"hummingbirdNoParent\" data-class=\"relatorio\" data-id=\"{$relatorio->id}\" type=\"checkbox\" /> <i class=\"bp-relatorio\"></i> " . mb_strtoupper(addslashes($relatorio->nome)) . "</label></li>";
                        }
                    }
                    if($perfil && $perfil->acesso_bi && !$perfil->is_admin)
                    {
                        $checked = (is_array($perfil->bpbi_menu_relatorio) && in_array($pasta->id, $perfil->bpbi_menu_relatorio)) ? 'checked' : '';
                    }
                    $menus .= "<li><label><input id=\"p-{$pasta->id}\" {$checked} name=\"AdminPerfil[permissoesConteudo][relatorio][p][{$pasta->id}][1]\" data-class=\"pasta\" data-id=\"{$pasta->id}\" type=\"checkbox\" /> <i class=\"bp-Folder\"></i>" . mb_strtoupper(addslashes($pasta->nome))
                        . "</label><ul>{$children}</ul></li>";
                } else {
                    if($perfil && $perfil->acesso_bi && !$perfil->is_admin)
                    {
                        $checked = (is_array($perfil->bpbi_menu_relatorio) && in_array($pasta->id, $perfil->bpbi_menu_relatorio)) ? 'checked' : '';
                    }
                    $menus .= "<li><label><input id=\"p-{$pasta->id}\" {$checked} class=\"hummingbirdNoParent\" name=\"AdminPerfil[permissoesConteudo][relatorio][p][{$pasta->id}][1]\" data-class=\"pasta\" data-id=\"{$pasta->id}\" type=\"checkbox\" /> <i class=\"bp-Folter\"></i> " . mb_strtoupper(addslashes($pasta->nome))
                        . "</label></li>";
                }
            }
        }
        return $menus;
    }
    public static function getMenuRelatorio($perfil = null) {
        $pastas = self::getPastasRelatorio();
        $relatorios = self::getRelatoriosRelatorio();
        $data = self::getContentRelatorio($perfil, $pastas, $relatorios);
        return "<ul id=\"treeviewp\">{$data}</ul>";
    }

}
