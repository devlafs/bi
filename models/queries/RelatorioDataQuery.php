<?php

namespace app\models\queries;

use app\models\RelatorioCampo;
use Yii;
use app\models\RelatorioDataItem;

class RelatorioDataQuery extends \yii\db\ActiveQuery
{
    public function getUnusedFields($id_relatorio, $id_data)
    {
        $subQuery = (new \yii\db\Query)
                ->select([new \yii\db\Expression('1')])
                ->from('bpbi_relatorio_data_item item')
                ->andWhere(['id_relatorio_data' => $id_data])
                ->andWhere('item.id_campo = bpbi_relatorio_campo.id');
        
        $query = RelatorioCampo::find()
            ->andWhere(['id_relatorio' => $id_relatorio])
            ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE])
            ->andWhere(['not exists', $subQuery])->orderBy('nome ASC');

        return $query->all();
    }
    
    public function getValueFields($id_data)
    {
        $query = RelatorioDataItem::find()
            ->andWhere(['id_relatorio_data' => $id_data, 'parametro' => 'valor'])
            ->orderBy('ordem ASC');

        return $query->all();
    }
    
    public function getArgFields($id_data)
    {
        $query = RelatorioDataItem::find()
            ->andWhere(['id_relatorio_data' => $id_data, 'parametro' => 'argumento'])
            ->orderBy('ordem ASC');

        return $query->all();
    }
}
