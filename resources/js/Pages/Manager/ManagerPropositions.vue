<script setup>
import MasterLogado from '@/Layouts/MasterLogado.vue'
import PropositionSearch from '@/Components/Manager/PropositionSearch.vue'
import { useStore } from 'vuex'
import { computed } from 'vue'
import ManagerProposition from '@/Layouts/Manager/ManagerProposition.vue'
import ManagerDashboard from '@/Components/Manager/ManagerDashboard.vue'

const store = useStore()
const proposition = computed(() => store.state.manager.proposition)

defineOptions({ layout: MasterLogado })
</script>

<template>
  <div class="w-screen h-screen -mt-20 pt-24 -ml-80 pl-80">
    <div class="h-full pt-4">
      <proposition-search />
      <div class="w-full h-full pt-16 pb-4">
        <div
          v-show="proposition.id"
          class="w-full h-full overflow-y-auto"
        >
          <ManagerProposition :proposition="proposition" />
        </div>
        <div
          v-show="proposition.length === 0"
          class="w-full h-full overflow-y-auto"
        >
          <manager-dashboard />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
