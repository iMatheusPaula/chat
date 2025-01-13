<script setup>
import apiClient from '@/services/apiClient.js';
import {onMounted, reactive} from "vue";
import ContactCard from "@/components/ContactCard.vue";
import {useToast} from "vue-toastification";
import IconLoading from "@/components/IconLoading.vue";

const toast = useToast();
const state = reactive({
  contacts: [],
  isLoading: false
});

async function showContacts(){
  state.isLoading = true;
  await apiClient.get('/api/contact/index').then((response) => {
    state.contacts = response.data;
  }).catch((error) => {
    toast.error("Erro ao carregar os contatos.");
  })
  state.isLoading = false;
}

onMounted(showContacts);
</script>
<template>
  <div v-if="state.isLoading">
    <IconLoading />
  </div>
  <div v-else class="w-96 py-10 justify-center px-6 lg:w-2/6 lg:px-8">
      <div>
        <RouterLink to="/addContact">
          <button class="bg-black text-white rounded-md px-3 py-2.5 text-center inline-flex items-center hover:bg-gray-800 transition duration-200">
            <svg class="w-5 h-5 me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#e8eaed">
              <path d="M720-400v-120H600v-80h120v-120h80v120h120v80H800v120h-80Zm-360-80q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm80-80h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0-80Zm0 400Z"/>
            </svg>
            Adicionar contato
          </button>
        </RouterLink>
      </div>
      <div v-if="state.contacts.length === 0">
        <div class="bg-white rounded-xl shadow-sm p-5 my-3">
          <h1 class="text-lg text-gray-900">Nenhum contato encontrado.</h1>
        </div>
      </div>
      <div v-else>
        <ContactCard
            v-for="contact in state.contacts"
            :key="contact.id"
            :contact="contact"
        />
      </div>
  </div>
</template>
