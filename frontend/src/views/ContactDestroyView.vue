<script setup>
import {useRoute, useRouter} from "vue-router";
import apiClient from "@/services/apiClient";
import {useToast} from "vue-toastification";
import {computed, onMounted, reactive} from "vue";
import {useAuthStore} from "@/stores/useAuthStore.js";

const route = useRoute();
const auth = useAuthStore();
const router = useRouter();
const toast = useToast();
const state = reactive({
  isLoading: false,
})
async function handleDestroyAccount(){
  state.isLoading = true;
  await apiClient.delete(`/api/user/${route.params.id}`).then(() => {
    toast.success("Conta deletada com sucesso.");
    router.push({ name: 'Home' });
  }).catch(() => toast.error("Falha ao deletar conta."))
}

async function handleDestroyContact(){
  state.isLoading = true;
  await apiClient.delete(`/api/contact/${route.params.id}`).then(() => {
    toast.success("Contato deletado com sucesso.");
    router.push({ name: 'Home' });
  }).catch(() => toast.error("Falha ao deletar o contato."))
}

const isCurrentUser = computed(() => {
  return route.params.id == auth.user.id;
})

</script>
<template>
  <div class="bg-white rounded-2xl shadow-2xl w-96 lg:w-1/3 justify-center px-6 py-20 lg:px-8">
    <h1 class="text-gray-900 tracking-tight font-bold text-2xl text-center mb-3">
      <template v-if="isCurrentUser">
        Tem certeza que deseja deletar sua conta?
      </template>
      <template v-else>
        Tem certeza que deseja deletar o contato de <span class="font-bold">{{ route.params.name }}</span>?
      </template>
    </h1>
    <div class="flex flex-row">
      <button class="bg-red-600 hover:bg-red-500"
          @click="isCurrentUser ? handleDestroyAccount : handleDestroyContact"
      >
        Deletar
      </button>
      <button class="bg-blue-600 hover:bg-blue-500" @click="router.back()">Cancelar</button>
    </div>
  </div>
</template>
<style scoped>
button{
  @apply p-2 rounded-md mx-2 mt-4 shadow-inner text-white transition duration-200 w-full
}
</style>
