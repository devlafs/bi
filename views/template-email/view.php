<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\lists\TemplateEmailList;

$this->title = 'Template: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
        
    .badge-default.tags
    {
        color: #555555;
        background: #f5f5f5;
        border: 1px solid #ccc;
        border-radius: 4px;
        cursor: default;
        margin: 5px 0 0 6px;
        padding: 2px 5px;
    }
        
CSS;

$this->registerCss($css);

?>

<div class="perfil-view">

    <p class="text-right">

        <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), (!empty(Yii::$app->request->referrer)) ? Yii::$app->request->referrer : ['index'], ['class' => 'btn btn-default']) ?>

        <?php if(Yii::$app->permissaoGeral->can('template-email', 'update')) : ?>

            <?= Html::a('<i class="bp-edit"></i> ' . \Yii::t('app', 'view.alterar'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?php endif; ?>        

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
        [
            'nome',
            [
                'attribute' => 'tipo',
                'value' => TemplateEmailList::getTipos($model->tipo)
            ],
            [
                'attribute' => 'tags',
                'label' => 'Tags',
                'format' => 'raw',
                'value' => $model->getTags()
            ],
            [
                'attribute' => 'html',
                'format' => 'raw',
                'value' => $model->html
            ],
        ],
    ]) ?>

</div>
