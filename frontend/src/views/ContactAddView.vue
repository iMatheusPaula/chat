<script setup>
import {reactive} from "vue";
import apiClient from "@/services/apiClient.js";
import {useToast} from "vue-toastification";
import {useRouter} from "vue-router";

const router = useRouter();
const toast = useToast();
const state = reactive({
  name: '',
  email: '',
  phone: '',
  image: null
})
function handleFileUpload(event){
  state.image = event.target.files[0];
}

async function addContact(){
  await apiClient.post('/api/contact/store', {
    name: state.name,
    email: state.email,
    phone: state.phone,
    image: state.image ?? ''
  }, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  }).then(() => {
    toast.success(`${state.name} foi criado com sucesso.`);
    router.push({ name: 'Home' });
  }).catch((error) => {
    if(error.response.status === 422) toast.error("Informe os dados corretamente.");
    else toast.error("Ocorreu um erro no servidor. Tente novamente mais tarde.");
  })
}
</script>
<template>
  <div class="bg-white rounded-2xl shadow-2xl w-96 py-10 justify-center px-6 lg:w-1/3 lg:px-8">
    <div @click="router.back()" class="mb-7 text-black transition duration-200 flex flex-row">
      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
        <path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/>
      </svg>
      Voltar
    </div>
    <h1 class="text-gray-900 tracking-tight font-bold text-2xl text-center mb-3">Adicionar contato</h1>
      <form @submit.prevent="addContact" class="flex flex-col p-2" type="multipart/form-data">
        <input id="name" v-model="state.name" type="text" placeholder="Nome" />
        <input id="phone" v-model="state.phone" type="text" placeholder="Telefone" />
        <input id="email" v-model="state.email" type="text" placeholder="E-mail" />
        <input id="image" type="file" @change="handleFileUpload" placeholder="Foto" />
        <button id="submit-btn" type="submit">Cadastrar</button>
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
