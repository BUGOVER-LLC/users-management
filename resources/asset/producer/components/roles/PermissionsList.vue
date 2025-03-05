<template>
    <div style="padding: 0; margin: 0">
        <v-container class="d-flex d-inline-block justify-center">
            <div style="max-width: 600px; min-width: 400px">
                <v-toolbar color="primary" dark>
                    <v-toolbar-title
                        v-text="trans('words.available', 2) + ' ' + trans('menu.permissions', 2).toLowerCase()"
                    />
                </v-toolbar>
                <v-list subheader>
                    <v-text-field class="mt-3" outlined dense :disabled="!availablePermissionsData.length" />
                </v-list>

                <v-list
                    subheader
                    flat
                    dense
                    outlined
                    max-width="600"
                    min-width="400"
                    width="500"
                    class="rounded-lg"
                    :height="windowHeight - 563"
                >
                    <v-checkbox
                        :disabled="!availablePermissionsData.length"
                        :label="trans('permissions.select_all')"
                        class="ml-3"
                        @change="selectAllAvailable"
                    />

                    <v-list-item-group
                        v-model="availablePermission"
                        multiple
                        class="overflow-auto"
                        :style="{ height: windowHeight - 610 + 'px' }"
                    >
                        <template v-for="permission in availablePermissionsData">
                            <v-list-item
                                v-if="permission"
                                :key="permission.permissionId"
                                dense
                                :value="permission.permissionId"
                            >
                                <template #default="{ active }">
                                    <v-list-item-action>
                                        <v-checkbox :input-value="active" color="primary" />
                                    </v-list-item-action>

                                    <v-list-item-content>
                                        <v-list-item-title>{{ permission.permissionName }}</v-list-item-title>
                                    </v-list-item-content>
                                </template>
                            </v-list-item>
                        </template>
                    </v-list-item-group>
                </v-list>
            </div>

            <div class="d-flex flex-md-column justify-center align-center mr-6 ml-6">
                <v-btn
                    class="rounded-lg mb-3"
                    color="primary"
                    depressed
                    :disabled="!availablePermission.length"
                    @click="passSelected"
                >
                    <v-icon v-text="'mdi-arrow-right'" />
                </v-btn>
                <v-btn class="rounded-lg" color="primary" :disabled="!selectedPermission.length" @click="passAvailable">
                    <v-icon v-text="'mdi-arrow-left'" />
                </v-btn>
            </div>

            <div style="max-width: 600px; min-width: 400px">
                <v-toolbar color="primary" dark>
                    <v-toolbar-title
                        v-text="trans('words.selected', 1) + ' ' + trans('menu.permissions', 2).toLowerCase()"
                    />
                </v-toolbar>
                <v-list subheader class="mt-3">
                    <v-text-field outlined dense :disabled="!selectedPermissionsData.length" />
                </v-list>
                <v-list
                    subheader
                    flat
                    dense
                    outlined
                    max-width="600"
                    min-width="400"
                    width="500"
                    class="rounded-lg"
                    :height="windowHeight - 563"
                >
                    <v-checkbox
                        :disabled="!selectedPermissionsData.length"
                        :label="trans('permissions.select_all')"
                        class="ml-3"
                        @change="selectAllSelected"
                    />

                    <v-list-item-group
                        v-model="selectedPermission"
                        multiple
                        class="overflow-auto"
                        :style="{ height: windowHeight - 610 + 'px' }"
                    >
                        <template v-for="permission in selectedPermissionsData">
                            <v-list-item
                                v-if="permission"
                                :key="permission.permissionId"
                                dense
                                :value="permission.permissionId"
                                @change="onChangeAccess(permission.access, permission.permissionId)"
                            >
                                <template #default="{ active }">
                                    <v-list-item-action>
                                        <v-checkbox :input-value="active" color="primary" />
                                    </v-list-item-action>

                                    <v-list-item-content>
                                        <v-list-item-title v-text="permission.permissionName" />
                                    </v-list-item-content>
                                    <v-menu top nudge-left="130" offset-x :close-on-content-click="false">
                                        <template #activator="{ on, attrs }">
                                            <v-btn color="grey darken-2" dark v-bind="attrs" icon text v-on="on">
                                                <v-icon v-text="'mdi-account-lock-open-outline'" />
                                            </v-btn>
                                        </template>
                                        <v-list dense rounded>
                                            <v-list-item-group
                                                v-model="permission.access"
                                                dense
                                                multiple
                                                @change="onChangeAccess(permission.access, permission.permissionId)"
                                            >
                                                <template v-for="(item, index) in accesses">
                                                    <v-list-item :key="index" :value="item" dense selectable ripple>
                                                        <template #default="{ active }">
                                                            <v-list-item-content v-text="item" />
                                                            <v-list-item-action>
                                                                <v-checkbox dense hide-details :input-value="active" />
                                                            </v-list-item-action>
                                                        </template>
                                                    </v-list-item>
                                                </template>
                                            </v-list-item-group>
                                        </v-list>
                                    </v-menu>
                                </template>
                            </v-list-item>
                        </template>
                    </v-list-item-group>
                </v-list>
            </div>
        </v-container>

        <v-divider class="mt-8" />
        <div class="d-flex align-end justify-end mt-3">
            <v-btn outlined color="primary" @click="emitSelected" v-text="buttonText" />
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Emit, Prop, Vue } from 'vue-property-decorator';

