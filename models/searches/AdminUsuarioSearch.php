<?php

namespace app\models\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AdminUsuario;

class AdminUsuarioSearch extends AdminUsuario
{
    public function rules()
    {
        return [
            [['id', 'perfil_id'], 'integer'],
            [['cargo', 'celular', 'departamento', 'email', 'login', 'nome', 'nomeResumo', 'obs', 'senha', 'acesso_bi'], 'safe'],
            [['assunto', 'email', 'created_at', 'updated_at', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'safe'],

        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = AdminUsuario::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>
            [
                'defaultOrder' =>
                [
                    'nomeResumo' => SORT_ASC
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
            'perfil_id' => $this->perfil_id,
            'acesso_bi' => $this->acesso_bi,
            'is_ativo' => $this->is_ativo,
            'is_excluido' => FALSE,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by
        ]);

        $query->andFilterWhere(['like', 'cargo', $this->cargo])
            ->andFilterWhere(['like', 'celular', $this->celular])
            ->andFilterWhere(['like', 'departamento', $this->departamento])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'nomeResumo', $this->nomeResumo])
            ->andFilterWhere(['like', 'obs', $this->obs])
            ->andFilterWhere(['like', 'senha', $this->senha]);

        return $dataProvider;
    }
}
