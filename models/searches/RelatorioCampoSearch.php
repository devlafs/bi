<?php

namespace app\models\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RelatorioCampo;

class RelatorioCampoSearch extends RelatorioCampo
{
    public function rules()
    {
        return [
            [['id', 'id_relatorio', 'ordem', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'integer'],
            [['nome', 'tipo', 'descricao', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($id, $params)
    {
        $query = RelatorioCampo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>
            [
                'defaultOrder' =>
                [
                    'ordem' => SORT_ASC
                ]
            ],
            'pagination' =>
            [
                'pageSize' => 10
            ]
        ]);
        $this->load($params);

        $query->andWhere(['id_relatorio' => $id]);

        if (!$this->validate())
        {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_relatorio' => $this->id_relatorio,
            'ordem' => $this->ordem,
            'is_ativo' => $this->is_ativo,
            'is_excluido' => FALSE,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'tipo', $this->tipo]);

        return $dataProvider;
    }
}
