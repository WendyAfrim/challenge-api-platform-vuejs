import axiosDefault from 'axios';
import router from '@/router';
import { useAuthStore } from '@/stores/auth.store';

export const axios = axiosDefault.create();
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';

axios.interceptors.request.use(
  async config => {
    const authStore = useAuthStore();
    if (authStore.access_token && !authStore.isTokenExpired()) config.headers['Authorization'] = `Bearer ${authStore.access_token}`;
    return config;
  },
  error => {
    Promise.reject(error)
});

axios.interceptors.response.use((response) => {
  return response
}, async function (error) {
  const originalRequest = error.config;
  if (error.response.status === 401) {
    router.push('login');
    return Promise.reject(error);
    }
  if (error.response.status === 403 && !originalRequest._retry) {
    originalRequest._retry = true;
    const authStore = useAuthStore();
    await authStore.refreshAccessToken();            
    return axios(originalRequest);
  }
  return Promise.reject(error);
});