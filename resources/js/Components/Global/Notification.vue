<script setup>
import {Link} from "@inertiajs/vue3";
import post_user from "@/Services/User/post_user.js";
import DOMPurify from 'dompurify';

const props = defineProps({
    notification: {
        type: Object,
        required: true
    }
});
const emits = defineEmits(['getNewNotifications']);

const archiveNotification = (id) => {
    post_user.archiveNotification(id).then(() => {
        emits('getNewNotifications');
    });
}
</script>

<template>
  <div
    class="w-full min-h-20 pr-2 pl-6 pt-3 pb-10"
    :class="[props.notification.prioritaria ? 'bg-orange-100/70 hover:bg-orange-100' : 'hover:bg-gray-100']"
  >
    <div class="flex justify-between items-center">
      <div class="flex gap-2 items-center">
        <div
          class="bg-[url('../../public/src/img/bg-avatar.jpg')] image-container rounded-full  h-10 w-10 bg-cover bg-center bg-no-repeat flex items-center justify-center"
        >
          <img
            :src="'data:image/png;base64,' + props.notification.responsavel_foto"
            class="absolute inset-0 z-10"
          >
        </div>
        <Link
          :href="'/perfil/' + props.notification.responsavel_id"
          replace
        >
          <p class="text-sm font-semibold">
            {{ props.notification.responsavel }}
          </p>
        </Link>
      </div>
      <div>
        <p class="text-xs text-gray-400 font-semibold">
          {{ props.notification.created_at }}
        </p>
      </div>
    </div>
    <div class="mt-3">
      <p
        class="text-sm text-gray-500"
        v-html="DOMPurify.sanitize(props.notification.notificacao)"
      />
    </div>
    <div
      v-if="!props.notification.lida"
      class="w-full flex justify-end items-end"
    >
      <button
        class="flex gap-2 relative top-6 text-gray-400 hover:text-black"
        title="Arquivar mensagem"
        @click="archiveNotification(props.notification.id)"
      >
        <i class="fa-solid fa-envelope-open-text" />
      </button>
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
</style>
