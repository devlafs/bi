<?php

namespace app\models;

use cranky4\changeLogBehavior\helpers\CompositeRelationHelper;
use yii\behaviors\TimestampBehavior;
use yii\console\Application;
use yii\db\ActiveRecord;
use Yii;

class LogItem extends ActiveRecord {

    public $relatedObject;

    public static function tableName() {
        return '{{%bpbi_changelogs}}';
    }

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function rules() {
        return [
            [['relatedObjectId', 'userId'], 'integer'],
            [['createdAt', 'relatedObject', 'data'], 'safe'],
            [['relatedObjectType', 'type', 'hostname'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels() {
        return [
            'relatedObjectType' => Yii::t('app', 'log_item.relatedObjectType'),
            'relatedObjectId' => Yii::t('app', 'log_item.relatedObjectId'),
            'data' => Yii::t('app', 'log_item.data'),
            'createdAt' => Yii::t('app', 'log_item.createdAt'),
            'type' => Yii::t('app', 'log_item.type'),
            'userId' => Yii::t('app', 'geral.usuario'),
            'hostname' => Yii::t('app', 'log_item.hostname'),
        ];
    }

    public function getUser() {
        return $this->hasOne(AdminUsuario::className(), ['id' => 'userId']);
    }

    public function beforeSave($insert) {
        if (empty($this->userId) && !(\Yii::$app instanceof Application) && !\Yii::$app->user->isGuest) {
            $this->userId = \Yii::$app->user->id;
        }

        if (empty($this->hostname) && \Yii::$app->request->hasMethod('getUserIP')) {
            $this->hostname = \Yii::$app->request->getUserIP();
        }

        if (!empty($this->data) && is_array($this->data)) {
            $this->data = json_encode($this->data);
        }

        if ($this->relatedObject) {
            $this->relatedObjectType = CompositeRelationHelper::resolveObjectType($this->relatedObject);
            $this->relatedObjectId = $this->relatedObject->primaryKey;
        }

        return parent::beforeSave($insert);
    }

    public function getStringData() {
        $string = '';
        $array_data = json_decode($this->data);

        if ($array_data) {
            foreach ($array_data as $field => $value) {
                if ($this->type == 'insert' && !in_array($field, ['is_ativo', 'is_excluido', 'created_at', 'updated_at', 'created_by', 'updated_by'])) {
                    $string .= "<p>{$field}: {$value}</p>";
                } elseif ($this->type == 'update' && !in_array($field, ['is_ativo', 'is_excluido', 'created_at', 'updated_at', 'created_by', 'updated_by'])) {
                    $old = (isset($value[0]) && is_string($value[0])) ? $value[0] : '<<>><<>>';
                    $new = (isset($value[1]) && is_string($value[1])) ? $value[1] : '<<>><<>>';
                    $string .= "<p>{$field}: {$old} => {$new}</p>";
                }
            }
        }

        return $string;
    }

}
