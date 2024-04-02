<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle {

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
        'css/hummingbird-treeview.css',
        'bower/gridstack/gridstack.min.css'
    ];
    public $js = [
        'js/select2.full.min.js',
        'js/iziToast.js',
        'js/iziModal.js',
        'js/swal.min.js',
        'js/jquery.typeahead.min.js',
        'js/tree.jquery.js',
        'js/sidebarToogle.js',
        'js/main/painelView.js',
        'echarts/echarts-all.js',
        'js/jquery.dataTables.min.js',
        'js/jquery.matchHeight-min.js',
        'js/sortable.js',
        'js/fontawesome.js',
        'js/pt-BR.js',
        'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.0/jquery-ui.js',
        'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.5.0/lodash.min.js',
        'bower/gridstack/gridstack.min.js',
        'bower/gridstack/gridstack.jQueryUI.min.js',
        'js/hummingbird-treeview.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset'
    ];

}
