<script setup>
import {Link} from "@inertiajs/vue3";
</script>

<template>
  <Link
    :href="route('logout')"
    method="post"
    as="button"
    title="Sair do sistema"
    replace
    class="rounded-full bg-gray-200 hover:bg-gray-100 hover:text-indigo-500 h-8 w-8 flex justify-center items-center"
  >
    <i class="fa-solid fa-arrow-right-to-bracket" />
  </Link>
</template>

<style scoped>

</style>
