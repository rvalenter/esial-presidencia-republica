<script setup>
import Parses from '@/Hooks/parses.js'
import {useStore} from 'vuex'

const store = useStore()
const props = defineProps({
  item: {
    type: Object,
    required: true
  },
  selected: {
    type: Boolean,
    required: false,
    default: false
  }
})
const emits = defineEmits(['setId'])

const setHistoryDefault = (data) => {
  store.commit('manager/setPropositionGeneralData', data)
  emits('setId', data.id)
}
</script>

<template>
  <div>
    <span>{{ item.usuario.nome }}</span> -
    <span>{{ Parses.timestamp(item.usuario.created_at, true) }}</span>
  </div>
  <Button
    v-if="!selected"
    icon="pi pi-check"
    variant="text"
    raised
    rounded
    aria-label="Filter"
    size="small"
    class="ml-4"
    @click="setHistoryDefault(item)"
  />
  <Button
    v-if="selected"
    icon="pi pi-check"
    rounded
    aria-label="Filter"
    size="small"
  />
</template>

<style scoped>

</style>
