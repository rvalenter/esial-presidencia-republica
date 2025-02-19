<script setup>
import Notification from "@/Components/Global/Notification.vue";
import {ref} from "vue";
import Spinner from "@/Components/Global/Spinner.vue";

const emits = defineEmits(['close', 'setFilter', 'getNewNotifications']);
const showViewAllNotifications = ref(false);
const menuShow = ref('todas');
const props = defineProps({
    notifications: {
        type: Array,
        required: false,
        default: []
    },
    loading: {
        type: Boolean,
        required: false,
        default: false
    }
});
const setFilter = (filter) => {
    menuShow.value = filter;
    emits('setFilter', filter);
}
</script>

<template>
  <div
    class="absolute"
    @blur="$emit('close')"
  >
    <div class="md:w-96 sm:w-48 relative -left-32 top-5 bg-white rounded-2xl shadow-2xl border border-gray-300">
      <div class="w-full flex justify-between items-center h-14 p-8">
        <div>
          <p class="text-lg font-semibold">
            Notificações
          </p>
        </div>
        <div>
          <button
            class="hover:bg-gray-100 flex justify-center items-center rounded-lg h-7 w-7"
            @click="$emit('close')"
          >
            <i class="fa-solid fa-xmark fa-lg" />
          </button>
        </div>
      </div>
      <div class="w-full border-b border-gray-300 grid grid-cols-2">
        <button
          class="flex justify-center gap-4 hover:text-black text-gray-400  text-center py-1.5"
          :class="[menuShow === 'todas' ? 'border-black text-black border-b-4' : '']"
          @click="setFilter('todas')"
        >
          <span class="text-sm font-semibold">Todas</span>
          <i class="fa-solid fa-inbox" />
        </button>
        <button
          class="flex justify-center gap-4 hover:text-black text-gray-400 py-1.5"
          :class="[menuShow === 'arquivadas' ? 'border-black text-black border-b-4' : '']"
          @click="setFilter('arquivadas')"
        >
          <span class="text-sm font-semibold">Arquivadas</span>
          <i class="fa-solid fa-box-open" />
        </button>
      </div>
      <div
        v-if="props.notifications.length > 0"
        class="grid grid-cols-1 max-h-96 overflow-y-auto divide-y"
      >
        <Notification
          v-for="notification in props.notifications"
          :key="notification.id"
          :notification="notification"
          @get-new-notifications="$emit('getNewNotifications')"
        />
      </div>
      <div
        v-if="props.notifications.length === 0"
        class="grid grid-cols-1 pt-4 max-h-96 overflow-y-auto divide-y"
      >
        <div class="py-4 w-full grid justify-items-center">
          <div class="">
            <img
              src="../../../../public/src/img/NoNotice.png"
              class="w-24"
              alt=""
            >
          </div>
          <div class="mt-4">
            <p class="font-bold">
              Nada por aqui...
            </p>
          </div>
        </div>
      </div>
      <div
        v-if="showViewAllNotifications"
        class="flex justify-between items-center"
      >
        <button class="w-full h-8 bg-gray-100 hover:bg-gray-50 hover:text-indigo-500 rounded-b-2xl">
          <p class="text-xs font-medium">
            Ver todas as notificações
          </p>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
