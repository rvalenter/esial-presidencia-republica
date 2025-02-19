<script setup>
import ManagerTitle from '@/Components/Manager/ManagerTitle.vue'
import ManagerDetails from '@/Components/Manager/ManagerDetails.vue'
import ManagerForm from '@/Components/Manager/ManagerForm.vue'
import ManagerPropositionsRelated from '@/Components/Manager/ManagerPropositionsRelated.vue'
import { useStore } from 'vuex'
import ManagerPropositionPath from '@/Components/Manager/ManagerPropositionPath.vue'
import ToggleSwitch from 'primevue/toggleswitch'
import { ref } from 'vue'

const store = useStore()
const props = defineProps({
  proposition: {
    type: Object,
    required: true
  }
})
const form = ref(true)
const related = ref(true)
const path = ref(true)
const details = ref(true)

const onSetId = (id) => {
  store.dispatch('manager/fetchProposition', id)
}

const onClose = () => {
  store.commit('manager/setProposition', [])
}
</script>

<template>
  <ManagerTitle
    :proposition="proposition"
    @close="onClose"
  />
  <div class="bg-white rounded-xl shadow-xl mt-4 p-4">
    <p class="font-semibold mb-4">
      Sessões:
    </p>
    <div class="flex gap-4">
      <div class="flex items-center">
        <input id="default-checkbox" type="checkbox" :value=true v-model="form" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Dados Gerais:</label>
      </div>
      <div class="flex items-center">
        <input checked id="checked-checkbox" type="checkbox" :value=true v-model="details" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Detalhes:</label>
      </div>
      <div class="flex items-center">
        <input checked id="checked-checkbox" type="checkbox" :value=true v-model="related" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Matérias Correlatas:</label>
      </div>
      <div class="flex items-center">
        <input checked id="checked-checkbox" type="checkbox" :value=true v-model="path" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Caminho:</label>
      </div>
    </div>
  </div>
  <ManagerDetails
    v-show="details"
    :proposition="proposition"
  />
  <manager-form
    v-show="form"
    :proposition="proposition"
  />
  <ManagerPropositionsRelated
    v-show="related"
    :proposition="proposition"
    @id="onSetId"
  />
  <manager-proposition-path
    v-show="path"
    :proposition="proposition"
  />
</template>

<style scoped></style>
