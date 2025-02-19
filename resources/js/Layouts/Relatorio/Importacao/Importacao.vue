<script setup>
import form_report from "@/Services/Report/form_report.js";
import {ref} from "vue";
import SpinnerLarge from "@/Components/Global/SpinnerLarge.vue";
import success from "@/Hooks/success.js";

const type = ref(null);
const loading = ref(false);
const emits = defineEmits(['close']);

async function input(event) {
  if (event) {
    loading.value = true;
    await form_report.store({
      type: type.value,
      csv: event.target.files[0]
    }).then(() => {
      loading.value = false;
      type.value = null;
      success.cofetti();
    });
  }
}
</script>

<template>
  <div
    v-if="!loading"
    class="w-full h-full rounded-xl grid grid-cols-12 mt-4"
  >
    <div
      :class="[type ? 'col-span-4 rounded-l-xl' : 'col-span-12 rounded-xl']"
      class=" h-full w-full bg-sky-800  flex justify-end items-center px-8 py-4"
    >
      <div class=" w-full h-full">
        <div class="flex justify-between">
          <p class="text-lg text-white mb-4">
            Importar Arquivos
          </p>
          <button
            class="text-white relative -top-2"
            @click="emits('close')"
          >
            <i class="fa-solid fa-xmark fa-lg" />
          </button>
        </div>
        <select
          id="tipo"
          v-model="type"
          class="w-full rounded-lg"
        >
          <option
            disabled
            selected
            value="null"
          >
            Selecione o tipo
          </option>
          <option value="1">
            Adesões
          </option>
          <option value="2">
            Adesões Individuais
          </option>
          <option value="3">
            Votações
          </option>
          <option value="4">
            Base Parlamentar
          </option>
          <option value="5">
            Comissões
          </option>
          <option value="6">
            Proposições
          </option>
        </select>
      </div>
    </div>
    <transition>
      <div
        v-if="type"
        class="col-span-8 w-full h-full"
      >
        <button class="w-full h-full focus:ring-0 rounded-r-xl border-2 border-sky-700 bg-sky-200/10">
          <input
            class="opacity-0 w-full h-full bg-red-500 rounded-r-xl cursor-pointer"
            type="file"
            @change="input"
          >
          <span class="relative -top-20 text-2xl">
            Clique ou Arraste o Arquivo
            <i class="fa-solid fa-file-arrow-up fa-lg ml-4 text-green-500" />
          </span>
        </button>
      </div>
    </transition>
  </div>
  <transition>
    <div
      v-if="loading"
      class="w-full h-full rounded-xl grid grid-cols-12"
    >
      <div class="col-span-12 w-full h-full bg-sky-800 flex justify-center items-center rounded-xl">
        <div class="w-full h-full flex justify-center items-center">
          <SpinnerLarge />
        </div>
      </div>
    </div>
  </transition>
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
