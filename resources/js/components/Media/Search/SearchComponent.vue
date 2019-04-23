<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                    
                <div class="card-body">
                    <div class="w-100 mb-3">
                        <button class="btn btn-sm btn-outline-dark" v-on:click="$router.push('/movies')">
                            Movies
                        </button>
                        <button class="btn btn-sm btn-outline-dark" v-on:click="$router.push('/books')">
                            Books
                        </button>
                        <button class="btn btn-sm btn-outline-dark" v-on:click="$router.push('/audiobooks')">
                            Audiobooks
                        </button>
                        <button class="btn btn-sm btn-outline-dark" v-on:click="$router.push('/albums')">
                            Albums
                        </button>
                        <button class="btn btn-sm btn-outline-dark" v-on:click="$router.push('/Games')">
                            Games
                        </button>
                    </div>

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

                        <!-- Filters -->
                        <div class="form-row">
                            <div class="form-group col">
                                <a href="#filters-collapse" data-toggle="collapse"  role="button" aria-expanded="false"
                                   aria-controls="filters-collapse"
                                   class="float-right" id="search-filters-toggler">
                                    filters
                                </a>
                            </div>
                        </div>
                        <div class="collapse" id="filters-collapse">
                            <Filters v-on:update-filters="onUpdateFilters" v-bind:media-type="mediaType"></Filters>
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
import Filters from './Filters/Common';

const mediaTypes = [
    'books', 'movies', 'audiobooks', 'albums', 'games',
];

export default {
    name: 'App',
    components: {
        Filters: Filters,
    },
    data: function () {
        return {
            isMediaTypeInvalid: false,
            mediaTypes: mediaTypes,
            mediaType: false,
            query: this.$route.query.q,
            filters: {},
        }
    },
    mounted: function () {
        this.setMediaTypeInSelect()
    },
    updated: function () {
        this.setMediaTypeInSelect()
    },
    beforeMount: function () {
        this.setMediaTypeInSelect();
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

            this.redirect();
        },
        onUpdateFilters: function (filters) {
            this.filters = filters;
        },
        redirect: function () {
            let query = {
                q: this.query,
                ...this.getFilters(),
            };

            this.$router.push({
                name: this.mediaType,
                path: '/'+this.mediaType,
                query: query,
                params: {
                    q: this.query,
                },
            });
        },
        getFilters: function () {
            let filters = {};

            for (let filter in this.filters) {
                switch (filter) {
                    case 'status':
                        if (this.filters['status'].active === true) {
                            filters['status_active'] = true;
                        }

                        if (this.filters['status'].inactive === true) {
                            filters['status_inactive'] = true;
                        }
                        break;
                }
            }

            return filters;
        },
        setMediaTypeInSelect: function () {
            for (let media_type of this.mediaTypes) {
                if (this.$route.fullPath.match(media_type) !== null) {
                    this.mediaType = media_type;
                }
            }
        },
    },
    watch: {
        mediaType: function () {
            if (this.$route.fullPath.match(this.mediaType) === null) {
                this.redirect()
            }
        },
    },
}

</script>