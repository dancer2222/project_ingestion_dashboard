<?php


namespace Ingestion;


/**
 * Class ArrayMovieLicensors
 * @package Ingestion
 */
class ArrayMovieLicensors
{
    /**
     * @var array
     */
    private $licensors = [
        'Gravitas' => 'GRAVITAS',
        'Echelon Studios' => '',
        'R-Squared Films' => 'RSQUARED',
        'Content Media' => '',
        'Movie Seals' => '',
        'Raymond Stross' => '',
        'ScreenMedia' => 'SCREENMEDIA',
        'Starz Media Films' => '',
        'Cinedigm Films' => '',
        'Eone Films' => '',
        'Cinedigm Series' => '',
        'Starz Media Series' => '',
        'Eone Series' => '',
        'Starz Media Series EU' => '',
        'Paramount' => '',
        'Brainstorm Media' => '',
        'DHXMedia' => '',
        'Nelvana' => 'NAVI',
        'TheOrchard' => '',
        'SandrewMetronome' => '',
        'UnderTheMilkyWay' => '',
        '9StoryMedia' => '',
        'Imira' => '',
        'Aenetworks' => '',
        'BrainStormMedia' => '',
        'MVDEntertainment' => 'MVD',
    ];

    /**
     * @param $licensorName
     *
     * @return mixed
     */
    public function getFolderName($licensorName) {

        foreach ($this->licensors as $licensor => $folder) {
            if ($licensor == $licensorName) {
                return $folder;
                break;
            }
        }
    }

    /**
     * @return array
     */
    public function getLicensorNames() {
        $licensorNames = [];

        foreach ($this->licensors as $licensorName => $folder) {
            $licensorNames[] = $licensorName;
        }

        return $licensorNames;
    }
}