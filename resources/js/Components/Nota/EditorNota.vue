<script setup>
import { ref, watch } from 'vue';
import { useStore } from 'vuex';
import TextEditor from '@/Components/Global/textEditor.vue';
import { debounce } from 'lodash';

const store = useStore();
const content = ref('');
const worker = ref(null);
const saveProcessing = ref(false);
const props = defineProps({
  notaTecnica: {
    type: Object,
    default: null
  },
  working: {
    type: Number,
    default: 0
  },
  versionSelected: {
    type: Number,
    default: 0
  },
  blocked: {
    type: Boolean,
    default: false
  }
});
const emits = defineEmits(['saved', 'edited']);
const exposeStoreNotes = (arg) => {
  if (arg === 1) {
    storeNotes()
    return
  }

  aiHelp()
}


defineExpose({
  exposeStoreNotes
})

function storeNotes() {
  saveProcessing.value = true

  store.dispatch('nota/storeNotes', {
    id: props.notaTecnica.id,
    text: content.value,
    working: props.working
  }).then(() => {
    emits('saved', props.notaTecnica.id)
  })
}

function autoSave() {
  store.dispatch('nota/autoSave', {
    id: worker.value,
    text: content.value,
    working: props.working,
    nt: props.notaTecnica.id
  }).then(() => {
    if (worker.value === null) {
      emits('saved', props.notaTecnica.id)
    }
  });
}

function setLastContent(arr, version) {
  worker.value = arr.filter(item => item.id === version).pop()

  return worker.value.conteudo
}

watch(() => [props.working, props.notaTecnica, props.versionSelected], ([newWorking, newNt, newVersion]) => {
  if (newWorking === 2 && newNt) {
    content.value = props.notaTecnica.criticos.length > 0 ? setLastContent(props.notaTecnica.criticos, newVersion) : worker.value = null
    return
  }

  if (newWorking === 3 && newNt) {
    content.value = props.notaTecnica.meritos.length > 0 ? setLastContent(props.notaTecnica.meritos, newVersion) : worker.value = null
    return
  }

  if (newWorking === 4 && newNt) {
    content.value = props.notaTecnica.conclusoes.length > 0 ? setLastContent(props.notaTecnica.conclusoes, newVersion) : worker.value = null
  }
})

watch(() => [props.versionSelected, props.working, props.notaTecnica], (newParams, oldParams) => {

  if ((newParams[0] !== oldParams[0] && newParams[2] !== null && oldParams[2] !== null)
    && newParams[1] === oldParams[1]
    && !saveProcessing.value) {
    if (newParams[2] === oldParams[2]) emits('edited', content.value)
  }
})

watch(() => props.versionSelected, (newVersion) => {
  if (newVersion !== 0) {
    saveProcessing.value = false
  }
})

const autosavedebounce = debounce(autoSave, 1000)

watch(() => content.value, () => {
  autosavedebounce()
})
</script>

<template>
  <div class="w-full h-full flex items-center justify-center">
    <text-editor
      v-model="content"
      :blocked="blocked"
      @keyup="emits('edited', content)"
    />
  </div>
</template>

<style scoped>
</style>
