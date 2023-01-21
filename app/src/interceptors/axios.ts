import axios from "axios";
import router from "../router";

axios.defaults.baseURL = `${import.meta.env.VITE_BASE_API_URL}`;

let refresh = false;
axios.interceptors.response.use(resp => resp, async error => {
    if (error.response.status === 401 && !refresh){
        refresh = true;
        const {status, data} = await axios.post('refresh', {});
        if (status === 200){
            axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
            return axios(error.config);
        }
    }
    refresh = false;
    return error;
});

export default axios
