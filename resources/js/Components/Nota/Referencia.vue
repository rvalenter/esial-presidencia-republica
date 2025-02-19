<script setup>
import { computed, ref, watch } from 'vue'
import {useStore} from 'vuex'
import parses from "@/Hooks/parses.js";

const store = useStore();
const props = defineProps({
  references: {
    type: Array || Object,
    required: false,
    default: []
  },
  referenceSelected: {
    type: Number,
    default: null
  },
  notaTecnica: {
    type: Object,
    default: null
  }
});
const emits = defineEmits(['updateReferenceList', 'updateReference']);
const selected = computed(() => store.state.references.selected);
const create = computed(() => store.state.references.create);
const newReferenceName = computed(() => store.state.references.newReferenceName);
const referenceToUpdate = computed(() => store.state.references.referenceToUpdate);
const showMenu = computed(() => store.state.references.showMenu);
const pdfFile = ref(null);

watch(() => props.referenceSelected, (newReference) => {
  store.commit('references/setSelected', newReference);
});

watch(() => props.references, (newReferences) => {
  if (newReferences.length > 0) {
    store.commit('references/setSelected', newReferences[0].id);
  }
});

function destroyReference() {
  store.dispatch('references/destroyReference', props.notaTecnica.id).then(() => {
    emits('updateReferenceList');
  });
}

function storeReference() {
  store.dispatch('references/storeReference', {
    id: props.notaTecnica.proposicoes.id,
    type: newReferenceName.value || 0,
    referenceToUpdate: referenceToUpdate.value,
    pdfFile: pdfFile.value
  }).then(response => {
    emits('updateReferenceList');
  });
}

function inputPdf(event) {
  pdfFile.value = event.target.files[0];
}
</script>

<template>
  <div class="flex">
    <div class="flex justify-end items-end gap-4">
      <label
        for="referencia"
        class="text-sm relative top-0.5"
      >Nota Técnica:</label>
      <select
        v-if="!create"
        id="referencia"
        class="h-7 w-48 p-0 px-4 text-sm font-semibold border-t-0 border-r-0 border-l-0 focus:ring-0"
        @change="store.commit('references/setSelected', $event.target.value), emits('updateReference', selected)"
      >
        <option
          v-for="reference in references.nota_tecnica"
          :key="reference.id"
          :value="reference.id"
        >
          {{ reference.id }} - {{ parses.date(reference.data_referencia) }}
        </option>
      </select>
      <div
        v-if="create"
        class="flex"
      >
        <select
          class="w-52 h-6 p-0 px-2 text-xs border-t-0 border-r-0 border-l-0 focus:ring-0"
          @change="store.commit('references/setNewReferenceName', $event.target.value)"
        >
          <option value="0">
            Nota Técnica Manual
          </option>
          <option value="1">
            Nota Técnica SEI
          </option>
        </select>
        <div
          v-if="newReferenceName == 1"
          class="h-6"
        >
          <button
            class="h-6 w-6 rounded-full shadow-md text-white ml-1.5 bg-sky-800 hover:bg-blue-500"
            title="Anexar Nota Tecnica SEI"
          >
            <i class="fa-solid fa-paperclip fa-xs" />
            <input
              type="file"
              class="w-full h-full rounded-full relative -top-6 opacity-0"
              @change="inputPdf"
            >
          </button>
        </div>
      </div>
    </div>
    <button
      v-if="!showMenu"
      class="h-6 w-6 bg-sky-800 hover:bg-sky-700 rounded-full shadow-md text-white ml-1.5"
      title="Exibir Menu"
      @click="store.commit('references/setShowMenu', true)"
    >
      <i class="fa-solid fa-ellipsis-vertical" />
    </button>
    <button
      v-if="!create && showMenu"
      class="h-6 w-6 bg-red-500 hover:bg-red-400 rounded-full shadow-md text-white ml-2"
      title="Apagar Nota Técnica"
      @click="destroyReference"
    >
      <i class="fa-regular fa-trash-can fa-xs" />
    </button>
    <button
      v-if="!create && showMenu"
      class="h-6 w-6 bg-sky-800 hover:bg-sky-700 rounded-full shadow-md text-white ml-1.5"
      title="Criar nova referência"
      @click="store.commit('references/setCreate', true)"
    >
      <i class="fa-solid fa-plus fa-xs" />
    </button>
    <button
      v-if="create && showMenu"
      class="h-6 w-6 bg-gray-600 hover:bg-gray-500 rounded-full shadow-md text-white ml-1.5"
      title="Cancelar nova alterações"
      @click="store.commit('references/setCreate', false), store.commit('references/setReferenceToUpdate', null)"
    >
      <i class="fa-solid fa-xmark fa-xs" />
    </button>
    <button
      v-if="create && showMenu && (newReferenceName == 0 || (newReferenceName == 1 && pdfFile))"
      class="h-6 w-6 bg-green-600 hover:bg-green-500 rounded-full shadow-md text-white ml-1.5"
      title="Salvar nova referência"
      @click="storeReference"
    >
      <i class="fa-regular fa-floppy-disk fa-xs" />
    </button>
  </div>
</template>

<style scoped>

</style>
