<?php

namespace App\Http\Controllers\Media;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Controllers\Controller;

class BooksController extends Controller
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
            $booksQuery = Book::query();
            if ($request->get('q')) {
                $booksQuery ->select('id', 'title', 'isbn', 'status')->where('id', $request->get('q', ''));
            }
            $books = $booksQuery->paginate();
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
        return response()->json(Book::find((int)$id));
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
