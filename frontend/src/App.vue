<script setup>
import {useAuthStore} from "@/stores/useAuthStore";
import {useToast} from "vue-toastification";
import {useRouter} from "vue-router";

const toast = useToast();
const router = useRouter();
const auth = useAuthStore();

async function logoutHandler() {
  await auth.logout().then(() => {
    router.push({name: 'Login'});
    toast.success("Desconectado.");
  });
}
</script>
<template>
  <header class="relative flex bg-white p-5 w-full items-center justify-between shadow-xl">
    <div>
      <RouterLink to="/">Chat 🫦</RouterLink>
    </div>
    <div v-if="auth.isLoggedIn" class="block text-black/90">
      <span class="px-3">Olá, {{ auth.user?.name }}</span>
      <span
        class="bg-black text-white rounded-md px-3 py-2.5 text-xs uppercase transition duration-200 hover:bg-gray-800 cursor-pointer"
        @click="logoutHandler"
      >
        Sair
      </span>
    </div>
  </header>
  <main class="min-h-screen bg-fixed grid place-items-center bg-gradient-to-r from-violet-500 to-fuchsia-500">
    <RouterView />
  </main>
</template>
