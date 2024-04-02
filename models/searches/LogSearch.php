<?php

namespace app\models\searches;

use app\models\LogItem;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class LogSearch extends LogItem
{
    public function rules()
    {
        return [
            [['relatedObjectId'], 'integer'],
            [['createdAt', 'type', 'relatedObject', 'data', 'userId'], 'safe'],
            [['relatedObjectType', 'hostname'], 'string', 'max' => 255],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = LogItem::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>
            [
                'defaultOrder' =>
                [
                    'createdAt' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) 
        {
            return $dataProvider;
        }
        
        $query->andWhere('relatedObjectType != "UltimaTelaAcesso"');

        $query->andFilterWhere([
            'relatedObjectId' => $this->relatedObjectId
        ]);

        if($this->type == 'deleted')
        {
            $query->andWhere(['like', 'data', '"is_excluido":[0,true]']);
        }
        else if($this->type == 'restore')
        {
            $query->andWhere(['like', 'data', '"is_excluido":[1,0]']);
        }
        else
        {
            $query->andFilterWhere(['like', 'type', $this->type]);
        }

        $query->andFilterWhere(['like', 'relatedObjectType', $this->relatedObjectType]);

        return $dataProvider;
    }
}
