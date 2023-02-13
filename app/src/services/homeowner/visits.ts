import { axios } from '@/services/auth';

export const getOwnerVisits = async (ownerId: number) => {
    const { data } = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/viewings/by_owner/${ownerId}`);
    return data;
};