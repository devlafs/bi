<?php

namespace app\magic;

use app\models\RelatorioData;
use Yii;
use app\models\Pasta;
use app\models\Consulta;
use app\models\Painel;
use app\models\Relatorio;
use yii\db\Query;

class MenuMagic {

    public static function updateMenus() {
        self::updatePastasConsulta();
        self::updateConsultasConsulta();
        self::updatePastasPainel();
        self::updatePaineisPainel();
        self::updatePastasRelatorio();
        self::updateRelatoriosRelatorio();
    }

    public static function getCuttedName($name) {
        $len = 35;
        return (strlen($name) > $len) ? mb_strtoupper(mb_substr($name, 0, $len)) . '...' : mb_strtoupper($name);
    }

//
//
//

    public static function updatePastasConsulta() {
        $cache = Yii::$app->cache;
        $user = Yii::$app->user;
        $user_id = $user->identity->id;
        $perfil = $user->identity->perfil;

        $key = 'pastas_consulta_' . $user_id;

        if ($perfil->is_admin) {
            $data = Pasta::find()
                ->andWhere('id_pasta is null')
                ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE, 'tipo' => "CONSULTA"])->orderBy('nome ASC')->all();
        } else {
            if ($perfil->bpbi_menu_consulta) {
                $id_menus = implode(",", $perfil->bpbi_menu_consulta);
                $orWhere = "(created_by = {$user_id} OR id IN({$id_menus}))";
            } else {
                $orWhere = "created_by = {$user_id}";
            }

            $data = Pasta::find()
                ->andWhere('id_pasta is null')
                ->andWhere($orWhere)
                ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE, 'tipo' => "CONSULTA"])->orderBy('nome ASC')->all();
        }

        $cache->set($key, $data);

        return $data;
    }

    public static function getPastasConsulta() {
        $cache = Yii::$app->cache;
        $user_id = Yii::$app->user->identity->id;
        $key = 'pastas_consulta_' . $user_id;

        $data = $cache->get($key);

        if ($data === false) {
            $data = self::updatePastasConsulta();
        }

        return $data;
    }

    public static function updateConsultasConsulta() {
        $cache = Yii::$app->cache;
        $user = Yii::$app->user;
        $user_id = $user->identity->id;
        $perfil = $user->identity->perfil;
        $key = 'consultas_' . $user_id;

        if ($perfil->is_admin) {
            $data = Consulta::find()
                ->joinWith('indicador')
                ->andWhere('bpbi_consulta.id_pasta is null')
                ->andWhere([
                    'bpbi_consulta.is_ativo' => TRUE,
                    'bpbi_consulta.is_excluido' => FALSE,
                    'bpbi_indicador.is_ativo' => TRUE,
                    'bpbi_indicador.is_excluido' => FALSE
                ])->orderBy('nome ASC')->all();
        } else {
            $subQuery = (new Query())->select('*')->from('bpbi_consulta_permissao permissao')
                ->andWhere('bpbi_consulta.id = permissao.id_consulta')
                ->andWhere('permissao.id_permissao = 1') // Permissão de Visualizar
                ->andWhere('permissao.id_perfil = ' . $perfil->id)
                ->andWhere([
                    'permissao.is_ativo' => TRUE,
                    'permissao.is_excluido' => FALSE,
                ]);

            $data = Consulta::find()
                ->joinWith('indicador')
                ->andWhere('bpbi_consulta.id_pasta is null')
                ->andWhere(['or', ['exists', $subQuery], ['bpbi_consulta.created_by' => $user_id]])
                ->andWhere([
                    'bpbi_consulta.is_ativo' => TRUE,
                    'bpbi_consulta.is_excluido' => FALSE,
                    'bpbi_indicador.is_ativo' => TRUE,
                    'bpbi_indicador.is_excluido' => FALSE
                ])->orderBy('nome ASC')->all();
        }

        $cache->set($key, $data);

        return $data;
    }

    public static function getConsultasConsulta() {
        $cache = Yii::$app->cache;
        $user_id = Yii::$app->user->identity->id;
        $key = 'consultas_' . $user_id;

        $data = $cache->get($key);

        if ($data === false) {
            $data = self::updateConsultasConsulta();
        }

        return $data;
    }

    public static function getContentConsulta($pastas = [], $consultas = []) {
        $user = Yii::$app->user;
        $user_id = $user->identity->id;
        $perfil = $user->identity->perfil;
        $menus = [];

        if ($consultas) {
            foreach ($consultas as $consulta) {
                $menus[] = ['id' => 'consulta_' . $consulta->id, 'state' => $consulta->id, 'name' => '<a href=\consulta\visualizar\\' . $consulta->id . '><span class=\"mr-2\"><i class=\"bp-consulta\"></i></span>  ' . self::getCuttedName($consulta->nome) . "</a>", 'title' => mb_strtoupper($consulta->nome), 'class' => 'consulta'];
            }
        }

        if ($pastas) {
            foreach ($pastas as $pasta) {
                if ($perfil->is_admin) {
                    $subpastas = $pasta->pastas;
                } else {
                    if ($perfil->bpbi_menu_consulta) {
                        $id_menus = implode(",", $perfil->bpbi_menu_consulta);
                        $orWhere = "(created_by = {$user_id} OR id IN({$id_menus}))";
                    } else {
                        $orWhere = "created_by = {$user_id}";
                    }

                    $subpastas = Pasta::find()
                        ->andWhere($orWhere)
                        ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE, 'id_pasta' => $pasta->id, 'tipo' => "CONSULTA"])
                        ->orderBy('nome ASC')->all();
                }

                $subQuery = (new Query())->select('*')->from('bpbi_consulta_permissao permissao')
                    ->andWhere('bpbi_consulta.id = permissao.id_consulta')
                    ->andWhere('permissao.id_permissao = 1') // Permissão de Visualizar
                    ->andWhere('permissao.id_perfil = ' . $perfil->id)
                    ->andWhere([
                        'permissao.is_ativo' => TRUE,
                        'permissao.is_excluido' => FALSE,
                    ]);

                $subconsultas = ($perfil->is_admin) ? $pasta->consultas : Consulta::find()
                    ->joinWith('indicador')
                    ->andWhere(['or', ['exists', $subQuery], ['bpbi_consulta.created_by' => $user_id]])
                    ->andWhere([
                        'id_pasta' => $pasta->id,
                        'bpbi_consulta.is_ativo' => TRUE,
                        'bpbi_consulta.is_excluido' => FALSE,
                        'bpbi_indicador.is_ativo' => TRUE,
                        'bpbi_indicador.is_excluido' => FALSE
                    ])->orderBy('nome ASC')->all();

                if ($subpastas) {
                    $menus[] = ['id' => 'pasta_' . $pasta->id, 'state' => $pasta->id, 'name' => self::getCuttedName($pasta->nome), 'title' => mb_strtoupper($pasta->nome), 'children' => self::getContentConsulta($subpastas, $subconsultas), 'class' => 'pasta'];
                } elseif ($subconsultas) {
                    $children = [];

                    foreach ($subconsultas as $consulta) {
                        if ($consulta->indicador->is_ativo || !$consulta->indicador->is_excluido) {
                            $children[] = ['id' => 'consulta_' . $consulta->id, 'state' => $consulta->id, 'name' => '<a href=\consulta\visualizar\\' . $consulta->id . '><span class=\"mr-2\"><i class=\"bp-consulta\"></i></span>  ' . self::getCuttedName($consulta->nome) . "</a>", 'title' => mb_strtoupper($consulta->nome), 'class' => 'consulta'];
                        }
                    }

                    $menus[] = ['id' => 'pasta_' . $pasta->id, 'state' => $pasta->id, 'name' => self::getCuttedName($pasta->nome), 'title' => mb_strtoupper($pasta->nome), 'children' => $children, 'class' => 'pasta'];
                } else {
                    $menus[] = ['id' => 'pasta_' . $pasta->id, 'state' => $pasta->id, 'name' => self::getCuttedName($pasta->nome), 'title' => mb_strtoupper($pasta->nome), 'children' => [], 'class' => 'pasta'];
                }
            }
        }

        return $menus;
    }

    public static function getMenuConsulta() {
        $pastas = self::getPastasConsulta();
        $consultas = self::getConsultasConsulta();
        $data = self::getContentConsulta($pastas, $consultas);

        return $data;
    }

    public static function getQuantidadeConsulta() {
        $data_pastas = self::getPastasConsulta();
        $qtd_pastas = ($data_pastas) ? count($data_pastas) : 0;
        $data_consultas = self::getConsultasConsulta();
        $qtd_consultas = ($data_consultas) ? count($data_consultas) : 0;

        return $qtd_pastas + $qtd_consultas;
    }

