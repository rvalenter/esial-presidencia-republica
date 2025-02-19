<script setup>
import Dialog from 'primevue/dialog'
import { computed, ref, watch } from 'vue'
import { useStore } from 'vuex'
import ManagerTimeLineItem from '@/Components/Global/ManagerTimeLineItem.vue'

const store = useStore()
const visible = ref(false)
const history = computed(() => store.state.manager.history)
const id = ref(null)
const props = defineProps({
  proposition: {
    type: Object,
    required: true
  }
})

const getHistory = (newValue) => {
  store.dispatch('manager/fetchHistory', newValue.id).then(() => {
    console.log(history.value)
  })
}

const onSetId = (newId) => {
  id.value = newId
}

watch(
  () => props.proposition,
  (newValue) => {
    getHistory(newValue)
  }
)
</script>

<template>
  <div class="card flex justify-center">
    <div>
      <Button
        v-if="history.length > 0"
        icon="pi pi-history"
        severity="info"
        variant="text"
        raised
        rounded
        aria-label="history"
        size="small"
        style="height: 1.6rem; width: 1.5rem"
        @click="visible = true"
      />
    </div>

    <Dialog
      v-model:visible="visible"
      :style="{ width: '35rem' }"
      header="Histórico:"
      modal
    >
      <div v-if="history.length > 0">
        <ul>
          <li
            v-for="item in history"
            :key="item.id"
            class="w-full p-4 flex justify-between"
          >
            <ManagerTimeLineItem
              :item="item"
              :selected="id === item.id"
              @set-id="onSetId"
            />
          </li>
        </ul>
      </div>
      <div v-if="history.length == 0">
        <span class="text-surface-500 dark:text-surface-400 block mb-8">Nenhum registro encontrado</span>
      </div>
    </Dialog>
  </div>
</template>

<style scoped>
.btn {
  @apply bg-emerald-600 p-2 rounded-full text-white hover:bg-emerald-600/70;
}
</style>
