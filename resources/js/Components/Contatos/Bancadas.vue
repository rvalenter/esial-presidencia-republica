<script setup>
import get_contact from "@/Services/Contact/get_contact.js";
import {onMounted, ref, watch} from "vue";

const props = defineProps({
  id: {
    type: Number,
    default: null
  }
});
const contact = ref({
  parlamentar_dados: {
    cargo_lideranca_y: [],
  },
  bancadas: []
});

function getContact() {
  if (!props.id) return;

  get_contact.fetchPhoto(props.id).then(res => {
    contact.value = res.data.data;
  });
}

function removeMinusChar(str) {
  return str.replace('-', ' ');
}

watch(props, () => {
  getContact();
});

onMounted(() => {
  getContact();
});
</script>

<template>
  <div class="mt-4 w-full">
    <div class="flex items-center gap-2">
      <h1 class="text-lg font-semibold text-sky-700">
        Liderenças:
      </h1>
    </div>
    <ul class="pt-2 w-full border-t border-gray-400 list-inside list-disc">
      <li
        v-for="lideranca in contact.parlamentar_dados?.cargo_lideranca_y.filter(lideranca => lideranca != ' ')"
        :key="lideranca"
        class="mt-1 font-semibold"
      >
        {{ removeMinusChar(lideranca) }}
      </li>
    </ul>
  </div>
  <div
    v-if="!$page.props.auth.isMobile"
    class="mt-10 w-full "
  >
    <div class="flex items-center gap-2">
      <h1 class="text-lg font-semibold text-sky-700">
        Bancadas:
      </h1>
    </div>
  </div>
  <div
    v-if="!$page.props.auth.isMobile"
    class="overflow-y-auto h-[72%] border-t border-gray-400 mt-1 pt-1"
  >
    <ul class="pt-2 w-full list-inside list-disc">
      <li
        v-for="bancada in contact.bancadas"
        :key="bancada.id"
        class="mt-1 font-semibold "
      >
        {{ bancada.nome }}
      </li>
      <li v-if="Object.values(contact.bancadas).length === 0">
        Não possui bancadas
      </li>
    </ul>
  </div>
</template>

<style scoped>

</style>
