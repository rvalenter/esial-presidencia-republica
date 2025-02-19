<script setup>
const props = defineProps({
  textColor: {
    type: String,
    required: false,
    default: 'text-gray-500'
  },
  textSize: {
    type: String,
    required: false,
    default: 'text-2xl'
  },
  height: {
    type: String,
    required: false,
    default: 'h-14'
  },
  width: {
    type: String,
    required: false,
    default: 'w-12'
  },
  onlyImg: {
    type: Boolean,
    required: false,
    default: false
  },
  bg: {
    type: Boolean,
    required: false,
    default: false
  }
})
</script>

<template>
  <div class="w-full h-full flex justify-center items-center">
    <p
      v-show="!props.onlyImg"
      class="relative top-1 right-2 font-sans"
      :class="[props.textColor, props.textSize]"
    >
      e-SIAL
    </p>
    <div
      class=" rounded-lg"
      :class="[bg ? 'p-1 bg-sky-700/60': '']"
    >
      <img
        src="../../../../public/src/img/logo_esial_2.png"
        :class="[props.height, props.width]"
        class="drop-shadow-lg"
        alt="logo app"
      >
    </div>
  </div>
</template>

<style scoped>

</style>
