<template>
    <div class="container">

                <table class="table table-hover text-dark">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="provider in providers">
                            <td><a href="">{{ provider.id }}</a> </td>
                            <td>{{ provider.name }}</td>
                        </tr>
                    </tbody>
                </table>

    </div>
</template>

<script>
    export default {
        props: [
            'providers'
        ],
        mounted() {
            this.update();
        },
        methods: {
            update: function() {
                console.log(this.providers)
            }
        }
    };
</script>
