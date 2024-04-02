<?php

namespace app\controllers;

use app\models\Ajuda;
use app\models\AjudaCategoria;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\lists\VersionList;

class AjudaController extends BaseController {

    public function behaviors() {
        return
        [
            'access' =>
            [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'], //@
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionIndex() {

        $categorias = AjudaCategoria::find()->orderBy('ordem ASC')->all();

        return $this->render('index', compact('categorias'));
    }
}
