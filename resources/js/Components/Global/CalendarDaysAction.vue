<script setup>
import { ref } from 'vue';
import post_calendar from "@/Services/Calendar/post_calendar.js";

const props = defineProps({
    evento: {
        type: Object,
        required: true
    }
});
const emits = defineEmits(['destroyed', 'updateEvent']);

const destroy = (id, isOwner) => {
    post_calendar.destroy(id, isOwner).then(() => {
        emits('destroyed');
    });
}

const show = ref(false);
</script>

<template>
  <div
    class="flex justify-between items-center mb-1 py-2.5 px-2 shadow-lg w-full cursor-pointer border-l-4 text-gray-800"
    :class="[$page.props.auth.user.id == props.evento.user_id ? 'bg-sky-200 border-sky-800' : 'bg-green-200 border-green-800']"
  >
    <VTooltip>
      <a class="font-bold">{{ props.evento.titulo }}</a>

      <template #popper>
        {{ props.evento.descricao }}
      </template>
    </VTooltip>
    <div
      v-if="$page.props.auth.user.id == props.evento.user_id && $page.props.auth.access.includes(7)"
      class="flex gap-2 justify-between"
    >
      <button @click="destroy(props.evento.id, $page.props.auth.user.id == props.evento.user_id)">
        <i class="fa-regular fa-trash-can text-red-500" />
      </button>
      <button @click="emits('updateEvent', props.evento.id)">
        <i class="fa-solid fa-pen-to-square text-yellow-700" />
      </button>
    </div>
  </div>
</template>

<style scoped></style>
