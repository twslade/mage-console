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
            }
        }
    }
</script>
