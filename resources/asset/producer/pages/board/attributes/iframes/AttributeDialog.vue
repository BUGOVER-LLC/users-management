<template>
    <v-card class="rounded-lg">
        <v-card-title class="accent">
            <span>
                {{
                    editData
                        ? `${trans('words.edit', 1)} ${trans('app.branches', 2).toLowerCase()}`
                        : `${trans('words.create', 1)} ${trans('app.branches', 1).toLowerCase()}`
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
                    vid="attributeName"
                    rules="required"
                >
                    <span>{{ trans('users.first_name') }} *</span>
                    <v-text-field
                        v-model="attributeName"
                        :error-messages="errors"
                        outlined
                        filled
                        dense
                        :value="attributeName"
                    />
                </ValidationProvider>

                <ValidationProvider
                    v-slot="{ errors }"
                    :name="trans('words.key')"
                    vid="attributeValue"
                    rules="required"
                >
                    <span>{{ trans('words.key') }} *</span>
                    <v-text-field
                        v-model="attributeValue"
                        :error-messages="errors"
                        outlined
                        filled
                        dense
                        :value="attributeValue"
                    />
                </ValidationProvider>

                <ValidationProvider
                    v-slot="{ errors }"
                    :name="trans('words.description')"
                    vid="attributeDescription"
                    rules="max:500"
                >
                    <span>{{ trans('words.description') }}</span>
                    <v-text-field
                        v-model="attributeDescription"
                        :error-messages="errors"
                        outlined
                        filled
                        dense
                        :value="attributeDescription"
                    />
                </ValidationProvider>

                <ValidationProvider
                    v-slot="{ errors }"
                    :name="trans('app.subtypes', 1)"
                    vid="resourceId"
                    rules="numeric"
                >
                    <span>{{ trans('app.subtypes', 1) }} *</span>
                    <v-select
                        v-model="resourceId"
                        :error-messages="errors"
                        :value="resourceId"
                        outlined
                        dense
                        filled
                        :items="resources"
                        item-text="resourceName"
                        item-value="resourceId"
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
                    @click="editData ? editAttribute(validate()) : storeAttribute(validate())"
                >
                    <span>{{ editData ? trans('words.edit') : trans('words.create') }}</span>
                </v-btn>
            </v-card-actions>
        </ValidationObserver>
    </v-card>
</template>

<script lang="ts">
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import { Component, Prop, PropSync, Vue, Watch } from 'vue-property-decorator';

import { OnCreated } from '@/producer/common/contracts/OnCreated';
import { HandlerModel } from '@/producer/store/models/HandlerModel';
import { NotifyType } from '@/producer/store/models/NotifyModel';
import AttributeModule from '@/producer/store/modules/AttributeModule';
import HandlerModule from '@/producer/store/modules/HandlerModule';
import NotifyModule from '@/producer/store/modules/NotifyModule';
import ResourceModule from '@/producer/store/modules/ResourceModule';
import { trans } from '@/producer/utils/StringUtils';

@Component({
    methods: { trans },
    components: { ValidationObserver, ValidationProvider },
})
export default class AttributeDialog extends Vue implements OnCreated {
    @Prop({ required: false, default: null }) public readonly editData;
    @PropSync('value', { required: true }) public dialog: boolean;

    public ref: string = 'createAttributeForm';
    public attributeId: null | number = null;
    public attributeName: string = '';
    public attributeValue: string = '';
    public attributeDescription: string = '';
    public resourceId: null | number = null;
    public createdAt: null | string = null;

    public get resources() {
        return Object.assign([], ResourceModule.resources);
    }

    @Watch('handler')
    public watchErrorHandler(val: HandlerModel) {
        if (val) {
            this.$refs.createAttributeForm
                ? // @ts-ignore
                  this.$refs.createAttributeForm.setErrors(val)
                : // @ts-ignore
                  this.$refs.updateAttributeForm.setErrors(val);
        }
    }

    public get handler() {
        return HandlerModule.handler;
    }

    public async storeAttribute(validate: Promise<any>) {
        validate.then(async (valid: boolean) => {
            if (valid) {
                const data = {
                    attributeName: this.attributeName,
                    attributeValue: this.attributeValue,
                    attributeDescription: this.attributeDescription,
                    resourceId: this.resourceId,
                };
                const result = await AttributeModule.createAttribute(data);
                this.dialog = false;
                NotifyModule.notify({ show: true, type: NotifyType.info, message: result.message });
            }
        });
    }

    public async editAttribute(validate: Promise<any>) {
        validate.then(async (valid: boolean) => {
            if (valid) {
                const data = {
                    resourceId: this.resourceId,
                    attributeId: this.attributeId,
                    attributeName: this.attributeName,
                    attributeValue: this.attributeValue,
                    attributeDescription: this.attributeDescription,
                };
                const result = await AttributeModule.editAttribute(data);
                this.dialog = false;
                NotifyModule.notify({ show: true, type: NotifyType.info, message: result.message });
            }
        });
    }

    private initialize() {
        if (this.editData) {
            this.ref = 'updateAttributeForm';

            this.attributeId = this.editData.attributeId;
            this.attributeName = this.editData.attributeName;
            this.attributeValue = this.editData.attributeValue;
            this.attributeDescription = this.editData.attributeDescription;
            this.resourceId = this.editData.resourceId;
            this.createdAt = this.editData.createdAt;
        } else {
            this.ref = 'createAttributeForm';
        }
    }

    public async created() {
        this.initialize();
    }
}
</script>
