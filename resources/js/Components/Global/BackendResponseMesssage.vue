<script setup>
import { useStore } from 'vuex'
import { computed, watch } from 'vue'

const store = useStore()
const statsShow = computed(() => store.state.statsShow)
const message = computed(() => store.state.statsMessage)
const stats = computed(() => store.state.statsType)

watch(statsShow, (value) => {
  if (value) {
    setTimeout(() => {
      store.commit('setStatsShow', false)
      store.commit('setStatsMessage', '')
    }, 3000)
  }
})
</script>

<template>
  <div
    v-if="statsShow"
    class="w-screen h-screen  absolute z-50 top-24 right-24 flex justify-end items-start"
  >
    <div
      :class="[
        stats === 'success' ? 'bg-green-600/20 border-green-600' : 'bg-red-500/20 border-red-500'
      ]"
      class="font-bold py-6 px-8 rounded-md shadow-lg border top-56 right-4 aboslute z-50"
    >
      <span class="text-lg">{{ message }}</span>
    </div>
  </div>
</template>

<style scoped></style>
