<template>
    <v-app-bar color="white" app class="ml-0 pl-0" clipped-right flat height="70" light>
        <v-spacer />

        <v-divider class="mr-5 ml-3" vertical />

        <v-btn icon @click="profileToggle">
            <v-icon
                large
                :color="profile ? 'grey darken-3' : ''"
                v-text="profile ? 'mdi-account-arrow-right' : 'mdi-account-arrow-left-outline'"
            />
        </v-btn>
    </v-app-bar>
</template>

<script lang="ts">
import { Component, Emit, Vue } from 'vue-property-decorator';

@Component({})
export default class AppBar extends Vue {
    public profile: boolean = false;

    public profileToggle(): void {
        this.profile ? (this.profile = false) : (this.profile = true);
        this.profileDrawEmitter();
    }

    @Emit('toggleProfile')
    public profileDrawEmitter(): boolean {
        return this.profile;
    }
}
</script>

<style>
.v-toolbar__content {
    border-bottom: solid rgb(53, 92, 140) 2px !important;
}
</style>
