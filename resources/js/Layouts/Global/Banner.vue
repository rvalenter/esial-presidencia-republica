<script setup>
import Notificacao from "@/Components/Banner/Notificacao.vue";
import Gestao from "@/Components/Banner/Gestao.vue";
import Logout from "@/Components/Banner/Logout.vue";
import Profile from "@/Components/Banner/Profile.vue";
import LogoApp from "@/Components/Global/LogoApp.vue";
import Collapse from "@/Components/Banner/Collapse.vue";

const emits = defineEmits([
  'collapse'
]);

</script>

<template>
  <div class="z-40 h-24 w-screen flex justify-between items-center fixed pr-4 pl-4">
    <div
      :class="[$page.props.auth.isMobile ? 'grid-cols-6' : 'grid-cols-12']"
      class="grid  bg-white rounded-xl border border-gray-300 w-full bg-[url('../../public/src/img/banner-bg.png')] bg-cover bg-bottom bg-no-repeat p-2"
    >
      <div class="col-span-2 flex justify-between items-center">
        <div class="flex w-full pl-1">
          <button>
            <LogoApp
              height="h-10"
              width="w-16"
              :bg="false"
              :only-img="true"
            />
          </button>
          <div class="w-10/12 ml-2 -mt-1">
            <p class="font-semibold text-white -mb-1">
              e-Sial
            </p>
            <p class="text-[0.5rem] font-semibold text-white -mb-0.5">
              SEPAR/SRI/PR
            </p>
            <p class="text-[0.5rem] font-light text-white">
              {{ $page.props.auth.organization }}
            </p>
          </div>
        </div>
        <div>
          <collapse @collapse="emits('collapse')" />
        </div>
      </div>
      <div
        class="col-span-10 flex justify-between items-center"
        :class="$page.props.auth.isMobile ? 'col-span-4' : 'col-span-10'"
      >
        <div class="flex justify-end w-full gap-5 pr-5">
          <button
            v-if="$page.props.auth.access.includes(17)"
            class="w-8"
          >
            <i class="fa-solid fa-sliders" />
          </button>
          <Notificacao />
          <Gestao v-if="$page.props.auth.access.includes(2)" />
          <Logout />
        </div>
        <div class="border-l-2 border-gray-300/70 flex justify-end items-center pl-2">
          <profile />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
