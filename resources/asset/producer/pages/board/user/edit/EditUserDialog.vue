<template lang="html" src="./EdiUserDialog.html" />

<script lang="ts">
import _ from 'lodash';
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import { Component, Prop, PropSync, Vue, Watch } from 'vue-property-decorator';

import { OnCreated } from '@/producer/common/contracts/OnCreated';
import { UserEditModel, UserEditModelInstance } from '@/producer/services/api/Model/UserEditModel';
import { PersonType } from '@/producer/services/enums/PersonType';
import { AttributeModel } from '@/producer/store/models/AttributeModel';
import { IRoleModel } from '@/producer/store/models/IRoleModel';
import { NotifyType } from '@/producer/store/models/NotifyModel';
import { UserModel } from '@/producer/store/models/UserModel';
import AttributeModule from '@/producer/store/modules/AttributeModule';
import NotifyModule from '@/producer/store/modules/NotifyModule';
import RoleModule from '@/producer/store/modules/RoleModule';
import UserModule from '@/producer/store/modules/UserModule';
import { trans } from '@/producer/utils/StringUtils';

@Component({
    components: { ValidationProvider, ValidationObserver },
    methods: {
        trans,
        parentItemText(item) {
            return `${item.firstName} ${item.lastName} ${item.patronymic}`;
        },
    },
})
export default class EditUserDialog extends Vue implements OnCreated {
    @PropSync('value', { required: true }) public dialog: boolean;
    @Prop({ required: true }) public readonly user: UserModel;

    public userModel: UserEditModel = new UserEditModelInstance();
    public selectedRole: IRoleModel | null = null;
    public loadData: boolean = false;

    public get roles(): Record<string, IRoleModel> {
        return RoleModule.roles;
    }

    public get attributes(): AttributeModel[] {
        return Object.assign([], AttributeModule.attributes);
    }

    public get parents(): UserModel[] {
        return Object.assign([], UserModule.usersByRole);
    }

    @Watch('userModel.roleId')
    private checkRoleWatcher(roleId: number) {
        this.setSelectedRoleValue(roleId);
        this.getAttributeByChangesRole();
    }

    private setSelectedRoleValue(roleId: number): void {
        this.selectedRole = _.find(this.roles, { roleId });
    }

    private async getAttributeByChangesRole(): Promise<void> {
        if (this.selectedRole?.roleValue) {
            if (this.selectedRole.hasSubordinates) {
                this.userModel.parentId = null;
            } else {
                await UserModule.usersByRoleValue(this.selectedRole.roleValue);
                this.userModel.attributeId = null;
            }
        }
    }

    public editUser(validate: Promise<boolean>): void {
        validate.then(async (valid: boolean) => {
            if (valid) {
                this.loadData = true;
                try {
                    const result = await UserModule.editUser(this.userModel);
                    NotifyModule.notify({ show: true, type: NotifyType.info, message: result.message });
                    this.loadData = false;
                    this.dialog = false;
                } catch (error) {
                    console.log(error);
                }
            }
        });
    }

    private initializeData(): void {
        this.userModel = {
            userId: this.user.userId,
            email: this.user.email,
            roleId: this.user.roleId,
            attributeId: this.user.attributeId,
            parentId: this.user.parentId,
            active: this.user.active,
            person: this.user.profileId ? PersonType.users : PersonType.citizens,
        };
    }

    private async initializeModels() {
        if (!this.roles.length) {
            await RoleModule.getAllRoles();
            this.setSelectedRoleValue(this.user.roleId);
        }

        if (!this.attributes.length) {
            await AttributeModule.initAttributes();
        }

        if (!this.parents.length && this.selectedRole && !this.selectedRole.hasSubordinates) {
            await UserModule.usersByRoleValue(this.selectedRole.roleValue);
        }
    }

    public async created(): Promise<void> {
        this.loadData = true;
        this.initializeData();
        await this.initializeModels();
        this.loadData = false;
    }
}
</script>
