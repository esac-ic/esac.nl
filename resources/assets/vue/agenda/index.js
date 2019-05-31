import Vue from 'vue';

import Agenda from './components/Agenda';
import store from './store'


/* eslint-disable no-unused */
const main = new Vue({
    el: '#agenda',
    components: { Agenda },
    store,
    render: h => h(Agenda)
});