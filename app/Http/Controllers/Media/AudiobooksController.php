<?php

namespace App\Http\Controllers\Media;

use Illuminate\Http\Request;
use App\Models\Audiobook;
use App\Http\Controllers\Controller;

class AudiobooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Audiobook $audiobook)
    {
        $message = '';
        $status = '';

        try {
            $audiobooks = $audiobook->search();
        } catch (\Exception $e) {
            $message = "There're no audiobooks by this query.";
            $audiobooks = [];
        }

        return response()->json([
            'audiobooks' => $audiobooks,
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
        return response()->json(Audiobook::find($id));
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
        $message = "Audiobook ($id) successfully updated.";
        $fieldsNeedToBeUpdated = [];

        foreach ($request->all() as $key => $value) {
            $fieldsNeedToBeUpdated[$key] = $value;
        }

        if ($fieldsNeedToBeUpdated) {
            $movie = Audiobook::where('id', $id)
                ->update($fieldsNeedToBeUpdated);

            if (!$movie) {
                $status = false;
                $message = 'An error occurred while saving the audiobook';
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
