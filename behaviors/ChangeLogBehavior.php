<?php

namespace app\behaviors;

use app\models\LogItem;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\StringHelper;

class ChangeLogBehavior extends Behavior {

    public $excludedAttributes = [];

    const UPDATE = 'update';
    const DELETED = 'deleted';
    const INSERT = 'insert';
    const CUSTOM = 'custom';

    public $type = self::UPDATE;

    public function events() {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'addUpdateLog',
            ActiveRecord::EVENT_AFTER_INSERT => 'addInsertLog',
            ActiveRecord::EVENT_BEFORE_DELETE => 'addDeleteLog',
        ];
    }

    public function addUpdateLog(Event $event) {
        $owner = $this->owner;
        $changedAttributes = $event->changedAttributes;

        $diff = [];

        foreach ($changedAttributes as $attrName => $attrVal) {
            $newAttrVal = $owner->getAttribute($attrName);

            $newAttrVal = is_float($newAttrVal) ? StringHelper::floatToString($newAttrVal) : $newAttrVal;
            $attrVal = is_float($attrVal) ? StringHelper::floatToString($attrVal) : $attrVal;

            if ($newAttrVal != $attrVal) {
                $diff[$attrName] = [$attrVal, $newAttrVal];
            }
        }
        $diff = $this->applyExclude($diff);

        if ($diff) {
            $diff = $this->owner->setChangelogLabels($diff);
            $logEvent = new LogItem();
            $logEvent->relatedObject = $owner;
            $logEvent->data = $diff;
            $logEvent->type = self::UPDATE;
            $logEvent->save();
        }
    }

    public function addCustomLog($data, $type = null) {
        if (!is_array($data)) {
            $data = [$data];
        }
        if ($type) {
            $this->setType($type);
        }

        $logEvent = new LogItem();
        $logEvent->relatedObject = $this->owner;
        $logEvent->data = $data;
        $logEvent->type = self::CUSTOM;
        $logEvent->save();
    }

    public function setType($type) {
        $this->type = $type;
    }

    private function applyExclude(array $diff) {
        foreach ($this->excludedAttributes as $attr) {
            unset($diff[$attr]);
        }

        return $diff;
    }

    public function setChangelogLabels(array $diff) {
        return $diff;
    }

    public function addDeleteLog() {
        $logEvent = new LogItem();
        $logEvent->relatedObject = $this->owner;
        $logEvent->data = '';
        $logEvent->type = self::DELETED;
        $logEvent->save();
    }

    public function addInsertLog(Event $event) {
        $logEvent = new LogItem();
        $logEvent->relatedObject = $this->owner;
        $logEvent->data = $event->sender->attributes;
        $logEvent->type = self::INSERT;
        $logEvent->save();
    }

}
