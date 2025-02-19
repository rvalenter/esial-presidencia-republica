<script setup>
import {Link} from "@inertiajs/vue3";
import get from "@/Services/User/get_user.js";
import {onMounted, ref} from "vue";
import parses from "@/Hooks/parses.js";

const user = ref({
    user: {
        name: null,
    },
    photo: null,
});

function firstName(name) {
    return name.split(' ')[0];
}

onMounted(() => {
    get.user().then(res => {
        user.value = res.data.data;
    });
});
</script>

<template>
  <Link
    :href="!$page.props.auth.isMobile ? route('usuario.perfil') : ''"
    replace
    title="Acessar seu perfil de usuário"
    class="flex justify-between items-center w-auto gap-5 pr-5"
  >
    <div>
      <p class="text-[0.7rem] font-bold">
        {{ firstName(parses.name(user.user.name)) }}
      </p>
      <p class="text-[0.7rem]">
        {{ $page.props.auth.profile }}
      </p>
    </div>
    <div class="image-container rounded-full bg-gray-200 hover:bg-gray-100 hover:text-indigo-500 h-10 w-10 flex justify-center items-center ring-2 ring-blue-500">
      <i
        v-if="!user.photo"
        class="fa-regular fa-user"
      />
      <img
        v-if="user.photo"
        :src="'data:image/png;base64,' + user.photo"
      >
    </div>
  </Link>
</template>

<style scoped>
.image-container {
    overflow: hidden;
    position: relative;
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover !important;
}
</style>
