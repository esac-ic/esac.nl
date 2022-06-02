import Vue from 'vue';
import store from '../vue/store';
import VuePaginate from 'vue-paginate';

window.Vue = Vue;
Vue.use(VuePaginate);

//load components
require('../vue/agenda/agenda');
require('../vue/zekeringen/zekeringen');
require('../vue/applicationForm/applicationForm');

Vue.component('auto-complete-field', require('../vue/components/AutoCompleteField').default);

if (document.getElementById('app') !== null) {
    /**
     * Create vue for the core package
     */
    const core = new Vue({
        el: '#app',
        store
    });
}