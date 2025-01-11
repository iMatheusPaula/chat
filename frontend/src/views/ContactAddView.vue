<script setup>
import {reactive} from "vue";
import apiClient from "@/services/apiClient.js";
import {useToast} from "vue-toastification";
import {useRouter} from "vue-router";
import ReturnBtn from "@/components/ReturnBtn.vue";

const router = useRouter();
const toast = useToast();
const state = reactive({
  isLoading: false,
  name: '',
  selectedUserId: null,
  listContact: {}
})
function handleFileUpload(event){
  state.image = event.target.files[0];
}

async function addContact(){
  if (!state.selectedUserId) {
    toast.warning("Por favor, selecione um usuário.");
    return;
  }

  await apiClient.post('/api/contact/store', {id: state.selectedUserId}).then(() => {
    toast.success(`Contato adicionado com sucesso.`);
    router.push({ name: 'Home' });
  }).catch((error) => {
    if(error.response.status === 422) toast.error("Informe os dados corretamente.");
    else toast.error("Ocorreu um erro no servidor. Tente novamente mais tarde.");
  })
}

async function searchContactHandler(){
  if (state.name.length < 3)
    return

  state.isLoading = true;
  state.selectedUserId = null;
  state.listContact = {};

  await apiClient.get(`/api/user/search`, {name: state.name}).then((response) => {
    state.listContact = response.data;
  }).catch(() => toast.error("Falha ao carregar o usuario"));
  state.isLoading = false;
}
</script>
<template>
  <div class="bg-white rounded-2xl shadow-2xl w-96 py-10 justify-center px-6 lg:w-1/3 lg:px-8">
   <ReturnBtn />
    <h1 class="text-gray-900 tracking-tight font-bold text-2xl text-center mb-3">Adicionar contato</h1>
      <form @submit.prevent="addContact" class="flex flex-col p-2" type="multipart/form-data">
        <input id="name" v-model="state.name" @input="searchContactHandler" type="text" placeholder="Pesquisar contato..." />
        <select v-if="state.listContact.length > 0" v-model="state.selectedUserId"
            class="p-2 bg-gray-100 rounded-md m-2 border-0 text-gray-900 shadow-inner ring-1 ring-gray-300 sm:text-sm sm:leading-6"
        >
          <option value="" disabled selected>Selecione um usuário</option>
          <option v-for="user in state.listContact" :key="user.id" :value="user.id">
            {{ user.name }}
          </option>
        </select>
        <button id="submit-btn" type="submit">Adicionar</button>
      </form>
  </div>
</template>
<style scoped>
form input {
  @apply p-2 bg-gray-100 rounded-md m-2 border-0
  text-gray-900 placeholder:text-gray-500 shadow-inner ring-1 ring-gray-300 sm:text-sm sm:leading-6
}
form button{
  @apply p-2 bg-blue-600 rounded-md mx-2 mt-4 mb-7 hover:bg-blue-500 leading-6 shadow-inner text-white transition duration-200
}
</style>
