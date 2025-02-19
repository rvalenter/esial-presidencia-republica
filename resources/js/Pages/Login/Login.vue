<script setup>
import { computed, ref } from 'vue'
import { useStore } from 'vuex'
import Master from '@/Layouts/Master.vue'
import LogoApp from '@/Components/Global/LogoApp.vue'
import { MaskInput } from 'vue-3-mask'
import AtualizacaoCadastral from '@/Layouts/Login/AtualizacaoCadastral.vue'
import VerificacaoToken from '@/Layouts/Login/VerificacaoToken.vue'

defineOptions({ layout: Master })

const store = useStore()
const cpf = computed(() => store.state.login.cpf)
const validation = computed(() => store.state.login.validation)
const validationData = computed(() => store.state.login.validationData)
const message = computed(() => store.state.login.message)

const login = () => {
  store.dispatch('login/login', cpf.value)
}
const setCpf = (value) => {
  store.commit('login/setCpf', value)
}
</script>

<template>
  <div :class="[$page.props.auth.isMobile ? 'h-full bg-gradient-to-b from-blue-600 to-cyan-500' : 'grid sm:grid-cols-5 md:grid-cols-5 lg:grid-cols-12 gap-0 h-full']">
    <div
      v-if="$page.props.auth.isMobile"
      class="flex justify-center items-start bg-white bg-mobile h-[57%] pt-8"
    >
      <img
        src="/src/img/planalto.png"
        alt="Logotipo do Planalto"
        class="h-48"
      >
    </div>
    <div
      v-if="!$page.props.auth.isMobile"
      class="lg:col-span-7 md:col-span-6 flex justify-center items-center"
    >
      <div
        class="bg-[url('/src/img/palacio-planalto.jpg')] bg-cover bg-center mix-blend-overlay w-full h-full bg-fixed opacity-40"
      />
      <img
        src="/src/img/planalto.png"
        alt="Logotipo do Planalto"
        class="w-7/12 absolute"
      >
      <div class="absolute bottom-5 flex justify-center items-end">
        <div class="text-center">
          <div>
            <img
              src="/src/img/sri.png"
              alt="Logotipo da SRI"
              class="w-20 mx-auto"
            >
          </div>
          <p class="text-sm text-black font-semibold mt-2">
            Presidência da República
          </p>
          <p class="text-sm text-black font-semibold">
            Secretaria de Relações Institucionais - SRI
          </p>
          <p class="text-sm text-black font-semibold">
            Secretaria Especial de Assuntos Parlamentares - SEPAR
          </p>
        </div>
      </div>
    </div>
    <div
      class="col-span-5 flex justify-center items-center"
      :class="[ !$page.props.auth.isMobile ? 'bg-gradient-to-b from-blue-600 to-cyan-500 shadow-2xl' : 'p-8']"
    >
      <div
        :class="[!$page.props.auth.isMobile
          ? 'sm:w-8/12 2xl:w-6/12 block:h-3/6 min-h-[400px] min-w-[300px] rounded-2xl  md:bg-white/95 px-10 py-10'
          : '-mt-[85%] bg-sky-800/90 rounded-xl p-8 shadow-2xl']"
      >
        <LogoApp :text-color="'text-white'" />
        <div v-show="validationData.status === 0 || Object.values(validationData).length === 0">
          <div class="mt-6 text-center">
            <p
              class="text-sm"
              :class="[$page.props.auth.isMobile ? 'text-white': 'text-gray-800']"
            >
              Bem vindo ao Sistema Eletrônico de Acompanhamento Legislativo, <strong>e-SIAL</strong>, para logar-se entre com seu CPF.
            </p>
          </div>
          <div class="mt-12 flex w-full">
            <div class="w-full">
              <MaskInput
                mask="###.###.###-##"
                alt="Inserir CPF neste campo para logar-se no sistema"
                class="input-login"
                placeholder="Seu CPF"
                @keyup="setCpf($event.target.value)"
                @keyup.enter="login()"
              />
            </div>
          </div>
          <div
            v-show="!validation"
            class="w-full -mb-[17.5px]"
          >
            <p class="text-xs text-red-600 mt-0.5">
              {{ message }}
            </p>
          </div>
          <div class="mt-8 w-full flex justify-center">
            <button
              class="bg-red-500 hover:bg-red-600/80 text-white py-2 px-4 rounded-full flex justify-center shadow-lg w-full"
              @click="login()"
            >
              <p class="font-semibold">
                Conectar-se
              </p>
            </button>
          </div>
        </div>
        <div
          v-if="validationData.status === 1"
          class="min-w-[260px]"
        >
          <AtualizacaoCadastral :cpf="cpf" />
        </div>
        <div
          v-if="validationData.status === 2"
          class="min-w-[260px]"
        >
          <VerificacaoToken :cpf="cpf" />
        </div>
      </div>
    </div>
    <div
      v-if="$page.props.auth.isMobile"
      class="absolute bottom-5 flex justify-center items-end w-full"
    >
      <div class="text-center">
        <div>
          <img
            src="/src/img/sri.png"
            alt="Logotipo da SRI"
            class="w-12 mx-auto"
          >
        </div>
        <p class="text-xs text-gray-600 mt-2">
          Presidência da República
        </p>
        <p class="text-xs text-gray-600">
          Secretaria de Relações Institucionais - SRI
        </p>
        <p class="text-xs text-gray-600">
          Secretaria Especial de Assuntos Parlamentares - SEPAR
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.input-login {
  @apply w-full bg-gray-300/60 shadow-md font-semibold px-4 rounded-full border-transparent focus:ring-2 ring-blue-600
}
.bg-mobile {
  border-bottom-left-radius: 70% 20%;
  border-bottom-right-radius: 70% 20%;
}
</style>
