<?php

namespace app\models\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Email;

class EmailSearch extends Email
{
    public $destinatario;
    
    public function rules()
    {
        return [
            [['id', 'id_usuario', 'id_perfil', 'frequencia', 'hora', 'dia_semana', 'dia_mes'], 'integer'],
            [['id_consulta', 'id_painel', 'destinatario', 'assunto', 'email', 'created_at', 'id_template', 'updated_at', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($t = 'consulta', $params)
    {
        return ($t == 'consulta') ? $this->searchConsulta($params) : $this->searchPainel($params);
    }
    
    public function searchConsulta($params)
    {
        $query = Email::find()->joinWith(['consulta', 'template']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>
            [
                'defaultOrder' =>
                [
                    'assunto' => SORT_ASC
                ]
            ],
            'pagination' =>
            [
                'pageSize' => 10
            ]
            
        ]);
        
        $dataProvider->sort->attributes['id_consulta'] = [
            'asc' => ['bpbi_consulta.nome' => SORT_ASC],
            'desc' => ['bpbi_consulta.nome' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['id_template'] = [
            'asc' => ['bpbi_template_email.nome' => SORT_ASC],
            'desc' => ['bpbi_template_email.nome' => SORT_DESC],
        ];
                
        $this->load($params);
        
        $query->andWhere('id_consulta is not null');

        if (!$this->validate()) 
        {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_usuario' => $this->id_usuario,
            'id_perfil' => $this->id_perfil,
            'frequencia' => $this->frequencia,
            'hora' => $this->hora,
            'dia_semana' => $this->dia_semana,
            'dia_mes' => $this->dia_mes,
            'bpbi_email.is_ativo' => $this->is_ativo,
            'bpbi_email.is_excluido' => FALSE,
        ]);
        
        $query->andFilterWhere(['like', 'assunto', $this->assunto])
            ->andFilterWhere(['like', 'bpbi_consulta.nome', $this->id_consulta])
            ->andFilterWhere(['like', 'bpbi_template_email.nome', $this->id_template])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
    
    public function searchPainel($params)
    {
        $query = Email::find()->joinWith(['painel', 'template']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>
            [
                'defaultOrder' =>
                [
                    'assunto' => SORT_ASC
                ]
            ],
            'pagination' =>
            [
                'pageSize' => 10
            ]
            
        ]);
        
        $dataProvider->sort->attributes['id_painel'] = [
            'asc' => ['bpbi_painel.nome' => SORT_ASC],
            'desc' => ['bpbi_painel.nome' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['id_template'] = [
            'asc' => ['bpbi_template_email.nome' => SORT_ASC],
            'desc' => ['bpbi_template_email.nome' => SORT_DESC],
        ];
        
        $this->load($params);
        
        $query->andWhere('id_painel is not null');

        if (!$this->validate()) 
        {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'bpbi_email.id' => $this->id,
            'id_usuario' => $this->id_usuario,
            'id_perfil' => $this->id_perfil,
            'frequencia' => $this->frequencia,
            'hora' => $this->hora,
            'dia_semana' => $this->dia_semana,
            'dia_mes' => $this->dia_mes,
            'bpbi_email.is_ativo' => $this->is_ativo,
            'bpbi_email.is_excluido' => FALSE,
        ]);

        $query->andFilterWhere(['like', 'assunto', $this->assunto])
            ->andFilterWhere(['like', 'bpbi_painel.nome', $this->id_painel])
            ->andFilterWhere(['like', 'bpbi_template_email.nome', $this->id_template])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
