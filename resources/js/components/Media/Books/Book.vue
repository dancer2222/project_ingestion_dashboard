<template>
    <div class="col-12">
        <div class="row">

            <div class="col-12 col-sm-12">
                <div class="alert alert-danger" role="alert" v-if="isError" v-html="errorMessage"></div>

                <div class="card">
                    <div class="card-header">
                        <!--<button class="btn btn-outline-dark float-left mr-3" v-on:click="$router.back()">Back</button>-->

                        <strong>Book</strong>
                        <span v-if="book.isbn">ISBN:{{book.isbn}}</span>
                        <span v-else>{{book.title}}</span>

                        <div class = 'float-right'>
                            <span><strong >Status:</strong></span>
                            <span v-if="book" v-bind:class="['badge', book.status === 'active' ? 'badge-success' : 'badge-danger']">{{ book.status }}</span>
                        </div>

                    </div>
                    <!--                    <img class="card-img-top mx-auto" src="img/no_product_image.png"-->
                    <!--                         v-bind:style="{maxWidth: 250 + 'px'}" alt="Card image cap">-->

                    <div class="card-body" v-if="book">

                        <button class="btn btn-outline-info float-right" v-on:click="editBook">edit</button>
                        <div >
                            <img v-if="book.num_of_images > 0" v-bind:src="book.coverUrl" alt="Book cover"/>
                            <img v-else v-bind:src="pictureError" v-bind:width="200" v-bind:height="200" alt="No picture available">
                        </div>
                        <div class="row">

                            <p class="card-title mb-2 col-12 col-md-6 col-lg-4" v-if="book" v-for="(value, key) in book">

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

    export default {
        debug: true,
        name: 'booksDataView',
        data: function () {
            return {
                isError: false,
                errorMessage: 'Something went wrong. Please try reloading the <a href="javascript:location.reload();">page</a>',
                book: {},
                mediaType: 'books',
                descriptionShow : false,
                pictureError: 'img/image-not-found.jpeg',
            }
        },
        methods: {
            fetchData: function () {
                let self = this;
                axios.get(this.mediaType + '/' + this.$route.params.id)
                    .then(function (response) {
                        if (Object.keys(response.data).length > 0) {
                            self.book = response.data;
                        } else {
                            self.isError = true;
                        }
                    }).catch(function (error) {
                    self.isError = true;
                })
            },
            editBook: function (e) {
                this.$router.push({
                    name: 'books_edit',
                    path: 'book',
                    params: { book: this.book, fetchData: this.fetchData },
                })
            },
        },
        mounted() {
            console.log('book component');
            this.fetchData();
        },

    }

</script>