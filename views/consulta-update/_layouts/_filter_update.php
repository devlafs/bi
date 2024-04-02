<?php

use app\magic\AdvancedFilterMagic;
use app\magic\CacheMagic;

$hasAdvancedFilter = CacheMagic::getSystemData('advancedFilter');

if($hasAdvancedFilter) :

$fields = AdvancedFilterMagic::getFields($model->id);
$dataAdvanced = json_encode($fields);

?>

<style>

    .mentions {
        margin-top: 20px;
        margin-bottom: 20px;
        border: 1px solid #007EC3;
        padding: 10px;
        word-break: break-word;
    }

    .mentions strong {
        background: #007EC340;
        border: 1px solid #007EC3;
        padding: 1px;
        line-height: 25px;
    }

    .mentions-autocomplete-list ul
    {
        cursor: pointer;
        margin-right: 10px;
        background-color: white;
        border: 1px solid #aaa;
        list-style: none;
    }

    .mentions-autocomplete-list ul li
    {
        padding: 3px;
    }

    .mentions-autocomplete-list ul li:hover,
    .mentions-autocomplete-list ul li:focus
    {
        color: #007EC3;
    }

</style>

<?php endif; ?>

<div class="d-flex align-item-center align-items-stretch">

    <div class="col-lg-12 tab-pane--title">

        <h3>
                
            Filtros 
            
            <button type="button" class="btn btn-sm btn-outline-primary align-self-center text-uppercase save-filter float-right">Salvar</button>

            <button type="button" class="btn btn-sm btn-link align-self-center text-uppercase clean-filter float-right">Limpar</button>

        </h3>

        <?php if($hasAdvancedFilter) : ?>

            <ul class="nav nav-tabs" id="tab-filter" role="tablist">

                <li class="nav-item">

                    <a class="nav-link <?= (!$model->condicao_avancada || trim($model->condicao_avancada) == '') ? 'active' : '' ?>" id="default-tab" data-toggle="tab" href="#default" role="tab" aria-controls="default" aria-selected="true">Padrão</a>

                </li>

                <li class="nav-item">

                    <a class="nav-link <?= ($model->condicao_avancada && trim($model->condicao_avancada) != '') ? 'active' : '' ?>" id="advanced-tab" data-toggle="tab" href="#advanced" role="tab" aria-controls="advanced" aria-selected="false">Avançado</a>

                </li>

            </ul>

        <?php endif; ?>

    </div>

</div>

<div class="tab-content" id="myTabContent">

    <?php if($hasAdvancedFilter) : ?>

        <div class="tab-pane tab-pane--title fade <?= (!$model->condicao_avancada || trim($model->condicao_avancada) == '') ? 'show active' : '' ?>" id="default" role="tabpanel" aria-labelledby="default-tab">

    <?php else: ?>

        <div class="tab-pane--title">

    <?php endif; ?>

        <p class="text-uppercase text-center m-3">
            Para criar um filtro basta adicionar um atributo +, configurar a condição desejada e clicar em salvar.
        </p>

        <div class="d-flex align-item-start justify-content-center">

            <button class="btn btn-lg btn-primary select--plus" id="add-atribute">

                <i class="bp-plus "><span class="and-button-text">E</span></i>

            </button>

        </div>

        <div style="height: calc(100vh - 308px); overflow-y: auto;">

            <form id="form-filter">

                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

                <div class="d-flex align-items-center justify-content-center flex-column" id="filter-list__container">

                    <?= $this->render('_partials/_and', ['model' => $model, 'index' => 1, 'condicao' => $model->condicao]) ?>

                </div>

            </form>

        </div>

    </div>

    <?php if($hasAdvancedFilter) : ?>

        <div class="tab-pane tab-pane--title fade <?= ($model->condicao_avancada && trim($model->condicao_avancada) != '') ? 'show active' : '' ?>" style="overflow-y: auto;" id="advanced" role="tabpanel" aria-labelledby="advanced-tab">

            <div class="col-lg-12">

                <form id="form-advanced-filter">

                    <textarea class='mention form-control' rows="8">

                    </textarea>

                    <script>

                        $('textarea.mention').mentionsInput({
                            defaultValue: "<?= $model->condicao_avancada ?>",
                            showAvatars: false,
                            minChars: 2,
                            allowRepeat: true,
                            onDataRequest:function (mode, query, callback) {
                                var data = <?= $dataAdvanced ?>;
                                data = _.filter(data, function(item) { return item.name.toLowerCase().indexOf(query.toLowerCase()) > -1 });
                                callback.call(this, data);
                            }
                        });

                        $('textarea.mention').val('');

                    </script>

                </form>

            </div>

        </div>

    <?php endif; ?>

</div>



