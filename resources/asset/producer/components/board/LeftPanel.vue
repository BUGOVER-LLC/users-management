<template>
    <DrawerDrag :right="false">
        <template #toolbar>
            <v-toolbar color="white" flat height="70">
                <v-toolbar-title class="headline text-uppercase">
                    <router-link :to="{ name: 'produceBoard' }" class="text-decoration-none">
                        <span class="text-decoration-underline">C O U R T</span
                        ><span class="font-weight-light"> U.M.</span>
                    </router-link>
                </v-toolbar-title>
            </v-toolbar>
        </template>
        <template #content>
            <v-btn class="float-right" icon large tile light @click="toggleMiniMenu"><v-icon v-text="icon" /></v-btn>
            <MenuList class="mt-11" />
        </template>
    </DrawerDrag>
</template>

<script lang="ts">
import { Component, Vue, Watch } from 'vue-property-decorator';

import DrawerDrag from '@/producer/components/board/DrawerDrag.vue';
import MenuList from '@/producer/components/board/MenuList.vue';
import HandlerModule from '@/producer/store/modules/HandlerModule';

@Component({
    components: { MenuList, DrawerDrag },
})
export default class LeftPanel extends Vue {
    public icon: string = 'mdi-arrow-left-bold-outline';

    @Watch('mini')
    private watchMenuMini(val: boolean) {
        if (val) {
            this.icon = 'mdi-arrow-right-bold-outline';
        } else {
            this.icon = 'mdi-arrow-left-bold-outline';
        }
    }

    private get mini(): boolean {
        return HandlerModule.menuMiniVariant;
    }

    public toggleMiniMenu() {
        HandlerModule.emitMenuMini();
    }
}
</script>

<style lang="scss" scoped>
.v-navigation-drawer {
    height: 100vh;
    top: 0 !important;
    max-height: 100% !important;
    transform: translateX(0%);
    width: 56px;
}
</style>
