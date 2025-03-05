<template>
    <v-card class="rounded-lg">
        <v-card-title class="accent">
            <span>
                {{
                    editData
                        ? `${trans('words.edit', 1)} ${trans('app.subtypes', 2).toLowerCase()}`
                        : `${trans('words.create', 1)} ${trans('app.subtypes', 1).toLowerCase()}`
                }}
            </span>
            <v-spacer />
            <v-btn icon @click="dialog = false">
                <v-icon color="grey darken-3" v-text="'mdi-close'" />
            </v-btn>
        </v-card-title>
        <v-divider class="mb-6" />
        <ValidationObserver :ref="ref" v-slot="{ invalid, validate }">
            <v-card-text>
                <ValidationProvider
                    v-slot="{ errors }"
                    :name="trans('users.first_name')"
                    vid="resourceName"
                    rules="required"
                >
                    {{ trans('users.first_name') }} *
                    <v-text-field
                        v-model="resourceName"
                        :error-messages="errors"
                        outlined
                        filled
                        dense
                        :value="resourceName"
                    />
                </ValidationProvider>

                <ValidationProvider v-slot="{ errors }" :name="trans('words.key')" vid="resourceValue" rules="required">
                    {{ trans('words.key') }} *
                    <v-text-field
                        v-model="resourceValue"
                        :error-messages="errors"
                        name="resourceValue"
                        outlined
                        filled
                        dense
                        :value="resourceValue"
                    />
                </ValidationProvider>

                <ValidationProvider
                    v-slot="{ errors }"
                    :name="trans('words.description')"
                    vid="resourceDescription"
                    rules="max:500"
                >
                    {{ trans('words.description') }}
                    <v-text-field
                        v-model="resourceDescription"
                        :error-messages="errors"
                        outlined
                        filled
                        dense
                        :value="resourceDescription"
                    />
                </ValidationProvider>
            </v-card-text>
            <v-card-actions>
                <v-btn
                    block
                    outlined
                    class="mb-3"
                    text
                    color="primary"
                    :disabled="invalid"
                    @click="editData ? editResource(validate()) : storeResource(validate())"
                >
                    <span>{{ editData ? trans('words.edit') : trans('words.create') }}</span>
                </v-btn>
            </v-card-actions>
        </ValidationObserver>
    </v-card>
</template>

<script lang="ts">
import { extend, ValidationObserver, ValidationProvider } from 'vee-validate';
import { numeric, required } from 'vee-validate/dist/rules';
import { Component, Prop, PropSync, Vue, Watch } from 'vue-property-decorator';

import { OnCreated } from '@/producer/common/contracts/OnCreated';
import { HandlerModel } from '@/producer/store/models/HandlerModel';
import { NotifyType } from '@/producer/store/models/NotifyModel';
import HandlerModule from '@/producer/store/modules/HandlerModule';
import NotifyModule from '@/producer/store/modules/NotifyModule';
import ResourceModule from '@/producer/store/modules/ResourceModule';
import { trans } from '@/producer/utils/StringUtils';

extend('required', required);
extend('numeric', numeric);

@Component({
    methods: { trans },
    components: { ValidationObserver, ValidationProvider },
})
export default class ResourceDialog extends Vue implements OnCreated {
    @Prop({ required: false, default: null }) public readonly editData;
    @PropSync('value', { required: true }) public dialog: boolean;

    public ref: string = 'createResourceForm';

    public resourceId: null | number = null;
    public resourceName: string = '';
    public resourceValue: string = '';
    public resourceDescription: string = '';
    public createdAt: null | string = null;

    public get resources() {
        return Object.assign([], ResourceModule.resources);
    }

    public async storeResource(validate: Promise<boolean>) {
        validate.then(async (valid: boolean) => {
            if (valid) {
                const data = {
                    resourceName: this.resourceName,
                    resourceValue: this.resourceValue,
                    resourceDescription: this.resourceDescription,
                };
                const result = await ResourceModule.createResource(data);
                this.dialog = false;
                NotifyModule.notify({ show: true, type: NotifyType.info, message: result.message });
            }
        });
    }

    @Watch('handler')
    public watchErrorHandler(val: HandlerModel) {
        if (val) {
            this.$refs.createResourceForm
                ? // @ts-ignore
                  this.$refs.createResourceForm.setErrors(val)
                : // @ts-ignore
                  this.$refs.updateResourceForm.setErrors(val);
        }
    }

    public get handler() {
        return HandlerModule.handler;
    }

    public async editResource(validate: Promise<boolean>) {
        validate.then(async (valid: boolean) => {
            if (valid) {
                const data = {
                    resourceId: this.resourceId,
                    resourceName: this.resourceName,
                    resourceValue: this.resourceValue,
                    resourceDescription: this.resourceDescription,
                };
                const result = await ResourceModule.editResource(data);
                this.dialog = false;
                NotifyModule.notify({ show: true, type: NotifyType.info, message: result.message });
            }
        });
    }

    private initialize() {
        if (this.editData) {
            this.ref = 'updateResourceForm';

            this.resourceId = this.editData.resourceId;
            this.resourceName = this.editData.resourceName;
            this.resourceValue = this.editData.resourceValue;
            this.resourceDescription = this.editData.resourceDescription;
            this.createdAt = this.editData.createdAt;
        } else {
            this.ref = 'createResourceForm';
        }
    }

    public async created() {
        this.initialize();
    }
}
</script>
