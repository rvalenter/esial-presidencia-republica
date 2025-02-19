<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import Menu from '@/Layouts/Global/Menu.vue'
import Banner from '@/Layouts/Global/Banner.vue'
import ScreenRotate from '@/Components/Global/ScreenRotate.vue'
import BackendResponseMesssage from '@/Components/Global/BackendResponseMesssage.vue'

const menuCollapsed = ref(true)
const orientation = ref(false)

const checkOrientation = () => {
  if (window.matchMedia('(orientation: portrait)').matches) {
    orientation.value = false
  } else {
    orientation.value = true
  }
}

onMounted(() => {
  checkOrientation()

  window.addEventListener('orientationchange', checkOrientation)
  window.addEventListener('resize', checkOrientation)
})

onUnmounted(() => {
  window.removeEventListener('orientationchange', checkOrientation)
  window.removeEventListener('resize', checkOrientation)
})
</script>

<template>
  <Head>
    <title>Sistema Eletrônico de Acompanhamento Legislativo</title>
    <meta
      content="Raphael Reges Valente"
      name="author"
    >
  </Head>
  <ScreenRotate v-if="$page.props.auth.isMobile && !orientation" />
  <backend-response-messsage />
  <main class="w-full container h-full">
    <Banner @collapse="menuCollapsed = !menuCollapsed" />
    <Menu :collapsed="menuCollapsed" />
    <div
      :class="[menuCollapsed ? 'pl-72' : 'pl-24']"
      class="w-full absolute"
    >
      <div class="bg-gray-200 h-screen w-full overflow-hidden px-4 pt-20">
        <div class="h-full">
          <slot />
        </div>
      </div>
    </div>
  </main>
</template>

<style>
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
  -webkit-overflow-scrolling: touch;
}

::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.5);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background-color: rgba(0, 0, 0, 0.7);
}

::-webkit-scrollbar-track {
  background: transparent;
}

* {
  -webkit-overflow-scrolling: touch;
  scrollbar-width: thin;
  scrollbar-color: rgba(0, 0, 0, 0.1) transparent;
}

*::-moz-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.5);
  border-radius: 10px;
}

*::-moz-scrollbar-thumb:hover {
  background-color: rgba(0, 0, 0, 0.7);
}
</style>
