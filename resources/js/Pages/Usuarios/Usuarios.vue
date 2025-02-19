<script setup>
import MasterLogado from '@/Layouts/MasterLogado.vue'
import GestaoAcesso from '@/Layouts/Usuarios/GestaoAcesso.vue'
import GestaoGruposAcesso from '@/Layouts/Usuarios/GestaoGruposAcesso.vue'
import MenuGestaoAcessos from '@/Components/Usuarios/MenuGestaoAcessos.vue'
import { ref } from 'vue'
import GestaoNovosAcessos from '@/Layouts/Usuarios/GestaoNovosAcessos.vue'

defineOptions({ layout: MasterLogado })
defineProps({
  cpf: {
    type: String,
    required: true
  }
})

const tab = ref(1)
</script>

<template>
  <div class="w-full">
    <MenuGestaoAcessos
      :tab="tab"
      @change-tab="tab = $event"
    />
    <GestaoAcesso
      v-if="tab === 1"
      :cpf="cpf"
    />
    <GestaoGruposAcesso v-if="tab === 2" />
    <GestaoNovosAcessos v-if="tab === 3" />
  </div>
</template>

<style scoped></style>
