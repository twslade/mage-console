<template>
    <v-app id="inspire" dark>
        <v-navigation-drawer
                clipped
                fixed
                v-model="drawer"
                app
        >
            <v-list dense>
                <v-list-tile @click="">
                    <v-list-tile-action>
                        <v-icon>dashboard</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Dashboard</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click="">
                    <v-list-tile-action>
                        <v-icon>settings</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Settings</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar app fixed clipped-left>
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-toolbar-title>{{ projectConfig.projectTitle }}</v-toolbar-title>
        </v-toolbar>
        <v-content>
            <v-container fluid fill-height @keyup.ctrl.enter="processCode()">
                <v-layout row wrap>
                    <projects v-model="projectConfig" :projects="projects"></projects>
                    <options v-model="options"></options>
                    <v-flex xs6>
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
                    </v-flex>
                    <v-flex xs6>
                        <div v-html="output"></div>
                    </v-flex>
                    <v-flex xs12>
                        <div class="text-xs-center">
                            <div>
                                <v-btn color="primary" dark large v-on:click="processCode()">Run</v-btn>
                            </div>
                        </div>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
        <v-footer app fixed>
            <span>&copy; 2017</span>
        </v-footer>
    </v-app>
</template>

<script>
    import Brace from 'vue-bulma-brace';
    import Projects from './Projects.vue';
    import Options from './Options.vue';
    import axios from 'axios';
    import * as brace from 'brace'

    export default {
        props: ['projects'],
        data(){
            return {
                code: '',
                output: '',
                editor: false,
                projectConfig: {
                    project: false,
                    website: false,
                    store: false,
                    projectTitle: 'Magento Console',
                },
                options: {
                    debug: false,
                    pretty: false,
                    autoload: false,
                },
                drawer: null,
            }
        },
        components: {
            Brace,
            Projects,
            Options,
        },
        methods: {
            updateCodeData(code){
                this.$ls.set('code', code);
                this.code = code;
            },
            processCode(){
                axios.post('/',{
                    code: this.code,
                    project: this.projectConfig.project,
                    website: this.projectConfig.website,
                    debug: this.options.debug,
                    pretty: this.options.pretty,
                    autoload: this.options.autoload,
                })
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
