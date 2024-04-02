<?php

namespace app\models\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MapaCampo;

class MapaCampoSearch extends MapaCampo
{
    public $nome_campo;
    
    public function rules()
    {
        return [
            [['id', 'id_mapa', 'id_campo', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'integer'],
            [['nome_campo', 'tag', 'descricao', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($id, $params)
    {
        $query = MapaCampo::find()->joinWith(['campo']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>
            [
                'defaultOrder' =>
                [
                    'nome_campo' => SORT_ASC
                ]
            ],
            'pagination' =>
            [
                'pageSize' => 10
            ]
        ]);
        
        $dataProvider->sort->attributes['nome_campo'] = [
            'asc' => ['bpbi_indicador_campo.nome' => SORT_ASC],
            'desc' => ['bpbi_indicador_campo.nome' => SORT_DESC],
        ];
        
        $this->load($params);

        $query->andWhere(['id_mapa' => $id]);

        if (!$this->validate())
        {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_mapa' => $this->id_mapa,
            'bpbi_mapa_campo.is_ativo' => $this->is_ativo,
            'bpbi_mapa_campo.is_excluido' => FALSE
        ]);

        $query->andFilterWhere(['like', 'bpbi_indicador_campo.nome', $this->nome_campo])
            ->andFilterWhere(['like', 'bpbi_mapa_campo.tag', $this->tag]);

        return $dataProvider;
    }
}
