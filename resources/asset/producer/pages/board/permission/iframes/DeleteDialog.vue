<template>
    <v-card>
        <v-card-title class="blue-grey">
            <span
                >Delete role <b class="text-decoration-underline">{{ permission.permissionName }}</b></span
            >
        </v-card-title>
        <v-divider />

        <v-card-text>
            <div class="d-flex align-center justify-center pa-8">
                <v-btn outlined @click="cancelPermission" v-text="'cancel'"><v-icon /></v-btn>
                <v-btn class="error" @click="deletePermission" v-text="'delete'">
                    <v-icon v-text="'mdi-delete'" />
                </v-btn>
            </div>
        </v-card-text>
    </v-card>
</template>

<script lang="ts">
import { Component, Emit, Prop, Vue } from 'vue-property-decorator';

import { PermissionModel } from '@/producer/store/models/PermissionModel';

@Component({})
export default class DeleteDialog extends Vue {
    @Prop({ required: true })
    public permission: PermissionModel;

    public result: boolean = false;

    public deletePermission() {
        this.result = true;
        this.triggerEmit();
    }

    public cancelPermission() {
        this.result = false;
        this.triggerEmit();
    }

    @Emit('trigger')
    public triggerEmit(): boolean {
        return this.result;
    }
}
</script>
