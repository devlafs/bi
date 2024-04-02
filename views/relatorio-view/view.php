<?php

use app\models\GraficoConfiguracao;
use yii\widgets\Pjax;
use app\magic\RelatorioMagic;

$this->title = $model->nome;

$can_generate_url = Yii::$app->permissaoPainel->can($model->id, 'ajax', 'generate-url-publica');

$can_send_email = Yii::$app->permissaoPainel->can($model->id, 'ajax', 'send-url-publica');

if($can_generate_url) :

//    if($model->privado)
    if(false)
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
            url: '/ajax/generate-url-publica?id_consulta=null&id_painel=null&id_relatorio=' + _id + '&view=3',
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
            url: '/ajax/generate-url-publica?id_consulta=null&id_painel=null&id_relatorio=' + _id + '&view=3',
            type: 'json',
            success: function (_data) 
            {
                if(_data.success)
                {
                    swal({
                        type: 'question',
                        confirmButtonColor: '#007EC3',
                        title: 'Compartilhar o relatório via whatsapp:',
                        showConfirmButton: false,
                        html:
                        '<a class="btn btn-success" style="color: #FFF !important" target="_blank" href="https://api.whatsapp.com/send?text=' + encodeURIComponent(_data.url) + '">Compartilhar</a>',
                    });
                }
                else
                {
                    iziToast.error({
                        title: 'Erro ao compartilhar relatório!',
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
                    url: '/ajax/send-url-publica?id_consulta=null&id_painel=null&id_relatorio=' + _id + '&view=3',
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
                                text: 'Relatório compartilhado com sucesso.'
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

$css = <<<CSS
        
    .grid-stack-item-content {
        height: auto;
        margin: 1em;
        background-color: #fff;
    }
            
    *:fullscreen
    *:-ms-fullscreen,
    *:-webkit-full-screen,
    *:-moz-full-screen 
    {
       overflow: auto !important;
    }
    
    .div-loading-p.loading 
    .div-loading-p.loading 
    {
        overflow: hidden;  
        display: block; 
    }
    .div-loading-p:not(.loading)
    {
        display: none;
    }

    ::-webkit-scrollbar {
      width: 5px;
      height: 5px;
    }
    
    /*!* Track *!*/
    ::-webkit-scrollbar-track {
      border-radius: 10px;
    }
     
    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: #bec3cf; 
      border-radius: 10px;
    }
CSS;

$this->registerCss($css);

$configuracoes = GraficoConfiguracao::find()->
andWhere([
    'is_ativo' => TRUE,
    'is_excluido' => FALSE,
    'is_serie' => false,
    'view' => 'view',
    'tipo' => 'table'
])->one();

$js = <<<"JS"
    
    $(function()
    {
        var groupColumn = 0;

        var table = $('#datagrid').DataTable({$configuracoes->data});
    });

JS;

$this->registerJs($js);

$this->registerJs(

    '$("document").ready(function(){ 

        $("#search-form").on("pjax:end", function() {

            $.pjax.reload({container:"#gridData"});

        });

    });'

);

echo $this->render('//layouts/_partials/_left');

if($model->javascript):

    $this->registerJs($model->javascript);

endif;

?>

<div id="page-content-wrapper " class="h-100 mh-100 ">

    <div class="page-content inset h-100 mh-100">

        <?= $this->render('_layouts/_top', compact('model', 'can_generate_url', 'can_send_email')); ?>

        <div class="relatorio-index">

            <div class="row">

                <div class="col-lg-12 col-md-12">

                    <div class="card card-outline-plan">

                        <div class="card-body p-5" style="max-height: calc(100vh - 50px); overflow-y: scroll;">

                            <?php if(!$dataProvider): ?>

                                <div class="alert alert-danger">
                                    O Relatório não foi configurado corretamente. Por favor, entre em contato com o administrador do sistema e solicite a correção.
                                </div>

                            <?php else: ?>

                                <?php if($model->condicao) :

                                    $limpar = ['visualizar', 'id' => $model->id];

                                    ?>

                                    <?= $this->render('/ajax/_relatorio/_layouts/_filter', compact('model', 'searchModel', 'limpar')) ?>

                                <?php endif; ?>

                                <?php Pjax::begin(['id' => 'gridData']) ?>

                                    <table id="datagrid" class="table table-hover table-bordered">

                                        <thead class="thead-default">

                                            <tr>

                                                <?php foreach ($campos['x'] as $campo) : ?>

                                                    <td><?= $campo['campo']['nome'] ?></td>

                                                <?php endforeach; ?>

                                                <td><?= $campos['y']['campo']['nome'] ?></td>

                                            </tr>

                                        </thead>

                                        <tbody>

                                        <?php foreach($dataProvider->getModels() as $valor) : ?>

                                            <tr>

                                                <?php foreach ($campos['x'] as $campo) : ?>

                                                    <td><?= RelatorioMagic::format($campo['campo'], $valor) ?></td>

                                                <?php endforeach; ?>

                                                <td><?= RelatorioMagic::format($campos['y']['campo'], $valor) ?></td>

                                            </tr>

                                        <?php endforeach; ?>

                                        </tbody>

                                    </table>

                                <?php Pjax::end() ?>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>