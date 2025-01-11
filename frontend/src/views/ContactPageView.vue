<script setup>
import {useRoute, useRouter} from "vue-router";
import apiClient from "@/services/apiClient";
import {onMounted, reactive, ref} from "vue";
import {useToast} from "vue-toastification";
import Image from "@/components/Image.vue";
import IconLoading from "@/components/IconLoading.vue";

const route = useRoute();
const router = useRouter();
const toast = useToast();
const state = reactive({
  contact: {},
  isLoading: false,
});

async function getContact() {
  state.isLoading = true;
  await apiClient.get(`/api/contact/show/${route.params.id}`).then((response) => {
    state.contact = response.data;
  }).catch((error) => {
    toast.error("Falha ao carregar o usuario");
  });
  state.isLoading = false;
}

function bntDeleteHandler(){
  router.push({ name: 'ContactDestroy', params: { id: state.contact.id, name: state.contact.name }})
}
function btnUpdateHandler(){
  router.push({ name: 'ContactUpdate', params: { id: state.contact.id }})
}
onMounted(getContact);
</script>
<template>
  <div v-if="state.isLoading">
    <IconLoading />
  </div>
  <div v-else class="bg-white rounded-2xl shadow-2xl w-96 py-10 justify-center px-6 lg:w-1/3 lg:px-8">
    <div @click="router.back()" class="mb-7 text-black transition duration-200 flex flex-row">
      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
        <path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/>
      </svg>
      Voltar
    </div>
    <div class="flex flex-col items-center justify-center gap-5">
      <Image :image="state.contact.image" class="h-48 w-48" />
      <h1 class="text-gray-900 tracking-tight font-bold text-2xl text-center mb-3">{{ state.contact.name }}</h1>
    </div>
    <div class="flex flex-col">
      <div class="mt-6 border-t border-gray-100 mx-2">
      <dl class="divide-y divide-gray-100">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Celular</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ state.contact.phone }}</dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">E-mail</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ state.contact.email }}</dd>
        </div>
      </dl>
      </div>
      <div class="w-full flex flex-row">
        <button class="btn" @click="btnUpdateHandler">Editar</button>
        <button class="btn" @click="bntDeleteHandler">Deletar</button>
      </div>
    </div>
  </div>
</template>
<style scoped>
button{
  @apply p-2 bg-blue-600 rounded-md mx-2 mt-4 hover:bg-blue-500 shadow-inner text-white transition duration-200 w-full
}
</style>