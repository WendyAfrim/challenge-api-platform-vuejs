import { defineStore } from 'pinia';
import { axios } from '@/services/auth';
import router from '@/router';
import { useLocalStorage } from '@vueuse/core';
import { ref, computed } from 'vue';
import jwt_decode, { type JwtPayload } from "jwt-decode";
import { Roles } from '@/enums/roles';

export const useAuthStore = defineStore('auth', () => {

    const access_token = ref(useLocalStorage('access_token', null));
    const refresh_token = ref(useLocalStorage('refresh_token', null));
    const user = ref<any>(access_token.value ? jwt_decode(access_token.value) : null);

    const getRole = computed(() => {
        if (access_token.value) {
            const roles: any = {
                'ROLE_AGENCY': `${Roles.Agency}`,
                'ROLE_TENANT': `${Roles.Tenant}`,
                'ROLE_HOMEOWNER': `${Roles.Homeowner}`
            };
            return roles[user.value.roles[0]];
        } else {
            return null;
        }
    });

    async function login(email: string, password: string) {
        const response = await axios.post(`${import.meta.env.VITE_BASE_API_URL}/api/login`, { email, password }, { headers: { 'Content-Type': 'application/json' } });
        access_token.value = response.data.token;
        refresh_token.value = response.data.refresh_token;
        // @TODO: fix typescript error
        // @ts-ignore
        user.value = jwt_decode(access_token.value);
        return access_token.value;
    }

    function logout() {
        access_token.value = null;
        refresh_token.value = null;
    }

    async function refreshAccessToken() {
        if (!refresh_token.value) router.push('login');
        const response = await axios.post(`${import.meta.env.VITE_BASE_API_URL}/api/token/refresh`, {
            refresh_token: refresh_token.value,
        });
        access_token.value = response.data.token;
        refresh_token.value = response.data.refresh_token;
        // @TODO: fix typescript error
        // @ts-ignore
        user.value = jwt_decode(access_token.value);
        return access_token.value;
    }

    function isTokenExpired() {
        if (!access_token.value) return false;
        const decoded: JwtPayload = jwt_decode(access_token.value);
        if (!decoded.exp) return false;
        return Date.now() > decoded.exp * 1000;
    }

    return { access_token, refresh_token, login, logout, refreshAccessToken, user, getRole, isTokenExpired }
});