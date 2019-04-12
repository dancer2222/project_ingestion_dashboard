<template>
    <div>
        <div class="row">

            <div class="col-sm-6">
                <div class="form-group">
                    <div class="input-group mb-1">
                        <input type="text" name="body"
                               id="body"
                               class="form-control"
                               placeholder="What do you think?"
                               rows="5"
                               required
                               v-model="body"
                               @keyup.enter="addReply">
                    </div>
                    <div class="input-group-append">
                        <button type="submit"
                                class="btn btn-primary pl-4 pr-4 mb-2"
                                @click="addReply">Find
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="is_refresh">

            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">

                        <h5 class="mt-0 mb-1">Name: {{ licensors.name }} <i
                                class="align-self-center ml-3 fas fa-user f-s-40"></i></h5>
                        <p class="h-4 mt-3">ID: {{ licensors.id }}</p>
                        <p class="h-4">Media type: {{ licensors.media_type }}</p>
                    </div>


                </div>
            </div>

            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">

                        <h5 class="mt-0 mb-1">{{ activeItems }} <i
                                class="align-self-center mr-3 fas fa-smile text-success f-s-40"></i></h5>
                        <p class="h-4 mt-3">active {{ licensors.media_type }}</p>
                    </div>

                </div>

            </div>

            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">

                        <h5 class="mt-0 mb-1">{{ inactiveItems }} <i
                                class="align-self-center mr-3 fas fa-dizzy text-danger f-s-40"></i></h5>
                        <p class="h-4 mt-3">inactive {{ licensors.media_type }}</p>
                    </div>

                </div>

            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: ['endpoint'],

        data() {
            return {
                body: '',
                licensors: {},
                activeItems: {},
                inactiveItems: {},
                is_refresh: false
            }
        },

        methods: {
            addReply() {
                axios.post('/licensors/search', {body: this.body})
                    .then((response) => {
                        this.licensors = response.data[0];
                        this.activeItems = response.data.activeItems;
                        this.inactiveItems = response.data.inactiveItems;
                        this.is_refresh = true;
                    });
            }
        }
    }
</script>
