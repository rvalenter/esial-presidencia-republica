<script setup>
import {ref, watch} from "vue";
import post from "@/Services/User/post_user.js"

const props = defineProps({
    nome: {
        type: String,
        required: true
    },
    idAcesso: {
        type: Number,
        required: true,
    },
    idUsuario: {
        type: Number,
        required: true,
    },
    checked: {
        type: Boolean,
        required: true,
    },
    description: {
        type: String,
        required: false,
        default: ''
    }
});
const emits = defineEmits([
    "updateAccess"
]);
const access = ref(props.checked);

function definePermissao() {
    if (access.value) {
        return post.gravaPermissao({
            status: access.value,
            idAcesso: props.idAcesso,
            idUsuario: props.idUsuario
        }).then(() => {
            emits('updateAccess');
        });
    }

    return post.removePermissao({
        idUsuario: props.idUsuario,
        idAcesso: props.idAcesso
    }).then(() => {
        emits('updateAccess');
    });
}

watch(() => props.checked, () => {
    access.value = props.checked;
});
</script>

<template>
  <div class="flex">
    <input
      :id="`check_${props.nome}`"
      v-model="access"
      type="checkbox"
      class="w-5 h-5 shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
      @change="definePermissao()"
    >
    <label
      :for="`check_${props.nome}`"
      class="text-base text-gray-700 ms-5"
    >
      {{ props.nome }}
      <button
        class="relative -top-3 hover:text-blue-500"
        :title="description"
      >
        <i class="fa-regular fa-question-circle fa-xs" />
      </button>
    </label>
  </div>
</template>

<style scoped>
</style>
