<?php

namespace app\models;

use app\magic\MobileMagic;
use Yii;

class RelatorioData extends \yii\db\ActiveRecord
{
    public $privado;

    public $permissoes;

    public static function tableName()
    {
        return 'bpbi_relatorio_data';
    }

    public function rules()
    {
        return [
            [['id_relatorio', 'nome'], 'required'],
            [['id_relatorio', 'id_pasta', 'email_externo', 'tempo_expiracao_email', 'created_by', 'updated_by'], 'integer'],
            [['javascript', 'descricao', 'limite'], 'string'],
            [['is_ativo', 'is_excluido', 'created_at', 'updated_at', 'permissoes', 'condicao'], 'safe'],
            [['nome'], 'string', 'max' => 255],
            [['id_pasta'], 'exist', 'skipOnError' => true, 'targetClass' => Pasta::className(), 'targetAttribute' => ['id_pasta' => 'id']],
            [['id_relatorio'], 'exist', 'skipOnError' => true, 'targetClass' => Relatorio::className(), 'targetAttribute' => ['id_relatorio' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_relatorio' => Yii::t('app', 'geral.relatorio'),
            'id_pasta' => Yii::t('app', 'geral.pasta'),
            'nome' => Yii::t('app', 'geral.nome'),
            'descricao' => Yii::t('app', 'geral.descricao'),
            'limite' => Yii::t('app', 'relatorio.limite'),
            'email_externo' => Yii::t('app', 'relatorio.email_externo'),
            'tempo_expiracao_email' => Yii::t('app', 'relatorio.tempo_expiracao_email'),
            'javascript' => Yii::t('app', 'relatorio.javascript'),
            'condicao' => Yii::t('app', 'relatorio.condicao'),
            'is_ativo' => Yii::t('app', 'geral.is_ativo'),
            'is_excluido' => Yii::t('app', 'geral.is_excluido'),
            'created_at' => Yii::t('app', 'geral.created_at'),
            'updated_at' => Yii::t('app', 'geral.updated_at'),
            'executed_at' => Yii::t('app', 'indicador.executed_at'),
            'created_by' => Yii::t('app', 'geral.created_by'),
            'updated_by' => Yii::t('app', 'geral.updated_by')
        ];
    }

    public function behaviors() {
        $behaviors = [
            [
                'class' => \app\behaviors\ChangeLogBehavior::className(),
                'excludedAttributes' => ['updated_at'],
            ],
            'json' =>
                [
                    'class' => 'app\components\JsonBehavior',
                    'attributes' =>
                        [
                            'condicao'
                        ],
                ],
            [
                'class' => \yii\behaviors\BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];

        return array_merge(parent::behaviors(), $behaviors);
    }

    public static function find() {
        return new queries\RelatorioDataQuery(get_called_class());
    }

    public function getPasta()
    {
        return $this->hasOne(Pasta::className(), ['id' => 'id_pasta']);
    }

    public function getRelatorio()
    {
        return $this->hasOne(Relatorio::className(), ['id' => 'id_relatorio']);
    }

    public function getRelatorioPermissaos()
    {
        return $this->hasMany(RelatorioPermissao::className(), ['id_relatorio_data' => 'id']);
    }

    public function getPath($pasta) {
        $nomes = [];

        if ($pasta) {
            if ($pasta->pasta) {
                $nomes = array_merge($nomes, $this->getPath($pasta->pasta));
            }

            $nomes[] = $pasta->nome;
        }

        return $nomes;
    }

    public function getPathName() {
        if (MobileMagic::isMobile()) {
            return $this->nome;
        }

        $paths = $this->getPath($this->pasta);

        $name = '';
        $isset = FALSE;

        foreach ($paths as $index => $pathName) {
            $name .= ($index > 0) ? " <i class='bp-arrow-right'></i> {$pathName}" : " {$pathName}";

            $isset = TRUE;
        }

        $name .= ($isset) ? " <i class='bp-arrow-right'></i> {$this->nome}" : "{$this->nome}";

        return $name;
    }

    public function duplicar($relatorio) {
        $this->id_relatorio = $relatorio->id_relatorio;
        $this->id_pasta = $relatorio->id_pasta;
        $this->nome = $relatorio->nome;
        $this->descricao = $relatorio->descricao;
        $this->condicao = $relatorio->condicao;
        $this->javascript = $relatorio->javascript;
        $this->privado = $relatorio->privado;
        $this->limite = $relatorio->limite;
        $this->email_externo = $relatorio->email_externo;
        $this->tempo_expiracao_email = $relatorio->tempo_expiracao_email;

        if ($this->save()) {
            $permissoes = RelatorioPermissao::find()->andWhere([
                'id_relatorio_data' => $relatorio->id,
                'is_ativo' => TRUE,
                'is_excluido' => FALSE
            ])->all();

            if ($permissoes) {
                foreach ($permissoes as $permissao) {
                    $novaPermissao = new RelatorioPermissao();
                    $novaPermissao->id_relatorio_data = $this->id;
                    $novaPermissao->id_permissao = $permissao->id_permissao;
                    $novaPermissao->id_perfil = $permissao->id_perfil;
                    $novaPermissao->save();
                }
            }

            $itens = RelatorioDataItem::find()->andWhere([
                'id_relatorio_data' => $relatorio->id,
                'is_ativo' => TRUE,
                'is_excluido' => FALSE
            ])->all();

            if ($itens) {
                foreach ($itens as $item) {
                    $novoItem = new RelatorioDataItem();
                    $novoItem->id_relatorio_data = $this->id;
                    $novoItem->id_campo = $item->id_campo;
                    $novoItem->ordem = $item->ordem;
                    $novoItem->parametro = $item->parametro;
                    $novoItem->save();
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function getPermissoes() {
        $perfis = AdminPerfil::find()->andWhere([
            'is_ativo' => TRUE,
            'is_excluido' => FALSE,
            'acesso_bi' => TRUE,
            'is_admin' => FALSE])->orderBy('nome ASC')->all();

        $modelPermissoes = PermissaoRelatorio::find()->andWhere(['is_ativo' => TRUE,
            'is_excluido' => FALSE])->orderBy('nome ASC')->all();

        $this->permissoes = [];

        if ($perfis) {
            foreach ($perfis as $perfil) {
                if ($modelPermissoes) {
                    foreach ($modelPermissoes as $modelPermissao) {
                        $value = RelatorioPermissao::find()->andWhere([
                            'is_ativo' => TRUE,
                            'is_excluido' => FALSE,
                            'id_relatorio_data' => $this->id,
                            'id_perfil' => $perfil->id,
                            'id_permissao' => $modelPermissao->id
                        ])->exists();

                        $this->permissoes[$perfil->id]['nome'] = $perfil->nome;

                        $this->permissoes[$perfil->id]['permissoes'][$modelPermissao->id] = [
                            'attributes' => $modelPermissao->attributes,
                            'value' => $value
                        ];
                    }
                }
            }
        }

        return $this->permissoes;
    }

    public function saveData($data) {
        RelatorioDataItem::deleteAll(['id_relatorio_data' => $this->id]);

        foreach ($data as $column => $value) {
            if ($column == 'nome') {
                $this->nome = $value;
                $this->save();
            } elseif ($column == 'argumento') {
                $ordem = 1;

                foreach ($value as $dados) {
                    $model = new RelatorioDataItem();
                    $model->id_relatorio_data = $this->id;
                    $model->id_campo = $dados['id'];
                    $model->ordem = $ordem;
                    $model->parametro = $column;

                    $model->save();

                    $ordem++;
                }
            } elseif ($column != '_csrf') {
                $ordem = 1;

                foreach ($value as $field_id) {
                    $model = new RelatorioDataItem();
                    $model->id_relatorio_data = $this->id;
                    $model->id_campo = $field_id;
                    $model->ordem = $ordem;
                    $model->parametro = $column;
                    $model->save();

                    $ordem++;
                }
            }
        }
    }

    public function aplicaFiltro($data) {
        $filtro = [];

        if (isset($data['Form'])) {
            $ordemGrupo = 1;

            foreach ($data['Form'] as $dadosGrupo) {
                $pode_pular = false;

                if ($dadosGrupo) {
                    $ordemCaixa = 1;

                    foreach ($dadosGrupo as $dadosCaixa) {
                        if ($dadosCaixa) {
                            $field = (isset($dadosCaixa['field']) && !empty($dadosCaixa['field'])) ? $dadosCaixa['field'] : null;
                            $type = (isset($dadosCaixa['type']) && (!empty($dadosCaixa['type']) || $dadosCaixa['type'] == 0)) ? $dadosCaixa['type'] : null;

                            if (!is_null($field) && !is_null($type)) {
                                $filtro[$ordemGrupo][$ordemCaixa] = $dadosCaixa;

                                $ordemCaixa++;
                                $pode_pular = true;
                            }
                        }
                    }
                }

                if ($pode_pular) {
                    $ordemGrupo++;
                }
            }
        }

        $this->condicao = $filtro;
        $this->save();
    }
}
