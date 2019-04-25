<template>
    <div class="form-row">
        <div class="form-group col">

            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input"
                       name="status[active]" id="status_active"
                    v-model="filters.status['active']" v-bind:value="filters.status['active']"
                    v-on:change="watchFilters">
                <label class="custom-control-label" for="status_active">active</label>
            </div>

            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input"
                       name="status[inactive]" id="status_inactive"
                       v-model="filters.status['inactive']" v-bind:value="filters.status['inactive']"
                       v-on:change="watchFilters">
                <label class="custom-control-label" for="status_inactive">inactive</label>
            </div>

            <books v-if="mediaType === 'books'"></books>
            <movies v-if="mediaType === 'movies'"></movies>
            <audiobooks v-if="mediaType === 'audiobooks'"></audiobooks>

        </div>
    </div>
</template>

<script>
    import Books from './Books';
    import Movies from './Movies';
    import Audiobooks from './Audiobooks';

    export default {
        props: {
            mediaType: {
                type: String,
                validator: function (mt) {
                    return ['', 'books', 'movies', 'audiobooks', 'albums', 'games'].indexOf(mt) !== -1;
                }
            },
        },
        components: {
            books: Books,
            movies: Movies,
            audiobooks: Audiobooks,
        },
        data: () => {
            return {
                filters: {
                    status: { active: false, inactive: false, },
                }
            }
        },
        methods: {
            watchFilters: function () {
                this.$emit('update-filters', {...this.filters})
            },
        },
        // watch: {
        //     'filters.status.active': function () { console.log('active changed');},
        //     'filters.status.inactive': function () { console.log('inactive changed');},
        // },
    }
</script>