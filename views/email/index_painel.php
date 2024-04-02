<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\Email;
use app\lists\FrequenciaList;

$this->title = 'Emails - Painéis';
$this->params['breadcrumbs'][] = $this->title;

$permissao = Yii::$app->permissaoGeral;

$attributes = [];

$attributes[] = 
[
    'label' => '',
    'filter' => FALSE,
    'format' => 'raw',
    'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
    'contentOptions' => ['style' => 'text-align: center;'],
    'value' => function ($model)
    {
        $class = "badge badge-success";
        $text = "Ativo";
        
        if(!$model->is_ativo)
        {
            $class = "badge badge-danger";
            $text = "Inativo";
        }
        elseif($model->id_painel && (!$model->painel->is_ativo || $model->painel->is_excluido))
        {
            $class = "badge badge-danger";
            $text = "Painel Inativo";
        }
        
        return '<span class="' . $class . '">' . $text . '</span>';
    }
];

$attributes[] =
[
    'header' => '<a style="color: #007EC3;">Destinatário</a>',
    'attribute' => 'destinatario',
    'format' => 'raw',
    'filter' => FALSE,
    'value' => function ($model)
    {
        return $model->getDestinatario();
    }
];

$attributes[] = 'assunto';

$attributes[] =
[
    'attribute' => 'id_painel',
    'value' => function ($model)
    {
        return $model->painel->nome;
    }
];

$attributes[] =
[
    'attribute' => 'id_template',
    'value' => function ($model)
    {
        return ($model->id_template) ? $model->template->nome : '';
    }
];

$attributes[] =
[
    'attribute' => 'frequencia',
    'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
    'contentOptions' => ['style' => 'text-align: center;'],
    'filter' => Email::$frequencias,
    'value' => function ($model)
    {
        $frequencias = $model::$frequencias;
        return isset($frequencias[$model->frequencia]) ? $frequencias[$model->frequencia] : $model->frequencia;
    }
];

$attributes[] =
[
    'attribute' => 'hora',
    'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
    'contentOptions' => ['style' => 'text-align: center;'],
    'filter' => FrequenciaList::getHoras(),
    'value' => function ($model)
    {
        return FrequenciaList::getNomeHora($model->hora);
    }
];

$attributes[] = 
[
    'attribute' => 'sent_at',
    'filter' => FALSE,
    'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
    'contentOptions' => ['style' => 'text-align: center;'],
    'format' => 'raw',
    'value' => function ($model)
    {
        return ($model->sent_at) ? Yii::$app->formatter->asDate($model->sent_at, 'php:d/m/Y H:i') : '';
    },
];

$attributes[] =
[
    'class' => '\kartik\grid\ActionColumn',
    'header' => 'Enviar',
    'template' => '{send}',
    'buttons' =>
    [
        'send' => function ($url, $model)
        {
            if((!$model->is_ativo) || 
            ($model->id_consulta && (!$model->consulta->indicador->is_ativo || $model->consulta->indicador->is_excluido))
            || ($model->id_consulta && (!$model->consulta->is_ativo || $model->consulta->is_excluido)) 
            || ($model->id_painel && (!$model->painel->is_ativo || $model->painel->is_excluido)))
            {
                return '<span class="fa fa-ban" title="Não é possível enviar o email"></span>';
            }
            
            return Html::a('<span class="fa fa-paper-plane"></span>', $url, [
                'title' => 'Enviar',
            ]);
        }
    ],
];
    
$attributes[] =
[
    'class' => '\kartik\grid\ActionColumn',
    'header' => \Yii::t('app', 'view.visualizar'),
    'template' => '{view}',
    'buttons' =>
    [
        'view' => function ($url, $model)
        {
            return Html::a('<span class="fa fa-eye"></span>', $url, [
                'title' => \Yii::t('app', 'view.visualizar'),
            ]);
        }
    ],
];
    
if($permissao->can('email', 'update')) :
    
$attributes[] =    
[
    'class' => '\kartik\grid\ActionColumn',
    'header' => \Yii::t('app', 'view.editar'),
    'template' => '{update}',
    'buttons' => 
    [
        'update' => function ($url, $model) {
            return Html::a('<i class="bp-edit"></i>', $url, [
                'title' => \Yii::t('app', 'view.alterar'),
            ]);
        },
    ],
];
                  
endif;
        
if($permissao->can('email', 'delete')) :
      
$attributes[] =        
[
    'class' => '\kartik\grid\ActionColumn',
    'header' => \Yii::t('app', 'view.excluir'),
    'template' => '{delete}',
    'buttons' => 
    [
        'delete' => function ($url, $model) {
            return Html::a('<i class="fa fa-trash"></i>', 'javascript:', [
                'title' => \Yii::t('app', 'view.excluir'),
                'class' => 'delete-email',
                'data-id' => $model->id,
                'data-title' => $model->assunto
            ]);
        },
    ],
];

$js = <<<JS
   
    $(document).delegate('.delete-email', 'click', function (e) 
    {
        e.preventDefault();
        var _id = $(this).data('id');
        var _title = $(this).data('title');
        
        swal({
            title: 'Exclusão de email',
            text: "Deseja realmente excluir o email '" + _title + "'?",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#007EC3',
            confirmButtonText: 'Excluir'
        }).then((result) => 
        {
            if (result.value) 
            {
                $.ajax({
                    url: '/email/delete?id=' + _id,
                    type: 'GET',
                    success: function (_data) 
                    {
                        location.reload();
                    }
                })
            }
        })
    });
        
JS;

$this->registerJs($js);

endif;

?>

<div class="email-index">
    
    <ul class="nav nav-tabs" id="email-tab" role="tablist">
        
        <li class="nav-item">
     
            <a class="nav-link" id="consulta-tab" href="/email/index?t=consulta">Consulta</a>
        
        </li>
        
        <li class="nav-item">
        
            <a class="nav-link active" id="consulta-tab" href="/email/index?t=painel">Painel</a>
        
        </li>
        
    </ul>
    
    <div class="tab-content mt-2" id="email-tab-content">
    
        <div class="tab-pane fade show active" id="painel" role="tabpanel" aria-labelledby="painel-tab">
            
            <?php if($permissao->can('email', 'create')) : ?>
    
                <p class="text-right">

                    <?= Html::a('<i class="fa fa-plus"></i> Novo (Painel)', ['create', 't' => 'painel'], ['class' => 'btn btn-primary']) ?>

                </p>

            <?php endif; ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout'=> "{items}<div class='row'><div class='col-lg-6'>{pager}</div><div class='col-lg-6 text-right'>{summary}</div></div>",
                'columns' => $attributes
            ]); ?>
            
        </div>
              
    </div>
    
</div>