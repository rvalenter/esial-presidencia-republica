<script setup>
import {defineProps, ref, watch} from 'vue';
import {MaskInput} from "vue-3-mask";
import post from "@/Services/User/post_user.js";
import Autocomplete from "@/Components/Perfil/Autocomplete.vue";
import spinner from "@/Components/Global/Spinner.vue";
import success from "@/Hooks/success.js";

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
});
const emits = defineEmits(['updateUser']);
const nome = ref(props.user.nome);
const email = ref(props.user.email);
const telefone = ref(props.user.telefone);
const orgao = ref(props.user.orgao ? props.user.orgao.nome : '');
const setor = ref(props.user.setor ? props.user.setor.nome : '');
const cargo = ref(props.user.cargo ? props.user.cargo.nome : '');
const show = ref(false);
const autocompleteOrgao = ref(false);
const autocompleteSetor = ref(false);
const autocompleteCargo = ref(false);
const disableEditEmail = ref(true);
const EditEmail = ref(false);
const emailError = ref('');
const codeSend = ref(false);
const code = ref('');
const checkEmail = ref(false);
const codeError = ref(false);

function salvarAlteracoes() {
  post.updatePerson({
    id: props.user.id,
    nome: nome.value,
    telefone: telefone.value,
    orgao: orgao.value,
    setor: setor.value,
    cargo: cargo.value
  }).then(res => {
    if (res.status === 200) {
      show.value = false;
      emits('updateUser');
      success.cofetti();
    }
  });
}

function setNewComplete(value) {
  show.value = true;

  switch (value.type) {
    case 'orgao':
      autocompleteOrgao.value = false;
      orgao.value = value.search;
      break;
    case 'setor':
      autocompleteSetor.value = false;
      setor.value = value.search;
      break;
    case 'cargo':
      autocompleteCargo.value = false;
      cargo.value = value.search;
      break;
  }
}

function sendVerificationCode() {
  post.sendVerificationCode({
    email: email.value
  }).then(res => {
    if (res.status === 200) {
      codeSend.value = true;
    }
  }).catch(err => {
    emailError.value = email.value;
    email.value = props.user.email;
    EditEmail.value = false;
  });
}

watch(
  () => email.value,
  () => {
    EditEmail.value = email.value !== props.user.email;
  }
);

watch(
  () => code.value,
  () => {
    if (code.value.length === 4) {
      checkEmail.value = true;

      post.updateEmail({
        id: props.user.id,
        email: email.value,
        code: code.value
      }).then(res => {
        if (res.status === 200) {
          EditEmail.value = false;
          disableEditEmail.value = true;
          emits('updateUser');
          code.value = '';
          checkEmail.value = false;
          codeSend.value = false;
          codeError.value = false;
          success.cofetti();
        }
      }).catch(err => {
        codeError.value = true;
        code.value = '';
        checkEmail.value = false;
      })
    }
  }
);
</script>

