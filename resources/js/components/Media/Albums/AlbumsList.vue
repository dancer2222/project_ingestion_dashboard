<template>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Albums
                <span v-if="this.$route.query.search_by === 'default' || (this.$route.query.q && !this.$route.query.search_by)"> Id /title </span>
                <span v-else>{{ this.$route.query.search_by }}</span>
                {{this.$route.query.q}}
            </div>

            <div class="card-body">

                <div class="alert alert-secondary" role="alert"
                     v-if="albums.total !== null && albums.total === 0">
                    No albums found by <b>"{{ $route.query.q }}"</b>
                </div>

                <table class="table table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="album in albums.data"
                        :style="{cursor:'pointer'}"
                        @click = "getAlbumData(album.id)">
                        <th scope="row">{{ album.id }}</th>
                        <td>{{ album.title }}</td>
                        <td>{{ album.description}}</td>
                        <span v-bind:class="['badge', album.status === 'active' ? 'badge-success' : 'badge-danger']">{{ album.status }}</span>
                    </tr>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation" class="mx-auto">
                <paginate v-if="albums.total !== null && albums.total >1"
                          v-model="page"
                          :initial-page="albums.current_page"
                          :page-count="Math.round(albums.total / albums.per_page)"
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
        name: 'albumsList',
        components: {Paginate},
        data: function () {
            return {
                mediaType: 'albums',
                q: this.$route.query.q,
                isList: true,
                albums: [],
                page: this.$route.query.page,
            }
        },
        methods: {
            getAlbumData: function (albumId) {
                this.q = albumId;
                this.$router.push({name: 'albumsData', path:this.mediaType + '/' + this.q, params: { id: this.q }});
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
                    self.albums = response.data.albums;
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