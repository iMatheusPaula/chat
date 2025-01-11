<script setup>
import {useRouter} from "vue-router";
import Image from "@/components/Image.vue";

const router = useRouter();
const props = defineProps({
  contact: {type: Object, required: true}
});

function goToContactPage() {
  router.push({ name: 'ContactPage', params: { id: props.contact.id }})
}
</script>

<template>
    <div class="gap-4 flex bg-white rounded-xl shadow-sm p-5 my-3 cursor-pointer hover:bg-gray-100"
         @click="goToContactPage"
    >
      <div class="flex items-center">
        <Image :image="contact.image" class="h-20 w-20" />
      </div>
      <div class="flex flex-col justify-center">
        <h3 class="font-semibold text-gray-900">{{ contact.name }}</h3>
        <p class="text-sm text-gray-900">{{ contact.phone }}</p>
        <p class="text-sm text-gray-900">{{ contact.email }}</p>
      </div>
    </div>
</template>
