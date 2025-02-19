<script setup>
import {onMounted, ref} from "vue";

const props = defineProps({
  contato: {
    type: Object,
    required: true
  },
});
const emits = defineEmits(['removeUser']);
const img = ref("../src/img/bg-avatar.jpg");

function setPhoto() {
  if (props.contato.foto) {
    img.value = props.contato.foto.url_foto_x;
  }
}

onMounted(() => {
  setPhoto();
});
</script>

<template>
  <div class="w-full py-3 px-3 border border-gray-400 flex justify-between items-center rounded-lg bg-sky-700 hover:bg-sky-600 transition duration-150 ease-in-out hover:scale-110">
    <div>
      <p class="text-white font-bold">
        {{ props.contato.nome }}
      </p>
      <p class="text-white text-xs mb-1">
        {{ props.contato.partido.siglaPartidoUf }}
      </p>
      <p class="text-white text-xs">
        {{ props.contato.cargo }}
      </p>
    </div>
    <div>
      <div
        class="col-span-2 bg-cover bg-bottom bg-no-repeat flex justify-start rounded-xl"
      >
        <div
          class="bg-[url('../../public/src/img/bg-avatar.jpg')] border-2 border-white image-container rounded-full w-14 h-14 bg-cover bg-center bg-no-repeat flex items-center justify-center"
        >
          <img
            :src="img"
            class="absolute inset-0 z-10"
          >
        </div>
      </div>
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
