<script setup>
import CpfInput from "@/Components/Usuarios/CpfInput.vue";
import {ref} from "vue";
import get from "@/Services/User/get_user.js"
import CheckBox from "@/Components/Usuarios/checkBox.vue";
import post from "@/Services/User/post_user.js";
import PublicoTipo from "@/Components/Usuarios/PublicoTipo.vue";

const props = defineProps({
    cpf: {
        type: String,
        required: false,
        default: ''
    }
});
const cpfValidated = ref(false);
const niveisAcesso = ref({});
const usuarioAcessos = ref({});
const cpf = ref(props.cpf ?? '');
const idUsuario = ref(null);
const error = ref(false);
const publico = ref(null);

function defineCpfValidated(inputForm) {
    error.value = false;
    cpf.value = inputForm.value;
    cpfValidated.value = inputForm.status;
    idUsuario.value = 0;

    if (inputForm.status) {
        consultarIdUsuario()
    }
}

async function consultarIdUsuario() {
    await post.consultarCadastroCpf({cpf: cpf.value}).then(res => {
        if (res.data.data.cadastro.length > 0) {
            idUsuario.value = res.data.data.cadastro.shift().id
            defineAcessosCpf();
        }
    });
}

async function defineAcessosCpf() {
    await get.acessosCpf(idUsuario.value).then(res => {
        usuarioAcessos.value = (res.data.data).map(acessos => {
            return acessos.esial_nivel_acesso_id;
        });

        fetchNiveisAcesso()
    });
}

async function fetchNiveisAcesso() {
    await get.niveisAcesso().then(res => {
        niveisAcesso.value = res.data.data
    });
}

function isChecked(id) {
    return usuarioAcessos.value.includes(id);
}

function callBackUpdatePublicoAlvo() {
    publico.value.updateAccessGroup();
}
</script>

<template>
  <div class="grid grid-cols-12 gap-4">
    <CpfInput
      :cpf="props.cpf"
      @cpf="defineCpfValidated"
      @error="error = true"
    />
    <div
      v-if="cpfValidated"
      class="grid grid-cols-12 gap-4 col-span-12"
    >
      <PublicoTipo
        v-if="!error && $page.props.auth.access.includes(1)"
        ref="publico"
        :user="idUsuario"
        @access-group="defineAcessosCpf"
      />
      <Transition>
        <div
          v-if="!error && $page.props.auth.access.includes(1)"
          class="col-span-12 mt-8"
        >
          <div class="border-t-2 border-gray-300 w-full pt-8 grid lg:grid-cols-3 2xl:grid-cols-4 gap-4">
            <CheckBox
              v-for="nivelAcesso in niveisAcesso"
              :key="nivelAcesso.id"
              :nome="nivelAcesso.acesso_nome"
              :description="nivelAcesso.acesso_tipo"
              :id-acesso="nivelAcesso.id"
              :id-usuario="idUsuario"
              :checked="isChecked(nivelAcesso.id)"
              @update-access="callBackUpdatePublicoAlvo"
            />
          </div>
        </div>
      </Transition>
    </div>
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
