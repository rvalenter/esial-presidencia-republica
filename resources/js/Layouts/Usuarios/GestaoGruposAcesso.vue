<script setup>
import {onMounted, ref, watch} from "vue";
import get from "@/Services/User/get_user.js";
import post from "@/Services/User/post_user.js";
import CheckBoxGestaoAcesso from "@/Components/Usuarios/checkBoxGestaoAcesso.vue";

const awaitingSearch = ref(false);
const group = ref('');
const list = ref([]);
const showBtnAddGroup = ref(false);
const showList = ref(false);
const listAll = ref([]);
const autorizations = ref([]);
const clickShow = ref(false);

function debounce() {
    if (!awaitingSearch.value) {
        setTimeout(() => {
            fetchAutocomplete();
            awaitingSearch.value = false;
        }, 1500);
    }

    awaitingSearch.value = true;
}

function fetchAutocomplete(click = false) {
    showBtnAddGroup.value = false;

    if (group.value !== '') {
        get.fetchAutocompleteGroupAccess(group.value).then(res => {
            list.value = res.data.data.map(item => {
                listAll.value.push(item.nome);

                return item.nome;
            });
        }).then(() => {
            if (list.value.length === 0) {
                showBtnAddGroup.value = true;
            } else {
                showList.value = true;
            }

            if (click) {
                showList.value = false;
            }
        });
    } else {
        setInicialList();
    }
}

function addNewGroup() {
    post.storeNewGroup(group.value).then(() => {
        fetchAutocomplete();
    });
}

function setGroup(groupName) {
    fetchAutorization(groupName);
    group.value = groupName;
    fetchAutocomplete(true);
}

function setInicialList() {
    get.fetchGroupAccess().then(res => {
        let primaryList = res.data.data.map(item => {
            return item.nome;
        });

        list.value = primaryList;
        listAll.value = primaryList;
    });
}

function fetchAutorization(groupName) {
    get.niveisAcessoChecked(groupName).then(res => {
        autorizations.value = res.data.data;
        clickShow.value = true;
    });
}

onMounted(() => {
    setInicialList();
});

watch(() => group.value, () => {

    clickShow.value = false;

    if (group.value === '') {
        setInicialList();
        autorizations.value = [];
    }
}, {immediate: true});
</script>

<template>
  <div class="w-full grid grid-cols-1">
    <div class="w-full flex">
      <input
        v-model="group"
        type="search"
        class="w-full focus:ring-0 border-t-0 border-l-0 border-r-0 border-b-2 border-gray-400 h-10 outline-0 text-gray-500 font-bold placeholder-gray-400/80 bg-white focus:bg-gray-50 hover:bg-gray-50 transition-all duration-300 ease-in-out"
        placeholder="Busque ou crie grupos de acesso"
        @keyup="() => {
          list.value = [];
          debounce();
        }"
      >
      <button
        v-if="showBtnAddGroup"
        class="h-10 min-w-48 px-4 flex justify-center items-center bg-blue-500 text-white hover:bg-sky-500"
        @click="addNewGroup"
      >
        Adicionar Grupo
      </button>
      <button
        v-if="!showBtnAddGroup"
        class="h-10 w-9 px-4 flex justify-center items-center bg-blue-500 text-white hover:bg-sky-500"
        @click="showList = !showList"
      >
        <i
          v-if="!showList"
          class="fa-solid fa-chevron-down"
        />
        <i
          v-if="showList"
          class="fa-solid fa-chevron-up"
        />
      </button>
    </div>
    <div class="relative w-full">
      <div
        v-if="showList"
        class="absolute w-full overflow-y-auto max-h-80 border border-gray-300 shadow-lg"
      >
        <div
          v-for="item in list"
          :key="item.id"
        >
          <button
            class="bg-gray-50 w-full flex justify-start items-center border-b border-gray-300 py-1.5 px-4 hover:font-medium hover:bg-gray-100"
            @click="setGroup(item)"
          >
            {{ item }}
          </button>
        </div>
      </div>
    </div>
  </div>
  <Transition>
    <div
      v-if="listAll.includes(group) && clickShow"
      class="mt-10 grid md:grid-cols-3 2xl:grid-cols-4 gap-4 h-16"
    >
      <check-box-gestao-acesso
        v-for="autorization in autorizations"
        :id="autorization.id"
        :key="autorization.id"
        :checked="autorization.acessos.length > 0"
        :name="autorization.acesso_nome"
        :group="group"
      />
    </div>
  </Transition>
</template>

<style scoped>

</style>
