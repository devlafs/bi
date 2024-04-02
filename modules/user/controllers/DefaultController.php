<?php

namespace app\modules\user\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\AdminUsuario;
use app\models\UsuarioToken;
use app\magic\MenuMagic;

class DefaultController extends Controller
{
    public $module;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'email', 'password'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => 
            [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionLogin()
    {
        $this->layout = '//login';        
        
        if (!\Yii::$app->user->isGuest) 
        {
            return $this->goHome();
        }
 
        $model = $this->module->model("LoginForm");
        
        if ($model->load(Yii::$app->request->post()) && $model->login()) 
        {
            MenuMagic::updateMenus();

            if(Yii::$app->user && Yii::$app->user->identity->language)
            {
                Yii::$app->language = Yii::$app->user->identity->language;
                Yii::$app->session->set('language', Yii::$app->user->identity->language);
            }
            else
            {
                Yii::$app->language = 'pt-BR';
                Yii::$app->session->set('language', 'pt-BR');
            }
            
            return $this->goBack();
        } 
        else 
        {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionEmail()
    {
        $this->layout = '//login';        
        
        if (!\Yii::$app->user->isGuest) 
        {
            return $this->goHome();
        }
 
        $model = $this->module->model("EmailForm");
        
        if ($model->load(Yii::$app->request->post()) && $model->send()) 
        {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_enviada', [
                'model' => Yii::t('app', 'geral.senha')
            ]));

            return $this->redirect(['/user/login']);
        } 
        else 
        {
            return $this->render('email', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionPassword($t)
    {
        $this->layout = '//login';        
        
        if (!\Yii::$app->user->isGuest) 
        {
            return $this->goHome();
        }
 
        $model = $this->module->model("PasswordForm");
        $token = $this->findToken($t);
        $usuario = ($token) ? AdminUsuario::findOne($token->id_usuario) : null;
        
        if ($model->load(Yii::$app->request->post()) && $model->save($usuario)) 
        {
            $token->is_used = TRUE;
            $token->save(FALSE, ['is_used']);

            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_alterada', [
                'model' => Yii::t('app', 'geral.senha')
            ]));

            return $this->redirect(['/user/login']);
        } 
        else 
        {
            return $this->render('password', [
                'model' => $model,
                'usuario' => $usuario,
                'token' => $token
            ]);
        }
    }
    
    protected function findToken($t)
    {
        $model = UsuarioToken::find()->andWhere([
            'token' => $t,
            'is_ativo' => TRUE,
            'is_excluido' => FALSE,
            'is_used' => FALSE
        ])->one();
                
        if($model)
        {
            $expirate_date = strtotime(date('Y-m-d',strtotime("-3 day")));
            $created_at = strtotime($model->created_at);

            if($created_at < $expirate_date)
            {
                $model = null;
            }
        }
        
        return $model;
    }
}