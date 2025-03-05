<template lang="html" src="./Index.html" />
<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

import { OnCreated } from '@/producer/common/contracts/OnCreated';
import ApproveDialog from '@/producer/components/static/ApproveDialog.vue';
import TableFooter from '@/producer/components/static/TableFooter.vue';
import DeleteDialog from '@/producer/pages/board/permission/iframes/DeleteDialog.vue';
import FormPermissionDialog from '@/producer/pages/board/permission/iframes/FormPermissionDialog.vue';
import router from '@/producer/router';
import routesNames from '@/producer/router/routesNames';
import { GetRolePaginateModel } from '@/producer/services/api/Model/IGetRolePaginateModel';
import { NotifyType } from '@/producer/store/models/NotifyModel';
import { PermissionModel } from '@/producer/store/models/PermissionModel';
import NotifyModule from '@/producer/store/modules/NotifyModule';
import PermissionModule from '@/producer/store/modules/PermissionModule';

@Component({
    components: { ApproveDialog, DeleteDialog, FormPermissionDialog, TableFooter },
    name: 'permissionIndex',
})
export default class Index extends Vue implements OnCreated {
    public permissionTableModel: [] = [];
    public permissionDialog: boolean = false;
    public window = { width: 0, height: 0, heightDif: 220 };
    public selectedRow: object;
    public deleteDialog = false;
    public permissionModel: GetRolePaginateModel = new GetRolePaginateModel();
    public dialogData: PermissionModel = new PermissionModel();

    get permissions() {
        return PermissionModule.permissionPager;
    }

    get payload() {
        return PermissionModule.permissionPayload;
    }

    get loader() {
        return PermissionModule.loader;
    }

    get headers() {
        return PermissionModule.permissionsHeaders;
    }

    async queryPaginate(val) {
        await PermissionModule.callAllPermission(val);
        // @ts-expect-error
        await router.push({
            name: routesNames.boardPermission,
            query: {
                page: this.permissions.current_page,
                per_page: this.permissions.per_page,
                search: this.$route.query.search || '',
            },
        });
    }

    private handleResize() {
        this.window.width = window.innerWidth;
        this.window.height = window.innerHeight - this.window.heightDif;
        window.addEventListener('resize', this.handleResize);
    }

    clickSelectEdit(item) {
        this.dialogData = item;
        this.permissionDialog = true;
    }

    clickSelectDelete(item) {
        this.dialogData = item;
        this.deleteDialog = true;
    }

    public markEdited(val: PermissionModel, row: object) {
        this.selectedRow = row;
        // @ts-ignore
        !this.selectedRow.isSelected ? this.selectedRow.select(true) : this.selectedRow.select(false);
    }

    public async permissionDeleteDialogTrigger(val: boolean) {
        if (!val) {
            this.deleteDialog = false;
            return;
        }

        if (this.dialogData.permissionId) {
            const res = await PermissionModule.deletePermission(this.dialogData.permissionId);
            this.deleteDialog = false;
            NotifyModule.notify({ show: true, type: NotifyType.info, message: res.message });
        }
    }

    public async created() {
        this.handleResize();
        this.permissionModel.page = Number(this.$route.query.page) || null;
        this.permissionModel.per_page = Number(this.$route.query.per_page) || null;
        this.permissionModel.search = String(this.$route.query.search) || null;

        await PermissionModule.callAllPermission(this.permissionModel);
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
