<template>
    <div>
        <label for="site-selector">Project</label>
        <select id="site-selector" v-model="selectedProject">
            <option v-for="(proj, idx) in projects" :value="idx">{{ proj.project_id }}</option>
        </select>
        <template v-if="isProjectSelected">
            <label for="site-selector">Website</label>
            <select v-model="selectedWebsite">
                <option v-for="website in getWebsites()" :value="website.code">{{ website.name }}</option>
            </select>
        </template>
        <template v-if="isProjectSelected">
            <label for="site-selector">Stores</label>
            <select v-model="selectedStore">
                <option v-for="store in getStores()" :value="store.code">{{ store.name }}</option>
            </select>
        </template>
    </div>
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
