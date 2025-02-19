<script setup>
import get_notes from "@/Services/Notes/get_notes.js";
import form_notes from "@/Services/Notes/form_notes.js";
import {onMounted, ref, watch} from "vue";
import cardNt from "@/Components/Nota/Dashboard/Card.vue";
import parses from "../../../Hooks/parses.js";
import Paginator from "@/Components/Global/Paginator.vue";
import TableDashboard from "@/Components/Nota/Dashboard/Table.vue";

const cards = ref([]);
const listAttribute = ref('Todos');
const tableData = ref([]);
const filterStatus = ref('Todos');
const emits = defineEmits(['newNt', 'emptyTable']);
const search = ref('');

function getDashboard() {
  get_notes.fetchDashboard({
    listAttribute: listAttribute.value,
    status: filterStatus.value,
    search: search.value
  }).then(response => {
    cards.value = response.data.data;
  });
}

function getTable() {
  form_notes.fetchTable({
    listAttribute: listAttribute.value,
    status: filterStatus.value,
    page: 1,
    search: search.value
  }).then(response => {
    tableData.value = response.data;
  });
}

function countByKey() {
  let amount = Object.values(cards.value).length;

  return {
    'grid-cols-1': amount <= 1,
    'grid-cols-2': amount === 2,
    'grid-cols-3': amount === 3,
    'grid-cols-4': amount >= 4,
  };
}

function onUpdateCard(newCard) {
  if (newCard === listAttribute.value) {
    listAttribute.value = 'Todos';
    return;
  }

  listAttribute.value = newCard;
}

function onPaginate(page) {
  tableData.value = [];

  form_notes.fetchTable({
    listAttribute: listAttribute.value,
    status: filterStatus.value,
    page: page,
    search: search.value
  }).then(response => {
    console.log(response.data);
    tableData.value = response.data;
  });
}

function onNewNt(id) {
  emits('newNt', id);
}

function onRecycle(id) {
  form_notes.recycle(id).then(() => {
    filterStatus.value = 'Todos';
    getTable();
  });
}

watch(search, () => {
  getTable();
  getDashboard();
});

watch([listAttribute, filterStatus], () => {
  getTable();
  getDashboard();
});

watch(tableData, (newTable) => {
  if (newTable.length === 0) {
    return;
  }

  if (search.value !== '') {
    return;
  }

  if (newTable.data.length === 0) {
    emits('emptyTable', false);
    return;
  }

  emits('emptyTable', true);
});

onMounted(() => {
  getDashboard();
  getTable()
});
</script>

<template>
  <div class="w-full h-screen -mt-96 pt-96 pb-24 pt-4">
    <div class="pl-4">
      <h1 class="text-xl font-bold text-gray-800">
        Gestão de Posicionamentos
      </h1>
    </div>
    <div
      class="grid gap-6 p-4"
      :class="countByKey()"
    >
      <div v-for="(card, index) in cards">
        <cardNt
          :key="index"
          :card="card"
          :card-name="index"
          :actived="listAttribute === index"
          @update-card="onUpdateCard"
        />
      </div>
    </div>
    <div class="mt-2 pt-2 border-t border-gray-300 pl-4 mb-2 flex justify-between items-center">
      <div>
        <p class="text-lg font-bold text-gray-700">
          Tabela de Posicionamentos: {{ parses.title(listAttribute) }}
        </p>
      </div>
      <div>
        <label
          for="search"
          class="mr-2"
        >Buscar:</label>
        <input
          id="search"
          v-model="search"
          type="search"
          class="p-0 border-b border-t-0 border-l-0 border-r-0 border-gray-400 px-2 w-44"
        >
      </div>
      <div>
        <label
          for="concluido"
          class="mr-2"
        >Status:</label>
        <select
          id="concluido"
          v-model="filterStatus"
          class="p-0 border-b border-t-0 border-l-0 border-r-0 border-gray-400 px-2 w-44"
        >
          <option value="Todos">
            Todos
          </option>
          <option value="1">
            Concluídos
          </option>
          <option value="0">
            Não Concluídos
          </option>
          <option value="2">
            Excluídas
          </option>
        </select>
      </div>
    </div>
    <div class="w-full h-full pl-4 pb-16">
      <TableDashboard
        :table-data="tableData.data"
        :status="filterStatus"
        @new-nt="onNewNt"
        @recycle="onRecycle"
      />
      <div>
        <Paginator
          :paginator="tableData"
          :on-paginate="onPaginate"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
