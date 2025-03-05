<template>
    <v-card class="rounded-lg" :loading="loaders">
        <v-card-title class="accent">
            {{ trans('words.room', 2) }}
            <v-spacer />
            <v-btn icon @click="dialog = false">
                <v-icon color="grey darken-3" v-text="'mdi-close'" />
            </v-btn>
        </v-card-title>
        <v-divider />
        <v-card-text>
            <div class="mb-3">
                <div
                    v-if="1 === windowItem"
                    class="text-decoration-underline blue-grey--text pointer"
                    @click="1 === windowItem ? (windowItem = 2) : (windowItem = 1)"
                    ><span>{{ trans('room.create') }}</span></div
                >
                <div
                    v-else
                    class="text-decoration-underline pointer blue-grey--text"
                    @click="1 === windowItem ? (windowItem = 2) : (windowItem = 1)"
                    ><span>{{ trans('words.room', 2) }}</span></div
                >
            </div>
            <v-window v-model="windowItem" class="mt-3">
                <v-window-item :value="1">
                    <v-data-table hide-default-footer :items="getRooms" :headers="headers">
                        <template #item.roomName="props">
                            <v-edit-dialog :return-value.sync="props.item.roomName">
                                {{ props.item.roomName }}
                                <template #input>
                                    <v-text-field v-model="props.item.roomName" label="Edit" single-line counter />
                                </template>
                            </v-edit-dialog>
                        </template>
                        <template #item.roomValue="props">
                            <v-edit-dialog :return-value.sync="props.item.roomValue">
                                {{ props.item.roomValue }}
                                <template #input>
                                    <v-text-field v-model="props.item.roomValue" label="Edit" single-line counter />
                                </template>
                            </v-edit-dialog>
                        </template>
                        <template #item.roomDescription="props">
                            <v-edit-dialog :return-value.sync="props.item.roomDescription">
                                {{ props.item.roomDescription }}
                                <template #input>
                                    <v-text-field
                                        v-model="props.item.roomDescription"
                                        label="Edit"
                                        single-line
                                        counter
                                    />
                                </template>
                            </v-edit-dialog>
                        </template>

                        <template #item.delete="{ item }">
                            <v-btn icon class="ml-1" @click="deleteRoom(item.roomId)">
                                <v-icon color="grey darken-3" v-text="'mdi-delete-outline'" />
                            </v-btn>
                        </template>
                    </v-data-table>
                    <v-btn
                        class="mt-3"
                        block
                        outlined
                        :loading="storeLoader"
                        @click="storeChange"
                        v-text="trans('words.save')"
                    />
                </v-window-item>
                <v-window-item :value="2">
                    <ValidationObserver ref="roomCreateForm" v-slot="{ invalid, validate }">
                        <v-form autocomplete="off">
                            <ValidationProvider
                                v-slot="{ errors }"
                                rules="required|max:150|min:3"
                                :name="trans('room.name')"
                                vid="roomName"
                            >
                                <v-text-field
                                    v-model="createRoomData.roomName"
                                    outlined
                                    dense
                                    :error-messages="errors"
                                    :label="trans('room.name')"
                                />
                            </ValidationProvider>
                            <ValidationProvider
                                v-slot="{ errors }"
                                rules="required|max:150|min:3"
                                :name="trans('room.value')"
                                vid="roomValue"
                            >
                                <v-text-field
                                    v-model="createRoomData.roomValue"
                                    outlined
                                    dense
                                    :error-messages="errors"
                                    :label="trans('room.value')"
                                />
                            </ValidationProvider>
                            <ValidationProvider
                                v-slot="{ errors }"
                                rules="max:200|min:3"
                                :name="trans('room.description')"
                                vid="roomDescription"
                            >
                                <v-textarea
                                    v-model="createRoomData.roomDescription"
                                    outlined
                                    dense
                                    rows="2"
                                    :error-messages="errors"
                                    :label="trans('room.description')"
                                />
                            </ValidationProvider>
                        </v-form>
                        <v-btn
                            class="mt-3"
                            block
                            outlined
                            :loading="storeLoader"
                            :disabled="invalid"
                            @click="createRoom(validate())"
                            v-text="trans('words.create')"
                        />
                    </ValidationObserver>
                </v-window-item>
            </v-window>
        </v-card-text>
    </v-card>
</template>

<script lang="ts">
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import { Component, Prop, PropSync, Vue, Watch } from 'vue-property-decorator';

import { OnCreated } from '@/producer/common/contracts/OnCreated';
import { saveRoomsByAttributeId } from '@/producer/services/api/RoomApi';
import { AttributeModel } from '@/producer/store/models/AttributeModel';
import { HandlerModel } from '@/producer/store/models/HandlerModel';
import { RoomModel } from '@/producer/store/models/RoomModel';
import RoomModule, { CreateRoom } from '@/producer/store/modules/RoomModule';
import { trans } from '@/producer/utils/StringUtils';

@Component({
    methods: { trans },
    components: { ValidationObserver, ValidationProvider },
})
export default class RoomDialog extends Vue implements OnCreated {
    @PropSync('value', { required: true }) public dialog: boolean;
    @Prop({ required: true }) public attribute: AttributeModel;

    public storeLoader: boolean = false;
    public windowItem: number = 1;
    public headers = [
        {
            text: trans('users.first_name'),
            align: 'start',
            sortable: false,
            value: 'roomName',
        },
        {
            text: trans('room.value'),
            align: 'start',
            sortable: false,
            value: 'roomValue',
        },
        {
            text: trans('room.description'),
            align: 'start',
            sortable: false,
            value: 'roomDescription',
        },
        {
            text: trans('words.delete'),
            align: 'start',
            sortable: false,
            value: 'delete',
        },
    ];
    public createRoomData: CreateRoom = {
        attributeId: 0,
        roomName: '',
        roomValue: '',
        roomDescription: '',
    };

    @Watch('handler')
    public watchErrorHandler(val: HandlerModel) {
        if (val) {
            // @ts-ignore
            this.$refs.roomCreateForm.setErrors(val);
        }
    }

    /**
     * @return RoomModel[]
     */
    public get getRooms(): RoomModel[] {
        return RoomModule.getRoomData[this.attribute.attributeId];
    }

    /**
     * @return RoomModel[]
     */
    public get loaders(): boolean {
        return RoomModule.loader;
    }

    public deleteRoom(roomId: number) {
        RoomModule.emitRemoveRoomById({ attributeId: this.attribute.attributeId, roomId });
    }

    public async storeChange() {
        this.storeLoader = true;
        await saveRoomsByAttributeId(this.attribute.attributeId, this.getRooms);
        this.storeLoader = false;
        this.dialog = false;
    }

    public async createRoom(validate: Promise<boolean>) {
        validate.then(async (valid: boolean) => {
            if (valid) {
                this.storeLoader = true;
                this.createRoomData.attributeId = this.attribute.attributeId;
                await RoomModule.emitCreateRoom(this.createRoomData);
                this.storeLoader = false;
                this.windowItem = 1;
                this.createRoomData = { attributeId: 0, roomName: '', roomDescription: '', roomValue: '' };
                // @ts-ignore
                this.$refs.roomCreateForm.reset();
            }
        });
    }

    async created() {
        if (this.attribute) {
            await RoomModule.getRoomsByAttributeId(this.attribute.attributeId);
        }
    }
}
</script>
