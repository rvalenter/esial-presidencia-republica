<script setup>
import MenuButtons from "@/Components/Global/MenuButtons.vue";

const props = defineProps({
  collapsed: {
    type: Boolean,
    required: false,
    default: true
  }
});
</script>

<template>
  <div
    class="h-screen grid fixed bg-gray-200 pl-4 pt-28 z-30 pb-10"
    :class="collapsed ? 'w-72' : 'w-24'"
  >
    <div class="w-full h-full  border-r border-gray-300 pr-4">
      <MenuButtons :collapsed="collapsed" />
    </div>
  </div>
</template>

<style scoped>

</style>
