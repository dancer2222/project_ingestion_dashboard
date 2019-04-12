<template>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Movies header
            </div>

            <div class="card-body">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">MA release date</th>
                        <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="movie in movies.data">
                            <th scope="row">{{ movie.id }}</th>
                            <td>{{ movie.title }}</td>
                            <td>{{ movie.ma_release_date }}</td>
                            <td>{{ movie.status }}</td>
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
    name: 'movies',
    data: function () {
        return {
            mediaType: 'movies',
            movies: {
                data: [],
            },
        }
    },
    methods: {

    },
    mounted() {
        var self = this;

        if (this.movies.data.length === 0) {
            axios.get('http://localhost:8080/movies')
                .then(function (response) {
                    self.movies = response.data;

                    console.log(self.movies)
                });
        }
  }
}
</script>