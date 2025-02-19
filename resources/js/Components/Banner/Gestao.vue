<script setup>
import {Link} from "@inertiajs/vue3";
</script>

<template>
  <Link
    v-if="!$page.props.auth.isMobile"
    :href="route('gestao.usuarios')"
    preserve-state
    title="Gestão de Usuários"
    class="rounded-full bg-gray-200 hover:bg-gray-100 hover:text-indigo-500 h-8 w-8 flex justify-center items-center"
    replace
  >
    <i class="fa-solid fa-gear" />
  </Link>
</template>

<style scoped>

</style>
