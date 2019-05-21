<template>
    <div class="col-lg-3 col-md-6 vld-parent">
        <div class="card">
            <loading :active.sync="isLoading"
                     :can-cancel="false"
                     :is-full-page="false"
                     :loader="'bars'"
                     :background-color="'#525050'"></loading>

            <div class="card-body">
                <div class="stat-widget-five">
                    <div :class="'stat-icon dib ' + iconColorClass">
                        <i :class="'fas ' + iconClass"></i>
                    </div>
                    <div class="stat-content">
                        <div class="text-left dib">
                            <div class="stat-text"><span class="count">{{ quantity }}</span></div>
                            <div class="stat-heading">{{ cardName | capitalize }}</div>
                            <div class="stat-heading text-danger" v-if="isError">
                                <small>
                                    Failed
                                </small>
                            </div>
                            <div class="stat-heading">
                                <small>
                                    <a href="#" v-on:click.prevent="getQuantity">Reload</a>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Loading from 'vue-loading-overlay';

    export default {
        name: "card_stats",
        props: {
            cardName: String,
            iconClass: String,
            iconColorClass: String,
        },
        components: {
            loading: Loading,
        },
        data: function () {
            return {
                isError: false,
                isLoading: true,
                quantity: 0,
            }
        },
        mounted() {
            this.getQuantity()
        },
        methods: {
            getQuantity: function () {
                this.isLoading = true;
                let self = this;

                axios.get(location.origin + '/stats/'+ this.cardName +'/total_count')
                    .then(function (r){
                        if (!r || typeof(r) !== 'object') {
                            self.isError = true;
                        } else {
                            self.quantity = r.data;
                            self.isError = false;
                        }

                        self.isLoading = false;
                    })
            },
        },
    }
</script>