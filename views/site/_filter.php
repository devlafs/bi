<?php

$options = '';

foreach($data->elementos as $elemento)
{
    $options .= '<option value="' . $elemento->nome . '">' . $elemento->nome .'</option>';
}

$js = <<<JS
        
    $(document).delegate('.remove-atribute', 'click', function(e) 
    {
        e.preventDefault();
        var _order = $(this).data('order');
        var _cors = $(this).data('cors');
        
        if($('#ul-filter-list__atribute__' + _cors + ' li').length <= 1)
        {
            $('#ul-filter-list__atribute__' + _cors).slideUp("slow", function() 
            {
                $(this).remove();
            });
        }
        else
        {
            $('#li-filter-list__atribute__' + _order).slideUp("slow", function() 
            {
                $(this).remove();
            });            
        
            if($('#form-filter #ul-filter-list__atribute__' + _cors + ' li').find('.add-and-attribute').length == 1)
            {
                var _order = $('#form-filter #ul-filter-list__atribute__' + _cors + ' li').eq(-2).data('order');
                $('#form-filter #ul-filter-list__atribute__' + _cors + ' li').eq(-2).append('<button class="bnt-add__and add-and-attribute" data-order="' + _order + '" data-cors="' + _cors + '"><i class="bp-plus"></i> Novo Operador</button>');
            }
        }
    });
                                
    $(document).delegate('#add-or-attribute', 'click', function(e) 
    {
        e.preventDefault();
        var _order = $(this).data('order');
        var _cors = $('#form-filter ul').length;

        var _index = _order + 1;
                                
        var _html = '<ul class="list-group filter-list__atribute" id="ul-filter-list__atribute__' + _cors + '" data-order="' + _index + '" data-cors="' + _cors + '">' +
            '<li class="list-group-item" id="li-filter-list__atribute__' + _index + '" data-order="' + _index + '" data-cors="' + _cors + '">' + 
            '<button class="remove-atribute" data-order="' + _index + '" data-cors="' + _cors + '"><i class="bp-close"></i></button>' + 
            '<div class="d-flex w-100 flex-column justify-content-between">' + 
                '<div class="d-flex" style="width: 100%; margin-top: 10px;">' + 
                    '<div class="d-inline-flex" style="min-width:200px;">' + 
                        '<select name="Filter[' + _cors + '][' + _index + '][elementoNome]" id="selectpicker_elemento_' + _index + '" style="width:200px;">' + 
                            '{$options}' +
                        '</select>' + 
                    '</div>' + 
                    '<div class="d-inline-flex ml-3">' + 
                        '<select name="Filter[' + _cors + '][' + _index + '][operador]" id="selectpicker_operador_' + _index + '" style="width:120px;">' + 
                            '<option id="maiorigual">Maior Igual</option>' + 
                            '<option id="contem">Contém</option>' + 
                            '<option id="igual">Igual à</option>' + 
                            '<option id="diferente">Diferente</option>' + 
                            '<option id="naocontem">Não Contem</option>' + 
                            '<option id="maior">Maior</option>' + 
                            '<option id="menor">Menor</option>' + 
                            '<option id="menorigual">Menor Igual</option>' + 
                        '</select>' + 
                    '</div>' + 
                '</div>' + 
                '<div class="d-flex align-item-end mt-2 text-center">' + 
                    '<input type="text" class="form-control" placeholder="Digite o valor" name="Filter[' + _cors + '][' + _index + '][valor]" id="selectpicker_valor_' + _index + '" style="width:100%; margin-left: auto; margin-right: auto;" />' + 
                '</div>' + 
            '</div>' + 
            '<button class="bnt-add__and add-and-attribute" data-order="' + _index + '" data-cors="' + _cors + '"><i class="bp-plus"></i> Novo Operador</button>' +
        '</li>' +
        '</ul>';
                            
        $('#form-filter').append(_html);
                                
        $('#filter-list__atribute__' + _index).slideDown('slow');
                                
        $(this).data('order', _index);
                                
        $("#selectpicker_elemento_" + _index).select2({
            theme: "bp1"
        });
        $("#selectpicker_operador_" + _index).select2({
            theme: "bp1",
            minimumResultsForSearch: Infinity
        });
    });
                                
    $(document).delegate('.add-and-attribute', 'click', function(e) 
    {
        e.preventDefault();
        var _order = $(this).data('order');
        var _cors = $(this).data('cors');

        var _index = _order + 1;
                                
        var _html = '<li class="list-group-item" id="li-filter-list__atribute__' + _index + '" data-order="' + _index + '" data-cors="' + _cors + '">' + 
            '<button class="remove-atribute" data-order="' + _index + '" data-cors="' + _cors + '"><i class="bp-close"></i></button>' + 
            '<div class="d-flex w-100 flex-column justify-content-between">' + 
                '<div class="d-flex" style="width: 100%; margin-top: 10px;">' + 
                    '<div class="d-inline-flex" style="min-width:200px;">' + 
                        '<select name="Filter[' + _cors + '][' + _index + '][elementoNome]" id="selectpicker_elemento_' + _index + '" style="width:200px;">' + 
                            '{$options}' +
                        '</select>' + 
                    '</div>' + 
                    '<div class="d-inline-flex ml-3">' + 
                        '<select name="Filter[' + _cors + '][' + _index + '][operador]" id="selectpicker_operador_' + _index + '" style="width:120px;">' + 
                            '<option value="maiorigual">Maior Igual</option>' + 
                            '<option value="contem">Contém</option>' + 
                            '<option value="igual">Igual à</option>' + 
                            '<option value="diferente">Diferente</option>' + 
                            '<option value="naocontem">Não Contem</option>' + 
                            '<option value="maior">Maior</option>' + 
                            '<option value="menor">Menor</option>' + 
                            '<option value="menorigual">Menor Igual</option>' + 
                        '</select>' + 
                    '</div>' + 
                '</div>' + 
                '<div class="d-flex align-item-end mt-2 text-center">' + 
                    '<input type="text" class="form-control" name="Filter[' + _cors + '][' + _index + '][valor]" id="selectpicker_valor_' + _index + '" style="width:80%; margin-left: auto; margin-right: auto;" />' + 
                '</div>' + 
            '</div>' + 
            '<button class="bnt-add__and add-and-attribute" data-order="' + _index + '" data-cors="' + _cors + '"><i class="bp-plus"></i> Novo Operador</button>' +
        '</li>';
                            
        $('#form-filter #ul-filter-list__atribute__' + _cors + ' li').find('.add-and-attribute').remove();

        $('#form-filter #ul-filter-list__atribute__' + _cors).append(_html);
        
        $('#form-filter #ul-filter-list__atribute__' + _cors).slideDown('slow');
                                
        $('#add-or-attribute').data('order', _index);
        $(this).data('order', _index);
        $(this).data('cors', _cors);
                                
        $("#selectpicker_elemento_" + _index).select2({
            theme: "bp1"
        });
                            
        $("#selectpicker_operador_" + _index).select2({
            theme: "bp1",
            minimumResultsForSearch: Infinity
        });
    });
        
    $(document).delegate('.save-filter', 'click', function(e) 
    {
        e.preventDefault();
        
        jQuery.ajax({
            url: '/site/save-filter',
            data: {data: JSON.stringify($('#form-filter').serializeArray()), url: '{$url}'},
            success: function (data) 
            {
                $('.sidebar--painel').toggleClass('block__slide');
            
                iziToast.success({
                    title: 'Parabéns!',
                    message: 'Os Filtros salvos com sucesso',
                    position: 'topCenter',
                    close: true,
                    transitionIn: 'flipInX',
                    transitionOut: 'flipOutX',
                });
            },
            beforeSend: function ()
            {
                $('.div-loading').addClass("loading");
            },
            complete: function () 
            {
                setTimeout(function() { $('.div-loading').removeClass("loading");}, 300);
            }
        });
    });
            
    $('#form-filter').on('keyup keypress', function(e) 
    {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) 
        { 
          e.preventDefault();
          return false;
        }
    });

