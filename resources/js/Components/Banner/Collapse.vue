<script setup>
import { onMounted, ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const { props } = usePage()
const collapsed = ref(true)
const emit = defineEmits([
  'collapse'
])

function collapse() {
  collapsed.value = !collapsed.value
  emit('collapse', collapsed.value)
}

onMounted(() => {
  if (props.auth.isMobile) {
    collapsed.value = false

    emit('collapse', collapsed.value);
  }
})

</script>

<template>
  <div
    v-show="!$page.props.auth.isMobile"
    class="flex justify-between items-center w-full pr-12"
  >
    <div class="text-white">
      <button @click="collapse()">
        <i class="fa-solid fa-bars" />
      </button>
    </div>
  </div>
</template>

<style scoped>

</style>
