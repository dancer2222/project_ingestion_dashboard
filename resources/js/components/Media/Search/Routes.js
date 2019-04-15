import Movies from './../Movies'
import Books from '../Books/Books'
import BookList from '../Books/BookList'
import Book from '../Books/Book'
import BookEdit from '../Books/BookEdit'


export default [
  { path: '/movies', component: Movies },
  { path: '/books', component: Books ,
    children: [
        {path: '', component: BookList},
        {path: 'id', component: Book},
        {path: 'id/edit', component: BookEdit},
    ]
  },
]