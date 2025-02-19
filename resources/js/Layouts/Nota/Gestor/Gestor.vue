<script setup>
import {ref} from "vue";
import TextEditor from "@/Components/Global/textEditor.vue";
import form_notes from "@/Services/Notes/form_notes.js";
import titles from "../../../../configs/titles.json";

const content = ref('');
const emits = defineEmits(['back', 'saved']);
const props = defineProps(['notaTecnica']);

const resetContent = () => {
  content.value = '';
}

function adjustsConfirm() {
    const confimed = window.confirm(titles.NotaTecnica.nt_adjusts_required);

    if (confimed) {
      sendAdjusts();
    }
}

const sendAdjusts = () => {
  if (props.notaTecnica) {
    form_notes.previewReturn({
      id: props.notaTecnica.id,
      adjusts: content.value
    }).then(() => {
      resetContent();
      emits('back');
      emits('saved');
    });
  }
}

defineExpose({
  resetContent
});
</script>

<template>
  <div class="w-full h-full">
    <div class="w-full h-full pt-4 pb-44">
      <text-editor
        v-model="content"
        :blocked="true"
      />
    </div>
    <div class="h-16 -mt-16 w-full">
      <button
        class="px-3 py-1 bg-gray-400 hover:bg-gray-400/70 hover:text-gray-700 shadow-lg rounded-full text-xs mr-4"
        @click="emits('back')"
      >
        Voltar
      </button>
      <button
        class="px-3 py-1 bg-sky-400 hover:bg-sky-400/70 hover:text-gray-700 shadow-lg rounded-full text-xs"
        @click="adjustsConfirm"
      >
        Enviar Solicitações
      </button>
    </div>
  </div>
</template>

<style scoped>

</style>
