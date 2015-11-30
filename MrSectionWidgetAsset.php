<?php
/**
 * Created by MackRais on 06.11.15.
 * @author Oleh Boiko
 * @site http://mackrais.zz.mu
 */

namespace MackRais\MrCropImageSection;

use yii\web\AssetBundle;
use Yii;


class MrSectionWidgetAsset extends AssetBundle{

    public $sourcePath = '@MackRais/MrCropImageSection';

    public $css = [
        'css/MrSection.css',
        'css/jquery.guillotine.css'
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];


    public function init() {
        $this->js[] = YII_DEBUG ? 'js/jquery.guillotine.js' : 'js/jquery.guillotine.min.js';
        $this->js[] = YII_DEBUG ? 'js/mr.section.min.js' : 'js/mr.section.min.js';
    }
}
