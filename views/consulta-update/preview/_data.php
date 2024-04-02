<?php

$js = <<<JS
        
    function applyCa(e, _index, _id, _filtro, _type)
    {
        e.preventDefault();
        var _valor = [];

        $('ul#valor').children().each(function() 
        {
            _valor.push($(this).data('id'));
        });

        var _argumento = [];

        $('ul#argumento').children().each(function() 
        {
            _argumento.push({'id': $(this).data('id'), 'type': $(this).data('type'), 'sort': $(this).data('sort'), 'tipo_numero': $(this).data('tipo_numero')});
        });

        var _serie = [];

        $('ul#serie').children().each(function() 
        {
            _serie.push($(this).data('id'));
        });

        _post = {'index': _index, 'valor': _valor, 'argumento': _argumento, 'serie': _serie, 'filtro': _filtro};

        $.ajax({
            url: '/consulta/preview?id=' + _id + '&type=' + _type + '&sqlMode={$sqlMode}',
            type: 'POST',
            data: _post,
            success: function(response) 
            {
                $('.consulta__preview').html(response);
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
    }     
        
JS;

$this->registerJs($js);

?>

<div class="block--consulta__preview mx-3 align-content-between">

    <?php if($sqlMode && isset($data['sql'])) : ?>
    
        <p><?= $data['sql']; ?></p>
    
    <?php endif; ?>
    
    <?= $this->render("/consulta-update/preview/_graph", compact('index', 'data', 'model', 'sqlMode')); ?>
    
</div>

<div class="block--consulta__preview block--consulta__preview--data  mx-3 mt-3">
    
    <?php if($data) : ?>
    
        <?= $this->render("/consulta-update/preview/_table", compact('index', 'data', 'model', 'sqlMode')); ?>
    
    <?php endif; ?>
        
</div>