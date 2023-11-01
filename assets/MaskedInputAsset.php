<?php


namespace app\assets;

use yii\web\AssetBundle;

class MaskedInputAsset extends AssetBundle
{
    public $sourcePath = '@bower/inputmask/dist';
    public $js = [
        'jquery.inputmask.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}