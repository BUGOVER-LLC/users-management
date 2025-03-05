<template>
    <v-card class="rounded-lg" :loading="loader">
        <v-form>
            <v-card-title class="accent darken-1 rounded-t-lg">
                <span
                    ><b>{{ trans('words.invitations', 2) }}</b
                    >: {{ user.profile.firstName }} {{ user.profile.lastName }}</span
                >
                <v-spacer />
                <v-btn icon @click="closeDialog">
                    <v-icon color="grey darken-3" v-text="'mdi-close'" />
                </v-btn>
            </v-card-title>

            <v-card-text :style="{ height: window.height + 'px' }" class="overflow-auto">
                <v-timeline v-for="invitation in invitations" :key="invitation.userInvitationId">
                    <v-timeline-item color="red" left icon="mdi-close">
                        <span>{{ trans('words.sent') }}:</span>
                        {{ invitation.passed | formatData }}
                    </v-timeline-item>
                    <v-timeline-item
                        :color="invitation.acceptedAt ? 'green' : 'red'"
                        right
                        :icon="invitation.acceptedAt ? 'mdi-check' : 'mdi-close'"
                    >
                        <span>{{ trans('words.accepted') }}:</span>
                        {{ invitation.acceptedAt | formatData }}
                    </v-timeline-item>
                </v-timeline>
                <v-divider />
            </v-card-text>
        </v-form>
    </v-card>
</template>

<script lang="ts">
import _ from 'lodash';
import moment from 'moment';
import { Component, Emit, Prop, Vue } from 'vue-property-decorator';

import { OnCreated } from '@/producer/common/contracts/OnCreated';
import { UserEditResponse, UserInvitationsModel } from '@/producer/store/models/UserModel';
import UserModule from '@/producer/store/modules/UserModule';
import { trans } from '@/producer/utils/StringUtils';

@Component({
    methods: { trans },
    filters: {
        formatData(value) {
            return value ? moment(String(value)).format('HH:mm:ss MM/DD/YYYY') : '';
        },
    },
})
export default class InvitationsDialog extends Vue implements OnCreated {
    @Prop({ required: true }) protected readonly user: UserEditResponse;

    public window = {
        width: 0,
        height: 0,
        heightDif: 800,
    };

    @Emit('closeDialog')
    public closeDialog() {
        return false;
    }

    handleResize() {
        this.window.width = window.innerWidth;
        this.window.height = window.innerHeight - this.window.heightDif;
        window.addEventListener('resize', this.handleResize);
    }

    public get invitations(): Record<string, UserInvitationsModel> {
        const invitations = UserModule.usersInvitations;

        return _.filter(invitations, { userId: this.user.user.userId });
    }

    public get loader() {
        return UserModule.loader;
    }

    public async created() {
        this.handleResize();
        await UserModule.getAllInvitations(this.user.user.userId);
    }
}
</script>
