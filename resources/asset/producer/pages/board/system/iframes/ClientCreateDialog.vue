<template>
    <v-card class="rounded-lg">
        <v-card-title class="accent">
            <span>Create Client</span>
            <v-spacer />
            <v-btn icon @click="toggle = false">
                <v-icon color="grey darken-3" v-text="'mdi-close'" />
            </v-btn>
        </v-card-title>
        <v-divider />
        <ValidationObserver ref="createClientForm" v-slot="{ invalid, validate }">
            <v-card-text class="mt-5">
                <v-form autocomplete="off">
                    <ValidationProvider v-slot="{ errors }" name="systemName" rules="required|max:100">
                        <span>Name *</span>
                        <v-text-field
                            v-model="name"
                            outlined
                            dense
                            rounded
                            placeholder="Social marketing"
                            filled
                            :error-messages="errors"
                        />
                    </ValidationProvider>
                    <ValidationProvider v-slot="{ errors }" name="systemDomain" rules="max:200">
                        <span>Domain</span>
                        <v-text-field
                            v-model="domain"
                            outlined
                            dense
                            rounded
                            placeholder="https://example.com"
                            filled
                            :error-messages="errors"
                        />
                    </ValidationProvider>
                    <ValidationProvider v-slot="{ errors }" name="systemProvider" rules="">
                        <span>Provider</span>
                        <v-select
                            v-model="provider"
                            dense
                            outlined
                            rounded
                            filled
                            :items="providers"
                            item-value="value"
                            item-text="name"
                            clearable
                            :error-messages="errors"
                        />
                    </ValidationProvider>
                    <ValidationProvider v-slot="{ errors }" name="systemType" rules="required|max:100">
                        <span>Type *</span>
                        <v-select
                            v-model="type"
                            dense
                            outlined
                            rounded
                            filled
                            :items="clients"
                            item-value="value"
                            item-text="name"
                            :error-messages="errors"
                        />
                    </ValidationProvider>
                </v-form>
            </v-card-text>

            <v-card-actions>
                <v-btn
                    block
                    outlined
                    text
                    depressed
                    :loading="loadingCreate"
                    class="mb-3"
                    :disabled="invalid"
                    @click="createClient(validate())"
                >
                    <span>create</span>
                    <v-icon right v-text="'mdi-send'" />
                </v-btn>
            </v-card-actions>
        </ValidationObserver>
    </v-card>
</template>

<script lang="ts">
import request from 'axios';
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import { Component, PropSync, Vue, Watch } from 'vue-property-decorator';

import { HandlerModel } from '@/producer/store/models/HandlerModel';
import { NotifyType } from '@/producer/store/models/NotifyModel';
import HandlerModule from '@/producer/store/modules/HandlerModule';
import NotifyModule from '@/producer/store/modules/NotifyModule';
import SystemModule from '@/producer/store/modules/SystemModule';

@Component({
    components: { ClientCreateDialog, ValidationProvider, ValidationObserver },
})
export default class ClientCreateDialog extends Vue {
    @PropSync('value', { required: true }) public toggle: boolean;

    public name: string = '';
    public domain: string = '';
    public provider: string = '';
    public type: string = '';

    public loadingCreate: boolean = false;

    public get providers() {
        return SystemModule.providerTypes;
    }

    public get clients() {
        return SystemModule.clientTypes;
    }

    public get handler() {
        return HandlerModule.handler;
    }

    @Watch('handler')
    public watchErrorHandler(val: HandlerModel) {
        if (val) {
            // @ts-ignore
            this.$refs.createClientForm.setErrors(val);
        }
    }

    public async createClient(validate: Promise<boolean>) {
        this.loadingCreate = true;
        try {
            validate.then(async (valid: boolean) => {
                if (valid) {
                    const result = await SystemModule.createClient({
                        name: this.name,
                        domain: this.domain,
                        provider: this.provider,
                        type: this.type,
                    });
                    this.toggle = false;
                    NotifyModule.notify({ show: true, type: NotifyType.info, message: result.message });
                }
            });
        } catch (error) {
            if (request.isAxiosError(error)) {
                NotifyModule.notify({ message: error.message, type: NotifyType.error, show: true });
            }
        } finally {
            this.loadingCreate = false;
        }
    }
}
</script>
