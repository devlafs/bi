<?php

namespace app\models\form;

use Yii;
use yii\base\Model;
use app\models\Sistema;

class SistemaForm extends Model
{
    public $name;

    public $logo;

    public $email;

    public $url;

    public $systemEmail;

    public $urlShareDaysExpiration;

    public $emailShareDaysExpiration;

    public $pallete;

    public $description;

    public $advancedFilter;

    public $homepage;

    public function rules()
    {
        return [
            [['name', 'url', 'systemEmail', 'urlShareDaysExpiration', 'emailShareDaysExpiration', 'pallete', 'homepage'], 'required'],
            [['description', 'advancedFilter'], 'safe'],
            [['systemEmail'], 'email'],
            [['logo'], 'file'],
        ];
    }

    public function attributeLabels() {
        return
            [
                'name' => Yii::t('app', 'sistema_form.usuario'),
                'logo' => Yii::t('app', 'sistema_form.usuario'),
                'url' => Yii::t('app', 'sistema_form.usuario'),
                'systemEmail' => Yii::t('app', 'sistema_form.usuario'),
                'urlShareDaysExpiration' => Yii::t('app', 'sistema_form.usuario'),
                'emailShareDaysExpiration' => Yii::t('app', 'sistema_form.usuario'),
                'pallete' => Yii::t('app', 'sistema_form.usuario'),
                'description' => Yii::t('app', 'sistema_form.usuario'),
                'advancedFilter' => Yii::t('app', 'sistema_form.usuario'),
                'homepage' => Yii::t('app', 'sistema_form.usuario'),
            ];
    }

    public function updateAttributes()
    {
        $fields = Sistema::find()->andWhere(['visible' => TRUE])->orderBy("ordem ASC")->all();
        foreach($fields  as $field)
        {
            $this->{$field->campo} = $field->valor;
        }
    }

    public function saveAttributes()
    {
        $fields = Sistema::find()->andWhere(['visible' => TRUE])->orderBy("ordem ASC")->all();

        foreach($fields  as $field)
        {
            if($field->campo != 'logo')
            {
                $field->valor = $this->{$field->campo};
                $field->save(FALSE, ['valor']);
            }
        }

        return true;
    }

    public static function saveTempAttachments($attachments)
    {
        $files = [];

        $allowedFiles = ['jpg', 'jpeg', 'gif', 'png'];

        if (!empty($attachments)) {

            $tmpFilePath = $attachments['logo']['tmp_name'];

            $size = $attachments['logo']['size'];

            $ext = substr(strrchr($attachments['logo']['name'], '.'), 1);

            if(in_array($ext, $allowedFiles)){

                $newFileName = 'logo/' . Yii::$app->security->generateRandomString(40) . "." . $ext;

                if (move_uploaded_file($tmpFilePath, $newFileName)) {

                    $files[] = ['fileName' => $newFileName, 'type' => $ext, 'size' => (($size/1000)), 'originalName' => $newFileName];

                }
            }
        }

        return $files;
    }
}