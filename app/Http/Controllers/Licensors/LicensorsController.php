<?php

namespace App\Http\Controllers\Licensors;

use App\Models\Audiobook;
use App\Models\Book;
use App\Models\Licensor;
use App\Models\Movie;
use App\Models\Album;
use App\Models\Game;
use App\Models\Software;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class LicensorsController
 * @package App\Http\Controllers\Licensors
 */
class LicensorsController extends Controller
{
    /**
     *
     */
    const CONTENT_MODELS_MAPPING = [
            'audiobooks' => Audiobook::class,
            'books' => Book::class,
            'movies' => Movie::class,
            'music' => Album::class,
            'games' => Game::class,
            'software' => Software::class,
    ];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('misc.licensors.index');
    }

    /**
     * @return Licensor[]|\Illuminate\Database\Eloquent\Collection
     */
    public function showAll() {
        return Licensor::all();
    }

    /**
     * @param Request $request
     * @param Licensor $licensor
     * @return array
     */
    public function getBySearch(Request $request, Licensor $licensor) {
        $data = [0 => ['name' => 'Not found']];
        $name = $request->body;

        if ($name) {
            if (is_numeric($name) && ctype_digit($name)) {
                $licensor = $licensor->where('id', $name);
            } else {
                $licensor = $licensor->where('name', 'like', "%$name%");
            }
        }

        if ($name) {
            $data = $licensor->get();

            $contentModel = self::CONTENT_MODELS_MAPPING[$data[0]['media_type']];

            $data['activeItems'] = $contentModel::where('licensor_id', $data[0]['id'])
                    ->where('status', 'active')
                    ->count();
            $data['inactiveItems'] = $contentModel::where('licensor_id', $data[0]['id'])
                    ->where('status', 'inactive')
                    ->count();

        }



        return $data;
    }
}
