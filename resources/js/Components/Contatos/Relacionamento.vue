<script setup>
import {onMounted, ref, watch} from "vue";

const props = defineProps({
  contato: {
    type: Object,
    required: true
  },
  defaultContact: {
    type: Boolean,
    default: false
  }
});
const img = ref('');

function setPhoto() {
  if (props.contato.get_photo) {
    img.value = 'data:image/png;base64,' + props.contato.get_photo.foto;
  }
}

function setFirstAndLastName() {
  if (!props.contato.nome) return;

  const name = props.contato.nome.split(' ');
  return name[0] + ' ' + name[name.length - 1];
}

onMounted(() => {
  setPhoto();
});

watch(() => props.contato, () => {
  img.value = '';
  setPhoto();
});
</script>

<template>
  <div
    :class="[defaultContact ? 'bg-sky-700 text-white' : 'bg-gray-200 hover:text-white hover:bg-sky-700']"
    class="w-full max-w-96 min-h-24 ring-1 ring-gray-300 shadow-xl rounded-lg p-3 grid grid-cols-5 gap-4 hover:scale-105 transition delay-150 duration-300 ease-in-out"
  >
    <div>
      <div
        class="col-span-2 w-10 h-10 bg-cover bg-bottom bg-opacity-50 bg-no-repeat flex justify-start rounded-full ring-1 "
        :class="[defaultContact ? 'ring-white' : 'ring-sky-900']"
      >
        <div
          class="bg-[url('../../public/src/img/bg-avatar.jpg')] image-container rounded-full w-10 h-10 bg-cover bg-center bg-no-repeat flex items-center justify-center"
        >
          <img
            :src="img"
            class="absolute inset-0 z-10"
          >
        </div>
      </div>
    </div>
    <div class="col-span-4">
      <div>
        <p class="font-bold text-lg">
          {{ setFirstAndLastName(props.contato.nome) }}
        </p>
        <p class="text-xs">
          {{ props.contato.cargo }} - {{ props.contato.organizacao }}
        </p>
        <p class="text-xs">
          {{ props.contato.email }}
        </p>
        <p class="text-xs">
          {{ props.contato.celular }}
        </p>
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
