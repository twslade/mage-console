<template>
    <div @keyup.ctrl.enter="processCode()">
        <projects v-model="projectConfig" :projects="projects"></projects>
        <pre v-html="output"></pre>
        <brace style="height: 500px"
               :fontsize="'12px'"
               :theme="'twilight'"
               :mode="'php'"
               :codefolding="'markbegin'"
               :softwrap="'free'"
               :selectionstyle="'text'"
               :highlightline="true"
               v-on:code-change="updateCodeData"
                >
        </brace>
        <button v-on:click="processCode()">Run!</button>
    </div>
</template>

<script>
    import Brace from 'vue-bulma-brace';
    import Projects from './Projects.vue';
    import axios from 'axios';
    import * as brace from 'brace'

    export default {
        props: ['projects'],
        data(){
            return {
                code: '',
                output: '',
                editor: false,
                projectConfig: {},
            }
        },
        components: {
            Brace,
            Projects,
        },
        methods: {
            updateCodeData(code){
                this.$ls.set('code', code);
                this.code = code;
            },
            processCode(){
                axios.post('/',{code: this.code})
                    .then(res => this.setOutput(res.data));
            },
            setOutput(output){
                this.output = output;
            }
        },
        mounted(){
            this.code = this.$ls.get('code');
            this.editor = brace.edit('vue-bulma-editor');
            this.editor.setValue(this.code);
        },
    }
</script>
