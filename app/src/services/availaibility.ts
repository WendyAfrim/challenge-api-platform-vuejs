import { axios } from '@/services/auth';

export const postAvailaibility = async (availaibilities: object) => {
    const { data } = await axios.post(`${import.meta.env.VITE_BASE_API_URL}/availaibilities/proposed/by_owner`, availaibilities);
    return data;
};

export const sendAvailaibilityToLodger = async (propertyId: any, lodgerId: any) => {
    const { data } = await axios.post(`${import.meta.env.VITE_BASE_API_URL}/send/property/${propertyId}/availaibility/${lodgerId}`);
    return data;
};
