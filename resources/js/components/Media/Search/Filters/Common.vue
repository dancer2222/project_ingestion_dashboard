<template>
    <div class="form-row">
        <div class="form-group col">

            <div class="custom-control custom-radio custom-control-inline search-radio">
                <input type="radio" id="customRadioInline4" name="customRadioInline1" class="custom-control-input"
                   v-model="filters.search_by"
                    value="default">
                <label class="custom-control-label" for="customRadioInline4">Default</label>
            </div>

            <div class="custom-control custom-radio custom-control-inline search-radio">
                <input type="radio" id="customRadioInline2" name="customRadioInline1" class="custom-control-input"
                   v-model="filters.search_by"
                   value="licensor">
                <label class="custom-control-label" for="customRadioInline2">Licensor</label>
            </div>

            <div class="custom-control custom-radio custom-control-inline search-radio"
                v-if="['books', 'audiobooks'].indexOf(mediaType) !== -1">
                <input type="radio" id="customRadioInline1" name="customRadioInline1" class="custom-control-input"
                   v-model="filters.search_by"
                   value="artist">
                <label class="custom-control-label" for="customRadioInline1">Artist</label>
            </div>

            <div class="custom-control custom-radio custom-control-inline search-radio"
                v-if="['books', 'audiobooks'].indexOf(mediaType) !== -1">
                <input type="radio" id="customRadioInline3" name="customRadioInline1" class="custom-control-input"
                   v-model="filters.search_by"
                   value="author">
                <label class="custom-control-label" for="customRadioInline3">Author</label>
            </div>

<!--            <books v-if="mediaType === 'books'"></books>-->
<!--            <movies v-if="mediaType === 'movies'"></movies>-->
<!--            <audiobooks v-if="mediaType === 'audiobooks'"></audiobooks>-->

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
                    // status: 'active',
                    search_by: 'default'
                }
            }
        },
        methods: {
            updateFilters: function () {
                this.$emit('update-filters', {...this.filters})
            },
        },
        watch: {
        //     'filters.status.active': function () { console.log('active changed');},
        //     'filters.status.inactive': function () { console.log('inactive changed');},
            'filters.search_by': function(v) {
                this.updateFilters()
            }
        },
    }
</script>

<style>
    .search-radio label:after, .search-radio label:before {
        top: 2px
    }

    .search-radio label {
        font-size: 12px;
    }
</style>