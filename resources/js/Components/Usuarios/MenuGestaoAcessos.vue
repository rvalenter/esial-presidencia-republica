<script setup>
const emits = defineEmits([
    "changeTab"
]);
const props = defineProps({
    tab: {
        type: Number,
        required: true,
    },
})
</script>

<template>
  <div class="mt-10 flex justify-between items-center gap-4 border-b-2 border-gray-300 mb-10">
    <div class="flex justify-start items-center gap-4">
      <div
        class="tabs"
        :class="[props.tab === 1 ? 'tab-active' : 'border-transparent']"
        @click="$emit('changeTab', 1)"
      >
        <p>Gestão de Usuários</p>
      </div>
      <div
        v-if="$page.props.auth.access.includes(3)"
        class="tabs"
        :class="[props.tab === 2 ? 'tab-active' : 'border-transparent']"
        @click="$emit('changeTab', 2)"
      >
        <p>Gestão de Grupos</p>
      </div>
      <div
        v-if="$page.props.auth.access.includes(3)"
        class="tabs"
        :class="[props.tab === 3 ? 'tab-active' : 'border-transparent']"
        @click="$emit('changeTab', 3)"
      >
        <p>Gestão de Acessos</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.tabs {
    @apply h-14 min-w-56 flex justify-center items-center gap-4 hover:border-blue-600  cursor-pointer hover:text-gray-900 hover:font-bold;
}

.tab-active {
    @apply border-blue-700 border-b-4 text-blue-600 font-bold;
}
</style>
