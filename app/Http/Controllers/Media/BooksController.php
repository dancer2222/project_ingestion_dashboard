<?php

namespace App\Http\Controllers\Media;


use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Controllers\Controller;
use Managers\BookImageManager;


class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Book  $book
     * @return \Illuminate\Http\Response
     */

    public function index(Book $book)
    {
        $message = '';
        $status = '';

        try {
            $books = $book->search();
        } catch (\Exception $e) {
            $message = "There're no books by this query.";
            $books = [];
        }

        return response()->json([
            'books' => $books,
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
        $book = Book::where('id', (int)$id)->with(['authors:id,name,status', 'artists:id,name,status', 'licensor:id,name,status', 'publisher:id,name,status'])->first();

        if($book && $book->num_of_images > 0){
            $book->coverUrl = BookImageManager::getCoverURL($book->isbn);
        }

        return response()->json($book);
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
        //
        $status = true;
        $message = "Book ($id) successfully updated.";
        $fieldsNeedToBeUpdated = [];

        foreach ($request->all() as $key => $value) {
            $fieldsNeedToBeUpdated[$key] = $value;
        }

        if ($fieldsNeedToBeUpdated) {
            $book = Book::where('id', $id)
                ->update($fieldsNeedToBeUpdated);

            if (!$book) {
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
