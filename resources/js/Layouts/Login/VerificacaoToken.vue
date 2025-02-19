<script setup>
import { computed, ref } from 'vue'
import { useStore } from 'vuex'
import Token from '@/Components/Login/Token.vue'
import { router } from '@inertiajs/vue3'

const store = useStore()
const counter = ref(3)
const validation = computed(() => store.state.login.validation)
const passwordFail = ref(false)
const props = defineProps({
  cpf: {
    type: String,
    required: true
  }
})

async function validateToken(code) {
  store
    .dispatch('login/validateToken', {
      cpf: props.cpf,
      token: code.code,
      password: code.password,
      type: code.type
    })
    .then(() => {
      if (!validation.value) {
        // passwordFail.value = true
        // return;
        router.visit('/', {
          method: 'get',
          replace: true
        })
      }

      router.visit('/home', {
        method: 'get',
        replace: true
      })
    })
}

function resetToken() {
  if (counter.value === 0) {
    store.commit('login/setCpf', '')

    location.reload(true)
  }
}

function voltar() {
  store.commit('login/setCpf', '')

  location.reload(true)
}
</script>

<template>
  <Token
    :cpf="props.cpf"
    :validate-token="true"
    :counter="counter"
    :password-fail="passwordFail"
    @confirmar="validateToken"
    @voltar="voltar"
  />
</template>

<style scoped></style>
