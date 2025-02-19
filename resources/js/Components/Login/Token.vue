<script setup>
import post from '@/Services/User/post_user.js'
import { computed, onMounted, ref, watch } from 'vue'
import InputOtp from 'primevue/inputotp'
import { useStore } from 'vuex'
import Password from 'primevue/password'

const store = useStore()
const securityType = computed(() => store.state.login.security)
const codigo = ref('')
const password = ref('')
const hashedEmail = ref('')
const emits = defineEmits(['voltar', 'confirmar'])
const props = defineProps({
  cpf: {
    type: String,
    required: true
  },
  name: {
    type: String,
    required: false,
    default: ''
  },
  cell: {
    type: String,
    required: false,
    default: ''
  },
  email: {
    type: String,
    required: false,
    default: ''
  },
  validateToken: {
    type: Boolean,
    required: true
  },
  counter: {
    type: Number,
    required: false,
    default: 3
  },
  passwordFail: {
    type: Boolean,
    required: true
  }
})
const state = ref(1)
const showBtnCheck = computed(() => {
  return (
    (securityType.value.tipo_acesso === 3 &&
      password.value.length >= 12 &&
      codigo.value.length === 4) ||
    (securityType.value.tipo_acesso === 2 && password.value.length >= 12) ||
    (securityType.value.tipo_acesso === 1 && codigo.value.length === 4)
  )
})

function getSecurityType() {
  store.dispatch('login/getSecurity', props.cpf)
}

function voltar() {
  emits('voltar')
}

async function emitToken() {
  hashedEmail.value = ''

  await post.emitToken({ cpf: props.cpf }).then((res) => {
    hashedEmail.value = res.data.data.email
  })
}

onMounted(() => {
  getSecurityType()
})

watch(
  () => securityType.value,
  () => {
    if (securityType.value.tipo_acesso === 1) {
      emitToken()
      state.value = 1
      return
    }

    if (securityType.value.tipo_acesso === 2) {
      state.value = 2
      return
    }

    if (securityType.value.tipo_acesso === 3) {
      emitToken()
      state.value = 2
    }
  }
)

watch(
  () => props.passwordFail,
  (newPassword) => {
    if (newPassword) {
      state.value = 1
      password.value = ' '
      getSecurityType()
      emitToken()
    }
  }
)
</script>

<template>
  <div>
    <div class="mt-6">
      <p v-show="state === 1" class="font-semibold">Verificação de E-mail:</p>
      <p v-show="state === 2" class="font-semibold">Autenticação de Acesso:</p>
    </div>
    <div v-show="passwordFail" class="-mb-2">
      <p class="text-red-500 font-semibold text-xs">
        A senha digitada não pode ser velidada, seu método de acesso foi alterado para: token de
        validação por motivo de segurança.
      </p>
    </div>
    <div class="h-64 mt-8">
      <div v-if="props.counter < 3" class="-mt-5 mb-4">
        <p class="text-red-600 text-xs">
          Restam <strong>{{ props.counter }}</strong> tentativas
        </p>
      </div>
      <div class="mt-4 mb-4">
        <p v-show="state === 1">
          Enviamos um código de verificação para o e-mail: <strong>{{ hashedEmail }}</strong>
        </p>
        <p v-show="state === 2">Insira sua senha de login:</p>
      </div>
      <div v-show="state === 1 && securityType.tipo_acesso !== 2" class="card flex justify-center">
        <div>
          <p class="text-xs mb-2">Código:</p>
          <InputOtp v-model="codigo" />
        </div>
      </div>
      <div
        v-show="state === 2 && securityType.tipo_acesso !== 1"
        class="card mt-3 grid grid-cols-1 gap-4"
      >
        <div>
          <p class="text-xs mb-2">Senha:</p>
          <Password v-model="password" :feedback="false" />

        </div>
        <button @click="
            $emit('confirmar', { code: codigo, password: 'asdf', type: securityType.tipo_acesso })
          " class="text-blue-500 text-xs mt-2">
          Esqueci minha senha
        </button>
      </div>
      <div
        v-if="!showBtnCheck && securityType.tipo_acesso !== 2 && securityType.tipo_acesso !== 1"
        class="mt-5"
      >
        <button
          v-if="state === 2"
          class="bg-blue-500 hover:bg-blue-600/80 text-white py-2 px-4 rounded-full flex justify-center shadow-lg w-full"
          @click="state = 1"
        >
          <span class="font-semibold">Ir Para o Código</span>
        </button>
        <button
          v-if="state === 1"
          class="bg-blue-500 hover:bg-blue-600/80 text-white py-2 px-4 rounded-full flex justify-center shadow-lg w-full"
          @click="state = 2"
        >
          <span class="font-semibold">Ir Para a Senha</span>
        </button>
      </div>
      <div
        v-if="showBtnCheck || securityType.tipo_acesso === 2 || securityType.tipo_acesso === 1"
        class="mt-5"
      >
        <button
          class="bg-blue-500 hover:bg-blue-600/80 text-white py-2 px-4 rounded-full flex justify-center shadow-lg w-full"
          @click="
            $emit('confirmar', { code: codigo, password: password, type: securityType.tipo_acesso })
          "
        >
          <span class="font-semibold">Entrar</span>
        </button>
      </div>
    </div>
    <div class="flex justify-between items-end">
      <button v-if="securityType.tipo_acesso !== 2" class="hover:text-blue-600" @click="emitToken">
        <span class="text-xs ml-2">Reenviar código</span>
      </button>
      <button class="hover:text-blue-600" @click="voltar">
        <span class="text-xs text-red-500">Mudar de conta</span>
      </button>
    </div>
  </div>
</template>

<style scoped>
.input-login {
  @apply w-full bg-gray-300/60 shadow-md font-semibold px-4 rounded-full border-transparent focus:ring-2 ring-blue-600;
}
</style>
