<template>
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-outline-dark float-center mr-3" v-on:click="$router.back()">Back</button>
                        <div class="row">
                        <p class="card-title mb-2 col-12 col-md-6 col-lg-4" v-for="(value, key) in track">
                            {{ key }} - {{ value }}
                        </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Track",
        data: function () {
            return {
                track: this.$route.params.track,
            }
        },
    }
</script>
