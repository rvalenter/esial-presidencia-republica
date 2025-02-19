<script setup>
import {ref} from "vue";
import {MaskInput} from "vue-3-mask";
import post from "@/Services/User/post_user.js";

const name = ref('');
const cell = ref('');
const email = ref('');
const emailDuplicated = ref(false);
const formError = ref(false);
const props = defineProps({
  cpf: {
    type: String,
    required: true,
  }
});
const emits = defineEmits(["cadastro"]);

async function checkEmail() {
  await post.validateEmail({
    // email: email.value,
    cpf: props.cpf,
  }).then(async res => {

    // emailDuplicated.value = res.data.data.validation;
    emailDuplicated.value = false;

    if (
      name.value !== ""
      && validateCell()
      // && validateEmail()
      // && !emailDuplicated.value
    ) {
      await post.validarCadastroIncial({
        name: name.value,
        cell: cell.value,
        email: email.value,
        cpf: props.cpf,
      }).then(res => {
        if (res.status === 200) {
          emits("cadastro", {
            name: name.value,
            cell: cell.value,
            email: email.value,
          });
        }
      });
    } else {
      formError.value = true;
    }
  });
}

function validateCell() {
  const regex = /^\(\d{2}\) \d{5}-\d{4}$/;

  return regex.test(cell.value) && cell.value !== "";
}

function validateEmail() {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  return regex.test(email.value) && email.value !== "";
}
</script>

<template>
  <div>
    <div class="mt-6">
      <p class="font-semibold">
        Dados cadastrais:
      </p>
    </div>
    <div class="grid grid-cols-1 gap-6 mt-4">
      <div>
        <input
          v-model="name"
          type="text"
          class="input-login"
          placeholder="Nome"
          @keyup.enter="checkEmail"
        >
        <p
          v-show="name === '' && formError"
          class="text-xs text-red-500 mt-0.5 ml-1 -mb-2"
        >
          Campo
          obrigatório
        </p>
      </div>
      <div class="w-full">
        <MaskInput
          v-model="cell"
          mask="(##) #####-####"
          class="input-login "
          placeholder="Celular"
          @keyup.enter="checkEmail"
        />
        <p
          v-show="!validateCell() && formError"
          class="text-xs text-red-500 mt-0.5 ml-1 -mb-2"
        >
          Campo obrigatório
        </p>
      </div>
      <!--            <div>-->
      <!--                <input @keyup.enter="checkEmail" type="email" class="input-login" placeholder="E-mail" v-model="email">-->
      <!--                <p class="text-xs text-red-500 mt-0.5 ml-1 -mb-2" v-show="(!validateEmail() || emailDuplicated) && formError">-->
      <!--                    {{ emailDuplicated && email !== "" ? 'E-mail já cadastrado' : 'Campo obrigatório' }}-->
      <!--                </p>-->
      <!--            </div>-->
      <div class="mt-5">
        <button
          class="bg-blue-500 hover:bg-blue-600/80 text-white py-2 px-4 rounded-full flex justify-center shadow-lg w-full"
          @click="checkEmail"
        >
          <p class="font-semibold">
            Enviar código
          </p>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.input-login {
  @apply w-full bg-gray-300/60 shadow-md font-semibold px-4 rounded-full border-transparent focus:ring-2 ring-blue-600
}
</style>
