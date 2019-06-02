window.Vue = require('vue');
import store from '../vue/store';
import VuePaginate from 'vue-paginate';

Vue.use(VuePaginate);

//load components
require('../vue/agenda/agenda');
require('../vue/zekeringen/zekeringen');

Vue.component('auto-complete-field', require('../vue/components/AutoCompleteField').default);

/**
 * Create vue for the core package
 */
const core = new Vue({
    el: '#app',
    store
});