<?php

namespace app\controllers;

use app\models\form\SistemaForm;
use app\models\Sistema;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Response;

class GeralController extends BaseController {

    public function behaviors() {
        return
        [
            'access' =>
            [
                'class' => AccessControl::className(),
                'rules' =>
                [
                    [
                        'actions' =>
                        [
                            'index', //bpone
                            'logo-upload', //bpone
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return (Yii::$app->user->identity->id == 1);
                        },
                    ],
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        $model = new SistemaForm();
        $model->updateAttributes();

        if ($model->load(Yii::$app->request->post()) && $model->saveAttributes()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_alterada', [
                'model' => Yii::t('app', 'geral.configuracao')
            ]));

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionLogoUpload()
    {
        $model = Sistema::find()->andWhere([
            'campo' => 'logo'
        ])->one();

        if (isset($_POST))
        {
            $files = SistemaForm::saveTempAttachments($_FILES);

            if($files)
            {
                $model->valor = $files[0]['originalName'];
                $model->save(FALSE, ['valor']);
            }

            Yii::$app->response->format = trim(Response::FORMAT_JSON);

            return ['file' => $model->valor];
        }
    }
}
