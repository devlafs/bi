<?php

namespace app\models\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AdminPerfil;

class AdminPerfilSearch extends AdminPerfil
{
    public function rules()
    {
        return [
            [['id', 'quantidade_usuarios'], 'integer'],
            [['nome', 'descricao', 'acesso_bi', 'is_admin', 'created_at', 'updated_at', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = AdminPerfil::find();

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

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'acesso_bi' => $this->acesso_bi,
            'is_admin' => $this->is_admin,
            'is_ativo' => $this->is_ativo,
            'is_excluido' => FALSE,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'descricao', $this->descricao]);

        return $dataProvider;
    }
}
