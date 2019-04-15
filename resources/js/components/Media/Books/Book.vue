<template>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <strong>Book with id:</strong>
            </div>

            <div class="card-body">


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
                    <tr v-for="book in books"
                        :style="{cursor:'pointer'}">
                        <th scope="row">{{ book.id }}</th>
                        <td>{{ book.title }}</td>
                        <td>{{ book.isbn}}</td>

                        <td v-if="book.status == 'active'">
                                <span class="font-weight-bold badge badge-success">
                                    {{ book.status }}
                                </span>
                        </td>
                        <td v-else>
                                <span class="font-weight-bold badge badge-danger">
                                    {{ book.status }}
                                </span>
                        </td>
                    </tr>
                    </tbody>
                </table>


            </div>

        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        name: 'booksDataView',
        data: function () {
            return {
                q: this.$route.query.q,
                mediaType: 'books',
                books: [],
            }
        },
        methods: {

        },
        mounted() {
            var self = this;

            if (this.books.length === 0) {
                axios.get(this.mediaType + '/' + this.q)
                    .then(function (response) {
                        self.books = response.data;

                    });
            }
        }
    }

</script>