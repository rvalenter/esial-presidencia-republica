<script setup>
import {onMounted, ref, watch} from "vue";
import get_contact from "@/Services/Contact/get_contact.js";
import Relacionamento from "@/Components/Contatos/Relacionamento.vue";

const props = defineProps(['id']);
const relationships = ref([]);

function getRelationships() {
  get_contact.fetchRelationships(props.id).then(res => {
    relationships.value = res.data.data;
  });
}

watch(() => props.id, () => {
  getRelationships();
});

onMounted(() => {
  if (props.id) {
    getRelationships();
  }
});
</script>

<template>
  <div class="w-full h-screen -mt-4">
    <div class="rounded-3xl bg-white h-5/6 overflow-y-auto shadow-3xl">
      <div
        v-if="props.id"
        class="px-12 py-10"
      >
        <div class="flex justify-center items-center mb-16 mt-10">
          <Relacionamento
            :contato="relationships"
            :default-contact="true"
          />
        </div>
        <div
          v-if="relationships.get_relationships"
          class="w-full grid grid-cols-3 gap-6 rounded-xl p-2"
        >
          <div
            v-for="contact in relationships.get_relationships"
            :key="contact.id"
          >
            <Relacionamento :contato="contact" />
          </div>
        </div>
      </div>
      <div
        v-if="!props.id"
        class="w-full h-full flex justify-center items-center"
      >
        <div>
          <p class="text-2xl font-black text-sky-700">
            Escolha um contato.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
