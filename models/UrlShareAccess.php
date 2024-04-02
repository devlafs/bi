<?php

namespace app\models;

use Yii;

class UrlShareAccess extends \yii\db\ActiveRecord {

    public $created_by;
    public $updated_at;
    public $updated_by;

    public static function tableName() {
        return 'bpbi_url_share_access';
    }

    public function rules() {
        return [
            [['id_url', 'ip'], 'required'],
            [['id_url'], 'integer'],
            [['created_at', 'is_expired'], 'safe'],
            [['ip'], 'string', 'max' => 255],
            [['id_url'], 'exist', 'skipOnError' => true, 'targetClass' => UrlShare::className(), 'targetAttribute' => ['id_url' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'id_url' => Yii::t('app', 'url_share_access.id_url'),
            'ip' => Yii::t('app', 'url_share_access.ip'),
            'is_expired' => Yii::t('app', 'url_share_access.is_expired'),
            'created_at' => Yii::t('app', 'geral.created_at'),
        ];
    }

    public function behaviors() {
        $behaviors = [
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

    public function getUrl() {
        return $this->hasOne(UrlShare::className(), ['id' => 'id_url']);
    }

}
