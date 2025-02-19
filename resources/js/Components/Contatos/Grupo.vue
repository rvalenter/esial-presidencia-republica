<script setup>
import form_contact from "@/Services/Contact/form_contact.js";

const props = defineProps({
  group: {
    type: Object,
    required: true
  },
  active: {
    type: Boolean,
    default: false
  }
});
const emits = defineEmits(['selectedCard', 'setCardsId', 'destroyGroup']);

function setPhoto(contato) {
  if (contato.get_photo) {
    return 'data:image/png;base64,' + contato.get_photo.foto;
  }

  return '';
}

function destroyGroup(id) {
  form_contact.destroyGroup(props.group.id).then(() => {
    emits('destroyGroup');
  });
}

function selectedCard() {
  emits('selectedCard', {
    title: props.group.nome,
    contacts: props.group.contatos.map(contato => {
      return {
        id: contato.id,
        nome: contato.nome,
        foto: contato.photo_link,
        partido: contato.partido,
        cargo: contato.cargo,
        organizacao: contato.organizacao,
      };
    })
  });
}

</script>

<template>
  <div
    :class="[props.active ? 'bg-sky-700 text-white' : 'bg-white']"
    class="w-full min-h-16 rounded-lg border border-gray-200 shadow-md py-2 px-4 hover:bg-gray-100 hover:text-gray-700 grid grid-cols-1 gap-4 cursor-pointer mb-4 group"
    @click="selectedCard(); emits('setCardsId', props.group.id)"
  >
    <div class="flex justify-between items-center">
      <h1>{{ props.group.nome }}</h1>
      <!--      <button class="text-red-500 w-6 h-6 rounded-full bg-white" @click="destroyGroup" v-if="$page.props.auth.access.includes(12)">-->
      <!--        <i class="fa-sm fa-regular fa-trash-can"></i>-->
      <!--      </button>-->
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
