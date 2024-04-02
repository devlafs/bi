<?php

namespace app\components;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;

class JsonBehavior extends Behavior {

    public $attributes;

    public function events() {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'encode',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'encode',
            ActiveRecord::EVENT_AFTER_FIND => 'decode',
        ];
    }

    public function encode($event) {
        foreach ($this->attributes as $attribute) {
            $this->owner->$attribute = Json::encode($this->owner->$attribute);
        }
    }

    public function decode($event) {
        foreach ($this->attributes as $attribute) {
            if (isset($this->owner->$attribute))
                $this->owner->$attribute = Json::decode($this->owner->$attribute);
        }
    }

}
