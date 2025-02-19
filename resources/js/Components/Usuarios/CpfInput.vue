<script setup>
import {MaskInput} from "vue-3-mask";
import {ref, watch, onMounted} from "vue";
import useCPFValidator from "@/Hooks/cpf.js";
import post from "@/Services/User/post_user.js"
import CriarCadastro from "@/Layouts/Usuarios/CriarCadastro.vue";

const props = defineProps({
    cpf: {
        type: String,
        required: false,
        default: ''
    }
});
const cadastro = ref(false);
const inputForm = ref('');
const {isValid, validateCPF} = useCPFValidator();
const emits = defineEmits(["cpf", "error"]);
const email = ref('');
const show = ref(false);

function isValidGovernmentEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.(gov\.br|leg\.br)$/i;
  return regex.test(email);
}

watch(
    () => inputForm.value,
    () => {
        if(inputForm.value === '') {
            emits("error");
            show.value = false;
        }

        validateCPF(inputForm.value);

        if (isValid.value) {
            consultarCadastro(inputForm.value)
        } else {
            emits("error");
        }
    }
);

onMounted(() => {
    if (props.cpf) {
        inputForm.value = props.cpf;
        validateCPF(props.cpf);
    }
});

async function consultarCadastro(cpf) {
    show.value = false;

    await post.consultarCadastroCpf({
        cpf: cpf,
    }).then(res => {
        if (res.data.data.cadastro.length == 0) {
            cadastro.value = false;
        } else {
            cadastro.value = true;
            emits("cpf", {
                status: isValid.value,
                name: 'cpf',
                value: inputForm.value
            });
        }
    }).then(() => {
        show.value = true;
    });
}
</script>

<template>
  <div class="w-full gap-4 flex justify-start items-end pr-10 col-span-12">
    <label class="font-medium text-gray-600 mr-20">CPF:</label>
    <MaskInput
      v-model="inputForm"
      :value="inputForm"
      mask="###.###.###-##"
      alt="Inserir CPF neste campo para fazer gestão de usuários."
      class="transition-all duration-300 ease-in-out font-medium border-b-2 hover:bg-gray-50 bg-white border-gray-400 focus:border-blue-600 w-96 border-t-0 border-l-0 border-r-0 focus:border-t-0 focus:border-l-0 focus:border-r-0 focus:ring-0"
      type="search"
    />
    <Transition>
      <div class="flex gap-4">
        <input
          v-if="isValid && !cadastro && show"
          v-model="email"
          placeholder="E-mail"
          type="email"
          class="transition-all duration-300 ease-in-out w-96 font-medium border-b-2 focus:bg-gray-50 bg-gray-200 border-gray-400 focus:border-blue-600 border-t-0 border-l-0 border-r-0 focus:border-t-0 focus:border-l-0 focus:border-r-0 focus:ring-0"
        >
        <div
          v-if="show"
          class="relative -top-2 w-96"
        >
          <CriarCadastro
            v-if="isValid && !cadastro && email !== '' && isValidGovernmentEmail(email)"
            :cpf="inputForm"
            :email="email"
            @cadastrado="consultarCadastro(inputForm)"
          />
          <i
            v-show="isValid && cadastro"
            class="fa-solid fa-circle-check fa-lg text-green-500"
          />
          <i
            v-show="!isValid"
            class="fa-solid fa-circle-xmark fa-lg text-red-500"
            title="O CPF não é válido!"
          />
        </div>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
