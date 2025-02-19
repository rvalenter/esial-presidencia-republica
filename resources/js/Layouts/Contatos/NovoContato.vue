<script setup>
import { ref, watch } from 'vue'
import form_contact from '@/Services/Contact/form_contact.js'
import get_contact from '@/Services/Contact/get_contact.js'
import { MaskInput } from 'vue-3-mask'
import Governismo from '@/Components/Contatos/Governismo.vue'
import Proposicoes from '@/Components/Contatos/Proposicoes.vue'
import Bancadas from '@/Components/Contatos/Bancadas.vue'
import Votacoes from '@/Components/Contatos/Votacoes.vue'
import Relatorias from '@/Components/Contatos/Relatorias.vue'
import Discursos from '@/Components/Contatos/Discursos.vue'
import { usePage } from '@inertiajs/vue3'
import Visao from '@/Layouts/Contatos/Presidente/visao.vue'
import { useStore } from 'vuex'

const page = usePage()
const stores = useStore()
const props = defineProps({
  id: {
    type: Number,
    required: false,
    default: null
  }
})
const emits = defineEmits(['updatedContact', 'resetId', 'setId', 'collapseMenu'])
const btnChangePhoto = ref(false)
const img = ref('')
const form = ref({
  id: props.id,
  photo: null,
  name: '',
  role: '',
  organization: '',
  phone: '',
  cellphone: '',
  email: '',
  address: '',
  notes: '',
  related: []
})
const validated = ref(false)
const edit = ref(false)
const searchRelated = ref('')
const relatedList = ref([])
const menu = ref(1)
const collapseMenu = ref(false)

function setFoto(event) {
  if (event) {
    let file = event.target.files[0]
    form.value.photo = file

    let img = document.getElementById('contato-img')

    img.onload = function () {
      URL.revokeObjectURL(img.src)
    }

    img.src = URL.createObjectURL(file)
  }
}

async function store() {
  validated.value = false

  if (
    form.value.name !== '' &&
    form.value.role !== '' &&
    form.value.organization !== '' &&
    form.value.cellphone.length === 15 &&
    validateEmail(form.value.email)
  ) {
    let method = form.value.id ? form_contact.update(form.value) : form_contact.store(form.value)

    await method.then(() => {
      edit.value = false
      emits('updatedContact')
    })
  } else {
    validated.value = true
  }
}

function setRelationshipView(related) {
  emits('setId', related.id)
}

function validateEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

  return regex.test(email) && email !== ''
}

function newContact() {
  if (!props.id) {
    form.value = {
      photo: null,
      name: '',
      role: '',
      organization: '',
      phone: '',
      cellphone: '',
      email: '',
      address: '',
      notes: '',
      related: []
    }
    img.value = null
    edit.value = true
    emits('resetId')
  }
}

function newContactAdd() {
  form.value = {
    photo: null,
    name: '',
    role: '',
    organization: '',
    phone: '',
    cellphone: '',
    email: '',
    address: '',
    notes: '',
    related: []
  }
  img.value = null
  edit.value = true
  emits('resetId')
}

function destroy() {
  if (props.id) {
    form_contact.destroy(props.id).then(() => {
      emits('updatedContact')
      newContact()
    })
  }
}

function addRelated(related) {
  form.value.related.push(related)
  searchRelated.value = ''
  relatedList.value = []
}

function setMenu(item) {
  menu.value = item
}

function fullScreen() {
  collapseMenu.value = !collapseMenu.value
  emits('collapseMenu', collapseMenu.value)
}

