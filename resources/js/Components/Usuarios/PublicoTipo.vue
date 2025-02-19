<script setup>
import {ref, watch} from "vue";
import get_user from "@/Services/User/get_user.js";
import post_user from "@/Services/User/post_user.js";

const props = defineProps({
    user: {
        type: Number,
        required: true,
    }
});
const emits = defineEmits([
    "accessGroup"
]);

const accessGroups = ref({});
const userAccessGroup = ref(null);
const btnShow = ref(false);
const updateAccessGroup = () => {
    fetchAccessGroup(props.user);
}
defineExpose({
    updateAccessGroup
});

function fetchAccessGroup(id) {
    get_user.fetchGroupAccessUser(id).then(res => {
        accessGroups.value = res.data.data.groups;
        userAccessGroup.value = res.data.data.userGroups !== null ? res.data.data.userGroups.esial_grupo_acesso_id : null;
    });
}

function storeGroupToUser() {
    post_user.storeGroupToUser({
        user: props.user,
        group: userAccessGroup.value
    }).then(() => {
        btnShow.value = false;
        fetchAccessGroup(props.user);
        emits('accessGroup');
    });
}

watch(() => props.user, () => {
    fetchAccessGroup(props.user)
});
</script>

<template>
  <div class="col-span-12 grid grid-cols-12 gap-4 mt-6 content-center">
    <div class="w-full gap-4 flex justify-start items-end col-span-4">
      <label
        class="font-medium text-gray-600"
        for="publico"
      >Predefinições:</label>
      <select
        id="publico"
        v-model="userAccessGroup"
        name="publico"
        class="relative left-2 font-medium border-b-2 focus:bg-gray-50 bg-white border-gray-400 focus:border-blue-600 w-full border-t-0 border-l-0 border-r-0 focus:border-t-0 focus:border-l-0 focus:border-r-0 focus:ring-0"
        @change="btnShow = true"
      >
        <option
          value="null"
          disabled
        >
          Personalizado
        </option>
        <option
          v-for="accessGroup in accessGroups"
          :key="accessGroup.id"
          :value="accessGroup.id"
        >
          {{ accessGroup.nome }}
        </option>
      </select>
    </div>
    <div class="col-span-1">
      <button
        v-if="btnShow"
        class="h-10 w-10 text-white rounded shadow-md bg-blue-500 hover:bg-sky-500"
        @click="storeGroupToUser"
      >
        <i class="fa-regular fa-floppy-disk" />
      </button>
    </div>
  </div>
</template>

<style scoped>

</style>
