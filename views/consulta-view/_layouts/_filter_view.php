<div class="d-flex align-item-center align-items-stretch">

    <div class="col-lg-12 tab-pane--title">

        <h3>
                
            Filtros 
            
            <button type="button" class="btn btn-sm btn-outline-primary align-self-center text-uppercase save-filter float-right">Salvar</button>

            <button type="button" class="btn btn-sm btn-link align-self-center text-uppercase clean-filter float-right">Limpar</button>

        </h3>

        <p class="text-uppercase">Para criar um filtro basta adicionar um atributo

            <i class="bp-plus"></i>, configurar a condição desejada e clicar em salvar.

        </p>

    </div>

</div>

<div class="d-flex align-item-start justify-content-center">

    <button class="btn btn-lg btn-primary select--plus" id="add-atribute">

        <i class="bp-plus "><span class="and-button-text">E</span></i> 

    </button>

</div>

<div style="height: calc(100vh - 308px); overflow-y: auto;">

    <form id="form-filter">

        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

        <div class="d-flex align-items-center justify-content-center flex-column" id="filter-list__container">

            <?= $this->render('_partials/_and', ['model' => $model, 'consulta' => $consulta, 'index' => 1, 'condicao' => $model->condicao]) ?>

        </div>

    </form>

</div>