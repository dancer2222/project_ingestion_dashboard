<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                    
                <div class="card-body">
                    <form action="" method="POST" class="w-100" id="create_permission"
                        v-on:submit.prevent="submitForm">

                        <div class="form-row">

                            <div class="form-group col-md-8">
                                <input type="text" class="form-control" name="q" id="q" placeholder="..."
                                    v-model="query">
                            </div>

                            <div class="form-group col-md-3">
                                <select id="media_type" name="media_type" v-bind:class="['form-control', {'is-invalid': isMediaTypeInvalid}]"
                                    v-model="mediaType">
                                    <option disabled value="">Media type</option>

                                    <option v-for="media_type in mediaTypes"
                                        v-bind:value="media_type">{{ media_type }}</option>

                                </select>
                            </div>

                            <div class="form-group col-md-1">
                                <button type="submit" class="btn btn-outline-success w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>

                        </div>
                        
                    </form>
                </div>

            </div>

        </div>


        <transition>
            <router-view></router-view>
        </transition>

    </div>
</template>

<script>
import msg from 'toastr';

const mediaTypes = [
    'books', 'movies', 'audiobooks', 'albums', 'games',
];

export default {
    name: 'App',
    data: function () {
        return {
            isMediaTypeInvalid: false,
            mediaTypes: mediaTypes,
            mediaType: false,
            query: this.$route.query.q
        }
    },
    mounted: function () {
        for (let media_type of this.mediaTypes) {
            if (this.$route.fullPath.match(media_type) !== null) {
                this.mediaType = media_type;
            }
        }
    },
    methods: {
        submitForm: function () {
            if (!this.mediaType) {
                this.isMediaTypeInvalid = true;
                msg.warning("Please choose the media type");

                return;
            }

            this.isLoading = true;
            this.isMediaTypeInvalid = false;

            this.$router.push({
                name: this.mediaType,
                path: '/'+this.mediaType,
                query: {
                    q: this.query,
                },
                params: {
                    q: this.query,
                },
            });
        },
    },
}

</script>