//
//
//

    public static function updatePastasPainel() {
        $cache = Yii::$app->cache;
        $user = Yii::$app->user;
        $user_id = $user->identity->id;
        $perfil = $user->identity->perfil;
        $key = 'pastas_painel_' . $user_id;

        if ($perfil->is_admin) {
            $data = Pasta::find()
                ->andWhere('id_pasta is null')
                ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE, 'tipo' => "PAINEL"])->orderBy('nome ASC')->all();
        } else {
            if ($perfil->bpbi_menu_painel) {
                $id_menus = implode(",", $perfil->bpbi_menu_painel);
                $orWhere = "(created_by = {$user_id} OR id IN({$id_menus}))";
            } else {
                $orWhere = "created_by = {$user_id}";
            }

            $data = Pasta::find()
                ->andWhere('id_pasta is null')
                ->andWhere($orWhere)
                ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE, 'tipo' => "PAINEL"])->orderBy('nome ASC')->all();
        }

        $cache->set($key, $data);

        return $data;
    }

    public static function getPastasPainel() {
        $cache = Yii::$app->cache;
        $user_id = Yii::$app->user->identity->id;
        $key = 'pastas_painel_' . $user_id;

        $data = $cache->get($key);

        if ($data === false) {
            $data = self::updatePastasPainel();
        }

        return $data;
    }

    public static function updatePaineisPainel() {
        $cache = Yii::$app->cache;
        $user = Yii::$app->user;
        $user_id = $user->identity->id;
        $perfil = $user->identity->perfil;
        $key = 'paineis_' . $user_id;

        if ($perfil->is_admin) {
            $data = Painel::find()
                ->andWhere('id_pasta is null')
                ->andWhere([
                    'is_ativo' => TRUE,
                    'is_excluido' => FALSE,
                ])->orderBy('nome ASC')->all();
        } else {
            $subQuery = (new Query())->select('*')->from('bpbi_painel_permissao permissao')
                ->andWhere('bpbi_painel.id = permissao.id_painel')
                ->andWhere('permissao.id_permissao = 1') // Permissão de Visualizar
                ->andWhere('permissao.id_perfil = ' . $perfil->id)
                ->andWhere([
                    'permissao.is_ativo' => TRUE,
                    'permissao.is_excluido' => FALSE,
                ]);

            $data = Painel::find()
                ->andWhere('id_pasta is null')
                ->andWhere(['or', ['exists', $subQuery], ['bpbi_painel.created_by' => $user_id]])
                ->andWhere([
                    'is_ativo' => TRUE,
                    'is_excluido' => FALSE,
                ])->orderBy('nome ASC')->all();
        }

        $cache->set($key, $data);

        return $data;
    }

    public static function getPaineisPainel() {
        $cache = Yii::$app->cache;
        $user_id = Yii::$app->user->identity->id;
        $key = 'paineis_' . $user_id;

        $data = $cache->get($key);

        if ($data === false) {
            $data = self::updatePaineisPainel();
        }

        return $data;
    }

    public static function getContentPainel($pastas = [], $paineis = []) {
        $user = Yii::$app->user;
        $user_id = $user->identity->id;
        $perfil = $user->identity->perfil;
        $menus = [];

        if ($paineis) {
            foreach ($paineis as $painel) {
                $menus[] = ['id' => 'painel_' . $painel->id, 'state' => $painel->id, 'name' => '<a href=\painel\visualizar\\' . $painel->id . '><span class=\"mr-2\"><i class=\"bp-painel\"></i></span>  ' . self::getCuttedName($painel->nome) . "</a>", 'title' => mb_strtoupper($painel->nome), 'class' => 'painel'];
            }
        }

        if ($pastas) {
            foreach ($pastas as $pasta) {
                if ($perfil->is_admin) {
                    $subpastas = $pasta->pastas;
                } else {
                    if ($perfil->bpbi_menu_painel) {
                        $id_menus = implode(",", $perfil->bpbi_menu_painel);
                        $orWhere = "(created_by = {$user_id} OR id IN({$id_menus}))";
                    } else {
                        $orWhere = "created_by = {$user_id}";
                    }

                    $subpastas = Pasta::find()
                        ->andWhere($orWhere)
                        ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE, 'id_pasta' => $pasta->id, 'tipo' => "PAINEL"])
                        ->orderBy('nome ASC')->all();
                }

                $subQuery = (new Query())->select('*')->from('bpbi_painel_permissao permissao')
                    ->andWhere('bpbi_painel.id = permissao.id_painel')
                    ->andWhere('permissao.id_permissao = 1') // Permissão de Visualizar
                    ->andWhere('permissao.id_perfil = ' . $perfil->id)
                    ->andWhere([
                        'permissao.is_ativo' => TRUE,
                        'permissao.is_excluido' => FALSE,
                    ]);

                $subpaineis = ($perfil->is_admin) ? $pasta->paineis : Painel::find()
                    ->andWhere(['or', ['exists', $subQuery], ['bpbi_painel.created_by' => $user_id]])
                    ->andWhere([
                        'id_pasta' => $pasta->id,
                        'is_ativo' => TRUE,
                        'is_excluido' => FALSE,
                    ])->orderBy('nome ASC')->all();

                if ($subpastas) {
                    $menus[] = ['id' => 'pasta_' . $pasta->id, 'state' => $pasta->id, 'name' => self::getCuttedName($pasta->nome), 'title' => mb_strtoupper($pasta->nome), 'children' => self::getContentPainel($subpastas, $subpaineis), 'class' => 'pasta'];
                } elseif ($subpaineis) {
                    $children = [];

                    foreach ($subpaineis as $painel) {
                        if ($painel->is_ativo || !$painel->is_excluido) {
                            $children[] = ['id' => 'painel_' . $painel->id, 'state' => $painel->id, 'name' => '<a href=\painel\visualizar\\' . $painel->id . '><span class=\"mr-2\"><i class=\"bp-painel\"></i></span>  ' . self::getCuttedName($painel->nome) . "</a>", 'title' => mb_strtoupper($painel->nome), 'class' => 'painel'];
                        }
                    }

                    $menus[] = ['id' => 'pasta_' . $pasta->id, 'state' => $pasta->id, 'name' => self::getCuttedName($pasta->nome), 'title' => mb_strtoupper($pasta->nome), 'children' => $children, 'class' => 'pasta'];
                } else {
                    $menus[] = ['id' => 'pasta_' . $pasta->id, 'state' => $pasta->id, 'name' => self::getCuttedName($pasta->nome), 'title' => mb_strtoupper($pasta->nome), 'children' => [], 'class' => 'pasta'];
                }
            }
        }

        return $menus;
    }

    public static function getMenuPainel() {
        $pastas = self::getPastasPainel();
        $paineis = self::getPaineisPainel();
        $data = self::getContentPainel($pastas, $paineis);

        return $data;
    }

    public static function getQuantidadePainel() {
        $data_pastas = self::getPastasPainel();
        $qtd_pastas = ($data_pastas) ? count($data_pastas) : 0;
        $data_paineis = self::getPaineisPainel();
        $qtd_paineis = ($data_paineis) ? count($data_paineis) : 0;

        return $qtd_pastas + $qtd_paineis;
    }

