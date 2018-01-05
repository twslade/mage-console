<template>
    <v-container grid-list-md>
        <v-layout>
            <v-flex xs4>
                <v-card dark>
                    <v-card-text>
                        <v-select
                                v-bind:items="projectsForSelect"
                                v-model="selectedProject"
                                :label="projectName"
                                dark
                        ></v-select>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex xs4>
                <v-card dark>
                    <v-card-text>
                        <v-select
                                v-if="isProjectSelected"
                                v-bind:items="getWebsites()"
                                v-model="selectedWebsite"
                                label="Website"
                                dark
                                item-value="code"
                                item-text="name"
                        ></v-select>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex xs4>
                <v-card dark>
                    <v-card-text>
                        <v-select
                                v-if="isProjectSelected"
                                v-bind:items="getStores()"
                                v-model="selectedStore"
                                label="Stores"
                                dark
                                item-value="code"
                                item-text="name"
                        ></v-select>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>

    import axios from 'axios';
    import _ from 'lodash';

    export default {
        props: ['projects'],
        data(){
            return {
                selectedProject: false,
                selectedWebsite: false,
                selectedStore: false,
                selectedProjectData: {},
                rememberedProjectData: {},
            }
        },
        computed: {
            isProjectSelected(){
                return this.selectedProject !== false && !_.isEmpty(this.selectedProjectData);
            },
            projectName(){
                return (this.projects[this.selectedProject]) ? this.projects[this.selectedProject].project_id : 'Select a Project';
            },
            projectsForSelect(){
                return _.map(this.projects, function(projData, idx){
                    return {text: projData.project_id, value: idx};
                });
            }
        },
        methods: {
            getWebsites(){
                return this.isProjectSelected ? this.selectedProjectData.websites : [];
            },
            getStores(){
                return this.isProjectSelected ? this.selectedProjectData.stores : [];
            },
            collectConfig(){
                return {
                    project : (this.projects[this.selectedProject]) ? this.projects[this.selectedProject].path : null,
                    website: this.selectedWebsite,
                    store: this.selectedStore,
                    projectTitle : (this.projects[this.selectedProject]) ? this.projects[this.selectedProject].project_id : 'Magento Console',
                }
            },
            emitConfig(){
                this.$emit('input', this.collectConfig());
            },
            getFromStorage(type){
                return (this.$ls.get(type)) ? this.$ls.get(type) : false;
            },
            rememberProjectData(projectData){
                this.rememberedProjectData[this.selectedProject] = projectData;
            },
            getProjectData: function(){
                if(this.selectedProject in this.rememberedProjectData){
                    this.selectedProjectData = this.rememberedProjectData[this.selectedProject];
                } else {
                    axios.post('/', {
                        proj: this.projects[this.selectedProject].path,
                    }).then(res => {
                        let projectData = res.data;
                        this.selectedProjectData = projectData;
                        this.rememberProjectData(projectData)
                    });
                }
            },
        },
        watch: {
            selectedStore(){
                this.$ls.set('store', this.selectedStore);
                this.emitConfig();
            },
            selectedWebsite(){
                this.$ls.set('website', this.selectedWebsite);
                this.emitConfig();
            },
            selectedProject: function(){
                if(this.selectedProject !== false){
                    this.getProjectData();
                    this.$ls.set('project', this.selectedProject);
                    this.emitConfig();
                }
            },
        },
        mounted(){
            this.selectedStore = (this.getFromStorage('store')) ? this.getFromStorage('store') : false;
            this.selectedWebsite = (this.getFromStorage('website')) ? this.getFromStorage('website') : false;
            this.selectedProject = (this.getFromStorage('project')) ? this.getFromStorage('project') : false;
        }
    }
</script>
