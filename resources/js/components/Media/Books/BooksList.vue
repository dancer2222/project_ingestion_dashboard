<template>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Books 
                <span v-if="this.$route.query.search_by"> by </span>
                <span v-if="this.$route.query.search_by === 'default' || (this.$route.query.q && !this.$route.query.search_by)"> id/ isbn/ title </span>
                <span v-else>{{ this.$route.query.search_by }}</span>
                {{this.$route.query.q}}
            </div>

            <div class="card-body">

                <div class="alert alert-secondary" role="alert"
                     v-if="books.total !== null && books.total === 0">
                    No books found by <b>"{{ $route.query.q }}"</b>
                </div>

                <table class="table table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Title</th>
                        <th scope="col">ISBN</th>
                        <th scope="col">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="book in books.data"
                        :style="{cursor:'pointer'}"
                        @click = "getBookData(book.id)">
                        <th scope="row">{{ book.id }}</th>
                        <td>{{ book.title }}</td>
                        <td>{{ book.isbn}}</td>
                        <span v-bind:class="['badge', book.status === 'active' ? 'badge-success' : 'badge-danger']">{{ book.status }}</span>
                    </tr>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation" class="mx-auto">
                <paginate v-if="books.total !== null && books.total >1"
                          v-model="page"
                          :initial-page="books.current_page"
                          :page-count="Math.round(books.total / books.per_page)"
                          :click-handler="changePage"
                          :prev-text="'Prev'"
                          :next-text="'Next'"
                          :container-class="'pagination'"
                          :page-class="'page-item'"
                          :next-class="'page-item'"
                          :prev-class="'page-item'"
                          :page-link-class="'page-link'"
                          :prev-link-class="'page-link'"
                          :next-link-class="'page-link'">
                </paginate>
            </nav>

        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import Paginate from 'vuejs-paginate'

    export default {
        name: 'booksList',
        components: {Paginate},
        data: function () {
            return {
                mediaType: 'books',
                q: this.$route.query.q,
                isList: true,
                books: [],
                page: this.$route.query.page,
            }
        },
        methods: {
            getBookData: function (bookId) {
                this.q = bookId;
                this.$router.push({name: 'booksData', path:this.mediaType + '/' + this.q, params: { id: this.q }});
            },
            changePage: function (page) {
                this.page = page;
                this.$router.push({
                    path: this.$route.fullPath,
                    query: {
                        page: page,
                        ...this.$route.query
                    }
                });
            },
            fetchData: function () {
                let self = this;

                axios.get(this.mediaType, {
                    params: {
                        page: this.page,
                        ...this.$route.query
                    },
                }).then(function (response) {
                    self.books = response.data.books;
                });
            },
        },
        mounted() {
            this.fetchData();
        },
        watch: {
            '$route': 'fetchData'
        },
    }
</script>