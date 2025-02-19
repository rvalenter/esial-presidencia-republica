<script setup>
import Notifications from "@/Layouts/Global/Notifications.vue";
import {onMounted, ref, watch} from "vue";
import get_user from "@/Services/User/get_user.js";

const notificationsCollapsed = ref(false);
const hasNotification = ref(false);
const myNotifications = ref([]);
const filterArg = ref('todas');
const loading = ref(true);
const checkNotifications = () => {
    get_user.hasNotifications().then(response => {
        hasNotification.value = response.data.data.status;

        if (notificationsCollapsed.value && hasNotification.value) {
            getNotifications();
        }
    });
}
const getNotifications = (filter) => {
    if (filter) {
        filterArg.value = filter;
    }

    if (filterArg.value !== 'todas') {
        loading.value = true;
    }

    get_user.fetchNotifications(filterArg.value).then(response => {
        myNotifications.value = response.data.data;
    }).then(() => {
        loading.value = false;
    });
}

watch(notificationsCollapsed, (value) => {
    if (value && hasNotification.value) {
        getNotifications();
    }
});

onMounted(() => {
    checkNotifications();
    setInterval(checkNotifications, 2 * 60 * 1000);
});
</script>

<template>
  <div v-if="!$page.props.auth.isMobile">
    <button
      class="rounded-full bg-gray-200 hover:bg-gray-100 hover:text-indigo-500 h-8 w-8 flex justify-center items-center"
      title="Notificações"
      @click="notificationsCollapsed = !notificationsCollapsed; getNotifications(); checkNotifications()"
    >
      <i class="fa-regular fa-bell" />
      <span
        v-if="hasNotification"
        class="relative flex h-2 w-2 -top-[2px] left-[1px] -ml-2"
      >
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75" />
        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500" />
      </span>
    </button>
    <Notifications
      v-if="notificationsCollapsed"
      :loading="loading"
      :notifications="myNotifications"
      @set-filter="getNotifications"
      @get-new-notifications="getNotifications(); checkNotifications()"
      @close="notificationsCollapsed = false"
    />
  </div>
</template>

<style scoped>

</style>
