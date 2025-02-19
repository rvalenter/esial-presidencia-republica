<script setup>
import MasterLogado from '@/Layouts/MasterLogado.vue'
import { onMounted, ref } from 'vue'
import Formulario from '@/Components/Perfil/Formulario.vue'
import Acessos from '@/Components/Perfil/Acessos.vue'
import Menu from '@/Layouts/Perfil/Menu.vue'
import Banner from '@/Layouts/Perfil/Banner.vue'
import UsuariosCadastrados from '@/Components/Perfil/UsuariosCadastrados.vue'
import get from '@/Services/User/get_user.js'
import Login from '@/Layouts/Perfil/Login.vue'

defineOptions({ layout: MasterLogado })

const props = defineProps({
  id: Number,
  owner: { type: Boolean, default: false }
})

const user = ref({ telefone: null })
const tab = ref(3)
const show = ref(false)
const relatedUsers = ref(false)

const getData = () => {
  get.profile(props.id).then((response) => {
    user.value = response.data.data.user
    show.value = true
    relatedUsers.value = response.data.data.user.my_users_count > 0
  })
}

onMounted(getData)
</script>

<template>
  <div class="w-full h-full pt-4">
    <Banner :id="props.id" :user="user" />
    <Menu :id="user.id" :related-users="relatedUsers" :tab="tab" @change-tab="tab = $event" />
    <div v-if="show" class="grid grid-cols-2 gap-4 w-full">
      <login v-if="tab === 5" :id="user.id" />
      <Acessos v-if="tab === 2" :id="user.id" />
      <Formulario v-if="tab === 3" :user="user" @update-user="getData" />
      <UsuariosCadastrados v-if="tab === 4" :user="user" />
    </div>
  </div>
</template>

<style scoped></style>
