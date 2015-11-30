<?php
/**
 * Created by MackRais on 06.11.15.
 * @author Oleh Boiko
 * @site http://mackrais.zz.mu
 */

use yii\helpers\Html;
use yii\helpers\Json;

?>
<div  class="mr-section-base <?= $class_block ?>" style="width: <?= $plugin_options['width']+4 ?>px; ">
    <div class="mr-section" id="<?= $plugin_options['section'] ?>" data-role="upload_image" style="width: <?= $plugin_options['width'] + 4 ?>px; height: <?= $plugin_options['height']+3  ?>px;">
        <?php if($template_image): ?>
           <?= $template_image ?>
          <?php else: ?>
            <i class="fa fa-photo fa-5x" ></i>
            <h2><?= Yii::t('app','Please click on the field to select the picture section'); ?> </h2>
        <?php endif; ?>

    </div>
    <div  class="mr-control-panel">
        <div class="btn-group">
        <button class='btn btn-primary mr-zoom-out'     type='button' title='<?= Yii::t('app','Zoom out'); ?>'> <span class="glyphicon glyphicon-zoom-out"></span> </button>
        <button class='btn btn-primary mr-fit'          type='button' title='<?= Yii::t('app','Fit image'); ?>'> <span class="glyphicon glyphicon-resize-small"></span> </button>
        <button class='btn btn-primary mr-zoom-in'      type='button' title='<?= Yii::t('app','Zoom in'); ?>'> <span class="glyphicon glyphicon-zoom-in"></span> </button>
       </div>
        <button class='btn btn-success mr-upload-btn-section pull-right'    title='<?= Yii::t('app','Upload image'); ?>  type='button' title='<?= Yii::t('app','Upload image'); ?>'> <span class="glyphicon glyphicon-upload"></span> </button>
    </div>
    <?= Html::activeFileInput($model, $attribute, $options); ?>
    <div class="mr-data-inputs">
        <?= Html::textInput($attribute_x,'',$options_x)?>
        <?= Html::textInput($attribute_y,'',$options_y)?>
        <?= Html::textInput($attribute_height,'',$options_height)?>
        <?= Html::textInput($attribute_width,'',$options_width)?>
        <?= Html::textInput($attribute_origin_width,'',$options_origin_width)?>
        <?= Html::textInput($attribute_origin_height,'',$options_origin_height)?>
        <?= Html::textInput($attribute_scale,'',$options_scale)?>
        <?= Html::textInput($attribute_angle,'',$options_angle)?>
    </div>
</div>

<?php
$plugin_options['section'] = '#'.$plugin_options['section'] ;
$plugin_options['id_input_file'] = '#'.$plugin_options['id_input_file'] ;
$options = Json::encode($plugin_options,true);
$section = $plugin_options['section'] ;

$this->registerJs("
mr_section_init($options);
");
?>
