<script setup>
import get_user from "@/Services/User/get_user.js";
import {onMounted, ref} from "vue";

const props = defineProps({
    id: {
      type: Number,
      required: true
    },
});
const access = ref([]);

const fetchAccess = () => {
    get_user.fetchMyGroupAccess(props.id).then(response => {
        access.value = response.data.data;
    });
}

onMounted(() => {
    fetchAccess();
});
</script>

<template>
  <div class="col-span-2 p-3">
    <div class="mt-10">
      <div class="grid 2xl:grid-cols-4 md:grid-cols-3 gap-8">
        <div
          v-for="auth in access"
          :key="auth.acesso_id"
          class="border-l-4 border-blue-800 pl-2 flex"
        >
          <p>{{ auth.acesso_nome }}</p>
          <button
            :title="auth.acesso_tipo"
            class="relative -top-3 left-3 text-blue-500"
          >
            <i class="fa-solid fa-circle-info fa-sm" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
