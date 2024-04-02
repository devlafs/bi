<?php

namespace app\models\form;

use Yii;
use yii\base\Model;
use app\models\Sistema;

class ImageForm extends Model
{
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file'],
        ];
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

                $newFileName = 'uploads/' . Yii::$app->security->generateRandomString(40) . "." . $ext;

                if (move_uploaded_file($tmpFilePath, $newFileName)) {

                    $files[] = ['fileName' => $newFileName, 'type' => $ext, 'size' => (($size/1000)), 'originalName' => $newFileName];

                }
            }
        }

        return $files;
    }
}