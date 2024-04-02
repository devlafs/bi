<?php

namespace app\models;

use Yii;
use yii\bootstrap\Html;

class AdminPerfil extends \yii\db\ActiveRecord {

    public $quantidade_usuarios;
    public $permissoes = [];
    public $permissoesConteudo = [];

    public static function getDb() {
        return Yii::$app->get('userDb');
    }

    public static function tableName() {
        return 'admin_perfil';
    }

    public function rules() {
        return [
            [['nome'], 'required'],
            [['nome'], 'string', 'max' => 30],
            [['bpbi_menu_consulta', 'bpbi_menu_painel', 'bpbi_menu_relatorio', 'permissoes', 'permissoesConteudo', 'quantidade_usuarios', 'descricao', 'acesso_bi',
                'is_admin', 'created_at', 'updated_at', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'safe'],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'nome' => Yii::t('app', 'geral.nome'),
                    'quantidade_usuarios' => Yii::t('app', 'perfil.quantidade_usuarios'),
                    'descricao' => Yii::t('app', 'geral.descricao'),
                    'bpbi_menu_consulta' => Yii::t('app', 'perfil.bpbi_menu_consulta'),
                    'bpbi_menu_painel' => Yii::t('app', 'perfil.bpbi_menu_painel'),
                    'bpbi_menu_relatorio' => Yii::t('app', 'perfil.bpbi_menu_relatorio'),
                    'acesso_bi' => Yii::t('app', 'perfil.acesso_bi'),
                    'is_admin' => Yii::t('app', 'perfil.is_admin'),
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
                    'json' =>
                    [
                        'class' => 'app\components\JsonBehavior',
                        'attributes' =>
                        [
                            'bpbi_menu_consulta', 'bpbi_menu_painel',
                            'bpbi_menu_relatorio'
                        ],
                    ],
                    [
                        'class' => \app\behaviors\ChangeLogBehavior::className(),
                        'excludedAttributes' => ['updated_at'],
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

    public function getUsuarios() {
        return $this->hasMany(AdminUsuario::className(), ['perfil_id' => 'id']);
    }

    public function getStringUsuarios() {
        $string = '';

        foreach ($this->usuarios as $usuario) {
            $string .= Html::a($usuario->nomeResumo, ['/usuario/view', 'id' => $usuario->id]) . ' <br>';
        }

        return $string;
    }

    public function getPermissoes() {
        $modelPermissoes = PermissaoGeral::find()->andWhere(['is_ativo' => TRUE,
                    'is_excluido' => FALSE])->orderBy('cast(gerenciador as unsigned) ASC, column ASC')->all();

        $this->permissoes = [];

        foreach ($modelPermissoes as $modelPermissao) {
            $value = PerfilPermissao::find()->andWhere([
                        'is_ativo' => TRUE,
                        'is_excluido' => FALSE,
                        'id_perfil' => $this->id,
                        'id_permissao' => $modelPermissao->id
                    ])->exists();

            $this->permissoes[$modelPermissao->gerenciador][$modelPermissao->column] = [
                        'attributes' => $modelPermissao->attributes,
                        $modelPermissao->id => $value
            ];
        }

        return $this->permissoes;
    }

    public function beforeSave($insert)
    {
        PerfilPermissao::deleteAll(['id_perfil' => $this->id]);

        if ($this->acesso_bi && !$this->is_admin) {
            foreach ($this->permissoes as $permissao) {
                foreach ($permissao as $dado) {
                    foreach ($dado as $id_permissao => $value) {
                        if ($value) {
                            $permissao = new PerfilPermissao();
                            $permissao->id_perfil = $this->id;
                            $permissao->id_permissao = $id_permissao;
                            $permissao->save();
                        }
                    }
                }
            }
        }

        $consultaVisualizar = PermissaoConsulta::find()->andWhere([
            'gerenciador' => 'visualizar',
            'is_ativo' => TRUE,
            'is_excluido' => FALSE
        ])->one();

        ConsultaPermissao::deleteAll(['id_perfil' => $this->id, 'id_permissao' => $consultaVisualizar->id]);

        $painelVisualizar = PermissaoPainel::find()->andWhere([
            'gerenciador' => 'visualizar',
            'is_ativo' => TRUE,
            'is_excluido' => FALSE
        ])->one();

        PainelPermissao::deleteAll(['id_perfil' => $this->id, 'id_permissao' => $painelVisualizar->id]);

        $relatorioVisualizar = PermissaoRelatorio::find()->andWhere([
            'gerenciador' => 'visualizar',
            'is_ativo' => TRUE,
            'is_excluido' => FALSE
        ])->one();

        RelatorioPermissao::deleteAll(['id_perfil' => $this->id, 'id_permissao' => $relatorioVisualizar->id]);

        if ($this->acesso_bi && !$this->is_admin)
        {
            $bpbi_menu_painel = [];
            $bpbi_menu_consulta = [];
            $bpbi_menu_relatorio = [];

            foreach ($this->permissoesConteudo as $class => $permissoes)
            {
                foreach($permissoes as $tipo => $permissao)
                {
                    foreach($permissao as $id_pasta => $data)
                    {
                        foreach($data as $id_model => $on)
                        {
                            if($tipo == 'c')
                            {
                                if($class == 'painel')
                                {
                                    $attribute = 'id_painel';
                                    $permissaoModel = new PainelPermissao();
                                    $permissaoModel->id_permissao = $painelVisualizar->id;
                                }
                                elseif($class == 'relatorio')
                                {
                                    $attribute = 'id_relatorio_data';
                                    $permissaoModel = new RelatorioPermissao();
                                    $permissaoModel->id_permissao = $relatorioVisualizar->id;
                                }
                                else
                                {
                                    $attribute = 'id_consulta';
                                    $permissaoModel = new ConsultaPermissao();
                                    $permissaoModel->id_permissao = $consultaVisualizar->id;
                                }

                                $permissaoModel->id_perfil = $this->id;
                                $permissaoModel->{$attribute} = $id_model;
                                $permissaoModel->save();

                                if($id_pasta != 'jg')
                                {
                                    if($class == 'painel' && !in_array($id_pasta, $bpbi_menu_painel))
                                    {
                                        $bpbi_menu_painel[] = $id_pasta;
                                    }
                                    elseif($class == 'relatorio' && !in_array($id_pasta, $bpbi_menu_relatorio))
                                    {
                                        $bpbi_menu_relatorio[] = $id_pasta;
                                    }
                                    elseif(!in_array($id_pasta, $bpbi_menu_consulta))
                                    {
                                        $bpbi_menu_consulta[] = $id_pasta;
                                    }
                                }
                            }
                            else
                            {
                                if($class == 'painel' && !in_array($id_pasta, $bpbi_menu_painel))
                                {
                                    $bpbi_menu_painel[] = $id_pasta;
                                }
                                elseif($class == 'relatorio' && !in_array($id_pasta, $bpbi_menu_relatorio))
                                {
                                    $bpbi_menu_relatorio[] = $id_pasta;
                                }
                                elseif(!in_array($id_pasta, $bpbi_menu_consulta))
                                {
                                    $bpbi_menu_consulta[] = $id_pasta;
                                }
                            }
                        }
                    }
                }
            }

            $this->bpbi_menu_painel = $bpbi_menu_painel;
            $this->bpbi_menu_consulta = $bpbi_menu_consulta;
            $this->bpbi_menu_relatorio = $bpbi_menu_relatorio;
        }

        return parent::beforeSave($insert);
    }
}
