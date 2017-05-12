<?php
/**
 * Created by PhpStorm.
 * @user: MackRias
 * @site: http://mackrais.com
 * @email: mackraiscms@gmail.com
 */

namespace mackrais\cropimage\helpers;

use Yii;
use yii\web\UploadedFile;
use yii\web\HttpException;
use yii\helpers\FileHelper;
use app\helpers\GD as CustomGD;

/**
 * Class Image
 * @package mackrais\cropimage\helpers
 */
class Image
{
    public static function upload(UploadedFile $fileInstance, $dir = '', $fullPath = false, $resizeWidth = null, $resizeHeight = null, $resizeCrop = false)
    {
        $fileName = Upload::getUploadPath($dir) . DIRECTORY_SEPARATOR . Upload::getFileName($fileInstance);
        $fileName = $fullPath ? $dir . Upload::getFileName($fileInstance) : $fileName;

        $uploaded = $resizeWidth
            ? self::copyResizedImage($fileInstance->tempName, $fileName, $resizeWidth, $resizeHeight, $resizeCrop)
            : $fileInstance->saveAs($fileName);

        if (!isset($uploaded)) {
            throw new HttpException(500, 'Cannot upload file "' . $fileName . '". Please check write permissions.');
        }

        return Upload::getLink($fileName);
    }

    static function thumb($filename, $width = null, $height = null, $crop = true)
    {
        if ($filename && file_exists(($filename = Yii::getAlias('@webroot') . $filename))) {
            $info = pathinfo($filename);
            $thumbName = $info['filename'] . '-' . md5(filemtime($filename) . (int)$width . (int)$height . (int)$crop) . '.' . $info['extension'];
            $thumbFile = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . Upload::$UPLOADS_DIR . DIRECTORY_SEPARATOR . 'thumbs' . DIRECTORY_SEPARATOR . $thumbName;
            $thumbWebFile = '/' . Upload::$UPLOADS_DIR . '/thumbs/' . $thumbName;
            if (file_exists($thumbFile)) {
                return $thumbWebFile;
            } elseif (FileHelper::createDirectory(dirname($thumbFile), 0777) && self::copyResizedImage($filename, $thumbFile, $width, $height, $crop)) {
                return $thumbWebFile;
            }
        }
        return '';
    }

    static function copyResizedImage($inputFile, $outputFile, $width, $height = null, $crop = true)
    {
        if (extension_loaded('gd')) {
            $image = new CustomGD($inputFile);

            if ($height) {
                if ($width && $crop) {
                    $image->cropThumbnail($width, $height);
                } else {
                    $image->resize($width, $height);
                }
            } else {
                $image->resize($width);
            }
            return $image->save($outputFile);
        } elseif (extension_loaded('imagick')) {
            $image = new \Imagick($inputFile);

            if ($height && !$crop) {
                $image->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1, true);
            } else {
                $image->resizeImage($width, null, \Imagick::FILTER_LANCZOS, 1);
            }

            if ($height && $crop) {
                $image->cropThumbnailImage($width, $height);
            }

            return $image->writeImage($outputFile);
        } else {
            throw new HttpException(500, 'Please install GD or Imagick extension');
        }
    }

    /**
     * @param $sourceImagePath
     * @param $thumbnailImagePath
     * @param $params
     * @param int $degrees
     * @return bool
     */
    static function cropImageSection($sourceImagePath, $thumbnailImagePath, $params, $degrees = 0)
    {
        if (file_exists($sourceImagePath) && is_file($sourceImagePath)) {
            $rotate = 0;
            $imageSizes = getimagesize($sourceImagePath);
            $sourceGDImage = null;
            /*
             * $imageSizes[0] - original image width
             * $imageSizes[1] - original image height
             */
            $sourceImageType = isset($imageSizes[2]) ? $imageSizes[2] : 0;
            $sourceGDImage = self::imageCreateFrom($sourceImageType, $sourceImagePath);
            if ($sourceGDImage === false) {
                return false;
            }
            if (array_key_exists('degrees', $params))
                $degrees = isset($params['degrees']) && $params['degrees'] == 270 ? 90 : ($params['degrees'] == 90 ? 270 : $params['degrees']);
            $thumbnailGDImage = imagecreatetruecolor($params['width'], $params['height']);
            if (isset($degrees))
                $rotate = imagerotate($sourceGDImage, $degrees * -1, 0);
            // Set transparate
            if ($sourceImageType == IMAGETYPE_PNG || $sourceImageType == IMAGETYPE_GIF) {
                imagealphablending($thumbnailGDImage, false);
                imagesavealpha($thumbnailGDImage, true);
                $transparent = imagecolorallocatealpha($thumbnailGDImage, 255, 255, 255, 127);
                imagefilledrectangle($thumbnailGDImage, 0, 0, $params['width'], $params['height'], $transparent);
            }
            $x = round($params['x'] / $params['scale']) + ceil($params['scale']);
            $y = round($params['y'] / $params['scale']) + ceil($params['scale']);
            $height = round($params['width'] / $params['scale']) - ceil($params['scale']);
            $width = round($params['width'] / $params['scale']) - ceil($params['scale']);
            imagecopyresampled(
                $thumbnailGDImage,
                $rotate,
                0,
                0,
                (int)$x,
                (int)$y,
                (int)$params['width'] + ceil($params['scale']),
                (int)$params['height'] + ceil($params['scale']),
                (int)$width,
                (int)$height
            );

            self::image($sourceImageType, $thumbnailGDImage, $thumbnailImagePath);
            imagedestroy($sourceGDImage);
            imagedestroy($thumbnailGDImage);
            return true;
        }
        return false;
    }

    /**
     * @param $sourceImageType
     * @param $sourceImagePath
     * @return bool|resource
     */
    private static function imageCreateFrom($sourceImageType, $sourceImagePath)
    {
        $sourceGDImage = false;
        switch ($sourceImageType) {
            case IMAGETYPE_GIF:
                $sourceGDImage = imagecreatefromgif($sourceImagePath);
                break;
            case IMAGETYPE_JPEG:
                $sourceGDImage = imagecreatefromjpeg($sourceImagePath);
                break;
            case IMAGETYPE_PNG:
                $sourceGDImage = imagecreatefrompng($sourceImagePath);
                break;
        }
        return $sourceGDImage;
    }

    /**
     * @param $sourceImageType
     * @param $thumbnailGDImage
     * @param $thumbnailImagePath
     * @return bool
     */
    private static function image($sourceImageType, $thumbnailGDImage, $thumbnailImagePath)
    {
        $result = false;
        switch ($sourceImageType) {
            case IMAGETYPE_GIF:
                $result = imagegif($thumbnailGDImage, $thumbnailImagePath);
                break;
            case IMAGETYPE_JPEG:
                $result = imagejpeg($thumbnailGDImage, $thumbnailImagePath, 100);
                break;
            case IMAGETYPE_PNG:
                $result = imagepng($thumbnailGDImage, $thumbnailImagePath, 9);
                break;
        }
        return $result;
    }

}