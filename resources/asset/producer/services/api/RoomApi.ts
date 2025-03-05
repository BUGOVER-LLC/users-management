import BaseApi from '@/producer/services/BaseApi';
import { RoomModel } from '@/producer/store/models/RoomModel';
import { CreateRoom } from '@/producer/store/modules/RoomModule';

const GET_ROOMS: string = '/umra/rooms/';
const CREATE_ROOM: string = '/umra/room';

export const getRoomsByAttributeId = async (attributeId: number) => {
    const res = await BaseApi.get(`${GET_ROOMS}${attributeId}`);

    return res?.data;
};

export const saveRoomsByAttributeId = async (attributeId: number, rooms: RoomModel[]) => {
    const res = await BaseApi.put(`${GET_ROOMS}${attributeId}`, rooms);

    return res?.data;
};

export const createRoomsBy = async (room: CreateRoom) => {
    const res = await BaseApi.post(CREATE_ROOM, room);

    return res?.data;
};
