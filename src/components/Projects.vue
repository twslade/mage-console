<template>
    <div>
        <label for="site-selector">Project</label>
        <select id="site-selector" v-model="selectedProject">
            <option v-for="(proj, idx) in projects" :value="idx">{{ proj.title }}</option>
        </select>
        <template v-if="selectedProject >= 0">
            <label for="site-selector">Website</label>
            <select v-model="selectedWebsite">
                <option v-for="website in getWebsites()" :value="website.code">{{ website.name }}</option>
            </select>
        </template>
        <template v-if="selectedProject >= 0">
            <label for="site-selector">Stores</label>
            <select v-model="selectedStore">
                <option v-for="store in getStores()" :value="store.code">{{ store.name }}</option>
            </select>
        </template>
    </div>
</template>

<script>
    export default {
        props: ['projects'],
        data(){
            return {
                selectedProject: -1,
                selectedWebsite: false,
                selectedStore: false,
            }
        },
        methods: {
            getWebsites(){
                return this.projects[this.selectedProject].websites;
            },
            getStores(){
                return this.projects[this.selectedProject].stores;
            },
            collectConfig(){
                return {
                    project : this.projects[this.selectedProject].file,
                    website: this.selectedWebsite,
                    store: this.selectedStore,
                }
            },
            emitConfig(){
                this.$emit('input', this.collectConfig());
            },
            getFromStorage(type){
                return (this.$ls.get(type)) ? this.$ls.get(type) : false;
            }
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
            selectedProject(){
                this.$ls.set('project', this.selectedProject);
                this.emitConfig();
            },
        },
        mounted(){
            this.selectedStore = (this.getFromStorage('store')) ? this.getFromStorage('storage') : false;
            this.selectedWebsite = (this.getFromStorage('website')) ? this.getFromStorage('website') : false;
            this.selectedProject = (this.getFromStorage('project')) ? this.getFromStorage('project') : false;
        }
    }
</script>
