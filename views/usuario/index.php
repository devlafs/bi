<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\AdminPerfil;

$this->title = 'Usuários';
$this->params['breadcrumbs'][] = $this->title;

$permissao = Yii::$app->permissaoGeral;

$attributes = [];

$attributes[] = 'nomeResumo';

$attributes[] = 'login';

$attributes[] = 
[
    'attribute' => 'perfil_id',
    'filter' => ArrayHelper::map(AdminPerfil::find()->andWhere(['is_ativo' => TRUE,
        'is_excluido' => FALSE])->orderBy('nome ASC')->all(), 'id', 'nome'),
    'value' => function ($model)
    {
        return ($model->perfil) ? $model->perfil->nome : '-';
    }
];

$attributes[] = 'email';

$attributes[] =
[
    'attribute' => 'acesso_bi',
    'format' => 'boolean',
    'headerOptions' => ['class' => 'text-center'],
    'contentOptions' => ['style' => 'width: 10%;', 'class' => 'text-center'],
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
            
$attributes[] = 
[
    'class' => '\kartik\grid\ActionColumn',
    'header' => \Yii::t('app', 'view.senha'),
    'template' => '{password}',
    'buttons' =>
    [
        'password' => function ($url, $model)
        {
            return Html::a('<span class="fa fa-key"></span>', $url, [
                'title' => 'Enviar Senha',
            ]);
        }
    ],
];
    
if($permissao->can('usuario', 'update')) :
    
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
        
if($permissao->can('usuario', 'delete')) :
        
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
                'class' => 'delete-usuario',
                'data-id' => $model->id,
                'data-title' => $model->nome
            ]);
        },
    ],
];
        
$js = <<<JS
   
    $(document).delegate('.delete-usuario', 'click', function (e) 
    {
        e.preventDefault();
        var _id = $(this).data('id');
        var _title = $(this).data('title');
        
        swal({
            title: 'Exclusão de usuário',
            text: "Deseja realmente excluir o usuário '" + _title + "'?",
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
                    url: '/usuario/delete?id=' + _id,
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

<div class="usuario-index">

    <?php if($permissao->can('usuario', 'create')) : ?>
    
        <p class="text-right">
            
            <?= Html::a('<i class="fa fa-plus"></i> ' . \Yii::t('app', 'view.novo'), ['create'], ['class' => 'btn btn-primary']) ?>
            
        </p>
    
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=> "{items}<div class='row'><div class='col-lg-6'>{pager}</div><div class='col-lg-6 text-right'>{summary}</div></div>",
        'columns' => $attributes
    ]); ?>
</div>