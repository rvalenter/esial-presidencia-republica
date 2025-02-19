<script setup>
import {onMounted, ref, watch} from "vue";
import get_contact from "@/Services/Contact/get_contact.js";
import SpinnerLarge from "@/Components/Global/SpinnerLarge.vue";
import form_contact from "@/Services/Contact/form_contact.js";
import Spinner from "@/Components/Global/Spinner.vue";

const props = defineProps({
  form: {
    type: Object,
    required: true
  }
});
const filter = ref('');
const speeches = ref([]);
const showSpinner = ref(false);
const openSpeech = ref(false);
const speechText = ref('');
const speechData = ref('');
const speechAnalyse = ref('');
const showSpinnerAnalyse = ref(false);

function openSpeechText(text, data) {
  speechText.value = text;
  speechData.value = data;
  openSpeech.value = true;
  speechAnalyse.value = '';
}

function analisarTexto() {
  showSpinnerAnalyse.value = true;

  form_contact.analyseSpeeches(speechText.value).then(res => {
    speechAnalyse.value = res.data.data;
    showSpinnerAnalyse.value = false;
  });
}

function getSpeeches() {
  let type = props.form.role === 'Deputado Federal' ? '1' : '2';
  let id = props.form.get_parlamentar;
  showSpinner.value = true;
  openSpeech.value = false;
  speechText.value = '';
  speechData.value = '';
  speechAnalyse.value = '';

  get_contact.fetchSpeeches({
    type: type,
    id: id
  }).then(res => {
    speeches.value = res.data.data;
    showSpinner.value = false;
  });
}

function filterSpeches() {
  if (!filter.value) return speeches.value;

  function normalize(str) {
    return str.replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
  }

  const normalizedFilter = normalize(filter.value);

  return speeches.value.filter(pp => {
    const normalizedSummary = normalize(pp.resumo);

    return normalizedSummary.includes(normalizedFilter);
  });
}

onMounted(() => {
  getSpeeches();
});

watch(() => props.form, () => {
  getSpeeches();
});
</script>

<template>
  <div
    v-if="!openSpeech"
    class="w-full flex justify-between items-center h-14 mb-2"
  >
    <input
      v-model="filter"
      type="search"
      class="h-12 p-0 px-4 w-full border-t-0 border-r-0 border-l-0 border-b-2 border-gray-300 focus:ring-0 focus:border-sky-500 bg-gray-100"
      placeholder="Buscar"
    >
  </div>
  <div class="h-full overflow-y-auto">
    <div v-if="(!showSpinner && !openSpeech)">
      <div
        v-for="(speeche, index) in filterSpeches()"
        :key="index"
        class="w-full flex cursor-pointer group px-2 min-h-24 hover:bg-gray-200/60"
      >
        <div class="w-full grid grid-cols-12 py-4 text-sm border-b border-gray-400">
          <div class="col-span-2 h-full flex items-center justify-start pl-2">
            <div class="text-center">
              <p class="text-xs font-light">
                {{ speeche.data }}
              </p>
            </div>
          </div>
          <div class="col-span-9 h-full flex items-center justify-start px-4 text-justify ">
            <p>{{ speeche.resumo }}</p>
          </div>
          <div class="col-span-1 flex justify-center items-center">
            <button
              class="hover:text-sky-700"
              @click="openSpeechText(speeche.texto, speeche.data)"
            >
              <i class="fa-solid fa-envelope-open-text fa-lg" />
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-if="openSpeech">
      <div class="w-full flex justify-between items-center mb-6 mt-4 px-2">
        <div>
          <h2 class="text-center text-xl font-bold">
            Discurso
          </h2>
        </div>
        <div class="flex gap-4 items-center">
          <button
            class="bg-sky-700 hover:bg-sky-700/70 text-white text-xs px-2 py-1 rounded-full flex justify-between items-center"
            @click="analisarTexto()"
          >
            Analisar Texto - IA
            <spinner
              v-if="showSpinnerAnalyse"
              class="relative left-4"
              size="w-4 h-4"
              top="-top-4"
            />
          </button>
          <button
            class="bg-gray-500/50 hover:bg-gray-700/50 text-xs px-2 py-1 rounded-full"
            @click="openSpeech = false"
          >
            <i class="fa-solid fa-times fa-lg" />
          </button>
        </div>
      </div>
      <div class="text-justify  px-2">
        <div
          v-if="speechAnalyse !== '' "
          class="rounded-lg p-2 bg-sky-700 text-white mb-6"
        >
          <p class="mb-2 font-semibold">
            Análise:
          </p>
          <p
            v-for="(analyse, index) in speechAnalyse"
            :key="index"
          >
            {{ analyse }}
          </p>
        </div>
        <div>{{ speechText }}</div>
        <div class="w-full flex justify-end mt-4 font-bold">
          {{ speechData }}
        </div>
      </div>
    </div>
    <div>
      <p
        v-if="speeches.length === 0 && !showSpinner"
        class="text-center text-gray-500"
      >
        Nenhum discurso encontrado
      </p>
    </div>
    <div
      v-if="showSpinner"
      class="h-full w-full flex justify-center items-center"
    >
      <SpinnerLarge />
    </div>
  </div>
</template>

<style scoped>

</style>
