<?php


namespace Ingestion\Movie;


/**
 * Class MovieProcess
 * @package Ingestion\Movie
 */
class MovieProcess
{
    /**
     * @param $object
     * @param $fileSource
     *
     * @return array
     */
    public function getData($object, $fileSource) {
        return $this->setArray(json_decode($object), $fileSource);
    }

    /**
     * @param $data
     * @param $fileSource
     *
     * @return array
     */
    private function setArray($data, $fileSource) {

        $movieArray = [
            'title'                 => $data->Title ?? '',
            'video file name'       => $fileSource ?? '',
            'caption file name'     => '',
            'description'           => $data->Plot ?? '',
            'date_published'        => $data->Released ?? '',
            'producers'             => '',
            'writers'               => $data->Writer ?? '',
            'directors'             => $data->Director ?? '',
            'actors'                => $data->Actors ?? '',
            'genres'                => $data->Genre ?? '',
            'country_of_origin'     => $data->Country ?? '',
            'subtitles'             => 'N',
            'language'              => $data->Language ?? '',
            'duration'              => $this->convertDuration($data->Runtime) ?? '',
            'quality'               => '',
            'season_number'         => '',
            'episode_number'        => '',
            'tv_rating_id'          => '',
            'mpaa_rating'           => $data->imdbRating ?? '',
            'media_geo_restrict'    => '',
            'data_origin_id'        => '',
            'alternative_languages' => '',
            'closed_captions_files' => '',
            'closed_captions'       => '',
            'series'                => [
                'title'       => '',
                'description' => ''
            ]
        ];

        return $movieArray;
    }

    /**
     * @param  string  $duration
     *
     * @return string
     */
    private function convertDuration(string $duration) : string {
        return str_replace(' min', '', $duration);
    }
}