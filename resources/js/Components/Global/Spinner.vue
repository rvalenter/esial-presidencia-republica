<script setup>
const props = defineProps({
  size: {
    type: String,
    default: 'w-7 h-7'
  },
  top: {
    type: String,
    default: '-top-7'
  }
});
</script>

<template>
  <div class="flex justify-center gap-4 items-center">
    <div :class="props.size">
      <div
        class="border-4 border-blue-300 rounded-full"
        :class="props.size"
      />
      <div
        class="spinner border-4 border-sky-700 rounded-full relative"
        :class="[props.size, props.top]"
      />
    </div>
    <span class="font-semibold text-sky-600">
      <slot />
    </span>
  </div>
</template>

<style scoped>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
.spinner {
    border-top-color: transparent;
    border-left-color: transparent;
    animation: spin 1s linear infinite;
}
</style>
