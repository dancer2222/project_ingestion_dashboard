<?php

namespace App\Http\Controllers\Media;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Http\Controllers\Controller;

class MoviesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $message = '';
        $status = '';

        try {
            $movieQuery = Movie::query();

            if ($request->get('q')) {
                $movieQuery->where('id', $request->get('q', ''));
            }

            $movies = $movieQuery->paginate();
        } catch (\Exception $e) {
            $message = "There're no movies by this query.";
            $movies = [];
        }

        return response()->json([
            'movies' => $movies,
            'status' => $status,
            'message' => $message
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Movie::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status = true;
        $message = "Movie ($id) successfully updated.";
        $fieldsNeedToBeUpdated = [];

        foreach ($request->all() as $key => $value) {
            $fieldsNeedToBeUpdated[$key] = $value;
        }

        if ($fieldsNeedToBeUpdated) {
            $movie = Movie::where('id', $id)
                ->update($fieldsNeedToBeUpdated);

            if (!$movie) {
                $status = false;
                $message = 'An error occurred while saving the movie';
            }
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
