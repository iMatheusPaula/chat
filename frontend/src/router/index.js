import { createRouter, createWebHistory } from 'vue-router';
import {useAuthStore} from "@/stores/useAuthStore";

export const routes = [
    {
        path: '/',
        name: 'Home',
        component: () => import('@/views/HomeView.vue'),
        meta: {
            auth: true
        }
    },
    {
        path: '/addContact',
        name: 'addContact',
        component: () => import('@/views/ContactAddView.vue'),
        meta: {
            auth: true
        }
    },
    {
        path: '/contactPage/:id',
        name: 'ContactPage',
        component: () => import('@/views/ContactPageView.vue'),
        meta: {
            auth: true
        },
    },
    {
        path: '/contact/destroy/:id/:name',
        name: 'ContactDestroy',
        component: () => import('@/views/ContactDestroyView.vue'),
        meta: {
            auth: true
        },
    },
    {
        path: '/contact/update/:id',
        name: 'ContactUpdate',
        component: () => import('@/views/ContactUpdateView.vue'),
        meta: {
            auth: true
        },
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('@/views/LoginView.vue'),
        meta: {
            auth: false
        }
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import('@/views/RegisterView.vue'),
        meta: {
            auth: false
        }
    },

]

const router = createRouter({
    history: createWebHistory('/'),
    routes
})

router.beforeEach((to, from, next) => {
    document.title = "Chat";
    const auth = useAuthStore();
    if(to.meta.auth){
        if(!auth.isLoggedIn) next({name: 'Login'});
        else next();
    }
    else if(!to.meta.auth) {
        if(auth.isLoggedIn) next({name: 'Home'});
        else next();
    }
})

export default router
