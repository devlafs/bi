<?php

namespace app\models;

use Yii;

class Pasta extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'bpbi_pasta';
    }

    public function rules() {
        return [
            [['id_pasta', 'created_by', 'updated_by'], 'integer'],
            [['nome', 'tipo'], 'required'],
            [['created_at', 'updated_at', 'is_ativo', 'is_excluido'], 'safe'],
            [['nome'], 'string', 'max' => 255],
            [['id_pasta'], 'exist', 'skipOnError' => true, 'targetClass' => Pasta::className(), 'targetAttribute' => ['id_pasta' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'id_pasta' => Yii::t('app', 'geral.pasta'),
                    'nome' => Yii::t('app', 'geral.nome'),
                    'tipo' => Yii::t('app', 'geral.tipo'),
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

    public function getConsultas() {
        return $this->hasMany(Consulta::className(), ['id_pasta' => 'id'])->andOnCondition(['is_ativo' => TRUE, 'is_excluido' => FALSE])->orderBy('nome ASC');
    }

    public function getPaineis() {
        return $this->hasMany(Painel::className(), ['id_pasta' => 'id'])->andOnCondition(['is_ativo' => TRUE, 'is_excluido' => FALSE])->orderBy('nome ASC');
    }

    public function getRelatorios() {
        return $this->hasMany(RelatorioData::className(), ['id_pasta' => 'id'])->andOnCondition(['is_ativo' => TRUE, 'is_excluido' => FALSE])->orderBy('nome ASC');
    }

    public function getPasta() {
        return $this->hasOne(Pasta::className(), ['id' => 'id_pasta']);
    }

    public function getPastas() {
        return $this->hasMany(Pasta::className(), ['id_pasta' => 'id'])->andOnCondition(['is_ativo' => TRUE, 'is_excluido' => FALSE])->orderBy('nome ASC');
    }

    public function getPath($pasta) {
        $nomes = [];

        if ($pasta->pasta) {
            $nomes = array_merge($nomes, $this->getPath($pasta->pasta));
        }

        $nomes[] = mb_strtoupper($pasta->nome);

        return $nomes;
    }

    public function getOrderedFolders($tipo = "CONSULTA") {
        $pastas = self::find()->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE, 'tipo' => $tipo])->all();

        $array = [];

        foreach ($pastas as $pasta) {
            $path = '';

            foreach ($this->getPath($pasta) as $index => $pathName) {

                $path .= ($index > 0) ? " / {$pathName}" : " {$pathName}";
            }

            $array[$pasta->id] = $path;
        }

        return $array;
    }

    public static function getTreePath($pasta) {
        $nomes = [];

        if ($pasta->pasta) {
            $nomes = array_merge($nomes, self::getTreePath($pasta->pasta));
        }

        $nomes[] = mb_strtoupper($pasta->nome);

        return $nomes;
    }

    public static function getTree($tipo = "CONSULTA") {
        $pastas = self::find()->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE, 'tipo' => $tipo])->all();

        $array = [];

        foreach ($pastas as $pasta) {
            $path = '';

            foreach (self::getTreePath($pasta) as $index => $pathName) {

                $path .= ($index > 0) ? " > {$pathName}" : " {$pathName}";
            }

            $array[$pasta->id] = $path;
        }

        asort($array);

        return $array;
    }

}
