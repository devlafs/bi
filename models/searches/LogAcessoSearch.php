<?php

namespace app\models\searches;

use app\models\LogItem;
use app\models\RbaAcesso;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class LogAcessoSearch extends RbaAcesso
{
    public function rules()
    {
        return [
            [['admusua_id', 'dthr_login', 'bpbi'], 'safe'],
            [['desc_ip'], 'string', 'max' => 15],
            [['desc_data'], 'string', 'max' => 20],
            [['desc_useragent'], 'string', 'max' => 150],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = RbaAcesso::find()->joinWith(['usuario']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>
            [
                'defaultOrder' =>
                [
                    'dthr_login' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) 
        {
            return $dataProvider;
        }

        $query->andWhere('rba_acesso.bpbi = TRUE');

        $query->andFilterWhere(['like', 'admin_usuario.nomeResumo', $this->admusua_id])
            ->andFilterWhere(['like', 'rba_acesso.desc_ip', $this->desc_ip]);

        return $dataProvider;
    }
}
