<?php

namespace app\models\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EmailLog;

class EmailLogSearch extends EmailLog
{
    public function rules()
    {
        return [
            [['id', 'id_email', 'status', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'integer'],
            [['destinatario', 'log', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($id, $params)
    {
        $query = EmailLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>
            [
                'defaultOrder' =>
                [
                    'created_at' => SORT_DESC
                ]
            ],
            'pagination' =>
            [
                'pageSize' => 10
            ]
        ]);

        $this->load($params);
        
        $query->andWhere(['id_email' => $id]);

        if (!$this->validate()) 
        {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
