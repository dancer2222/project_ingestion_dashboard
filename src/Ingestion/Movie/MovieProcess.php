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
    public function getData($object, $fileSource, $firstData)
    {
        return $this->setArray(json_decode($object), $fileSource, $firstData);
    }

    /**
     * @param $data
     * @param $fileSource
     *
     * @return array
     */
    private function setArray($data, $fileSource, $firstData)
    {

        $coverFileName = mb_strtolower(str_replace(' ', '-', $data->Title));
        $coverFileName .= ".jpg";

        $movieArray = [
            'title' => $firstData['title'] ?? $data->Title ?? '',
            'video file name'          => $fileSource ?? '',
            'caption file name'        => '',
            'description'              => $firstData['description'] ?? $data->Plot,
            'date_published'           => $firstData['date_published'] ?? $data->Released ?? '',
            'producers'                => $firstData['producers'] ?? '',
            'writers'                  => $firstData['writers'] ?? $data->Writer ?? '',
            'directors'                => $firstData['directors'] ?? $data->Director ?? '',
            'actors'                   => $firstData['actors'] ?? $data->Actors ?? '',
            'genres'                   => $firstData['genres'] ?? $data->Genre ?? '',
            'country_of_origin'        => $firstData['country_of_origin'] ?? $data->Country,
            'subtitles'                => 'N',
            'language'                 => $data->Language ?? '',
            'duration'                 => $this->convertDuration($data->Runtime) ?? '',
            'quality'                  => '',
            'season_number'            => $firstData['season_number'] ?? '',
            'episode_number'           => $firstData['episode_number'] ?? '',
            'tv_rating_id'             => $data->imdbRating ?? '',
            'mpaa_rating'              => $data->imdbRating ?? '',
//            'media_geo_restrict'       => '',
            'data_origin_id'           => '',
            'alternative_languages'    => '',
            'closed_captions_files'    => '',
            'closed_captions'          => '',
            'cover file name'          => $coverFileName,
            'episode title'            => $firstData['episode title'] ?? '',
            'description - series'     => $firstData['description - series'] ?? '',
            'description - episode'    => $firstData['description - episode'] ?? '',

        ];

        return $movieArray;
    }

    /**
     * @param  string  $duration
     *
     * @return string
     */
    private function convertDuration(string $duration) : string
    {
        return str_replace(' min', '', $duration);
    }
}