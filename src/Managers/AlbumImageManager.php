<?php

namespace Managers;


class AlbumImageManager
{
    //1001100000413743291
    //https://content.milkbox.com/music_album/000/109/kaleidoscope-200x200.jpg

    public static function getCoverURL($albumId, $albumTitle){

        $title = mb_strtolower(str_replace(' ', '-', $albumTitle));
        $URLPath = 'https://content.milkbox.com/music_album/';
        $folders = self::createFolder($albumId);
        $URLPath .= $folders["folder1"] . $folders["folder2"];
        $URLPath .=  $title . '-200x200.jpg';
        return $URLPath;
    }

    public static function createFolder($id)
    {
        $folder1 = floor($id / 1000) . "/";
        $folder1 = "0000" . $folder1;
        $folder1 = substr($folder1, -4);

        $folder2 = $id % 1000 . "/";
        $folder2 = "0000" . $folder2;
        $folder2 = substr($folder2, -4);

        return array("folder1" => $folder1, "folder2" => $folder2);
    }
}