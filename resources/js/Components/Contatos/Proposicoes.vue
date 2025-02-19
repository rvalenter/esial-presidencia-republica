<script setup>
import {onMounted, watch, ref} from "vue";
import get_services from "@/Services/WebService/get_services.js";

const props = defineProps({
  id: {
    type: Number,
    default: null
  }
});
const proposicao = ref([]);
const filter = ref('');

function getProposicao() {
  get_services.proposicoes(props.id).then(res => {
    proposicao.value = res.data.data.dados;
  });
}

function filterProposicao() {
  if (!filter.value) return proposicao.value;

  function normalize(str) {
    return str.replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
  }

  const normalizedFilter = normalize(filter.value);

  return proposicao.value.filter(pp => {
    const normalizedEmenta = normalize(pp.ementa);
    const normalizedSiglaTipo = normalize(pp.siglaTipo);
    const normalizedNumero = normalize(pp.numero.toString());
    const normalizedAno = normalize(pp.ano.toString());
    const concatenated = normalize(pp.siglaTipo + pp.numero + pp.ano);

    return normalizedEmenta.includes(normalizedFilter)
      || concatenated.includes(normalizedFilter)
      || normalizedSiglaTipo === normalizedFilter
      || normalizedNumero === normalizedFilter
      || normalizedAno === normalizedFilter;
  });
}

watch(() => props.id, () => {
  getProposicao()
});

onMounted(() => {
  getProposicao()
});
</script>

<template>
  <div class="w-full flex justify-between items-center">
    <input
      v-model="filter"
      type="search"
      class="p-0 px-4 w-full border-t-0 border-r-0 border-l-0 border-b-2 border-gray-300 focus:ring-0 focus:border-sky-500 bg-gray-100"
      placeholder="Buscar"
      :class="[ $page.props.auth.isMobile ? 'h-6 mb-2' : 'h-12  mb-4']"
    >
  </div>
  <div class="h-full overflow-y-auto">
    <div
      v-for="(pp, index) in filterProposicao()"
      :key="index"
      class="w-full flex hover:bg-gray-200/60 group px-2 min-h-24"
    >
      <div class="w-full grid grid-cols-12 py-4 text-sm text-gray-700 border-b border-gray-500">
        <div
          class="h-full flex items-center justify-start pl-2"
          :class="[$page.props.auth.isMobile ? 'col-span-4' : 'col-span-2']"
        >
          <p :class="[$page.props.auth.isMobile ? 'text-xs' : 'font-semibold' ]">
            {{ pp.siglaTipo }} {{ pp.numero }}/{{ pp.ano }}
          </p>
        </div>
        <div
          class=" h-full flex items-center justify-start"
          :class="[$page.props.auth.isMobile ? 'col-span-8' : 'col-span-10']"
        >
          <p
            class="text-justify"
            :class="[$page.props.auth.isMobile ? 'text-xs' : '' ]"
          >
            {{ pp.ementa }}.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
