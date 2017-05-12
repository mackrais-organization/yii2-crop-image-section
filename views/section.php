<?php
/**
 * Created by PhpStorm.
 * @user: MackRias
 * @site: http://mackrais.com
 * @email: mackraiscms@gmail.com
 */

use yii\helpers\Html;
use yii\helpers\Json;
use mackrais\cropimage\MrSectionWidgetAsset;

/* @var $this yii\web\View */
/* @var $model \yii\base\Model */
/* @var $class_block string */
/* @var $template_image string */
/* @var $attribute string */
/* @var $options array */
/* @var $attribute_x string */
/* @var $options_x array */
/* @var $attribute_x string */
/* @var $options_x array */
/* @var $attribute_y string */
/* @var $options_y array */
/* @var $attribute_height string */
/* @var $options_height array */
/* @var $attribute_width string */
/* @var $options_width array */
/* @var $attribute_origin_height string */
/* @var $options_origin_height array */
/* @var $attribute_origin_width string */
/* @var $options_origin_width array */
/* @var $attribute_scale string */
/* @var $options_scale  array */
/* @var $attribute_angle string */
/* @var $options_angle array */
/* @var $attribute_remove string */
/* @var $options_remove array */
/* @var $plugin_options array */


MrSectionWidgetAsset::register($this);
$removeBtn = !empty($template_image) ? '' : 'hidden';
$emptyBlock = empty($template_image) ? '' : 'hidden';
?>
    <div class="mr-section-base <?= $class_block ?>" style="width: <?= $plugin_options['width'] + 4 ?>px; ">

        <div class="mr-upload-block mr-tmp-clear-block hidden">
            <i class="fa fa-photo fa-5x" style="margin-top:<?= $plugin_options['height'] / 3 ?>px;"></i>
            <h2><?= Yii::t('app', 'Please click on the field to select the picture section'); ?> </h2>
        </div>

        <div class="mr-section" id="<?= $plugin_options['section'] ?>" data-role="upload_image"
             style="width: <?= $plugin_options['width'] + 4 ?>px; height: <?= $plugin_options['height'] + 3 ?>px;  ">
            <?php if ($template_image): ?>
                <?= $template_image ?>
            <?php endif; ?>
            <div class="mr-upload-block <?= $emptyBlock ?>">
                <i class="fa fa-photo fa-5x" style="margin-top:<?= $plugin_options['height'] / 3 ?>px;"></i>
                <h2><?= Yii::t('app', 'Please click on the field to select the picture section'); ?> </h2>
            </div>
            <span class="glyphicon  glyphicon-remove mr-remove <?= $removeBtn ?>"></span>

        </div>
        <div class="mr-control-panel">
            <div class="btn-group">
                <button class='btn btn-primary mr-zoom-out' type='button' title='<?= Yii::t('site', 'Zoom out'); ?>'>
                    <span class="glyphicon glyphicon-zoom-out"></span></button>
                <button class='btn btn-primary mr-fit' type='button' title='<?= Yii::t('site', 'Fit image'); ?>'><span
                        class="glyphicon glyphicon-resize-small"></span></button>
                <button class='btn btn-primary mr-zoom-in' type='button' title='<?= Yii::t('site', 'Zoom in'); ?>'><span
                        class="glyphicon glyphicon-zoom-in"></span></button>
            </div>
            <button class='btn btn-success mr-upload-btn-section pull-right' title='<?= Yii::t('app', 'Upload image'); ?>' type='button'>
                <span class="glyphicon glyphicon-upload"></span>
            </button>
        </div>
        <?= Html::activeFileInput($model, $attribute, $options); ?>
        <div class="mr-data-inputs">
            <?= Html::textInput($attribute_x, '', $options_x) ?>
            <?= Html::textInput($attribute_y, '', $options_y) ?>
            <?= Html::textInput($attribute_height, '', $options_height) ?>
            <?= Html::textInput($attribute_width, '', $options_width) ?>
            <?= Html::textInput($attribute_origin_width, '', $options_origin_width) ?>
            <?= Html::textInput($attribute_origin_height, '', $options_origin_height) ?>
            <?= Html::textInput($attribute_scale, '', $options_scale) ?>
            <?= Html::textInput($attribute_angle, '', $options_angle) ?>
            <?= Html::textInput($attribute_remove, isset($options_remove['value']) ? $options_remove['value'] : '', $options_remove) ?>
        </div>
    </div>

<?php
$plugin_options['section'] = '#' . $plugin_options['section'];
$plugin_options['id_input_file'] = '#' . $plugin_options['id_input_file'];
$options = Json::encode($plugin_options, true);
$section = $plugin_options['section'];

$this->registerJs("
mr_section_init($options);
");
?>