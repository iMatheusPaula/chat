<script setup>
import {computed, onMounted, reactive} from "vue";
import apiClient from "@/services/apiClient.js";
import {useToast} from "vue-toastification";
import {useRoute, useRouter} from "vue-router";
import IconLoading from "@/components/IconLoading.vue";

const route = useRoute();
const router = useRouter();
const toast = useToast();
const state = reactive({
  name: '',
  email: '',
  phone: '',
  image: null,
  isLoading: false
})

function handleFileUpload(event){
  state.image = event.target.files[0];
}

async function getUser() {
  state.isLoading = true;
  await apiClient.get(`/api/user/${auth.user.id}`).then((response) => {
    state.user = response.data;
  }).catch(() => toast.error("Falha ao carregar o usuario"));
  state.isLoading = false;
}

async function updateUser(){
  state.isLoading = true;
  await apiClient.p(`/api/user`, {
    name: state.name,
    email: state.email,
    phone: state.phone,
    image: state.image ?? ''
  }, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  }).then(() => {
    toast.success('Perfil atualizado com sucesso.');
    router.back();
  })
      .catch((error) => {
        if(error.response.status === 404) toast.error("Usuário não encontrado.");
        else toast.error("Ocorreu um erro no servidor. Tente novamente mais tarde.");
      })
  state.isLoading = false;
}

onMounted(getUser);
</script>
<template>
  <div v-if="state.isLoading">
    <IconLoading />
  </div>
  <div v-else class="bg-white rounded-2xl shadow-2xl w-96 lg:w-1/3 justify-center px-6 py-20 lg:px-8">
    <h1 class="text-gray-900 tracking-tight font-bold text-2xl text-center mb-3">Editar Perfil</h1>
    <form @submit.prevent="updateUser" class="flex flex-col p-2" type="multipart/form-data">
      <input id="name" v-model="state.name" type="text" placeholder="Nome" />
      <input id="phone" v-model="state.phone" type="text" placeholder="Telefone" />
      <input id="email" v-model="state.email" type="text" placeholder="E-mail" />
      <input id="image" type="file" @change="handleFileUpload" placeholder="Foto" />
      <div class="w-full flex flex-row">
        <button id="submit-btn" type="submit">Salvar</button>
        <button id="cancel-btn" type="button" @click="router.back()">Cancelar</button>
      </div>
    </form>
  </div>
</template>
<style scoped>
form input {
  @apply p-2 bg-gray-100 rounded-md m-2 border-0
  text-gray-900 placeholder:text-gray-500 shadow-inner ring-1 ring-gray-300 sm:text-sm sm:leading-6
}
form button {
  @apply p-2 bg-blue-600 rounded-md mx-2 mt-4 hover:bg-blue-500 shadow-inner text-white transition duration-200 w-full
}
</style>
