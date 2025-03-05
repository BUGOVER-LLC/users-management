<template>
    <div class="ma-0 pa-0">
        <NotificationComponents />

        <AppBar @toggleProfile="profile = $event" />

        <LeftPanel />

        <v-main class="grey lighten-3 fill-height">
            <InetMonitor @status="hasInet = $event" />
            <v-container fluid fill-height>
                <v-slide-x-transition mode="out-in">
                    <router-view />
                </v-slide-x-transition>
            </v-container>
        </v-main>

        <ProfileInfo v-if="profile" />
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';

import { OnCreated } from '@/producer/common/contracts/OnCreated';
import AppBar from '@/producer/components/board/AppBar.vue';
import LeftPanel from '@/producer/components/board/LeftPanel.vue';
import ProfileInfo from '@/producer/components/started/ProfileInfo.vue';
import InetMonitor from '@/producer/components/static/InetMonitor.vue';
import NotificationComponents from '@/producer/components/static/NotificationComponents.vue';
import { IProfileModel } from '@/producer/store/models/IProfileModel';
import ProfileModule from '@/producer/store/modules/ProfileModule';

@Component({
    components: { InetMonitor, ProfileInfo, NotificationComponents, AppBar, LeftPanel },
})
export default class Index extends Vue implements OnCreated {
    @Prop({ required: false }) private producer: null | IProfileModel;

    public profile: boolean = false;
    public hasInet: boolean = false;

    public async created() {
        if (this.producer) {
            ProfileModule.addProfileData(this.producer);
        } else {
            await ProfileModule.emitProfileData();
        }
    }
}
</script>
