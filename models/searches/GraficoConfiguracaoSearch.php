<?php

namespace app\models\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GraficoConfiguracao;

class GraficoConfiguracaoSearch extends GraficoConfiguracao
{
    public function rules()
    {
        return [
            [['id', 'is_ativo', 'is_excluido', 'created_by', 'updated_by', 'is_serie'], 'integer'],
            [['view', 'tipo', 'data', 'created_at', 'updated_at', 'data_serie'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = GraficoConfiguracao::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>
            [
                'defaultOrder' =>
                [
                    'view' => SORT_ASC
                ]
            ],
            'pagination' =>
            [
                'pageSize' => 10
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) 
        {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'is_excluido' => FALSE,
            'view' => $this->view,
            'tipo' => $this->tipo,
            'is_serie' => $this->is_serie,
        ]);

        return $dataProvider;
    }
}
