<?php


namespace Managers;

use Intervention\Image\Facades\Image;

/**
 * Class MovieImageManager
 * @package Managers
 */
class MovieImageManager
{

    /**
     * @param $id
     * @param $title
     *
     * @return string
     */
    public static function getCoverURL($id, $title) {
        $URLPath = 'https://content.milkbox.com/movie/';

        $array = self::getFolder($id);
        $title = mb_strtolower(str_replace(' ', '-', $title));

        $URLPath .= $array['folder1'] . $array['folder2'] .  $title . '-200x282.jpg';

        return $URLPath;
    }

    /**
     * @param $id
     *
     * @return array
     */
    private static function getFolder($id)
    {
        $folder1 = floor($id / 1000) . "/";
        $folder1 = "0000" . $folder1;
        $folder1 = substr($folder1, -4);

        $folder2 = $id % 1000 . "/";
        $folder2 = "0000" . $folder2;
        $folder2 = substr($folder2, -4);

        return array("folder1" => $folder1, "folder2" => $folder2);
    }

    /**
     * @param $id
     * @param $title
     * @param $urlImage
     *
     * @return array
     */
    public function convertImage($title, $urlImage ) {
        Image::configure(array('driver' => 'imagick'));

        $pattern = '/@\..*\./i';
        $urlImage = preg_replace($pattern,"@.",$urlImage);

        $images = [];
        $images[200] = Image::make($urlImage)->resize(200, 282)->save($title.'-200x282.jpg');
        //$images[140] = Image::make($urlImage)->resize(140, 210)->save($title.'-140x210.jpg');
        //$images[32] = Image::make($urlImage)->resize(32, 45)->save($title.'-32x45.jpg');

        return $images;
    }
}