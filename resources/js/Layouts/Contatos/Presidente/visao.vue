<script setup>
import { computed, onMounted, watch, ref } from 'vue'
import Raia from '@/Layouts/Contatos/Presidente/Raia.vue'
import { useStore } from 'vuex'
import MultiSelect from 'primevue/multiselect';

const store = useStore()
const img = computed(() => store.state.vision.img)
const casa = computed(() => store.state.vision.casa)
const partido = computed(() => store.state.vision.partido)
const partidos = computed(() => store.state.vision.partidos)
const group = computed(() => store.state.vision.group)
const parliamentarians = computed(() => store.state.vision.parliamentarians)
const posicionamento = computed(() => store.state.vision.posicionamento)
const refinement = ref([])

const getParlamentaries = () => {
  store.dispatch('vision/getParlamentaries', { casa: casa.value, partido: partido.value, group: group.value })
}

const setAmount = computed(() => {
  let amount = 0

  Object.values(parliamentarians.value).forEach(parliamentary => {
    amount += parliamentary.length
  })

  return amount
})

const getGov = () => {
  store.dispatch('vision/getGov', { casa: casa.value, partido: partido.value })
}

const setOptions = (options) => {
  return options.map(option => {
    return { name: option, value: option }
  })
}

const setRefinementParliamentary = (val) => {
  if (refinement.value.length === 0) {
    return val
  }

  return refinement.value.map((refinement) => {
    return { [refinement.value]: val[refinement.value] }
  }).reduce((acc, item) => {
    const key = Object.keys(item)[0];
    acc[key] = item[key];
    return acc;
  }, {});
}

watch(casa, (newCasa) => {
  if (newCasa == 4) {
    store.dispatch('vision/getPartidos')
  }
})

watch(partido, () => {
  getGov()
})

watch(casa, () => {
  if (casa.value != 4) {
    store.commit('vision/setPartido', 'todos')
  }

  getGov()
})

watch([casa, group, partido], () => {
  refinement.value = []
  getParlamentaries()
})

onMounted(() => {
  getParlamentaries()
  getGov()
})
</script>

<template>
  <div class="h-full w-full">
    <div class="bg-white h-full w-full rounded-3xl shadow-xl overflow-y-auto">
      <div
        :class="[$page.props.auth.isMobile ? 'h-32' : 'h-48']"
        class="flex justify-end items-center w-full  pt-10 pr-16 pl-4 rounded-t-2xl bg-[url('../../public/src/img/congresso.jpg')] bg-cover bg-center bg-opacity-50 bg-no-repeat"
      >
        <div
          :class="[$page.props.auth.isMobile ? 'h-28 w-28 border-2' : 'h-52 w-52 border-8']"
          class="image-container rounded-full  border-sky-700 mt-16 bg-cover bg-bottom bg-no-repeat flex items-center justify-center shadow-box"
        >
          <img
            id="contato-img"
            :src="casa === 1 ? 'https://www2.camara.leg.br/a-camara/presidencia/imagens/img_representante.jpg'
              : casa === 2 || casa === 3 ? 'https://www.senado.leg.br/senadores/img/fotos-oficiais/senador5732.jpg'
                : 'https://www.camara.leg.br/midias/image/2022/08/img20220331154441356.jpg'"
            class="absolute inset-0 z-10"
          >
        </div>
      </div>
      <div class="grid grid-flow-row auto-rows-max gap-4 h-1/2 w-full mt-10 px-4">
        <div class="relative -top-2">
          <p class="text-xl font-bold">
            Total Parlamentares: {{ setAmount }}
          </p>
        </div>
        <div
          class="mb-4 grid gap-4"
          :class="[1, 2].includes(parseInt(group)) ? 'grid-cols-3' : 'grid-cols-2'"
        >
          <div class="w-full">
            <label class="font-bold">Filtros</label>
            <div class="flex justify-between items-center gap-4">
              <select
                class="w-full h-12 text-sm border-0 border-b-2 border-gray-400 p-0 focus:ring-0 bg-transparent"
                @change="store.commit('vision/setCasa', $event.target.value)"
              >
                <option :value="1">
                  Câmara
                </option>
                <option :value="2">
                  Senado
                </option>
                <option :value="4">
                  Partido Político
                </option>
              </select>
              <select
                v-if="casa === 4"
                class="w-full h-12 text-sm border-0 border-b-2 border-gray-400 p-0 focus:ring-0 bg-transparent"
                @change="store.commit('vision/setPartido', $event.target.value)"
              >
                <option
                  value="todos"
                  selected
                  disabled
                >
                  Selecione o Partido Desejado
                </option>
                <option
                  v-for="(partido, index) in partidos"
                  :key="index"
                  :value="partido.siglaPartido"
                >
                  {{ partido.siglaPartido }}
                </option>
              </select>
            </div>
          </div>
          <div class="w-full">
            <label class="font-bold">Agrupamento</label>
            <select
              class="w-full h-12 text-sm border-0 border-b-2 border-gray-400 p-0 focus:ring-0 bg-transparent"
              @change="store.commit('vision/setGroup', $event.target.value)"
            >
              <option :value="1">
                Partidário
              </option>
              <option :value="2">
                Região
              </option>
              <option :value="3">
                Alinhamento
              </option>
            </select>
          </div>
          <div
            v-show="[1, 2].includes(parseInt(group))"
            class="w-full"
          >
            <label class="font-bold">Refinamento</label>
            <MultiSelect
              v-model="refinement"
              :options="setOptions(Object.keys(parliamentarians))"
              option-label="name"
              filter
              placeholder="Selecione o refinamento"
              :max-selected-labels="3"
              class="w-full h-12 text-sm border-0 border-b-2 border-gray-400 p-0 focus:ring-0 bg-transparent"
            />
          </div>
        </div>
        <div>
          <div
            v-for="(parliamentary, index) in setRefinementParliamentary(parliamentarians)"
            :key="index"
          >
            <div class="flex justify-between mb-4">
              <div class="text-gray-700 w-full border-b-2 border-gray-300 font-bold text-lg">
                {{ index }} - {{ Object.values(parliamentary).length }}
              </div>
            </div>
            <Raia :indicadors="parliamentary" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.image-container {
  overflow: hidden;
  position: relative;
}

.image-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
