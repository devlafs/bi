<?php

namespace app\assets;

use yii\web\AssetBundle;

class ConsultaViewAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.css',
        'css/jquery.typeahead.min.css',
        'css/dataTables.bootstrap4.min.css',
        'css/select2.min.css',
        'css/iziModal.min.css',
        'css/iziToast.min.css',
        'css/swal.min.css',
        'css/swal.css',
        'css/main.css',
        'css/main/consultaView.css'
    ];
    public $js = [
        'js/select2.full.min.js',
        'js/iziModal.js',
        'js/iziToast.js',
        'js/swal.min.js',
        'js/jquery.typeahead.min.js',
        'js/tree.jquery.js',
        'js/sidebarToogle.js',
        'echarts/echarts-en.min.js',
        'js/jquery.dataTables.min.js',
        'js/jquery.matchHeight-min.js',
        'js/sortable.js',
        'js/fontawesome.js',
        'js/pt-BR.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];

}
