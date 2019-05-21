<template>
    <div class="col-12">
        <div class="row">

            <div class="col-12 col-sm-12">
                <div class="alert alert-danger" role="alert" v-if="isError" v-html="errorMessage"></div>

                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-outline-dark float-left mr-3" v-on:click="$router.back()">Back</button>

                        <strong>Album</strong> id:{{album.id}}

                        <div class = 'float-right'>
                            <span><strong >Status:</strong></span>
                            <span v-if="album" v-bind:class="['badge', album.status === 'active' ? 'badge-success' : 'badge-danger']">{{ album.status }}</span>
                        </div>

                    </div>
                    <div class="card-body">
                        <button class="btn btn-outline-warning float-right" v-on:click="editAlbum">edit</button>
                        <div >
                            <img v-if="album.num_of_images > 0" v-bind:src="pictureURL" alt="Album cover"/>
                            <img v-else v-bind:src="pictureError" v-bind:width="200" v-bind:height="200" alt="No picture available">
                        </div>
                        <div class="row">
                            <tracks :tracks="tracks"/>
                            <p class="card-title mb-2 col-12 col-md-6 col-lg-4" v-if="album" v-for="(value, key) in album">
                                <b v-if="key == 'description'">
                                    <button @click="descriptionShow=!descriptionShow" class="btn btn-sm btn-outline-dark">{{key}}</button>
                                    <div v-show="descriptionShow">
                                        <b>{{ value }}</b>
                                    </div>
                                </b>

                                <b v-if="key !== 'seq_id' && key !== 'description'">{{ key }} - {{ value }}</b>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import tracks from "./Tracks";

    export default {
        components: {tracks},
        debug: true,
        name: 'albumsDataView',
        data: function () {
            return {
                isError: false,
                errorMessage: 'Something went wrong. Please try reloading the <a href="javascript:location.reload();">page</a>',
                album: {},
                mediaType: 'albums',
                pictureError: 'img/image-not-found.jpeg',
                pictureURL:'',
                descriptionShow: false,
                tracks: {}
            }
        },
        methods: {
            fetchData: function () {
                let self = this;
                axios.get(this.mediaType + '/' + this.$route.params.id)
                    .then(function (response) {
                        if (Object.keys(response.data).length > 0) {
                            self.album = response.data;
                            self.pictureURL = self.album.coverUrl;
                            Vue.delete(self.album, 'coverUrl');
                            self.tracks = self.album.tracks;
                            Vue.delete(self.album, 'tracks');
                            console.log(self.album.tracks)
                        } else {
                            self.isError = true;
                        }
                    }).catch(function (error) {
                    self.isError = true;
                })
            },
            editAlbum: function (e) {
                this.$router.push({
                    name: 'albums_edit',
                    path: 'album',
                    params: { album: this.album },
                })
            },
        },
        mounted() {
            console.log('album component');
            this.fetchData();
        },

    }

</script>