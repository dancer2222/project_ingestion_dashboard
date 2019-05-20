<template>
    <div class="col-12">
        <div class="row">

            <div class="col-12 col-sm-12">
                <div class="alert alert-danger" role="alert" v-if="isError" v-html="errorMessage"></div>

                <div class="card">
                    <div class="card-header">
                        <!--<button class="btn btn-outline-dark float-left mr-3" v-on:click="$router.back()">Back</button>-->

                        <strong>Book</strong> ISBN:{{game.isbn}}

                        <div class = 'float-right'>
                            <span><strong >Status:</strong></span>
                            <span v-if="game" v-bind:class="['badge', game.status === 'active' ? 'badge-success' : 'badge-danger']">{{ game.status }}</span>
                        </div>

                    </div>
                    <!--                    <img class="card-img-top mx-auto" src="img/no_product_image.png"-->
                    <!--                         v-bind:style="{maxWidth: 250 + 'px'}" alt="Card image cap">-->

                    <div class="card-body">
                        <button class="btn btn-outline-warning float-right" v-on:click="editBook">edit</button>
                        <div class="row">
                            <p class="card-title mb-2 col-12 col-md-6 col-lg-4" v-if="game" v-for="(value, key) in game">
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
        name: 'booksDataView',
        data: function () {
            return {
                isError: false,
                errorMessage: 'Something went wrong. Please try reloading the <a href="javascript:location.reload();">page</a>',
                game: {},
                mediaType: 'games',
            }
        },
        methods: {
            fetchData: function () {
                let self = this;
                axios.get(this.mediaType + '/' + this.$route.params.id)
                    .then(function (response) {
                        if (Object.keys(response.data).length > 0) {
                            self.game = response.data;
                        } else {
                            self.isError = true;
                        }
                    }).catch(function (error) {
                    self.isError = true;
                })
            },
            editBook: function (e) {
                this.$router.push({
                    name: 'games_edit',
                    path: 'game',
                    params: { game: this.game, fetchData: this.fetchData },
                })
            },
        },
        mounted() {
            console.log('book component');
            this.fetchData();
        },

    }

</script>