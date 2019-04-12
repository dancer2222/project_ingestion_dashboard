<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                    
                <div class="card-body">
                    <form action="" method="POST" class="w-100" id="create_permission"
                        v-on:submit.prevent="submitForm">

                        <div class="form-row">

                            <div class="form-group col-md-8">
                                <input type="text" class="form-control" name="q" id="q" placeholder="..."
                                    v-model="query">
                            </div>

                            <div class="form-group col-md-3">
                                <select id="media_type" name="media_type" class="form-control" 
                                    v-model="mediaType">
                                    <option disabled value="">Media type</option>
                                    <option value="books">Books</option>
                                    <option value="movies">Movies</option>
                                    <option value="audiobooks">Audiobooks</option>
                                    <option value="albums">Albums</option>
                                    <option value="games">Games</option>
                                </select>
                            </div>

                            <div class="form-group col-md-1">
                                <button type="submit" class="btn btn-outline-success w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>

                        </div>
                        
                    </form>
                </div>
            </div>

        </div>

        <router-view></router-view>
    </div>
</template>

<script>

export default {
    name: 'App',
    data: function () {
        return {
            mediaType: '',
            query: ''
        }
    },
    methods: {
        submitForm: function (event) {
            console.log(this.mediaType, this.query)

            if (this.query.length > 0) {
                this.$router.push(this.mediaType);
            }
        },
    },
}

</script>