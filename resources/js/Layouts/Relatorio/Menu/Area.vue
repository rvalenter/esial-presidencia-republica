<script setup>
import {ref, watch} from "vue";
import RelatorioItemMenu from "@/Components/Relatorios/Menu/RelatorioItemMenu.vue";

const emits = defineEmits(['select']);
const pesquisa = ref('');
const selected = ref(null);
const relatorios = ref({
  2: {
    id: 2,
    titulo: 'Comissões',
    descricao: 'Relatório de Comissões',
    icon: 'fa-solid fa-people-roof'
  }
});

function setSelected(relatorio) {
  selected.value = relatorio;
  emits('select', relatorio);
}

function filterRelatorios() {
  return Object.values(relatorios.value).filter(relatorio => {
    return relatorio.titulo.toLowerCase().includes(pesquisa.value.toLowerCase());
  });
}

watch(pesquisa, () => {
  filterRelatorios();
});
</script>

<template>
  <div class="w-full h-full overflow-y-auto pl-2 col-span-3">
    <div class="flex items-center z-40 mb-4 relative left-1">
      <input
        v-model="pesquisa"
        class="w-full border-b-2 border-t-0 border-l-0 border-r-0 border-gray-400 h-8 bg-transparent hover:ring-0 focus:ring-0 z-50"
        placeholder="Pesquisar Relatório"
        type="text"
      >
      <i class="fa-solid fa-magnifying-glass relative -left-6 -ml-2" />
    </div>
    <relatorio-item-menu
      v-for="(relatorio, index) in filterRelatorios()"
      :key="index"
      :relatorio="relatorio"
      :active="selected === relatorio.id"
      @select="setSelected"
    />
  </div>
</template>

<style scoped>

</style>
