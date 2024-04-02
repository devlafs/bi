<?php

namespace app\models\searches;

use app\models\RelatorioCampo;
use yii\base\Model;
use Yii;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class RelatorioDinamicoSearch extends Model
{
    public $relatorio;

    public $campos;

    public $filtros;

    private $_dynamicData = [];

    private $_dynamicFields = [];

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), self::getDynamicAttributeLabels());
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), self::getDynamicRules());
    }

    public function getFields()
    {
        $fields = [];

        if($this->filtros)
        {
            foreach ($this->filtros as $filtro)
            {
                $f = RelatorioCampo::findOne($filtro[1]['field']);
                $fields[] = $f;
            }
        }

        return $fields;
    }

    public function getLabels()
    {
        return ($this->campos) ? $this->campos : [];
    }

    public function getDynamicRules()
    {
        $safe = $rules = [];

        foreach ($this->fields as $field)
        {
            $safe[] = 'dynamic_' . $field->id;
        }

        if (sizeof($safe) > 0)
        {
            $rules[] =
                [
                    $safe,
                    'safe'
                ];
        }

        return $rules;
    }

    public function getDynamicAttributeLabels()
    {
        $attributes = [];

        foreach ($this->fields as $field)
        {
            $attributes = ArrayHelper::merge($attributes,
                [
                    'dynamic_' . $field->id => $field->nome
                ]);
        }

        return $attributes;
    }

    public function __get($name)
    {
        if (! empty($this->_dynamicFields[$name]))
        {
            if (! empty($this->_dynamicData[$name]))
            {
                return $this->_dynamicData[$name];
            }
            else
            {
                return null;
            }
        }
        else
        {
            return parent::__get($name);
        }
    }

    public function __set($name, $value)
    {
        if (isset($this->_dynamicFields[$name]))
        {
            $this->_dynamicData[$name] = $value;
        }
        else
        {
            parent::__set($name, $value);
        }
    }

    public function getAttributesDynamicFields()
    {
        $this->_dynamicFields = [];

        foreach ($this->fields as $field)
        {
            $this->_dynamicFields = ArrayHelper::merge($this->_dynamicFields,
                [
                    'dynamic_' . $field->id => $field->id
                ]);
        }

        return $this->_dynamicFields;
    }

    public function search($params, $show_sql = false)
    {
        $this->load($params);

        $sql = $this->relatorio->relatorio->sql;

        if($this->fields)
        {
            $where = '';
            $idx = 0;

            foreach ($this->fields as $field)
            {
                $filtro = 'dynamic_' . $field->id;

                if($this->{$filtro})
                {
                    $where .= ($idx > 0) ? " AND `{$field->campo}` like '%{$this->{$filtro}}%'" : " WHERE `{$field->campo}` like '%{$this->{$filtro}}%'";

                    $idx++;
                }
            }

            $sql = "SELECT * FROM ({$sql}) as v {$where}";
        }

//        preg_match_all('#\[(.*?)\]#', $sql, $filters);
//
//        if($filters)
//        {
//            foreach ($filters[0] as $filter)
//            {
//                preg_match('/#(\w+)/',$filter,$hashids);
//
//                if($hashids)
//                {
//                    $id = substr($hashids[0], 1);
//                    $field = "dynamic_{$id}";
//
//                    if($this->{$field})
//                    {
//                        $filter_n = substr(str_replace("#{$id}#", $this->{$field}, $filter),1 ,-1);
//                        $sql = str_replace($filter, $filter_n, $sql);
//                    }
//                    else
//                    {
//                        $sql = str_replace($filter, "", $sql);
//                    }
//                }
//            }
//        }

        if($show_sql)
        {
            var_dump($sql);die;
        }

        $attributes = [];

        if(isset($this->labels['x']))
        {
            foreach ($this->labels['x'] as $label)
            {
                $attributes[] = $label['campo']['nome'];
            }
        }

        if(isset($this->labels['y']))
        $attributes[] = $this->labels['y']['campo']['nome'];

        try {
            $conn = $this->relatorio->relatorio->conexao->getConnection();

            $data = $conn->createCommand($sql)->queryAll();

            $provider = new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => false,
                'sort' => [
                    'attributes' => $attributes,
                ],
            ]);
        }
        catch (Exception $e)
        {
            $provider =  null;
        }

        return $provider;
    }
}
