<template>
    <v-flex xs12>
        <label for="debug">Enable Debugger</label>
        <input type="checkbox" name="debug" id="debug" v-model="debug">
        <input v-if="debug" v-model="debugCode">
        <label for="debug">Enable Pretty Dump</label>
        <input type="checkbox" name="prettyDump" id="prettyDump" v-model="prettyDump">
    </v-flex>
</template>

<script>

    export default {
        data(){
            return {
                debug: false,
                debugCode: 'xdebug_break();',
                prettyDump: false,
            }
        },
        methods: {
            collectConfig(){
                return {
                    debug: this.debug ? this.debugCode : '',
                    pretty: this.prettyDump,
                }
            }
        },
        watch: {
            debug(){
                this.$emit('input', this.collectConfig());
                this.$ls.set('debug', this.debug);
                this.$ls.set('debugCode', this.debugCode);
                this.$ls.set('prettyDump', this.prettyDump);
            },
            prettyDump(){
                this.$emit('input', this.collectConfig());
                this.$ls.set('prettyDump', this.prettyDump);
            }
        },
        mounted(){
            this.debug = this.$ls.get('debug') ? this.$ls.get('debug') : this.debug;
            this.debugCode = this.$ls.get('debugCode') ? this.$ls.get('debugCode') : this.debugCode;
            this.prettyDump = this.$ls.get('prettyDump') ? this.$ls.get('prettyDump') : this.prettyDump;
        },
    }
</script>
