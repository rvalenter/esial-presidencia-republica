<script setup>
import {ref, watch} from "vue";
import get_calendar from "@/Services/Calendar/get_calendar.js";
import post_calendar from "@/Services/Calendar/post_calendar.js";
import titles from "../../../configs/titles.json";
import success from "@/Hooks/success.js";

const props = defineProps({
  show: {
    type: Boolean,
    required: false,
    default: false
  },
  start: {
    type: [Date, null],
    required: false,
    default: new Date().getTime()
  },
  eventToEdit: {
    type: [Number, null],
    required: false,
    default: null
  }
});
const emits = defineEmits(['create']);
const title = ref('');
const description = ref('');
const startDate = ref(props.start);
const duration = ref(30);
const endDate = ref(new Date().getTime() + 15 * 60000);
const popover = ref(true);
const list = ref([]);
const usuario = ref('');
const usuarioStaging = ref([]);
const usuarioCommit = ref([]);
const showList = ref(false);
const notSearch = ref(false);
const formValidated = ref(false);
const subMenu = ref(1);
const legislativo = ref('');
const legislativoStaging = ref([]);
const legislativoCommit = ref([]);
const range = ref({start: new Date(), end: new Date()});
const listCollegiate = ref([]);
const collegiate = ref(0);

function resetForm() {
  title.value = '';
  description.value = '';
  startDate.value = new Date();
  duration.value = 30;
  endDate.value = new Date().getTime() + 15 * 60000;
  usuario.value = '';
  usuarioStaging.value = [];
  usuarioCommit.value = [];
  showList.value = false;
  notSearch.value = false;
  formValidated.value = false;
  subMenu.value = 1;
  legislativo.value = '';
  legislativoStaging.value = [];
  legislativoCommit.value = [];
}

function addUser(user) {
  usuarioStaging.value = user;
  usuario.value = user.email;
  showList.value = false;
}

function addlegis(legis) {
  legislativoStaging.value = legis;
  legislativo.value = legis.sigla + ' ' + legis.numero + '/' + legis.ano + ' - ' + legis.origem;
  showList.value = false;
}

function commmitUser() {
  if (usuarioStaging.value.length == 0) {
    usuarioCommit.value.push({email: usuario.value});
  } else {
    usuarioCommit.value.push(usuarioStaging.value);
  }

  usuario.value = '';
  usuarioStaging.value = [];
  list.value = [];
  showList.value = false;
}

function commmitLegis() {

  legislativoCommit.value.push(legislativoStaging.value);

  legislativo.value = '';
  legislativoStaging.value = [];
  list.value = [];
  showList.value = false;
}

function removeUser(user) {
  usuarioCommit.value = usuarioCommit.value.filter((u) => u.email !== user.email);
}

function removeLegis(legis) {
  legislativoCommit.value = legislativoCommit.value.filter((u) => u.sigla + ' ' + u.numero + '/' + u.ano + ' - ' + u.origem !== legis.sigla + ' ' + legis.numero + '/' + legis.ano + ' - ' + legis.origem);
}

function storeEvent(type) {
  const data = {
    id: props.eventToEdit,
    title: title.value,
    description: description.value,
    start: startDate.value,
    end: new Date(endDate.value),
    duration: duration.value,
    users: usuarioCommit.value,
    legislatives: legislativoCommit.value,
    collegiate: collegiate.value,
  };

  if (type === 1) {
    post_calendar.store(data).then((response) => {
      if (response.status === 200) {
        resetForm();
        emits('create');
        success.cofetti();
      }
    });
  } else {
    post_calendar.update(data).then((response) => {
      if (response.status === 200) {
        resetForm();
        emits('create');
        success.cofetti();
        emits('create');
      }
    });
  }
}

function getCollegiate(id = 0) {
  if (subMenu.value == 3 || id !== 0) {
    post_calendar.listCollegiate(range.value).then((response) => {
      listCollegiate.value = response.data.data;

      if (id !== 0) {
        collegiate.value = id;
      }
    });
  }
}

