<?php

namespace app\assets;

use yii\web\AssetBundle;

/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 * 
 */

class AdminLteAsset extends AssetBundle {

    //change source location
    public $sourcePath = '@vendor/bower';
    public $css = [
        'adminlte/dist/css/skins/_all-skins.min.css',
        'adminlte/dist/css/AdminLTE.min.css',
        'fontawesome/css/font-awesome.min.css',
        'fontawesome/css/font-awesome.css.map',
    ];
    public $js = [
//        'bootstrap/dist/js/bootstrap.min.js',
        'bootstrap/js/tooltip.js',        
        'adminlte/plugins/slimScroll/jquery.slimscroll.min.js',
        'adminlte/dist/js/app.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];

}
