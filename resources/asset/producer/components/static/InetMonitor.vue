<template>
    <div v-if="!onLine" id="monitor-component-content">
        <v-system-bar dark color="red darken-1">
            <div class="text-center text-h6 w-100 text--darken-1">
                Connection failed. Please check your internet connection !
            </div>
        </v-system-bar>
    </div>
</template>

<script lang="ts">
import { Component, Emit, Vue, Watch } from 'vue-property-decorator';

import { OnBeforeDestroyed } from '@/producer/common/contracts/OnBeforeDestroyed';
import { OnCreated } from '@/producer/common/contracts/OnCreated';
import { OnMounted } from '@/producer/common/contracts/OnMounted';

@Component({
    name: 'InetMonitor',
})
export default class InetMonitor extends Vue implements OnMounted, OnBeforeDestroyed, OnCreated {
    public onLine: boolean = navigator.onLine;
    protected showBackOnline: boolean = false;

    @Watch('onLine')
    public onLineWatcher(hasInet: boolean) {
        if (hasInet) {
            this.showBackOnline = true;
            setTimeout(() => {
                this.showBackOnline = false;
            }, 1000);
        }
    }

    private updateOnlineStatus(e) {
        const { type } = e;
        this.onLine = 'online' === type;
        this.statusEmitter();
    }

    @Emit('status')
    public statusEmitter() {
        return this.onLine;
    }

    public mounted() {
        window.addEventListener('online', this.updateOnlineStatus);
        window.addEventListener('offline', this.updateOnlineStatus);
    }

    public beforeDestroy() {
        window.removeEventListener('online', this.updateOnlineStatus);
        window.removeEventListener('offline', this.updateOnlineStatus);
    }

    public created() {
        this.statusEmitter();
    }
}
</script>

<style lang="scss">
#monitor-component-content {
    z-index: 9999;
}
</style>
