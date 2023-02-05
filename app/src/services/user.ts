import axios from "axios";

export const fetchUser = async (id: number) => {
    console.log(import.meta.env)
    const { data } = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/users/${id}`);
    return data;
};

export const addUser = async (user: any) => {
    const { data } = await axios.post(`${import.meta.env.VITE_BASE_API_URL}/users`, user);
    return data;
};

export const updateUser = async (id: number, user: any) => {
    const { data } = await axios.put(`${import.meta.env.VITE_BASE_API_URL}/users/${id}`, user);
    return data;
};

export const requestNewLink = async (email: string) => {
    const { data } = await axios.post(`${import.meta.env.VITE_BASE_API_URL}/verify/request-new-link`, { email });
    return data;
}