<template>
    <v-card class="rounded-lg">
        <v-card-title class="blue-grey">
            <span>{{ trans('permissions.new', 1) }}</span>
            <v-spacer />
            <v-btn icon @click="closeDialog">
                <v-icon color="grey darken-3" v-text="'mdi-close'" />
            </v-btn>
        </v-card-title>

        <v-divider class="mb-5" />

        <ValidationObserver ref="permissionForm" v-slot="{ invalid, validate }">
            <v-card-text>
                <v-form autocomplete="off">
                    <ValidationProvider
                        v-slot="{ errors }"
                        rules="required|max:150|min:3"
                        :name="trans('users.first_name')"
                        vid="permissionName"
                    >
                        <v-text-field
                            v-model="permissionData.permissionName"
                            outlined
                            filled
                            color="black"
                            :label="trans('users.first_name')"
                            :error-messages="errors"
                        />
                    </ValidationProvider>
                    <ValidationProvider
                        v-slot="{ errors }"
                        rules="required|max:50|min:3"
                        :name="trans('words.key')"
                        vid="permissionName"
                    >
                        <v-text-field
                            v-model="permissionData.permissionValue"
                            outlined
                            color="black"
                            filled
                            :label="trans('words.key')"
                            :error-messages="errors"
                        />
                    </ValidationProvider>
                    <ValidationProvider
                        v-slot="{ errors }"
                        rules="max:500|min:1"
                        :name="trans('words.description')"
                        vid="permissionDescription"
                    >
                        <v-textarea
                            v-model="permissionData.permissionDescription"
                            outlined
                            color="black"
                            filled
                            rows="3"
                            :label="trans('words.description')"
                            :error-messages="errors"
                        />
                        <span class="error">{{ errors[0] }}</span>
                    </ValidationProvider>

                    <v-spacer />
                    <v-switch v-model="permissionData.permissionActive" dense light :label="trans('words.active')" />
                </v-form>
            </v-card-text>
            <v-divider />
            <v-card-actions>
                <v-btn
                    :disabled="invalid"
                    block
                    depressed
                    text
                    @click="permissionData.permissionId ? updatePermission(validate()) : createPermission(validate())"
                    v-text="permissionData.permissionId ? trans('words.edit') : trans('words.create')"
                />
            </v-card-actions>
        </ValidationObserver>
    </v-card>
</template>

<script lang="ts">
import { extend, ValidationObserver, ValidationProvider } from 'vee-validate';
import { max, min, required } from 'vee-validate/dist/rules';
import { Component, Emit, PropSync, Vue, Watch } from 'vue-property-decorator';

import { HandlerModel } from '@/producer/store/models/HandlerModel';
import { NotifyType } from '@/producer/store/models/NotifyModel';
import { PermissionModel } from '@/producer/store/models/PermissionModel';
import HandlerModule from '@/producer/store/modules/HandlerModule';
import NotifyModule from '@/producer/store/modules/NotifyModule';
import PermissionModule from '@/producer/store/modules/PermissionModule';
import { trans } from '@/producer/utils/StringUtils';

extend('required', required);
extend('min', min);
extend('max', max);

@Component({
    methods: { trans },
    components: { ValidationObserver, ValidationProvider },
})
export default class FormPermissionDialog extends Vue {
    @PropSync('permissionDataProp', { required: false }) public permissionData!: PermissionModel;

    public async createPermission(validate: Promise<boolean>) {
        validate.then(async (valid: boolean) => {
            if (valid) {
                const result = await PermissionModule.createPermission(this.permissionData);
                NotifyModule.notify({ message: result.message, type: NotifyType.message, show: true });
                this.closeDialog();
            }
        });
    }

    public async updatePermission(validate: Promise<boolean>) {
        validate.then(async (valid: boolean) => {
            if (valid) {
                const result = await PermissionModule.updatePermission(this.permissionData);
                NotifyModule.notify({ message: result.message, type: NotifyType.message, show: true });
                this.closeDialog();
            }
        });
    }

    @Watch('handler')
    public watchErrorHandler(val: HandlerModel) {
        if (val) {
            // @ts-ignore
            this.$refs.permissionForm.setErrors(val);
        }
    }

    public get handler() {
        return HandlerModule.handler;
    }

    @Emit('closeDialog')
    public closeDialog() {
        return true;
    }
}
</script>
