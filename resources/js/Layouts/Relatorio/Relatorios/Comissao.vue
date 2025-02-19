<script setup>
import { computed, onMounted } from 'vue'
import { useStore } from 'vuex'

const store = useStore()
const comissao = computed(() => store.state.reports.comissao)
const committee = computed(() => store.state.reports.committee)
const house = computed(() => store.state.reports.house)

function getCommittee() {
  store.dispatch('reports/getCommittee')
}

onMounted(() => {
  getCommittee()
})
</script>

<template>
  <div>
    <div class="w-full px-4 pt-3 flex justify-between items-center gap-4">
      <div class="w-4/12">
        <label
          for="search"
          class="font-semibold ml-1 text-gray-600 mb-2 text-xs"
        >Casa:</label>
        <select
          class="w-full h-8 border border-gray-200 bg-gray-100 shadow-md p-0 px-4 rounded-full mt-2 mb-2 text-sm"
          @change="store.commit('reports/SET_HOUSE', $event.target.value)"
        >
          <option
            value="Câmara dos Deputados"
          >
            Câmara
          </option>
          <option
            value="Senado Federal"
          >
            Senado
          </option>
        </select>
      </div>
      <div class="w-8/12">
        <label
          for="search"
          class="font-semibold ml-1 text-gray-600 mb-2 text-xs"
        >Selecione a comissão:</label>
        <select
          class="w-full h-8 border border-gray-200 bg-gray-100 shadow-md p-0 px-4 rounded-full mt-2 mb-2 text-sm"
          @change="store.commit('reports/SET_COMISSAO', $event.target.value)"
        >
          <option
            value="null"
            disabled
            selected
          >
            Selecione a comissão
          </option>
          <option
            v-for="item in Object.values(committee).filter(item => item.origem === house)"
            :key="item.id"
            :value="item.id"
          >
            {{ item.sigla }} - {{ item.descricao }}
          </option>
        </select>
      </div>
    </div>
  </div>
  <div class="w-full h-[88%] pt-4">
    <iframe
      v-if="comissao"
      class="w-full h-full"
      :src="'/relatorios/comissao/' + comissao + '/id'"
    />
  </div>
  <div v-if="!comissao">
    <div class="w-full h-full flex justify-center items-center">
      <p class="text-gray-500">
        Selecione uma comissão para visualizar o relatório.
      </p>
    </div>
  </div>
</template>

<style scoped>

</style>
