<template lang="html" src="./CreateUserDialog.html" />

<script lang="ts">
import _ from 'lodash';
import { VueMaskDirective } from 'v-mask';
import { validate, ValidationObserver, ValidationProvider } from 'vee-validate';
import { ValidationResult } from 'vee-validate/dist/types/types';
import { Component, PropSync, Vue, Watch } from 'vue-property-decorator';

import { OnCreated } from '@/producer/common/contracts/OnCreated';
import DatePicker from '@/producer/components/static/DatePicker.vue';
import {
    CreateUserInviteModel,
    CreateUserInviteModelInstance,
} from '@/producer/services/api/Model/CreateUserInviteModel';
import { HandlerModel } from '@/producer/store/models/HandlerModel';
import { IRoleModel } from '@/producer/store/models/IRoleModel';
import { NotifyType } from '@/producer/store/models/NotifyModel';
import AttributeModule from '@/producer/store/modules/AttributeModule';
import HandlerModule from '@/producer/store/modules/HandlerModule';
import NotifyModule from '@/producer/store/modules/NotifyModule';
import RoleModule from '@/producer/store/modules/RoleModule';
import UserModule from '@/producer/store/modules/UserModule';
import { trans } from '@/producer/utils/StringUtils';

@Component({
    components: { DatePicker, ValidationObserver, ValidationProvider },
    directives: {
        mask: VueMaskDirective,
    },
    methods: {
        trans,
        parentItemText(item) {
            return `${item.firstName} ${item.lastName} ${item.patronymic}`;
        },
    },
})
export default class CreateUserDialog extends Vue implements OnCreated {
    @PropSync('value', { required: true }) public dialog: boolean;

    public window = { width: 0, height: 0, heightDif: 120 };
    public userModel: CreateUserInviteModel = new CreateUserInviteModelInstance();
    public checkSpnLoader: boolean = false;
    public psnMask: string = '##########';
    public checkSpnStatus: boolean = false;
    public selectedRole: IRoleModel | null = null;
    public patronymicDisable = true;

    public get roles() {
        return RoleModule.roles;
    }

    public get loader() {
        return UserModule.loader;
    }

    public get attributes() {
        return Object.assign([], AttributeModule.attributes);
    }

    public get parents() {
        return Object.assign([], UserModule.usersByRole);
    }

    private get handler() {
        return HandlerModule.handler;
    }

    public async created() {
        this.handleResize();
        await RoleModule.getAllRoles();
    }

    @Watch('handler')
    private watchErrorHandler(val: HandlerModel) {
        if (val) {
            // @ts-ignore
            this.$refs.createUserForm.setErrors(val);
        }
    }

    @Watch('userModel.psn')
    private checkSpnWatcher(psn) {
        if (this.checkSpnStatus && 10 !== psn.toString().length) {
            this.checkSpnStatus = false;
        }
    }

    @Watch('userModel.roleId')
    private checkRoleWatcher(roleId: number) {
        this.selectedRole = _.find(this.roles, { roleId });
        this.getAttributeByChangesRole();
    }

    private async getAttributeByChangesRole() {
        if (this.selectedRole?.roleValue) {
            if (this.selectedRole.hasSubordinates) {
                await AttributeModule.initAttributes();
                this.userModel.parentId = null;
            } else {
                await UserModule.usersByRoleValue(this.selectedRole.roleValue);
                this.userModel.attributeId = null;
            }
        }
    }

    public handleResize() {
        this.window.width = window.innerWidth;
        this.window.height = window.innerHeight - this.window.heightDif;
        window.addEventListener('resize', this.handleResize);
    }

    public checkSpn() {
        this.checkSpnLoader = true;
        validate(this.userModel.psn, 'required|min:10|max:10').then(async (result: ValidationResult) => {
            if (result.valid && this.userModel.psn) {
                try {
                    const user = await UserModule.initCheckPSN(this.userModel.psn);
                    this.userModel.dateBirth = user._payload.dateBirth;
                    this.userModel.firstName = user._payload.firstName;
                    this.userModel.lastName = user._payload.lastName;
                    this.userModel.patronymic = user._payload.patronymic;
                    this.checkSpnStatus = true;
                } catch (error) {
                    //  @ts-ignore
                    this.$refs.createUserForm.setErrors({ userPsn: error.message });
                } finally {
                    this.checkSpnLoader = false;
                    this.patronymicDisable = !!this.userModel.patronymic;
                }
            }
        });
    }

    public createUser(validate: Promise<boolean>) {
        validate.then(async (result: boolean) => {
            if (!result) {
                return;
            }
            try {
                const user = await UserModule.createUser(this.userModel);
                NotifyModule.notify({ show: true, type: NotifyType.info, message: user.message });
                this.dialog = false;
            } catch (error) {
                // @ts-ignore
                this.$refs.createUserForm.setErrors({ userEmail: error.message });
            } finally {
                UserModule.initLoader(false);
            }
        });
    }
}
</script>

<style>
.check-spn-btn {
    margin-top: 2px !important;
}
</style>
