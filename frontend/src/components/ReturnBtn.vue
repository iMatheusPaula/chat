<script setup lang="ts">
import {useRouter} from "vue-router";
const router = useRouter();
</script>

<template>
  <button @click="router.back()" class="mb-7 text-black transition duration-200 flex flex-row">
    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
      <path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/>
    </svg>
    Voltar
  </button>
</template>