JS;
                
$this->registerJs($js);

?>

<div class="tab-pane active" id="filtro" role="tabpanel">
    
    <div class="d-flex align-item-center align-items-stretch">
        <div class="col-lg-12 tab-pane--title">
            <h3>Crie Filtros</h3>
            <p class="text-uppercase">Para criar um filtro basta adicionar um atributo <i class="bp-plus"></i> e configurar a condição desejada.</p>
        </div>
    </div>
    
    <div class="d-flex align-item-start justify-content-center">
        <button class="btn btn-lg btn-primary select--plus" id="add-or-attribute" data-order="<?= count($data->filtros) ?>" data-toggle="tooltip " title="Adicione um novo atributo."><i class="bp-plus "></i></button>
    </div>
    
    <div style="overflow-y:auto; height: calc(100vh - 308px);">
        
        <div class="d-flex align-items-center justify-content-center flex-column" id="filter-list__container">
                        
            <form id="form-filter">
            
                <?php 
                
                $ou = TRUE; 
                $cors = 0;
                
                foreach($data->filtros as $index => $filtro) :
                
                    if($ou) : ?>
                
                        <ul class="list-group filter-list__atribute" id="ul-filter-list__atribute__<?= $cors ?>" data-order="<?= $index ?>" data-cors="<?= $cors ?>">
                                
                    <?php endif; ?>
                            
                        <li class="list-group-item" id="li-filter-list__atribute__<?= $index ?>" data-order="<?= $index ?>" data-cors="<?= $cors ?>">
                            <button class="remove-atribute" data-order="<?= $index ?>" data-cors="<?= $cors ?>"><i class="bp-close"></i></button>
                            <div class="d-flex w-100 flex-column justify-content-between">

                                <div class="d-flex" style="width: 100%; margin-top: 10px;">
                                    <div class="d-inline-flex" style="min-width:200px;">
                                        <select name="Filter[<?= $cors ?>][<?= $index ?>][elementoNome]" id="selectpicker_elemento_<?= $index ?>" style="width:200px;">

                                            <?php foreach($data->elementos as $elemento) : ?>

                                                <option <?= ($elemento->nome == $filtro->elementoNome) ? "selected='selected'" : "" ?> value="<?= $elemento->nome ?>"><?= $elemento->nome ?></option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>
                                    <div class="d-inline-flex ml-3">
                                        <select name="Filter[<?= $cors ?>][<?= $index ?>][operador]" id="selectpicker_operador_<?= $index ?>" style="width:120px;">
                                            <option value="maiorigual" <?= ($filtro->operador == '>=') ? "selected='selected'" : "" ?>>Maior Igual</option>
                                            <option value="contem" <?= ($filtro->operador == 'like') ? "selected='selected'" : "" ?>>Contém</option>
                                            <option value="igual" <?= ($filtro->operador == '=') ? "selected='selected'" : "" ?>>Igual à</option>
                                            <option value="diferente" <?= ($filtro->operador == '!=') ? "selected='selected'" : "" ?>>Diferente</option>
                                            <option value="naocontem" <?= ($filtro->operador == 'not like') ? "selected='selected'" : "" ?>>Não Contem</option>
                                            <option value="maior" <?= ($filtro->operador == '>') ? "selected='selected'" : "" ?>>Maior</option>
                                            <option value="menor" <?= ($filtro->operador == '<') ? "selected='selected'" : "" ?>>Menor</option>
                                            <option value="menorigual" <?= ($filtro->operador == '<=') ? "selected='selected'" : "" ?>>Menor Igual</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex align-item-end mt-2 text-center">
                                    <input type="text" class="form-control" name="Filter[<?= $cors ?>][<?= $index ?>][valor]" value="<?= $filtro->valor ?>" id="selectpicker_valor_<?= $index ?>" style="width:80%; margin-left: auto; margin-right: auto;" />
                                </div>

                            </div>
                            
                            <?php $ou = ($filtro->operadorLogico == 'or') ? TRUE : FALSE; ?>
                            
                            <?php if($ou || count($data->filtros) == ($index + 1)) : 
                                
                            ?>

                                <button class="bnt-add__and add-and-attribute" data-order="<?= $index ?>" data-cors="<?= $cors ?>"><i class="bp-plus"></i> Novo Operador</button>

                                </li>

                            </ul>

                            <?php 
                            
                            $cors += 1;
                            
                            else: ?>

                                </li>
                                
                            <?php endif; 
                            
                            $js = <<<JS

                            $("document").ready(function()
                            { 
                                $("#selectpicker_elemento_{$index}").select2({
                                    theme: "bp1"
                                });
                                
                                $("#selectpicker_operador_{$index}").select2({
                                    theme: "bp1",
                                    minimumResultsForSearch: Infinity
                                });
                            });
JS;
                    $this->registerJs($js, \yii\web\View::POS_READY);
                    
                    ?>

                <?php endforeach; ?>
                
            </form>

        </div>
    </div>
</div>