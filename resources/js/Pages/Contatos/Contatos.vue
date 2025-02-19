<script setup>
import MasterLogado from '@/Layouts/MasterLogado.vue'
import { useStore } from 'vuex'
import { computed, onMounted, ref, watch } from 'vue'
import Contato from '@/Components/Contatos/Contato.vue'
import NovoContato from '@/Layouts/Contatos/NovoContato.vue'
import Cards from '@/Layouts/Contatos/Cards.vue'
import Grupo from '@/Components/Contatos/Grupo.vue'

defineOptions({ layout: MasterLogado })

const store = useStore()
const props = defineProps({
  parlamentary: {
    type: Number,
    default: null
  }
})
const contatos = computed(() => store.state.parliamentary.contacts)
const pesquisa = ref('')
const id = computed(() => store.state.parliamentary.id)
const idCard = ref(null)
const selectedCards = computed(() => store.state.parliamentary.selectedCards)
const groupId = ref(null)
const typeSelect = ref(1)
const groups = computed(() => store.state.parliamentary.groups)
const titleGroups = ref('')
const collapseMenu = ref(false)

function getContacts() {
  store.dispatch('parliamentary/getContacts', {
    search: pesquisa.value,
    type: typeSelect.value
  })
}

function getGroups() {
  store.dispatch('parliamentary/getGroups', pesquisa.value)
}

function setId(param) {
  store.commit('parliamentary/setId', param)
}

function setCardId(param) {
  store.commit('parliamentary/setIdCard', param)
}

function selectedCard(param) {
  if (param.newSelectedCard) {
    store.state.parliamentary.selectedCards.push(param)
    return
  }

  store.commit('parliamentary/setSelectedCards', store.state.parliamentary.selectedCards.filter(card => card.id !== param.id))
}

function setSelectedCard(param) {
  store.commit('parliamentary/setSelectedCards', param.contacts)
  titleGroups.value = param.title
}

function setGroupId(param) {
  groupId.value = param.id
  store.commit('parliamentary/setSelectedCards', [])
}

function onCollapseMenu(collapse) {
  collapseMenu.value = collapse
}

watch(typeSelect, (nType) => {
  if (nType == 2) {
    store.commit('parliamentary/setSelectedCards', [])
    getGroups()
    return
  }

  store.commit('parliamentary/setSelectedCards', [])
  pesquisa.value = ''
  getContacts()
})

watch(pesquisa, () => {
  if ([1, 4].includes(typeSelect.value)) {
    getContacts()
    return
  }

  getGroups()
})

onMounted(() => {
  getContacts()

  if (props.parlamentary) {
    setId(props.parlamentary)
  }
})
</script>

<template>
  <div class="grid grid-cols-11">
    <div
      v-if="!collapseMenu"
      class="col-span-4"
    >
      <div class="p-2 flex items-center relative top-4 z-40 w-full pr-4 gap-4 pt-6">
        <div
          v-if="!$page.props.auth.isMobile"
          class="w-1/3"
        >
          <select
            v-model="typeSelect"
            class="text-sm w-full border-b-2 border-t-0 border-l-0 border-r-0 border-gray-400 h-7 bg-gray-50 hover:ring-0 focus:ring-0 z-50 p-0 px-2"
          >
            <option value="1">
              Parlamentar
            </option>
            <option value="2">
              Bancadas/ Frentes
            </option>
          </select>
        </div>
        <div
          class="flex"
          :class="[$page.props.auth.isMobile ? 'w-full' : 'w-2/3']"
        >
          <input
            v-model="pesquisa"
            class="text-sm w-full border-b-2 border-t-0 border-l-0 border-r-0 border-gray-400 h-7 bg-gray-50 hover:ring-0 focus:ring-0"
            placeholder="Pesquisar"
            type="text"
          >
          <i class="fa-solid fa-magnifying-glass relative -left-6 top-1" />
        </div>
      </div>
      <div class="pr-5 pl-2 h-screen -mt-36 pt-44 pb-8">
        <div class="h-full overflow-y-auto overscroll-contain pr-4">
          <div v-if="[1,4].includes(typeSelect) ">
            <Contato
              v-for="contato in contatos"
              :key="contato.id"
              :contato="contato"
              :selected="contato.id === id"
              :type="typeSelect"
              @selected-card="selectedCard"
              @set-id="setId"
            />
          </div>
          <div v-if="typeSelect == 2">
            <Grupo
              v-for="group in groups"
              :key="group.id"
              :active="group.id === idCard"
              :group="group"
              @selected-card="setSelectedCard"
              @set-cards-id="setCardId"
              @destroy-group="getGroups"
            />
          </div>
        </div>
      </div>
    </div>
    <div :class="[collapseMenu ? 'col-span-11' : 'col-span-7']">
      <div
        v-if="typeSelect != 3"
        class="bg-transparent h-full rounded-lg border border-gray-200 mt-4"
      >
        <NovoContato
          v-show="selectedCards.length === 0"
          :id="parseInt(id)"
          @reset-id="id = null"
          @updated-contact="getContacts()"
          @set-id="setId"
          @collapse-menu="onCollapseMenu"
        />
        <Cards
          v-if="selectedCards.length > 0"
          :group-id="groupId"
          :selected="selectedCards"
          :setted-title="titleGroups"
          :type="typeSelect"
          @group-set="getGroups"
          @collapse-menu="onCollapseMenu"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>







