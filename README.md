# mr-crop-image-section

![SectionCrop Screenshot](https://archive.org/download/ScreenshotFrom20151130100220/Screenshot%20from%202015-11-30%2010:02:20.png)

 composer require mackrais/mr-crop-image-section:"dev-master"
Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist mackrais/mr-crop-image-section "dev-master"
```

or add

```
"mackrais/mr-crop-image-section": "dev-master"
```

to the require section of your `composer.json` file.

## Demo

You can refer detailed [documentation and demos](#) on usage of the extension.

## Usage

```php
use MackRais\MrCropImageSection\ImageCropSection;

// usage by model
echo '<label>Cropping section</label>';
echo $form->field($model, "image_1")->widget(ImageCropSection::className(), [
                         'options' => [
                             'id' => 'mr_file_input1',
                             'class' => 'hidden',
                         ],
                         'attribute_x'=>'section1_x',
                         'attribute_y'=>'section1_y',
                         'attribute_width'=>'section1_w',
                         'attribute_height'=>'section1_h',
                         'attribute_scale'=>'section1_scale',
                         'plugin_options' =>  [
                             'width' => 780,
                             'height' => 270,
                             'id_input_file' => 'mr_file_input1',
                             'section' => 'section_1'
                         ],
                         'template_image'=> null,

                     ])->label(false);
```

## Function by cropping
```php
  static function cropImageSection($source_image_path, $thumbnail_image_path, $params, $degrees = 0)
    {
        if(file_exists($source_image_path) && is_file($source_image_path)){
            list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
            switch ($source_image_type) {
                case IMAGETYPE_GIF:
                    $source_gd_image = imagecreatefromgif($source_image_path);
                    break;
                case IMAGETYPE_JPEG:
                    $source_gd_image = imagecreatefromjpeg($source_image_path);
                    break;
                case IMAGETYPE_PNG:
                    $source_gd_image = imagecreatefrompng($source_image_path);
                    break;
            }
            if ($source_gd_image === false) {
                return false;
            }

            if(array_key_exists('degrees',$params))
                $degrees = isset($params['degrees']) && $params['degrees'] == 270 ? 90 : ($params['degrees'] == 90 ?  270 : $params['degrees']);

            $thumbnail_gd_image = imagecreatetruecolor($params['width'], $params['height']);

            if(isset($degrees))
                $rotate = imagerotate($source_gd_image, $degrees * -1, 0);

            $x = round($params['x']/$params['scale']) + $params['scale'];
            $y = round($params['y']/$params['scale']) - $params['scale'];
            $height = ($params['height']/$params['scale']) + ceil($params['scale']);
            $width = $params['width']/$params['scale'] - ceil($params['scale']);

            imagecopyresampled($thumbnail_gd_image, $rotate, 0, 0, $x , $y, $params['width'], $params['height'], $width,$height );

            imagejpeg($thumbnail_gd_image, $thumbnail_image_path, 100);
            imagedestroy($source_gd_image);
            imagedestroy($thumbnail_gd_image);
            return true;
        }
        return false;
    }
