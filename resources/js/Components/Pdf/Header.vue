<script setup>
import {ref} from "vue";

const logo = ref('../src/img/sri.png');
const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  title: {
    type: String,
    required: true
  }
});

</script>

<template>
  <div
    v-show="show"
    class="w-full col-span-5"
  >
    <div class="border-b-2 border-gray-300 w-full flex justify-between items-center pb-3">
      <div>
        <p class="text-4xl font-bold">
          {{ title }}
        </p>
      </div>
      <div>
        <img
          :src="logo"
          alt="logo"
          class="w-32"
        >
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
