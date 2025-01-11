<script setup>
import {computed, ref} from "vue";

const props = defineProps({
  image: String,
})
const img = computed(() => {
  if(props.image) return 'http://localhost:8000/storage/'+props.image;
  else return 'http://localhost:8000/storage/nophoto.png';
})
</script>
<template>
  <img :src="img" alt="" class="rounded-full" />
</template>
