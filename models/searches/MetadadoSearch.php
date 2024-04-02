<?php

namespace app\models\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Metadado;

class MetadadoSearch extends Metadado
{
    public function rules()
    {
        return [
            [['id', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'integer'],
            [['nome', 'descricao', 'caminho', 'created_at', 'updated_at', 'executed_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Metadado::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>
                [
                    'defaultOrder' =>
                        [
                            'nome' => SORT_ASC
                        ]
                ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'is_ativo' => $this->is_ativo,
            'is_excluido' => FALSE
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'caminho', $this->caminho]);

        return $dataProvider;
    }
}
