<script setup>

import {ref, watch} from "vue";

const input = ref('');
const props = defineProps({
    placeholder: {
        type: String,
        required: true,
    },
    autocomplete: {
        type: Object,
        required: true
    }
});
const emits = defineEmits([
    "input"
]);

watch(() => input.value, () => {
    emits("input", input.value)
})

function setInput(arg) {
    input.value = arg
}
</script>

<template>
  <div class="w-full">
    <input
      v-model="input"
      type="text"
      class="input"
      :placeholder="props.placeholder"
    >
    <div>
      <ul>
        <li
          v-for="(arg, index) in autocomplete"
          :key="index"
          class="cursor-pointer hover:text-sky-800 bg-gray-50 h-10 flex justify-start items-center px-3 border-b"
          @click="setInput(arg.nome_cargo)"
        >
          <p>{{ arg.nome_cargo }}</p>
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped>
.input {
    @apply w-full bg-gray-200 rounded-lg shadow-md px-4 border border-gray-300/70 focus:ring-0 focus:border-2 focus:border-blue-400 focus:bg-gray-100
}
</style>
