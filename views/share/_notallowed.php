<?php

use app\magic\CacheMagic;

$version = CacheMagic::getSystemData('version');

$js = <<<JS
    
    swal({
        type: 'error',
        text: 'Verifique a chave e tente novamente, caso problema persista entre em contato com o administrador do sistema.',
        title: 'Chave de acesso inválida'
    });

JS;

$this->registerJs($js);

?>

<div id="page-content-wrapper " style="padding-left:0px;">

    <div class="page-content inset h-100 mh-100">

        <nav class="nav pageContent--nav align-item-center justify-content-start">

            <span id="title-graph" class="navbar-text text-uppercase align-self-center" style="cursor: help;">403</span>

        </nav>

        <div class="container-fluid h-100 mh-100 justify-content-between" id="content--container">

            <div class="col-md-12 mt-3">

            </div>


        </div>

    </div>

</div>