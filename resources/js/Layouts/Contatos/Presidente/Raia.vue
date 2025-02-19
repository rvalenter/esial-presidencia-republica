<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps({
  indicadors: {
    type: Array,
    default: []
  }
});

function linkUrl(id) {
  return `/contato/${id}/id`;
}

</script>

<template>
  <div class="flex flex-wrap gap-1 mb-12">
    <div
      v-for="indicador in indicadors"
      :key="indicador.id"
      class="cursor-pointer"
    >
      <Link
        :href="linkUrl(indicador.id)"
        title="teste"
        class="cursor-pointer"
        type="button"
      >
        <VTooltip>
          <a>
            <div class="flex justify-start items-center">
              <div
                class="bg-[url('../../public/src/img/bg-avatar.jpg')] image-container h-5 w-5 rounded-full border border-sky-700 bg-cover bg-bottom bg-no-repeat flex items-center justify-center shadow-box ring-1 ring-gray-100"
              >
                <img
                  id="contato-img"
                  :src="indicador.photo"
                  class="absolute inset-0 z-10"
                >
              </div>
            </div>
          </a>
          <template #popper>
            <div class="flex justify-between items-end min-w-96 p-2">
              <div
                class="bg-[url('../../public/src/img/bg-avatar.jpg')] image-container rounded-full h-28 w-28 bg-cover bg-bottom bg-no-repeat flex items-center justify-center shadow-box ring"
              >
                <img
                  id="contato-img"
                  :src="indicador.photo"
                  class="absolute inset-0 z-10"
                >
              </div>
              <div class="pl-4 w-8/12">
                <p class="text-2xl text-nowrap">
                  {{ indicador.nome }}
                </p>
                <p>{{ indicador.uf }}</p>
              </div>
            </div>
          </template>
        </VTooltip>
      </Link>
    </div>
  </div>
</template>

<style scoped>
.image-container {
  overflow: hidden;
  position: relative;
}

.image-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
