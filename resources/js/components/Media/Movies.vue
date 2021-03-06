<template>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Movies
                <span v-if="this.$route.query.search_by === 'default' || (this.$route.query.q && !this.$route.query.search_by)"> Id /title </span>
                <span v-else>{{ this.$route.query.search_by }}</span>
                {{this.$route.query.q}}
            </div>

            <div class="card-body">
                <div class="alert alert-secondary" role="alert"
                    v-if="movies.total !== null && movies.total === 0">
                    No movies found by <b>"{{ $route.query.q }}"</b>
                </div>

                <table class="table table-hover" v-if="movies.total !== null && movies.total > 0">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col" :style="{whiteSpace: 'nowrap'}">MA release date</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="movie in movies.data">
                            <th scope="row">{{movie.id}}</th>
                            <td :style="{whiteSpace: 'nowrap'}">
                                <router-link :to="{ name: 'movie', params: { id: movie.id }}">
                                    {{ movie.title }}
                                </router-link>
                            </td>
                            <td :style="{fontSize: '10px'}">
                                {{ movie.description.substring(0, 70) }}...
                            </td>
                            <td :style="{whiteSpace: 'nowrap'}">{{ movie.ma_release_date }}</td>
                            <td>
                                <span v-bind:class="['badge', movie.status === 'active' ? 'badge-success' : 'badge-danger']">{{ movie.status }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation" class="mx-auto">
                <paginate v-if="movies.total !== null"
                    v-model="page"
                    :initial-page="movies.current_page"
                    :page-count="Math.round(movies.total / movies.per_page)"
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
    name: 'movies',
    components: {Paginate},
    props: {
        q: String,
    },
    data: function () {
        return {
            mediaType: 'movies',
            isList: true,
            movies: {
                data: [],
            },
            page: this.$route.query.page,
        }
    },
    mounted: function () {
        this.fetchData()
    },
    updated: function () {
        this.page = this.$route.query.page ? this.$route.query.page : 1;
    },
    methods: {
        fetchData: function () {
            let self = this;

            axios.get(location.origin + '/movies', {
                params: {
                    page: this.page,
                    ...this.$route.query
                },
            }).then(function (response) {
                self.movies = response.data.movies;
            });
        },
        changePage: function (page) {
            this.page = page;
            this.$router.push({
                path: this.$route.fullPath,
                query: {
                    q: this.$route.query.q,
                    page: page
                }
            });
        },
    },
    watch: {
        '$route': 'fetchData'
    },
}
</script>