watch(
  () => props.id,
  async () => {
    if (props.id !== null) {
      edit.value = false

      if (page.props.auth.access.includes(16)) {
        menu.value = 5
      }

      await get_contact
        .fetchPhoto(props.id)
        .then((res) => {
          form.value = {
            id: res.data.data.id,
            name: res.data.data.nome,
            role: res.data.data.cargo,
            organization: res.data.data.organizacao,
            phone: res.data.data.telefone,
            cellphone: res.data.data.celular,
            email: res.data.data.email,
            address: res.data.data.endereco,
            notes: res.data.data.observacoes,
            related: res.data.data.get_relationships,
            isParliamentary: !!res.data.data.get_parlamentar,
            get_parlamentar: res.data.data.get_parlamentar
              ? res.data.data.get_parlamentar.codigo
              : null,
            votes: res.data.data.parlamentar_dados ? res.data.data.parlamentar_dados.votacoes : []
          }

          if (res.data.data.get_photo) {
            img.value = 'data:image/png;base64,' + res.data.data.get_photo.foto
          } else {
            img.value = ''
          }
        })
        .then(() => {
          if (!form.value.isParliamentary) {
            menu.value = 1
          }
        })
    }
  }
)

watch(
  () => searchRelated.value,
  () => {
    if (searchRelated.value.length > 0) {
      get_contact.searchRelated(searchRelated.value).then((res) => {
        relatedList.value = res.data.data.filter((related) => {
          return !form.value.related.some((rel) => rel.id === related.id)
        })
      })
    }

    if (searchRelated.value.length === 0) {
      relatedList.value = []
    }
  }
)
</script>

