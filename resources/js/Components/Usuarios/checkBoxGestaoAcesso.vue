<script setup>
import {ref} from "vue";
import post from "@/Services/User/post_user.js";


const props = defineProps({
    name: {
        type: String,
        required: true
    },
    checked: {
        type: Boolean,
        required: true
    },
    group: {
        type: String,
        required: true
    },
    id: {
        type: Number,
        required: true
    }
});
const access = ref(props.checked);

function definePermissao() {
    if (access.value) {
        post.storeAutorizationFromGroup({
            group: props.group,
            id: props.id
        });
        return;
    }

    post.destroyAutorizationFromGroup({
        group: props.group,
        id: props.id
    });
}
</script>

<template>
  <div class="flex">
    <input
      :id="`check_${props.name}`"
      v-model="access"
      type="checkbox"
      class="w-5 h-5 shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
      @change="definePermissao()"
    >
    <label
      :for="`check_${props.name}`"
      class="text-base text-gray-700 ms-5"
    >
      {{ props.name }}
    </label>
  </div>
</template>

<style scoped>

</style>
