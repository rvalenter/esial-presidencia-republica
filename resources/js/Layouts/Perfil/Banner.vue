<script setup>
import get from "@/Services/User/get_user.js";
import form from "@/Services/User/form_user.js";
import {onMounted, ref} from "vue";
import parses from "@/Hooks/parses.js";

const props = defineProps({
    user: Object,
    id: Number,
});
const btnChangePhoto = ref(false);
const img = ref('');

function name(name) {
    return parses.name(name);
}

async function getPhoto() {
    await get.fetchPhoto(props.id).then(res => {
        img.value = 'data:image/png;base64,' + res.data.data.photo;
    });
}

async function changePhoto(event) {
    if (event) {
        await form.photoUpdate({photo: event.target.files[0]}).then(() => {
            getPhoto();
        });
    }
}

onMounted(() => {
    getPhoto();
});
</script>

<template>
  <div class="w-full h-44 bg-[url('../../public/src/img/palacio-fachada.png')] bg-cover bg-bottom bg-opacity-50 bg-no-repeat flex justify-between rounded-xl pl-10 pr-28">
    <div class="flex items-center">
      <div>
        <p class="text-6xl font-black text-white subpixel-antialiased drop-shadow-xl">
          {{ props.user.nome != null ? name(props.user.nome) : props.user.cpf }}
        </p>
      </div>
    </div>
    <div
      class="bg-[url('../../public/src/img/bg-avatar.jpg')] image-container rounded-full h-44 w-44 relative top-16 border-8 border-gray-100 bg-cover bg-center bg-no-repeat flex items-center justify-center"
      @mouseleave="btnChangePhoto = false"
      @mouseover="btnChangePhoto = true"
    >
      <button
        v-show="btnChangePhoto"
        class="text-white font-bold text-xl z-50 absolute inset-0 "
        :class="[btnChangePhoto ? 'bg-black/40' : '']"
        @click="changePhoto()"
      >
        <p class="relative top-16">
          Alterar
        </p>
        <input
          type="file"
          class="w-44 h-44 opacity-0"
          @change="changePhoto($event)"
        >
      </button>
      <img
        :src="img"
        class="absolute inset-0 z-10"
      >
    </div>
  </div>
</template>

<style scoped>
.image-container {
    overflow: hidden;
    position: relative;
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
