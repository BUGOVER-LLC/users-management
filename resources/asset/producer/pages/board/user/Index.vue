<template lang="html" src="./Index.html" />
<script lang="ts">
import _ from 'lodash';
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import { Component, Vue, Watch } from 'vue-property-decorator';

import { OnCreated } from '@/producer/common/contracts/OnCreated';
import ApproveDialog from '@/producer/components/static/ApproveDialog.vue';
import TableFooter from '@/producer/components/static/TableFooter.vue';
import CreateUserDialog from '@/producer/pages/board/user/create/CreateUserDialog.vue';
import EditUserDialog from '@/producer/pages/board/user/edit/EditUserDialog.vue';
import InvitationsDialog from '@/producer/pages/board/user/iframes/InvitationsDialog.vue';
import routesNames from '@/producer/router/routesNames';
import { GetUserPaginateModelInstance } from '@/producer/services/api/Model/GetUserPaginateModel';
import { PersonType } from '@/producer/services/enums/PersonType';
import { IRoleModel } from '@/producer/store/models/IRoleModel';
import { NotifyType } from '@/producer/store/models/NotifyModel';
import { PaginateHeader, PaginateModel } from '@/producer/store/models/PaginateModel';
import { UserModel } from '@/producer/store/models/UserModel';
import NotifyModule from '@/producer/store/modules/NotifyModule';
import RoleModule from '@/producer/store/modules/RoleModule';
import UserModule from '@/producer/store/modules/UserModule';
import { trans } from '@/producer/utils/StringUtils';

@Component({
    methods: {
        trans,
    },
    components: {
        ApproveDialog,
        InvitationsDialog,
        EditUserDialog,
        CreateUserDialog,
        TableFooter,
        ValidationProvider,
        ValidationObserver,
    },
})
export default class Index extends Vue implements OnCreated {
    public window = { width: 0, height: 0, heightDif: 220 };
    public deleteDialog: boolean = false;
    public createDialog: boolean = false;
    public editDialog: boolean = false;
    public invitationsDialog: boolean = false;
    public dialogData: null | UserModel;
    public disableActive: boolean = false;
    public userPaginateModel: GetUserPaginateModelInstance = new GetUserPaginateModelInstance();
    public selectedRow: object;

    public async queryPaginate() {
        if (this.paginate) {
            this.userPaginateModel.page = this.paginate.current_page;
            this.userPaginateModel.per_page = this.paginate.per_page;
        }
        await UserModule.callUserPager(this.userPaginateModel);
        // @ts-ignore
        await this.$router.push({
            name: routesNames.usersControl,
            query: Object.assign(this.userPaginateModel),
        });
    }

    public get checkActive(): boolean {
        if (Object.hasOwn(this.$route.query, 'active')) {
            return 'true' === this.$route.query.active;
        }
        return true;
    }

    public get paginate(): PaginateModel {
        return UserModule.paginate;
    }

    public get payload(): Record<string, UserModel> {
        return UserModule.payload;
    }

    public get roles(): Record<string, IRoleModel> {
        return RoleModule.roles;
    }

    public get loader(): boolean {
        return UserModule.loader;
    }

    public get header(): PaginateHeader {
        return UserModule.headers;
    }

    @Watch('userPaginateModel.active')
    public async activeUserTrigger(val: boolean, oldVal: boolean | null) {
        if (null !== oldVal && val !== oldVal) {
            this.userPaginateModel.active = val;
            await this.queryPaginate();
        }
    }

    @Watch('userPaginateModel.person')
    public async personTrigger(val: string, oldVal: string | null) {
        if (oldVal && val !== oldVal) {
            this.userPaginateModel.person = val;
            await this.queryPaginate();
        }
    }

    public deleteUserDialog(user: UserModel) {
        this.dialogData = user;
        this.deleteDialog = true;
    }

    public async userDeleteDialogTrigger(val: boolean) {
        if (!val) {
            this.deleteDialog = false;
            return;
        }

        if (this.dialogData) {
            const res = await UserModule.deleteUser(this.dialogData.userId);
            NotifyModule.notify({ show: true, type: NotifyType.info, message: res.message });
        }

        this.deleteDialog = false;
    }

    public userInvitationsDialog(user) {
        this.dialogData = user;
        this.invitationsDialog = true;
    }

    public editUserDialog(user) {
        this.dialogData = user;
        this.editDialog = true;
    }

    public async searchUserTrigger() {
        this.userPaginateModel.page = this.paginate.current_page;
        this.userPaginateModel.per_page = this.paginate.per_page;

        await this.queryPaginate();
    }

    private handleResize() {
        this.window.width = window.innerWidth;
        this.window.height = window.innerHeight - this.window.heightDif;
        window.addEventListener('resize', this.handleResize);
    }

    private async initializeRoles() {
        if (!this.roles.length) {
            await RoleModule.getAllRoles();
        }
    }

    public markEdited(val: UserModel, row: object) {
        this.selectedRow = row;
        // @ts-ignore
        !this.selectedRow.isSelected ? this.selectedRow.select(true) : this.selectedRow.select(false);
    }

    private initializePager() {
        this.userPaginateModel.active = this.checkActive;
        this.userPaginateModel.search = this.$route.query.search ? String(this.$route.query.search) : '';
        this.userPaginateModel.per_page = this.$route.query.per_page ? Number(this.$route.query.per_page) : 25;
        this.userPaginateModel.page = this.$route.query.per_page ? Number(this.$route.query.page) : 1;
        this.userPaginateModel.person = String(this.$route.query.person || PersonType.users);
        this.userPaginateModel.roles = this.$route.query.roles
            ? _.map(this.$route.query.roles, (item: string) => parseInt(item))
            : [];
    }

    public async created() {
        this.handleResize();
        this.initializePager();
        await this.initializeRoles();

        await UserModule.callUserPager(this.userPaginateModel);
    }
}
</script>

<style lang="scss">
.v-data-table {
    border-radius: 15px !important;
}

.v-data-table header {
    border-radius: 15px !important;
}
</style>
