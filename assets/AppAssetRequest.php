<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAssetRequest extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/select2.mis.js',
        'js/select2.ru.js',
    ];
    public $css = [
        'css/select2.min.css',
        'css/select2.bootstrap4.css',
    ];


    public $jsOptions = [ 'position' => \yii\web\View::POS_BEGIN ];
}