<template>
  <div class="h-screen w-full">
    <div v-if="!form.id" class="w-full h-full -mt-28 pt-32">
      <div
        v-if="$page.props.auth.access.includes(16)"
        class="col-span-12 overflow-y-auto h-full w-full"
      >
        <visao />
      </div>
      <div
        v-if="!$page.props.auth.access.includes(16)"
        class="rounded-3xl shadow-2xl bg-white w-full h-full flex justify-center items-center"
      >
        <h1 class="text-5xl font-light text-center text-gray-400">Escolha Parlamentar/ Bancada</h1>
      </div>
    </div>
    <div
      v-if="form.id && !$page.props.auth.isMobile"
      class="bg-cover bg-center bg-no-repeat w-full rounded-t-3xl flex image-container"
      :class="[
        form.role === 'Deputado Federal'
          ? 'bg-[url(/src/img/plenario-camara.jpg)]'
          : 'bg-[url(/src/img/plenario.jpg)]',
        $page.props.auth.isMobile ? 'h-20' : 'h-60'
      ]"
    >
      <div class="w-7/12 h-full flex justify-start items-center ml-10">
        <div
          class="bg-[url('/src/img/bg-avatar.jpg')] relative top-3 -left-10 image-container border-gray-300 rounded-r-full h-72 w-72 border-t-8 border-r-8 border-b-8 border-l-0 bg-cover bg-bottom bg-no-repeat flex items-center justify-center shadow-box"
          @mouseleave="btnChangePhoto = false"
          @mouseover="btnChangePhoto = true"
        >
          <button
            v-show="edit && btnChangePhoto"
            :class="[btnChangePhoto ? 'bg-black/40' : '']"
            class="text-white font-bold text-xl z-50 absolute inset-0"
          >
            <p class="relative top-16">Alterar</p>
            <input class="h-80 w-80 opacity-0" type="file" @change="setFoto($event)" />
          </button>
          <img id="contato-img" :src="img" class="absolute inset-0 z-10" />
        </div>
      </div>
      <div class="w-5/12 h-full grid grid-rows-1 p-4">
        <div class="flex items-start justify-end">
          <div v-if="props.id" class="flex space-x-3 z-30 mr-4">
            <button
              class="w-7 h-7 bg-sky-800 hover:bg-sky-600 rounded-lg text-white shadow-md"
              @click="fullScreen()"
            >
              <i v-if="!collapseMenu" class="fa-solid fa-up-right-and-down-left-from-center" />
              <i v-if="collapseMenu" class="fa-solid fa-down-left-and-up-right-to-center" />
            </button>
          </div>
        </div>
      </div>
    </div>
    <div
      v-if="form.id"
      class="w-full h-24 bg-white pl-8 pr-4 pt-4 border-b border-gray-300"
      :class="[$page.props.auth.isMobile ? 'rounded-t-3xl' : 'flex justify-between']"
    >
      <div
        v-if="$page.props.auth.isMobile"
        class="w-full h-full flex justify-end relative -top-4 -right-8 -mb-20"
      >
        <div
          class="bg-[url('/src/img/bg-avatar.jpg')] relative top-3 -left-10 image-container border-gray-300 rounded-full h-16 w-16 bg-cover bg-bottom bg-no-repeat flex items-center justify-center border border-sky-700"
        >
          <button
            v-show="edit && btnChangePhoto"
            :class="[btnChangePhoto ? 'bg-black/40' : '']"
            class="text-white font-bold text-xl z-20 absolute inset-0"
          >
            <p class="relative top-16">Alterar</p>
            <input class="h-80 w-80 opacity-0" type="file" @change="setFoto($event)" />
          </button>
          <img id="contato-img" :src="img" class="absolute inset-0 z-10" />
        </div>
      </div>
      <div :class="$page.props.auth.isMobile ? '-ml-4' : 'pt-2'">
        <h1 :class="[$page.props.auth.isMobile ? 'text-sm font-semibold' : 'text-4xl font-light']">
          {{ form.name }} - {{ $page.props.auth.isMobile ? form.role : '' }}
        </h1>
        <h5 v-if="!$page.props.auth.isMobile" class="text-sm font-light">
          {{ form.role }} - {{ form.organization }}
        </h5>
      </div>
      <div
        v-if="form.isParliamentary"
        class="flex items-center"
        :class="[
          $page.props.auth.isMobile ? 'pt-6 gap-8 -ml-4 z-50 relative' : 'justify-end pr-2 gap-2'
        ]"
      >
        <button
          v-if="$page.props.auth.access.includes(16)"
          :class="[
            menu === 5
              ? 'border-b-2 text-sky-600 border-sky-700'
              : 'border-gray-600 hover:border-b-2'
          ]"
          @click="setMenu(5)"
        >
          <i class="fa-solid fa-landmark" :class="$page.props.auth.isMobile ? '' : 'fa-xs'" />
          <p v-if="!$page.props.auth.isMobile" class="text-[10px]">Governismo</p>
        </button>
        <button
          :class="[
            menu === 1
              ? 'border-b-2 text-sky-600 border-sky-700'
              : 'border-gray-600 hover:border-b-2'
          ]"
          @click="setMenu(1)"
        >
          <i class="fa-regular fa-address-card" :class="$page.props.auth.isMobile ? '' : 'fa-xs'" />
          <p v-if="!$page.props.auth.isMobile" class="text-[10px]">Contato</p>
        </button>
        <button
          :class="[
            menu === 2
              ? 'border-b-2 text-sky-600 border-sky-700'
              : 'border-gray-600 hover:border-b-2'
          ]"
          @click="setMenu(2)"
        >
          <i class="fa-regular fa-comments" :class="$page.props.auth.isMobile ? '' : 'fa-xs'" />
          <p v-if="!$page.props.auth.isMobile" class="text-[10px]">Proposições</p>
        </button>
        <button
          :class="[
            menu === 4
              ? 'border-b-2 text-sky-600 border-sky-700'
              : 'border-gray-600 hover:border-b-2'
          ]"
          @click="setMenu(4)"
        >
          <i class="fa-solid fa-check-to-slot" :class="$page.props.auth.isMobile ? '' : 'fa-xs'" />
          <p v-if="!$page.props.auth.isMobile" class="text-[10px]">Votações</p>
        </button>
        <button
          :class="[
            menu === 6
              ? 'border-b-2 text-sky-600 border-sky-700'
              : 'border-gray-600 hover:border-b-2'
          ]"
          @click="setMenu(6)"
        >
          <i class="fa-solid fa-bullhorn" :class="$page.props.auth.isMobile ? '' : 'fa-xs'" />
          <p v-if="!$page.props.auth.isMobile" class="text-[10px]">Discursos</p>
        </button>
        <button
          :class="[
            menu === 3
              ? 'border-b-2 text-sky-600 border-sky-700'
              : 'border-gray-600 hover:border-b-2'
          ]"
          @click="setMenu(3)"
        >
          <i class="fa-solid fa-medal" :class="$page.props.auth.isMobile ? '' : 'fa-xs'" />
          <p v-if="!$page.props.auth.isMobile" class="text-[10px]">Destaques</p>
        </button>
        <a
          v-if="$page.props.auth.access.includes(16)"
          class="border-gray-600 hover:border-b-2 text-center"
          target="_blank"
          :href="route('relatorio.perfil-parlamentar.pdf', { id: form.id })"
        >
          <i class="fa-regular fa-file-pdf" :class="$page.props.auth.isMobile ? '' : 'fa-xs'" />
          <p v-if="!$page.props.auth.isMobile" class="text-[10px]">Baixar</p>
        </a>
      </div>
    </div>
    <div
      v-if="form.id"
      class="h-full pb-24"
      :class="[$page.props.auth.isMobile ? '-mt-28 pt-28' : '-mt-96 pt-96']"
    >
      <div
        v-if="[1, 2, 3, 4, 6, 7].includes(menu)"
        class="bg-white shadow-2xl rounded-b-[2rem] h-full px-8 pt-2 pb-16"
      >
        <div v-if="menu === 1" class="grid grid-cols-2 gap-4 h-full overflow-y-auto mt-4">
          <div class="w-full">
            <div class="mb-4">
              <label class="label-form">Nome:</label>
              <input
                v-model="form.name"
                :class="[edit ? 'input-form' : 'input-view']"
                :disabled="!edit"
                type="text"
              />
              <span v-if="validated" class="text-xs text-red-500 font-bold"
                >Campo obrigatório!</span
              >
            </div>
            <div class="mb-4">
              <label class="label-form">Cargo:</label>
              <input
                v-model="form.role"
                :class="[edit ? 'input-form' : 'input-view']"
                :disabled="!edit"
                type="text"
              />
              <span v-if="validated" class="text-xs text-red-500 font-bold"
                >Campo obrigatório!</span
              >
            </div>
            <div class="mb-4">
              <label class="label-form">Organização:</label>
              <input
                v-model="form.organization"
                :class="[edit ? 'input-form' : 'input-view']"
                :disabled="!edit"
                type="text"
              />
              <span v-if="validated" class="text-xs text-red-500 font-bold"
                >Campo obrigatório!</span
              >
            </div>
            <div class="mb-4">
              <label class="label-form">Telefone:</label>
              <MaskInput
                v-model="form.phone"
                :class="[edit ? 'input-form' : 'input-view']"
                :disabled="!edit"
                :value="form.phone"
                mask="(##) ####-####"
              />
            </div>
            <div>
              <label class="label-form">Celular:</label>
              <MaskInput
                v-model="form.cellphone"
                :class="[edit ? 'input-form' : 'input-view']"
                :disabled="!edit"
                :value="form.cellphone"
                mask="(##) #####-####"
              />
              <span v-if="validated" class="text-xs text-red-500 font-bold"
                >Campo obrigatório!</span
              >
            </div>
          </div>
          <div class="pl-6">
            <div class="mb-4">
              <label class="label-form">E-mail:</label>
              <input
                v-model="form.email"
                :class="[edit ? 'input-form' : 'input-view']"
                :disabled="!edit"
                type="text"
              />
              <span v-if="validated" class="text-xs text-red-500 font-bold"
                >Campo obrigatório!</span
              >
            </div>
            <div class="mb-4">
              <label class="label-form">Endereço:</label>
              <input
                v-model="form.address"
                :class="[edit ? 'input-form' : 'input-view']"
                :disabled="!edit"
                type="text"
              />
            </div>
            <div v-if="form.related.length > 0" class="mb-4">
              <label class="label-form">Relacionamentos:</label>
              <div
                v-if="edit"
                class="bg-gray-200 pt-2 px-2 pb-0.5 rounded-lg absolute w-full shadow-xl"
              >
                <input
                  v-model="searchRelated"
                  class="border-b border-t-0 border-l-0 border-r-0 w-full focus:ring-0 bg-gray-50 rounded-lg"
                  type="search"
                />
                <ul class="overflow-y-auto mt-0.5 space-y-2">
                  <li
                    v-for="related in relatedList"
                    :key="related.id"
                    class="flex justify-between items-center border-b border-gray-400 text-sm h-7 cursor-pointer hover:text-blue-500"
                    @click="addRelated(related)"
                  >
                    <span>{{ related.nome }}</span>
                  </li>
                </ul>
              </div>
              <div :class="[edit ? 'mt-16' : '']">
                <ul class="h-5/6 overflow-y-auto mt-2 space-y-2 pr-3">
                  <li
                    v-for="related in form.related"
                    :key="related.id"
                    class="flex justify-between items-center border-b border-gray-400 text-sm h-7"
                  >
                    <span
                      class="w-full font-semibold hover:text-sky-600 cursor-pointer"
                      @click="setRelationshipView(related)"
                    >
                      {{ related.nome }} - {{ related.cargo }}
                    </span>
                    <button
                      v-if="edit && $page.props.auth.access.includes(11)"
                      class="text-red-500 hover:text-red-700"
                      @click="form.related.splice(form.related.indexOf(related), 1)"
                    >
                      <i class="fa-sm fa-regular fa-trash-can" />
                    </button>
                  </li>
                </ul>
              </div>
            </div>
            <div v-if="(form.notes && form.notes !== '- - -') || edit" class="mb-4">
              <div>
                <label class="label-form">Observações:</label>
              </div>
              <textarea
                v-if="edit"
                v-model="form.notes"
                :class="[edit ? 'input-form min-h-24' : 'input-view h-fit']"
                :disabled="!edit"
              />
              <p v-if="!edit">
                {{ form.notes }}
              </p>
            </div>
          </div>
          <div
            v-if="edit && form.name !== ''"
            class="col-span-2 flex justify-center items-center space-x-6"
          >
            <button
              class="w-20 p-2 bg-gray-200 hover:bg-gray-300 rounded-lg shadow-md text-sm"
              @click="((edit = false), newContact())"
            >
              Cancelar
            </button>
            <button
              class="w-20 p-2 bg-blue-500 hover:bg-sky-500 text-white rounded-lg shadow-md text-sm"
              @click="store"
            >
              Salvar
            </button>
          </div>
        </div>
        <proposicoes v-if="menu === 2" :id="form.get_parlamentar" />
        <bancadas v-if="menu === 3" :id="props.id" />
        <votacoes v-if="menu === 4" :form="form" />
        <relatorias v-if="menu === 7" :form="form" />
        <discursos v-if="menu === 6" :form="form" />
      </div>
      <div v-if="menu === 5" class="bg-white shadow-2xl rounded-b-[2rem] h-full pb-8">
        <governismo :id="props.id" />
      </div>
    </div>
  </div>
</template>

<style scoped>
.image-container {
  overflow: hidden;
  position: relative;
}

.image-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.input-form {
  @apply w-full bg-gray-200 border border-gray-300 focus:bg-white rounded-lg focus:ring-2 ring-blue-600 py-1 px-2 mt-1 text-sm;
}

.input-view {
  @apply w-full border-b border-gray-400 focus:ring-0 border-t-0 border-r-0 border-l-0 pb-0 pt-1 p-0;
}

.label-form {
  @apply font-medium text-gray-500 text-xs;
}

.shadow-box {
  -webkit-box-shadow: 7px 2px 15px -5px rgba(0, 0, 0, 0.75);
  -moz-box-shadow: 7px 2px 15px -5px rgba(0, 0, 0, 0.75);
  box-shadow: 7px 2px 15px -5px rgba(0, 0, 0, 0.75);
}
</style>
