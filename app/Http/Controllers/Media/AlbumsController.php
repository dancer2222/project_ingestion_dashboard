<?php

namespace App\Http\Controllers\Media;

use App\Models\Music;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Album;
use Managers\AlbumImageManager;

/**
 * Class AlbumsController
 * @package App\Http\Controllers\Media
 */
class AlbumsController extends Controller
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
            $albumsQuery = Album::query();
            if ($request->get('q')) {
                $albumsQuery ->select('id', 'title', 'description', 'status')->where('id', $request->get('q', ''));
            }
            $albums = $albumsQuery->paginate();
        } catch (\Exception $e) {
            $message = "There're no albums by this query.";
            $albums = [];
        }

        return response()->json([
            'albums' => $albums,
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
        $album = Album::find((int)$id);

        $music = new Music();

        $album->tracks = $music->getMusicByAlbumId($id);

        if($album && $album->num_of_images > 0){
            $album->coverUrl = AlbumImageManager::getCoverURL($album->id, $album->title);
        }

        return response()->json($album);
    }

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTrack(Request $request) {
        return response()->json(Music::find($request->id));
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
        $message = "Album ($id) successfully updated.";
        $fieldsNeedToBeUpdated = [];

        foreach ($request->all() as $key => $value) {
            $fieldsNeedToBeUpdated[$key] = $value;
        }

        if ($fieldsNeedToBeUpdated) {
            $album = Album::where('id', $id)
                ->update($fieldsNeedToBeUpdated);

            if (!$album) {
                $status = false;
                $message = 'An error occurred while saving the album';
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
