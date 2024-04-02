<?php

use app\magic\CacheMagic;

$version = CacheMagic::getSystemData('version');

?>

<nav class="nav pageContent--nav align-item-center justify-content-start">
    
    <span id="title-graph" class="navbar-text text-uppercase align-self-center" style="cursor: help;"><?= ($model) ? $model->nome : '404' ?></span>
    
</nav>