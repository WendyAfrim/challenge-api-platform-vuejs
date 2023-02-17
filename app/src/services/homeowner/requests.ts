import { axios } from '@/services/auth';

export const postRequestSlots = async (requestId: number, slots: object) => {
    const { data } = await axios.post(`${import.meta.env.VITE_BASE_API_URL}/requests/${requestId}/slots/proposals`, slots);
    return data;
};

export const getRequestsByOwner = async (ownerId: number) => {
    const { data } = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/requests/by_owner/${ownerId}`);
    return data;
};
