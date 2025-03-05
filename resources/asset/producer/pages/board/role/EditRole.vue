<template>
    <v-container fluid>
        <v-card class="rounded-lg">
            <v-card-title class="accent">
                <span>{{ trans('words.edit') }} {{ trans('menu.roles', 1) }}</span>
                <v-spacer />
            </v-card-title>
            <v-divider class="mb-7" />
            <v-card-text :style="{ height: window.height - 90 + 'px' }" class="overflow-auto">
                <ValidationObserver ref="updateRoleForm" v-slot="{ validate }">
                    <v-row v-if="role">
                        <v-col cols="4">
                            <ValidationProvider
                                v-slot="{ errors }"
                                rules="required|max:100|min:3"
                                :name="`${trans('roles.role_name', 1)} *`"
                                vid="roleName"
                            >
                                <v-text-field
                                    v-model="role.roleName"
                                    filled
                                    outlined
                                    class="rounded-md"
                                    :label="`${trans('roles.role_name', 1)} *`"
                                    :error-messages="errors"
                                />
                            </ValidationProvider>
                        </v-col>
                        <v-col cols="4">
                            <ValidationProvider
                                v-slot="{ errors }"
                                rules="required|max:100|min:3"
                                vid="roleName"
                                :name="`${trans('roles.role_value', 1)} *`"
                            >
                                <v-text-field
                                    v-model="role.roleValue"
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
                                vid="roleName"
                            >
                                <v-textarea
                                    v-model="role.roleDescription"
                                    filled
                                    rows="1"
                                    outlined
                                    class="rounded-md"
                                    :label="trans('roles.role_description', 1)"
                                    :error-messages="errors"
                                />
                            </ValidationProvider>
                            <div class="d-flex column float-right mr-1">
                                <v-radio-group v-model="role.hasSubordinates" class="mr-5">
                                    <v-radio :label="trans('roles.belongs.building', 2)" :value="true" />
                                    <v-radio :label="trans('roles.belongs.judge', 2)" :value="false" />
                                </v-radio-group>
                                <v-switch v-model="role.roleActive" dense :label="trans('words.active')" />
                            </div>
                        </v-col>
                    </v-row>
                    <v-row v-else>
                        <v-col cols="4">
                            <v-skeleton-loader type="list-item" />
                        </v-col>
                        <v-col cols="4">
                            <v-skeleton-loader type="list-item" />
                        </v-col>
                        <v-col cols="4">
                            <v-skeleton-loader type="list-item" />
                        </v-col>
                    </v-row>

                    <v-divider />
                    <PermissionsList
                        :available-permissions-data="availablePermissions"
                        :selected-permissions-data="selectedPermissions"
                        :accesses="accesses"
                        :window-height="window.height"
                        :button-text="trans('words.edit')"
                        @select="updateRole($event, validate())"
                    />
                </ValidationObserver>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script lang="ts">
import _ from 'lodash';
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import { Component, Vue } from 'vue-property-decorator';

import { OnCreated } from '@/producer/common/contracts/OnCreated';
import PermissionsList from '@/producer/components/roles/PermissionsList.vue';
import routesNames from '@/producer/router/routesNames';
import { RoleInfoById } from '@/producer/services/api/RoleApi';
import { IRoleModel } from '@/producer/store/models/IRoleModel';
import { NotifyType } from '@/producer/store/models/NotifyModel';
import NotifyModule from '@/producer/store/modules/NotifyModule';
import RoleModule from '@/producer/store/modules/RoleModule';

import { trans } from '../../../utils/StringUtils';

@Component({
    methods: { trans },
    components: { PermissionsList, ValidationProvider, ValidationObserver },
})
export default class EditRole extends Vue implements OnCreated {
    public selectedPermissions: [] = [];
    public availablePermissions: [] = [];
    public role: null | IRoleModel = null;
    public accesses: null | [] = null;
    public window = { width: 0, height: 0, heightDif: 120 };

    public async created() {
        this.handleResize();

        if (this.$route.params.roleId) {
            const result = await RoleInfoById(Number(this.$route.params.roleId));
            this.role = result._payload.role;
            this.accesses = result._payload.accessors;
            this.availablePermissions = result._payload.availablePermissions;
            this.selectedPermissions = result._payload.selectedPermissions;

            _.differenceBy(this.selectedPermissions, this.availablePermissions, []);
        }
    }

    handleResize() {
        this.window.width = window.innerWidth;
        this.window.height = window.innerHeight - this.window.heightDif;
        window.addEventListener('resize', this.handleResize);
    }

    public async updateRole(permissions: number[], validate: Promise<boolean>) {
        validate.then(async () => {
            if (this.role) {
                this.role.assignedPermissions = permissions;
                const res = await RoleModule.updateRole(this.role);
                NotifyModule.notify({ show: true, message: res.message, type: NotifyType.info });
                await this.$router.push({ name: routesNames.boardRole });
            }
        });
    }
}
</script>
