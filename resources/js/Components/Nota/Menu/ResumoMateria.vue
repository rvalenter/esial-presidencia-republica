<script setup>
import {onMounted, watch, ref} from "vue";
import get_notes from "@/Services/Notes/get_notes.js";
import SpinnerLarge from "@/Components/Global/SpinnerLarge.vue";
import Error from "@/Components/Global/Error.vue";

const props = defineProps({
  proposicao: {
    type: Object,
    required: true
  },
});
const summary = ref([]);
const loaded = ref(false);
const errorLoad = ref(false);

function getSummary(id) {
  loaded.value = true;
  errorLoad.value = false;

  get_notes.fetchSummary(id).then((response) => {
    summary.value = response.data.data;
    loaded.value = false;
  }).catch((error) => {
    console.log(error);
    loaded.value = false;
    errorLoad.value = true;
  });
}

watch(() => props.proposicao, (newVal) => {
  getSummary(newVal.id);
});

onMounted(() => {
  getSummary(props.proposicao.id);
});
</script>

<template>
  <div
    v-if="!loaded"
    class="text-justify text-pretty leading-loose text-sm"
    v-html="summary"
  />
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
