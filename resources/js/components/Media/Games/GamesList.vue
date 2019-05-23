<template>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Games
                <span v-if="this.$route.query.search_by === 'default' || (this.$route.query.q && !this.$route.query.search_by)"> Id /title </span>
                <span v-else>{{ this.$route.query.search_by }}</span>
                {{this.$route.query.q}}
            </div>

            <div class="card-body">

                <div class="alert alert-secondary" role="alert"
                     v-if="games.total !== null && games.total === 0">
                    No games found by <b>"{{ $route.query.q }}"</b>
                </div>

                <table class="table table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Title</th>
<!--                        <th scope="col">Description</th>-->
                        <th scope="col">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="game in games.data"
                        :style="{cursor:'pointer'}"
                        @click = "getGameData(game.id)">
                        <th scope="row">{{ game.id }}</th>
                        <td>{{ game.title }}</td>
<!--                        <td>{{ game.description}}</td>-->
                        <span v-bind:class="['badge', game.status === 'active' ? 'badge-success' : 'badge-danger']">{{ game.status }}</span>
                    </tr>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation" class="mx-auto">
                <paginate v-if="games.total !== null && games.total >1"
                          v-model="page"
                          :initial-page="games.current_page"
                          :page-count="Math.round(games.total / games.per_page)"
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
        name: 'gamesList',
        components: {Paginate},
        data: function () {
            return {
                mediaType: 'games',
                q: this.$route.query.q,
                isList: true,
                games: [],
                page: this.$route.query.page,
            }
        },
        methods: {
            getGameData: function (gameId) {
                this.q = gameId;
                this.$router.push({name: 'gamesData', path:this.mediaType + '/' + this.q, params: { id: this.q }});
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
            fetchData: function () {
                let self = this;

                axios.get(this.mediaType, {
                    params: {
                        q: this.$route.query.q,
                        page: this.page,
                    },
                }).then(function (response) {
                    self.games = response.data.games;
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