<template src="./Index.html" lang="html" />
<script lang="ts">
import _ from 'lodash';
import moment from 'moment';
import { Component, Vue } from 'vue-property-decorator';

import { OnCreated } from '@/producer/common/contracts/OnCreated';
import ApproveDialog from '@/producer/components/static/ApproveDialog.vue';
import AttributeDialog from '@/producer/pages/board/attributes/iframes/AttributeDialog.vue';
import ResourceDialog from '@/producer/pages/board/attributes/iframes/ResourceDialog.vue';
import RoomDialog from '@/producer/pages/board/attributes/iframes/RoomDialog.vue';
import { AttributeModel } from '@/producer/store/models/AttributeModel';
import { NotifyType } from '@/producer/store/models/NotifyModel';
import { ResourceModel } from '@/producer/store/models/ResourceModel';
import AttributeModule from '@/producer/store/modules/AttributeModule';
import NotifyModule from '@/producer/store/modules/NotifyModule';
import ResourceModule from '@/producer/store/modules/ResourceModule';

@Component({
    components: { RoomDialog, ResourceDialog, ApproveDialog, AttributeDialog },
    filters: {
        formatData(value) {
            return value ? moment(String(value)).format('HH:mm MM/DD/YYYY') : '';
        },
    },
})
export default class Index extends Vue implements OnCreated {
    public window = { width: 0, height: 0, heightDif: 170 };

    public attributeDialog: boolean = false;
    public resourceDialog: boolean = false;
    public deleteDialog: boolean = false;
    public roomDialog: boolean = false;
    public dialogData: AttributeModel | ResourceModel | null = null;

    public get attributes() {
        return AttributeModule.attributes;
    }

    public get resources() {
        return ResourceModule.resources;
    }

    public get resourcesLoading(): boolean {
        return ResourceModule.resourcesLoading;
    }

    public get attributesLoading(): boolean {
        return AttributeModule.attributesLoading;
    }

    public resourceName(resourceId: number | null) {
        return resourceId ? _.find(this.resources, { resourceId: resourceId }).resourceName ?? null : '';
    }

    public editAttribute(attribute: AttributeModel) {
        this.dialogData = attribute;
        this.attributeDialog = true;
    }

    public editRoom(attribute: AttributeModel) {
        this.dialogData = attribute;
        this.roomDialog = true;
    }

    public editResource(attribute: ResourceModel) {
        this.dialogData = attribute;
        this.resourceDialog = true;
    }

    public async deleteAttribute(event: boolean) {
        if (event && this.dialogData && 'attributeId' in this.dialogData) {
            const result = await AttributeModule.deleteAttribute(this.dialogData.attributeId);
            NotifyModule.notify({ show: true, type: NotifyType.info, message: result.message });
        }

        this.deleteDialog = false;
    }

    public async deleteResource(event: boolean) {
        if (event && this.dialogData && 'resourceId' in this.dialogData) {
            const result = await ResourceModule.deleteResource(this.dialogData.resourceId);
            NotifyModule.notify({ show: true, type: NotifyType.info, message: result.message });
        }

        this.deleteDialog = false;
    }

    public handleResize() {
        this.window.width = window.innerWidth;
        this.window.height = window.innerHeight - this.window.heightDif;
        window.addEventListener('resize', this.handleResize);
    }

    public async created() {
        this.handleResize();
        await ResourceModule.initResources();
        await AttributeModule.initAttributes();
    }
}
</script>

<style lang="scss">
.v-data-table__wrapper {
    overflow: hidden !important;
}
</style>
