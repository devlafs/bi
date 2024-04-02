<?php

namespace app\models;

use Yii;

class PerfilComplemento extends \yii\db\ActiveRecord {

    CONST PAGINA_INICIAL = 1;

    CONST PAINEL_ESPECIFICO = 2;

    CONST CONSULTA_ESPECIFICO = 3;

    CONST ULTIMA_TELA = 4;

    CONST RELATORIO_ESPECIFICO = 5;

    public static $tipos = 
    [
        self::PAGINA_INICIAL => 'Página Inicial',
        self::PAINEL_ESPECIFICO => 'Painel Específico',
        self::CONSULTA_ESPECIFICO => 'Consulta Específica',
        self::RELATORIO_ESPECIFICO => 'Relatório Específico',
        self::ULTIMA_TELA => 'Última tela'
    ];

    public static function tableName() {
        return 'bpbi_perfil_complemento';
    }

    public function rules() {
        return [
            [['id_perfil', 'pagina_inicial'], 'required'],
            [['id_perfil', 'pagina_inicial', 'id_consulta', 'id_painel', 'id_relatorio_data'], 'integer'],
            ['pagina_inicial', 'validateView'],
            [['id_consulta'], 'exist', 'skipOnError' => true, 'targetClass' => Consulta::className(), 'targetAttribute' => ['id_consulta' => 'id']],
            [['id_painel'], 'exist', 'skipOnError' => true, 'targetClass' => Painel::className(), 'targetAttribute' => ['id_painel' => 'id']],
            [['id_relatorio_data'], 'exist', 'skipOnError' => true, 'targetClass' => RelatorioData::className(), 'targetAttribute' => ['id_relatorio_data' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
        [
            'id_perfil' => Yii::t('app', 'geral.perfil'),
            'pagina_inicial' => Yii::t('app', 'perfil_complemento.pagina_inicial'),
            'id_consulta' => Yii::t('app', 'geral.consulta'),
            'id_painel' => Yii::t('app', 'geral.painel'),
            'id_relatorio_data' => Yii::t('app', 'geral.relatorio'),
        ];
    }

    public function validateView($attribute, $params, $validator) {
        switch ($this->pagina_inicial) {
            case self::CONSULTA_ESPECIFICO:

                if (!$this->id_consulta) {
                    $this->addError('id_consulta', 'Consulta não pode ser vazio;');
                }

                break;
            case self::PAINEL_ESPECIFICO:

                if (!$this->id_painel) {
                    $this->addError('id_painel', 'Painel não pode ser vazio;');
                }

                break;
            case self::RELATORIO_ESPECIFICO:

                if (!$this->id_relatorio_data) {
                    $this->addError('id_relatorio_data', 'Relatório não pode ser vazio;');
                }
        }
    }

    public function getConsulta() {
        return $this->hasOne(Consulta::className(), ['id' => 'id_consulta']);
    }

    public function getPainel() {
        return $this->hasOne(Painel::className(), ['id' => 'id_painel']);
    }
    public function getRelatorio() {
        return $this->hasOne(RelatorioData::className(), ['id' => 'id_relatorio_data']);
    }
}
