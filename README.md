# yii2-crop-image-section

[![Latest Stable Version](https://poser.pugx.org/mackrais/yii2-crop-image-section/v/stable)](https://packagist.org/packages/mackrais/yii2-crop-image-section)
[![Latest Unstable Version](https://poser.pugx.org/mackrais/yii2-crop-image-section/v/unstable)](https://packagist.org/packages/mackrais/yii2-crop-image-section)
[![License](https://poser.pugx.org/mackrais/yii2-crop-image-section/license)](https://packagist.org/packages/mackrais/yii2-crop-image-section)
[![Total Downloads](https://poser.pugx.org/mackrais/yii2-crop-image-section/downloads)](https://packagist.org/packages/mackrais/yii2-crop-image-section)
[![Monthly Downloads](https://poser.pugx.org/mackrais/yii2-crop-image-section/d/monthly)](https://packagist.org/packages/mackrais/yii2-crop-image-section)
[![Daily Downloads](https://poser.pugx.org/mackrais/yii2-crop-image-section/d/daily)](https://packagist.org/packages/mackrais/yii2-crop-image-section)

This widget is based on the [ Guillotine jQuery plugin](http://guillotine.js.org/) plugin.

![SectionCrop Screenshot](https://archive.org/download/ScreenshotFrom20151130100220/Screenshot%20from%202015-11-30%2010:02:20.png)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist mackrais/yii2-crop-image-section "dev-master"
```

or 

```
"mackrais/yii2-crop-image-section": "dev-master"
```
or add

```
 composer require mackrais/yii2-crop-image-section:"dev-master"
```

to the require section of your `composer.json` file.

## Usage

```php

use mackrais\cropimage\ImageCropSection;

// usage by model
echo '<label>Cropping section</label>';
echo  $form->field($model, "image")->widget(mackrais\cropimage\ImageCropSection::className(), [
                         'options' => [
                             'id' => 'mr_file_input1',
                             'class' => 'hidden',
                         ],
                         'attribute_x'=>'section1_x',
                         'attribute_y'=>'section1_y',
                         'attribute_width'=>'section1_w',
                         'attribute_height'=>'section1_h',
                         'attribute_scale'=>'section1_scale',
                         'attribute_remove'=>'section1_remove',
                         'class_block'=>'center-block',
                         'plugin_options' => [
                             'width' => 400,
                             'height' => 400,
                             'id_input_file' => 'mr_file_input1',
                             'section' => 'section_1'
                         ],

                         'template_image'=> null
          
                  ])->label(false);
```

## Example use 

For example we have Unit category with image.

## UnitCategory.php
```php

<?php

/**
 * Created by PhpStorm.
 * @user: MackRias
 * @site: http://mackrais.com
 * @email: mackraiscms@gmail.com
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use mackrais\cropimage\helpers\Image;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


/**
 * This is the model class for table "{{%unit_categories}}".
 *
 * @property integer $id
 * @property string $slug
 * @property integer $user_id
 * @property string $class_icon
 * @property string $image
 * @property string $name
 * @property integer $order_num
 * @property integer $status
 * @property string $date_create
 * @property string $date_update
 *
 * @property string|null $imageUrl
 */
class UnitCategories extends ActiveRecord
{
    public $onStatus = true;

    const IMAGE_WIDGET_CONFIGS = [
        'section1' => [
            'width' => 200,
            'height' => 200,
            'id_input_file' => 'mr_file_input1',
            'section' => 'section_1'
        ]
    ];

    const DEFAULT_IMG = '/default_img/unit-category.png';

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%unit_categories}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['user_id', 'order_num', 'status'], 'integer'],
            [['date_create', 'date_update'], 'safe'],
            [['slug', 'class_icon', 'name', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'slug' => Yii::t('app', 'Slug'),
            'user_id' => Yii::t('app', 'User ID'),
            'class_icon' => Yii::t('app', 'Class Icon'),
            'image' => Yii::t('app', 'Image'),
            'name' => Yii::t('app', 'Name'),
            'order_num' => Yii::t('app', 'Order Num'),
            'status' => Yii::t('app', 'Status'),
            'date_create' => Yii::t('app', 'Date Create'),
            'date_update' => Yii::t('app', 'Date Update'),
        ];
    }


    /**
     * @param null $id
     * @param bool $withDefault
     * @return null|string
     */
    public function getImageUrl($id = null, $withDefault = true)
    {
        $model = $id ? self::findOne($id) : $this;
        if (!empty($model) && !empty($model->image)) {
            $path = Yii::getAlias('@webroot/uploads/unit-category');
            $file = $path . $model->image;
            if (file_exists($file) && is_file($file)) {
                return Url::to('uploads/unit-category/' . $model->image, true);
            }
        }
        return $withDefault ? Url::to(self::DEFAULT_IMG, true) : null;
    }

    /**
     * @param $id
     * @param bool $withDefault
     * @return null|string
     */
    public static function imageUrl($id, $withDefault = true)
    {
        $model = self::findOne((int)$id);
        if (!empty($model) && !empty($model->image)) {
            $path = Yii::getAlias('@unitCategoryImgPath');
            $file = $path . $model->image;
            if (is_readable($file) && is_file($file)) {
                return Url::to(Yii::getAlias('@unitCategoryImgUrl') . $model->image, true);
            }
        }
        return $withDefault ? Url::to(self::DEFAULT_IMG, true) : null;
    }

    /**
     *  Save image
     * @return bool|mixed
     */
    public function saveImage()
    {
        $data = Yii::$app->request->post();
        $fileInstance = UploadedFile::getInstance($this, 'image');
        if (isset($data['section1_remove']) && !empty($data['section1_remove'])) {
            $this->deleteImage();
        }
        $path = Yii::getAlias('@unitCategoryImgPath');
        if (isset($fileInstance) && !empty($fileInstance))
            if ($this->validate(['image'])) {
                $this->deleteImage();
                $this->image = uniqid('unit_category_') . '_' . date('Y_m_d-H_i_s', time()) . '.' . $fileInstance->extension;
                $imagePath = $path . $this->image;
                $save = $fileInstance->saveAs($imagePath);
                if ($save) {
                    Image::cropImageSection($imagePath, $imagePath, [
                        'width' => $data['section1_w'],
                        'height' => $data['section1_h'],
                        'y' => $data['section1_y'],
                        'x' => $data['section1_x'],
                        'scale' => $data['section1_scale'],
                    ]);

                    return $imagePath;
                }
            }
        if (isset($this->oldAttributes['image'])) {
            $this->image = $this->oldAttributes['image'];
        }
        return false;
    }

    /**
     * Before save Deleting old image
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!$insert) {
                if (isset($this->oldAttributes['image']) && empty($this->image)) {
                    $this->image = $this->oldAttributes['image'];
                }
                $this->deleteImageBeforeSave();
            }
            $this->saveImage();


            return true;
        } else {
            return false;
        }
    }

    /**
     * After Deleting image
     */
    public function afterDelete()
    {
        parent::afterDelete();
        $this->deleteImage();
    }

    /** 
     * Delete old image before save
     * @return bool
     */
    private function deleteImageBeforeSave()
    {
        $path = Yii::getAlias('@unitCategoryImgPath');
        if ($this->image !== $this->oldAttributes['image'] && !empty($this->oldAttributes['image'])) {
            $file = $path . $this->oldAttributes['image'];
            if (is_readable($file) && is_file($file)) {
                return unlink($file);
            }
        }
        return false;
    }

    /** 
     * Delete image
     * @return bool
     */
    private function deleteImage()
    {
        $path = Yii::getAlias('@unitCategoryImgPath');
        $file = $path . $this->image;
        if (!empty($this->image) && is_readable($file) && is_file($file)) {
            return unlink($file);
        }
        return false;
    }
}

```

## UnitCategory.php
Default controller generated by module [gii](http://www.yiiframework.com/doc-2.0/guide-start-gii.html)
```php

<?php 
/**
 * Created by PhpStorm.
 * @user: MackRias
 * @site: http://mackrais.com
 * @email: mackraiscms@gmail.com
 */
namespace app\models;

use Yii;
use app\models\UnitCategories;
use yii\web\Controller;

/**
 * UnitCategoriesController implements the CRUD actions for UnitCategories model.
 */
class UnitCategoriesController extends Controller
{

// ...

    /**
     * Creates a new UnitCategories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UnitCategories();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
// ...

}
```
## _form.php
Default controller generated by module gii
```php

<?php 
/**
 * Created by PhpStorm.
 * @user: MackRias
 * @site: http://mackrais.com
 * @email: mackraiscms@gmail.com
 */

use mackrais\cropimage\ImageCropSection;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UnitCategories */
/* @var $form yii\widgets\ActiveForm */
$action =
    $model->isNewRecord ?
        Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/create'
        :  Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/update?id='.$model->id;
 ?>

<div class="unit-categories-form">

    <?php $form = ActiveForm::begin(
        [
            'action'=> Url::to($action,true),
            'options' => ['enctype' => 'multipart/form-data']
        ]
    ); ?>

    <?= $form->field($model, 'status',[
        'template' => ' {input}{label} {error}{hint}'
    ])->checkbox(['class'=>'mr-checkbox'],false) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'class_icon')->textInput(['maxlength' => true, 'placeholder'=>$model->getAttributeLabel('class_icon')])->label(false) ?>

        <?=
        $form->field($model, "image")->widget(mackrais\cropimage\ImageCropSection::className(), [
            'options' => [
                'id' => 'mr_file_input1',
                'class' => 'hidden',
            ],
            'attribute_x'=>'section1_x',
            'attribute_y'=>'section1_y',
            'attribute_width'=>'section1_w',
            'attribute_height'=>'section1_h',
            'attribute_scale'=>'section1_scale',
            'attribute_remove'=>'section1_remove',
            'class_block'=>'center-block',
            'plugin_options' => $model::IMAGE_WIDGET_CONFIGS['section1'],
            'template_image'=> isset($model->id) && $model->getImageUrl($model->id,false) ? Html::img($model->getImageUrl($model->id),$model::IMAGE_WIDGET_CONFIGS['section1']) : null

        ])->label(false);
        ?>


    <div class="form-group text-center">
        <?= Html::submitButton(Yii::t('app', 'Save') , ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
```
## License

**yii2-crop-image-section** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.
