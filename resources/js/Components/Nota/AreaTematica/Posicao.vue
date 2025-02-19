<script setup>

import DOMPurify from 'dompurify'
import parses from '@/Hooks/parses.js'
</script>

<template>
  <div class="mt-5 pt-4">
    <div
      class="w-full bg-sky-700 text-white border-b-4 border-sky-400 h-14 py-4 px-6"
      @click="onActive(2)"
    >
      <p class="cursor-pointer font-semibold text-sm">
        Notas Técnicas - Pareceres:
      </p>
    </div>
    <div
      v-if="menuActive === 2"
      class="w-full h-auto"
    >
      <div class="w-full grid grid-cols-3 gap-4 pt-2">
        <div
          v-for="(item, index) in compare"
          :key="index"
          class="text-gray-700 bg-gray-200 p-2 mt-4 rounded-lg shadow-lg flex"
        >
          <div
            class="border-l-4 mr-4 ml-2"
            :class="getColorClass(item.posicao ? item.posicao.posicionamento : 'Sem Posicionamento')"
          />
          <div>
            <div class="flex justify-between items-center gap-4 w-full">
              <div class="font-semibold">
                {{ capitalizeTitle(item.orgao.nome) }}
              </div>
              <a
                v-if="showBtnDownloadDocument()"
                :href="route('relatorio.nota-tecnica.pdf', { id: item.id })"
                class="w-8 p-1 bg-sky-900 hover:bg-sky-900/70 hover:text-gray-700 shadow-lg rounded"
              >
                <i class="fa-solid fa-file-pdf text-white" />
              </a>
            </div>

            <div v-if="item.orgao.id == 1">
              <p v-html="DOMPurify.sanitize(item.conclusoes[0]?.conteudo)" />
            </div>
            <div v-if="item.orgao.id == 1">
              {{ parses.timestamp(item.conclusoes[0]?.created_at) }}
            </div>
            <div v-if="item.orgao.id != 1">
              {{ capitalizeTitle(item.posicao ? item.posicao.posicionamento : 'Sem Posicionamento') }}
            </div>
            <div v-if="item.orgao.id != 1">
              {{ parses.date(item.data_referencia) }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
