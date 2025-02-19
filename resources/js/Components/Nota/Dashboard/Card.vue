<script setup>
import parses from "@/Hooks/parses.js";

const props = defineProps({
  card: {
    type: Number,
    default: 0
  },
  cardName: {
    type: String,
    default: ''
  },
  actived: {
    type: Boolean,
    default: false
  }
});
const emits = defineEmits(['updateCard']);

const getColorClass = (type) => {
  const colors = {
    'CONTRÁRIO': 'border-red-500',
    'FAVORÁVEL': 'border-green-500',
    'CONTRÁRIO COM AJUSTES': 'border-yellow-500',
    'FAVORÁVEL COM AJUSTES': 'border-blue-500',
    'FORA DE COMPETÊNCIA': 'border-gray-500',
    'MATÉRIA PREJUDICADA': 'border-orange-500',
    'NADA A OPOR': 'border-cyan-500',
    'PERDA DE EFICÁCIA': 'border-purple-500',
  };

  return colors[type] || 'border-white';
};

function setCard() {
  emits('updateCard', props.cardName);
}
</script>

<template>
  <div
    :class="[actived ? 'scale-110 bg-sky-900 shadow-2xl ': 'bg-sky-700 shadow-lg', getColorClass(cardName)]"
    class="cursor-pointer border-l-8 w-full h-20 bg-opacity-90 rounded-sm py-2 flex justify-between transition duration-300 ease-in-out"
    @click="setCard"
  >
    <div class="px-2 w-full h-full grid grid-rows-2 grid-flow-col">
      <div class="w-full flex justify-end items-start">
        <p class="font-light text-3xl text-white">
          {{ card }}
        </p>
      </div>
      <div class="w-full flex justify-start items-end">
        <p class="text-sm text-white">
          {{ parses.title(cardName) }}
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
