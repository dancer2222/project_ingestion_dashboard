<template>
    <div class="row justify-content-center">

        <card card-name="movies" icon-class="fa-film" icon-color-class="flat-color-1"></card>
        <card card-name="books" icon-class="fa-book" icon-color-class="flat-color-2"></card>
        <card card-name="music-Album" icon-class="fa-music" icon-color-class="flat-color-3"></card>
        <card card-name="audiobooks" icon-class="fa-file-audio" icon-color-class="flat-color-4"></card>

    </div>
</template>

<script>
    import Card from './Card'

    export default {
        name: 'Stats',
        components: {
            card: Card,
        },
        data: function () {
            return {}
        },
        methods: {

        },
    }
</script>