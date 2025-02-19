<script setup>
import {ref} from "vue";
import {Link} from "@inertiajs/vue3";

const emits = defineEmits([
  'collapse'
]);

const search = ref('');
const collapse = ref(false);

function removeSpecialChars(str) {
  return str.normalize('NFD')
    .replace(/[\u0300-\u036f]/g, ' ')
    .replace(/[^a-zA-Z0-9]/g, ' ')
    .replace(/\s+/g, '-')
    .toLowerCase();
}

function onPressEnter() {
  window.location.href = '/busca/' + removeSpecialChars(search.value);
}
</script>

<template>
  <div
    v-if="!$page.props.auth.isMobile"
    class="w-full col-span-6 flex justify-center"
  >
    <div class="flex w-full items-center">
      <div
        class="w-full flex items-center"
      >
        <Link
          :href="'/busca/' + removeSpecialChars(search)"
          method="get"
          as="button"
          type="button"
          class="text-gray-800 relative -mr-9"
        >
          <i class="fa-solid fa-magnifying-glass" />
        </Link>
        <input
          v-model="search"
          class="pl-14 w-full border-transparent focus:border-transparent focus:ring-0 h-10 placeholder-gray-400 font-semibold bg-white/80 focus:bg-white rounded-lg"
          placeholder="Pesquisar"
          type="search"
          @keyup.enter="onPressEnter"
        >
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
