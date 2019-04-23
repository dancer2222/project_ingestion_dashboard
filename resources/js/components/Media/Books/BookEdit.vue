<template>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>
                    <button class="btn btn-outline-dark float-left" v-on:click="$router.back()">Back</button>
                    <span class="ml-3">Edit - {{ bookCopy.title }}</span>
                    <button class="btn btn-outline-success float-right" v-on:click="updateMovie">Update</button>
                </h4>
            </div>

            <div class="card-body">
                <form action="">

                    <div class="row">
                        <div class="form-group" v-for="(value, key) in book" v-bind:class="['col-12 col-md-6 col-lg-4']">
                            <label :for="key">{{ key }} </label>
                            <input :type="key" class="form-control" :id="key" v-model="book[key]" v-bind:value="book[key]">
                        </div>
                    </div>

                    <button class="btn btn-outline-success w-100" v-on:click="updateMovie">Update</button>
                </form>
            </div>
        </div>
    </div>
</template>


<script>
    import axios from 'axios';
    import toastr from 'toastr';

    export default {
        data: function () {
            return {
                bookCopy: {},
                book: {},
                mediaType: 'books',
            }
        },
        mounted: function () {
            console.log('book edit component');
            this.fetchData();
        },
        methods: {
            fetchData: function () {
                let self = this;

                axios.get(this.mediaType + '/' + this.$route.params.id)
                    .then(function (response) {
                        if (Object.keys(response.data).length > 0) {
                            self.book = response.data;
                            self.bookCopy = {
                                ...self.book
                            };
                        } else {
                            self.isError = true;
                        }
                    }).catch(function (error) {
                    self.isError = true;
                })
            },
            updateMovie: function () {
                let self = this;
                let needToBeUpdated = {};

                for (let key in this.book) {
                    if (this.book[key] !== this.bookCopy[key]) {
                        needToBeUpdated[key] = this.book[key];
                    }
                }

                if (Object.keys(needToBeUpdated).length > 0) {
                    axios.post('/books/' + this.book.id, needToBeUpdated)
                        .then(function (response) {
                            if (response.data.status) {
                                self.bookCopy = {
                                    ...self.book
                                };

                                toastr.success(response.data.message);
                            } else {
                                toastr.error(response.data.message);
                            }
                        }).catch(function () {
                        toastr.error('Something went wrong. Can\'t save the book.');
                    })
                }
            },
        },
    }
</script>