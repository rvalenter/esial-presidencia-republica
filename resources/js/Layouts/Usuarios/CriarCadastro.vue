<script setup>
import post from "@/Services/User/post_user.js";

const props = defineProps({
    cpf: {
        type: String,
        required: true
    },
    email: {
        type: String,
        required: true
    }
});
const emits = defineEmits(["cadastrado"]);

async function store() {
    await post.storeCadastro({
        cpf: props.cpf,
        email: props.email
    }).then(() => {
        emits("cadastrado");
    })
}
</script>

<template>
  <div class="w-full flex justify-start items-end">
    <button
      title="Criar Cadastro para este CPF"
      class="w-36 h-10 text-xs bg-blue-500 hover:bg-blue-600 text-white rounded-md shadow-md flex justify-center items-center relative top-2 px-2"
      @click="store"
    >
      <p>Cadastrar Usuário</p>
      <i class="fa-regular fa-floppy-disk fa-lg ml-2" />
    </button>
  </div>
</template>

<style scoped>

</style>
