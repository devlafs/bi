<?php

namespace app\models\queries;

use Yii;
use app\models\IndicadorCampo;
use app\models\ConsultaItem;

class ConsultaQuery extends \yii\db\ActiveQuery
{
    public function getUnusedFields($id_indicador, $id_consulta)
    {
        $subQuery = (new \yii\db\Query)
                ->select([new \yii\db\Expression('1')])
                ->from('bpbi_consulta_item item')
                ->andWhere(['id_consulta' => $id_consulta])
                ->andWhere('item.id_campo = bpbi_indicador_campo.id');
        
        $query = IndicadorCampo::find()
            ->andWhere(['id_indicador' => $id_indicador])
            ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE])
            ->andWhere(['not exists', $subQuery])->orderBy('nome ASC');

        return $query->all();
    }
    
    public function getValueFields($id_consulta)
    {
        $query = ConsultaItem::find()
            ->andWhere(['id_consulta' => $id_consulta, 'parametro' => 'valor'])
            ->orderBy('ordem ASC');

        return $query->all();
    }
    
    public function getArgFields($id_consulta)
    {
        $query = ConsultaItem::find()
            ->andWhere(['id_consulta' => $id_consulta, 'parametro' => 'argumento'])
            ->orderBy('ordem ASC');

        return $query->all();
    }
    
    public function getSerieFields($id_consulta)
    {
        $query = ConsultaItem::find()
            ->andWhere(['id_consulta' => $id_consulta, 'parametro' => 'serie'])
            ->orderBy('ordem ASC');

        return $query->all();
    }
}
