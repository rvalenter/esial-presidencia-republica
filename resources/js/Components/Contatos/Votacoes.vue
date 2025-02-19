<script setup>
import { onMounted, ref, watch } from 'vue'
import get_contact from '@/Services/Contact/get_contact.js'
import parses from '@/Hooks/parses.js'

const props = defineProps({
  form: {
    type: Object,
    default: () => {}
  }
})
const filter = ref('')
const votes = ref([])

function link(id) {
  if (props.form.role === 'Deputado Federal') {
    return `https://www.camara.leg.br/proposicoesWeb/fichadetramitacao?idProposicao=${id}`
  }

  return `https://www25.senado.leg.br/web/atividade/materias/-/materia/${id}`
}

function getVotes() {
  let casa = 'camara'
  let prefix = `1${props.form.get_parlamentar}`

  if (props.form.role !== 'Deputado Federal') {
    casa = 'senado'
    prefix = `2${props.form.get_parlamentar}`
  }

  get_contact.fetchVotes({ id: prefix, casa: casa }).then((res) => {
    votes.value = res.data.data
  })
}

function filterVotes() {
  if (!filter.value) return votes.value

  function normalize(str) {
    if (!str) return ''
    return str.replace(/[^a-zA-Z0-9]/g, '').toLowerCase()
  }

  const normalizedFilter = normalize(filter.value)

  return votes.value.filter((pp) => {
    const orientacao = normalize(pp.objeto)
    const nome = normalize(pp.nome)
    const date = normalize(parses.date(pp.data))

    return (
      orientacao.includes(normalizedFilter) ||
      nome.includes(normalizedFilter) ||
      date.includes(normalizedFilter) ||
      orientacao === normalizedFilter ||
      nome === normalizedFilter ||
      date === normalizedFilter
    )
  })
}

onMounted(() => {
  getVotes()
})

watch(
  () => props.form,
  () => {
    getVotes()
  }
)
</script>

<template>
  <div class="w-full flex justify-between items-center">
    <input
      v-model="filter"
      type="search"
      class="p-0 px-4 w-full border-t-0 border-r-0 border-l-0 border-b-2 border-gray-300 focus:ring-0 focus:border-sky-500 bg-gray-100"
      :class="[$page.props.auth.isMobile ? 'h-6 mb-2' : 'h-12  mb-4']"
      placeholder="Buscar"
    >
  </div>
  <div class="h-full overflow-y-auto">
    <div
      v-for="(vote, index) in filterVotes()"
      :key="index"
      class="w-full flex cursor-pointer group px-2 min-h-24 hover:bg-gray-200/60"
    >
      <div class="w-full grid grid-cols-12 py-4 text-sm border-b border-gray-400">
        <div class="col-span-2 h-full flex items-center justify-start pl-2">
          <div class="text-center">
            <p>{{ vote.nome }}</p>
            <p class="text-xs font-light">
              {{ parses.date(vote.data) }}
            </p>
          </div>
        </div>
        <div
          class="h-full flex items-center justify-start text-justify pl-6"
          :class="[$page.props.auth.isMobile ? 'col-span-7' : 'col-span-6']"
        >
          {{ vote.objeto }}
        </div>
        <div class="col-span-3 grid grid-flow-row auto-rows-max gap-2 pl-8">
          <div
            v-if="!$page.props.auth.isMobile"
            class="grid grid-cols-2 gap-4"
          >
            <p>Governo:</p>
            <p
              :class="[
                vote.orientacao === 'Sim'
                  ? 'text-green-600'
                  : vote.orientacao === 'Não'
                    ? 'text-red-500'
                    : 'text-gray-600'
              ]"
              class="font-bold"
            >
              {{ vote.orientacao }}
            </p>
          </div>
          <div
            class="grid gap-4"
            :class="[$page.props.auth.isMobile ? 'grid-cols-1' : 'grid-cols-2']"
          >
            <p v-if="!$page.props.auth.isMobile">
              Parlamentar:
            </p>
            <p
              :class="[
                vote.voto === 'Sim'
                  ? 'text-green-600'
                  : vote.voto === 'Não'
                    ? 'text-red-500'
                    : 'text-gray-600'
              ]"
              class="font-bold"
            >
              {{ vote.voto ?? '-' }}
            </p>
          </div>
        </div>
        <div
          v-if="!$page.props.auth.isMobile"
          class="flex justify-center items-center"
        >
          <a
            :href="link(vote.idProposicao)"
            target="_blank"
            class="text-sky-700 hover:text-sky-600"
          >
            <i class="fa-solid fa-external-link-alt" />
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
