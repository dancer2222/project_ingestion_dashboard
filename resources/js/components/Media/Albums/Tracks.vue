<template>
    <div class="container">

        <ul class="list-group">
            <li v-for="track in tracks" class="list-group-item" :style="{cursor:'pointer'}">
                <b v-on:click="showTraks(track)">
                    <span style="color: red">ID</span> : {{ track.id }} | <span
                        style="color: red">Title</span> : {{ track.title }} | <span
                        style="color: red">Artist name</span> : {{ track.artist_name }}</b>
            </li>
        </ul>

        <p class="card-title mb-2 col-12 col-md-6 col-lg-4" v-if="trackData !== false"
           v-for="(value, key) in trackData">
            {{ key }} - {{ value }}
        </p>

    </div>
</template>

<script>
    export default {
        name: "Tracks",
        props: [
            "tracks",
        ],
        data: function () {
            return {
                track: {},
                trackData: null,
            }
        },
        methods: {
            showTraks: function (track) {
                axios.get('albums/track/' + track.id, {id: track.id})
                    .then(({data}) => {
                        this.trackData = data;
                        this.$router.push({
                            name: 'track', path: 'albums/track/' + track.id, params: {track: this.trackData}
                        });
                    });
            }
        }
    }
</script>