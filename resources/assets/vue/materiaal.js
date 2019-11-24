import Vue from 'vue';
import InputSpinner from "react-native-input-spinner";
import Materiaal from './components/materiaal/Materiaal';

const main = new Vue({
    el: '#materiaal',
    components: { Materiaal },
    render: h => h(Materiaal)

});