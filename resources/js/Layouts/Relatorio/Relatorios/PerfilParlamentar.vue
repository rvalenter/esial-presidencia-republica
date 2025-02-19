<script setup>
import {ref, watch} from "vue";
import get_report from "@/Services/Report/get_report";

const parlamentar = ref(null);
const parlamentarSearch = ref(null);
const parlamentarId = ref(null);
const show = ref(false);

async function listarParlamentar() {
  if (!parlamentarSearch.value) {
    parlamentar.value = null;
    return;
  }

  show.value = true;

  await get_report.fetchList(parlamentarSearch.value)
    .then(response => {
      parlamentar.value = response.data.data;
    })
    .catch(error => {
      console.log(error);
    });
}

function debounce(func, wait, immediate) {
  let timeout;
  return function () {
    const context = this, args = arguments;
    const later = function () {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    const callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
}

function setPhoto(photoBase64) {
  return `data:image/png;base64,${photoBase64}`;
}

function setId(id, nome) {
  parlamentarId.value = id;
  parlamentar.value = null;
  show.value = false;
}

watch(parlamentarSearch, debounce(() => {
  listarParlamentar();
}, 500));
</script>

<template>
  <div class="w-full h-full pt-2 relative">
    <div class="w-full px-4 py-2 absolute">
      <label
        for="search"
        class="font-semibold ml-1 text-gray-600"
      >Busque pelo nome do parlamentar:</label>
      <input
        id="search"
        v-model="parlamentarSearch"
        type="search"
        class="w-full h-8 p-0 mt-2 px-4 bg-neutral-100 border-gray-200 shadow-md rounded-full mb-4"
        @keyup="listarParlamentar"
      >
      <div
        v-if="parlamentar && show"
        class="w-full -mt-9 pt-8 bg-gray-200 h-fit rounded-2xl shadow-lg px-2"
      >
        <ol>
          <li
            v-for="item in parlamentar"
            :key="item.id"
            class="flex items-center justify-between py-2 border-b border-gray-200 cursor-pointer hover:bg-gray-50 rounded-full px-2"
            @click="setId(item.id, item.nome)"
          >
            <div class="flex items-center">
              <div
                class="bg-[url('../../public/src/img/bg-avatar.jpg')] relative image-container rounded-full h-10 w-10 bg-cover bg-center bg-no-repeat flex items-center justify-center ring-2 ring-sky-600"
              >
                <img
                  :src="setPhoto(item.get_photo.foto)"
                  class="absolute inset-0 z-10"
                >
              </div>
              <div class="ml-4">
                <p class="text-sm font-semibold">
                  {{ item.nome }}
                </p>
                <p class="text-xs text-gray-500">
                  {{ item.parlamentar_dados.siglaPartidoUf }}
                </p>
              </div>
            </div>
            <div class="flex items-center">
              <button class="text-xs font-semibold text-blue-500">
                Ver perfil
              </button>
            </div>
          </li>
        </ol>
      </div>
    </div>
    <div
      v-if="parlamentarId"
      class="w-full h-full pt-16"
    >
      <iframe
        class="w-full h-full"
        :src="'/relatorios/perfil-parlamentar/' + parlamentarId + '/id' "
      />
    </div>
    <div
      v-if="!parlamentarId"
      class="w-full h-[90%] flex items-center justify-center"
    >
      <div>
        <h1 class="text-4xl font-semibold">
          Perfil Parlamentar
        </h1>
        <p class="font-light">
          Selecione um Parlamentar Para Seguir
        </p>
      </div>
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
