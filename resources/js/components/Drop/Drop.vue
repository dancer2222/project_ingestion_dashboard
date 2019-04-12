<template>
    <div class="container">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon3">Licensor folder name</span>
            </div>
            <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" v-model="licensor" >
        </div>

        <table class="table table-bordered table-responsive" v-if="licensor !== null">
            <thead class="thead-light">
            <tr>
                <th width="1">#</th>
                <th width="10%">Title</th>
                <th width="10%">Year</th>
                <th width="10%">File name</th>
                <th width="150">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index) in items" :key="index">
                <td>{{ index + 1 }}</td>
                <td>
                    <span v-if="editIndex !== index">{{ item.title }}</span>
                    <span v-if="editIndex === index">
                        <input class="form-control form-control-m" v-model="item.title">
                    </span>
                </td>
                <td>
                    <span v-if="editIndex !== index">{{ item.year }}</span>
                    <span v-if="editIndex === index">
                        <input class="form-control form-control-m" v-model="item.year">
                    </span>
                </td>
                <td>
                    <span v-if="editIndex !== index">{{ item.filename }}</span>
                    <span v-if="editIndex === index">
                      <input class="form-control form-control-m" v-model="item.filename">
                    </span>
                </td>
                <td>
                    <span v-if="editIndex !== index">
                      <button @click="edit(item, index)" class="btn btn-sm btn-outline-secondary mr-2">Edit</button>
                      <button @click="remove(item, index)" class="btn btn-sm btn-outline-secondary mr-2">Remove</button>
                    </span>
                    <span v-else>
                      <button class="btn btn-sm btn-outline-secondary mr-2" @click="save(item)">Save</button>
                    </span>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="col-3 offset-9 text-right my-3">
            <button @click="add" class="btn btn-sm btn-secondary" v-if="licensor !== null && editIndex === null">Add item</button>
        </div>
        <div class="col-3 offset-9 text-right my-3">
            <button @click="submit" class="btn btn-sm btn-secondary" v-if="licensor !== null && editIndex === null">Submit</button>
        </div>

    </div>
</template>


<script>
    export default {
        data() {
            return {
                editIndex: null,
                originalData: null,
                items: [],
                licensor: null
            }
        },
        methods: {

            add() {
                this.originalData = null
                this.items.push({title: '', year: '', filename: ''})
                this.editIndex = this.items.length - 1
            },

            edit(item, index) {
                this.originalData = Object.assign({}, item)
                this.editIndex = index
            },

            remove(item, index) {
                this.items.splice(index, 1)
            },

            save(item) {
                if (item.title == '' || item.year == '' || item.filename == '') {
                    this.editIndex = 1
                    this.originalData = 1
                } else {
                    axios.post('/awsCheck', {body: item.title, folder: this.licensor})
                        .then((response) => {
                            if (response.data !== true) {
                                this.editIndex = 1
                                this.originalData = 1
                            } else {
                                this.originalData = null
                                this.editIndex = null
                            }
                        });
                }
                console.log(item.title);
            },
            submit() {
                console.log(this.items);
            }
        },
    }
</script>
