<script setup>
import {ref} from "vue";
import MasterNotaTecnica from "@/Layouts/Nota/MasterNotaTecnica.vue";
import Menu from "@/Components/Nota/Menu.vue"
import WorkSpace from "@/Components/Nota/WorkSpace.vue";

const proposicao = ref(null);
const showMenu = ref(true);
const exposedUpdatePropositionList = ref(null);
const newSearch = ref(0);
const showDashboard = ref(true);
const isId = ref(false);

function setIdToLoad(newId) {
  proposicao.value = newId.proposicao;
}

function setShowMenu(show) {
  showMenu.value = show;
}

function updatePropositionList() {
  exposedUpdatePropositionList.value.updateList();
}

function onNewNT(id) {
  exposedUpdatePropositionList.value.handleResetFilters();
  isId.value = true;
  newSearch.value = id.id;
}

function onShowDashboard(show) {
  showDashboard.value = show;
}
</script>

<template>
  <MasterNotaTecnica>
    <Menu
      v-show="showMenu"
      ref="exposedUpdatePropositionList"
      :type="'editor'"
      :new-search="newSearch"
      :is-id="isId"
      @reset-is-id="isId = false"
      @set-id-to-load="setIdToLoad"
      @show-dashboard="onShowDashboard"
    />
    <WorkSpace
      :menu="showMenu"
      :proposicao="proposicao"
      :show-dashboard="showDashboard"
      @show-menu="setShowMenu"
      @stored-tech-note="updatePropositionList"
      @new-nt="onNewNT"
    />
  </MasterNotaTecnica>
</template>

<style scoped>

</style>
