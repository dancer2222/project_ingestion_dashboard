<template>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>
                    <button class="btn btn-outline-dark float-left" v-on:click="$router.back()">Back</button>
                    <span class="ml-3">Edit - {{ abookCopy.title }}</span>
                    <button class="btn btn-outline-success float-right" v-on:click="update">Update</button>
                </h4>
            </div>

            <div class="card-body">
                <form action="">

                    <div class="row">
                        <div class="form-group" v-for="(value, key) in abook" v-bind:class="['col-12 col-md-6 col-lg-4']">
                            <label :for="key">{{ key }} </label>
                            <input :type="key" class="form-control" :id="key" v-model="abook[key]" v-bind:value="abook[key]">
                        </div>
                    </div>

                    <button class="btn btn-outline-success w-100" v-on:click="update">Update</button>
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
                abookCopy: {},
                abook: {},
            }
        },
        mounted: function () {
            console.log('audiobooks edit component');
            this.fetchData();
        },
        methods: {
            fetchData: function () {
                let self = this;

                axios.get(location.origin + '/audiobooks/' + this.$route.params.id)
                    .then(function (response) {
                        if (Object.keys(response.data).length > 0) {
                            self.abook = response.data;
                            self.abookCopy = {
                                ...self.abook
                            };
                        } else {
                            self.isError = true;
                        }
                    }).catch(function (error) {
                    self.isError = true;
                })
            },
            update: function () {
                let self = this;
                let needToBeUpdated = {};

                for (let key in this.abook) {
                    if (this.abook[key] !== this.abookCopy[key]) {
                        needToBeUpdated[key] = this.abook[key];
                    }
                }

                if (Object.keys(needToBeUpdated).length > 0) {
                    axios.post('/audiobooks/' + this.abook.id, needToBeUpdated)
                        .then(function (response) {
                            if (response.data.status) {
                                self.abookCopy = {
                                    ...self.abook
                                };

                                toastr.success(response.data.message);
                            } else {
                                toastr.error(response.data.message);
                            }
                        }).catch(function () {
                        toastr.error('Something went wrong. Can\'t save the audiobook.');
                    })
                }
            },
        },
    }
</script>