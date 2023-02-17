import { axios } from '@/services/auth';

export const getRequestsByLodger = async (lodgerId: number) => {
    const { data } = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/requests/by_lodger/${lodgerId}`);
    return data;
};

export const getTenantRequest = async (requestId: number) => {
    const { data } = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/requests/${requestId}`);
    return data;
};

export const postSlotChoosenByTheLodger = async (requestId: number, slotId: number) => {
    const { data } = await axios.post(`${import.meta.env.VITE_BASE_API_URL}/requests/${requestId}/slot/${slotId}`);
    return data;
};