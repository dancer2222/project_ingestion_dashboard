<template>
    <div class="container">
        <div class="input-group-prepend">
            <div class="input-group-prepend">
                <label class="input-group-text" for="inputGroupSelect01">Select licensor folder name</label>
            </div>
            <select class="custom-select" id="inputGroupSelect01" v-model="licensor" v-on:change="onChange">
                <option v-for="(name, index) in licensorNames">
                    {{ name }}
                </option>
            </select>
        </div>

        <div class="row justify-content-center" v-if="licensor !== null">
            <div class="card-body">
                <div v-if="success != ''" class="alert alert-info" role="alert">
                    {{success}}
                </div>
                <form enctype="multipart/form-data">
                    <strong>Choose file:</strong>
                    <input type="file" class="form-control" v-on:change="onChange">
                </form>
            </div>
        </div>

        <table class="table table-bordered table-hover" v-if="items !== null">
            <thead class="thead-light">
            <tr>
                <th width="1">Title</th>
                <th width="1">Year</th>
                <th width="1">Source filename</th>
                <th width="1">Present in AWS</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="movie in items">
                <td>
                    <span>{{ movie.title }}</span>
                </td>
                <td>
                    <span>{{ movie.year }}</span>
                </td>
                <td>
                    <span>{{ movie.src }}</span>
                </td>
                <td>
                    <span v-if="movie.saved === true">
                        <img v-bind:src="pictureSuccess" v-bind:width="width"
                             v-bind:height="height">
                    </span>
                    <span v-else>
                        <img v-bind:src="pictureError" v-bind:width="width" v-bind:height="height">
                    </span>
                </td>

            </tr>
            </tbody>
        </table>
        <center>
            <button class="btn btn-lg btn-outline-success" v-if="submitData !== false && items !== null"
                    @click="submit">Start ingestion
            </button>
        </center>

    </div>

</template>

<script>
    import toastr from 'toastr';

    export default {
        props: {
            data: Array
        },
        data() {
            return {
                file: '',
                success: '',
                items: null,
                pictureError: 'img/error.svg',
                pictureSuccess: 'img/success.svg',
                width: '50px',
                height: '50px',
                submitData: true,
                licensor: null,
                licensorNames: this.data[0],
            };
        },
        methods: {
            onChange(e) {
                if (e.target.files !== undefined) {
                    this.file = e.target.files[0];
                }

                if (this.file !== '') {
                    e.preventDefault();

                    let formData = new FormData();
                    formData.append("file", this.file);

                    axios.post('/ingestion/movie/convertMetadata', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(({data}) => {
                        this.items = data.items;
                        this.checkInAwsBucket();
                    })
                        .catch(function (error) {
                            toastr.error(error);
                        });
                }
            },
            submit(e) {

                if (this.submitData === true) {
                    axios.post('/ingestion/movie/ombdApi', {body: this.items, metadata: this.file.name,
                        licensorName: this.licensor})
                        .then((response) => {
                            toastr.success('Everything OK');
                        });
                }
            },
            checkInAwsBucket() {
                let i;
                for (i = 0; i < this.items.length; i++) {
                    if (this.items[i].title == '') {
                        this.items.splice(i, 1)
                    }
                }

                for (let obj in this.items) {
                    axios.post('/ingestion/movie/awsCheck', {body: this.items[obj].src, folder: this.licensor})
                        .then((response) => {
                            if (response.data !== true) {
                                Vue.set(this.items[obj], 'saved', false)
                                this.submitData = false
                                toastr.error(this.items[obj].src + ' - Not found in aws bucket');
                            } else {
                                Vue.set(this.items[obj], 'saved', true)
                                this.submitData = true
                                toastr.success(this.items[obj].src + ' - present in aws bucket');
                            }
                        });
                }
            },

        }
    }
</script>