<?php

namespace app\widgets\kindeditor;

use yii\web\AssetBundle;

/**
 * KindEditor资源文件
 * @author DuanMu
 */
class KindEditorAsset extends AssetBundle {

    public $sourcePath = '@app/widgets/kindeditor/assets';
    public $css = [
        'themes/default/default.css',
    ];
    public $js = [
        'kindeditor-min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];

}
