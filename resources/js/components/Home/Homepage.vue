<template>
    <div>
        <top-stats></top-stats>
    </div>
</template>

<script>
    import Stats from './TopStats/Stats'

    export default {
        name: 'homepage',
        components: {
            topStats: Stats,
        },
        data: function() {
            return {

            }
        },
        mounted: function () {

        },
        methods: {

        },
    }
</script>