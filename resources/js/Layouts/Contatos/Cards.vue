<script setup>
import ContatoShow from "@/Components/Contatos/ContatoShow.vue";
import pdf from "@/Hooks/pdf.js";
import {onMounted, ref, watch} from "vue";
import get_contact from "@/Services/Contact/get_contact.js";
import form_contact from "@/Services/Contact/form_contact.js";
import Header from "@/Components/Pdf/Header.vue";

const props = defineProps({
  selected: {
    type: Object,
    required: true
  },
  groupId: {
    type: Number,
    required: false,
    default: null
  },
  settedTitle: {
    type: String,
    required: false,
    default: ''
  },
  type: {
    type: String,
    required: true,
  }
});
const emits = defineEmits(['groupSet', 'collapseMenu']);
const title = ref(props.settedTitle);
const edit = ref(false);
const listLegis = ref([]);
const groupName = ref('');
const groupId = ref(null);
const selectedCards = ref([]);
const backupSelectedCards = ref([]);
const print = ref(false);
const collapseMenu = ref(false);

function setGroupTitle(group) {
  groupName.value = `${group.sigla} ${group.numero}/${group.ano} - ${group.origem}`;
  groupId.value = group.id;
  title.value = groupName.value;
}

function storeGroup() {
  form_contact.storeGroup({
    title: title.value,
    contacts: selectedCards.value.map(contact => contact.id),
    legis: groupId.value
  }).then(res => {
    edit.value = false;
    emits('groupSet');
    backupSelectedCards.value = selectedCards.value;
  });
}

function removeUser(id) {
  selectedCards.value = selectedCards.value.filter(contact => contact.id !== id);
}

function pdfGenerate() {
  print.value = true;

  setTimeout(() => {
    pdf.generate(document.getElementById('printableDiv'), title.value);
    print.value = false;
  }, 5);
}

function cancel() {
  edit.value = false;
  selectedCards.value = backupSelectedCards.value;
}

function fullScreen() {
  collapseMenu.value = !collapseMenu.value;
  emits('collapseMenu', collapseMenu.value);
}

watch(title, (newTitle) => {
  if (newTitle !== '' && edit.value) {
    get_contact.fetchLegis(newTitle).then(res => {
      listLegis.value = res.data.data;
    })
  }

  if (title.value === '' && edit.value) {
    listLegis.value = [];
  }
});

watch(() => props.settedTitle, (newTitle) => {
  title.value = newTitle;
}, {immediate: true});

watch(() => props.selected, (newSelected) => {
  backupSelectedCards.value = newSelected;
  selectedCards.value = newSelected;
}, {immediate: true});

onMounted(() => {
  if (props.type == 1) {
    title.value = ''
    edit.value = true
  }
})
</script>

<template>
  <div class="w-full h-screen -mt-36 pt-36">
    <div class="rounded-2xl bg-white h-full overflow-y-auto shadow-xl pb-8">
      <div class="px-12 py-8 flex justify-between items-center ">
        <div class="border-b border-gray-500 flex w-full">
          <div class="w-full relative z-20">
            <input
              v-model="title"
              :class="[edit ? 'input-form' : 'input-view']"
              :disabled="!edit"
              type="search"
            >
            <div
              class="absolute bg-gray-200 text-gray-700 w-full divide-gray-300 divide-y-2 rounded-lg shadow-xl mt-0.5"
            >
              <div
                v-for="item in listLegis"
                :key="item.id"
                class="p-2 hover:text-blue-500 hover:bg-gray-100 cursor-pointer"
                @click="setGroupTitle(item)"
              >
                {{ item.sigla }} {{ item.numero }}/{{ item.ano }} - {{ item.origem }}
              </div>
            </div>
          </div>
          <div class="flex pb-2.5">
            <!--            <button v-if="edit"-->
            <!--                    class="w-9 h-9 text-gray-500 hover:text-white ml-4 hover:bg-gray-500 p-1.5 rounded-lg"-->
            <!--                    @click="cancel()">-->
            <!--              <i class="fa-lg fa-solid fa-xmark"></i>-->
            <!--            </button>-->
            <!--            <button v-if="edit"-->
            <!--                    class="w-9 h-9 text-blue-500 hover:text-white ml-4 hover:bg-blue-500 p-1.5 rounded-lg"-->
            <!--                    @click="storeGroup">-->
            <!--              <i class="fa-lg fa-solid fa-floppy-disk"></i>-->
            <!--            </button>-->
            <!--            <button v-if="!edit"-->
            <!--                    class="w-9 h-9 text-yellow-500 hover:text-white ml-4 hover:bg-yellow-500 p-1.5 rounded-lg"-->
            <!--                    @click="edit = true">-->
            <!--              <i class="fa-lg fa-solid fa-pen-to-square"></i>-->
            <!--            </button>-->
            <!--            <button v-if="!edit" class="w-9 h-9 text-red-500 hover:text-white ml-4 hover:bg-red-500 p-1.5 rounded-lg"-->
            <!--                    @click="pdfGenerate">-->
            <!--              <i class="fa-lg fa-solid fa-file-pdf"></i>-->
            <!--            </button>-->
            <button
              class="w-7 h-7 bg-sky-700 hover:bg-sky-600 rounded-lg text-white shadow-md"
              @click="fullScreen()"
            >
              <i
                v-if="!collapseMenu"
                class="fa-solid fa-up-right-and-down-left-from-center"
              />
              <i
                v-if="collapseMenu"
                class="fa-solid fa-down-left-and-up-right-to-center"
              />
            </button>
          </div>
        </div>
      </div>
      <div
        id="printableDiv"
        class="grid grid-cols-3 gap-6 px-12"
      >
        <Header
          :show="print"
          :title="title"
        />
        <ContatoShow
          v-for="contato in selectedCards"
          :key="contato.id"
          :contato="contato"
          :edit="edit"
          @remove-user="removeUser"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>
.input-form {
  @apply w-full text-2xl font-semibold border-b-2 border-sky-500 focus:ring-0 border-t-0 border-r-0 border-l-0 bg-gray-100;
}

.input-view {
  @apply w-full text-2xl font-semibold border-b-0 focus:ring-0 border-t-0 border-r-0 border-l-0;
}
</style>
