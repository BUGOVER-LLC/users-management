<template>
    <div class="auth-wrapper d-flex align-center justify-center pa-4 row">
        <VCard
            class="auth-card pa-4 pt-7 rounded-lg mb-16"
            max-width="650"
            min-width="450"
            outlined
            max-height="500"
            elevation="4"
        >
            <v-toolbar color="darkblue" dark>
                <v-toolbar-title>{{ toolbarName }}</v-toolbar-title>

                <v-spacer />

                <v-btn
                    v-if="2 === systemSetType && systems.length"
                    icon
                    elevation="4"
                    color="blue"
                    @click="systemSetType = 1 === systemSetType ? 2 : 1"
                >
                    <v-icon v-text="'mdi-format-list-bulleted'" />
                </v-btn>
            </v-toolbar>

            <v-window v-model="systemSetType">
                <v-window-item v-if="systems.length" :value="1">
                    <v-list two-line class="mb-5 overflow-auto" style="height: 310px">
                        <v-list-item-group v-model="selected" active-class="blue--text">
                            <template v-for="item in systems">
                                <v-list-item :key="item.systemId">
                                    <template #default="{ active }">
                                        <v-list-item-content>
                                            <v-list-item-title v-text="item.systemName" />

                                            <v-list-item-subtitle v-text="item.systemDomain" />
                                        </v-list-item-content>

                                        <v-list-item-action>
                                            <v-icon v-if="!active" color="grey lighten-1">mdi-check-outline</v-icon>

                                            <v-icon v-else color="yellow darken-3">mdi-check</v-icon>
                                        </v-list-item-action>
                                    </template>
                                </v-list-item>
                            </template>
                        </v-list-item-group>
                    </v-list>
                    <v-btn
                        :disabled="disabledSelectedEnv"
                        block
                        color="blue"
                        class="mb-3"
                        outlined
                        @click="sendSelectedSystem()"
                        v-text="trans('auth.go')"
                    />
                    <span
                        class="text-decoration-underline black--text pointer"
                        @click="systemSetType = 1 === systemSetType ? 2 : 1"
                        v-text="trans('auth.or_create_new')"
                    />
                </v-window-item>

                <v-window-item :value="2">
                    <div class="mt-6">
                        <ValidationObserver ref="createNewSystemForm" v-slot="{ invalid, validate }">
                            <v-form autocomplete="off">
                                <ValidationProvider
                                    v-slot="{ errors, valid }"
                                    rules="required|max:50|min:3"
                                    name="systemName"
                                >
                                    <v-text-field
                                        v-model="system.systemName"
                                        name="systemName"
                                        outlined
                                        dense
                                        filled
                                        :label="trans('users.first_name')"
                                        :error-messages="errors"
                                    />
                                </ValidationProvider>

                                <ValidationProvider
                                    v-slot="{ errors, valid }"
                                    rules="required|max:250|min:3"
                                    name="systemDomain"
                                >
                                    <v-text-field
                                        v-model="system.systemDomain"
                                        name="systemDomain"
                                        outlined
                                        dense
                                        filled
                                        :label="trans('words.domain', 1)"
                                        placeholder="example.com"
                                        :error-messages="errors"
                                    />
                                </ValidationProvider>

                                <ValidationProvider v-slot="{ errors }" name="systemLogo" rules="mimes:image/*">
                                    <v-file-input
                                        v-model="systemLogo"
                                        dense
                                        name="systemLogo"
                                        outlined
                                        filled
                                        :label="trans('words.logo')"
                                        :error-messages="errors"
                                        @change="validate"
                                    />
                                </ValidationProvider>

                                <v-btn
                                    :disabled="invalid"
                                    block
                                    color="blue"
                                    class="mt-5"
                                    outlined
                                    @click="sendCreatedSystem(validate())"
                                    v-text="trans('words.create')"
                                />
                            </v-form>
                        </ValidationObserver>
                    </div>
                </v-window-item>
            </v-window>
        </VCard>
    </div>
</template>

<script lang="ts">
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import { Component, Prop, Vue, Watch } from 'vue-property-decorator';

import { OnCreated } from '@/producer/common/contracts/OnCreated';
import routesNames from '@/producer/router/routesNames';
import { StoreEnvironment } from '@/producer/services/api/SystemApi';
import { ISystemModel, SystemInstance } from '@/producer/store/models/SystemModel';
import { trans } from '@/producer/utils/StringUtils';

@Component({
    methods: { trans },
    components: { ValidationProvider, ValidationObserver },
})
export default class SelectSystem extends Vue implements OnCreated {
    @Prop({ required: true }) public systems: ISystemModel[];

    public disabledSelectedEnv: boolean = true;
    public selected = null;
    public systemSetType: number = 1;
    public system: SystemInstance = new SystemInstance();

    @Watch('selected')
    public observeSelectedEnv(val: number) {
        this.disabledSelectedEnv = undefined === val;
    }

    public get toolbarName() {
        if (1 === this.systemSetType) {
            return this.$t('auth.select_system');
        }

        return this.$t('auth.create_system');
    }

    public async sendCreatedSystem(validate: Promise<any>) {
        validate.then(async (result: boolean) => {
            if (result) {
                await StoreEnvironment(this.system);
                await this.$router.push({ name: routesNames.produceBoard });
            }
        });
    }

    public async sendSelectedSystem() {
        if (this.selected || 0 === this.selected) {
            await StoreEnvironment(this.systems[this.selected]);
            await this.$router.push({ name: routesNames.produceBoard });
        }
    }

    public created() {
        if (!this.systems.length) {
            this.systemSetType = 2;
        }
    }
}
</script>
