<?php

namespace app\models;

use Yii;

class ConsultaFiltroUsuario extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'bpbi_consulta_filtro_usuario';
    }

    public function rules() {
        return [
            [['id_usuario', 'id_consulta'], 'required'],
            [['id_usuario', 'id_consulta'], 'integer'],
            [['condicao', 'created_at', 'updated_at', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'safe'],
            [['id_consulta'], 'exist', 'skipOnError' => true, 'targetClass' => Consulta::className(), 'targetAttribute' => ['id_consulta' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'id_usuario' => Yii::t('app', 'geral.usuario'),
                    'id_consulta' => Yii::t('app', 'geral.consulta'),
                    'condicao' => Yii::t('app', 'consulta_filtro_usuario.condicao'),
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

    public function getConsulta() {
        return $this->hasOne(Consulta::className(), ['id' => 'id_consulta']);
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
                            $type = (isset($dadosCaixa['type']) && !empty($dadosCaixa['type'])) ? $dadosCaixa['type'] : null;
                            $value = (isset($dadosCaixa['value']) && !empty($dadosCaixa['value'])) ? $dadosCaixa['value'] : null;

                            if ($field && $type && $value) {
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