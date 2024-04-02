<?php

namespace app\models\queries;

use Yii;
use yii\caching\DbDependency;

class ConexaoQuery extends \yii\db\ActiveQuery
{
    public function defaultDependency() 
    {
        return new DbDependency(['sql' => 'SELECT MAX(updated_at) FROM bpbi_conexao', 'reusable' => true]);
    }

    public function all($db = null) 
    {
        $db = Yii::$app->db;
        
        return $db->cache(function ($db) 
        {
            return parent::all($db);
        }, Yii::$app->params['cacheDuration'], $this->defaultDependency());
    }

    public function one($db = null)
    {
        $db = Yii::$app->db;
    
        return $db->cache(function ($db)
        {
            return parent::one($db);
        }, Yii::$app->params['cacheDuration'], $this->defaultDependency());
    }
}
