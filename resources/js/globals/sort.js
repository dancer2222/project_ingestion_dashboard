export default {
    install: function (Vue, options) {
        Vue.prototype.$g = {
            sortBy: function (obj, key) {
                console.log(obj, key, 'global method');
            }
        }
    }
}