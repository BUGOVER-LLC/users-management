<template>
    <v-container fluid>
        <v-card class="rounded-lg">
            <v-card-title class="accent">
                <span>{{ trans('roles.new', 1) }}</span>
                <v-spacer />
            </v-card-title>
            <v-divider class="mb-7" />
            <v-card-text :style="{ height: window.height - 90 + 'px' }" class="overflow-auto">
                <ValidationObserver ref="createRoleForm" v-slot="{ validate }">
                    <v-row>
                        <v-col cols="4">
                            <ValidationProvider
                                v-slot="{ errors }"
                                rules="required|max:100|min:3"
                                :name="`${trans('roles.role_name', 1)} *`"
                                vid="roleKey"
                            >
                                <v-text-field
                                    v-model="roleModel.roleName"
                                    outlined
                                    class="rounded-md"
                                    :label="`${trans('roles.role_name', 1)} *`"
                                    filled
                                    :error-messages="errors"
                                />
                            </ValidationProvider>
                        </v-col>
                        <v-col cols="4">
                            <ValidationProvider
                                v-slot="{ errors }"
                                rules="required|max:100|min:3"
                                :name="`${trans('roles.role_value', 1)} *`"
                                vid="roleName"
                            >
                                <v-text-field
                                    v-model="roleModel.roleValue"
                                    filled
                                    outlined
                                    class="rounded-md"
                                    :label="`${trans('roles.role_value', 1)} *`"
                                    :error-messages="errors"
                                />
                            </ValidationProvider>
                        </v-col>
                        <v-col cols="4">
                            <ValidationProvider
                                v-slot="{ errors }"
                                rules="max:300|min:3"
                                :name="trans('roles.role_description', 1)"
                                vid="roleDescription"
                            >
                                <v-textarea
                                    v-model="roleModel.roleDescription"
                                    filled
                                    rows="1"
                                    outlined
                                    class="rounded-md"
                                    :label="trans('roles.role_description', 1)"
                                    :error-messages="errors"
                                />
                            </ValidationProvider>
                            <div class="d-flex column float-right mr-1">
                                <v-radio-group v-model="roleModel.hasSubordinates" class="mr-5">
                                    <v-radio :label="trans('roles.belongs.building', 2)" :value="false" />
                                    <v-radio :label="trans('roles.belongs.judge', 2)" :value="true" />
                                </v-radio-group>
                                <v-switch v-model="roleModel.roleActive" dense :label="trans('words.active')" />
                            </div>
                        </v-col>
                    </v-row>
                    <v-divider />

                    <PermissionsList
                        v-if="permissions"
                        :available-permissions-data="permissions"
                        :window-height="window.height"
                        :accesses="accesses"
                        :button-text="trans('words.create')"
                        @select="createRole($event, validate())"
                    />
                </ValidationObserver>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script lang="ts">
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import { Component, Vue } from 'vue-property-decorator';

import { OnCreated } from '@/producer/common/contracts/OnCreated';
import PermissionsList from '@/producer/components/roles/PermissionsList.vue';
import routesNames from '@/producer/router/routesNames';
import { RoleModel } from '@/producer/store/models/IRoleModel';
import { NotifyType } from '@/producer/store/models/NotifyModel';
import NotifyModule from '@/producer/store/modules/NotifyModule';
import PermissionModule from '@/producer/store/modules/PermissionModule';
import RoleModule from '@/producer/store/modules/RoleModule';
import { trans } from '@/producer/utils/StringUtils';

@Component({
    methods: { trans },
    components: { PermissionsList, ValidationProvider, ValidationObserver },
})
export default class CreateRole extends Vue implements OnCreated {
    public window = { width: 0, height: 0, heightDif: 120 };
    public roleModel: RoleModel = new RoleModel();
    public accesses: null | [] = null;

    public get permissions() {
        return PermissionModule.permissionPayload;
    }

    handleResize() {
        this.window.width = window.innerWidth;
        this.window.height = window.innerHeight - this.window.heightDif;
        window.addEventListener('resize', this.handleResize);
    }

    public async created() {
        this.handleResize();
        const res = await PermissionModule.freePermissions();
        this.accesses = res.accessor;
    }

    public async createRole(permissions: number[], validate: Promise<any>) {
        validate.then(async (result: boolean) => {
            if (result) {
                this.roleModel.assignedPermissions = permissions;
                const res = await RoleModule.createRole(this.roleModel);
                NotifyModule.notify({ show: true, message: res.message, type: NotifyType.info });
                await this.$router.push({ name: routesNames.boardRole });
            }
        });
    }
}
</script>
