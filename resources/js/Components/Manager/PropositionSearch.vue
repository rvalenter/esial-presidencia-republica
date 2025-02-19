<script setup>
import { useStore } from 'vuex'
import { computed, ref } from 'vue'
import { debounce } from 'lodash'

const store = useStore()
const autoCompleteValues = computed(() => store.state.manager.autoComplete);
const idSelected = ref(false);
const search = ref('');

const autoComplete = debounce((e) => {
  store.dispatch('manager/getAutoComplete', e.target.value)
}, 500);

const getProposition = (id) => {
  store.dispatch('manager/fetchProposition', id);
}

const setSearch = (item) => {
  search.value = '';
}

const setImg = (origem) => {
  if (origem === 'Câmara dos Deputados') {
    return '../../src/img/logo_camara.png';
  } else {
    return '../../src/img/logo_senado.png';
  }
}
</script>

<template>
  <div
    class="py-4 mb-8 absolute w-full -ml-80 pl-80 grid justify-items-stretch"
  >
    <div
      v-if="autoCompleteValues.length > 0 && !idSelected"
      class="bg-white/95 rounded-3xl shadow-xl z-0 pt-16 relative -top-6 p-4"
    >
      <ul class="w-full ">
        <li
          v-for="item in autoCompleteValues"
          :key="item.id"
          class="h-14 w-full hover:border-b border-gray-400 hover:bg-gray-100 hover:font-semibold flex items-center cursor-pointer px-2 z-50"
          @click="getProposition(item.id), idSelected = true, setSearch(item)"
        >
          <img
            :src="setImg(item.origem)"
            alt="Logo Casa Legislativa"
            class="h-6 rounded-sm mr-4"
          > {{ item.sigla }} {{ item.numero }}/{{ item.ano }} - {{ item.origem }}
        </li>
      </ul>
    </div>
    <div
      class="pl-80 absolute top-0 justify-self-center"
      :class="[(autoCompleteValues.length > 0 && !idSelected) ? 'w-[98%]' : 'w-full']"
    >
      <input
        v-model="search"
        class="w-full rounded-lg border-gray-400 shadow-lg px-4 focus:bg-gray-50 py-2 focus:outline-none"
        type="search"
        placeholder="Buscar Proposição"
        @keyup="autoComplete($event), idSelected = false"
      >
    </div>
  </div>
</template>

<style scoped></style>
