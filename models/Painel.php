<?php

namespace app\models;

use Yii;
use app\magic\MobileMagic;

class Painel extends \yii\db\ActiveRecord {

    public $permissoes;

    public static function tableName() {
        return 'bpbi_painel';
    }

    public function rules() {
        return [
            [['id_pasta', 'created_by', 'updated_by'], 'integer'],
            [['nome'], 'required'],
            [['descricao', 'javascript'], 'string'],
            [['condicao', 'data', 'created_at', 'updated_at', 'privado', 'is_ativo', 'is_excluido', 'permissoes'], 'safe'],
            [['nome'], 'string', 'max' => 255],
            [['id_pasta'], 'exist', 'skipOnError' => true, 'targetClass' => Pasta::className(), 'targetAttribute' => ['id_pasta' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'id_pasta' => Yii::t('app', 'geral.pasta'),
                    'nome' => Yii::t('app', 'geral.nome'),
                    'descricao' => Yii::t('app', 'geral.descricao'),
                    'data' => Yii::t('app', 'metadado.data'),
                    'condicao' => Yii::t('app', 'consulta.condicao'),
                    'javascript' => Yii::t('app', 'metadado.javascript'),
                    'privado' => Yii::t('app', 'metadado.privado'),
                    'is_ativo' => Yii::t('app', 'geral.is_ativo'),
                    'is_excluido' => Yii::t('app', 'geral.is_excluido'),
                    'created_at' => Yii::t('app', 'geral.created_at'),
                    'updated_at' => Yii::t('app', 'geral.updated_at'),
                    'created_by' => Yii::t('app', 'geral.created_by'),
                    'updated_by' => Yii::t('app', 'geral.updated_by'),
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
                            'data', 'condicao'
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

    public function getPasta() {
        return $this->hasOne(Pasta::className(), ['id' => 'id_pasta']);
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

        if($this->privado)
        {
            $name .= " <i class='fa fa-lock'></i>";
        }

        return $name;
    }

    public function getPermissoes() {
        $perfis = AdminPerfil::find()->andWhere([
                    'is_ativo' => TRUE,
                    'is_excluido' => FALSE,
                    'acesso_bi' => TRUE,
                    'is_admin' => FALSE])->orderBy('nome ASC')->all();

        $modelPermissoes = PermissaoPainel::find()->andWhere(['is_ativo' => TRUE,
                    'is_excluido' => FALSE])->orderBy('nome ASC')->all();

        $this->permissoes = [];

        if ($perfis) {
            foreach ($perfis as $perfil) {
                if ($modelPermissoes) {
                    foreach ($modelPermissoes as $modelPermissao) {
                        $value = PainelPermissao::find()->andWhere([
                                    'is_ativo' => TRUE,
                                    'is_excluido' => FALSE,
                                    'id_painel' => $this->id,
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

    public function duplicar($painel) {
        $this->id_pasta = $painel->id_pasta;
        $this->nome = $painel->nome;
        $this->data = $painel->data;
        $this->descricao = $painel->descricao;
        $this->javascript = $painel->javascript;
        $this->privado = $painel->privado;
        $this->condicao = $painel->condicao;

        if ($this->save()) {
            $permissoes = PainelPermissao::find()->andWhere([
                        'id_painel' => $painel->id,
                        'is_ativo' => TRUE,
                        'is_excluido' => FALSE
                    ])->all();

            if ($permissoes) {
                foreach ($permissoes as $permissao) {
                    $novaPermissao = new PainelPermissao();
                    $novaPermissao->id_painel = $this->id;
                    $novaPermissao->id_permissao = $permissao->id_permissao;
                    $novaPermissao->id_perfil = $permissao->id_perfil;
                    $novaPermissao->save();
                }
            }

            return true;
        } else {
            return false;
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
                            $cubo = (isset($dadosCaixa['cubo']) && !empty($dadosCaixa['cubo'])) ? $dadosCaixa['cubo'] : null;
                            $field = (isset($dadosCaixa['field']) && !empty($dadosCaixa['field'])) ? $dadosCaixa['field'] : null;
                            $type = (isset($dadosCaixa['type']) && !empty($dadosCaixa['type'])) ? $dadosCaixa['type'] : null;

                            if ($field && $type && $cubo) {
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
