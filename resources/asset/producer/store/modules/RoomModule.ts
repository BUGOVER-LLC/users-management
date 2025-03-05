import Vue from 'vue';
import { Action, getModule, Module, Mutation, VuexModule } from 'vuex-module-decorators';

import { createRoomsBy, getRoomsByAttributeId } from '@/producer/services/api/RoomApi';
import store from '@/producer/store';
import { RoomModel } from '@/producer/store/models/RoomModel';
import modulesNames from '@/producer/store/moduleNames';

interface RemoveRoom {
    attributeId: number;
    roomId: number;
}

export interface CreateRoom {
    attributeId: number;
    roomName: string;
    roomValue: string;
    roomDescription: null | string;
}

@Module({ dynamic: true, namespaced: true, store, name: modulesNames.roomModule })
class RoomModule extends VuexModule {
    private readonly _roomData: Record<number, RoomModel[]> = {};
    private _loading: boolean = false;

    @Mutation
    private fetchRoomsData(rooms: RoomModel[]) {
        if (rooms.length && rooms[0].attributeId) {
            Vue.set(this._roomData, rooms[0].attributeId, rooms);
        }
    }

    @Mutation
    private fetchRemoveByIndex(state: RemoveRoom) {
        this._roomData[state.attributeId].splice(state.roomId, 1);
    }

    @Mutation
    private fetchLoading(value: boolean) {
        this._loading = value;
    }

    @Action({ rawError: true })
    public async getRoomsByAttributeId(attributeId: number) {
        this.fetchLoading(true);
        const result = await getRoomsByAttributeId(attributeId);
        this.fetchRoomsData(result._payload);
        this.fetchLoading(false);

        return result._payload;
    }

    @Action({ rawError: true })
    public async emitCreateRoom(payload: CreateRoom) {
        const result = await createRoomsBy(payload);
        this.fetchRoomsData(result._payload);

        return result;
    }

    @Action({ rawError: true })
    public emitRemoveRoomById(payload: RemoveRoom) {
        const roomIndex = this._roomData[payload.attributeId].findIndex(
            (item: RoomModel) => item.roomId === payload.roomId,
        );
        if (~roomIndex) {
            this.fetchRemoveByIndex({ attributeId: payload.attributeId, roomId: roomIndex });
        }
    }

    public get getRoomData(): Record<string, RoomModel[]> {
        return this._roomData;
    }

    public get loader(): boolean {
        return this._loading;
    }
}

export default getModule(RoomModule);
