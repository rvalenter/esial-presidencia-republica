<script setup>
import { onMounted, ref } from 'vue'
import MasterLogado from "@/Layouts/MasterLogado.vue";
import Importacao from "@/Layouts/Relatorio/Importacao/Importacao.vue";
import Area from "@/Layouts/Relatorio/Menu/Area.vue";
import PerfilParlamentar from "@/Layouts/Relatorio/Relatorios/PerfilParlamentar.vue";
import Comissao from "@/Layouts/Relatorio/Relatorios/Comissao.vue";
import {usePage} from '@inertiajs/vue3'

const page = usePage();
const showImport = ref(false);
const report = ref(null);

const setSelected = (newReport) => {
  report.value = newReport;
};

defineOptions({ layout: MasterLogado });

onMounted(() => {
  if (page.props.auth.isMobile) {
    report.value = 2;
  }
})
</script>

<template>
  <div class="h-full w-full">
    <div
      v-if="!$page.props.auth.isMobile"
      class="w-full flex justify-end"
    >
      <button
        v-if="!showImport && $page.props.auth.access.includes(14)"
        class="text-xs w-auto h-7 px-2 bg-sky-800 rounded-full -mb-2 mt-2 text-white"
        @click="showImport = true"
      >
        Importar
        <i class="fa-solid fa-file-import ml-2" />
      </button>
    </div>
    <div
      v-if="$page.props.auth.access.includes(14) && showImport && !$page.props.auth.isMobile"
      class="w-full h-32 bg-white rounded-xl shadow-xl"
    >
      <Importacao @close="showImport = false" />
    </div>
    <div
      :class="[$page.props.auth.access.includes(14) && showImport ? 'pt-52 -mt-44' : '-mt-10 pt-14']"
      class="grid grid-cols-11 gap-4 h-full w-full"
    >
      <Area
        v-if="!$page.props.auth.isMobile"
        @select="setSelected"
      />
      <div
        class="bg-white w-full h-full rounded-xl p-2 shadow-xl  overflow-y-auto"
        :class="[$page.props.auth.isMobile ? 'col-span-12' : 'col-span-8']"
      >
        <PerfilParlamentar v-if="report === 1" />
        <Comissao v-if="report === 2" />
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>