import { PermissionModel } from '@/producer/store/models/PermissionModel';
import { trans } from '@/producer/utils/StringUtils';

@Component({
    methods: { trans },
})
export default class PermissionsList extends Vue {
    @Prop({ required: true, type: Array }) public availablePermissionsData: PermissionModel[];
    @Prop({ required: true, default: () => [] }) public accesses: [];
    @Prop({ required: true, type: [String, Number] }) public windowHeight: string | number;
    @Prop({ required: false, default: () => [] }) public selectedPermissionsData: PermissionModel[];
    @Prop({ required: false, default: () => [] }) public buttonText: string;

    public availablePermission: number[] = [];
    public selectedPermission: number[] = [];

    public onChangeAccess(val, permissionId: null | number) {
        if (permissionId) {
            this.selectedPermission[permissionId] = val ?? [];
        }
    }

    public passSelected() {
        this.availablePermission.forEach(selectedPermission => {
            const permission = this.availablePermissionsData.find(item => item.permissionId === selectedPermission);
            if (permission && permission.permissionId) {
                const index = this.availablePermissionsData.findIndex(
                    (item: PermissionModel) => item.permissionId === selectedPermission,
                );
                if (~index) {
                    permission['access'] = this.accesses;
                    this.selectedPermissionsData.push(permission);
                    this.selectedPermission.push(permission.permissionId);
                    this.availablePermissionsData.splice(index, 1);
                }
            }
        });
    }

    public passAvailable() {
        this.selectedPermission.forEach(selectedPermission => {
            const permission = this.selectedPermissionsData.find(item => item.permissionId === selectedPermission);
            if (permission && permission.permissionId) {
                const index = this.selectedPermissionsData.findIndex(
                    (item: PermissionModel) => item.permissionId === selectedPermission,
                );
                if (~index) {
                    this.availablePermissionsData.push(permission);
                    this.selectedPermissionsData.splice(index, 1);
                }
            }
        });
    }

    public selectAllAvailable(event) {
        const ids = this.availablePermissionsData.map(item => item.permissionId);

        if (!ids.length) return;

        if (event) {
            ids.forEach((item: null | number) => (item ? this.availablePermission.push(item) : null));
        } else {
            this.availablePermission = [];
        }
    }

    public selectAllSelected(event: boolean) {
        const ids = this.selectedPermissionsData.map(item => item.permissionId);

        if (!ids.length) return;

        if (event) {
            ids.forEach((item: null | number) => (item ? this.selectedPermission.push(item) : null));
        } else {
            this.selectedPermission = [];
        }
    }

    @Emit('select')
    public emitSelected() {
        return this.selectedPermissionsData.map(function (item) {
            return {
                permissionId: item.permissionId,
                access: item.access,
            };
        });
    }
}
</script>
