<script setup>
import {Link} from "@inertiajs/vue3";
import get from "@/Services/User/get_user.js";
import {onMounted, ref, watch} from "vue";
import Paginator from "@/Components/Global/Paginator.vue";
import form_notes from "@/Services/Notes/form_notes.js";

const users = ref([]);
const user = ref('');
const usersNote = ref(null);
const showBtnDemand = ref(true);

const props = defineProps({
  thematic: {
    type: Boolean,
    default: false,
    required: false,
  },
  proposicao: {
    type: Object,
    default: null,
  },
  notaTecnica: {
    type: Object,
    default: null,
  },
  collapse: {
    type: Boolean,
    default: true,
  },
});
const emits = defineEmits(['setUserTechnicalNote', 'active']);

function unCollapse() {
  emits('active', 1);
}

function fetchUsers() {
  get.fetchMyUsers(user.value)
    .then(response => {
      users.value = response.data;
    })
}

function destroy(id) {
  get.destroyUser(id)
    .then(() => {
      fetchUsers();
    })
}

function storeDemand() {
  form_notes.storeDemand({
    user: usersNote.value,
    id: props.proposicao.id,
  })
    .then(res => {
      emits('setUserTechnicalNote', res.data);
      usersNote.value = res.data.data.user_id;
    })
}

onMounted(() => {
  fetchUsers();
  setUserTechnicalNote();
});

watch(user, () => {
  fetchUsers();
  setUserTechnicalNote();
});

watch(() => props.proposicao, () => {
  setUserTechnicalNote();
});

function setUserTechnicalNote() {
  if (!props.thematic) {
    return;
  }

  if (props.notaTecnica) {
    showBtnDemand.value = false;
    usersNote.value = props.notaTecnica.user_id;
    return;
  }

  if (props.proposicao.nota_tecnica.length > 0) {
    showBtnDemand.value = false;
    usersNote.value = props.proposicao.nota_tecnica[0].user_id;
  }
}

function setUrl(id) {
  return `/perfil/${id}/id`;
}

function onPaginate(page) {
  get.fetchMyUsers(user.value, page)
    .then(response => {
      users.value = response.data;
    })
}
</script>

<template>
  <div class="col-span-2">
    <div
      class="w-full flex  mb-4"
      :class="[!thematic ? 'justify-end' : 'justify-start']"
    >
      <div class="pt-4">
        <input
          v-model="user"
          :class="[!thematic ? 'w-72 h-8 rounded-lg' : 'h-7 text-xs rounded-lg']"
          placeholder="Pesquisar usuários"
          type="search"
        >
      </div>
    </div>
    <div class="overflow-y-auto max-h-96">
      <div class="flex flex-col">
        <div>
          <div class="w-full py-2">
            <div class="overflow-hidden">
              <table
                class="min-w-full text-left text-sm font-light text-surface"
              >
                <thead
                  class="cursor-pointer border-b border-gray-300 font-medium "
                  @click="unCollapse()"
                >
                  <tr class="bg-sky-700 text-white border-b-4 border-sky-400">
                    <th
                      class="px-6 py-4"
                      scope="col"
                    >
                      Analista/ Órgão
                    </th>
                    <th
                      v-if="!thematic"
                      class="px-6 py-4"
                      scope="col"
                    >
                      E-Mail
                    </th>
                    <th
                      v-if="!thematic"
                      class="px-6 py-4"
                      scope="col"
                    >
                      Telefone
                    </th>
                    <th
                      v-if="!thematic"
                      class="py-4 text-center max-w-10"
                      scope="col"
                    >
                      Ações
                    </th>
                    <th
                      v-if="thematic"
                      class="py-4 text-center max-w-10"
                      scope="col"
                    >
                      Responsável
                    </th>
                  </tr>
                </thead>
                <tbody v-if="collapse">
                  <tr
                    v-for="user in users.data"
                    :id="user.id"
                    :key="user.id"
                    class="border-b border-gray-300 hover:bg-gray-300"
                  >
                    <td
                      class="whitespace-nowrap px-6 py-2  font-bold"
                      :class="[thematic ? 'bg-transparent' : 'bg-white']"
                    >
                      <div class="w-full flex justify-between">
                        {{ user.nome ?? user.cpf }} / {{ user.orgaos[0].sigla }}
                        <Link
                          v-if="!thematic"
                          :href="setUrl(user.id)"
                          class="text-blue-500 hover:text-blue-600"
                        >
                          <i class="fa-solid fa-arrow-up-right-from-square" />
                        </Link>
                      </div>
                    </td>
                    <td
                      v-if="!thematic"
                      class="whitespace-nowrap px-6 py-2 bg-white"
                    >
                      {{ user.email }}
                    </td>
                    <td
                      v-if="!thematic"
                      class="whitespace-nowrap px-6 py-2 bg-white"
                    >
                      {{ user.telefone }}
                    </td>
                    <td
                      v-if="!thematic"
                      class="whitespace-nowrap px-6 py-2 bg-white gap-4 flex justify-center"
                    >
                      <Link
                        :href="'/usuarios/' + user.cpf + '/cpf'"
                        class="w-8 h-8 bg-yellow-500 rounded-full shadow-md hover:bg-yellow-600 flex justify-center items-center"
                      >
                        <i class="fa-solid fa-sliders" />
                      </Link>
                      <button
                        class="w-8 h-8 bg-red-500 rounded-full shadow-md text-white hover:bg-red-600"
                        @click="destroy(user.id)"
                      >
                        <i class="fa-regular fa-trash-can" />
                      </button>
                    </td>
                    <td
                      v-show="thematic"
                      class="py-2 flex items-center justify-center"
                    >
                      <div class="flex items-center justify-center">
                        <input
                          id="default-radio-1"
                          v-model="usersNote"
                          type="radio"
                          :value="user.id"
                          name="default-radio"
                          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2"
                          @change="showBtnDemand = true"
                        >
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div
                v-if="showBtnDemand && usersNote && thematic"
                class="w-full pt-2.5 flex justify-end items-center"
              >
                <div class="w-full h-11 flex justify-center items-center">
                  <button
                    class="px-2 py-1 flex justify-center items-center bg-sky-700 hover:bg-sky-600 text-white rounded-lg text-sm font-light"
                    @click="storeDemand"
                  >
                    Demandar
                    <i class="fa-solid fa-file-circle-check ml-2" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Paginator
      v-if="collapse"
      :on-paginate="onPaginate"
      :paginator="users"
    />
  </div>
</template>

<style scoped>

</style>
