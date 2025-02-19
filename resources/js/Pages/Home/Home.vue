<script setup>
import {ref} from "vue";
import MasterLogado from "@/Layouts/MasterLogado.vue";
import NotaTecnica from "@/Components/Home/NotaTecnica.vue";
import Visao from "@/Layouts/Contatos/Presidente/visao.vue";
import Destaques from "@/Components/Home/Destaques.vue";

defineOptions({layout: MasterLogado});
const posicionamentos = ref({});

function onDadosAtualizados(dados) {
  posicionamentos.value = dados;
}
</script>

<template>
  <main class="w-full h-screen -mt-24 pt-24 pl-6">
    <div class="w-full h-full overflow-y-auto grid grid-cols-12 gap-8 pt-8">
      <div
        v-if="$page.props.auth.access.includes(16)"
        class="col-span-12"
      >
        <visao @dados-atualizados="onDadosAtualizados" />
      </div>
      <!--      <div class="col-span-12">-->
      <!--        <NotaTecnica/>-->
      <!--      </div>-->
    </div>
  </main>
</template>

<style scoped>

</style>
