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
        
        _post = {'index': _index, 'valor': _valor, 'argumento': _argumento, 'filtro': _filtro};

        $.ajax({
            url: '/relatorio-data/preview?id=' + _id + '&type=' + _type + '&sqlMode={$sqlMode}',
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

<style>
    .preview__data--content {
        height: calc(100vh - 70px) !important;
    }
</style>

<div class="block--consulta__preview mx-3 align-content-between">

    <?= $this->render("/relatorio-update/preview/_table", compact('index', 'data', 'model', 'sqlMode')); ?>
    
</div>