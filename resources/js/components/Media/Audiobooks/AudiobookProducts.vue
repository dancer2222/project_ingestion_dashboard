<template>
    <div class="container">
        <button type="button" class="btn btn-success" data-toggle="collapse" data-target="#products">Show tracks in album
        </button>
        <div id="products" class="collapse">
            <ul class="list-group">
                <li v-for="(product, key) in products" class="list-group-item" :style="{cursor:'pointer'}">
                    <b>
                        <span style="color: #99a9ff" v-for="(value, data) in product">
                            {{ data }} - {{ value }}
                        </span>
                    </b>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AudiobookProducts",
        props: [
            "products",
        ],
        data: function () {
            return {
                product: {},
                productkData: null,
            }
        },
    }
</script>

<style scoped>

</style>