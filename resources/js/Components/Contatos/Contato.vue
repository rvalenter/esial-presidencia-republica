<script setup>
import {onMounted, ref, watch} from "vue";

const props = defineProps({
  contato: {
    type: Object,
    required: true
  },
  selected: {
    type: Boolean,
    default: false
  },
  type: {
    type: Number,
    required: true
  }
});
const emits = defineEmits(['setId', 'selectedCard']);
const img = ref('');
const selectedCard = ref(false);

function setPhoto() {
  if (props.contato.get_photo) {
    img.value = 'data:image/png;base64,' + props.contato.get_photo.foto;
  }
}

watch(selectedCard, (newSelectedCard) => {
  emits('selectedCard', {
    newSelectedCard: newSelectedCard,
    id: props.contato.id,
    nome: props.contato.nome,
    foto: props.contato.get_photo,
    cargo: props.contato.cargo,
    organizacao: props.contato.organizacao,
  });
});

onMounted(() => {
  setPhoto();
});
</script>

<template>
  <div
    :class="[props.selected ? 'bg-sky-700 text-white' : 'bg-white', $page.props.auth.isMobile ? 'min-h-14' : 'min-h-28']"
    class="w-full rounded-lg border border-gray-200 shadow-md p-2 hover:bg-gray-100 hover:text-gray-700 grid grid-cols-12 gap-4 cursor-pointer mb-4"
    @click="emits('setId', props.contato.id)"
  >
    <div
      class="col-span-2 2xl:w-14 md:w-10 md:h-10 2xl:h-14 bg-cover bg-bottom bg-opacity-50 bg-no-repeat flex justify-start rounded-xl "
    >
      <div
        class="bg-[url('../../public/src/img/bg-avatar.jpg')] image-container rounded-full md:h-10 md:w-10 2xl:w-12 2xl:h-12 bg-cover bg-center bg-no-repeat flex items-center justify-center  border-2"
        :class="[props.selected ? 'border-white' : 'border-sky-700']"
      >
        <img
          :src="img"
          class="absolute inset-0 z-10"
        >
      </div>
    </div>
    <div :class="props.type == 1 ? 'col-span-9' : 'col-span-10' ">
      <div>
        <p :class="[$page.props.auth.isMobile ? 'ml-4 text-sm mt-4' : 'text-lg']">
          {{ props.contato.nome }}
        </p>
        <p
          v-if="!$page.props.auth.isMobile "
          class="mt-0.5 md:text-[11px] 2xl:text-xs"
        >
          {{ props.contato.cargo }} - {{ props.contato.organizacao }}
        </p>
        <p
          v-if="!$page.props.auth.isMobile "
          class="mt-0.5 md:text-xs 2xl:text-sm"
        >
          {{ props.contato.email }}
        </p>
        <p
          v-if="!$page.props.auth.isMobile "
          class="mt-0.5 md:text-xs 2xl:text-sm"
        >
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
