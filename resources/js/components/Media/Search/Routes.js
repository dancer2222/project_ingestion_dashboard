import Movies from './../Movies'
import Movie from './../Movie'
import MovieEdit from './../MovieEdit'
import Books from '../Books/Books'
import Book from '../Books/Book'
import BooksList from '../Books/BooksList'
import BookEdit from '../Books/BookEdit'
import Albums from '../Albums/Albums'
import AlbumsList from '../Albums/AlbumsList'
import Album from '../Albums/Album'
import AlbumEdit from '../Albums/AlbumEdit'
import Games from "../Games/Games";
import GamesList from "../Games/GamesList";
import Game from "../Games/Game";
import GameEdit from "../Games/GameEdit";
import Track from "../Albums/Track";

export default [
    // Movies
    { path: '/movies', name: 'movies', component: Movies, },
    { path: '/movies/:id', name: 'movie', component: Movie, },
    { path: '/movies/:id/edit', name: 'movie_edit', component: MovieEdit, },
    // Books
    { path: '/books', component: Books,
        children: [
            {path: '', name: 'books', component: BooksList},
            {path: ':id', name:'booksData', component: Book},
            {path: ':id/edit', name:'books_edit', component: BookEdit},
        ]
    },
    // Albums
    { path: '/albums', component: Albums,
        children: [
            {path: '', name: 'albums', component: AlbumsList},
            {path: ':id', name:'albumsData', component: Album},
            {path: ':id/edit', name:'albums_edit', component: AlbumEdit},
            {path: '/track/:id', name:'track', component: Track},
        ]
    },
    // Games
    { path: '/games', component: Games,
        children: [
            {path: '', name: 'games', component: GamesList},
            {path: ':id', name:'gamesData', component: Game},
            {path: ':id/edit', name:'games_edit', component: GameEdit},
        ]
    },
]