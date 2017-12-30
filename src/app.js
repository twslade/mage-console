import Vue from 'vue';

import BraceEditor from './components/Brace.vue';
import VueLocalStorage from 'vue-ls';

const options = {
    namespace: 'php_console__',
};

Vue.use(VueLocalStorage, options);

const app = new Vue({
    el: '#app',
    components: {
        BraceEditor,
    }
});