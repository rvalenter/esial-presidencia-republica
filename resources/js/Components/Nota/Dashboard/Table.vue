<script setup>
import parses from "../../../Hooks/parses.js";

const props = defineProps({
  tableData: {
    type: Object,
    default: () => {}
  },
  status: {
    type: String,
    default: 'Todos'
  }
});
const emits = defineEmits(['newNt', 'recycle']);

const getColorClass = (type) => {
  const colors = {
    'CONTRÁRIO': 'border-red-500',
    'FAVORÁVEL': 'border-green-500',
    'CONTRÁRIO COM AJUSTES': 'border-yellow-500',
    'FAVORÁVEL COM AJUSTES': 'border-blue-500',
    'FORA DE COMPETÊNCIA': 'border-gray-500',
    'MATÉRIA PREJUDICADA': 'border-orange-500',
    'NADA A OPOR': 'border-cyan-500',
    'PERDA DE EFICÁCIA': 'border-purple-500',
  };

  return colors[type] || 'border-white';
};

function setNt(id) {
  emits('newNt', id);
}

function recycle(id) {
  emits('recycle', id);
}
</script>

<template>
  <div class="h-full overflow-y-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
      <thead class="text-xs text-gray-700 uppercase bg-gray-300 sticky top-0 z-10">
        <tr>
          <th
            scope="col"
            class="px-6 py-3"
          >
            Proposição/ Matéria
          </th>
          <th
            scope="col"
            class="px-6 py-3"
          >
            Posição
          </th>
          <th
            scope="col"
            class="px-6 py-3"
          >
            Percepção Impacto
          </th>
          <th
            scope="col"
            class="px-6 py-3 text-center"
          >
            Data
          </th>
          <th
            scope="col"
            class="px-6 py-3 text-center"
          >
            Concluida
          </th>
          <th
            scope="col"
            class="px-6 py-3 text-center"
          >
            {{ status == '2' ? 'Restaurar' : 'Visualizar' }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="row in tableData"
          :key="row.id"
          class="odd:bg-white even:bg-gray-100 border-b border-gray-200"
        >
          <th
            scope="row"
            class="pl-6 py-4 font-medium text-gray-900 whitespace-nowrap flex h-full"
          >
            <div
              class="border-l-4 h-5 mr-2"
              :class="getColorClass(row.posicionamento.posicionamento)"
            />
            {{ row.proposicoes.sigla }} {{ row.proposicoes.numero }}/{{ row.proposicoes.ano }}
          </th>
          <td class="px-6 py-4">
            {{ parses.title(row.posicionamento.posicionamento) }}
          </td>
          <td class="px-6 py-4">
            {{ row.impacto_percepcao == null ? '-' : row.impacto_percepcao ? 'Positivo' : 'Negativo' }}
          </td>
          <td class="px-6 py-4 text-center">
            {{ parses.date(row.data_referencia) }}
          </td>
          <td class="px-6 py-4 text-center">
            {{ row.concluida ? 'Sim' : 'Não' }}
          </td>
          <td class="px-6 py-4 flex justify-center">
            <button
              v-if="row.deleted_at === null"
              class="font-medium text-blue-600 hover:underline"
              @click="setNt(row.proposicoes)"
            >
              <i class="fa-solid fa-up-right-from-square" />
            </button>
            <button
              v-if="row.deleted_at !== null"
              class="font-medium text-blue-600 hover:underline"
              @click="recycle(row.id)"
            >
              <i class="fa-solid fa-recycle" />
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>

</style>
