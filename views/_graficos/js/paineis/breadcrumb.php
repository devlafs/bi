<?php

$js = <<<"JS"
        
    function getPreviousData{$square}(e, _index, _id, _token)
    {
        e.preventDefault();
        
        var _csrfToken = $('meta[name="csrf-token"]').attr("content");
        
        jQuery.ajax({
            url: {$url} + '&previous=TRUE',
            type: 'POST',
            data: 
            {
                index: _index,
                token: _token,
                _csrf: _csrfToken
            },
            success: function (data) 
            {
                $('.card-consulta{$square}').html(data);
            },
            beforeSend: function ()
            {
                $('.div-loading-{$square}').addClass("loading");
            },
            complete: function () 
            {
                setTimeout(function() { $('.div-loading-{$square}').removeClass("loading");}, 300);
            }
        });
    }
        
    $('.breadcrumb-cp{$square}').click(function(e)
    {
        var _index = $(this).data('index'); 
        var _id = parseInt('{$model_id}');
        var _token = '{$token}';
        getPreviousData{$square}(e, _index, _id, _token);
    });
        
JS;

$this->registerJs($js);