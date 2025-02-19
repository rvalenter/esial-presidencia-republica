<script setup>
import {ref, watch} from "vue";
import get from "@/Services/User/get_user.js";
import post from "@/Services/User/post_user.js";

const props = defineProps({
    type: {
      type: String,
      required: true
    },
    search: {
      type: String,
      required: true
    }
});
const emits = defineEmits(['setNewComplete']);
const list = ref([]);
const awaitingSearch = ref(false);

function debounce() {
    if (!awaitingSearch.value) {
        setTimeout(() => {
            fetchAutocomplete();
            awaitingSearch.value = false;
        }, 1000);
    }

    awaitingSearch.value = true;
}

function fetchAutocomplete() {
    get.autocompletePerfil({
        type: props.type,
        search: props.search
    }).then(res => {
        list.value = res.data.data.map(item => {
            return item.nome;
        });
    });
}

function store() {
    post.storeFuncional({
        type: props.type,
        search: props.search
    }).then(() => {
        emits('setNewComplete', {
            search: props.search,
            type: props.type
        });
        fetchAutocomplete();
    });
}

function Selected(item) {
    emits('setNewComplete', {
        search: item,
        type: props.type
    });
}

watch(
    () => props.search,
    () => {
        debounce();
    },
    {immediate: true}
);
</script>

<template>
  <div class="absolute z-10 w-full mt-0.5">
    <div
      v-if="list.length > 0"
      class="w-full rounded-2xl shadow-lg bg-gray-50 divide-y-2 border-2 border-t-0 border-gray-300/70"
    >
      <div
        v-for="item in list"
        :key="item"
        class="px-4 py-2 cursor-pointer hover:text-blue-600"
        @click="Selected(item)"
      >
        {{ item }}
      </div>
    </div>
  </div>
  <div class="absolute -mb-10 z-10 w-12 right-0">
    <div
      v-if="list.length === 0"
      class="relative -top-10 flex justify-end"
    >
      <button
        class="px-3.5 py-2 flex gap-2 items-center justify-between bg-blue-500 hover:bg-sky-500 w-full h-10 rounded-r-full text-white"
        @click="store"
      >
        <i class="fa-regular fa-floppy-disk fa-lg" />
      </button>
    </div>
  </div>
</template>

<style scoped>

</style>
