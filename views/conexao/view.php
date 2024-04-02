<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = \Yii::t('app', 'geral.conexao') . ': ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'view.conexoes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$length = strlen($model->senha);

$pass = '';

for($i = 0; $i < $length; $i++)
{
    $pass .= 'x';
}

?>

<div class="conexao-view">

    <p class="text-right">

        <?= Html::a('<i class="bp-arrow-left"></i> ' . \Yii::t('app', 'view.voltar'), (!empty(Yii::$app->request->referrer)) ? Yii::$app->request->referrer : ['index'], ['class' => 'btn btn-default']) ?>

        <?php if(Yii::$app->permissaoGeral->can('conexao', 'update')) : ?>

            <?= Html::a('<i class="bp-edit"></i> ' . \Yii::t('app', 'view.alterar'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?php endif; ?>        

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
        [
            'nome',
            'tipo',
            'host',
            'database',
            'porta',
            'login',
            [
                'attribute' => 'senha',
                'value' => $pass
            ],
        ],
    ]) ?>

</div>
