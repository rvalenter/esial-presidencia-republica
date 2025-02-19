<script setup>
import DOMPurify from 'dompurify';
function replaceTitle(titulo) {
    return titulo.split('/').join('<i class="fa-solid fa-chevron-right mr-2 fa-xs text-green-600 ml-2"></i>');
}
</script>

<template>
  <div class="bg-gray-300 h-10 rounded-lg flex items-center px-3 shadow-inner">
    <p
      class="text-gray-600 font-semibold"
      v-html="DOMPurify.sanitize(replaceTitle($page.url))"
    />
  </div>
</template>

<style scoped>

</style>
