<?php
/**
 * Created by PhpStorm.
 * @user: MackRias
 * @site: http://mackrais.com
 * @email: mackraiscms@gmail.com
 */

namespace mackrais\cropimage;

use yii\base\Model;
use yii\bootstrap\Widget;
use yii\web\AssetManager;

/**
 * Class ImageCropSection
 * @package mackrais\cropimage
 */
class ImageCropSection extends Widget
{

    /**
     * @var Model the data model that this widget is associated with.
     */
    public $model;

    /**
     * @var string the model attribute that this widget is associated with.
     */
    public $attribute;

    /**
     * @var string the model attribute & options input x
     */
    public $attribute_x = 'x';
    public $options_x = [
        'class'=>'mr-x hidden'
    ];

    /**
     * @var string the model attribute & options input x
     */
    public $attribute_y = 'y';
    public $options_y = [
        'class'=>'mr-y hidden'
    ];

    /**
     * @var string the model attribute & options input height
     */
    public $attribute_height =  'height';
    public $options_height = [
        'class'=>'mr-h hidden'
    ];

    /**
     * @var string the model attribute & options input width
     */
    public $attribute_width = 'width';
    public $options_width = [
        'class'=>'mr-w hidden'
    ];

    /**
     * @var string the model attribute & options input origin_width
     */
    public $attribute_origin_width = 'origin_width';
    public $options_origin_width= [
        'class'=>'mr-origin-width hidden'
    ];

    /**
     * @var string the model attribute & options input origin_height
     */
    public $attribute_origin_height = 'origin_height';
    public $options_origin_height = [
        'class'=>'mr-origin-height hidden'
    ];

    /**
     * @var string the model attribute & options input scale
     */
    public $attribute_scale = 'scale';
    public $options_scale = [
        'class'=>'mr-scale hidden'
    ];

    /**
     * @var string the model attribute & options input remove
     */
    public $attribute_remove = 'remove';
    public $options_remove = [
        'class'=>'hidden',
        'value'=>'0'
    ];

    /**
     * @var string the model attribute & options input origin_height
     */
    public $attribute_angle = 'angle';
    public $options_angle = [
        'class'=>'mr-angle hidden'
    ];

    public $options = [];

    public $plugin_options = [
        'width' => 400,
        'height' =>400,
        'id_input_file'=>'mr_file_input',
        'section'=>'section_1'
    ];

    public $class_block = '';
    public $template_image = '';

    public function init(){
        $assetManager = new AssetManager();
        $assetManager->forceCopy = YII_ENV_DEV ? true : false;
        $assetManager->linkAssets = true;
        $assetManager->publish('@app/widgets/MrCropImageSection/assets');
        parent::init();

    }

    /**
     * @return null|string
     */
    public function run() {
        if ($this->hasModel()) {
            if(isset($this->options_remove['class'])){
                $this->options_remove['class'] .= ' mr-remove-input';
            }else{
                $this->options_remove['class'] = 'mr-remove-input';
            }
            return $this->render('section', [
                'model' => $this->model,
                'plugin_options' => $this->plugin_options,
                'options' => $this->options,
                'attribute' =>$this->attribute,
                'attribute_x'=>$this->attribute_x,
                'options_x'=> $this->options_x,
                'attribute_y'=>$this->attribute_y,
                'options_y'=> $this->options_y,
                'attribute_height'=>$this->attribute_height,
                'options_height'=> $this->options_height,
                'attribute_width'=>$this->attribute_width,
                'options_width'=> $this->options_width,
                'attribute_origin_height'=>$this->attribute_origin_height,
                'options_origin_height'=> $this->options_origin_height,
                'attribute_origin_width'=>$this->attribute_origin_width,
                'options_origin_width'=> $this->options_origin_width,
                'attribute_scale'=>$this->attribute_scale,
                'options_scale'=> $this->options_scale,
                'attribute_angle'=>$this->attribute_angle,
                'options_angle'=> $this->options_angle,
                'attribute_remove'=>$this->attribute_remove,
                'options_remove'=> $this->options_remove,
                'class_block' => $this->class_block,
                'template_image' =>  $this->template_image,
            ]);
        }
        return null;
    }

    /**
     * @return boolean whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        return  $this->model instanceof Model &&  $this->attribute !== null;
    }
}

