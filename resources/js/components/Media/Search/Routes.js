import Movies from './../Movies'
import Movie from './../Movie'
import MovieEdit from './../MovieEdit'
import Books from './../Books'

export default [
    // Movies
  { path: '/movies', name: 'movies', component: Movies, },
  { path: '/movies/:id', name: 'movie', component: Movie, },
  { path: '/movies/:id/edit', name: 'movie_edit', component: MovieEdit, },

  { path: '/books', name: 'books', component: Books },
]