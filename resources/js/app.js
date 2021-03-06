
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('jquery-match-height');
require('./theme/main');
window.Vue = require('vue');
require('./filters/filters');

import VueRouter from 'vue-router'
import Routes from './components/Media/Search/Routes'
import SearchApp from './components/Media/Search/SearchComponent'
import Homepage from './components/Home/Homepage'
import GlobalMethods from './globals/sort'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('licensors-list', require('./components/Licensors/Licensors').default);
Vue.component('search-form', require('./components/Licensors/SearchForm').default);
Vue.component('meatadata-form', require('./components/MetadataForm/MetadataForm').default);
Vue.component('tracks', require('./components/Media/Albums/Tracks').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});


jQuery(document).ready(function($) {
    if ($('#homepage').length > 0) {
        const homepage = new Vue({
            el: '#homepage',
            render: h => h(Homepage)
        })
    }

    if ($('#media_search_page').length > 0) {
        var router = new VueRouter({
            routes: Routes
        });

        Vue.use(VueRouter);
        Vue.use(GlobalMethods);
        const appSearch = new Vue({
            el: '#media_search_page',
            render: h => h(SearchApp),
            router: router,
        })
    }
});

