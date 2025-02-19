<script setup>
import { computed, ref } from 'vue'
import { useStore } from 'vuex'
import Cadastro from '@/Components/Login/Cadastro.vue'
import Token from '@/Components/Login/Token.vue'
import { router } from '@inertiajs/vue3'

const store = useStore()
const validateEmailToken = ref(false)
const name = ref('')
const cell = ref('')
const email = ref('')
const counter = ref(3)
const validation = computed(() => store.state.login.validation)
const props = defineProps({
  cpf: {
    type: String,
    required: true
  }
})

function cadastro(params) {
  validateEmailToken.value = true
  name.value = params.name
  cell.value = params.cell
  email.value = params.email
}

const voltar = () => {
  validateEmailToken.value = false
  name.value = ''
  cell.value = ''
  email.value = ''
}

const confirmEmail = (condigo) => {
  store.dispatch('login/validateEmail', {
    name: name.value,
    cell: cell.value,
    email: email.value,
    cpf: props.cpf,
    code: condigo
  }).then(() => {
    if (!validation.value) {
      counter.value--
      resetToken()
      return
    }

    router.visit('/home', {
      method: 'get',
      replace: true
    })
  })
}

function resetToken() {
  if (counter.value === 0) {
    validateEmailToken.value = false
  }
}
</script>

<template>
  <Cadastro
    v-show="!validateEmailToken"
    :cpf="props.cpf"
    @cadastro="cadastro"
  />
  <Token
    v-show="validateEmailToken"
    :cpf="props.cpf"
    :validate-token="false"
    :name="name"
    :cell="cell"
    :email="email"
    :counter="counter"
    @voltar="voltar"
    @confirmar="confirmEmail"
  />
</template>

<style scoped>

</style>