<template>
  <div class="mt-4 col-span-2 min-h-96">
    <div class="grid grid-cols-2 gap-8 mb-10 pb-24">
      <div class="h-10">
        <label for="nome">Nome:</label>
        <input
          id="nome"
          v-model="nome"
          type="text"
          name="nome"
          class="input-login"
          :class="[!disableEditEmail ? 'cursor-not-allowed text-gray-400' : 'cursor-default text-gray-800']"
          :disabled="!disableEditEmail"
          :readonly="!disableEditEmail"
          @keyup="show = true"
        >
      </div>
      <div
        class="w-full flex justify-between items-end relative "
        :class="[emailError !== '' ? '-top-6' : '']"
      >
        <div
          class="w-full relative "
          :class="[emailError !== '' ? 'top-6' : '']"
        >
          <label for="email">E-mail:</label>
          <input
            id="email"
            v-model="email"
            type="email"
            name="email"
            class="mt-1.5 h-10 w-full bg-white shadow-lg font-semibold px-6  border-gray-300 focus:ring-2 ring-blue-600 "
            :class="[
              (disableEditEmail) ? 'cursor-not-allowed text-gray-400' : 'cursor-default text-gray-800',
              $page.props.auth.access.includes(2) ? 'cursor-default rounded-l-full' : 'cursor-not-allowed text-gray-400 rounded-full'
            ]"
            :disabled="(disableEditEmail)"
            :readonly="(disableEditEmail)"
          >
          <span
            v-if="emailError !== ''"
            class="text-red-500 text-xs"
          >Endereço de e-mail incompatível: <strong>{{
            emailError
          }}</strong></span>
        </div>
        <button
          v-if="!EditEmail && $page.props.auth.access.includes(2)"
          class="h-10 w-12 bg-blue-500 rounded-r-full text-white"
          @click="disableEditEmail = !disableEditEmail"
        >
          <i
            v-if="disableEditEmail"
            class="fa-solid fa-lock"
          />
          <i
            v-if="!disableEditEmail"
            class="fa-solid fa-lock-open"
          />
        </button>
        <div v-if="!codeSend">
          <button
            v-if="EditEmail"
            class="h-10 w-64 bg-green-600 hover:bg-green-500 rounded-r-full text-white flex justify-around items-center"
            @click="sendVerificationCode"
          >
            <span class="text-sm font-semibold">Enviar código de verificação</span>
            <i class="fa-solid fa-envelope-circle-check" />
          </button>
        </div>
      </div>
      <div class="w-full">
        <label for="text">Telefone:</label>
        <MaskInput
          v-model="telefone"
          :value="telefone"
          mask="(##) #####-####"
          class="input-login"
          placeholder="Celular"
          :class="[!disableEditEmail ? 'cursor-not-allowed text-gray-400' : 'cursor-default text-gray-800']"
          :disabled="!disableEditEmail"
          :readonly="!disableEditEmail"
          @keyup="show = true"
        />
      </div>
      <div class="relative group">
        <label for="orgao">Orgão:</label>
        <input
          id="orgao"
          v-model="orgao"
          type="text"
          name="orgao"
          class="input-login"
          autocomplete="off"
          :class="[!disableEditEmail || !$page.props.auth.access.includes(1) ? 'cursor-not-allowed text-gray-400' : 'cursor-default text-gray-800']"
          :disabled="!disableEditEmail || !$page.props.auth.access.includes(1)"
          :readonly="!disableEditEmail || !$page.props.auth.access.includes(1)"
          @keyup="() => {show = true}"
          @focusin="autocompleteOrgao = true"
        >
        <Autocomplete
          v-if="orgao !== '' && autocompleteOrgao"
          :type="'orgao'"
          :search="orgao"
          @set-new-complete="setNewComplete"
        />
      </div>
      <div class="relative">
        <label for="setor">Setor:</label>
        <input
          id="setor"
          v-model="setor"
          type="text"
          name="setor"
          class="input-login"
          autocomplete="off"
          :class="[!disableEditEmail ? 'cursor-not-allowed text-gray-400' : 'cursor-default text-gray-800']"
          :disabled="!disableEditEmail"
          :readonly="!disableEditEmail"
          @keyup="() => {show = true}"
          @focus="autocompleteSetor = true"
        >
        <Autocomplete
          v-if="setor !== '' && autocompleteSetor"
          :type="'setor'"
          :search="setor"
          @set-new-complete="setNewComplete"
        />
      </div>
      <div class="relative">
        <label for="cargo">Cargo:</label>
        <input
          id="cargo"
          v-model="cargo"
          type="text"
          name="cargo"
          class="input-login"
          autocomplete="off"
          :class="[!disableEditEmail ? 'cursor-not-allowed text-gray-400' : 'cursor-default text-gray-800']"
          :disabled="!disableEditEmail"
          :readonly="!disableEditEmail"
          @keyup="() => {show = true}"
          @focus="autocompleteCargo = true"
        >
        <Autocomplete
          v-if="cargo !== '' && autocompleteCargo"
          :type="'cargo'"
          :search="cargo"
          @set-new-complete="setNewComplete"
        />
      </div>
    </div>
  </div>
  <div class="col-span-2 -mt-24">
    <Transition>
      <div
        v-if="disableEditEmail && show && !autocompleteOrgao && !autocompleteSetor && !autocompleteCargo"
        class="w-full flex justify-center items-center gap-4 col-span-2 mb-10"
      >
        <button
          class="text-gray-600 bg-gray-300/85 hover:bg-gray-400/50 px-4 py-2 rounded-lg shadow-lg"
          @click="show = false"
        >
          Cancelar
        </button>
        <button
          class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg"
          @click="salvarAlteracoes"
        >
          Salvar Alterações
        </button>
      </div>
    </Transition>
  </div>
  <div
    v-if="codeSend"
    class="z-50 w-full h-full absolute top-0 left-0 bg-black/40 flex justify-center items-center"
  >
    <div class="w-4/12 h-1/6 min-h-44 bg-white shadow-2xl rounded-2xl">
      <div
        class="border-b border-gray-300 h-10 px-4 bg-sky-600 text-white rounded-t-2xl flex justify-between items-center"
      >
        <p class="font-semibold">
          Validação:
        </p>
        <button @click="() => { codeSend = false }">
          <i class="fa-solid fa-xmark" />
        </button>
      </div>
      <div
        v-if="!checkEmail"
        class="grid grid-cols-1 justify-items-center h-full p-4"
      >
        <div class="flex justify-start-">
          <p class="text-center">
            Enviamos um código de verificação para o e-mail: <strong>{{ email }}</strong>
          </p>
        </div>
        <div class="-mt-6">
          <div>
            <MaskInput
              v-model="code"
              type="text"
              placeholder="____"
              class="bg-gray-50 w-64 text-center text-xl tracking-[15px] h-10"
              :class="[codeError ? 'border-red-500' : 'border-gray-300']"
              mask="####"
            />
          </div>
          <div class="w-full flex justify-center mt-1">
            <span
              v-if="codeError"
              class="text-red-500 font-semibold text-xs"
            >Código errado, gentileza ajustar!</span>
          </div>
        </div>
      </div>
      <div
        v-if="checkEmail"
        class="w-full h-4/6 flex justify-center items-center"
      >
        <div class="flex justify-around items-center">
          <spinner>
            Salvando seu novo e-mail!
          </spinner>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.input-login {
  @apply mt-1.5 h-10 w-full bg-white shadow-lg font-semibold px-6 rounded-full border-gray-300 focus:ring-2 ring-blue-600
}

.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
