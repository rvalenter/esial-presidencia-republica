<script setup>
import get_user from '@/Services/User/get_user.js'
import post_user from '@/Services/User/post_user.js'
import { onMounted, ref, watch } from 'vue'
import Paginator from '@/Components/Global/Paginator.vue'

const items = ref([])
const search = ref(null)
const showNewAccess = ref(false)
const newAccessName = ref('')
const newAccessDescription = ref('')
const idUpdate = ref(null)
const errors = ref({
  acesso_nome: null,
  acesso_tipo: null
})
const fetchAccess = (page = null) => {
  get_user.fetchAccess(search.value, page).then(res => {
    items.value = res.data
  })
}
const firstElement = (arr) => {
  if (!arr) return null

  return arr[0]
}
const storeNewAccess = () => {
  post_user.storeNewAccess({
    id: idUpdate.value,
    acesso_nome: newAccessName.value,
    acesso_tipo: newAccessDescription.value
  }).then(res => {
    fetchAccess()
    showNewAccess.value = !showNewAccess.value
    idUpdate.value = null
    newAccessName.value = null
    newAccessDescription.value = null
  }).catch(err => {
    errors.value['acesso_nome'] = firstElement(err.response.data.errors['acesso_nome'])
    errors.value['acesso_tipo'] = firstElement(err.response.data.errors['acesso_tipo'])
  })
}
const deleteAccess = (id) => {
  post_user.destroyAccess(id).then(res => {
    fetchAccess()
  })
}
const editAccessForm = (id, name, description) => {

  idUpdate.value = id
  newAccessName.value = name
  newAccessDescription.value = description
  showNewAccess.value = true
}

onMounted(() => {
  console.log('mounted')
  fetchAccess()
})

watch(search, () => {
  if (search.value === '') {
    search.value = null
  }

  fetchAccess()
})
</script>

<template>
  <div
    v-if="!showNewAccess"
    class="w-full flex justify-between items-center mb-5"
  >
    <button
      class="bg-sky-700 hover:bg-sky-500 text-white rounded shadow px-3 h-8 flex gap-4 items-center "
      @click="showNewAccess = !showNewAccess"
    >
      <span class="text-sm font-semibold">Adicionar Acesso</span>
      <i class="fa-solid fa-user-shield" />
    </button>
    <div>
      <input
        v-model="search"
        type="search"
        class="w-72 h-8 px-2 focus:ring-0 shadow rounded border-b border-gray-400 border-t-0 border-l-0 border-r-0"
        placeholder="Pesquisar"
      >
    </div>
  </div>
  <div class="flex flex-col ">
    <div class=" md:-mx-6 2xl:-mx-8">
      <div class="inline-block min-w-full py-2 md:px-6 2xl:px-8">
        <div class="overflow-hidden">
          <table
            class="min-w-full text-left text-sm font-light text-surface"
          >
            <thead
              class="border-b-4 border-sky-400 font-medium bg-sky-700 text-white"
            >
              <tr>
                <th
                  scope="col"
                  class="px-6 py-4"
                >
                  #
                </th>
                <th
                  scope="col"
                  class="px-6 py-4"
                >
                  Acesso Nome
                </th>
                <th
                  scope="col"
                  class="px-6 py-4"
                >
                  Acesso Descrição
                </th>
                <th
                  scope="col"
                  class="px-6 py-4 text-center"
                >
                  Ações
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-if="showNewAccess"
                class="border-b border-neutral-300 bg-gray-50 md:h-28 2xl:h-32"
              >
                <td class="whitespace-nowrap px-6 py-4 font-medium">
                  #
                </td>
                <td class="h-full items-start px-6 w-1/3">
                  <input
                    v-model="newAccessName"
                    type="text"
                    class="w-full h-8 rounded shadow"
                  >
                  <span
                    v-if="errors.acesso_nome"
                    class="text-red-500 text-xs"
                  >{{ errors.acesso_nome }}</span>
                </td>
                <td class="h-full items-start px-6 w-2/3">
                  <input
                    v-model="newAccessDescription"
                    type="text"
                    class="w-full h-8 rounded shadow"
                  >
                  <span
                    v-if="errors.acesso_tipo"
                    class="text-red-500 text-xs"
                  >{{ errors.acesso_tipo }}</span>
                </td>
                <td class=" flex gap-4 justify-center items-center h-full w-48">
                  <button
                    class="rounded shadow h-8 w-8 bg-gray-400/70 hover:bg-gray-400"
                    @click="showNewAccess = !showNewAccess"
                  >
                    <i class="fa-solid fa-xmark" />
                  </button>
                  <button
                    class="rounded shadow h-8 w-8 text-white bg-blue-600 hover:bg-sky-500"
                    @click="storeNewAccess"
                  >
                    <i class="fa-regular fa-floppy-disk" />
                  </button>
                </td>
              </tr>
              <tr
                v-for="item in items.data"
                v-show="!showNewAccess"
                :key="item.id"
                class="border-b border-neutral-300 hover:bg-sky-500 hover:text-white bg-white"
              >
                <td class="whitespace-nowrap px-6 2xl:py-3 md:py-2 font-medium">
                  {{ item.id }}
                </td>
                <td class="whitespace-nowrap px-6 2xl:py-3 md:py-2">
                  {{ item.acesso_nome }}
                </td>
                <td class="whitespace-nowrap px-6 2xl:py-3 md:py-2">
                  {{ item.acesso_tipo }}
                </td>
                <td class="whitespace-nowrap px-6 2xl:py-3 md:py-2 flex gap-4 justify-center">
                  <button
                    class="rounded shadow h-7 w-7 bg-yellow-500 hover:bg-yellow-400"
                    @click="editAccessForm(item.id, item.acesso_nome, item.acesso_tipo)"
                  >
                    <i class="fa-regular fa-pen-to-square" />
                  </button>
                  <button
                    class="rounded shadow h-7 w-7 text-white bg-red-600 hover:bg-red-500"
                    @click="deleteAccess(item.id)"
                  >
                    <i class="fa-regular fa-trash-can" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <Paginator
    v-if="!showNewAccess"
    :on-paginate="fetchAccess"
    :paginator="items"
  />
</template>

<style scoped>

</style>
