<?php

namespace app\models\searches;

use app\models\Relatorio;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class RelatorioSearch extends Relatorio
{
    public function rules()
    {
        return [
            [['id', 'id_conexao', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'integer'],
            [['nome', 'descricao', 'sql', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Relatorio::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>
            [
                'defaultOrder' =>
                [
                    'nome' => SORT_ASC
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
            'id' => $this->id,
            'id_conexao' => $this->id_conexao,
            'is_ativo' => $this->is_ativo,
            'is_excluido' => FALSE,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'sql', $this->sql]);

        return $dataProvider;
    }
}