<template>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Audiobooks
                <span v-if="this.$route.query.search_by === 'default' || (this.$route.query.q && !this.$route.query.search_by)"> Id /title </span>
                <span v-else>{{ this.$route.query.search_by }}</span>
                {{this.$route.query.q}}
            </div>

            <div class="card-body">
                <div class="alert alert-secondary" role="alert"
                     v-if="abooks.total !== null && abooks.total === 0">
                    No audiobooks found by <b>"{{ $route.query.q }}"</b>
                </div>

                <table class="table table-hover" v-if="abooks.total !== null && abooks.total > 0">
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
                    <tr v-for="abook in abooks.data">
                        <th scope="row">
                            <router-link :to="{ name: 'audiobook', params: { id: abook.id }}">
                                {{ abook.id }}
                            </router-link>
                        </th>
                        <td :style="{whiteSpace: 'nowrap'}">
                            <router-link :to="{ name: 'audiobook', params: { id: abook.id }}">
                                {{ abook.title }}
                            </router-link>
                        </td>
                        <td :style="{fontSize: '10px'}">
                            {{ abook.description.substring(0, 70) }}...
                        </td>
                        <td :style="{whiteSpace: 'nowrap'}">{{ abook.ma_release_date }}</td>
                        <td>
                            <span v-bind:class="['badge', abook.status === 'active' ? 'badge-success' : 'badge-danger']">{{ abook.status }}</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation" class="mx-auto">
                <paginate v-if="abooks.total !== null"
                          v-model="page"
                          :initial-page="abooks.current_page"
                          :page-count="Math.round(abooks.total / abooks.per_page)"
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
        name: 'audio_books',
        components: {Paginate},
        props: {
            q: String,
        },
        data: function () {
            return {
                mediaType: 'audiobooks',
                isList: true,
                abooks: {
                    data: [],
                },
                page: this.$route.query.page,
            }
        },
        mounted: function () {
            console.log('abooks list mounted');
            this.fetchData();
            // this.$g.sortBy(this.abooks, 'name');
        },
        updated: function () {
            this.page = this.$route.query.page ? this.$route.query.page : 1;
        },
        methods: {
            fetchData: function () {
                let self = this;

                axios.get(location.origin + '/audiobooks', {
                    params: {
                        page: this.page,
                        ...this.$route.query
                    },
                }).then(function (response) {
                    self.abooks = response.data.audiobooks;
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