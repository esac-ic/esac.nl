import Vue from 'vue';
import Materiaal from './components/materiaal/Materiaal';

const main = new Vue({
    el: '#materiaal',
    components: { Materiaal },
    render: h => h(Materiaal)

});