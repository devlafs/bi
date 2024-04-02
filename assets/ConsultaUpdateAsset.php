<?php

namespace app\assets;

use yii\web\AssetBundle;

class ConsultaUpdateAsset extends AssetBundle {

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
        'css/main/consultaUpdate.css'
    ];
    public $js = [
        'js/select2.full.min.js',
        'js/iziToast.js',
        'js/iziModal.js',
        'js/swal.min.js',
        'js/jquery.typeahead.min.js',
        'js/tree.jquery.js',
        'js/sidebarToogle.js',
        'js/main/painelUpdate.js',
        'js/main/consultaUpdate.js',
        'echarts/echarts-all.js',
        'js/jquery.dataTables.min.js',
        'js/jquery.matchHeight-min.js',
        'js/sortable.js',
        'js/fontawesome.js',
        'js/pt-BR.js',
        'js/underscore.min.js',
        'js/jquery.elastic.js',
        'js/jquery.mentionsInput.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];

}
