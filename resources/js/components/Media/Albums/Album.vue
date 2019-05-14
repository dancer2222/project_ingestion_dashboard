<template>
    <div class="col-12">
        <div class="row">

            <div class="col-12 col-sm-12">
                <div class="alert alert-danger" role="alert" v-if="isError" v-html="errorMessage"></div>

                <div class="card">
                    <div class="card-header">
                        <!--<button class="btn btn-outline-dark float-left mr-3" v-on:click="$router.back()">Back</button>-->

                        <strong>Album</strong> id:{{album.id}}

                        <div class = 'float-right'>
                            <span><strong >Status:</strong></span>
                            <span v-if="album" v-bind:class="['badge', album.status === 'active' ? 'badge-success' : 'badge-danger']">{{ album.status }}</span>
                        </div>

                    </div>
                    <!--                    <img class="card-img-top mx-auto" src="img/no_product_image.png"-->
                    <!--                         v-bind:style="{maxWidth: 250 + 'px'}" alt="Card image cap">-->

                    <div class="card-body">
                        <button class="btn btn-outline-warning float-right" v-on:click="editAlbum">edit</button>
                        <div class="row">
                            <p class="card-title mb-2 col-12 col-md-6 col-lg-4" v-if="album" v-for="(value, key) in album">
                                <b>{{ key }}</b> - {{ value }}
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

    export default {
        debug: true,
        name: 'albumsDataView',
        data: function () {
            return {
                isError: false,
                errorMessage: 'Something went wrong. Please try reloading the <a href="javascript:location.reload();">page</a>',
                album: {},
                mediaType: 'albums',
            }
        },
        methods: {
            fetchData: function () {
                let self = this;
                axios.get(this.mediaType + '/' + this.$route.params.id)
                    .then(function (response) {
                        if (Object.keys(response.data).length > 0) {
                            self.album = response.data;
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
                    params: { album: this.album, fetchData: this.fetchData },
                })
            },
        },
        mounted() {
            console.log('album component');
            this.fetchData();
        },

    }

</script>