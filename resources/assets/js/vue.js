window.Vue = require('vue');

Vue.component('auto-complete-field', require('../vue/components/AutoCompleteField').default);

/**
 * Create vue for the core package
 */
const core = new Vue({
    el: '#app'
});