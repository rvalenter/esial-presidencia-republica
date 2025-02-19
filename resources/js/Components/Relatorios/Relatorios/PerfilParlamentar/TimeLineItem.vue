<script setup>
const props = defineProps({
  type: Boolean
});
</script>

<template>
  <div class="w-full min-h-14 flex cursor-pointer hover:bg-gray-200/60 group">
    <div class="col-span-1 flex justify-center items-center relative pl-5">
      <div class="h-full border-l-2 border-neutral-200 absolute" />
      <div
        :class="[type ? 'bg-red-200' : 'bg-sky-200']"
        class="h-5 w-5 group-hover:scale-125 rounded-full absolute text-white"
      />
      <div
        :class="[type ? 'bg-red-600 group-hover:bg-red-700' : 'bg-sky-600 group-hover:bg-sky-700']"
        class="h-3 w-3 group-hover:scale-125 rounded-full absolute text-[7px] text-white flex justify-center items-center"
      >
        <i
          v-if="!type"
          class="fa-solid fa-thumbs-up absolute"
        />
        <i
          v-if="type"
          class="fa-solid fa-thumbs-down absolute"
        />
      </div>
    </div>
    <div class="w-full grid grid-cols-12 col-span-11 py-6">
      <div class="col-span-2 text-center">
        26/02/1985
      </div>
      <div class="col-span-8">
        <p>
          Aconteceu nesta data o nascimento do Raphael Reges Valente por isso estamos testando esse campo de texto para
          nosso app
        </p>
      </div>
      <div class="col-span-2 text-center">
        Favorável
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
