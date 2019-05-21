<?php

namespace Managers;

class BookImageManager
{
    //10009789730178586
    //http://images.books.milkbox.com/978/973/9789730178586-200x282.jpg?1557925677

    public static function getCoverURL($book_isbn){
        $URLPath = 'http://images.books.milkbox.com/';
        $URLPath .= substr($book_isbn, 0, 3) . '/' . substr($book_isbn, 3, 3) . '/' . $book_isbn . '-200x282.jpg';
        return $URLPath;
    }
}