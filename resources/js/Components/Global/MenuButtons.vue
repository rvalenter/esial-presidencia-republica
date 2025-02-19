<script setup>
import MenuButton from '@/Components/Global/MenuButton.vue'
import { usePage } from '@inertiajs/vue3'

const { page } = usePage()
const props = defineProps({
  collapsed: {
    type: Boolean,
    required: false,
    default: false
  }
})

const checkNtAccess = (page) => {
  if (page) {
    return (page.props.auth.access.includes(8)
        || page.props.auth.access.includes(9)
        || page.props.auth.access.includes(15)
        || page.props.auth.access.includes(10))
      && !page.props.auth.isMobile
  }
}
</script>

<template>
  <div class="grid grid-cols-1 w-full ">
    <MenuButton
      v-if="$page.props.auth.access.includes(19)"
      :collapsed="collapsed"
      :current-route="route().current() === 'manager.position.index'"
      :name="'Gestão de Proposição'"
      :router="'manager.position.index'"
      icon="fa-solid fa-map-location fa-lg"
    />
    <MenuButton
      v-if="$page.props.auth.access.includes(20)"
      :collapsed="collapsed"
      :current-route="route().current() === 'manager.agenda.index'"
      :name="'Pautas Semanais'"
      :router="'manager.agenda.index'"
      icon="fa-solid fa-calendar-check fa-lg"
    />
    <MenuButton
      v-if="checkNtAccess($page)"
      :collapsed="collapsed"
      :current-route="route().current() === 'nota-tecnica.index'"
      :name="'Nota Técnica'"
      :router="'nota-tecnica.index'"
      icon="fa-solid fa-book-open-reader fa-lg"
    />
    <MenuButton
      v-if="$page.props.auth.access.includes(6)"
      :collapsed="collapsed"
      :current-route="route().current() === 'parlamentares.index'"
      :name="'Parlamento'"
      :router="'parlamentares.index'"
      icon="fa-regular fa-address-card fa-lg"
    />
    <MenuButton
      v-if="$page.props.auth.access.includes(13)"
      :collapsed="collapsed"
      :current-route="route().current() === 'relatorio.index'"
      :name="'Relatórios'"
      :router="'relatorio.index'"
      icon="fa-solid fa-newspaper fa-lg"
    />
    <div
      v-if="!$page.props.auth.isMobile"
      class="text-gray-700 rounded-lg"
    >
      <a
        href="/Manual_e-Sial_-_Módulo_de_Gestão_de_Posicionamentos_-_Versão_Beta.pdf"
        class="btn"
        target="_blank"
      >
        <div
          class="h-8 relative -left-1 -mr-[3rem] pl-1"
        />
        <i class="fa-solid fa-download text-sky-700" />
        <span
          v-show="collapsed"
          class="-ml-4"
        >Manual</span>
      </a>
    </div>
  </div>
</template>

<style scoped>
.btn {
  @apply pl-5 hover:text-black hover:bg-gray-300 w-full h-14 flex justify-start items-center gap-12
}
</style>
