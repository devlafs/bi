<?php

$js = <<<"JS"
        
    function getPreviousData(e, _index, _id, _token)
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
                $('.card-consulta').html(data);
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
        
    $(document).unbind('keydown').keydown(function(e)
    {
        var _index = parseInt('{$index}');
        
        if (_index > 0 && e.keyCode == 37) 
        { 
            var _id = parseInt('{$model_id}');
            var _token = '{$token}';
            getPreviousData(e, _index - 1, _id, _token);
        }    
    });
        
    $('.breadcrumb-cp').click(function(e)
    {
        var _index = $(this).data('index'); 
        var _id = parseInt('{$model_id}');
        var _token = '{$token}';
        getPreviousData(e, _index, _id, _token);
    });
        
JS;

$this->registerJs($js);