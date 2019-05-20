<?php

namespace App\Http\Controllers\Media;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Managers\GameImageManager;

class GamesController extends Controller
{
    /**
     * @param  Game  $game
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Game $game)
    {
        $message = '';
        $status = '';

        try {
            $games = $game->search();
        } catch (\Exception $e) {
            $message = "There're no games by this query.";
            $games = [];
        }

        return response()->json([
            'games' => $games,
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
        $game = Game::find((int)$id);

        if($game && $game->num_of_images > 0){
            $game->coverUrl = GameImageManager::getCoverURL($game->id, $game->title);
        }

        return response()->json($game);
    }

    /**
     * @param  Request  $request
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $status = true;
        $message = "Game ($id) successfully updated.";
        $fieldsNeedToBeUpdated = [];

        foreach ($request->all() as $key => $value) {
            $fieldsNeedToBeUpdated[$key] = $value;
        }

        if ($fieldsNeedToBeUpdated) {
            $game = Game::where('id', $id)
                ->update($fieldsNeedToBeUpdated);

            if (!$game) {
                $status = false;
                $message = 'An error occurred while saving the book';
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
