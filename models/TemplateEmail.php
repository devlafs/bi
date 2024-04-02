<?php

namespace app\models;

use Yii;
use app\lists\TemplateEmailList;

class TemplateEmail extends \yii\db\ActiveRecord {

    public $tags_obrigatorias;

    public static function tableName() {
        return 'bpbi_template_email';
    }

    public function rules() {
        return [
            [['nome', 'tipo', 'tags', 'html'], 'required'],
            [['tipo'], 'integer'],
            [['html'], 'validateHtml'],
            [['created_at', 'is_ativo', 'is_excluido', 'created_by',
            'tags', 'html', 'updated_by', 'updated_at', 'tags_obrigatorias'], 'safe'],
            [['nome'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'nome' => Yii::t('app', 'geral.nome'),
                    'tipo' => Yii::t('app', 'geral.tipo'),
                    'tags' => Yii::t('app', 'template_email.tags'),
                    'tags_obrigatorias' => Yii::t('app', 'template_email.tags_obrigatorias'),
                    'html' => Yii::t('app', 'template_email.html'),
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
                            'tags'
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

    public function getTags() {
//        $string = '<span title="#' . TemplateEmailList::TAG_LOGO_BP1 . '" class="badge-default tags">#' . TemplateEmailList::TAG_LOGO_BP1 . '</span> ';
//        $string .= '<span title="#' . TemplateEmailList::TAG_SITE_BP1 . '" class="badge-default tags">#' . TemplateEmailList::TAG_SITE_BP1 . '</span> ';

        $string = '';

        if ($this->tags) {
            foreach ($this->tags as $tag) {
                $string .= '<span title="#' . $tag . '" class="badge-default tags">#' . $tag . '</span> ';
            }
        }

        return $string;
    }

    public function validateHtml($attribute, $params, $validator) {
//        if (stripos($this->html, "#" . TemplateEmailList::TAG_LOGO_BP1) === false) {
//            $this->addError('html', 'A tag #' . TemplateEmailList::getTags(TemplateEmailList::TAG_LOGO_BP1) . ' não foi encontrada no HTML.');
//        }
//        
//        if (stripos($this->html, "#" . TemplateEmailList::TAG_SITE_BP1) === false) {
//            $this->addError('html', 'A tag #' . TemplateEmailList::getTags(TemplateEmailList::TAG_SITE_BP1) . ' não foi encontrada no HTML.');
//        }

        if ($this->tags) {
            foreach ($this->tags as $tag) {
                if (stripos($this->html, "#{$tag}") === false) {
                    $this->addError('html', \Yii::t('app', 'A tag {tag} não foi encontrada no HTML.', [
                        'tag' => $tag,
                    ]));
                }
            }
        }
    }

}
