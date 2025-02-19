<script setup>
import {onMounted, ref, watch} from "vue";
import get_notes from "@/Services/Notes/get_notes.js";
import SpinnerLarge from "@/Components/Global/SpinnerLarge.vue";
import Error from "@/Components/Global/Error.vue";

const props = defineProps({
  proposicao: {
    type: Object,
    required: true
  }
});
const texts = ref([]);
const link = ref(null);
const loaded = ref(false);
const errorLoad = ref(false);

function getText(id) {
  loaded.value = true;
  errorLoad.value = false;

  get_notes.fetchTexts(id).then((response) => {
    texts.value = response.data.data.text ?? response.data.data[0];
    link.value = response.data.data.link;
    loaded.value = false;
  }).catch((error) => {
    loaded.value = false;
    errorLoad.value = true;
  });
}

watch(() => props.proposicao, (newVal) => {
  getText(newVal.id);
});

onMounted(() => {
  getText(props.proposicao.id);
});
</script>

<template>
  <div class="w-full flex justify-end pr-4 pt-4 -mb-12">
    <div
      v-if="!loaded"
      class="flex justify-start items-center"
    >
      <a
        :href="link"
        target="_blank"
        class="px-2 py-1 rounded-full bg-white text-sky-700 cursor-pointer"
      >
        <p class="text-sm text-center">Link do Texto</p>
      </a>
    </div>
  </div>
  <div
    v-if="!loaded"
    class="leading-loose text-justify text-sm"
  >
    <div v-html="texts" />
  </div>

  <div
    v-if="loaded"
    class="w-full h-full flex justify-center items-center"
  >
    <SpinnerLarge />
  </div>
  <Error v-if="errorLoad" />
</template>

<style scoped>

</style>