//
//
//

    public static function updatePastasRelatorio() {
        $cache = Yii::$app->cache;
        $user = Yii::$app->user;
        $user_id = $user->identity->id;
        $perfil = $user->identity->perfil;
        $key = 'pastas_relatorio_' . $user_id;

        if ($perfil->is_admin) {
            $data = Pasta::find()
                ->andWhere('id_pasta is null')
                ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE, 'tipo' => "RELATORIO"])->orderBy('nome ASC')->all();
        } else {
            if ($perfil->bpbi_menu_relatorio) {
                $id_menus = implode(",", $perfil->bpbi_menu_relatorio);
                $orWhere = "(created_by = {$user_id} OR id IN({$id_menus}))";
            } else {
                $orWhere = "created_by = {$user_id}";
            }

            $data = Pasta::find()
                ->andWhere('id_pasta is null')
                ->andWhere($orWhere)
                ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE, 'tipo' => "RELATORIO"])->orderBy('nome ASC')->all();
        }

        $cache->set($key, $data);

        return $data;
    }

    public static function getPastasRelatorio() {
        $cache = Yii::$app->cache;
        $user_id = Yii::$app->user->identity->id;
//        $key = 'pastas_relatorio_' . $user_id;
//
//        $data = $cache->get($key);
//
//        if ($data === false) {
            $data = self::updatePastasRelatorio();
//        }

        return $data;
    }

    public static function updateRelatoriosRelatorio() {
        $cache = Yii::$app->cache;
        $user = Yii::$app->user;
        $user_id = $user->identity->id;
        $perfil = $user->identity->perfil;
        $key = 'relatorios_' . $user_id;

        if ($perfil->is_admin) {
            $data = RelatorioData::find()
                ->andWhere('id_pasta is null')
                ->andWhere([
                    'is_ativo' => TRUE,
                    'is_excluido' => FALSE,
                ])->orderBy('nome ASC')->all();
        } else {
            $subQuery = (new Query())->select('*')->from('bpbi_relatorio_permissao permissao')
                ->andWhere('bpbi_relatorio_data.id = permissao.id_relatorio_data')
                ->andWhere('permissao.id_permissao = 1') // Permissão de Visualizar
                ->andWhere('permissao.id_perfil = ' . $perfil->id)
                ->andWhere([
                    'permissao.is_ativo' => TRUE,
                    'permissao.is_excluido' => FALSE,
                ]);

            $data = RelatorioData::find()
                ->andWhere('id_pasta is null')
                ->andWhere(['or', ['exists', $subQuery], ['bpbi_relatorio_data.created_by' => $user_id]])
                ->andWhere([
                    'is_ativo' => TRUE,
                    'is_excluido' => FALSE,
                ])->orderBy('nome ASC')->all();
        }

        $cache->set($key, $data);

        return $data;
    }

    public static function getRelatoriosRelatorio() {
        $cache = Yii::$app->cache;
        $user_id = Yii::$app->user->identity->id;
        $key = 'relatorios_' . $user_id;

        $data = $cache->get($key);

        if ($data === false) {
            $data = self::updateRelatoriosRelatorio();
        }

        return $data;
    }

    public static function getContentRelatorio($pastas = [], $relatorios = []) {
        $user = Yii::$app->user;
        $user_id = $user->identity->id;
        $perfil = $user->identity->perfil;
        $menus = [];

        if ($relatorios) {
            foreach ($relatorios as $relatorio) {
                $menus[] = ['id' => 'relatorio_' . $relatorio->id, 'state' => $relatorio->id, 'name' => '<a href=\relatorio-data\visualizar\\' . $relatorio->id
                    . '><span class=\"mr-2\"><i class=\"bp-chart--grid\"></i></span>  ' . self::getCuttedName($relatorio->nome) . "</a>", 'title' => mb_strtoupper($relatorio->nome), 'class' => 'relatorio'];
            }
        }

        if ($pastas) {
            foreach ($pastas as $pasta) {
                if ($perfil->is_admin) {
                    $subpastas = $pasta->pastas;
                } else {
                    if ($perfil->bpbi_menu_relatorio) {
                        $id_menus = implode(",", $perfil->bpbi_menu_relatorio);
                        $orWhere = "(created_by = {$user_id} OR id IN({$id_menus}))";
                    } else {
                        $orWhere = "created_by = {$user_id}";
                    }

                    $subpastas = Pasta::find()
                        ->andWhere($orWhere)
                        ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE, 'id_pasta' => $pasta->id, 'tipo' => "RELATORIO"])
                        ->orderBy('nome ASC')->all();
                }

                $subQuery = (new Query())->select('*')->from('bpbi_relatorio_permissao permissao')
                    ->andWhere('bpbi_relatorio_data.id = permissao.id_relatorio_data')
                    ->andWhere('permissao.id_permissao = 1') // Permissão de Visualizar
                    ->andWhere('permissao.id_perfil = ' . $perfil->id)
                    ->andWhere([
                        'permissao.is_ativo' => TRUE,
                        'permissao.is_excluido' => FALSE,
                    ]);

                $subrelatorios = ($perfil->is_admin) ? $pasta->relatorios : RelatorioData::find()
                    ->andWhere(['or', ['exists', $subQuery], ['bpbi_relatorio_data.created_by' => $user_id]])
                    ->andWhere([
                        'id_pasta' => $pasta->id,
                        'is_ativo' => TRUE,
                        'is_excluido' => FALSE,
                    ])->orderBy('nome ASC')->all();

                if ($subpastas) {
                    $menus[] = ['id' => 'pasta_' . $pasta->id, 'state' => $pasta->id, 'name' => self::getCuttedName($pasta->nome), 'title' => mb_strtoupper($pasta->nome), 'children' => self::getContentRelatorio($subpastas, $subrelatorios), 'class' => 'pasta'];
                } elseif ($subrelatorios) {
                    $children = [];

                    foreach ($subrelatorios as $relatorio) {
                        if ($relatorio->is_ativo || !$relatorio->is_excluido) {
                            $children[] = ['id' => 'relatorio_' . $relatorio->id, 'state' => $relatorio->id, 'name' => '<a href=\relatorio-data\visualizar\\'
                                . $relatorio->id . '><span class=\"mr-2\"><i class=\"bp-chart--grid\"></i></span>  ' . self::getCuttedName($relatorio->nome) . "</a>", 'title' => mb_strtoupper($relatorio->nome), 'class' => 'relatorio'];
                        }
                    }

                    $menus[] = ['id' => 'pasta_' . $pasta->id, 'state' => $pasta->id, 'name' => self::getCuttedName($pasta->nome), 'title' => mb_strtoupper($pasta->nome), 'children' => $children, 'class' => 'pasta'];
                } else {
                    $menus[] = ['id' => 'pasta_' . $pasta->id, 'state' => $pasta->id, 'name' => self::getCuttedName($pasta->nome), 'title' => mb_strtoupper($pasta->nome), 'children' => [], 'class' => 'pasta'];
                }
            }
        }

        return $menus;
    }

    public static function getMenuRelatorio() {
        $pastas = self::getPastasRelatorio();
        $relatorios = self::getRelatoriosRelatorio();
        $data = self::getContentRelatorio($pastas, $relatorios);

        return $data;
    }

    public static function getQuantidadeRelatorio() {
        $data_pastas = self::getPastasRelatorio();
        $qtd_pastas = ($data_pastas) ? count($data_pastas) : 0;
        $data_relatorios = self::getRelatoriosRelatorio();
        $qtd_relatorios = ($data_relatorios) ? count($data_relatorios) : 0;

        return $qtd_pastas + $qtd_relatorios;
    }

}
