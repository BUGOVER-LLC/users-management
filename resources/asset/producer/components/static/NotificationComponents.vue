<template>
    <v-snackbar :value="notification.show" :color="colorByType()" :timeout="10000" elevation="6" class="mt-12">
        <span>{{ notification.message }}</span>

        <template #action="{ attrs }">
            <v-btn v-bind="attrs" class="float-right" text icon @click="closeSnackbar">
                <v-icon v-text="'mdi-close'" />
            </v-btn>
        </template>
    </v-snackbar>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

import { NotifyType } from '@/producer/store/models/NotifyModel';
import NotifyModule from '@/producer/store/modules/NotifyModule';
@Component({})
export default class NotificationComponents extends Vue {
    get notification() {
        return NotifyModule.notification;
    }

    colorByType() {
        switch (this.notification.type) {
            case NotifyType.error:
                return 'error';
            case NotifyType.warning:
                return 'warning';
            case NotifyType.info:
                return 'info';
            case NotifyType.message:
                return 'success';
            default: {
                throw new Error('Not implemented yet');
            }
        }
    }

    closeSnackbar() {
        NotifyModule.notify({ message: '', type: NotifyType.error, show: false });
    }
}
</script>
