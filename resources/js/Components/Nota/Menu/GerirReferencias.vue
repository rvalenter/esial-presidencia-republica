<script setup>
import {onMounted, ref, watch} from 'vue';
import form_notes from "@/Services/Notes/form_notes.js";

const props = defineProps({
  proposicao: {
    type: Object,
    required: true
  },
});
const referenceEdit = ref('');
const referenceEditId = ref(null);
const references = ref([]);
const showReferenceList = ref(false);
const btnShowReferenceList = ref(true);
const btnSaveReference = ref(false);
const bundle = ref({});
const emits = defineEmits(['updateReference']);

function setReferences() {
  references.value = [];

  Object.values(props.proposicao.nota_tecnica).map((nota) => {
    references.value.push(nota);
  });
}

function setReference(id) {
  referenceEdit.value = references.value.find((reference) => reference.id === id).referencias.referencia;
  referenceEditId.value = id;
  showReferenceList.value = false;
  btnShowReferenceList.value = false;
  btnSaveReference.value = false;

  bundle.value = {
    reference: referenceEdit.value,
    referenceId: referenceEditId.value
  };
}

function searchReference() {
  bundle.value = {};
  btnSaveReference.value = true;
  setReferences();

  references.value = references.value.filter((reference) => {
    return reference.referencias.referencia.toLowerCase().includes(referenceEdit.value.toLowerCase());
  });

  if (references.value.length > 0) {
    showReferenceList.value = true;
    btnShowReferenceList.value = true;
  }
}

function cancelReference() {
  referenceEdit.value = '';
  referenceEditId.value = null;
  btnShowReferenceList.value = true;
  setReferences();
}

function storeReference() {
  form_notes.storeReference({proposicaoId: props.proposicao.id, referece: referenceEdit.value})
    .then(() => {
      emits('updateReference');
    })
    .then(() => {
      referenceEdit.value = '';
      referenceEditId.value = null;
      btnShowReferenceList.value = true;
      setReferences();
    });
}

watch(() => props.proposicao, () => {
  setReferences();
}, {immediate: true});

onMounted(() => {
  setReferences();
});
</script>

<template>
  <div class="w-full h-full flex justify-center p-4">
    <div class="w-full">
      <div>
        <h1 class="text-2xl font-bold">
          Referências
        </h1>
        <p class="text-sm text-gray-200">
          Adicione, exclua ou modifique referências para a sua nota técnica
        </p>
      </div>
      <div class="mt-4 flex">
        <input
          v-model="referenceEdit"
          :class="[showReferenceList && references.length > 0 ? 'rounded-tl-lg border-b' : 'rounded-l-lg shadow-lg']"
          class="w-full text-gray-600 px-4 h-10 border-0 focus:ring-0"
          type="text"
          @keyup="searchReference"
        >
        <button
          v-if="btnShowReferenceList && references.length !== 0"
          :class="[showReferenceList && references.length > 0 ? 'rounded-tr-lg' : 'rounded-r-lg shadow-lg']"
          class="h-10 w-10 bg-gray-700 text-white hover:bg-gray-600"
          @click="showReferenceList = !showReferenceList"
        >
          <i
            v-if="!showReferenceList || references.length === 0"
            class="fa-solid fa-chevron-down"
          />
          <i
            v-if="showReferenceList && references.length > 0"
            class="fa-solid fa-chevron-up"
          />
        </button>
        <button
          v-if="!btnShowReferenceList || references.length === 0"
          class="h-10 w-10 bg-gray-400 text-gray-700 hover:bg-gray-300"
          @click="cancelReference"
        >
          <i class="fa-solid fa-xmark" />
        </button>
        <button
          v-if="!btnShowReferenceList"
          class="h-10 w-10 bg-red-500 text-white hover:bg-red-400"
          @click="showReferenceList = !showReferenceList"
        >
          <i class="fa-regular fa-trash-can" />
        </button>
        <button
          v-if="(!btnShowReferenceList && btnSaveReference) || (references.length === 0 && btnSaveReference)"
          :class="[showReferenceList && references.length !== 0 ? 'rounded-tr-lg' : 'rounded-r-lg shadow-lg']"
          class="h-10 w-10 bg-green-500 text-gray-700 hover:bg-green-400"
          @click="storeReference"
        >
          <i class="fa-regular fa-floppy-disk" />
        </button>
      </div>
      <Transition>
        <div
          v-if="showReferenceList"
          class="text-gray-700 w-full shadow-lg divide-y-2 divide-gray-300 overflow-y-auto max-h-36 rounded-b-xl"
        >
          <div
            v-for="(reference, index) in references"
            :key="reference.id"
            :class="[index === references.length - 1 ? 'rounded-b-lg' : '']"
            class="w-full h-10 bg-white cursor-pointer hover:bg-gray-200 flex items-center px-4"
            @click="setReference(reference.id)"
          >
            {{ reference.referencias.referencia }}
          </div>
        </div>
      </Transition>
    </div>
  </div>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
