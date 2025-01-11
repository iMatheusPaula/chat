import {ref, computed} from "vue"
import apiClient from "@/services/apiClient";
import {defineStore} from "pinia";

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null);
    const isLoggedIn = computed(() => !!user.value);
    const getUser = async function () {
        await apiClient.get('/api/user').then((response) => {
            user.value = response.data;
        })
    }
    const login = async function (credentials) {
        await apiClient.get('/sanctum/csrf-cookie');
        await apiClient.post('/api/login', credentials)
        await getUser();
    }
    const register = async function (credentials) {
        await apiClient.get('/sanctum/csrf-cookie');
        await apiClient.post('/api/register', credentials);
    }
    const logout = async function () {
        await apiClient.post('/api/logout');
        user.value = null;
    }
    return { user, isLoggedIn, getUser, login, register, logout }
}, {persist: true});