watch([title, startDate, duration, description], () => {
  formValidated.value = title.value !== '' && startDate.value !== '' && duration.value !== '' && description.value !== '';
});

watch(duration, (newDuration) => {
  endDate.value = new Date(new Date(startDate.value).getTime() + newDuration * 60000);
});

watch(usuario, (newUsuario) => {
  if (newUsuario === '') {
    list.value = [];
    notSearch.value = false;
  }

  if (newUsuario && usuarioStaging.value.email !== newUsuario && !notSearch.value) {
    get_calendar.autocomplete(newUsuario).then((response) => {
      if (response.data.data.length === 0) {
        notSearch.value = true;
      }

      list.value = response.data.data.filter((user) => {
        return !usuarioCommit.value.some((u) => u.email === user.email);
      });
    }).then(() => {
      showList.value = true;
    });
  }
});

watch(legislativo, (newLegislativo) => {
  if (newLegislativo === '') {
    list.value = [];
    notSearch.value = false;
  }

  if (newLegislativo && legislativoStaging.value.email !== newLegislativo && !notSearch.value) {
    post_calendar.legislaticoAutocomplete({proposicao: newLegislativo}).then((response) => {
      if (response.data.data.length === 0) {
        notSearch.value = true;
      }

      list.value = response.data.data.filter((leg) => {
        return !legislativoCommit.value.some((u) => u.sigla + ' ' + u.numero + '/' + u.ano === leg.sigla + ' ' + leg.numero + '/' + leg.ano);
      });
    }).then(() => {
      showList.value = true;
    });
  }
});

watch(() => props.start, () => {
  startDate.value = props.start;
  endDate.value = new Date(new Date(startDate.value).getTime() + duration.value * 60000);
});

watch(() => props.eventToEdit, () => {
  range.value = {
    start: new Date(),
    end: new Date()
  }

  collegiate.value = 0;

  if (props.eventToEdit) {
    get_calendar.show(props.eventToEdit).then((response) => {
      let newDuration = Math.round((new Date(response.data.data.data_fim) - new Date(response.data.data.data_inicio)) / (1000 * 60));

      title.value = response.data.data.titulo;
      description.value = response.data.data.descricao;
      startDate.value = new Date(response.data.data.data_inicio);
      duration.value = newDuration < 29 ? 30 : newDuration > 180 ? 180 : newDuration;
      endDate.value = new Date(new Date(startDate.value).getTime() + duration.value * 60000);
      usuarioCommit.value = response.data.data.guest_data;
      legislativoCommit.value = response.data.data.propositions_data;

      if (response.data.data.colegiados_data) {
        range.value = {
          start: new Date(response.data.data.colegiados_data.data),
          end: new Date(response.data.data.colegiados_data.data)
        };

        getCollegiate(response.data.data.colegiados_data.id);
      }
    });
  } else {
    resetForm();
  }
});

watch([range, subMenu], () => {
  getCollegiate()
});
</script>

