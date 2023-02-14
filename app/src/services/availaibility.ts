import { axios } from '@/services/auth';

export const sendAvailaibilityToLodger = async (propertyId: any, lodgerId: any) => {
    const { data } = await axios.post(`${import.meta.env.VITE_BASE_API_URL}/send/property/${propertyId}/availaibility/${lodgerId}`);
    return data;
};
