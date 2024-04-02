<?php

namespace app\modules\user\components;

use Yii;
use app\models\RbaAcesso;
use yii\db\Expression;

class User extends \yii\web\User
{
    public $identityClass = 'app\modules\user\models\User';

    public $enableAutoLogin = true;

    public $loginUrl = ["/user/login"];

    public function getIsGuest()
    {
        $user = $this->getIdentity();

        return $user === null;
    }
    
    public function getIsLoggedIn()
    {
        return !$this->getIsGuest();
    }

    public function registerAccess($identity)
    {
        $model = new RbaAcesso();
        $model->admusua_id = $identity->id;
        $model->dthr_login = new Expression('NOW()');
        $model->desc_ip = $_SERVER['REMOTE_ADDR'];
        $model->desc_useragent = $_SERVER['HTTP_USER_AGENT'];
        $model->bpbi = TRUE;
        $model->save();
    }
    
    public function afterLogin($identity, $cookieBased, $duration)
    {
        $this->registerAccess($identity);
        parent::afterLogin($identity, $cookieBased, $duration);
    }

}
