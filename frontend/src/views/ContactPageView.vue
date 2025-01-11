<script setup>
import {useRoute, useRouter} from "vue-router";
import apiClient from "@/services/apiClient";
import {onMounted, reactive} from "vue";
import {useToast} from "vue-toastification";
import Image from "@/components/Image.vue";
import IconLoading from "@/components/IconLoading.vue";
import ReturnBtn from "@/components/ReturnBtn.vue";

const route = useRoute();
const router = useRouter();
const toast = useToast();
const state = reactive({
  user: {},
  isLoading: false,
});

async function getUser() {
  state.isLoading = true;
  await apiClient.get(`/api/user/${route.params.id}`).then((response) => {
    state.user = response.data;
  }).catch((error) => toast.error("Falha ao carregar o usuario"));
  state.isLoading = false;
}

function bntDeleteHandler(){
  router.push({ name: 'ContactDestroy', params: { id: state.user.id, name: state.user.name }})
}
function btnUpdateHandler(){
  router.push({ name: 'ContactUpdate', params: { id: state.user.id }})
}

const isCurrentUser = () => {
  return state.user.id === auth.user.id
}
onMounted(getUser);
</script>
<template>
  <div v-if="state.isLoading">
    <IconLoading />
  </div>
  <div v-else class="bg-white rounded-2xl shadow-2xl w-96 py-10 justify-center px-6 lg:w-1/3 lg:px-8">
    <ReturnBtn />
    <div class="flex flex-col items-center justify-center gap-5">
      <Image :image="state.user.image" class="h-48 w-48" />
      <h1 class="text-gray-900 tracking-tight font-bold text-2xl text-center mb-3">{{ state.user.name }}</h1>
    </div>
    <div class="flex flex-col">
      <div class="mt-6 border-t border-gray-100 mx-2">
      <dl class="divide-y divide-gray-100">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Celular</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ state.user.phone }}</dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">E-mail</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ state.user.email }}</dd>
        </div>
      </dl>
      </div>
      <div class="w-full flex flex-row">
        <button class="btn bg-blue-600 hover:bg-blue-500" @click="btnUpdateHandler">Editar</button>
        <button class="btn bg-red-600 hover:bg-red-500 " @click="bntDeleteHandler">Deletar</button>
      </div>
    </div>
  </div>
</template>
<style scoped>
button{
  @apply p-2 rounded-md mx-2 mt-4 shadow-inner text-white transition duration-200 w-full
}
</style>