<script setup>
import MasterLogado from "@/Layouts/MasterLogado.vue";
import Editor from "@/Layouts/Nota/Editor/Editor.vue";

defineOptions({layout: MasterLogado});
</script>

<template>
  <Editor v-if="$page.props.auth.access.includes(8) || $page.props.auth.access.includes(9)" />
</template>

<style scoped>

</style>
