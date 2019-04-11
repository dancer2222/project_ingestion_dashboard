<template>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Books header
            </div>

            <div class="card-body">
                Books list
            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: 'books',
    data: function () {
        return {
            mediaType: 'books',
            movies: [],
        }
    },
    methods: {

    },
}
</script>