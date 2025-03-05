<template>
    <v-card>
        <v-card-title class="blue-grey">
            <span
                >Delete role <b class="text-decoration-underline">{{ role.roleName }}</b></span
            >
        </v-card-title>
        <v-divider />

        <v-card-text>
            <div class="d-flex align-center justify-center pa-8">
                <v-btn outlined @click="cancelRole" v-text="'cancel'"><v-icon /></v-btn>
                <v-btn class="error" @click="deleteRole" v-text="'delete'">
                    <v-icon v-text="'mdi-delete'" />
                </v-btn>
            </div>
        </v-card-text>
    </v-card>
</template>

<script lang="ts">
import { Component, Emit, Prop, Vue } from 'vue-property-decorator';

import { IRoleModel } from '@/producer/store/models/IRoleModel';

@Component({})
export default class DeleteDialog extends Vue {
    @Prop({ required: true }) public role: IRoleModel;

    public result: boolean = false;

    public deleteRole() {
        this.result = true;
        this.triggerEmit();
    }

    public cancelRole() {
        this.result = false;
        this.triggerEmit();
    }

    @Emit('trigger')
    public triggerEmit(): boolean {
        return this.result;
    }
}
</script>
