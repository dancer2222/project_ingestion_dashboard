<template>
    <div class="col-12">
        <div class="row">

            <div class="col-12 col-sm-12">
                <div class="alert alert-danger" role="alert" v-if="isError" v-html="errorMessage"></div>

                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-outline-dark float-left mr-3" v-on:click="$router.back()">Back</button>

                        {{ abook.title }}

                        <button class="btn btn-outline-info float-right" v-on:click="edit">edit</button>
                    </div>
                    <!--                    <img class="card-img-top mx-auto" src="img/no_product_image.png"-->
                    <!--                         v-bind:style="{maxWidth: 250 + 'px'}" alt="Card image cap">-->

                    <div class="card-body">

                        <div class="row">
                            <AudiobookProducts :products="products" />
                            <p class="card-title mb-2 col-12 col-md-6 col-lg-4" v-if="abook" v-for="(value, key) in abook">
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
    import AudiobookProducts from "./AudiobookProducts";

    export default {
        components: {AudiobookProducts},
        debug: true,
        name: 'audiobook',
        data: function () {
            return {
                isError: false,
                errorMessage: 'Something went wrong. Please try reloading the <a href="javascript:location.reload();">page</a>',
                abook: {},
                descriptionShow: false,
                products: {},
            }
        },
        mounted() {
            console.log('audiobook component');
            this.fetchData();
        },
        methods: {
            fetchData: function () {
                let self = this;

                axios.get(location.origin + '/audiobooks/' + this.$route.params.id)
                    .then(function (response) {
                        if (Object.keys(response.data).length > 0) {
                            self.abook = response.data;
                            self.products = self.abook.products;
                            Vue.delete(self.abook, 'products');
                        } else {
                            self.isError = true;
                        }
                    }).catch(function (error) {
                    self.isError = true;
                })
            },
            edit: function (e) {
                console.log('edit abook');
                this.$router.push({
                    name: 'audiobook_edit',
                    path: 'audiobooks',
                    params: { abook: this.abook },
                })
            },
        },
    }
</script>