<template>
  <Transition>
    <div
      v-if="props.show"
      class="mt-5 h-full"
    >
      <div class="w-full">
        <p class="font-bold">
          Criar Novo Evento
        </p>
      </div>
      <div class="grid grid-cols-1 content-between h-5/6">
        <div class="w-full grid grid-cols-2 gap-4 mt-3">
          <div class="col-span-2">
            <label
              class="text-sm font-light"
              for="title"
            >Evento:</label>
            <input
              id="title"
              v-model="title"
              class="input h-7 px-3 text-sm"
              type="text"
            >
          </div>
          <div class="z-50 -mt-3">
            <label
              class="text-sm font-light"
              for="startdate"
            >Data:</label>
            <VDatePicker
              id="startdate"
              v-model="startDate"
              :popover="popover"
              hide-time-header
              mode="datetime"
            >
              <template #default="{ inputValue, inputEvents }">
                <div class="flex justify-center items-center">
                  <input
                    :value="inputValue"
                    class="input h-8 text-sm"
                    v-on="inputEvents"
                  >
                  <i class="fa-regular fa-calendar-check relative -left-6 text-sky-700" />
                </div>
              </template>
            </VDatePicker>
          </div>
          <div class="-mt-3">
            <label
              class="text-sm font-light"
              for="duration"
            >Duração:</label>
            <select
              id="duration"
              v-model="duration"
              class="input h-8 text-sm"
              name="duration"
            >
              <option value="30">
                30 minutos
              </option>
              <option value="60">
                1 hora
              </option>
              <option value="90">
                1 hora e 30 minutos
              </option>
              <option value="120">
                2 horas
              </option>
              <option value="150">
                2 horas e 30 minutos
              </option>
              <option value="180">
                3 horas
              </option>
            </select>
          </div>
          <div class="col-span-2 -mt-3">
            <label
              class="text-sm font-light"
              for="description"
            >Descrição:</label>
            <textarea
              id="description"
              v-model="description"
              class="input h-16 text-sm p-2"
            />
          </div>
          <div class="col-span-2 flex justify-between items-center -mt-2">
            <select
              v-model="subMenu"
              class="w-full h-7 p-0 px-3 text-sm rounded-md shadow-lg border-0"
            >
              <option value="1">
                Participantes
              </option>
              <option value="2">
                Proposição/ Matéria
              </option>
              <option value="3">
                Colegiado
              </option>
              <!--              <option value=4>Documentos</option>-->
            </select>
            <div
              v-if="subMenu == 3"
              class="w-7/12 ml-3"
            >
              <v-date-picker
                v-model="range"
                is-range
              >
                <template #default="{ inputValue, inputEvents }">
                  <div class="flex justify-center items-center">
                    <input
                      :value="inputValue.start"
                      class="px-2 py-1 w-32 rounded focus:outline-none focus:border-indigo-300 text-sm h-7 shadow-lg border-0"
                      v-on="inputEvents.start"
                    >
                    <svg
                      class="w-4 h-4 mx-2"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        d="M14 5l7 7m0 0l-7 7m7-7H3"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                      />
                    </svg>
                    <input
                      :value="inputValue.end"
                      class="px-2 py-1 w-32 rounded focus:outline-none focus:border-indigo-300  text-sm h-7 shadow-lg border-0"
                      v-on="inputEvents.end"
                    >
                  </div>
                </template>
              </v-date-picker>
            </div>
          </div>
          <div
            v-if="subMenu == 1"
            class="col-span-2"
          >
            <div class=" flex justify-between relative">
              <div class="flex w-full justify-between absolute">
                <button
                  class="h-7 w-7 mr-2 mt-0.5 bg-blue-500 hover:bg-sky-500 text-white rounded-lg text-xs shadow-lg"
                  title="Adicionar convidado"
                  @click="commmitUser()"
                >
                  <i class="fa-solid fa-user-plus" />
                </button>
                <div class="w-full -mt-1.5 z-40">
                  <div class="w-full">
                    <div class="p-1.5 w-full">
                      <input
                        v-model="usuario"
                        class="input h-7"
                        placeholder="Adicionar participante"
                        type="search"
                      >
                    </div>
                    <div
                      v-if="list.length > 0 && showList"
                      class="w-full bg-gray-50 rounded-lg p-1 shadow-lg -mt-10 pt-10"
                    >
                      <div class="divide-y">
                        <div
                          v-for="user in list"
                          :key="user.id"
                          class="p-1.5 text-gray-600 hover:font-bold hover:bg-gray-200 hover:text-black cursor-pointer text-sm w-full rounded-lg"
                          @click="addUser(user)"
                        >
                          {{ user.email }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div
              v-if="usuarioCommit.length > 0"
              class="flex flex-nowrap overflow-x-auto gap-4 col-span-2 mt-12"
            >
              <span
                v-for="(user, index) in usuarioCommit"
                :key="index"
                class="font-semibold text-sm text-nowrap h-6"
              >
                <button :title="titles.CalendarNewEvent.alert_user">
                  <i
                    v-if="!user.name"
                    class="fa-solid fa-triangle-exclamation text-yellow-500"
                  />
                </button>
                {{ user.name ?? user.email }}
                <button @click="removeUser(user)">
                  <i class="fa-solid fa-user-minus text-red-500 fa-xs ml-0.5" />
                </button>
              </span>
            </div>
          </div>
          <div
            v-if="subMenu == 2"
            class="col-span-2"
          >
            <div class=" flex justify-between relative">
              <div class="flex w-full justify-between absolute">
                <button
                  class="h-7 w-7 mr-2 mt-0.5 bg-blue-500 hover:bg-sky-500 text-white rounded-lg text-xs shadow-lg"
                  title="Adicionar convidado"
                  @click="commmitLegis()"
                >
                  <i class="fa-solid fa-file-circle-check" />
                </button>
                <div class="w-full -mt-1.5 z-40">
                  <div class="w-full">
                    <div class="p-1.5 w-full">
                      <input
                        v-model="legislativo"
                        class="input h-7"
                        placeholder="Adicionar Proposições/ Matéria"
                        type="search"
                      >
                    </div>
                    <div
                      v-if="list.length > 0 && showList"
                      class="w-full bg-gray-50 rounded-lg p-1 shadow-lg -mt-10 pt-10"
                    >
                      <div class="divide-y">
                        <div
                          v-for="(legis, index) in list"
                          :key="index"
                          class="p-1.5 text-gray-600 hover:font-bold hover:bg-gray-200 hover:text-black cursor-pointer text-sm w-full rounded-lg"
                          @click="addlegis(legis)"
                        >
                          {{ legis.sigla }} {{ legis.numero }}/{{ legis.ano }} - {{ legis.origem }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div
              v-if="legislativoCommit.length > 0"
              class="flex flex-nowrap overflow-x-auto gap-4 col-span-2 mt-12"
            >
              <span
                v-for="(legis, index) in legislativoCommit"
                :key="index"
                class="font-semibold text-sm text-nowrap h-6"
              >
                {{ legis.sigla }} {{ legis.numero }}/{{ legis.ano }} - {{ legis.origem }}
                <button @click="removeLegis(legis)">
                  <i class="fa-solid fa-trash-can text-red-500 fa-xs" />
                </button>
              </span>
            </div>
          </div>
          <div
            v-if="subMenu == 3"
            class="col-span-2"
          >
            <select
              v-model="collegiate"
              class="w-full h-7 p-0 px-3 text-sm border-0 shadow-lg rounded"
            >
              <option
                disabled
                selected
                value="0"
              >
                Selecione
              </option>
              <option
                v-for="item in listCollegiate"
                :key="item.id"
                :value="item.id"
              >
                {{ item.colegiado.tema }} -
                {{ item.tipo }}
              </option>
            </select>
          </div>
        </div>
        <div class="relative top-3">
          <button
            v-if="!props.eventToEdit"
            :class="[formValidated ? 'bg-blue-500 hover:bg-sky-500' : 'bg-gray-300 cursor-not-allowed']"
            :disabled="!formValidated"
            class="w-full h-8 px-2 text-white rounded-lg text-sm font-bold shadow-md"
            @click="storeEvent(1)"
          >
            Criar Evento
          </button>
          <button
            v-if="props.eventToEdit"
            :class="[formValidated ? 'bg-yellow-600 hover:bg-sky-500' : 'bg-gray-300 cursor-not-allowed']"
            :disabled="!formValidated"
            class="w-full h-8 px-2 text-white rounded-lg text-sm font-bold shadow-md relative -top-4"
            @click="storeEvent(2)"
          >
            Atualizar Evento
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
.input {
  @apply w-full bg-gray-50 shadow-md px-2 border-transparent focus:ring-2 ring-blue-600 mt-0.5 rounded-lg
}
</style>
