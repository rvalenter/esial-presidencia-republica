<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  tab: {
    type: Number,
    default: 3
  },
  id: {
    type: Number,
    default: null
  },
  relatedUsers: {
    type: Boolean,
    default: false
  }
})
const profileUrl = ref('')
const showUrl = ref(false)

function copyToTransferArea(text) {
  navigator.clipboard.writeText(text)
  showUrl.value = false
}

watch(
  () => props.id,
  () => {
    profileUrl.value = 'https://' + window.location.host + '/perfil/' + props.id
  }
)
</script>

<template>
  <div class="mt-14 flex justify-between items-center gap-4 border-b-2 border-gray-300">
    <div class="flex justify-start items-center gap-4 z-40">
      <div
        class="tabs"
        :class="[props.tab === 3 ? 'tab-active' : 'border-transparent']"
        @click="$emit('changeTab', 3)"
      >
        <p>Dados Pessoais</p>
      </div>
      <div
        class="tabs"
        :class="[props.tab === 5 ? 'tab-active' : 'border-transparent']"
        @click="$emit('changeTab', 5)"
      >
        <p>Login</p>
      </div>
      <div
        class="tabs"
        :class="[props.tab === 2 ? 'tab-active' : 'border-transparent']"
        @click="$emit('changeTab', 2)"
      >
        <p>Permissões/ Acessos</p>
      </div>
      <div
        v-if="relatedUsers"
        class="tabs"
        :class="[props.tab === 4 ? 'tab-active' : 'border-transparent']"
        @click="$emit('changeTab', 4)"
      >
        <p>Usuários Cadastrados</p>
      </div>
    </div>
    <div class="flex gap-4 z-40">
      <div>
        <button
          class="bg-blue-500 text-white h-8 w-8 rounded-full shadow-md hover:bg-blue-600"
          @click="showUrl = !showUrl"
        >
          <i class="fa-solid fa-share-nodes" />
        </button>
        <Transition>
          <div v-if="showUrl" class="absolute right-5 mt-2 shadow-2xl p-4 bg-white rounded-lg">
            <div>
              <p>Link:</p>
              <div>
                <input type="text" class="w-64 rounded-lg mr-4" :value="profileUrl" readonly />
                <button @click="copyToTransferArea(profileUrl)">
                  <i class="fa-regular fa-copy fa-lg" />
                </button>
              </div>
            </div>
          </div>
        </Transition>
      </div>
    </div>
  </div>
</template>

<style scoped>
.tabs {
  @apply h-14 min-w-56 flex justify-center items-center gap-4 hover:border-blue-600  cursor-pointer hover:text-gray-900 hover:font-bold;
}

.tab-active {
  @apply border-blue-700 border-b-4 text-blue-600 font-bold;
}
</style>
