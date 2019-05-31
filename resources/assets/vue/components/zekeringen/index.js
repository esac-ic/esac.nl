import Vue from 'vue';

import Zekeringen from './components/Zekeringen';
import VuePaginate from 'vue-paginate'

Vue.use(VuePaginate);


/* eslint-disable no-unused */
const main = new Vue({
    el: '#zekeringen',
    components: { Zekeringen },
    render: h => h(Zekeringen)
});