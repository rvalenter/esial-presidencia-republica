<script setup>
import MasterLogado from "@/Layouts/MasterLogado.vue";
import Editor from "@/Layouts/Nota/Editor/Editor.vue";
import Gestor from "@/Layouts/Nota/Gestor/Gestor.vue";
import Presidencia from "@/Layouts/Posicionamento/Presidencia/Presidencia.vue";

defineOptions({layout: MasterLogado});
</script>

<template>
  <Presidencia v-if="$page.props.auth.access.includes(5)" />
</template>

<style scoped>

</style>
