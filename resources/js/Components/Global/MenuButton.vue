<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps({
  collapsed: {
    type: Boolean,
    required: false,
    default: true
  },
  router: {
    type: String,
    required: true
  },
  currentRoute: {
    type: Boolean,
    required: true
  },
  name: {
    type: String,
    required: true
  },
  icon: {
    type: String,
    required: false,
    default: ''
  },
});
</script>

<template>
  <div
    :class="[props.currentRoute ? 'bg-sky-900 text-white group shadow-md' : 'text-gray-700']"
    class="rounded-lg"
  >
    <Link
      :href="route(props.router)"
      :title="props.name"
      class="btn"
      preserve-state
      type="button"
    >
      <div
        v-if="props.currentRoute"
        class="border-l-4 border-sky-500 h-8 relative -left-2.5 -mr-[2.8rem]"
      />
      <i :class="[props.icon, props.currentRoute ? 'text-white group-hover:text-sky-800' : 'text-sky-700']" />
      <span
        v-if="props.collapsed"
        class="-ml-4"
      >{{ props.name }}</span>
    </Link>
  </div>
</template>

<style scoped>
.btn {
  @apply pl-6 hover:text-black hover:bg-gray-300 w-full h-14 flex justify-start items-center gap-11
}
</style>
