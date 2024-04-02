<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
    public function init()
    {
        Yii::$app->language = Yii::$app->session->get('language');
    }
}
