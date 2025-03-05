<template src="./Index.html" />
<script lang="ts">
import _ from 'lodash';
import moment from 'moment';
import { extend, setInteractionMode, ValidationObserver, ValidationProvider } from 'vee-validate';
import { email, max, regex, required } from 'vee-validate/dist/rules';
import { Component, Vue } from 'vue-property-decorator';

import { OnCreated } from '@/producer/common/contracts/OnCreated';
import ApproveDialog from '@/producer/components/static/ApproveDialog.vue';
import ClientCreateDialog from '@/producer/pages/board/system/iframes/ClientCreateDialog.vue';
import { IClientModel, ISystemModel } from '@/producer/store/models/SystemModel';
import SystemModule from '@/producer/store/modules/SystemModule';

setInteractionMode('eager');
extend('required', required);
extend('max', max);
extend('regex', regex);
extend('email', email);

@Component({
    components: { ApproveDialog, ClientCreateDialog, ValidationProvider, ValidationObserver },
    filters: {
        formatData(value: string) {
            return moment(String(value)).format('HH:mm MM/DD/YYYY');
        },
    },
})
export default class Index extends Vue implements OnCreated {
    public showClientDialog: boolean = false;
    public lazyImage: string = '/storage/img/noimage.png';
    public deleteDialog: boolean = false;
    public deleteData: number;
    public secretInputType: string = 'password';
    public window = { width: 0, height: 0, heightDif: 435 };

    public get initialSystem(): ISystemModel {
        return SystemModule.initialSystem;
    }

    public get initialClients(): Record<string, IClientModel> {
        return SystemModule.initialClients;
    }

    public get systemLoading(): boolean {
        return SystemModule.systemLoading;
    }

    public get clientLoading(): boolean {
        return SystemModule.clientLoading;
    }

    private handleResize() {
        this.window.width = window.innerWidth;
        this.window.height = window.innerHeight - this.window.heightDif;
        window.addEventListener('resize', this.handleResize);
    }

    public copySecret(clientId: number) {
        const textToCopy = _.find(this.initialClients, { clientId });
        navigator.clipboard.writeText(textToCopy.clientSecret);
    }

    public toggleShowClientSecret() {
        'password' === this.secretInputType ? (this.secretInputType = 'text') : (this.secretInputType = 'password');
    }

    public toggleClientSecretIcon() {
        return 'password' === this.secretInputType ? 'mdi-eye' : 'mdi-eye-off';
    }

    public previewImage(event) {
        const reader = new FileReader();

        reader.onload = e => {
            // @ts-ignore
            this.initialSystem.systemLogo = e.target.result;
        };

        if (event) {
            reader.readAsDataURL(event.target.files[0]);
            this.initialSystem.systemLogo = event.target.files[0];
        } else {
            // @ts-ignore
            this.initialSystem.systemLogo = this.lazyImage;
        }

        return true;
    }

    public deleteClient(event: boolean) {
        if (event) {
            SystemModule.deleteClient(this.deleteData);
        }

        this.deleteDialog = false;
    }

    public async saveSystemData() {
        await SystemModule.saveSystem();
    }

    public async created() {
        this.handleResize();
        await SystemModule.emitInitial();
    }
}
</script>
