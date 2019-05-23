<template>
    <div class="col-12">
        <div class="row">

            <div class="col-12 col-sm-12">
                <div class="alert alert-danger" role="alert" v-if="isError" v-html="errorMessage"></div>

                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-outline-dark float-left mr-3" v-on:click="$router.back()">Back</button>

                        {{ movie.title }}

                        <button class="btn btn-outline-info float-right" v-on:click="editMovie">edit</button>

                    </div>

                    <div class="card-body">
                        <div>
                            <img v-if="movie.num_of_images > 0" v-bind:src="movie.coverUrl" alt="Movie cover"/>
                            <img v-else v-bind:src="pictureError" v-bind:width="200" v-bind:height="200"
                                 alt="No picture available">
                        </div>
                        <div class="row">
                            <p class="card-title mb-2 col-12 col-md-6 col-lg-4" v-if="movie"
                               v-for="(value, key) in movie">

                                <b v-if="key == 'description'">
                                    <button @click="descriptionShow=!descriptionShow"
                                            class="btn btn-sm btn-outline-dark">{{key}}
                                    </button>
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
    import axios from 'axios';

    export default {
        debug: true,
        name: 'movie',
        data: function () {
            return {
                isError: false,
                errorMessage: 'Something went wrong. Please try reloading the <a href="javascript:location.reload();">page</a>',
                movie: {},
                descriptionShow: false,
                pictureError: 'img/image-not-found.jpeg',
            }
        },
        mounted() {
            console.log('movie component');
            this.fetchData();
        },
        methods: {
            fetchData: function () {
                let self = this;

                axios.get(location.origin + '/movies/' + this.$route.params.id)
                    .then(function (response) {
                        if (Object.keys(response.data).length > 0) {
                            self.movie = response.data;
                        } else {
                            self.isError = true;
                        }
                    }).catch(function (error) {
                    self.isError = true;
                })
            },
            editMovie: function (e) {
                this.$router.push({
                    name: 'movie_edit',
                    path: 'movie',
                    params: {movie: this.movie, fetchData: this.fetchData},
                })
            },
        },
    }
</script>