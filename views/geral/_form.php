<?php

use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\helpers\Html;
use yii\bootstrap\Modal;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Pallete;

$img = Html::img("/" . $model->logo,  ['class'=>'file-preview-image']);

$palletes = Pallete::find()->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE])->orderBy('nome ASC')->all();

$map = [];

foreach($palletes as $pallete)
{
    $map[] = ['id' => $pallete->id, 'text' => $pallete->nome, 'bg1' => $pallete->color1, 'bg2' => $pallete->color2];
}

$mapPalletes = json_encode($map);

$select2 = <<<JS

    var _language =
    {
        errorLoading: function () {
          return 'Os resultados não puderam ser carregados.';
        },
        inputTooLong: function (args) {
          var overChars = args.input.length - args.maximum;

          var message = 'Apague ' + overChars + ' caracter';

          if (overChars != 1) {
                message += 'es';
          }

          return message;
        },
        inputTooShort: function (args) {
          var remainingChars = args.minimum - args.input.length;

          var message = 'Digite ' + remainingChars + ' ou mais caracteres';

          return message;
        },
        loadingMore: function () {
          return 'Carregando mais resultados…';
        },
        maximumSelected: function (args) {
          var message = 'Você só pode selecionar ' + args.maximum + ' ite';

          if (args.maximum == 1) {
            message += 'm';
          } else {
            message += 'ns';
          }

          return message;
        },
        noResults: function () {
          return 'Nenhum resultado encontrado';
        },
        searching: function () {
          return 'Buscando…';
        }
    };

    function format(pallete) {
        return '<i style="height: 15px; width: 15px;background: linear-gradient(135deg, #fff 0%, ' + pallete.bg1 + ' 50%, ' + pallete.bg2 + ' 51%, #fff 100%);">&nbsp;&nbsp;</i> ' + pallete.text;
    }
    
    $("#sistemaform-pallete").select2({
        data: {$mapPalletes},
        language: _language,
        templateSelection: format,
        templateResult: format,
        allowHtml: true,
        escapeMarkup: function(m) { return m; }
    });
    
JS;

$this->registerJs($select2);

?>

<style>

    .img-logo
    {
        border: 1px solid #007EC3;
        height: 200px;
        width: 200px;
        border-radius: 100px;
    }

    .file-preview-image
    {
        max-width: 200px;
        max-height: 200px;
    }

</style>

<div class="usuario-form">

    <?php $form = ActiveForm::begin(); ?>

    <p class="text-right">

        <?= Html::submitButton( \Yii::t('app', 'view.salvar'),
            [
                'class' => 'btn btn-primary',
            ]); ?>

    </p>

    <div class="card p-3">

        <div class="row">

            <div class="col-lg-3 text-center">

                <img width="100%" class="img-responsive img-logo mb-3 d-block ml-auto mr-auto" src="/<?= $model->logo ?>" />

                <?php

                    Modal::begin([
                        'toggleButton' => [
                            'label'=>'Alterar Logo', 'class'=>'btn btn-sm btn-default'
                        ],
                    ]);

                        $formLogo = ActiveForm::begin([
                            'options'=>['enctype'=>'multipart/form-data']
                        ]);

                            echo FileInput::widget([
                                'name'=>'logo',
                                'language' => 'pt-BR',
                                'pluginOptions' =>
                                [
                                    'showCaption' => false,
                                    'showRemove' => true,
                                    'showUpload' => true,
                                    'language' => 'pt-BR',
                                    'browseIcon' => '<i class="fa fa-search"></i>',
                                    'removeIcon' => '<i class="fa fa-trash"></i> ',
                                    'uploadLabel' => 'Salvar',
                                    'maxFileSize' => 1024,
                                    'maxFileCount' => 1,
                                    'initialPreview' =>
                                    [
                                        $img
                                    ],
                                    'previewSettings' =>
                                    [
                                        'width' => '200px'
                                    ],
                                    'fileActionSettings' =>
                                    [
                                        'showUpload' => FALSE,
                                        'showDownload' => FALSE,
                                        'showRemove' => FALSE,
                                        'showZoom' => FALSE,
                                        'showDrag' => FALSE
                                    ],
                                    'previewFileType' => 'image',
                                    'uploadUrl' => Url::to(['/geral/logo-upload'])
                                ],
                                'pluginEvents' =>
                                [
                                    'fileuploaded' => 'function(event, data, previewId, index){
                                        $(".img-logo").attr("src", "/" + data.response.file);
                                    }',
                                ],
                                'options' => ['accept' => 'image/*']
                            ]);

                        ActiveForm::end();

                    Modal::end();

                ?>

            </div>

            <div class="col-lg-9">

                <?= Form::widget(
                [
                    'model' => $model,
                    'form' => $form,
                    'columns' => 12,
                    'attributes' =>
                    [
                        'name' =>
                        [
                            'type' => Form::INPUT_TEXT,
                            'columnOptions' => ['colspan' => 4],
                        ],
                        'description' =>
                        [
                            'type' => Form::INPUT_TEXT,
                            'columnOptions' => ['colspan' => 8],
                        ]
                    ],
                ]); ?>

                <?= Form::widget(
                [
                    'model' => $model,
                    'form' => $form,
                    'columns' => 12,
                    'attributes' =>
                    [
                        'url' =>
                        [
                            'type' => Form::INPUT_TEXT,
                            'columnOptions' => ['colspan' => 6],
                        ],
                        'systemEmail' =>
                        [
                            'type' => Form::INPUT_TEXT,
                            'columnOptions' => ['colspan' => 6],
                        ]
                    ],
                ]); ?>

                <?= Form::widget(
                [
                    'model' => $model,
                    'form' => $form,
                    'columns' => 12,
                    'attributes' =>
                    [
                        'urlShareDaysExpiration' =>
                        [
                            'type' => Form::INPUT_TEXT,
                            'options' => ['type' => 'number'],
                            'columnOptions' => ['colspan' => 3],
                        ],
                        'emailShareDaysExpiration' =>
                        [
                            'type' => Form::INPUT_TEXT,
                            'options' => ['type' => 'number'],
                            'columnOptions' => ['colspan' => 3],
                        ],
                        'advancedFilter' =>
                        [
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'items' => [0 => 'Não', 1 => 'Sim'],
                            'columnOptions' => ['colspan' => 3],
                        ],
                        'pallete' =>
                        [
                            'type' => Form::INPUT_TEXT,
                            'columnOptions' => ['colspan' => 3],
                        ]
                    ],
                ]); ?>

                <?= Form::widget(
                    [
                        'model' => $model,
                        'form' => $form,
                        'columns' => 12,
                        'attributes' =>
                        [
                            'homepage' =>
                            [
                                'type' => Form::INPUT_TEXTAREA,
                                'options' => ['rows' => 30],
                                'columnOptions' => ['colspan' => 12],
                            ],
                        ],
                    ]); ?>

            </div>

        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>