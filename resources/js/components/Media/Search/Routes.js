import Movies from './../Movies'
import Movie from './../Movie'
import MovieEdit from './../MovieEdit'
import Books from '../Books/Books'
import Book from '../Books/Book'
import BookList from '../Books/BookList'
import BookEdit from '../Books/BookEdit'

export default [
    // Movies
    { path: '/movies', name: 'movies', component: Movies, },
    { path: '/movies/:id', name: 'movie', component: Movie, },
    { path: '/movies/:id/edit', name: 'movie_edit', component: MovieEdit, },
    // Books
    { path: '/books', component: Books ,
        children: [
            {path: '', name: 'books', component: BookList},
            {path: ':id', name:'booksData', component: Book},
            {path: ':id/edit', name:'books_edit', component: BookEdit},
        ]
    },

]