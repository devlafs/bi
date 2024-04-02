<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\lists\VersionList;

class ChangeLogController extends BaseController {

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
        $list = VersionList::$data;

        return $this->render('index', compact('list'));
    }

}
