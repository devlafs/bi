<?php

$this->title = 'Consulta::: ' . $model->nome;

$can_generate_url = Yii::$app->permissaoConsulta->can($model->id, 'ajax', 'generate-url-publica');

$can_export = Yii::$app->permissaoConsulta->can($model->id, 'consulta', 'export-pdf') && 
        Yii::$app->permissaoConsulta->can($model->id, 'consulta', 'export-excel') &&
        Yii::$app->permissaoConsulta->can($model->id, 'consulta', 'export-csv');

$can_send_email = Yii::$app->permissaoConsulta->can($model->id, 'ajax', 'send-url-publica');

$can_filter_graph = Yii::$app->permissaoConsulta->can($model->id, 'ajax', 'open-filter-view');

$can_change_graph = Yii::$app->permissaoConsulta->can($model->id, 'ajax', 'change-user-graph');

if($can_filter_graph) :
    
$open_filter_call = <<<JS
        
    $(".sidebar--painel .tab-content #filtro").html('');
    var _id = $(this).data('id');

    jQuery.ajax({
        url: '/ajax/open-filter-view?id=' + _id,
        success: function (_data) 
        {
            $(".sidebar--painel .tab-content #filtro").html(_data);

            $(".sidebar--painel").toggleClass("block__slide");
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
        
JS;

else: 
    
$open_filter_call = <<<JS
            
    $(".sidebar--painel .tab-content #filtro").html('');
    $(".sidebar--painel").toggleClass("block__slide");
   
JS;
    
endif;

$js = <<<JS
   
    $(function() 
    {
        $('.card-consulta').matchHeight();
    });
    
    $(".open-config").click(function(e) 
    {
        e.preventDefault();
        
        if($(".sidebar--painel").hasClass("block__slide"))
        {
            {$open_filter_call}
        }
        else
        {
            $(".sidebar--painel").toggleClass("block__slide");
        }
    });
    
    $(".close-painel").click(function(e) 
    {
        e.preventDefault();
        $(".sidebar--painel").toggleClass("block__slide");
    });
    
JS;

$this->registerJs($js);

if($can_generate_url) :

if($model->privado)
{
    $swal_success = <<<JS

        swal({
            type: 'success',
            confirmButtonColor: '#007EC3',
            title: 'Url gerada com sucesso!',
            html:
            '<hr><br><p style="font-weight: bold; font-size: 16px;">Url privada:</p><p><b>' + _data.url + '</b></p><br><p style="font-weight: bold; font-size: 16px;">Chave de acesso:</p><p><b>' + _data.pass + '</b></p>',
        });

JS;
}
else
{
    $swal_success = <<<JS

        swal({
            type: 'success',
            confirmButtonColor: '#007EC3',
            title: 'Url gerada com sucesso!',
            html:
            '<hr><br><p style="font-weight: bold; font-size: 16px;">Url pública:</p><p><b>' + _data.url + '</b></p>',
        });

JS;
}

$js_generate_url = <<<JS
        
    $(document).delegate("#generate-url", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();
        
        var _id = $(this).data('id');
        
        jQuery.ajax({
            url: '/ajax/generate-url-publica?id_consulta=' + _id + '&id_painel=null&view=1',
            type: 'json',
            success: function (_data) 
            {
                if(_data.success)
                {
                    {$swal_success}
                }
                else
                {
                    iziToast.error({
                        title: 'Erro ao gerar a URL!',
                        message: 'Favor entrar em contato com o administrador do sistema!',
                        position: 'topCenter',
                        close: true,
                        transitionIn: 'flipInX',
                        transitionOut: 'flipOutX',
                    });
                }
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

    $(document).delegate("#generate-wpp", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();
        
        var _id = $(this).data('id');
        
        jQuery.ajax({
            url: '/ajax/generate-url-publica?id_consulta=' + _id + '&id_painel=null&view=1',
            type: 'json',
            success: function (_data) 
            {
                if(_data.success)
                {
                    swal({
                        type: 'question',
                        confirmButtonColor: '#007EC3',
                        title: 'Compartilhar a consulta via whatsapp:',
                        showConfirmButton: false,
                        html:
                        '<a class="btn btn-success" style="color: #FFF !important" target="_blank" href="https://api.whatsapp.com/send?text=' + encodeURIComponent(_data.url) + '">Compartilhar</a>',
                    });
                }
                else
                {
                    iziToast.error({
                        title: 'Erro ao gerar ao compartilhar consulta!',
                        message: 'Favor entrar em contato com o administrador do sistema!',
                        position: 'topCenter',
                        close: true,
                        transitionIn: 'flipInX',
                        transitionOut: 'flipOutX',
                    });
                }
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
        
JS;

$this->registerJs($js_generate_url);

endif;

if($can_send_email) :

$js_send_url = <<<'JS'

    $(document).delegate("#send-url", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();
        
        var _id = $(this).data('id');
        
        swal({
            title: 'Informe o email para o compartilhamento',
            input: 'text',
            inputAttributes:
            {
              autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Enviar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#007EC3',
            showLoaderOnConfirm: true,
            preConfirm: (email) => 
            {
                $.ajax({
                    url: '/ajax/send-url-publica?id_consulta=' + _id + '&id_painel=null&view=1',
                    type: 'POST',
                    data: {'email': email},
                    success: function(data)
                    {
                        if(data.success)
                        {
                            swal({
                                type: 'success',
                                confirmButtonColor: '#007EC3',
                                title: 'Sucesso',
                                text: 'Consulta compartilhada com sucesso.'
                            });
                        }
                    },
                    error: function(data)
                    {
                        swal("Erro ao enviar", data.responseJSON.message, "error");
                    }
                });
            },
            allowOutsideClick: () => !swal.isLoading()
        });
    });
        
JS;

$this->registerJs($js_send_url);

endif;

if($can_export) :

$js_export = <<<JS
        
    $(document).delegate(".export-data-pdf", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();
        
        var _id = {$model->id};
        var _orientation = $(this).data('orientation');
        var _index = $('#rendergraph').data('index');
        var _token = $('#rendergraph').data('token');
        var _csrfToken = $('meta[name="csrf-token"]').attr("content");
        var _url = '/consulta/export-pdf?id=' + _id + '&orientation=' + _orientation + '&index=' + _index + '&token=' + _token;
        
        var _new = window.open(_url, '_blank');
        
        if (_new) 
        {
            _new.focus();
        }
        else 
        {
            alert('Por favor, autorize os pop-ups para imprimir os dados.');
        }
    });
        
    $(document).delegate("#export-data-excel", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();
        
        var _id = {$model->id};
        var _index = $('#rendergraph').data('index');
        var _token = $('#rendergraph').data('token');
        var _csrfToken = $('meta[name="csrf-token"]').attr("content");
        var _url = '/consulta/export-excel?id=' + _id + '&index=' + _index + '&token=' + _token;
        
        var _new = window.open(_url, '_blank');
        
        if (_new) 
        {
            _new.focus();
        }
        else 
        {
            alert('Por favor, autorize os pop-ups para imprimir os dados.');
        }
    });
        
    $(document).delegate("#export-data-csv", 'click', function(e) 
    {
        e.preventDefault();
        e.stopPropagation();
        
        var _id = {$model->id};
        var _index = $('#rendergraph').data('index');
        var _token = $('#rendergraph').data('token');
        var _csrfToken = $('meta[name="csrf-token"]').attr("content");
        var _url = '/consulta/export-csv?id=' + _id + '&index=' + _index + '&token=' + _token;
        
        var _new = window.open(_url, '_blank');
        
        if (_new) 
        {
            _new.focus();
        }
        else 
        {
            alert('Por favor, autorize os pop-ups para imprimir os dados.');
        }
    });
        
JS;

$this->registerJs($js_export);

endif;

$action = 'view';

if($can_filter_graph || $can_change_graph) :

    echo $this->render('_layouts/_filter', compact('model', 'index', 'can_filter_graph', 'can_change_graph'));

endif;

echo $this->render('//layouts/_partials/_left'); 

?>

<div id="page-content-wrapper " class="h-100 mh-100 ">

    <div class="page-content inset h-100 mh-100">

        <?= $this->render('_layouts/_top', compact('model', 'can_export', 'can_generate_url', 'can_send_email', 'can_filter_graph', 'can_change_graph', 'modifications')); ?>

        <div class="container-fluid h-100 mh-100 justify-content-between" id="content--container">

            <div class="col-md-12">
                
                <div data-mh="painel-group-001" class="card card-consulta card--chart card--consuta__full">

                    <?= $this->render('/_graficos/_general/view', compact('index', 'data', 'model')) ?>

                </div>
                    
            </div>

        </div>

    </div>

</div>