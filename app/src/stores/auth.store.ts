import { defineStore } from 'pinia';
import { axios } from '@/services/auth';
import router from '@/router';
import { useLocalStorage } from '@vueuse/core';
import { ref, computed } from 'vue';
import jwt_decode, { type JwtPayload } from "jwt-decode";
import { Roles } from '@/enums/roles';
import type { Ref } from 'vue'
export const useAuthStore = defineStore('auth', () => {

    const access_token: Ref<string | null> = ref(useLocalStorage('access_token', null));
    const refresh_token: Ref<string | null> = ref(useLocalStorage('refresh_token', null));
    const payload: any = ref(access_token.value ? jwt_decode(access_token.value) : null);
    const user: any = ref(null);
    
    const getUser = computed(async () => {
        if (isTokenExpired()) {
            refreshAccessToken();
        }
        if (access_token.value) {
            if (user.value) return user.value;
            const response = await axios.get(`${import.meta.env.VITE_BASE_API_URL}/users/${payload.value.id}`, { headers: { 'Authorization': `Bearer ${access_token.value}` } });
            user.value = response.data;
            return response.data;
        } else {
            return null;
        }
    });

    const updateUser = async (data: any) => {
        if (isTokenExpired()) {
            refreshAccessToken();
        }
        if (access_token.value) {
            const response = await axios.put(`${import.meta.env.VITE_BASE_API_URL}/users/${payload.value.id}`, data, { headers: { 'Authorization': `Bearer ${access_token.value}` } });
            user.value = response.data;
            return response.data;
        } else {
            return null;
        }
    }

    async function login(email: string, password: string) {
        const response = await axios.post(`${import.meta.env.VITE_BASE_API_URL}/api/login`, { email, password }, { headers: { 'Content-Type': 'application/json' } });
        access_token.value = response.data.token;
        refresh_token.value = response.data.refresh_token;
        payload.value = jwt_decode<any>(access_token.value as string);
        return access_token.value;
    }

    function logout() {
        [access_token.value, refresh_token.value, user.value] = Array(3).fill(null);
        console.log(payload);
    }

    async function refreshAccessToken() {
        if (!refresh_token.value) router.push('login');
        const response = await axios.post(`${import.meta.env.VITE_BASE_API_URL}/api/token/refresh`, {
            refresh_token: refresh_token.value,
        });
        access_token.value = response.data.token;
        refresh_token.value = response.data.refresh_token;
        payload.value = jwt_decode(access_token.value as string);
        return access_token.value;
    }

    function isTokenExpired() {
        if (!access_token.value) return false;
        const decoded: JwtPayload = jwt_decode(access_token.value);
        if (!decoded.exp) return false;
        return Date.now() > decoded.exp * 1000;
    }

    return { access_token, refresh_token, login, logout, refreshAccessToken, payload, getUser, updateUser, isTokenExpired }
});