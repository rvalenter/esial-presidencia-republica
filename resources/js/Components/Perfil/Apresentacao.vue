<script setup>
import TimelineItem from '@/Components/Perfil/TimelineItem.vue'
import get from '@/Services/User/get_user.js'
import post from '@/Services/User/post_user.js'
import { onMounted, ref } from 'vue'
import success from '@/Hooks/success.js'

const description = ref('')
const edit = ref(false)
const props = defineProps({
  id: {
    type: Number,
    required: true
  }
})

function getApresentacao() {
  get.fetchDescriptions(props.id).then((response) => {
    description.value = response.data.data.descricao
  })
}

function storeApresentacao() {
  post
    .storeApresentacao({
      id: props.id,
      descricao: description.value
    })
    .then(() => {
      edit.value = false
      success.cofetti()
    })
}

onMounted(() => {
  getApresentacao()
})
</script>

<template>
  <div class="px-2 col-span-2 -mt-96 z-0">
    <div class="h-screen -mt-24 pt-96 grid grid-cols-2 gap-4">
      <div class="relative top-28 h-5/6 overflow-y-auto">
        <div>Você esta no topo</div>
      </div>
      <div class="relative top-28 h-5/6 overflow-y-auto bg-white rounded-xl shadow-xl">
        <div>
          <TimelineItem />
          <TimelineItem />
          <TimelineItem />
          <TimelineItem />
          <TimelineItem />
          <TimelineItem />
          <TimelineItem />
          <TimelineItem />
          <TimelineItem />
          <TimelineItem />
          <TimelineItem />
          <TimelineItem />
          <TimelineItem />
          <TimelineItem />
          <TimelineItem />
          <TimelineItem />
          <TimelineItem />
          <TimelineItem />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
