import Vue from 'vue';

import BraceEditor from './components/Brace.vue';
import VueLocalStorage from 'vue-ls';
import Vuetify from 'vuetify';


require('../node_modules/vuetify/dist/vuetify.min.css');

const options = {
    namespace: 'php_console__',
};

Vue.use(VueLocalStorage, options);
Vue.use(Vuetify);

const app = new Vue({
    el: '#app',
    components: {
        BraceEditor,
    }
});