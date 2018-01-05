<template>
    <v-container grid-list-md>
        <v-layout>
            <v-flex xs6>
                <v-card color="secondary" flat>
                    <v-card-text>
                        <v-switch v-model="debug" label="Enable Debugger" dark></v-switch>
                        <v-text-field v-if="debug" v-model="debugCode" label="Debug code"></v-text-field>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex xs6>
                <v-card color="secondary" flat>
                    <v-card-text>
                        <v-switch v-model="prettyDump" label="Enable Pretty Dump" dark></v-switch>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
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
