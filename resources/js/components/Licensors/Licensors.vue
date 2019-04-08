<template>
    <div class="row">
        <div class="col-sm-6">
            <button @click="update" class="btn btn-primary pl-2 pr-4 mb-2">Get all licensors</button>
        </div>

        <table class="table table-hover table-responsive text-dark" v-if="is_refresh">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Media Type</th>
                <th>Status</th>
                <th>Contract start</th>
                <th>Contract end</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="licensor in licensors">
                <td><a href="">{{ licensor.id }}</a></td>
                <td>{{ licensor.name }}</td>
                <td>{{ licensor.media_type }}</td>
                <td v-if="licensor.status == 'active'">
                    <span class="font-weight-bold badge badge-success">
                        {{ licensor.status }}
                    </span>
                </td>
                <td v-else>
                    <span class="font-weight-bold badge badge-danger">
                        {{ licensor.status }}
                    </span>
                </td>
                <td>{{ licensor.contract_start }}</td>
                <td>{{ licensor.contract_end }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                licensors: [],
                is_refresh: false,
            }
        },
        mounted() {

        },
        methods: {
            update: function () {
                axios
                    .get('/licensors/get-json')
                    .then((response) => {
                        this.licensors = response.data;
                        if (this.is_refresh == false) {
                            this.is_refresh = true
                        } else {
                            this.is_refresh = false
                        }
                    });
            }
        }
    };
</script>
